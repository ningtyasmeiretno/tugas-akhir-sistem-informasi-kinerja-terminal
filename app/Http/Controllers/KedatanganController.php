<?php

namespace App\Http\Controllers;

use App\Models\JenisAngkutan;
use App\Models\Kedatangan;
use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Exports\KedatanganExport;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class KedatanganController extends Controller
{
    protected $status = null;
    protected $error = null;
    protected $data = null;
    public function index()
    {
        // $kedatangan = Report::with('get_kendaraan')->with('get_terminal')->get();

    return Report::when(request('search'), function($query) {
        $query->where('no_kendaraan', 'like', '%' . request('search') . '%');
        })
        ->select(
            "reports.*",
            "kendaraans.no_kendaraan as no_kendaraan",
            "terminals.nama_terminal as  nama_terminal",
            "perusahaans.nama_po as nama_po",
            "kendaraans.no_uji as no_uji",
            "kendaraans.no_kps as no_kps",
            "kendaraans.exp_uji as exp_uji",
            "kendaraans.exp_kps as exp_kps",
            "users.name as name",
        )
    ->join("kendaraans", "reports.id_kendaraan", "=", "kendaraans.id")
    ->join("terminals", "reports.id_terminal", "=", "terminals.id")
    ->join("perusahaans", "kendaraans.id_perusahaan", "=", "perusahaans.id")
    ->join("users", "reports.id_operator", "=", "users.id")
    // ->join("users", "reports.id_status", "=", "users.id")
    ->where("id_status_report", "=", 1)
    ->with("get_kendaraan")
    ->paginate(20);
        //make response json
        
    }
    //SHOW
    public function show()
    {
        return Report::when(request('search'), function($query) {
        $query->where('no_kendaraan', 'like', '%' . request('search') . '%');
        })->select(
            "reports.*",
            "kendaraans.no_kendaraan as no_kendaraan",
            "terminals.nama_terminal as  nama_terminal",
            "perusahaans.nama_po as nama_po",
            "kendaraans.no_uji as no_uji",
            "kendaraans.no_kps as no_kps",
            "kendaraans.exp_uji as exp_uji",
            "kendaraans.exp_kps as exp_kps",
            "users.name as name",
        )
        ->join("kendaraans", "reports.id_kendaraan", "=", "kendaraans.id")
        ->join("terminals", "reports.id_terminal", "=", "terminals.id")
        ->join("perusahaans", "kendaraans.id_perusahaan", "=", "perusahaans.id")
        ->join("users", "reports.id_operator", "=", "users.id")
        // ->join("users", "reports.id_status", "=", "users.id")
        ->where("id_status_report", "=", 1)
        ->where('id_operator', auth()->guard('user-api')->user()->id)
        ->paginate(20);
        //make response JSON
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Detail Data',
        //     'data'    => $kedatangan
        // ], 200);
    }


    //STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_status_report' => 'required',
            'id_kendaraan' => 'required',
            'trayek' => 'required',
            'id_terminal' => 'required',
            'tgl' => 'required',
            'jam' => 'required',
            'id_operator' => 'required'
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $kedatangan = Report::create([
            'id_status_report' => $request -> id_status_report,
            'id_kendaraan' => $request->id_kendaraan,
            'trayek' => $request->trayek,
            'id_terminal' => $request->id_terminal,
            'tgl' => $request->tgl,
            'jam' => $request->jam,
            'id_operator' => $request->id_operator,
        ]);

        //success save to database
        if ($kedatangan) {
            return response()->json([
                'success' => true,
                'message' => 'Data kedatangan Created',
                'data'    => $kedatangan
            ]);
        }

        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data Kedatangan Failed to Save',
        ]);
    }

    //UPDATE
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            // 'id_status_report' => 'required',
            'id_kendaraan' => 'required',
            'trayek' => 'required',
            'id_terminal' => 'required',
            'tgl' => 'required',
            'jam' => 'required',
            // 'id_operator' => 'required'
        ]);
        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $kedatangan = Report::findOrFail($id);
        if ($kedatangan) {
            //update level akses
            $kedatangan->update([
                // 'id_status' => $request->id_status,
                'id_kendaraan' => $request->id_kendaraan,
                'trayek' => $request->trayek,
                'id_terminal' => $request->id_terminal,
                'tgl' => $request->tgl,
                'jam' => $request->jam,
                // 'id_operator' => $request->id_operator,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Data Updated',
                'data' => $kedatangan
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Kedatangan'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $kedatangan = Report::findOrfail($id);

        if ($kedatangan) {
            $kedatangan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Kedatangan Deleted'
            ]);
        }

        //data termnal not found
        return response()->json([
            'success' => false,
            'message' => 'Kedatangan not found'
        ]);
    }
    //export excel
    public function kedatanganExport(){

        return Excel::download(new KedatanganExport('id_status_report'), 'report_kedatangan.xlsx');
        
    }

    //hitung jumlah kedatangan per hari
    public function countData()
    {
        $count = Report::where('tgl', Carbon::now()->format('Y-m-d'))->count();

        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }

    // public function countData(Request $request){
    //     // $data = DB::table('ta_siter1')
    //     // ->select([
    //     //     DB::raw('count(*) as jumlah'),
    //     //     DB::raw('DATE(created_at) as tgl')
    //     // ])
    //     // ->groupBy('tgl')
    //     // ->whereRaw('DATE(created_at) >= ? ', [date('Y-m-d', strtotime('-7 days'))])
    //     // ->orderBy('tgl', 'desc')
    //     // ->get();
    //     // dd($data);
    //     // $count = Report::select(DB::raw("CAST(SUM(id_status_report) as int) as id_status_report"))
    //     // ->groupBy(DB::raw("Day(created_at)"))
    //     // ->pluck('count');

        
    // }
    
}
