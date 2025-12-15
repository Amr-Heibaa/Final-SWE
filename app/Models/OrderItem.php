<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

 /**
  * @property int $id
  * @property int $order_id
  * @property string $name
  * @property string|null $fabric_name
  * @property bool $has_printing
  * @property string|null $description
  * @property float $single_price
  */
class OrderItem extends Model
{





            protected $table='order_items';

      protected $fillable = [
        'order_id',
        'name',
        'fabric_name',
        'has_printing',
        'description',

        'single_price',


    ];

    /**
     * Cast values to correct data types
     */
    protected $casts = [
        'has_printing' => 'boolean',
        'single_price' => 'integer',
    ];


        protected $guarded=['id'];

          public function order() { return $this->belongsTo(Order::class); }


          public function itemSizes()
{
    return $this->hasMany(OrderItemSize::class);
}

  public function sizes()
    {
        return $this->belongsToMany(Size::class, 'order_item_sizes')
            ->withPivot('quantity')
            ->withTimestamps();
    }

}
