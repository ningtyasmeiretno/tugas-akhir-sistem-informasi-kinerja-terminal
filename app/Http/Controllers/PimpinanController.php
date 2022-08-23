<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pimpinan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class PimpinanController extends Controller
{
    public function index()
    {
        // //get data from table user
        // $pimpinans = Pimpinan::latest()->get();

        // //make response json
        // return response()->json([
        //     'success' => true,
        //     'message' => 'List Data Dinas',
        //     'data' => $pimpinans
        // ]);
        return Pimpinan::when(request('search'), function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        })->with('get_status')->paginate(3);
    }
    //SHOW
    public function show($id)
    {
        $pimpinan = Pimpinan::findOrfail($id);

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Dinas',
            'data' => $pimpinan
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required',
            'telp'      => 'required',
            'username'  => 'required',
            'password'  => 'required',
            'id_status' => 'required'
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $pimpinan = Pimpinan::create([
            'name' => $request->name,
            'email' => $request->email,
            'telp' => $request->telp,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_status' => $request->id_status
        ]);

        //success save to database
        if ($pimpinan) {
            return response()->json([
                'success' => true,
                'message' => 'Data Perusahaan Created',
                'data'    => $pimpinan
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
            'name'      => 'required',
            'email'     => 'required',
            'telp'      => 'required',
            'username'  => 'required',
            'id_status' => 'required'
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $pimpinan = Pimpinan::findOrFail($id);
        if ($pimpinan) {
            //update level akses
            $pimpinan->update([
                'name' => $request->name,
                'email' => $request->email,
                'telp' => $request->telp,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'id_status' => $request->id_status
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data Saved',
                'data' => $pimpinan
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Dinas'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $pimpinan = Pimpinan::findOrfail($id);

        if ($pimpinan) {
            $pimpinan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Pimpinan Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'User Dinas not found'
        ]);
    }

    public function resetPassword(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pimpinan = Pimpinan::find($id);
        $pimpinan->update([
            'password'  => Hash::make($request->password)
        ]);

        //return with response JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Password Berhasil Diupdate!',
            'data'    => $pimpinan,
        ], 201);
    }
    public function countData()
    {
        $count = Pimpinan::count();

        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }
}

