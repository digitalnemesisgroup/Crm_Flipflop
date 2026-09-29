<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'marketplace_order_id',
        'marketplace_product_id',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(MarketplaceOrder::class, 'marketplace_order_id');
    }

    public function product()
    {
        return $this->belongsTo(MarketplaceProduct::class, 'marketplace_product_id');
    }
}
