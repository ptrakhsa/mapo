<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Organizer;
use App\Models\Category;
use App\Models\SubmittedEvent;
use Illuminate\Support\Facades\DB;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;


    public function organizer()
    {
        return $this->belongsTo(Organizer::class, 'organizer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function submissions()
    {
        return $this->hasMany(SubmittedEvent::class);
    }

    public function status()
    {
        return $this->hasOne(SubmittedEvent::class)->latestOfMany();
    }

    public function inWaiting()
    {
        return $this->hasOne(SubmittedEvent::class)->latestOfMany()->where('status', 'waiting');
    }

    public function hasVerified()
    {
        return $this->hasOne(SubmittedEvent::class)->latestOfMany()->where('status', 'verified');
    }

    public function hasRejected()
    {
        return $this->hasOne(SubmittedEvent::class)->latestOfMany()->where('status', 'rejected');
    }

    public function hasTakedown()
    {
        return $this->hasOne(SubmittedEvent::class)->latestOfMany()->where('status', 'takedown');
    }

    public function inPopularPlaces()
    {
        return $this->belongsTo(PopularPlaces::class, 'popular_place_id');
    }
}
