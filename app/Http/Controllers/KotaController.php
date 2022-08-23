<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kota;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class KotaController extends Controller
{

    protected $status = null;
    protected $error = null;
    protected $data = null;

    public function index()
    {
        return Kota::when(request('search'), function ($query) {
            $query->where('kota', 'like', '%' . request('search') . '%');
        })->orderBy('kota', 'asc')->with('get_status')->paginate(20);
    }
    public function dropdown()
    {
        //get data from table user
        $kotas = Kota::latest()->get();

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'List Data Kota',
            'data' => $kotas
        ]);
    }
    //SHOW
    public function show($id)
    {
        $kota = Kota::with('get_status')->findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $kota 
        ], 200);

        // try {
        //     if ($id) {
        //         $kota = Kota::with('get_status')->get();
        //     } else {
        //         $kota = Kota::get();
        //     }
        //     $this->data = $kota;
        //     $this->status = "success";
        // } catch (QueryException $e) {
        //     $this->status = "failed";
        //     $this->error = $e;
        // }
        // return response()->json([
        //     "status" => $this->status,
        //     "data" => $this->data,
        //     "error" => $this->error
        // ]);
    }

    //STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kota' => 'required',
            'provinsi' => 'required',
            'id_status' => 'required',
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $kota = Kota::create([
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'id_status' => $request->id_status,
            
        ]);

        //success save to database
        if ($kota) {
            return response()->json([
                'success' => true,
                'message' => 'Data Kota Created',
                'data'    => $kota
            ]);
        }

        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data Kota Failed to Save',
        ]);
    }

    //UPDATE
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kota' => 'required',
            'provinsi' => 'required',
            'id_status' => 'required',
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $kota = Kota::findOrFail($id);
        if ($kota) {
            //update level akses
            $kota->update([
                'kota' => $request->kota,
                'provinsi' => $request->provinsi,
                'id_status' => $request->id_status,
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data Updated',
                'data' => $kota
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Kota'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $kota = Kota::findOrfail($id);

        if ($kota) {
            $kota->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Kota Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'Kota not found'
        ]);
    }
}
