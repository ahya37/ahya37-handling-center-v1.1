<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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


Route::get('/', 'HomeController@index')->name('/');
Route::get('/place/data', 'DataController@places')->name('place.data'); // DATA TABLE CONTROLLER
Route::post('/tugas/delete', 'TugasController@delete');

Route::get('/kuisioner/view/{url}', 'KuisionerUmrahController@view')->name('kuisioner.umrah.view');
Route::post('/kuisioner/save/{kuisionerumrah_id}/{umrah_id}', 'KuisionerUmrahController@saveKuisionerUmrah')->name('kuisioner.umrah.save');
Route::get('/kuisioner/success', 'KuisionerUmrahController@kuisionerSuccess')->name('kuisioner.umrah.success');

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('dashboard')->group(function () {
    Route::get('/kuisioner','DashboardController@dashboardKuisioner')->name('dashbaord.kuisioner');
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::group(['middleware' => ['auth']], function () {
    Route::resource('places', 'PlaceController');

    Route::group(['prefix' =>  'tugas'], function(){
        Route::get('/', 'TugasController@index')->name('tugas.index');
        Route::get('/listdata', 'TugasController@listData');
        Route::get('/create', 'TugasController@create')->name('tugas.create');
        Route::post('/store', 'TugasController@store')->name('tugas.store');
        Route::post('/delete', 'TugasController@delete')->name('tugas.delete');
        Route::post('/delete/sop', 'TugasController@deleteSop');
        Route::get('/edit/{id}', 'TugasController@edit')->name('tugas.edit');
        Route::post('/update/{id}', 'TugasController@update')->name('tugas.update');
        Route::post('/tukarnomor/{id}', 'TugasController@tukarNomor')->name('tugas.tukarnomor');
        Route::post('/settinguploadfile', 'TugasController@settingUploadFileSop');

        // SOP
        Route::get('/listdatasop', 'TugasController@listDataSop');
        Route::get('/sop/detail/{id}', 'TugasController@detailSop')->name('tugas.sop.detail');
        Route::post('/sop/update', 'TugasController@updateSop');
        Route::post('/sop/judul/save', 'TugasController@saveJudulTugas');
        Route::post('/sop/judul/update', 'TugasController@updateJudulTugas');
        Route::get('/sop/count/alphabet/{id}', 'TugasController@countAlphabetMasterJudul');
        Route::get('/sop/count/tugas/{id}', 'TugasController@countNumberMasteTugasByJudul');
        Route::post('/sop/judul/tugas/save', 'TugasController@saveTugasByJudul');
        Route::post('/sop/judul/tugas/update', 'TugasController@updateTugas');

        // SOP PETUGAS
        Route::get('/create/petugas', 'TugasController@sopPetugas')->name('tugas.create.petugas');
        Route::post('/petugas/store', 'TugasController@storeSopPetugas')->name('sop.petugas.store');
        Route::get('/listdatasoppetugas', 'TugasController@listDataSopPetugas');
        Route::get('/sop/petugas/detail/{id}', 'TugasController@detailSopPetugas')->name('tugas.sop.petugas.detail');
        Route::post('/sop/petugas/update', 'TugasController@updateSopPetugas');
        Route::get('/sop/petugas/count/alphabet/{id}', 'TugasController@countAlphabetMasterJudulSopPetugas');
        Route::post('/sop/petugas/judul/save', 'TugasController@saveJudulTugasSopPetugas');
        Route::post('/sop/petugas/judul/update', 'TugasController@updateJudulTugasPetugas');
        Route::get('/sop/petugas/count/tugas/{id}', 'TugasController@countNumberMasteTugasByJudulSopPetugas');
        Route::post('/sop/petugas/judul/tugas/save', 'TugasController@saveTugasByJudulSopPetugas');
        Route::post('/sop/petugas/judul/tugas/update', 'TugasController@updateTugasPetugas');
        Route::post('/sop/petugas/delete', 'TugasController@deleteTugasForPetugas');
        Route::post('/sop/petugas/settinguploadfile', 'TugasController@settingUploadFileSopPetugas');


        // EXPORT
        Route::get('/listdatasop/export/pdf/{id}', 'TugasController@exportDataSopPDF')->name('sop-export-pdf');
        Route::get('/listdatasop/export/excel/{id}', 'TugasController@exportDataSopExcel')->name('sop-export-excel');
        
    });

    Route::group(['prefix' =>  'panduan'], function(){
        Route::get('/', 'PanduanController@index')->name('panduan.index');
        Route::post('/save', 'PanduanController@store')->name('panduan.store');
        Route::get('/create', 'PanduanController@create')->name('panduan.create');
        Route::get('/listdata', 'PanduanController@listData'); 
        Route::get('/edit/{id}', 'PanduanController@edit')->name('panduan.edit'); 
        Route::post('/update/{id}', 'PanduanController@update')->name('panduan.update');
		Route::post('/delete', 'PanduanController@deletePanduan');

    });


    Route::group(['prefix' =>  'umrah'], function(){
        Route::get('/', 'UmrahController@index')->name('umrah.index');
        Route::get('/create', 'UmrahController@create')->name('umrah.create');
        Route::post('/store', 'UmrahController@store')->name('umrah.store');
        Route::post('/listdata', 'UmrahController@listData');
        Route::get('/edit/{id}', 'UmrahController@edit')->name('umrah.edit');
        Route::post('/update/{id}', 'UmrahController@update')->name('umrah.update');
        Route::post('/destroy', 'UmrahController@destroy');
        Route::get('/show/kuisioner/{id}', 'UmrahController@showKuisionerByUmrahId')->name('umrah.kuisioner.show');
        Route::get('/result/kuisioner/{id}', 'UmrahController@hasilKuisionerByUmrahId')->name('umrah.kuisioner.result');
        Route::get('/show/kuisioner/responden/{id}', 'UmrahController@detailResponden')->name('umrah.kuisioner.respondendetail');    

        Route::get('/tag/orange/', 'OperasionalController@indexTagOrange')->name('tagorange.index');    
        Route::get('/tag/orange/create', 'OperasionalController@createGroupTageOrange')->name('tagorange.create');    
        Route::post('/tag/orange/store', 'OperasionalController@storeTagOrange')->name('tagorange.store');    
        Route::get('/tag/orange/listdata', 'OperasionalController@listDataTagOrange'); 
        Route::get('/tag/orange/listdetaildata/{id}', 'OperasionalController@listDataDetailTagOrange'); 
        Route::get('/tag/orange/detail/{id}', 'OperasionalController@detailGroup')->name('tagorange.detail'); 
        Route::get('/tag/orange/addjamaah/{id}', 'OperasionalController@addJamaah')->name('tagorange.addjamaah'); 
        Route::post('/tag/orange/addjamaah/save/{id}', 'OperasionalController@storeDetailTagOrange')->name('tagorange.addjamaah.store'); 
		Route::post('/tag/orange/export/{id}', 'OperasionalController@exportTagOrange')->name('tagorange.export'); 
		Route::post('/tag/orange/update/{id}', 'OperasionalController@updateTagOrange')->name('tagorange.update'); 
        Route::post('/tag/orange/delete', 'OperasionalController@deleteTagOrange');   
		
		Route::get('/tag/orange/jamaah/edit/{id}/{tagorangeid}', 'OperasionalController@editJamaahDetailTag')->name('tagorange.jamaah.edit');     
        Route::post('/tag/orange/jamaah/update/{id}/{tagorangeid}', 'OperasionalController@editDetailTagOrange')->name('tagorange.jamaah.update');    
        Route::post('/tag/orange/jamaah/delete', 'OperasionalController@deleteJamaahDetailTag');
		
		Route::post('/tag/orange/jamaah/import/{id}', 'OperasionalController@importJamaahTag')->name('tagorange.jamaah.import');  

		Route::post('/tag/orange/jamaah/uploadfoto', 'OperasionalController@uploadFotoJamaahByModal')->name('tagorange.jamaah.uploadfoto');   


    

    });
    
    Route::group(['prefix' => 'kuisioner'], function(){
        Route::get('/','KuisionerController@index')->name('kusioner.index');
        Route::get('/kategori/pilihan','KuisionerController@showPilihanJawabanKuisioner')->name('kusioner.kategoripilihan');
        Route::get('/create','KuisionerController@create')->name('kusioner.create');
        Route::post('/store','KuisionerController@store')->name('kusioner.store');
        Route::get('/listdata','KuisionerController@listData');
        Route::get('/edit/{id}','KuisionerController@edit')->name('kuisioner.edit');
        Route::post('update','KuisionerController@update')->name('kuisioner.update');
        Route::get('/show/{id}','KuisionerController@show')->name('kuisioner.show');
        Route::post('/delete','KuisionerController@deletePertanyaan');
        Route::post('/destroy','KuisionerController@destroy');
        Route::get('/pertanyaan/create/{id}','KuisionerController@createPertanyaan')->name('kuisioner.pertanyaan.create');
        Route::post('/pertanyaan/save/{kuisioner_id}','KuisionerController@savePilihanPertanyaan')->name('kuisioner.pertanyaan.save');
        Route::post('/pilihanjawaban/delete','KuisionerController@deletePilihanJawaban');


        // VIA SWEATALERT AJAX
        Route::post('/ajax/pertanyaan/save','KuisionerController@savePilihanJawabanPertanyaan');
        
        Route::post('/pilihan/update', 'KuisionerController@updatePertanyaan');
        Route::post('/pilihan/update', 'KuisionerController@updatePertanyaan');
        Route::get('/kategori/pilihan/jawaban', 'KuisionerController@kategoriPilihanJawaban');

        // KATEGORI PILIHAN JAWABAN
        Route::post('/kategori/pilihan/save','KuisionerController@saveKategoriPilihanJawaban');
        Route::post('/kategori/pilihan/update','KuisionerController@updateKategoriPilihan');
        Route::post('/kategori/pilihan/delete','KuisionerController@deleteKategoriPilihan');

        Route::get('/kategori/pilihan/listdata','KuisionerController@listDataKategoriPilihan');


        
    });

    Route::group(['prefix' =>  'pembimbing'], function(){
        Route::get('/', 'PembimbingController@index')->name('pembimbing.index');
        Route::get('/create', 'PembimbingController@create')->name('pembimbing.create');
        Route::post('/store', 'PembimbingController@store')->name('pembimbing.store');
        Route::post('/update/{id}', 'PembimbingController@update')->name('pembimbing.update');
        Route::get('/listdata', 'PembimbingController@listData');
        Route::post('/destroy', 'PembimbingController@destroy');
        Route::get('/edit/{id}', 'PembimbingController@edit')->name('pembimbing.edit');

    });

    Route::group(['prefix' =>  'petugas'], function(){
        Route::get('/create', 'PetugasController@create')->name('petugas.create');
        Route::post('/store', 'PetugasController@store')->name('petugas.store');
        Route::get('/listdata', 'PetugasController@listData');
        Route::get('/edit/{id}', 'PetugasController@edit')->name('petugas.edit');
        Route::post('/update/{id}', 'PetugasController@update')->name('petugas.update');
        Route::post('/destroy', 'PetugasController@destroy');

    });

    Route::group(['prefix' =>  'aktivitas'], function(){
        Route::get('/umrah', 'AktivitasUmrahController@index')->name('aktivitas.index');
        Route::get('/create', 'AktivitasUmrahController@create')->name('aktivitas.create');
        Route::post('/store', 'AktivitasUmrahController@store')->name('aktivitas.store');
        Route::get('/listdatajadwalumrah', 'AktivitasUmrahController@listDataJawdalUmrah');
        Route::get('/detail/{id}', 'AktivitasUmrahController@show')->name('aktivitas.detail');
        Route::post('/finish', 'AktivitasUmrahController@updateStatusAktifitasUmrah');
        Route::post('/delete/tugas', 'AktivitasUmrahController@deleteAktifitasUmrah');
        Route::get('/detailactivitas/{id}', 'AktivitasUmrahController@getDetailData');
        Route::get('/report/tugas/{id}', 'AktivitasUmrahController@downloadPdfByAktivitasUmrahId')->name('tugas.report');
        Route::get('/jadwal/tugas/me', 'AktivitasUmrahController@jadwalTugasKetuaPembimbing')->name('aktivitas.tugas.me');
       
        Route::post('/cek/perbarui/tugas', 'AktivitasUmrahController@cekAndPerbaruiTugas');

        // AKSES KETUA PEMBIMBING
        Route::get('/jadwal/umrah/active', 'AktivitasUmrahController@jadwalUmrahActive')->name('tugas.jadwalumrah-active');
        Route::post('/jadwal/umrah/active/validasi', 'AktivitasUmrahController@validasiTugasUmrah')->name('tugas.jadwalumrah-validasi');
        Route::get('/jadwal/umrah/detail/validasi/{id}', 'AktivitasUmrahController@getDetailDataValidasi');
        Route::post('/jadwal/umrah/detail/validasi/save/{id}', 'AktivitasUmrahController@storeValidasi');
        Route::get('/jadwal/umrah/active/detail/{id}', 'AktivitasUmrahController@jadwalUmrahActiveDetail')->name('tugas.jadwalumrah.detail');

        // DETAIL VIA GRAFIK
        Route::get('/detail/y/{aktivitas}/{id}', 'AktivitasUmrahController@detailGrafifkProgresY')->name('tugas.detail-y');
        Route::get('/detailactivitas/statusY/{aktivitasId}/{id}', 'AktivitasUmrahController@getDetailDataStatusY'); // datatable
        
        Route::get('/detail/n/{aktivitas}/{id}', 'AktivitasUmrahController@detailGrafifkProgresN')->name('tugas.detail-n');
        Route::get('/detailactivitas/statusN/{aktivitasId}/{id}', 'AktivitasUmrahController@getDetailDataStatusN'); // datatable

        Route::get('/detail/null/{aktivitas}/{id}', 'AktivitasUmrahController@detailGrafifkProgresNull')->name('tugas.detail-null');
        Route::get('/detailactivitas/statusNull/{aktivitasId}/{id}', 'AktivitasUmrahController@getDetailDataStatusNull'); // datatable

        Route::get('/kuisioner/detail/{id}', 'DashboardController@detailKuisionerByDashboard')->name('kusioner.detail.jawaban');

        // PETUGAS
        Route::get('/petugas/umrah', 'AktivitasUmrahPetugascontroller@indexPetugas')->name('aktivitas.petugas.index');
        Route::get('/petugas/detail/{id}', 'AktivitasUmrahPetugascontroller@show')->name('aktivitas.detail');
        Route::post('/petugas/finish', 'AktivitasUmrahPetugascontroller@updateStatusAktifitasUmrah');
        Route::post('/petugas/delete/tugas', 'AktivitasUmrahPetugascontroller@deleteAktifitasUmrah');


    });
    

    Route::group(['prefix' =>  'user'], function(){
        Route::get('/tugas', 'AktivitasUmrahController@pageTahapanTugasByPembimbing')->name('user.aktivitas.index');
        Route::get('/detailtugas/{id}', 'AktivitasUmrahController@pageDetaiTugasByPembimbing')->name('user.aktivitas.detail');
        Route::get('/judul/detailtugas/{aktitivitas_umrah_id}/{id}', 'AktivitasUmrahController@pageDetaiTugasByJudul')->name('user.aktivitas.judul.detail');
        Route::get('/listtugas/{aktitivitas_umrah_id}/{id}', 'AktivitasUmrahController@getDetailTugasByPembimbing');
        Route::post('/createTugas', 'AktivitasUmrahController@createStatusTugasByPembimbing');
		
		Route::post('/catatan/store/{id}', 'AktivitasUmrahController@storeCatatanEvaluasi')->name('aktivitas.store.catatan');
		
        Route::get('/form/isitugas/{master_sop_id}', 'AktivitasUmrahController@pageFormTugasByPembimbingByJudul')->name('user.form.isitugas');

        // ISI TUGAS BESERTA FILE GAMBAR
        Route::post('/createTugaswithfile', 'AktivitasUmrahController@createStatusTugasByPembimbingWithFile');
        Route::post('/createTugaswithfileUpload', 'AktivitasUmrahController@uploadPelaksanaan')->name('user.create.aktivitas');

        Route::get('/history', 'AktivitasUmrahController@historyTugasJadwalUmrah')->name('user.aktivitas.history');
        Route::get('/history/all', 'AktivitasUmrahController@historyTugasJadwalUmrahAll')->name('user.aktivitas.history.all');
        Route::get('/history/detail/{id}', 'AktivitasUmrahController@pageDetaiHistoryTugasByPembimbing')->name('user.aktivitas.historydetail');
        Route::get('/history/listtugas/{id}', 'AktivitasUmrahController@getDetailHistoryTugasByPembimbing');

        // PROFILE
        Route::get('/myprofile', 'UserController@myProfile')->name('user.myprofile');
        Route::post('/myprofile/update/{user_id}', 'UserController@updateProfile')->name('user.updateprofile');

        // UPDATE NILAI AKHIR DARI SOP = N YANG DIPERTIMBANGKAN
        Route::post('/update/nilai/pertimbangan', 'AktivitasUmrahController@updateNilaiAkhirPertimbangan');
		
		// PANDUAN
        Route::get('/panduan', 'PanduanController@panduanPembimbing')->name('user.panduan.index');
        Route::get('/panduan/{slug}', 'PanduanController@show');

        // PETUGAS
        Route::get('/petugas/tugas', 'AktivitasUmrahPetugascontroller@pageTahapanTugasByPetugas')->name('user.aktivitas.petugas.index');
        Route::get('/petugas/form/isitugas/{master_sop_id}', 'AktivitasUmrahPetugascontroller@pageFormTugasByPetugasByJudul')->name('user.petugas.form.isitugas');
        Route::get('/petugas/judul/detailtugas/{aktitivitas_umrah_petugas_id}/{id}', 'AktivitasUmrahPetugascontroller@pageDetaiTugasPetugasByJudul')->name('user.petugas.aktivitas.judul.detail');
        Route::get('/petugas/listtugas/{aktitivitas_umrah_petugas_id}/{id}', 'AktivitasUmrahPetugascontroller@getDetailTugasByPetugas');
        Route::post('/petugas/createTugaswithfileUpload', 'AktivitasUmrahPetugascontroller@uploadPelaksanaanPetugas')->name('user.petugas.create.aktivitas');

    });
});

// SAMPLE MAP DISPLAY
//Route::get('/sample', 'PlaceController@sampleMap')->name('sample');
