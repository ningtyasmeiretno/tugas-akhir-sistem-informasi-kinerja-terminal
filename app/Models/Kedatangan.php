<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kedatangan extends Model
{
    use HasFactory;
    protected $fillable=[
        'id_kendaraan','trayek', 'tgl_kedatangan', 'jam_kedatangan'
    ];
    public function get_kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id');
    }
    public function get_keberangkatan()
    {
        return $this->hasMany(Keberangkatan::class, 'id_kedatangan', 'id');
    }
}
