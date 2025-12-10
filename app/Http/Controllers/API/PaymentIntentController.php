<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\SignatureVerificationException;

class PaymentIntentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for checkout
     */
    public function create(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|integer',
                'items.*.name' => 'required|string',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.quantity' => 'required|integer|min:1',
                'customer.name' => 'required|string|max:255',
                'customer.email' => 'required|email|max:255',
                'customer.address.line1' => 'required|string|max:255',
                'customer.address.city' => 'required|string|max:100',
                'customer.address.state' => 'required|string|max:100',
                'customer.address.postal_code' => 'required|string|max:20',
                'customer.address.country' => 'required|string|size:2',
                'user_id' => 'nullable|integer|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => 'Validation failed',
                    'details' => $validator->errors()
                ], 422);
            }

            $items = $request->input('items');
            $customer = $request->input('customer');
            $userId = $request->input('user_id');

            // Validate products exist and prices match (CRITICAL for production)
            $productIds = array_column($items, 'id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $validatedItems = [];
            $subtotalCents = 0;

            foreach ($items as $item) {
                $product = $products->get($item['id']);
                
                if (!$product) {
                    return response()->json([
                        'error' => "Product with ID {$item['id']} not found"
                    ], 404);
                }

                // CRITICAL: Use server-side price, not client price
                $serverPrice = $product->price;
                $quantity = intval($item['quantity']);

                if ($quantity <= 0) {
                    return response()->json([
                        'error' => "Invalid quantity for product: {$product->name}"
                    ], 400);
                }

                // Check stock availability
                if (isset($product->stock) && $product->stock < $quantity) {
                    return response()->json([
                        'error' => "Insufficient stock for product: {$product->name}. Available: {$product->stock}"
                    ], 400);
                }

                $priceCents = $this->toCents($serverPrice);
                $subtotalCents += $priceCents * $quantity;

                $validatedItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'qty' => $quantity,
                    'price' => $serverPrice,
                    'price_cents' => $priceCents,
                    'image' => $product->image ?? ($product->images[0] ?? null),
                ];
            }

            if ($subtotalCents <= 0) {
                return response()->json(['error' => 'Invalid computed amount'], 400);
            }

            // Minimum charge amount for Stripe (50 cents)
            if ($subtotalCents < 50) {
                return response()->json([
                    'error' => 'Order total must be at least $0.50'
                ], 400);
            }

            // Create Stripe Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $subtotalCents,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'description' => 'Order for ' . count($items) . ' item(s)',
                'receipt_email' => $customer['email'],
                'metadata' => [
                    'user_id' => $userId ?? 'guest',
                    'customer_name' => $customer['name'],
                    'items' => json_encode($validatedItems),
                    'subtotal_cents' => (string)$subtotalCents,
                    'total_cents' => (string)$subtotalCents,
                    'environment' => config('app.env'),
                ],
                'shipping' => [
                    'name' => $customer['name'],
                    'address' => [
                        'line1' => $customer['address']['line1'],
                        'city' => $customer['address']['city'],
                        'state' => $customer['address']['state'],
                        'postal_code' => $customer['address']['postal_code'],
                        'country' => $customer['address']['country'],
                    ],
                ],
            ]);

            Log::info('Payment intent created', [
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $subtotalCents,
                'email' => $customer['email'],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id,
                'totals' => [
                    'subtotal_cents' => $subtotalCents,
                    'total_cents' => $subtotalCents,
                ],
            ]);

        } catch (\Stripe\Exception\CardException $e) {
            Log::error('Stripe Card Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Card error: ' . $e->getError()->message,
            ], 402);

        } catch (\Stripe\Exception\RateLimitException $e) {
            Log::error('Stripe Rate Limit Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Too many requests. Please try again later.',
            ], 429);

        } catch (\Stripe\Exception\InvalidRequestException $e) {
            Log::error('Stripe Invalid Request: ' . $e->getMessage());
            return response()->json([
                'error' => 'Invalid payment request.',
            ], 400);

        } catch (\Stripe\Exception\AuthenticationException $e) {
            Log::error('Stripe Authentication Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment service authentication failed.',
            ], 500);

        } catch (\Stripe\Exception\ApiConnectionException $e) {
            Log::error('Stripe Connection Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Network communication error. Please try again.',
            ], 503);

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment processing error.',
            ], 500);

        } catch (\Exception $e) {
            Log::error('Payment Intent Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => config('app.debug') ? $e->getMessage() : 'An unexpected error occurred.',
                'details' => config('app.debug') ? $e->getTraceAsString() : null,
            ], 500);
        }
    }

    /**
     * Webhook handler for Stripe events
     * CRITICAL: This must be called by Stripe, not from frontend
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (!$webhookSecret) {
            Log::error('Stripe webhook secret not configured');
            return response()->json(['error' => 'Webhook not configured'], 500);
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );

            Log::info('Stripe webhook received', [
                'type' => $event->type,
                'id' => $event->id,
            ]);

            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentSuccess($paymentIntent);
                    break;
                
                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentFailure($paymentIntent);
                    break;

                case 'payment_intent.canceled':
                    $paymentIntent = $event->data->object;
                    $this->handlePaymentCanceled($paymentIntent);
                    break;
                
                default:
                    Log::info('Unhandled webhook event: ' . $event->type);
            }

            return response()->json(['status' => 'success']);

        } catch (SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);

        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Handle successful payment - Create order in database
     */
    private function handlePaymentSuccess($paymentIntent)
    {
        try {
            DB::beginTransaction();

            // Check if order already exists (prevent duplicates)
            $existingOrder = Order::where('payment_intent_id', $paymentIntent->id)->first();
            
            if ($existingOrder) {
                Log::info('Order already exists for payment intent: ' . $paymentIntent->id);
                DB::commit();
                return;
            }

            $metadata = $paymentIntent->metadata;
            $items = json_decode($metadata->items, true);
            $shipping = $paymentIntent->shipping;
            $userId = $metadata->user_id !== 'guest' ? $metadata->user_id : null;

            // Calculate totals
            $itemsPrice = $metadata->subtotal_cents / 100;
            $taxPrice = 0; // Add tax calculation if needed
            $shippingPrice = 0; // Add shipping calculation if needed
            $totalPrice = $metadata->total_cents / 100;

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'email' => $paymentIntent->receipt_email,
                // order_number is auto-generated in Model boot method
                'shipping_address' => [
                    'name' => $shipping->name,
                    'address' => $shipping->address->line1,
                    'city' => $shipping->address->city,
                    'state' => $shipping->address->state,
                    'postal_code' => $shipping->address->postal_code,
                    'country' => $shipping->address->country,
                ],
                'payment_method' => 'stripe',
                'payment_result' => [
                    'id' => $paymentIntent->id,
                    'status' => $paymentIntent->status,
                    'amount' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'created' => $paymentIntent->created,
                ],
                'payment_intent_id' => $paymentIntent->id,
                'items_price' => $itemsPrice,
                'tax_price' => $taxPrice,
                'shipping_price' => $shippingPrice,
                'total_price' => $totalPrice,
                'status' => 'paid',
                'is_paid' => true,
                'paid_at' => now(),
                'is_delivered' => false,
                'shipping_method' => 'standard',
            ]);

            // Create order items
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'name' => $item['name'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'image' => $item['image'],
                ]);

                // Update product stock (if you track inventory)
                $product = Product::find($item['id']);
                if ($product && isset($product->stock)) {
                    $product->decrement('stock', $item['qty']);
                }
            }

            DB::commit();

            Log::info('Order created successfully', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'payment_intent_id' => $paymentIntent->id,
                'total' => $totalPrice,
            ]);

            // Send order confirmation email (implement this)
            // event(new OrderCreated($order));

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create order from payment intent', [
                'payment_intent_id' => $paymentIntent->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Optionally notify admin about this critical error
            throw $e;
        }
    }

    /**
     * Handle failed payment
     */
    private function handlePaymentFailure($paymentIntent)
    {
        Log::warning('Payment failed', [
            'payment_intent_id' => $paymentIntent->id,
            'email' => $paymentIntent->receipt_email,
            'amount' => $paymentIntent->amount,
            'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
        ]);

        // Optionally create a failed order record or send notification
    }

    /**
     * Handle canceled payment
     */
    private function handlePaymentCanceled($paymentIntent)
    {
        Log::info('Payment canceled', [
            'payment_intent_id' => $paymentIntent->id,
            'email' => $paymentIntent->receipt_email,
        ]);
    }

    /**
     * Convert dollars to cents
     */
    private function toCents($value)
    {
        return (int) round(floatval($value) * 100);
    }

    /**
     * Get order by payment intent ID (for frontend verification)
     */
    public function getOrderByPaymentIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_intent_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid request',
                'details' => $validator->errors()
            ], 422);
        }

        $order = Order::with('items')
            ->where('payment_intent_id', $request->payment_intent_id)
            ->first();

        if (!$order) {
            return response()->json([
                'error' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'order' => [
                'order_number' => $order->order_number,
                'email' => $order->email,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'is_paid' => $order->is_paid,
                'paid_at' => $order->paid_at,
                'created_at' => $order->created_at,
                'items' => $order->items,
            ]
        ]);
    }
    public function syncAfterSuccess(Request $request)
{
    $request->validate([
        'payment_intent_id' => 'required|string',
    ]);

    try {
        // Get the PaymentIntent from Stripe to verify it really succeeded
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($paymentIntent->status !== 'succeeded') {
            return response()->json([
                'error' => 'Payment not completed yet.',
            ], 400);
        }

        // Avoid duplicate orders if this endpoint is called twice
        $existingOrder = Order::where('payment_intent_id', $paymentIntent->id)->first();
        if ($existingOrder) {
            return response()->json([
                'success' => true,
                'order_number' => $existingOrder->order_number,
            ]);
        }

        // Re-use your existing logic to create the order + items
        $this->handlePaymentSuccess($paymentIntent);

        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();

        return response()->json([
            'success'      => true,
            'order_number' => $order?->order_number,
        ]);

    } catch (\Exception $e) {
        Log::error('syncAfterSuccess error: '.$e->getMessage(), [
            'payment_intent_id' => $request->payment_intent_id,
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'error' => 'Failed to sync order.',
        ], 500);
    }
}

}