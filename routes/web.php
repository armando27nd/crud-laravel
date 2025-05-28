<?php

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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes([
    'register' => true,
    'reset' => false
]);

Route::get('/home', 'HomeController@index')->name('home');

// CRUD KATEGORI
Route::get('/kategori', 'HomeController@kategori');
Route::get('/kategori/tambah', 'HomeController@kategori_tambah');
Route::post('/kategori/aksi', 'HomeController@kategori_aksi');
Route::get('/kategori/edit/{id}', 'HomeController@kategori_edit');
Route::put('/kategori/update/{id}', 'HomeController@kategori_update');
Route::get('/kategori/hapus/{id}', 'HomeController@kategori_hapus');

// CRUD ARSIP
Route::get('/arsip','HomeController@arsip');
Route::get('/arsip/tambah','HomeController@arsip_tambah');
Route::post('/arsip/aksi','HomeController@arsip_aksi');
Route::get('/arsip/edit/{id}','HomeController@arsip_edit');
Route::put('/arsip/update/{id}','HomeController@arsip_update');
Route::get('/arsip/hapus/{id}','HomeController@arsip_hapus');

// Lihat PDF File
Route::get('/arsip_file/{filename}','HomeController@arsip_file');

// Route LAPORAN
Route::get('/laporan', 'HomeController@laporan');
Route::get('/laporan/hasil', 'HomeController@laporan_hasil');
Route::get('/laporan/print', 'HomeController@laporan_print');
Route::get('/laporan/excel', 'HomeController@laporan_excel');
