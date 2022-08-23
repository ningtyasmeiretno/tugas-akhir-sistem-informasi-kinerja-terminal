<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use App\Models\Pimpinan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //current admin
public function currentAdmin(Request $request){
    $admin = Admin::find($request->user());
    return response()->json($admin);
}

//current operator
public function currentOperator(Request $request)
{
    $user = User::find($request->user());
    return response()->json($user);
}

//current dinas
public function currentPimpinan(Request $request)
{
    $user = Pimpinan::find($request->user());
    return response()->json($user);
}
    // USER
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }

        $success =  $user;
        $success['token'] =  $user->createToken('MyApp', ['user'])->accessToken;

        return response()->json([
            'success' => true,
            'data' => $success
        ]);
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        $removeToken = $request->user()->tokens()->delete();

        if ($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Success!',
            ]);
        }
    }
    // ADMIN
    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }

        $success =  $admin;
        $success['token'] =  $admin->createToken('MyApp', ['admin'])->accessToken;

        return response()->json([
            'success' => true,
            'data' => $success
        ]);
    }

    //login dinas
    public function pimpinanLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $dinas = Pimpinan::where('email', $request->email)->first();

        if (!$dinas || !Hash::check($request->password, $dinas->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Success!',
            'data'    => $dinas,
            'token'   => $dinas->createToken('authToken')->accessToken
        ]);
    }

    //login operator
    
    public function operatorLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $operator = Pimpinan::where('email', $request->email)->first();

        if (!$operator || !Hash::check($request->password, $operator->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Success!',
            'data'    => $operator,
            'token'   => $operator->createToken('authToken')->accessToken
        ]);
    }



    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    
}
