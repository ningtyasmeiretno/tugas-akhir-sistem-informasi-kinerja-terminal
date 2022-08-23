<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Pimpinan;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    //REGISTRASI OPERATOR
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'telp'      => 'required',
            'username'  => 'required',
            'password'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'telp'      => $request->telp,
            'username'  => $request->username,
            'password'  => Hash::make($request->password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Register Success!',
            'data'    => $user
        ]);
    }


    

    public function adminRegister(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'email' => 'required|email',
            'username' => 'required',
            'password' => 'required',
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
    //REGISTRASI DINAS
    public function pimpinanRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'telp' => 'required',
            'username' => 'required',
            'password' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = Pimpinan::create($input);
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user;

        return response()->json(['success' => $success]);
    }

    //get data operator (tabel user)
    public function index()
    {
        //get data from table user
        $users = User::latest()->get();

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'List Data Operator',
            'data' => $users
        ]);

        $admins = Admin::latest()->get();

        //make response json
        return response()->json([
                'success' => true,
                'message' => 'List Data Admin',
                'data' => $admins
            ]);
    }

    //SHOW
    public function show($id)
    {
        $user = User::findOrfail($id);

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Operator',
            'data' => $user
        ]);

        $admin = Admin::findOrfail($id);

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'Detail Data Admin',
            'data' => $admin
        ]);
    }


}
