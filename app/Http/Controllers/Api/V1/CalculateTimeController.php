<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class CalculateTimeController extends Controller
{
    public $hours = 0;
    public $minutes = 0;

    public function __construct($in_time, $out_time, $rate)
    {
        $this->in_time = $in_time;
        $this->out_time = $out_time;
        $this->rate = (int) $rate;
    }

    public function calculateTimeUse()
    {
        $this->in_time = Carbon::parse($this->in_time);
        $this->hours = $this->in_time->diffInHours($this->out_time);

        if ($this->hours >= 1) {
            $this->hours;
        } else {
            $this->minutes = $this->in_time->diffInMinutes($this->out_time);
        }

        // $timeHours = $inTime->diffInHours($out_time) . ':' . $inTime->diff($out_time)->format('%I:%S');
        
        // return $timeOcuppied;
    }

    public function timeOccupied()
    {

        return $occupied = $this->in_time->diffInHours($this->out_time) . ':' . $this->in_time->diff($this->out_time)->format('%I:%S');
    }

    /* public function timeInMinutes($inTime, $out_time)
    {
        return $this->minutes = $inTime->diffInMinutes($out_time);
    } */

    /* public function timeInHours($inTime, $out_time)
    {
        return $this->hours = $inTime->diffInHours($out_time);
    } */

    public function calculateAmount()
    {
        $this->calculateTimeUse();

        if ($this->hours > 0) {
            $this->rate = $this->rate * $this->hours;
        } else {
            if ($this->minutes <= 15) {
                $this->rate = $this->rate / 4;
            } else if ($this->minutes <= 30) {
                $this->rate = $this->rate / 3;
            } else if ($this->minutes <= 45) {
                $this->rate = $this->rate / 2;
            } else {
                $this->rate = $this->rate / 1;
            }
        }
    }
}
