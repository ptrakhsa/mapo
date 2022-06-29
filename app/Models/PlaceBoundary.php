<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class PlaceBoundary extends Authenticatable
{
    use HasFactory;
    use Notifiable;


    public function events()
    {
        return $this->join('events', function($join){
            $join->where(DB::raw('ST_WITHIN(events.position, place_boundaries.polygon_area)'), true);
        });
    }
}
