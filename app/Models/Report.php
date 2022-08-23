<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_status_report', 'id_kendaraan', 'trayek', 'id_terminal','tgl', 'jam', 'id_operator'
    ];
    public function get_kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan', 'id');
    }
    public function get_statusreport()
    {
        return $this->belongsTo(StatusReport::class, 'id_status', 'id');
    }
    public function get_terminal()
    {
        return $this->belongsTo(Terminal::class, 'id_terminal', 'id');
    }
    public function get_operator()
    {
        return $this->belongsTo(User::class, 'id_operator', 'id');
    }

}
