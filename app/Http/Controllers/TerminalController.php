<?php

namespace App\Http\Controllers;

use App\Models\JenisAngkutan;
use Illuminate\Http\Request;
use App\Models\Terminal;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TerminalController extends Controller
{
    protected $status = null;
    protected $error = null;
    protected $data = null;
    public function index()
    {
        return Terminal::when(request('search'), function ($query) {
            $query->where('nama_terminal', 'like', '%' . request('search') . '%');
        })->with('get_kota')->with('get_upt')->paginate(20);
        // try {
        //     $terminal = DB::table("terminals")
        //     ->select(
        //         "terminals.nama_terminal",
        //         "kotas.kota",
        //         "terminals.alamat",
        //         "terminals.foto",
        //         "terminals.telp"

        //     )
        //         ->join("kotas", "terminals.id_kota", "=", "kotas.id")
        //         ->get();

            
        // } catch (QueryException $e) {
        //     $this->status = "failed";
        //     $this->error = $e;
        // }
        // //get data from table user

        // //make response json
        // return response()->json([
        //     'success' => true,
        //     'message' => 'List Data Terminal',
        //     'data' => $terminal
        // ]);
    }
    public function dropdown()
    {
        //get data from table user
        $terminals = Terminal::latest()->get();

        //make response json
        return response()->json([
            'success' => true,
            'message' => 'List Data Kota',
            'data' => $terminals
        ]);
    }
    //SHOW
    public function show($id)
    {
        $terminal = Terminal::with('get_kota')->with('get_upt')->findOrfail($id);

        //make response JSON
        return response()->json([
            'success' => true,
            'message' => 'Detail Data',
            'data'    => $terminal 
        ], 200);
    }
    //STORE
    
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'nama_terminal' => 'required',
            'id_kota' => 'required',
            'id_upt' => 'required',
            'alamat' => 'required',
            'foto' => 'required|mimes:jpg,png,jpeg|max:15000',
            'telp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'error' => $validator->errors()
                ],
                401
            );
        }
        $terminal = new Terminal();
        $terminal->nama_terminal = $request->nama_terminal;
        $terminal->id_kota = $request->id_kota;
        $terminal->id_upt = $request->id_upt;
        $terminal->alamat = $request->alamat;
        $terminal->telp = $request->telp;

        if ($request->foto && $request->foto->isValid()) {
            $file_name = $request->foto->getClientOriginalName();
            $request->foto->move(public_path('terminal'), $file_name);
            $path = $file_name;
            $terminal->foto = $path;
        }

        try {
            // $success['token'] =  $admin->createToken('nApp')->accessToken;
            $success['name'] =  $terminal;
            $terminal->save();
            $this->data = $terminal;
            $this->status = "success";
        } catch (QueryException $e) {
            $this->status = "failed";
            $this->error = $e;
        }

        return response()->json(['success' => $success]);
    }


    //UPDATE
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'nama_terminal' => 'required',
            'id_kota' => 'required',
            'alamat' => 'required',
            'id_upt' => 'required',
            'foto' => 'required|mimes:jpg,png,jpeg|max:15000',
            'telp' => 'required',

        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json([
                'status' => 'failed',
                'message' => $error,
                'data' => []
            ]);
        }
        $terminal = Terminal::find($id);
        //$date = Carbon::now()->toDateString();
        $terminal->nama_terminal = $request->nama_terminal;
        $terminal->id_kota = $request->id_kota;
        $terminal->id_upt = $request->id_upt;
        $terminal->alamat = $request->alamat;
        $terminal->telp = $request->telp;

        if ($request->foto && $request->foto->isValid()) {
            $file_name = $request->foto->getClientOriginalName();
            $request->foto->move(public_path('terminal'), $file_name);
            $path = $file_name;
            $terminal->foto = $path;
        }

        $terminal->update();
        return response()->json([
            'status' => 'success',
            'data' => $terminal,
            'messagge' => 'Data Terminal Updated'
        ]);
    }
   
    //DESTROY
    public function destroy($id)
    {
        $terminal = Terminal::findOrfail($id);

        if ($terminal) {
            $terminal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data Terminal Deleted'
            ]);
        }

        //data termnal not found
        return response()->json([
            'success' => false,
            'message' => 'Terminal not found'
        ]);
    }
    //hitung jumlah terminal
    // public function countTerminal(){
    //     $terminal = DB::table('terminals')->count();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'List Data Terminal',
    //         'data' => $terminal
    //     ]);
    // }
    public function countData()
    {
        $count = Terminal::count();
        
        return response()->json([
            "status" => true,
            "data" => $count
        ]);
    }
}
