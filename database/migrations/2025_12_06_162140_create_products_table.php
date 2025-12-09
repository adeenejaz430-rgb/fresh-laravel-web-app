<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // name: String, required, trim
            $table->string('name');

            // slug: String, required, unique, lowercase
            $table->string('slug')->unique();

            // description: String, required
            $table->text('description');

            // price: Number, required
            $table->decimal('price', 10, 2);

            // images: [String] -> JSON array
            $table->json('images')->nullable();

            // category: String, required
            $table->string('category');

            // featured: Boolean, default false
            $table->boolean('featured')->default(false);

            // quantity: Number, default 0
            $table->unsignedInteger('quantity')->default(0);

            // averageRating: Number, default 0
            $table->decimal('average_rating', 3, 2)->default(0);

            $table->timestamps(); // timestamps: true
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
