<?php

namespace App\Exports;

use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KedatanganExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return Report::select(
    //         "reports.*",
    //         "kendaraans.no_kendaraan as no_kendaraan",
    //         "terminals.nama_terminal as  nama_terminal",
    //         "perusahaans.nama_po as nama_po",
    //         "kendaraans.no_uji as no_uji",
    //         "kendaraans.no_kps as no_kps",
    //         "kendaraans.exp_uji as exp_uji",
    //         "kendaraans.exp_kps as exp_kps",
    //         "users.name as name",
    //     )
    // ->join("kendaraans", "reports.id_kendaraan", "=", "kendaraans.id")
    // ->join("terminals", "reports.id_terminal", "=", "terminals.id")
    // ->join("perusahaans", "kendaraans.id_perusahaan", "=", "perusahaans.id")
    // ->join("users", "reports.id_operator", "=", "users.id")
    // ->where("id_status_report", "=", 1);
    //     //return Report::all();
    // }

    public function __construct (string $keyword){
        $this->id_status_report = $keyword;
    }
    public function query (){
        return Report::when(request('search'), function($query) {
        $query->where('id_status_report', 'like', '%' . request('search') . '%');
        })
        ->select(
            // "reports.*",
            "kendaraans.no_kendaraan as no_kendaraan",
            "terminals.nama_terminal as  nama_terminal",
            "perusahaans.nama_po as nama_po",
            "reports.trayek",
            "kendaraans.no_uji as no_uji",
            "kendaraans.no_kps as no_kps",
            "reports.tgl",
            "reports.jam",
            "users.name as name",
        )
    ->join("kendaraans", "reports.id_kendaraan", "=", "kendaraans.id")
    ->join("terminals", "reports.id_terminal", "=", "terminals.id")
    ->join("perusahaans", "kendaraans.id_perusahaan", "=", "perusahaans.id")
    ->join("users", "reports.id_operator", "=", "users.id")
    ->where("id_status_report", "=", 1);
    }

    public function headings(): array
    {
        return [
            'No Kendaraan',
            'Terminal Asal',
            'Nama PO',
            'Trayek',
            'No Uji',
            'No KPS',
            'Tanggal Kedatangan',
            'Jam Kedatangan',
            'Nama Operator',
        ];
    }
}
