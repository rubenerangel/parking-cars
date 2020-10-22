<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'availability_status',
        'type_vehicle_id',
        'position',
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

    public function parking()
    {
        return $this->hasOne('App\Models\Parking')
            ->join('vehicles', 'parkings.vehicle_id', '=', 'vehicles.id')
            ->join('customers', 'parkings.customer_id', '=', 'customers.id')
            ->where('paid_status', 0);
    }

    public function parkingSlot()
    {
        return $this->hasOne('App\Models\Parking')
            ->where('paid_status', 0);
    }

    public function dataVehicle()
    {
        return $this->hasOneThrough(
            'App\Models\Vehicle', 
            'App\Models\Parking',
            'vehicle_id',
            'id',
            'id'
        ) ;
    }
}
