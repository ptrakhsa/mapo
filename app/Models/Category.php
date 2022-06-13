<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Event;

class Category extends Model
{
    use HasFactory;

    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];


    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }
}
