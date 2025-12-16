<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemSize extends Model
{
    //
    protected $table = 'order_item_sizes';

    protected $fillable = [
        'order_item_id',
        'size_id',
        'quantity',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }
}
