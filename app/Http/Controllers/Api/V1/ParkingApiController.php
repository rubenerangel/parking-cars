<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Slot;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Api\V1\CalculateTimeController;

class ParkingApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new ParkingResource(Parking::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* validate get Data */
        $validateData = $request->validate([
            'documentId' => 'required',
            'in_time' => 'required',
            'model' => 'required',
            'name' => 'required',
            'rate_id' => 'required',
            'slot_id' => 'required',
            'type_vehicle_id' => 'required',
        ]);

        /* Create or Update User if exists */
        $customer = Customer::updateOrCreate (
            ['documentId' => $request->documentId],
            ['name' => $request->name]
        );

        /* Create or Update Vehicle if exists */
        $vehicle = Vehicle::updateOrCreate (
            ['plate' => $request->plate, 'serial' => $request->serial, 'type_vehicle_id' => $request->type_vehicle_id],
            [
                'model' => $request->model,
                'customer_id' => $customer->id,
            ]
        );

        /* Pupulate data parking */
        $parking = Parking::create([
            'type_vehicle_id' => $request->type_vehicle_id,
            'rate_id'         => $request->rate_id,
            'slot_id'         => $request->slot_id,
            'customer_id'     => $customer->id,
            'vehicle_id'      => $vehicle->id,
            'in_time'         => $request->in_time,
        ]);

        $parking->save();

        /* Slot now is busy */
        $parking->slot->availability_status = 1;
        $parking->slot->save();

        if ($parking) {
            $message = [
                'status'    => 1,
                'message'   => 'Slot has been Occupied'
            ];
            $status = 200;
        } else {
            $message = [
                'status'    => 0,
                'message'   => 'Problems Occuping Slot.'
            ];
            $status = 400;
        }

        return response()->json($message, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function emptySlot(Request $request)
    {
        $parking = Parking::where('slot_id', $request->id)
            ->where('paid_status', 0)
            ->first();

        /* Calculate Time and Amount*/
        $calTime = new CalculateTimeController(
            $parking->in_time, 
            $request->out_time,
            $parking->rate->rate
        );
        $calTime->calculateAmount();

        // dd($calTime->rate);

        /* Update Row */
        $parking->out_time = $request->out_time;
        $parking->total_time = $calTime->timeOccupied();
        $parking->earned_amount = $calTime->rate;
        $parking->paid_status = 1;

        
        /* Released Slot*/
        $parking->slot->availability_status = 0;
        $parking->slot->save();
        
        $parking->save();

        if ($parking) {
            $message = [
                'status'    => 1,
                'message'   => 'Slot released...'
            ];

            $statusResponse = 200;
        } else {
            $message = [
                'status'    => 0,
                'message'   => 'Problems in released, Please Check...'
            ];

            $status = 400;
        }
        
        return response()->json($message, $statusResponse);
    }
}
