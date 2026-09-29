<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_order_id',
        'amount',
        'status',
        'transaction_id',
    ];

    public function items()
    {
        return $this->hasMany(MarketplaceOrderItem::class, 'marketplace_order_id');
    }
}
