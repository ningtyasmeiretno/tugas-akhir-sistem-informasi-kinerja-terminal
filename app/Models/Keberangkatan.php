<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keberangkatan extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_kendaraan', 'trayek_keberangkatan', 'tgl_keberangkatan', 'jam_keberangkatan'
    ];
    public function get_kedatangan()
    {
        return $this->belongsTo(Kedatangan::class, 'id_kedatangan', 'id');
    }
}
