<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_po', 'id_kota', 'alamat', 'telp'
    ];
    public function get_kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id');
    }
    public function get_kendaraan()
    {
        return $this->hasMany(Kendaraan::class, 'id_perusahaan', 'id');
    }
    public function get_report()
    {
        return $this->hasMany(Report::class, 'id_perusahaan', 'id');
    }
}
