<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubmittedEvent extends Model
{
    use HasFactory;
    use SoftDeletes;

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
