<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'id_perusahaan', 'merk_kendaraan', 'no_kendaraan', 'jumlah_seat', 'no_uji', 'exp_uji', 'no_kps', 'exp_kps', 'id_angkutan'
    ];
    public function get_perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id');
    }
    public function get_kedatangan()
    {
        return $this->hasMany(Kedatangan::class, 'id_kendaraan', 'id');
    }
    public function get_angkutan()
    {
        return $this->belongsTo(JenisAngkutan::class, 'id_angkutan', 'id');
    }
}
