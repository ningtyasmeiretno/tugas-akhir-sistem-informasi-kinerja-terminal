<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terminal extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama_terminal', 'id_kota', 'id_upt' ,'alamat', 'foto', 'telp'
    ];
    public function get_kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id');
    }
    public function get_report()
    {
        return $this->hasMany(Report::class, 'id_terminal', 'id');
    }
    public function get_upt()
    {
        return $this->belongsTo(UnitPelaksana::class, 'id_upt', 'id');
    }
}
