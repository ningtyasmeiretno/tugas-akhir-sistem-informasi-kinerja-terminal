<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    public function index()
    {
        //get data from table user
        $statuses = Status::latest()->get();

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'List Data Operator',
            'data' => $statuses
        ]);
    }
    //SHOW
    public function show($id)
    {
        $status = Status::findOrfail($id);

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'Detail Data status',
            'data' => $status
        ]);
    }

    //Add Data
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'      => 'required',
        ]);

        //response error validator
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //save to DB
        $status = Status::create([
            'status' => $request->status,
    
        ]);

        //success save to database
        if ($status) {
            return response()->json([
                'success' => true,
                'message' => 'Data Perusahaan Created',
                'data'    => $status
            ]);
        }
        //failed save to DB
        return response()->json([
            'success' => false,
            'message' => 'Data Status Failed to Save',
        ]);
    }
    //UPDATE
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status'      => 'required',
        ]);

        //response error
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        //find data by ID
        $status = Status::findOrFail($id);
        if ($status) {
            //update level akses
            $status->update([
                'status' => $request->status,
            ]);
            return response()->json([
                'success' => true,
                'messaage' => 'Data Saved',
                'data' => $status
            ]);
            //respnse json
            return response()->json([
                'success' => 'Failed to Save Data Status'
            ]);
        }
    }

    //DESTROY
    public function destroy($id)
    {
        $status = Status::findOrfail($id);

        if ($status) {
            $status->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data status Deleted'
            ]);
        }

        //data people not found
        return response()->json([
            'success' => false,
            'message' => 'Status not found'
        ]);
    }
}
