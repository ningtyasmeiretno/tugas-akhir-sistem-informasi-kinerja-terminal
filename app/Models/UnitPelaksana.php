<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitPelaksana extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_upt', 'id_kota', 'alamat'
    ];
    public function get_kota()
    {
        return $this->belongsTo(Kota::class, 'id_kota', 'id');
    }
    public function get_terminal()
    {
        return $this->hasMany(Terminal::class, 'id_upt', 'id');
    }
}
