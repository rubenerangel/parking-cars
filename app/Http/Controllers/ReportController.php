<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Parking;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        /* validate get Data */
        $validateData = $request->validate([
            'from' => 'required|date',
            'until' => 'required|date|after_or_equal:from',
        ]);

        switch ($request->reportType) {
            case 'more_used':
                $moreUsed = $this->moreUsed($request->from, $request->until);

                return view('reportSlot', compact('moreUsed'));
                break;
            case 'in_out':
                $inOut = $this->inOut($request->from, $request->until);

                return view('reportSlot', compact('inOut'));
                break;
            case 'type_vehicle':
                $typeVehicle = $this->typeVehicle($request->from, $request->until);

                return view('reportSlot', compact('typeVehicle'));
                break;
            
            case 'amount':
                $amount = $this->amount($request->from, $request->until);

                return view('reportSlot', compact('amount'));
                break;
        }
    }

    public function moreUsed($from, $until)
    {
        $parkingMoreUsed = Parking::select(
                DB::raw (
                    'parkings.slot_id, 
                    COUNT(*) AS more_used,
                    slots.name'
                ) 
            )
            ->join('slots', 'slots.id', '=', 'parkings.slot_id')
            ->whereBetween('in_time', [$from . ' 00:00:00', $until . ' 23:59:59'])
            ->groupBy('slot_id')
            ->orderBy('more_used', 'DESC')
            ->get();

        return $parkingMoreUsed;
    }

    public function inOut($from, $until)
    {
        $parkingInOut = Parking::select(
            DB::raw(
                'vehicles.plate,
                parkings.in_time,
                parkings.out_time,
                parkings.total_time,
                parkings.earned_amount'
            )
        ) 
        ->join('vehicles', 'parkings.vehicle_id', '=', 'vehicles.id')
        ->whereBetween('in_time', [$from . ' 00:00:00', $until . ' 23:59:59'])
        ->get();

        return $parkingInOut;
    }

    public function typeVehicle($from, $until)
    {
        // dd($from, $until);
        $parkingTypeVehicles = Parking::select(
            DB::raw(
                'COUNT(*) AS count_types,
                type_vehicles.name'
            )
        )
        ->join('vehicles', 'parkings.vehicle_id', '=', 'vehicles.id')
        ->join('type_vehicles', 'vehicles.type_vehicle_id', '=', 'type_vehicles.id')
        ->whereBetween('in_time', [$from . ' 00:00:00', $until . ' 23:59:59'])
        ->groupBy('type_vehicles.id')
        ->get();

        return $parkingTypeVehicles;
    }

    public function amount($from, $until)
    {
        $amount = Parking::select(
            DB::raw('SUM(parkings.earned_amount) as mount_total')
        )
        ->whereBetween('in_time', [$from . ' 00:00:00', $until . ' 23:59:59'])
        ->get();

        return $amount;
    }
}
