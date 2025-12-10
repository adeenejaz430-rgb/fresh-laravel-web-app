<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Roles equivalent to enum: ['user', 'admin']
    public const ROLE_USER  = 'user';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'role',
        'street',
        'city',
        'state',
        'zip',
        'country',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Wishlist: array of Product IDs in Mongo
     * -> many-to-many in SQL via `wishlists` table
     */
   public function wishlistProducts()
{
    return $this->belongsToMany(Product::class, 'wishlists', 'user_id', 'product_id')
                ->withTimestamps();
}

    /**
     * Cart: array of { product, quantity } in Mongo
     * -> many-to-many with pivot data `quantity`
     *    in `cart_items` table
     */
    public function cartProducts()
    {
        return $this->belongsToMany(Product::class, 'cart_items')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Orders: one-to-many relationship
     * A user can have many orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
