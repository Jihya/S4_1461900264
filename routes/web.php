<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { 
    return view('0264_home');
});
Route::get('/Tampil', function () { 
    return view('0264_Tampil');
});


Route::get('/tampil/pdf', [DataController::class,'generate']);
Route::get('/Tambah', [DataController::class, 'create']);
Route::post('/store', [DataController::class, 'store']);
Route::get('/Edit/{id}', [DataController::class, 'edit']);
Route::post('/update', [DataController::class, 'update']);
Route::get('/hapus/{id}', [DataController::class, 'destroy']);
Route::get('/tampil', [DataController::class, 'index']);
Route::post('/pasien/import_excel', [DataController::class, 'import']);


Route::resource('/Tampil', DataController::class);