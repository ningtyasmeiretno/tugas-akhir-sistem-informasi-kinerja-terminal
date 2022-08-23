<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TerminalController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\JenisAngkutanController;
use App\Models\UnitPelaksana;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::middleware('auth:admin-api')->get('/user',[LoginController::class, 'currentUser'] );

// Route::middleware('auth:admin-api')->get('/currentAdmin', function (Request $request) {
//     return $request->user();
// });

//current admin
Route::middleware('auth:admin-api')->get('currentAdmin', [LoginController::class, 'currentAdmin']);

//current operator
Route::middleware('auth:user-api')->get('currentOperator', [LoginController::class, 'currentOperator']);

//current dinas
Route::middleware('auth:pimpinan-api')->get('currentPimpinan', [LoginController::class, 'currentPimpinan']);

Route::post('user/login', [LoginController::class, 'userLogin'])->name('userLogin');

Route::post('/admin/login', [LoginController::class, 'loginAdmin'])->name('loginAdmin');

Route::post('/admin/register', [RegisterController::class, 'adminRegister'])->name('adminRegister');

Route::post('/pimpinan/login', [LoginController::class, 'pimpinanLogin'])->name('pimpinanLogin');

Route::post('/pimpinan/register', [RegisterController::class, 'pimpinanRegister'])->name('pimpinanRegister');

//operator login
Route::post('/operator/login', [LoginController::class, 'operatorLogin'])->name('operatorLogin');

Route::post('/operator/register', [RegisterController::class, 'operatorRegister'])->name('operatorRegister');


Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin-api', 'scopes:admin']], function () {
    // authenticated staff routes here 

    //getAdmin
    Route::get('/adminprofile', [AdminController::class, 'index']);
    Route::post('/adminreset/{id}', [AdminController::class, 'resetPassword']);

    


    Route::get('dashboard', [LoginController::class, 'adminDashboard']);

    //Route::get('/status', [AdminController::class, 'indexStatus']);
    //get data  status
    Route::get('/status', [StatusController::class, 'index']);
    Route::get('/status/{id}', [StatusController::class, 'show']);
    Route::post('/status', [StatusController::class, 'store']);
    Route::post('/status/{id}', [StatusController::class, 'update']);
    Route::delete('/status/{id}', [StatusController::class, 'destroy']);

    //get data user operator
    Route::get('/operator', [OperatorController::class, 'index']);
    Route::get('/operator/{id}', [OperatorController::class, 'show']);
    Route::post('/operator', [OperatorController::class, 'store']);
    Route::post('/operator/{id}', [OperatorController::class, 'update']);
    Route::delete('/operator/{id}', [OperatorController::class, 'destroy']);
    Route::post('/operator/resetpassword/{id}', [OperatorController::class, 'resetPassword']);
    Route::get('/operators', [OperatorController::class, 'countData'
    ]);


    //get data user dinas
    Route::get('/pimpinan', [PimpinanController::class, 'index']);
    Route::get('/pimpinan/{id}', [PimpinanController::class, 'show']);
    Route::post('/pimpinan', [PimpinanController::class, 'store']);
    Route::post('/pimpinan/{id}', [PimpinanController::class, 'update']);
    Route::delete('/pimpinan/{id}', [PimpinanController::class, 'destroy']);
    Route::post('/pimpinan/resetpassword/{id}', [PimpinanController::class, 'resetPassword']);
    Route::get('/pimpinans', [PimpinanController::class, 'countData'
    ]);


    //data Kota
    Route::get('/kota', [KotaController::class, 'index']);
    Route::get('/listkota', [KotaController::class, 'dropdown']);
    Route::get('/kota/{id}', [KotaController::class, 'show']);
    Route::post('/kota', [KotaController::class, 'store']);
    Route::post('/kota/{id}', [KotaController::class, 'update']);
    Route::delete('/kota/{id}', [KotaController::class, 'destroy']);

    //route UPT
    Route::get('/unitpelaksana', [UnitPelaksanaController::class, 'index']);
    Route::get('/unitpelaksana/{id}', [UnitPelaksanaController::class, 'show']);
    Route::post('/unitpelaksana', [UnitPelaksanaController::class, 'store']);
    Route::post('/unitpelaksana/{id}', [UnitPelaksanaController::class, 'update']);
    Route::delete('/unitpelaksana/{id}', [UnitPelaksanaController::class, 'destroy']);
    Route::get('/unitpelaksanas', [UnitPelaksanaController::class, 'countData']);


    //data Terminal
    Route::get('/terminal', [TerminalController::class, 'index']);
    Route::get('/listterminal', [TerminalController::class, 'dropdown']);
    Route::get('/terminal/{id}', [TerminalController::class, 'show']);
    Route::post('/terminal', [TerminalController::class, 'store']);
    Route::post('/terminal/{id}', [TerminalController::class, 'update']);
    Route::delete('/terminal/{id}', [TerminalController::class, 'destroy']);
    Route::get('/terminals', [TerminalController::class, 'countData']);

    //data jenis angkutan
    Route::get('/angkutan', [JenisAngkutanController::class, 'index']);
    Route::get('/angkutan/{id}', [JenisAngkutanController::class, 'show']);
    Route::post('/angkutan', [JenisAngkutanController::class, 'store']);
    Route::post('/angkutan/{id}', [JenisAngkutanController::class, 'update']);
    Route::delete('/angkutan/{id}', [JenisAngkutanController::class, 'destroy']);

    //route perusahaan
    Route::get('/perusahaan', [PerusahaanController::class, 'index']);
    Route::get('/perusahaan/{id}', [PerusahaanController::class, 'show']);
    Route::get('/listperusahaan', [PerusahaanController::class, 'dropdown']);
    Route::post('/perusahaan', [PerusahaanController::class, 'store']);
    Route::post('/perusahaan/{id}', [PerusahaanController::class, 'update']);
    Route::delete('/perusahaan/{id}', [PerusahaanController::class, 'destroy']);
    Route::get('/perusahaans', [PerusahaanController::class, 'countData']);

    //route perusahaan
    Route::get('/kendaraan', [KendaraanController::class, 'index']);
    Route::get('/kendaraan/{id}', [KendaraanController::class, 'show']);
    Route::post('/kendaraan', [KendaraanController::class, 'store']);
    Route::post('/kendaraan/{id}', [KendaraanController::class, 'update']);
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);

    //kedatangan
    Route::get('/allkedatangan', [KedatanganController::class, 'index']);

    //keberangkatan
    Route::get('/allkeberangkatan', [KeberangkatanController::class, 'index']);

    
});


