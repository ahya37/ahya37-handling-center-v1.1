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

Route::post('/listdata', 'UmrahController@listData');


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
Route::get('/getdatapetugas/umrah/{month}/{year}', 'PetugasController@getDataPetugasUmrahByMonth');

Route::post('/add/form/pembimbing', 'AktivitasUmrahController@addElementFormPembimbing');
Route::post('/add/form/asistenpembimbing', 'AktivitasUmrahController@addFormAsistenPembimbing');
Route::post('/add/form/muthowwif', 'MuthowwifController@addFormMuthowiif');

Route::get('/getdatamuthowwif', 'MuthowwifController@getDataMuthowwif');
Route::get('/getdatamuthowwif/umrah/{month}/{year}', 'MuthowwifController@getMuthowwifUmrahByMonth');

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

    #get kuisioner from element
    Route::post('/getkuisioner', 'AktivitasUmrahController@getDataOptionKuisionerForElement');

    #get pembimbing from element
    Route::post('/getpembimbing', 'AktivitasUmrahController@getDataOptionPembimbingForElement');

    #get muthowwif from element
    Route::post('/getmuthowwif', 'MuthowwifController@getDataOptionMuthowwifForElement');

    #get sop from element
    Route::post('/getsop', 'AktivitasUmrahController@getDataOptionSopForElement');
    Route::post('/getsopmuthowwif', 'MuthowwifController@getDataOptionSopForElement');

    Route::post('/kuisioner/dashboard/detail/listdata/{id}','DashboardController@listDetailKuisionerByDashboard');
	
	Route::get('/roomlist', 'UmhajController@getRoomlist');

    // PETUGAS
    Route::post('/petugas/dt/aktivitas', 'AktivitasUmrahPetugascontroller@dataTableListData');

    // Muthoowwif
    Route::post('/dt/muthowwif/list', 'MuthowwifController@dataTableListData');

    // ANALYTIC
    Route::post('/grade/pembimbing', 'DashboardController@dataGradeByPembimbing');

    // merge data kuisioner
    Route::post('mergedatakuisioner', 'TestController@mergeDataKuisioner');

});

Route::post('/v1/login','Api\UserController@login')->name('login');

Route::middleware('auth:api')->get('/v1/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->prefix('v1')->group(function(){
    Route::post('/profile/update','Api\UserController@updateProfile');
    Route::post('/logout','Api\UserController@logout')->name('logout');

    // jadwal umrah
    Route::get('/jadwalumrah','Api\JadwalUmrahController@listJadwalUmrah');
    Route::get('/jadwalumrah/listjudul','Api\JadwalUmrahController@listJudulBySOP');
    Route::get('/jadwalumrah/judul/sop','Api\JadwalUmrahController@listSopByJudul');

    // create tugas
    // Route::post('/create/tugas/sop','Api\JadwalUmrahController@createTugasSop');
    Route::post('/create/tugas/sop','Api\JadwalUmrahController@createTugasSopWithOutResizeImage');

    Route::post('/createtugasmarketing', 'Api\JadwalUmrahController@saveJumlahPotensialJamaahByPembimbing');
	Route::post('/catatan/sop/store/', 'Api\JadwalUmrahController@storeCatatanEvaluasi');

    // Histori
    Route::get('/history', 'Api\JadwalUmrahController@historyTugasJadwalUmrah');
    Route::get('/history/detail/{id}', 'Api\JadwalUmrahController@pageDetaiHistoryTugasByPembimbing');

    // Panduan
    Route::get('/panduan', 'Api\PanduanController@listPanduan');
    Route::get('/panduan', 'Api\PanduanController@readPanduan');



});



