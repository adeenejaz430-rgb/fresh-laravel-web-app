<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // user: optional (guest checkout)
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->string('email')->index();

            $table->string('order_number')->unique();

            // Embedded shippingAddress
            $table->json('shipping_address');

            // enum: card, paypal, crypto
            $table->enum('payment_method', ['card', 'paypal', 'crypto'])
                  ->default('card');

            // embedded paymentResult
            $table->json('payment_result')->nullable();

            $table->string('payment_intent_id')->unique();

            $table->decimal('items_price', 10, 2)->default(0);
            $table->decimal('tax_price', 10, 2)->default(0);
            $table->decimal('shipping_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);

            $table->enum('status', [
                'pending', 'paid', 'processing', 'shipped',
                'delivered', 'cancelled', 'failed', 'refunded'
            ])->default('pending');

            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();

            $table->boolean('is_delivered')->default(false);
            $table->timestamp('delivered_at')->nullable();

            $table->enum('shipping_method', ['standard', 'express'])
                  ->default('standard');

            $table->string('tracking_number')->nullable();

            $table->text('notes')->nullable();

            $table->timestamp('refunded_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
