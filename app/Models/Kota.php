<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kota extends Model
{
    use HasFactory;
    protected $fillable = [
        'kota', 'provinsi', 'id_status'
    ];
    public function get_status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id');
    }
    public function get_terminal()
    {
        return $this->hasMany(Terminal::class, 'id_kota', 'id');
    }
    public function get_perusahaan()
    {
        return $this->hasMany(Perusahaan::class, 'id_kota', 'id');
    }
    public function get_unitpelaksana()
    {
        return $this->hasMany(UnitPelaksana::class, 'id_kota', 'id');
    }
}
