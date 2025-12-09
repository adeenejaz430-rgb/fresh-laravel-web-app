<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
    ];

    // Relationship: If you want each product to belong to a category
    // (optional, depends on your Product model design)
    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'slug');
        // You were storing category as string slug in Product model
    }
}
