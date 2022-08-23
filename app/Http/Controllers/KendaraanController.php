<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kendaraan;
// use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\DB;

class KendaraanController extends Controller
{

    protected $status = null;
    protected $error = null;
    protected $data = null;

    public function index()
    {
		// return Kendaraan::with('get_perusahaan')->with('get_angkutan')->paginate(2);

		return Kendaraan::when(request('search'), function ($query) {
			$query->where('no_kendaraan', 'like', '%' . request('search') . '%');
		})->with('get_perusahaan')->with('get_angkutan')->paginate(20);

        //make response json
    }
    //SHOW
    public function show($id)
    {
        $kendaraan = Kendaraan::with('get_perusahaan')->with('get_angkutan')->findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $kendaraan
        ], 200);
    }

    //STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_perusahaan' => 'required',
            'merk_kendaraan' => 'required',
            'no_kendaraan' => 'required',
            'jumlah_seat' => 'required',
            'no_uji' => 'required',
            'exp_uji' => 'required',
            'no_kps' => 'required',
            'exp_kps' => 'required',
            'id_angkutan' => 'required'
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $kendaraan = Kendaraan::create([
            'id_perusahaan' => $request->id_perusahaan,
            'merk_kendaraan' => $request->merk_kendaraan,
            'no_kendaraan' => $request->no_kendaraan,
            'jumlah_seat' => $request->jumlah_seat,
            'no_uji' => $request->no_uji,
            'exp_uji' => $request->exp_uji,
            'no_kps' => $request->no_kps,
            'exp_kps' => $request->exp_kps,
            'id_angkutan' => $request->id_angkutan,
        ]);

        //success save to database
        if ($kendaraan) {
            return response()->json([
                'success' => true,
                'message' => 'Data kendaraan Created',
                'data'    => $kendaraan
            ]);
        }

        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data Kendaraan Failed to Save',
        ]);
    }

    //UPDATE
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_perusahaan' => 'required',
            'merk_kendaraan' => 'required',
            'no_kendaraan' => 'required',
            'jumlah_seat' => 'required',
            'no_uji' => 'required',
            'exp_uji' => 'required',
            'no_kps' => 'required',
            'exp_kps' => 'required',
            'id_angkutan' => 'required'
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $kendaraan = Kendaraan::findOrFail($id);
        if ($kendaraan) {
            //update level akses
            $kendaraan->update([
                'id_perusahaan' => $request->id_perusahaan,
                'merk_kendaraan' => $request->merk_kendaraan,
                'no_kendaraan' => $request->no_kendaraan,
                'jumlah_seat' => $request->jumlah_seat,
                'no_uji' => $request->no_uji,
                'exp_uji' => $request->exp_uji,
                'no_kps' => $request->no_kps,
                'exp_kps' => $request->exp_kps,
                'id_angkutan' => $request->id_angkutan,
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data Updated',
                'data' => $kendaraan
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Kendaraan'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $kendaraan = Kendaraan::findOrfail($id);

        if ($kendaraan) {
            $kendaraan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data kendaraan Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'Kendaraan not found'
        ]);
    }
}
