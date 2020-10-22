<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Reduction;

class CalculateTimeController extends Controller
{
    public $hours   = 0;
    public $minutes = 0;
    public $rate    = 0;
    public $percentageDiscount = 0;
    public $diffTotalInMinutes = 0;

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
    }

    public function timeOccupied()
    {
        return $occupied = $this->in_time->diffInHours($this->out_time) . ':' . $this->in_time->diff($this->out_time)->format('%I:%S');
    }

    public function calculateAmount()
    {
        $this->calculateTimeUse();

        $reduction = Reduction::first();
        
        if ($this->hours > 0) {
            $amount = ($this->rate * $this->hours);
            $fraction = 1;
        } else {
            if ($this->minutes <= 15) {
                $amount = $this->rate / 4;
                
            } else if ($this->minutes <= 30) {
                $amount = $this->rate / 3;
                
            } else if ($this->minutes <= 45) {
                $amount = $this->rate / 2;
                
            } else {
                $amount = $this->rate / 1;
                
            }
        }

        $diffInMinutes = $this->in_time->diffInMinutes($this->out_time);
        
        /* Have reduction? */
        if ( $diffInMinutes >= $reduction->time) {
            $percentage = ($amount * $reduction->percentage) / 100;
            $this->percentageDiscount = $reduction->percentage;
        } else {
            $percentage = 0;
        }

        $this->rate = ($amount) - $percentage;
    }
}