//Scope OPERATOR
Route::group(['prefix' => 'user', 'middleware' => ['auth:user-api', 'scopes:user']],
    function () {
        // authenticated staff routes here 
        //Route::get('dashboard', [OperatorController::class, 'operatorDashboard']);
    // Route::get('/operatorprofile', [OperatorController::class, 'index']);
        //kedatangan

    Route::post('/resetpassword/{id}', [OperatorController::class, 'resetPassword']);

    Route::get('/allkedatangan', [KedatanganController::class, 'index']);
    // Route::get('/kedatangan/{id}', [KedatanganController::class, 'show']);
    Route::middleware('auth:user-api')->get('/kedatangan', [KedatanganController::class, 'show']);
    Route::post('/kedatangan', [KedatanganController::class, 'store']);
    Route::post('/kedatangan/{id}', [KedatanganController::class, 'update']);
    Route::delete('/kedatangan/{id}', [KedatanganController::class, 'destroy']);

        //keberangkatan
    Route::get('/allkeberangkatan', [KeberangkatanController::class, 'index']);
    // Route::get('/keberangkatan/{id}', [KeberangkatanController::class, 'show']);
    Route::middleware('auth:user-api')->get('/keberangkatan', [KeberangkatanController::class, 'show']);
    Route::post('/keberangkatan', [KeberangkatanController::class, 'store']);
    Route::post('/keberangkatan/{id}', [KeberangkatanController::class, 'update']);
    Route::delete('/keberangkatan/{id}', [KeberangkatanController::class, 'destroy']);

    Route::get('/kendaraan', [KendaraanController::class, 'index']);
    Route::get('/kendaraan/{id}', [KendaraanController::class, 'show']);
    Route::post('/kendaraan', [KendaraanController::class, 'store']);
    Route::post('/kendaraan/{id}', [KendaraanController::class, 'update']);
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);

    //terminal
    Route::get('/terminal', [TerminalController::class, 'index']);
    Route::get('/listterminal', [TerminalController::class, 'dropdown']);
    Route::get('/terminal/{id}', [TerminalController::class, 'show']);
    Route::post('/terminal', [TerminalController::class, 'store']);
    Route::post('/terminal/{id}', [TerminalController::class, 'update']);
    Route::delete('/terminal/{id}', [TerminalController::class, 'destroy']);
    Route::get('/terminals', [TerminalController::class, 'countData']);

    //route perusahaan
    Route::get('/perusahaan', [PerusahaanController::class, 'index']);
    Route::get('/perusahaan/{id}', [PerusahaanController::class, 'show']);
    Route::post('/perusahaan', [PerusahaanController::class, 'store']);
    Route::post('/perusahaan/{id}', [PerusahaanController::class, 'update']);
    Route::delete('/perusahaan/{id}', [PerusahaanController::class, 'destroy']);
    Route::get('/perusahaans', [PerusahaanController::class, 'countData']);

        //UPT
    Route::get('/unitpelaksanas', [UnitPelaksanaController::class, 'countData']);

    
});

