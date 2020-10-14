<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Slot;

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
        $validateData = $request->validate([
            'documentId' => 'required',
            'in_time' => 'required',
            'model' => 'required',
            'name' => 'required',
            'rate_id' => 'required',
            'slot_id' => 'required',
            'type_vehicle_id' => 'required',
        ]);

        $customer = Customer::updateOrCreate (
            ['documentId' => $request->documentId],
            ['name' => $request->name]
        );

        $vehicle = Vehicle::updateOrCreate (
            ['plate' => $request->plate, 'serial' => $request->serial, 'type_vehicle_id' => $request->type_vehicle_id],
            [
                'model' => $request->model,
                'customer_id' => $customer->id,
            ]
        );

        Parking::create([
            'type_vehicle_id' => $request->type_vehicle_id,
            'rate_id'         => $request->rate_id,
            'slot_id'         => $request->slot_id,
            'customer_id'     => $customer->id,
            'vehicle_id'      => $vehicle->id,
            'in_time'         => $request->in_time,
        ]);

        $slot = Slot::find($request->slot_id);
        $slot->availability_status = 1;
        $slot->save();

        return response()->json($request->all(), 200);
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
}
