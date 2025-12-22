<?php

namespace App\Models;

use App\Enums\OrderPhaseEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $customer_id
 * @property string $customer_name
 * @property string|null $brand_name
 * @property int|null $meeting_id
 * @property float $total_price
 * @property string $current_phase
 * @property bool $requires_printing
 */
class Order extends Model
{
    //
    protected $table = 'orders';

    use HasUuids;
    protected $primaryKey = 'id';
    protected $keytype = 'string';
    public $incrementing = false;

    protected $fillable = [
        'customer_id',
        'customer_name',
        'brand_name',
        'meeting_id',
        'requires_printing',
        'current_phase',
        'total_price',
        'created_by',
    ];

    protected $casts = [
        'requires_printing' => 'boolean',
        'total_price' => 'integer',
        'completed_at' => 'datetime',
        'current_phase'     => OrderPhaseEnum::class,
    ];

    //relations
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
