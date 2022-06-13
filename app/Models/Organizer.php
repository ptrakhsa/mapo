<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;




class Organizer extends Authenticatable
{
    use HasFactory;
    use SoftDeletes;
    use Notifiable;

    protected $hidden = ['password', 'created_at', 'updated_at', 'deleted_at'];
    protected $guard = 'organizer';

    protected $fillable = ['name', 'email', 'address', 'password'];


    public function events()
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }
}
