<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Status;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $status = null;
    protected $error = null;
    protected $data = null;

    public function index()
    {
    //get data from table user
    // $admins = Admin::latest()->get();

    // //make response json
    // return response()->json([
    //     'success' => true,
    //     'message' => 'List Data Admin',
    //     'data' => $admins
    // ]);
    
        //return with response JSON
        return response()->json([
                'success' => true,
                'message' => 'Data Profile',
                'data'    => Auth::user(),
            ], 200);
    
       
    }

    //SHOW
    public function show($id)
    {
        $admin = Admin::findOrfail($id);

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Admin',
            'data' => $admin
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'username' => 'required',
            'telp' => 'required',
            'foto' => 'required|mimes:jpg,png,jpeg|max:15000',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()
                ],
                401
            );
        }
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->password = bcrypt($request->password);
        $admin->telp = $request->telp;

        if ($request->foto && $request->foto->isValid()) {
            $file_name = $request->foto->getClientOriginalName();
            $request->foto->move(public_path('admin'), $file_name);
            $path = $file_name;
            $admin->foto = $path;
        }

        try {
            $success['token'] =  $admin->createToken('nApp')->accessToken;
            $success['name'] =  $admin;
            $admin->save();
            $this->data = $admin;
            $this->status = "success";
        } catch (QueryException $e) {
            $this->status = "failed";
            $this->error = $e;
        }

        return response()->json(['success' => $success]);
    }

    //UPDATE
    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
            'telp' => 'required',
            'foto' => 'required|mimes:jpg,png,jpeg'

        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json([
                'status' => 'failed',
                'message' => $error,
                'data' => []
            ]);
        }
        $admin = Admin::whereId(auth()->guard('admin-api')->user()->id)->first();
        //$date = Carbon::now()->toDateString();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->username = $request->username;
        $admin->password = Hash::make($request->password);
        $admin->telp = $request->telp;
        if ($request->foto && $request->foto->isValid()) {
            $file_name = $request->foto->getClientOriginalName();
            $request->foto->move(public_path('admin'), $file_name);
            $path = $file_name;
            $admin->foto = $path;
        }

        $admin->update();
        return response()->json([
            'status' => 'success',
            'data' => $admin,
            'messagge' => 'Data Admin Updated'
        ]);
    }
   
    //DELETE
    public function destroy($id)
    {
        //find post by ID
        $admin = Admin::findOrfail($id);

        if ($admin) {

            //delete post
            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Admin Deleted',
            ], 200);
        }

        //data post not found
        return response()->json([
            'success' => false,
            'message' => 'Data Admin Not Found',
        ], 404);
    }

   public function indexStatus(){
        //data status
        $statuses = Status::latest()->get();

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'List Data Status',
            'data' => $statuses
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

        $admin = Admin::find($id);
        $admin->update([
            'password'  => Hash::make($request->password)
        ]);

        //return with response JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Password Berhasil Diupdate!',
            'data'    => $admin,
        ], 201);
    }
}



