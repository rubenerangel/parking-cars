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
use Illuminate\Support\Facades\DB;

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
            [
                'documentId' => $request->documentId,
            ],
            ['name' => $request->name]
        );

        /* Create or Update Vehicle if exists */
        $vehicle = Vehicle::updateOrCreate (
            [
                'plate' => $request->plate,
            ],
            [
                'model'             => $request->model,
                'customer_id'       => $customer->id,
                'type_vehicle_id'   => $request->type_vehicle_id,
                'plate'             => $request->plate,
            ]
        );

        /* Pupulate data parking */
        $parking = Parking::create([
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
                'vehicle'   => $parking->vehicle,
                'customer'  => $parking->customer,
                'slot'      => $parking->slot->id,
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

    public function checkBlockedMove($id)
    {
        /* Plus One to id */
        $idAux = $id + 1; 

        $slotFront = Slot::find($idAux);

        /* If availability_status is 1 slot front is occupied */
        if ($slotFront->availability_status === 1) {
            
            /* Search a Slot Free */
            $slotFree = Slot::where('availability_status', 0)
                ->where('type_vehicle_id', $slotFront->type_vehicle_id)
                ->first();

            if (!empty($slotFree->id)) {  // Board is Not full  
                /* Change Id Slot in Parkings */
                $slotFront->parkingSlot->slot_id = $slotFree->id;
                $slotFront->push();

                /* Availability the Slot Front */
                $slotFront->availability_status = 0;
                $slotFront->push();

                /* Slot Free now is Occupied */
                $slotFree->availability_status = 1;
                $slotFree->save();

                $this->slotClickedReleased($id);

                return true;
            } else { // Board Is full
                /* Availability the Slot Front */
                $slotFront->availability_status = 0;
                $slotFront->push();

                $slotFront->parkingSlot->slot_id = $id;
                $slotFront->push();

                $this->slotClickedReleased($id);
                
                return true;
            }
        } else {
            $slot = Slot::find($id);

            $slot->availability_status = 0;
            $slot->save();
        }

        return true;
    }

    public function slotClickedReleased($id)
    {
        /* Slot Clicked */
        $slot = Slot::find($id);
        $slot->availability_status = 0;
        $slot->save();
    }

    public function emptySlot(Request $request)
    {
        $parking = Parking::with(
            [
                'slot', 
                'vehicle', 
                'customer',
                'rate'
            ])
            ->where('slot_id', $request->id)
            ->where('paid_status', 0)
            ->first();

        /* Calculate Time and Amount*/
        $calTime = new CalculateTimeController(
            $parking->in_time, 
            $request->out_time,
            $parking->rate->rate
        );
        $calTime->calculateAmount();

        /* Update Row */
        $parking->out_time = $request->out_time;
        $parking->total_time = $calTime->timeOccupied();
        $parking->earned_amount = $calTime->rate;
        $parking->paid_status = 1;
        
        /* Check Blocked or Not */
        $this->checkBlockedMove($request->id);
        
        $parking->save();

        if ($parking && $parking->slot) {
            $message = [
                'status'    => 1,
                'message'   => 'Slot released...',
                'parking'   => $parking,
                'percentage' => $calTime->percentageDiscount
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

    public function validatePlate(Request $request)
    {
        /* validate get Data */
        $validatePlate = $request->validate([
            'plate' => 'required',
        ]);
        
        $plate = Vehicle::select(
            DB::raw('slots.name')
        )
        ->join('parkings', 'parkings.vehicle_id', '=', 'vehicles.id')
        ->join('slots', 'parkings.slot_id', '=', 'slots.id')
        ->where('vehicles.plate', 'like', $request->plate)
        ->where('parkings.paid_status', 0)
        ->first();

        if (!empty($plate) && $plate->count() > 0) {
            $message = [
                'status' => 0,
                'slot'   => $plate->name
            ];
            $status = 200;
        } else {
            $message = [
                'status' => 1
            ];
            $status = 200;
        }

        return response()->json($message, $status);
    }
}
