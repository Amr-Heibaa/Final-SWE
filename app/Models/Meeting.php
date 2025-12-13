<?php

namespace App\Models;

use App\Enums\MeetingStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;



    protected $table = 'meetings';
    protected $primaryKey = 'id';



    protected $fillable = [
        'customer_id',
        'scheduled_date',
        'status',
    ];

    protected $guarded = ['id'];


    protected $casts = [
        'scheduled_date' => 'datetime',
        'status'         => MeetingStatus::class,
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'meeting_id');
    }
}
