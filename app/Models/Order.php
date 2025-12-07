<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Allowed Fillable Fields
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'address',
        'city',
        'postal_code',
        'status',
        'total_amount',
        'currency',
        'payment_method',
        'payment_status',
        'payment_reference',
    ];

    /*
    |--------------------------------------------------------------------------
    | Order Status Flow
    |--------------------------------------------------------------------------
    */
    const STATUSES = [
        'pending',
        'paid',
        'processing',
        'completed',
        'cancelled',
    ];

    /*
    |--------------------------------------------------------------------------
    | Resolve Orders By PayPal Payment Reference
    |--------------------------------------------------------------------------
    */
    public function getRouteKeyName()
    {
        return 'payment_reference';
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // User who placed the order
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Order items belonging to this order
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers (Optional but useful)
    |--------------------------------------------------------------------------
    */

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
