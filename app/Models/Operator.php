<?php

namespace App\Models;

use App\Http\Middleware\Authenticate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'telp','username', 'password', 'id_status'
    ];
    public function get_operator()
    {
        return $this->hasMany(Kedatangan::class, 'id_operator', 'id');
    }
    public function get_status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id');
    }

}
