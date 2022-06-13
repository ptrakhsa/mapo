<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;

class SubmittedEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'event_id',
        'reason',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
