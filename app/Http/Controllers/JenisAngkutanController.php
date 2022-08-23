<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisAngkutan;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class JenisAngkutanController extends Controller
{
    protected $status = null;
    protected $error = null;
    protected $data = null;
   public function index()
    {
        return JenisAngkutan::when(request('search'), function ($query) {
            $query->where('jenis_angkutan', 'like', '%' . request('search') . '%');
        })->with('get_status')->paginate(10);
    }
    //SHOW
    public function show($id)
    {
        $angkutan = JenisAngkutan::with('get_status')->findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $angkutan 
        ], 200);
    }

    //STORE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_angkutan' => 'required',
            'id_status' => 'required'
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $angkutan = JenisAngkutan::create([
            'jenis_angkutan' => $request->jenis_angkutan,
            'id_status' => $request->id_status,
        ]);

        //success save to database
        if ($angkutan) {
            return response()->json([
                'success' => true,
                'message' => 'Data Jenis Angkutan Created',
                'data'    => $angkutan
            ]);
        }

        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data Jenis Angkutan Failed to Save',
        ]);
    }

    //UPDATE
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jenis_angkutan' => 'required',
            'id_status' => 'required',
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $angkutan = JenisAngkutan::findOrFail($id);
        if ($angkutan) {
            //update level akses
            $angkutan->update([
                'jenis_angkutan' => $request->jenis_angkutan,
                'id_status' => $request->id_status,
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data Jenis Angkutan Updated',
                'data' => $angkutan
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Jenis Angkutan'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $angkutan = JenisAngkutan::findOrfail($id);

        if ($angkutan) {
            $angkutan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Jenis Angkutan Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'Jenis Angkutan not found'
        ]);
    }
}
