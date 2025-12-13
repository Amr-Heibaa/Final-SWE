<?php

namespace App\Models;

use App\Enums\SizeEnum;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    
    protected $fillable=[
        'name',
        'sort_order'
    ];
       protected function casts(): array
    {
        return [
            'name' => SizeEnum::class,
        ];
    }

      public function orderItems()
    {
        return $this->belongsToMany(OrderItem::class, 'order_item_sizes')
            ->withPivot('quantity')
            ->withTimestamps();
    }

        public function itemSizes()
    {
        return $this->hasMany(OrderItemSize::class);
    }
}
