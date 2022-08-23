<?php

namespace App\Http\Controllers;

use App\Models\Pimpinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required,',
            'telp'      => 'required',
            'username'  => 'required',
            'password'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $pimpinan = Pimpinan::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'telp'     => $request->telp,
            'username'     => $request->username,
            'password'  => Hash::make($request->password),
            
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Register Success!',
            'data'    => $pimpinan
        ]);
    }
}
