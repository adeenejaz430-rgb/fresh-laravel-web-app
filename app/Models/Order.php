<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'order_number',
        'shipping_address',
        'payment_method',
        'payment_result',
        'payment_intent_id',
        'items_price',
        'tax_price',
        'shipping_price',
        'total_price',
        'status',
        'is_paid',
        'paid_at',
        'is_delivered',
        'delivered_at',
        'shipping_method',
        'tracking_number',
        'notes',
        'refunded_at',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'payment_result'   => 'array',
        'is_paid'          => 'boolean',
        'is_delivered'     => 'boolean',

        'paid_at'          => 'datetime',
        'delivered_at'     => 'datetime',
        'refunded_at'      => 'datetime',
    ];

    // -------------------------------
    // Relationships
    // -------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // -------------------------------
    // Accessors (Virtual fields)
    // -------------------------------

    public function getOrderAgeAttribute()
    {
        return $this->created_at
            ? $this->created_at->diffInDays(Carbon::now())
            : null;
    }

    // -------------------------------
    // Business Logic (Instance Methods)
    // -------------------------------

    public function markAsPaid()
    {
        $this->is_paid = true;
        $this->paid_at = now();
        $this->status = 'paid';
        $this->save();
    }

    public function markAsDelivered()
    {
        $this->is_delivered = true;
        $this->delivered_at = now();
        $this->status = 'delivered';
        $this->save();
    }

    // -------------------------------
    // Static Methods → Local Scopes
    // -------------------------------

    public function scopeOfUser($query, $userId)
    {
        return $query->where('user_id', $userId)
                     ->orderByDesc('created_at');
    }

    public function scopeOfEmail($query, $email)
    {
        return $query->where('email', strtolower($email))
                     ->orderByDesc('created_at');
    }

    // -------------------------------
    // Equivalent to Mongoose pre('save')
    // -------------------------------

    public static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (!$order->order_number) {
                $count = Order::count() + 1;
                $order->order_number = 'ORD-' . str_pad($count, 6, '0', STR_PAD_LEFT);
            }
        });
    }
}
