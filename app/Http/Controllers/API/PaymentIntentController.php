<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class PaymentIntentController extends Controller
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret'));
    }

    protected function toCents($v): int
    {
        return (int) round(((float) $v) * 100);
    }

    // POST /api/create-payment-intent
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'items'            => 'required|array|min:1',
                'items.*.price'    => 'nullable|numeric|min:0',
                'items.*.price_cents' => 'nullable|integer|min:0',
                'items.*.quantity' => 'nullable|integer|min:1',
                'items.*.id'       => 'nullable',
                'items.*.name'     => 'nullable|string',
                'customer'         => 'nullable|array',
                'customer.email'   => 'nullable|email',
                'customer.name'    => 'nullable|string',
                'customer.address' => 'nullable|array',
            ]);

            $items = $data['items'];
            $customer = $data['customer'] ?? null;

            $subtotalCents = 0;

            foreach ($items as $item) {
                $priceCents = $item['price_cents'] ?? $this->toCents($item['price'] ?? 0);
                $qty        = $item['quantity'] ?? 1;
                $subtotalCents += $priceCents * $qty;
            }

            if ($subtotalCents <= 0) {
                return response()->json(['error' => 'Invalid computed amount'], 400);
            }

            $paymentIntent = $this->stripe->paymentIntents->create([
                'amount'   => $subtotalCents,
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'description' => 'Order for ' . count($items) . ' item(s)',
                'receipt_email' => $customer['email'] ?? null,
                'metadata' => [
                    'items' => json_encode(array_map(function ($it) {
                        return [
                            'id'          => $it['id'] ?? null,
                            'name'        => $it['name'] ?? null,
                            'qty'         => $it['quantity'] ?? 1,
                            'price_cents' => $it['price_cents'] ?? $this->toCents($it['price'] ?? 0),
                        ];
                    }, $items)),
                    'subtotal_cents' => (string) $subtotalCents,
                    'total_cents'    => (string) $subtotalCents,
                ],
                'shipping' => $customer ? [
                    'name'    => $customer['name'] ?? null,
                    'address' => [
                        'line1'       => $customer['address']['line1']   ?? null,
                        'city'        => $customer['address']['city']    ?? null,
                        'state'       => $customer['address']['state']   ?? null,
                        'postal_code' => $customer['address']['postal_code'] ?? null,
                        'country'     => $customer['address']['country'] ?? 'US',
                    ],
                ] : null,
            ]);

            return response()->json([
                'clientSecret'     => $paymentIntent->client_secret,
                'paymentIntentId'  => $paymentIntent->id,
                'totals' => [
                    'subtotal_cents' => $subtotalCents,
                    'total_cents'    => $subtotalCents,
                ],
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json([
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
