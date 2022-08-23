<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisAngkutan extends Model
{
    use HasFactory;
    protected $fillable =[
        'jenis_angkutan', 'id_status'
    ];
    public function get_status(){
        return $this->belongsTo(Status::class, 'id_status','id');
    }
    public function get_kendaraan()
    {
        return $this->hasMany(Kendaraan::class, 'id_angkutan', 'id');
    }
}
