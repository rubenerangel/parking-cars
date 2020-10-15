<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_vehicle_id',
        'rate_id',
        'slot_id',
        'in_time',
        'out_time',
        'total_time',
        'earned_amount',
        'customer_id',
        'vehicle_id',
        'paid_status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function slot()
    {
        return $this->belongsTo('App\Models\Slot');
    }

    public function rate()
    {
        return $this->belongsTo('App\Models\Rate');
    }
}
