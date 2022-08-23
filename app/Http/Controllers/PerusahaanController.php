<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perusahaan;
use Illuminate\Support\Facades\Validator;

class PerusahaanController extends Controller
{
    public function index()
    {
        return Perusahaan::when(request('search'), function ($query) {
            $query->where('nama_po', 'like', '%' . request('search') . '%');
        })->with('get_kota')->paginate(20);
    }
    //get all without pagination
    public function dropdown()
    {
        //get data from table user
        $perusahaans = Perusahaan::latest()->get();

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'List Data Kota',
            'data' => $perusahaans
        ]);
    }
    //SHOW
    public function show($id)
    {
        $perusahaan = Perusahaan::with('get_kota')->findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $perusahaan
        ], 200);
    }

    //STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_po' => 'required',
            'id_kota' => 'required',
            'alamat' => 'required',
            'telp' => 'required',
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $perusahaan = Perusahaan::create([
            'nama_po' => $request->nama_po,
            'id_kota' => $request->id_kota,
            'alamat' => $request->alamat,
            'telp' => $request->telp,
        ]);

        //success save to database
        if ($perusahaan) {
            return response()->json([
                'success' => true,
                'message' => 'Data Perusahaan Created',
                'data'    => $perusahaan
            ]);
        }

        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data Perusahaan Failed to Save',
        ]);
    }

    //UPDATE
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_po' => 'required',
            'id_kota' => 'required',
            'alamat' => 'required',
            'telp' => 'required',
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $perusahaan = Perusahaan::findOrFail($id);
        if ($perusahaan) {
            //update level akses
            $perusahaan->update([
                'nama_po' => $request->nama_po,
                'id_kota' => $request->id_kota,
                'alamat' => $request->alamat,
                'telp' => $request->telp,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Data Saved',
                'data' => $perusahaan
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Perusahaan'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $perusahaan = Perusahaan::findOrfail($id);

        if ($perusahaan) {
            $perusahaan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Perusahaan Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'Perusahaan not found'
        ]);
    }

    //hitung jumlah PO
    public function countData()
    {
        $count = Perusahaan::count();

        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }
}
