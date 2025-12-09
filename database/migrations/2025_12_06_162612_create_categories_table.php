<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // name: String, required
            $table->string('name');

            // slug: String, required, unique, lowercase
            $table->string('slug')->unique();

            // image: String
            $table->string('image')->nullable();

            $table->timestamps(); // same as { timestamps: true }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
