<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitPelaksana;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UnitPelaksanaController extends Controller
{
    protected $status = null;
    protected $error = null;
    protected $data = null;

    
    public function index()
    {
        return UnitPelaksana::when(request('search'), function ($query) {
            $query->where('nama_upt', 'like', '%' . request('search') . '%');
        })->orderBy('nama_upt', 'asc')->with('get_kota')->paginate(20);
    }
    //SHOW
    public function show($id)
    {
        $unit = UnitPelaksana::with('get_kota')->findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $unit
        ], 200);
    }

    //STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_upt' => 'required',
            'id_kota' => 'required',
            'alamat' => 'required'
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $unit = UnitPelaksana::create([
            'nama_upt' => $request->nama_upt,
            'id_kota' => $request->id_kota,
            'alamat' => $request->alamat,
        ]);

        //success save to database
        if ($unit) {
            return response()->json([
                'success' => true,
                'message' => 'Data Jenis UPT Created',
                'data'    => $unit
            ]);
        }

        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data UPT Failed to Save',
        ]);
    }

    //UPDATE
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_upt' => 'required',
            'id_kota' => 'required',
            'alamat' => 'required'
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $unit = UnitPelaksana::findOrFail($id);
        if ($unit) {
            //update level akses
            $unit->update([
                'nama_upt' => $request->nama_upt,
                'id_kota' => $request->id_kota,
                'alamat' => $request->alamat,
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data UPT Updated',
                'data' => $unit
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data UPT'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $unit = UnitPelaksana::findOrfail($id);

        if ($unit) {
            $unit->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data UPT Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'UPT not found'
        ]);
    }
    //hitung jumlah PO
    public function countData()
    {
        $count = UnitPelaksana::count();

        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }
}
