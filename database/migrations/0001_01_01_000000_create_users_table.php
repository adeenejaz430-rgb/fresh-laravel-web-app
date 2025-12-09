<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // Mongoose: name: { type: String, required: true }
            $table->string('name');

            // Mongoose: email: { type: String, required: true, unique: true }
            $table->string('email')->unique();

            // Mongoose: password: { type: String }
            $table->string('password')->nullable();

            // Mongoose: image: { type: String }
            $table->string('image')->nullable();

            // Mongoose: role: { type: String, enum: ['user', 'admin'], default: 'user' }
            $table->string('role')->default(User::ROLE_USER);

            // Embedded AddressSchema (single address object on user)
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();

            // Standard Laravel fields
            $table->rememberToken();
            $table->timestamps(); // same as { timestamps: true } in Mongoose
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
