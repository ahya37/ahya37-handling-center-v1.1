<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
// UMHAJ ONLY EXTEND DB


// END UMHAJ ONLY EXTEND DB


Route::get('/getdataumrah', 'UmrahController@getDataUmrah');
Route::post('/getcekasisten', 'UmrahController@getDataAsistenSopByIdUmrah');
Route::get('/getdataumrah/{pembimbingId}', 'UmrahController@getDataTourcodeByPembimbing');
Route::get('/getdataumrahbymonth/{month}/{year}', 'UmrahController@getDataUmrahByTourcode');
Route::get('/getdatapembimbing', 'PembimbingController@getDataPembimbing');
Route::get('/getdatapembimbing/umrah/{month}/{year}', 'PembimbingController@getDataPembimbingUmrahByMonth');
Route::post('/add/form/essay', 'KuisionerController@addElementFormEssayJawaban');
Route::get('getpilihan', 'KuisionerController@getDataJawabanPilihan'); 

Route::get('testlisttugas/{id}', 'TestController@testListTugas'); 

Route::post('searchpanduan', 'PanduanController@searchPanduan');

Route::get('/getdatapetugas', 'PetugasController@getDataPetugas');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['as' => 'api.'], function () {
    Route::post('/grafik/tugas', 'AktivitasUmrahController@grafikCardTugas');
    Route::post('/chart/grafik/tugas', 'AktivitasUmrahController@grafikChartTugas');
    Route::post('/grafik/kuisioner','DashboardController@grafikKuisioner');

    Route::post('/createTugasmarketing', 'AktivitasUmrahController@saveJumlahPotensialJamaahByPembimbing');

    // API DATA TABLEs
    Route::post('/umrah/dt/umrah/tourcode', 'UmrahController@umrahDataTable');
    Route::post('/dt/aktivitas', 'AktivitasUmrahController@dataTableListData');
    Route::post('/umrah/count', 'UmrahController@countJumlahJamaahByUmrahId');

    Route::get('/getasisten', 'AktivitasUmrahController@addElementFormAsistenPembimbing');

    Route::post('/kuisioner/dashboard/detail/listdata/{id}','DashboardController@listDetailKuisionerByDashboard');
	
	Route::get('/roomlist', 'UmhajController@getRoomlist');


});