//Scope DINAS
Route::group(['prefix' => 'pimpinan', 'middleware' => ['auth:pimpinan-api']],
    function () {
        // authenticated staff routes here 
        Route::get('dashboard', [PimpinanController::class, 'pimpinanDashboard']);
    // Route::get('/operatorprofile', [OperatorController::class, 'index']);
        //kedatangan

    Route::post('/resetpassword/{id}', [PimpinanController::class, 'resetPassword']);

    Route::get('/allkedatangan', [KedatanganController::class, 'index']);
    Route::get('/countkedatangan', [KedatanganController::class, 'countData']);

    //Route::get('/kedatangan/export', [KedatanganController::class, 'kedatanganExport']);

        //keberangkatan
    Route::get('/allkeberangkatan', [KeberangkatanController::class, 'index']);
    Route::get('/keberangkatans', [KeberangkatanController::class, 'countData']);

    

    Route::get('/kendaraan', [KendaraanController::class, 'index']);
    Route::get('/kendaraan/{id}', [KendaraanController::class, 'show']);
    Route::post('/kendaraan', [KendaraanController::class, 'store']);
    Route::post('/kendaraan/{id}', [KendaraanController::class, 'update']);
    Route::delete('/kendaraan/{id}', [KendaraanController::class, 'destroy']);

    //terminal
    Route::get('/terminal', [TerminalController::class, 'index']);
    Route::get('/terminal/{id}', [TerminalController::class, 'show']);
    Route::post('/terminal', [TerminalController::class, 'store']);
    Route::post('/terminal/{id}', [TerminalController::class, 'update']);
    Route::delete('/terminal/{id}', [TerminalController::class, 'destroy']);
    Route::get('/terminals', [TerminalController::class, 'countData']);

    //route perusahaan
    Route::get('/perusahaan', [PerusahaanController::class, 'index']);
    Route::get('/perusahaan/{id}', [PerusahaanController::class, 'show']);
    Route::post('/perusahaan', [PerusahaanController::class, 'store']);
    Route::post('/perusahaan/{id}', [PerusahaanController::class, 'update']);
    Route::delete('/perusahaan/{id}', [PerusahaanController::class, 'destroy']);
    Route::get('/perusahaans', [PerusahaanController::class, 'countData']);

        //UPT
    Route::get('/unitpelaksanas', [UnitPelaksanaController::class, 'countData']);
    Route::get('/kota', [KotaController::class, 'index']);

    Route::get('/terminal', [TerminalController::class, 'index']);
    Route::get('/unitpelaksana', [UnitPelaksanaController::class, 'index']);
    Route::get('/angkutan', [JenisAngkutanController::class, 'index']);

     Route::get('/operator', [OperatorController::class, 'index']);
    Route::get('/operator/{id}', [OperatorController::class, 'show']);
    Route::post('/operator', [OperatorController::class, 'store']);
    Route::post('/operator/{id}', [OperatorController::class, 'update']);
    Route::delete('/operator/{id}', [OperatorController::class, 'destroy']);
});

Route::post('/register', [RegisterController::class, 'register']);
// Route::post('/admin/register', [RegisterAdminController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');

Route::get('/kedatangan/export', [KedatanganController::class, 'kedatanganExport']);

Route::get('/keberangkatan/export', [KeberangkatanController::class, 'keberangkatanExport']);



//get data user admin
// Route::get('/admin', [AdminController::class, 'index']);
Route::get('/admin/{id}', [AdminController::class, 'show']);
// Route::post('/admin', [AdminController::class, 'store']);
Route::post('/admin/{id}', [AdminController::class, 'update']);
// Route::delete('/admin/{id}', [AdminController::class, 'delete']);


