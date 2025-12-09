<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'images',
        'category',       // 👈 slug string
        'featured',
        'quantity',
        'average_rating',
    ];

    protected $casts = [
    'images' => 'array',
    'price' => 'decimal:2',
    'quantity' => 'integer',
    'average_rating' => 'decimal:1',
];

    // 👇 RENAME THIS to avoid conflict with the 'category' column
    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category', 'slug');
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')
                    ->withTimestamps();
    }

    public function inCarts()
    {
        return $this->belongsToMany(User::class, 'cart_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}