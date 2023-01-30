<?php

namespace App\Http\Controllers;

use App\TugasModel;
use PDF;
use App\AktivitasUmrahModel;
use App\KuisionerModel;
use Illuminate\Http\Request;
use App\DetailAktivitasUmrahModel;
use App\Helpers\ResponseFormatter;
use App\UmrahModel;
use App\SopModel;
use App\PembimbingModel;
use App\AktivitasUmrahPetugasModel;
use App\TugasForPetugasModel;
use App\DetailAktivitasUmrahPetugasModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use File;
use Illuminate\Support\Facades\Validator;
use  App\Providers\Globalprovider;
use Carbon\Carbon;
use Image;

class AktivitasUmrahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $path;
    public $dimensions;

    public function __construct()
    {
        //DEFINISIKAN PATH
        $this->path = storage_path('app/public/tugas');
        //DEFINISIKAN DIMENSI
        // $this->dimensions = ['245'];
    }

    public function index()
    {
        return view('aktivitasumrah.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('aktivitasumrah.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function addElementFormAsistenPembimbing(Request $request)
    {
            $KategoriPilihanModel = new PembimbingModel();
            $data = $KategoriPilihanModel->select('id','nama')->get();
           
            if($request->has('q')){
                $search = $request->q;
                $data = $KategoriPilihanModel->where('nama','LIKE',"%$search%")->get();

        }
        return response()->json($data);

    }

    // public function store(Request $request)
    // {


    //     DB::beginTransaction();
    //     try {

    //         $request->validate([
    //             'umrah' => 'required',
    //             'pembimbing' => 'required',
    //         ]);

    //         $assisten = $request->asisten;
    //         // jik ada asisten
    //         if ($assisten != '') {
    //             // cek jika ada id sama antara pembilbing dan asisten
    //             $asist = [];
    
    //             foreach ($assisten as $key => $value) {
    //                 $asist[] = [
    //                     'asisten' => $value
    //                 ];
    //             }
    
    //             $cek = array_search($request->pembimbing, array_column($asist,'asisten'));
                
    //             if ($cek === false) { // JIKA TIDAK ADA YG SAMA
    //                 // prosess store dengan asisten
    //                 // CEK TIDAK BOLEH ADA PM
    //                 $cek_aktivitas = AktivitasUmrahModel::where('pembimbing_id', $request->pembimbing)->where('umrah_id', $request->umrah)->count();
                   
    //                 if ($cek_aktivitas > 0) {
                        
    //                     return redirect()->route('aktivitas.create')->with(['error' => 'Gagal, Pembimbing dengan Tourcode tersebut telah tersedia']);

    //                 }else{

    //                     // INSERT PEMBIMBING KE TB AKTIVITAS_UMRAH
    //                     $aktitivitas = AktivitasUmrahModel::create([
    //                         'pembimbing_id' => $request->pembimbing,
    //                         'umrah_id' => $request->umrah,
    //                         'status_tugas' => 'Pembimbing',
    //                         'create_by' => Auth::user()->id
    //                     ]);
                       

    //                      // CARI SOP ASISTEN_MASTER_SOP BERDASARKAN UMRAH
    //                         // GET MASTER_SOP_ID DARI TABLE UMRAH WHERE $request->umrah
    //                         $umrah = UmrahModel::select('master_sop_id','asisten_master_sop_id')->where('id', $request->umrah)->first();
    //                         $master_sop_id = $umrah->master_sop_id;
    //                         $asisten_master_sop_id = $umrah->asisten_master_sop_id;

    //                         $tugasModel = new TugasModel();

    //                         // INSERT KE DETAIL , MENAMPUNG SEMUA POIN TUGAS YANG DIBERIKAN KEDAPA PEMBIMBING BERDASARKAN TOURCODE DAN MASTER_SOP_ID NYA
    //                         $tugas = $tugasModel->select('id','nama','nomor','master_sop_id','master_judul_tugas_id','require_image')->where('master_sop_id', $master_sop_id)->get();

    //                         foreach ($tugas  as $val) {
    //                             $detailAktivitas = new DetailAktivitasUmrahModel();
    //                             $detailAktivitas->aktivitas_umrah_id = $aktitivitas->id;
    //                             $detailAktivitas->master_sop_id = $val->master_sop_id;
    //                             $detailAktivitas->master_judul_tugas_id = $val->master_judul_tugas_id;
    //                             $detailAktivitas->master_tugas_id = $val->id;
    //                             $detailAktivitas->nomor_tugas = $val->nomor;
    //                             $detailAktivitas->nama_tugas  = $val->nama;
    //                             $detailAktivitas->require_image  = $val->require_image;
    //                             $detailAktivitas->status = '';
    //                             $detailAktivitas->alasan = '';
    //                             $detailAktivitas->created_at = date('Y-m-d H:i:s');
    //                             $detailAktivitas->updated_at = date('Y-m-d H:i:s');;
    //                             $detailAktivitas->save();
    //                         }

    //                     // INSERT ASISTEN KE TB AKTIVITAS_UMRAH
    //                     foreach ($assisten as $key => $value) {
    //                         $aktitivitas = new AktivitasUmrahModel();
    //                         $aktitivitas->pembimbing_id = $value;
    //                         $aktitivitas->umrah_id = $request->umrah;
    //                         $aktitivitas->status_tugas = 'Asisten Pembimbing';
    //                         $aktitivitas->create_by = Auth::user()->id;
    //                         $aktitivitas->save();

    //                          // INSERT KE DETAIL , MENAMPUNG SEMUA POIN TUGAS YANG DIBERIKAN KEDAPA PEMBIMBING BERDASARKAN TOURCODE DAN MASTER_SOP_ID NYA
    //                          $tugasAsisten =  $tugasModel->select('id','nama','nomor','master_sop_id','master_judul_tugas_id','require_image')->where('master_sop_id', $asisten_master_sop_id)->get();
    //                          foreach ($tugasAsisten  as $val) {
    //                              $detailAktivitas = new DetailAktivitasUmrahModel();
    //                              $detailAktivitas->aktivitas_umrah_id = $aktitivitas->id;
    //                              $detailAktivitas->master_sop_id = $val->master_sop_id;
    //                              $detailAktivitas->master_judul_tugas_id = $val->master_judul_tugas_id;
    //                              $detailAktivitas->master_tugas_id = $val->id;
    //                              $detailAktivitas->nomor_tugas = $val->nomor;
    //                              $detailAktivitas->nama_tugas  = $val->nama;
    //                              $detailAktivitas->require_image  = $val->require_image;
    //                              $detailAktivitas->status = '';
    //                              $detailAktivitas->alasan = '';
    //                              $detailAktivitas->created_at = date('Y-m-d H:i:s');
    //                              $detailAktivitas->updated_at = date('Y-m-d H:i:s');
    //                              $detailAktivitas->save();
    //                          }



    //                     }
                        
    //                     DB::commit();
                        
    //                     return redirect()->route('aktivitas.create')->with(['success' => 'Tugas telah dibuat']);
    //                 }
    //             }else{
    //                 return redirect()->route('aktivitas.create')->with(['warning' => 'Pembimbing dan asisten tidak boleh sama !']);
    //             }

    //         }else{
    //             // prosess store tanpa asisten
    //             // CEK TIDAK BOLEH ADA PM
    //             $cek_aktivitas = AktivitasUmrahModel::where('pembimbing_id', $request->pembimbing)->where('umrah_id', $request->umrah)->count();

    //             if ($cek_aktivitas > 0) {

    //                 return redirect()->route('aktivitas.create')->with(['error' => 'Gagal, Pembimbing dengan Tourcode tersebut telah tersedia']);

    //             }else{
    //                 // INSERT KE TB AKTIVITAS_UMRAH
    //                 $aktitivitas = AktivitasUmrahModel::create([
    //                     'pembimbing_id' => $request->pembimbing,
    //                     'umrah_id' => $request->umrah,
    //                     'status_tugas' => 'Pembimbing',
    //                     'create_by' => Auth::user()->id
    //                 ]);

    //                 // GET MASTER_SOP_ID DARI TABLE UMRAH WHERE $request->umrah
    //                 $umrah = UmrahModel::select('master_sop_id')->where('id', $request->umrah)->first();
    //                 $master_sop_id = $umrah->master_sop_id;
            
    //                 // INSERT KE DETAIL , MENAMPUNG SEMUA POIN TUGAS YANG DIBERIKAN KEDAPA PEMBIMBING BERDASARKAN TOURCODE DAN MASTER_SOP_ID NYA
    //                 $tugas = TugasModel::select('id','nama','nomor','master_sop_id','master_judul_tugas_id','require_image')->where('master_sop_id', $master_sop_id)->get();
    //                 foreach ($tugas  as $val) {
    //                     $detailAktivitas = new DetailAktivitasUmrahModel();
    //                     $detailAktivitas->aktivitas_umrah_id = $aktitivitas->id;
    //                     $detailAktivitas->master_sop_id = $val->master_sop_id;
    //                     $detailAktivitas->master_judul_tugas_id = $val->master_judul_tugas_id;
    //                     $detailAktivitas->master_tugas_id = $val->id;
    //                     $detailAktivitas->nomor_tugas = $val->nomor;
    //                     $detailAktivitas->nama_tugas  = $val->nama;
    //                     $detailAktivitas->require_image  = $val->require_image;
    //                     $detailAktivitas->status = '';
    //                     $detailAktivitas->alasan = '';
    //                     $detailAktivitas->created_at = date('Y-m-d H:i:s');
    //                     $detailAktivitas->updated_at = date('Y-m-d H:i:s');
    //                     $detailAktivitas->save();
    //                 }
    //             }
    //         }

    //      DB::commit();

    //      return redirect()->route('aktivitas.create')->with(['success' => 'Tugas telah dibuat']);

    //     } catch (\Exception $e) {
    //         DB::rollback();
    //        echo 'Message: ' .$e->getMessage();
    //     }


    // }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

            $request->validate([
                'umrah' => 'required',
                'pembimbing' => 'required',
            ]);

            $assisten   = $request->asisten;
            $pembimbing = $request->pembimbing;
            $petugas    = $request->petugas == null ? [] : $request->petugas;

            // jik ada asisten
            if ($assisten != '') {
                // cek jika ada id sama antara pembimbing dan asisten

                $asist = [];
                $pem   = [];
    
                foreach ($assisten as $key => $value) {
                    $asist[] = [
                        'asisten' => $value
                    ];
                }

                foreach ($pembimbing as $key => $value) {
                    $pem[] = [
                        'asisten' => $value
                    ];
                }
    
                // $cek = array_search($pembimbing, array_column($asist,'asisten'));

                $cek = true;

                foreach ($asist as $value) {
                    if (in_array($value, $pem)) {
                        $cek = false;
                    }else{
                        $cek = true;
                    }
                }

                
                if ($cek === true) { // JIKA TIDAK ADA YG SAMA
                    // prosess store dengan asisten
                    // CEK TIDAK BOLEH ADA PM
                    $cek_aktivitas = AktivitasUmrahModel::where('pembimbing_id', $request->pembimbing)->where('umrah_id', $request->umrah)->count();

                    // CEK TIDAK BOLEH ADA PETUGAS DI TOURCODE YG SAMA DAN NAMA YG SAMA
                    $cek_aktivitas_petugas = AktivitasUmrahPetugasModel::where('petugas_id', $request->petugas)->where('umrah_id', $request->umrah)->count();
                   
                    if ($cek_aktivitas > 0) {
                        
                        return redirect()->route('aktivitas.create')->with(['error' => 'Gagal, Pembimbing dengan Tourcode tersebut telah tersedia']);

                    }elseif($cek_aktivitas_petugas > 0){

                        return redirect()->route('aktivitas.create')->with(['error' => 'Gagal, Petugas dengan Tourcode tersebut telah tersedia']);
                    }else{

                        $storeTugasPembimbing = $this->storeTugasPembimbing($pembimbing,$petugas,$request);
                        $asisten_master_sop_id = $storeTugasPembimbing;
                        $tugasModel = new TugasModel();

                        // INSERT ASISTEN KE TB AKTIVITAS_UMRAH
                        foreach ($assisten as $key => $value) {
                            $aktitivitas = new AktivitasUmrahModel();
                            $aktitivitas->pembimbing_id = $value;
                            $aktitivitas->umrah_id = $request->umrah;
                            $aktitivitas->status_tugas = 'Asisten Pembimbing';
                            $aktitivitas->create_by = Auth::user()->id;
                            $aktitivitas->save();


                             // INSERT KE DETAIL , MENAMPUNG SEMUA POIN TUGAS YANG DIBERIKAN KEDAPA PEMBIMBING BERDASARKAN TOURCODE DAN MASTER_SOP_ID NYA
                             $tugasAsisten =  $tugasModel->select('id','nama','nomor','master_sop_id','master_judul_tugas_id','require_image')->where('master_sop_id', $asisten_master_sop_id)->get();
                             foreach ($tugasAsisten  as $val) {
                                 $detailAktivitas = new DetailAktivitasUmrahModel();
                                 $detailAktivitas->aktivitas_umrah_id = $aktitivitas->id;
                                 $detailAktivitas->master_sop_id = $val->master_sop_id;
                                 $detailAktivitas->master_judul_tugas_id = $val->master_judul_tugas_id;
                                 $detailAktivitas->master_tugas_id = $val->id;
                                 $detailAktivitas->nomor_tugas = $val->nomor;
                                 $detailAktivitas->nama_tugas  = $val->nama;
                                 $detailAktivitas->require_image  = $val->require_image;
                                 $detailAktivitas->status = '';
                                 $detailAktivitas->alasan = '';
                                 $detailAktivitas->created_at = date('Y-m-d H:i:s');
                                 $detailAktivitas->updated_at = date('Y-m-d H:i:s');
                                 $detailAktivitas->save();
                             }

                        }


                        DB::commit();
                        
                        return redirect()->route('aktivitas.create')->with(['success' => 'Tugas telah dibuat']);
                    }
                }else{
                    return redirect()->route('aktivitas.create')->with(['warning' => 'Pembimbing dan asisten tidak boleh sama !']);
                }

            }else{
                // prosess store tanpa asisten
                // CEK TIDAK BOLEH ADA PM
                $cek_aktivitas = AktivitasUmrahModel::where('pembimbing_id', $request->pembimbing)->where('umrah_id', $request->umrah)->count();

                if ($cek_aktivitas > 0) {

                    return redirect()->route('aktivitas.create')->with(['error' => 'Gagal, Pembimbing dengan Tourcode tersebut telah tersedia']);

                }else{

                    $this->storeTugasPembimbing($pembimbing,$petugas, $request);

                }
            }

         DB::commit();

         return redirect()->route('aktivitas.create')->with(['success' => 'Tugas telah dibuat']);

        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }


    }


    public function storeTugasPembimbing($pembimbing, $petugas, $request)
    {
        // CARI SOP ASISTEN_MASTER_SOP BERDASARKAN UMRAH
            // GET MASTER_SOP_ID DARI TABLE UMRAH WHERE $request->umrah
            $umrah = UmrahModel::select('master_sop_id','asisten_master_sop_id','master_sop_petugas_id')->where('id', $request->umrah)->first();
            $master_sop_id = $umrah->master_sop_id;
            $asisten_master_sop_id = $umrah->asisten_master_sop_id;
            $master_sop_petugas_id = $umrah->master_sop_petugas_id;

                foreach ($pembimbing as $key => $value) {
                    // INSERT PEMBIMBING KE TB AKTIVITAS_UMRAH
                    $aktitivitas = AktivitasUmrahModel::create([
                        'pembimbing_id' => $value,
                        'umrah_id' => $request->umrah,
                        'status_tugas' => 'Pembimbing',
                        'create_by' => Auth::user()->id
                    ]);

    
                    $tugasModel = new TugasModel();

                    // INSERT KE DETAIL , MENAMPUNG SEMUA POIN TUGAS YANG DIBERIKAN KEDAPA PEMBIMBING BERDASARKAN TOURCODE DAN MASTER_SOP_ID NYA
                    $tugas = $tugasModel->select('id','nama','nomor','master_sop_id','master_judul_tugas_id','require_image')->where('master_sop_id', $master_sop_id)->get();
                    foreach ($tugas  as $val) {
                            $detailAktivitas = new DetailAktivitasUmrahModel();
                            $detailAktivitas->aktivitas_umrah_id = $aktitivitas->id;
                            $detailAktivitas->master_sop_id = $val->master_sop_id;
                            $detailAktivitas->master_judul_tugas_id = $val->master_judul_tugas_id;
                            $detailAktivitas->master_tugas_id = $val->id;
                            $detailAktivitas->nomor_tugas = $val->nomor;
                            $detailAktivitas->nama_tugas  = $val->nama;
                            $detailAktivitas->require_image  = $val->require_image;
                            $detailAktivitas->status = '';
                            $detailAktivitas->alasan = '';
                            $detailAktivitas->created_at = date('Y-m-d H:i:s');
                            $detailAktivitas->updated_at = date('Y-m-d H:i:s');;
                            $detailAktivitas->save();
                    }
                }

                foreach ($petugas as $key => $value) {
                    // INSERT PETUGAS KE TB AKTIVITAS_UMRAH_PETUGAS
                    $aktitivitasPetugas = AktivitasUmrahPetugasModel::create([
                        'petugas_id' => $value,
                        'umrah_id' => $request->umrah,
                        'status_tugas' => 'Petugas',
                        'create_by' => Auth::user()->id
                    ]);
                    // INSERT KE DETAIL , MENAMPUNG SEMUA POIN TUGAS YANG DIBERIKAN KEDAPA PETUGAS BERDASARKAN TOURCODE DAN MASTER_SOP_ID NYA
                    $tugas_petugas = TugasForPetugasModel::select('id','nama','nomor','master_sop_petugas_id','master_judul_tugas_id','require_image')->where('master_sop_petugas_id', $master_sop_petugas_id)->get();
                    foreach ($tugas_petugas  as $val) {
                        $detailAktivitas = new DetailAktivitasUmrahPetugasModel();
                        $detailAktivitas->aktivitas_umrah_petugas_id = $aktitivitasPetugas->id;
                        $detailAktivitas->master_sop_petugas_id = $val->master_sop_petugas_id;
                        $detailAktivitas->master_judul_tugas_id = $val->master_judul_tugas_id;
                        $detailAktivitas->master_tugas_petugas_id = $val->id;
                        $detailAktivitas->nomor_tugas = $val->nomor;
                        $detailAktivitas->nama_tugas  = $val->nama;
                        $detailAktivitas->require_image  = $val->require_image;
                        $detailAktivitas->status = '';
                        $detailAktivitas->alasan = '';
                        $detailAktivitas->created_at = date('Y-m-d H:i:s');
                        $detailAktivitas->updated_at = date('Y-m-d H:i:s');;
                        $detailAktivitas->save();
                    }
                }

            return $asisten_master_sop_id;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // GET TOURCODE DAN NAMA PEMBIMBING
        $aktitivitasModel = new AktivitasUmrahModel();
        $aktitivitas      = $aktitivitasModel->getNameTourcodeAndPembimbing($id);
        $judul_sop        = $aktitivitasModel->getListTugasByAktivitasUmrahId($id);

        // jika status tugas adalah pembimbing, get sop pembimnbing dgn cara relasi
        $status_tugas = $aktitivitas->status_tugas; 
        $sopModel     = SopModel::select('name');
        if ($status_tugas == 'Pembimbing') {

            $sop = $sopModel->where('id', $aktitivitas->master_sop_id)->first();
            $title = 'Pembimbing';
        }else{
            $sop = $sopModel->where('id', $aktitivitas->asisten_master_sop_id)->first();
            $title = 'Asisten Pembimbing';
        }

        return view('aktivitasumrah.detail', compact('aktitivitas','judul_sop','aktitivitasModel','sop','title'));
    }

    public function cekAndPerbaruiTugas()
    {

        DB::beginTransaction();
        try {

            $id = request()->id;
            $aktitivitasModel = new AktivitasUmrahModel();
            $aktitivitas      = $aktitivitasModel->getCekAndPerbaruiTugas($id);

             // INSERT KE DETAIL , MENAMPUNG SEMUA POIN TUGAS YANG DIBERIKAN KEDAPA PEMBIMBING BERDASARKAN TOURCODE DAN MASTER_SOP_ID NYA
             $tugas = TugasModel::select('id','nama','nomor','master_sop_id','master_judul_tugas_id','require_image')->where('master_sop_id',  $aktitivitas->id)->get();

            if (count($tugas) == 0) {                 
                    return ResponseFormatter::success([
                                     'status' => 0,
                                     'message' => 'Belum ada tugas di SOP tersebut!'
                    ]);
            }else{

                 foreach ($tugas  as $val) {
                     $detailAktivitas = new DetailAktivitasUmrahModel();
                     $detailAktivitas->aktivitas_umrah_id = $id;
                     $detailAktivitas->master_sop_id = $val->master_sop_id;
                     $detailAktivitas->master_judul_tugas_id = $val->master_judul_tugas_id;
                     $detailAktivitas->master_tugas_id = $val->id;
                     $detailAktivitas->nomor_tugas = $val->nomor;
                     $detailAktivitas->nama_tugas  = $val->nama;
                     $detailAktivitas->require_image  = $val->require_image;
                     $detailAktivitas->status = '';
                     $detailAktivitas->alasan = '';
                     $detailAktivitas->created_at = date('Y-m-d H:i:s');
                     $detailAktivitas->updated_at = date('Y-m-d H:i:s');
                     $detailAktivitas->save();
                 }

                 DB::commit();
                return ResponseFormatter::success([
                        'status' => 1,
                        'message' => 'Tugas telah diperbarui'
                    ]);
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function dataTableListData(Request $request)
    {
        $orderBy = 'c.tourcode';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'c.tourcode';
                break;
        }

        // $aktitivitasModel = new AktivitasUmrahModel();
        $data             = DB::table('aktivitas_umrah as a')
                            ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                            ->join('umrah as c','c.id','=','a.umrah_id')
                            ->join('kuisioner_umrah as d','d.umrah_id','=','c.id')
                            ->join('kuisioner as e','e.id','=','d.kuisioner_id')
                            ->select('a.id','b.nama as pembimbing','c.tourcode', 'a.status','c.dates','c.id as umrah_id','c.start_date','c.end_date','a.status_tugas','e.nama as kuisioner',
                            DB::raw('(select sum(nilai_akhir) from detail_aktivitas_umrah where aktivitas_umrah_id = a.id) as nilai_akhir'))
                            ->where('a.isdelete', 0);

        if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(c.tourcode) like ? ',['%'.strtolower($request->input('search.value')).'%']);
            });
        }

        if($request->input('month') != '' AND $request->input('year') != ''){
                            $data->whereMonth('c.start_date', $request->month);
                            $data->whereYear('c.start_date', $request->year);
        }

        if($request->input('tourcode') != ''){
                            $data->where('c.tourcode', $request->tourcode);
        }

        if($request->input('pembimbing') != ''){
                            $data->where('b.id', $request->pembimbing);
        }

        
        $recordsFiltered = $data->get()->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();

        $recordsTotal = $data->count();

        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $data
            ]);
    }

    public function listDataJawdalUmrah()
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data             = UmrahModel::select('id','tourcode','dates')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '<a class="border rounded-circle p-1 bg-light-info" href="'.route('tugas.jadwalumrah.detail', $item->id).'"><i class="fa fa-eye"></i></a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function updateStatusAktifitasUmrah(Request $request)
    {
            
        DB::beginTransaction();
        try {

            $id = $request->id;

            // HITUNG TAHAPAN TUGAS
            // $tugasModel = new DetailAktivitasUmrahModel();
            // $count_tugas = $tugasModel->where('aktivitas_umrah_id', $id)
            //                 ->where('status','=','')->count();
            // JIKA SAMA DENGAN 0, MAKA UPDATE STATUS DI AKTIVITAS_UMRAH MENJADI = FINISH
            // $updateStatus = false;

            // $count_validate = $tugasModel->where('aktivitas_umrah_id', $id)
            //                 ->where('validate','=','N')->count();

            // if ($count_tugas  > 0) {
            //     return ResponseFormatter::success([
            //         'data' => 'status',
            //         'message' => 'Gagal, Pembimbing belum meyelesaikan tahapan tugas'
            //     ],200);

            // }
            // // elseif($count_validate > 0){
            // //     return ResponseFormatter::success([
            // //         'data' => 'validate',
            // //         'message' => 'Gagal, Beberapa tugas belum divalidasi'
            // //     ],200);
            // // }
            // else{

            //     $aktitivitas = AktivitasUmrahModel::where('id', $id)->first();
            //     $updateStatus =  $aktitivitas->update(['status' => 'finish']); 
            // }
            
            // if ($count_tugas == 0) {
            // }

            $aktitivitas = AktivitasUmrahModel::where('id', $id)->first();
            $updateStatus =  $aktitivitas->update(['status' => 'finish']); 


            if($updateStatus){

                DB::commit();
                return ResponseFormatter::success([
                   null,
                   'message' => 'Tugas telah selesai'
            ],200);
            }else{
                 return ResponseFormatter::error([
                   null,
                   null
                ]); 
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function deleteAktifitasUmrah(Request $request)
    {
            
        DB::beginTransaction();
        try {

            $id = $request->id;

            // UPDATE ISDELETE AKTITVITAS UMRAH = 1
           $tugas = AktivitasUmrahModel::where('id', $id)->first();
           $tugas->update(['isdelete' => 1]);

            DB::commit();
            return ResponseFormatter::success([
                'data',
                'message' => 'Tugas telah dihapus'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }
    
    public function getDetailData($id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data             = $aktitivitasModel->getDetailActivitas($id);
        if (request()->ajax()) 
        {
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('pelaksanaan', function($item){
                        $validate = $item->validate == 'Y' ? '<i><small>(valid)</small></i>' : '';
                        if ($item->status == 'N') {
                            return '<span class="text-danger"><i class="lni lni-close"></i></span><br>'. $validate.'';
                        }elseif($item->status == 'Y'){
                            return '<span class="text-success"><i class="lni lni-checkmark"></i></span><br>'.$validate.'';

                        }else{
                            return '-';
                        }
                    })
                    ->rawColumns(['pelaksanaan'])
                    ->make(true);
        }
    }

    // GET PAGE TUGAS BERDASARKAN LOGIN PEMBIMBNING , YANG DI SET OLEH ADMIN
    public function pageTahapanTugasByPembimbing()
    {
        // $user_id = Auth::user()->id;
        // $aktitivitasModel = new AktivitasUmrahModel();
        // $jadwal      = $aktitivitasModel->getNameTourcodeByPembimbing($user_id);

        $user_id = Auth::user()->id;
        $aktitivitasModel = new AktivitasUmrahModel();
        $jadwal      = $aktitivitasModel->getHistoryNameTourcodeByPembimbingListJudul($user_id);

        $gf         = new Globalprovider();
        $result     = [];
        foreach ($jadwal as $value) {
            $result[] = [
                'id' => $value->id,
                'tourcode' => $value->tourcode,
				'url' => $value->url,
                'count_jamaah' => $value->count_jamaah,
                'responden_kuisioner' => $value->total_responden
            ];
        }

        // return $result;

        return view('users.tugas.index', compact('jadwal','aktitivitasModel','result'));
    }
	
	

    public function pageFormTugasByPembimbingByJudul($aktitivitas_umrah_id)
    {

        $user_id = Auth::user()->id;
        $aktitivitasModel = new AktivitasUmrahModel();
        $jadwal      = $aktitivitasModel->getNameTourcodeByPembimbingByAkunPembimbing($user_id, $aktitivitas_umrah_id);
		$catatan     = $aktitivitasModel->select('catatan')->where('id', $aktitivitas_umrah_id)->first();


        return view('users.tugas.listjudul', compact('jadwal','aktitivitasModel','aktitivitas_umrah_id','catatan'));
    }

    public function pageDetaiTugasByPembimbing($id)
    {
        // $user_id = Auth::user()->id;
        $aktitivitasModel = new AktivitasUmrahModel();
        $jadwal      = $aktitivitasModel->getNameTourcodeByAktivitasUmrahId($id);
        return view('users.tugas.detail', compact('jadwal'));
    }

    public function pageDetaiTugasByJudul($aktitivitas_umrah_id, $id)
    {
        // $user_id = Auth::user()->id;
        // $aktitivitasModel = new AktivitasUmrahModel();
        // $jadwal      = $aktitivitasModel->getNameTourcodeByAktivitasUmrahId($id);
        // return $jadwal;
        $judul = DB::table('master_judul_tugas')->select('nama')->where('id', $id)->first();
        $user_id = Auth::user()->id;

        return view('users.tugas.form', ['judul' => $judul,'user_id' => $user_id,'aktitivitas_umrah_id' => $aktitivitas_umrah_id]);
    }

    public function getDetailTugasByPembimbing($aktitivitas_umrah_id, $id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data             = $aktitivitasModel->getListTugasByPembimbingByJudul($aktitivitas_umrah_id, $id);
        if (request()->ajax()) 
        {
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('check', function($item){
                      $checked = $item->status == 'Y' ? 'checked' : '';
                      $unchecked = $item->status == 'N' ? 'checked' : '';
                      $status = $item->status == 'N' ? 'Tidak' : 'Ya';
                      if($item->validate == 'Y'){
                          return '<div class="form-check">
                                   <input class="form-check-input" type="radio" name="status_'.$item->id.'" value="Y" id="'.$item->id.'" checked> '.$status.'
                                  <i><small>(valid)</small></i>
                               </div>';
                      }
                      return '
                               <div class="form-check">
                                   <input class="form-check-input" data-require-image="'.$item->require_image.'" type="radio" name="status_'.$item->id.'" onclick="selectedWithFile(this)" value="Y" id="'.$item->id.'" '.$checked.'> Ya
                               </div>
                               <div class="form-check">
                                   <input class="form-check-input" data-require-image="'.$item->require_image.'" type="radio" name="status_'.$item->id.'" onclick="selectedWithFile(this)" value="N" id="'.$item->id.'" '.$unchecked.'> Tidak
                               </div>
                           ';
                      
                    })
                    ->addColumn('pelaksanaan', function($item){
                        if ($item->status == 'N') {
                            return '<span class="badge bg-danger"><i class="lni lni-close"></i></span>';
                        }elseif($item->status == 'Y'){
                            return '<span class="badge bg-success"><i class="lni lni-checkmark"></i></span>';

                        }else{
                            return '-';
                        }
                    })
                    ->addColumn('cretedAt', function($item){
                        return date('d-m-Y H:i', strtotime($item->created_at));
                    })
                    ->rawColumns(['pelaksanaan','check','cretedAt'])
                    ->make(true);
        }

        return $data;
    }

    public function createStatusTugasByPembimbing(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        // jika status Y, tidak perlu alasan, jadi kosongkan saja
        $alasan  = $status == 'Y' ? '' : $request->alasan;
        $aktitivitas_umrah_id = $request->aktivitasUmrahId;
        
        DB::beginTransaction();
        try {

            $tugasModel = new DetailAktivitasUmrahModel();

            $tugas = $tugasModel->where('id', $id)->first();
            $tugas->update([
                'status' => $status,
                'alasan' => $alasan,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // HITUNG TAHAPAN TUGAS,
            $count_tugas = $tugasModel->where('aktivitas_umrah_id', $aktitivitas_umrah_id)
                            ->where('status','=','')->count();

            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => $count_tugas == 0 ? 'Selesai' :'Sukses'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $id
            ]);

        }

    }

    public function historyTugasJadwalUmrah()
    {
        $user_id = Auth::user()->id;
        $aktitivitasModel = new AktivitasUmrahModel();
        $jadwal      = $aktitivitasModel->getHistoryNameTourcodeByPembimbing($user_id);

        $gf         = new Globalprovider();
        $result     = [];
        foreach ($jadwal as $value) {
            $result[] = [
                'id' => $value->id,
                'tourcode' => $value->tourcode,
                'nilai_akhir' => $gf->calculateGradeByUmrah($value->nilai_akhir)
            ];
        }


        return view('users.history.index', compact('result'));
    }

    public function historyTugasJadwalUmrahAll()
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $jadwal      = $aktitivitasModel->getAllHistory();

        return view('users.history.all', compact('jadwal'));
    }

    public function pageDetaiHistoryTugasByPembimbing($id)
    {
        // $user_id = Auth::user()->id;
        $aktitivitasModel = new AktivitasUmrahModel();
        $jadwal      = $aktitivitasModel->getNameTourcodeByAktivitasUmrahId($id);
        $judul_sop   =  $aktitivitasModel->getListTugasByAktivitasUmrahId($id);

        return view('users.history.detail', compact('jadwal','judul_sop','aktitivitasModel'));
    }

    public function getDetailHistoryTugasByPembimbing($id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data             = $aktitivitasModel->getListHistoryTugasByPembimbing($id);
        if (request()->ajax()) 
        {
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('check', function($item){
                      $validate = $item->validate == 'Y' ? '<i><small>(valid)</small></i>' : '';
                      $value = $item->status == 'Y' ? "Ya $validate" : 'Tidak';
                      return $value;
                    })
                    ->addColumn('updatedAt', function($item){
                        return date('d-m-Y H:i', strtotime($item->updated_at));
                    })
                    ->rawColumns(['pelaksanaan','check','updatedAt'])
                    ->make(true);
        }

        return $data;
    }

    public function downloadPdfByAktivitasUmrahId($id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data             = $aktitivitasModel->getDetailActivitas($id);
        $jadwal           = $aktitivitasModel->getNameTourcodeAndPembimbing($id);

        $pdf = PDF::LoadView('report.tugas', compact('data','jadwal'));
        return $pdf->download('LAPORAN TAHAPAN TUGAS'.$jadwal->tourcode.'.pdf');
        
    }

    public function grafikCardTugas(Request $request)
    {
        // DATA UMRAH
        // $umrah = UmrahModel::select('id','tourcode')->get();
        $data = DB::table('umrah as a')->select('b.id','a.tourcode','c.nama as pembimbing')
                    ->join('aktivitas_umrah as b','b.umrah_id','=','a.id')
                    ->join('pembimbing as c','b.pembimbing_id','=','c.id');

        if($request->input('month') != '' AND $request->input('year') != ''){
                            $data->whereMonth('start_date', $request->month);
                            $data->whereYear('end_date', $request->year);
                        }

        if($request->input('tourcode') != ''){
                            $data->where('tourcode', $request->tourcode);
                        }

        $data = $data->get();

        $data = [
            'umrah' => $data,
        ];

        return response()->json($data);
    }

    public function grafikChartTugas()
    {
        $id = request()->id;
        $aktitivitasModel = new AktivitasUmrahModel();
        // $tugas            = $aktitivitasModel->getDataGrafikAktivitasUmrah($id);
        $tugas            = $aktitivitasModel->getListSopByAktivitasUmrahId($id);
        $result           = [];

        foreach ($tugas as $key => $value) {
            $persentage = ($value->total_terisi / $value->total_sop)*100;
            $result[] = [
                'id' => $value->id,
                'aktivitas_umrah_id' => $value->aktivitas_umrah_id,
                'nama' => $value->nama,
                'total_sop' => $value->total_sop,
                'total_terisi' => $value->total_terisi,
                'total_null' => $value->total_null,
                'total_N' => $value->total_N,
                'total_Y' => $value->total_Y,
                'persentage' => $persentage
            ];
        }
        
        // return $result;
        
        $judul     = [];
        $data_null = [];
        $data_Y = [];
        $data_N = [];
        $chart = [];
        
        foreach ($result as $val) {
            // persentasi
            // $persentage = ($val['total_terisi'] / $val['total_tugas']) * 100;
            $judul[] = $val['nama'];  
            $data_null[] = [
                'y' => $val['total_null'],
                'url' => route('tugas.detail-null', ['aktivitas' => $val['aktivitas_umrah_id'], 'id' => $val['id']])
            ];          
            $data_Y[] = [
                'y' => $val['total_Y'],
                'url' => route('tugas.detail-y', ['aktivitas' => $val['aktivitas_umrah_id'], 'id' => $val['id']])
            ];          
            $data_N[] = [
                'y' => $val['total_N'],
                'url' => route('tugas.detail-n', ['aktivitas' => $val['aktivitas_umrah_id'], 'id' => $val['id']])
            ];          
        }

        $data = [
            'judul' => $judul,
            'data_null' => $data_null,
            'data_Y' => $data_Y,
            'data_N' => $data_N
        ];
        return response()->json($data);
    }

    public function detailGrafifkProgresY($id)
    {
         // GET TOURCODE DAN NAMA PEMBIMBING
        // $aktitivitasModel = new AktivitasUmrahModel();
        // $aktitivitas      = $aktitivitasModel->getNameTourcodeAndPembimbing($id);

        return view('dashboard.detail-y', compact('id'));
    }

    public function detailGrafifkProgresN($id)
    {
         // GET TOURCODE DAN NAMA PEMBIMBING
        // $aktitivitasModel = new AktivitasUmrahModel();
        // $aktitivitas      = $aktitivitasModel->getNameTourcodeAndPembimbing($id);

        return view('dashboard.detail-n', compact('id'));
    }

    public function detailGrafifkProgresNull($id)
    {

        return view('dashboard.detail-null', compact('id'));
    }

    public function getDetailDataStatusY($aktivitasId, $id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data            = $aktitivitasModel->getListTugasByMasterJudulIdInChartNew($aktivitasId, $id);
        $data            = $data->where('status','=','Y')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('pelaksanaan', function($item){
                        return '<span class="badge bg-success"><i class="lni lni-checkmark"></i></span>';
                    })
                    ->addColumn('foto', function($item){
                        if($item->file == NULL){
                            return '-';
                        }else{
                            return '
                                <a href="'.asset('/storage/'.$item->file).'" target="_blank">
                                    <img src="'.asset('/storage/'.$item->file).'" width="30">
                                </a>
                            ';
                        }
                    })
                    ->addColumn('file', function($item){
                        if($item->file_doc == NULL) {
                            return '-';
                        }else{
                            return '
                                <a href="'.asset('/storage/'.$item->file_doc).'" target="_blank">
                                    "'.$item->file_doc_name.'"
                                </a>
                            ';
                        }
                    })
                    ->addColumn('updatedAt', function($item){
                        return date('d-m-Y H:i', strtotime($item->updated_at));
                    })
                    ->rawColumns(['pelaksanaan','updatedAt','foto','file'])
                    ->make(true);
        }
    }

    public function getDetailDataStatusN($aktivitasId, $id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data            = $aktitivitasModel->getListTugasByMasterJudulIdInChartNew($aktivitasId, $id);
        $data            = $data->where('status','=','N')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('pelaksanaan', function($item){
                        return '<span class="badge bg-danger"><i class="lni lni-close"></i></span>';
                    })
                    ->addColumn('updatedAt', function($item){
                        return date('d-m-Y H:i', strtotime($item->updated_at));
                    })
                    ->rawColumns(['pelaksanaan','updatedAt'])
                    ->make(true);
        }
    }

    public function getDetailDataStatusNull($aktivitasId, $id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data            = $aktitivitasModel->getListTugasByMasterJudulIdInChartNew($aktivitasId,$id);
        $data            = $data->where('status','=','')->get();
       
        // $aktitivitas      = $aktitivitasModel->getNameTourcodeAndPembimbing($id)

        // $aktitivitasModel = new AktivitasUmrahModel();
        // $data             = $aktitivitasModel->getDetailActivitasStatusNull($id);
        if (request()->ajax()) 
        {
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('updatedAt', function($item){
                        if($item->updated_at == NULL){
                            return '-';
                        }else{
                            return date('d-m-Y H:i', strtotime($item->updated_at));
                        }
                    })
                    ->rawColumns(['updatedAt'])
                    ->make(true);
        }
    }


    public function jadwalUmrahActive()
    {
        return view('tugas.list-tugas');
    }

    public function jadwalUmrahActiveDetail($id)
    {
        
        $aktitivitasModel = new AktivitasUmrahModel();
        $pembimbing       = $aktitivitasModel->getPembimbingByUmrahId($id);
        $umrah            = UmrahModel::select('tourcode')->where('id', $id)->first();
        // $jadwal             = $aktitivitasModel->getDataJadwalUmrahActive();

        return view('tugas.list-pembimbing', compact('pembimbing','umrah'));
    }

    // public function validasiTugasUmrah($id)
    // {
    //      // GET TOURCODE DAN NAMA PEMBIMBING
    //     $aktitivitasModel = new AktivitasUmrahModel();
    //     $aktitivitas      = $aktitivitasModel->getNameTourcodeAndPembimbing($id);

    //     return view('tugas.detail-list-tugas',['aktitivitas' => $aktitivitas]);
    // }

    public function getDetailDataValidasi($id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data             = $aktitivitasModel->getDetailActivitas($id);
        if (request()->ajax()) 
        {
            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('pelaksanaan', function($item){
                        if($item->validate == 'N'){
                            if($item->status != ''){
                                $btnValidate =  '<button onclick="onValidate(this)" value="'.$item->nomor.'" id="'.$item->id.'" class="badge bg-primary mt-1">Validasi</button>';
                            }
                        }else{
                            $btnValidate = '(<i>valid</i>)';
                        }
                        if ($item->status == 'N') {
                            return '<span  class="text-danger"><i class="lni lni-close"></i></span><br>
                            <button  class="text-primary" onclick="onDetaiSatusNo(this)" value="'.$item->alasan.'"><small>> Detail alasan</small></button><br>'.$btnValidate.'';
                        }elseif($item->status == 'Y'){
                            return '<span class="text-success"><i class="lni lni-checkmark"></i></span><br>'.$btnValidate.'';

                        }else{
                            return '-';
                        }
                    })
                    ->addColumn('updatedAt', function($item){
                        return date('d-m-Y H:i', strtotime($item->updated_at));
                    })
                    // ->addColumn('validasi', function($item){
                    //     if($item->validate == 'N'){
                    //         if($item->status != ''){
                    //             return '<button onclick="onValidate(this)" value="'.$item->nomor.'" id="'.$item->id.'" class="btn btn-sm btn-primary">Validasi</button>';
                    //         }
                    //     }else{
                    //         return '<span class="badge badge bg-success">sukses</span>';
                    //     }
                    // })
                    ->rawColumns(['pelaksanaan','validasi'])
                    ->make(true);
        }
    }

    public function storeValidasi(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->id;
            $cby= $request->cby;
            $tugas = DetailAktivitasUmrahModel::where('id', $id)->first();
            $tugas->update([
                'validate' => 'Y',
                'validate_by' => $cby
            ]);

        DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Sukses'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function jadwalTugasKetuaPembimbing()
    {
        $user_id = Auth::user()->id;
        $aktitivitasModel = new AktivitasUmrahModel();
        $jadwal      = $aktitivitasModel->getNameTourcodeByPembimbing($user_id);
        return view('users.tugas.index', compact('jadwal'));
    }

    public function createStatusTugasByPembimbingWithFile(Request $request)
    {
        // return $request->all();
               
        DB::beginTransaction();
        try {

            $id = $request->id;
            $status = $request->status;
            $user_id = $request->user_id;

            $tugasModel = new DetailAktivitasUmrahModel();
    
            // jika status Y, tidak perlu alasan, jadi kosongkan saja
            $alasan  = $request->alasan;
            $aktitivitas_umrah_id = $request->aktivitasUmrahId;
            

            $tugas = $tugasModel->where('id', $id)->first();

            $require_image = $tugas->require_image;
            $nilai_akhir   = 0;

            if ($require_image == 'Y') { // JIKA REEQUIRE FILE UPLOAD FOTO
                $nilai_akhir = 0;
            }else{
                $nilai_akhir  = 1;
            }

            // jika file gambar ada
            if ($request->image != '' ) {

                // cek jika file tidak kosong, hapus file di direktori
                // $image_path = public_path('/'.$tugas->file);
                // if(File::exists($image_path)) {
                //     File::delete($image_path);
                // }

                // $path_img = public_path('images/tugas/');

                // $data = $request->image;
                // $image_array_1 = explode(";", $data);
                // $image_array_2 = explode(",", $image_array_1[1]);
                // $base64 = base64_decode($image_array_2[1]);
                // $imageName = uniqid().'.jpg';

                // // VALIDASI IMAGE

                // $fileName  = $path_img.$imageName;

                // #simpan ke tb
                // $file  = 'images/tugas/'.$imageName;
                // #simpan ke direktori
                // file_put_contents($fileName, $base64);

                $filename = $request->image->store('tugas','public');

            }else{
                $filename = NULL;
            }

            // GET NILAI_POINT DARI MASTER_TUGAS
            $master_tugas = TugasModel::select('nilai_point')->where('id',  $tugas->master_tugas_id)->first(); 

            $tugas->update([
                'status' => $status,
                'alasan' => $alasan,
                'file'   => $filename,
                'nilai_akhir' => $status == 'N' ? 0 : $nilai_akhir * $master_tugas->nilai_point,
                'create_by'   => $user_id,
                'created_at'   => $tugas->created_at,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // HITUNG TAHAPAN TUGAS,
            $count_tugas = $tugasModel->where('aktivitas_umrah_id', $aktitivitas_umrah_id)
                            ->where('status','=','')->count();

            DB::commit();
            return ResponseFormatter::success([
                'message' => $count_tugas == 0 ? 'Selesai' :'Sukses'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }

    }

    public function uploadPelaksanaan(Request $request)
    {
		// return $request->all();
		
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
               'image' => 'nullable|image|mimes:jpg,png,jpeg,JPG',
                'docx' => 'mimes:doc,pdf,docx,zip,ppt,pptx'

            ]);

            // $this->validate($request, [
                // 'image' => 'nullable|image|mimes:jpg,png,jpeg,JPG',
                // 'docx' => 'mimes:doc,pdf,docx,zip,ppt,pptx'
            // ]);

            if ($validator->fails()) {
                return redirect()->back()->with(['warning' => 'Cek kembali format file yang di upload, coba lagi']);
            }

            $id = $request->id;
            $status = $request->status;

            $tugasModel = new DetailAktivitasUmrahModel();
    
            // jika status Y, tidak perlu alasan, jadi kosongkan saja
            $aktitivitas_umrah_id = $request->aktivitasUmrahId;

            $tugas = $tugasModel->where('id', $id)->first();
            $image = $tugas->file;
            $docx = $tugas->file_doc;
            $alasan=  $request->note == '' ? $tugas->alasan : $request->note;

            $require_image = $tugas->require_image;
            $nilai_akhir   = 0;

            if ($require_image == 'Y') { // JIKA REEQUIRE FILE UPLOAD FOTO
                if ($request->image != '' AND $status == 'Y') {
                    $nilai_akhir = 2; // 1 + 1 (NILAI TAMBAHAN KUSUSUS UNTUK REQUIRE FOTO)
                }else{
                    $nilai_akhir = 0;
                }
            }else{
                if ($status == 'Y') {
                    $nilai_akhir  = 2;
                }else {
                    $nilai_akhir  = 0;
                }
            }
			
			if($request->image != ''){
			 if ($request->file('image')) {
                //  cek jika file tidak kosong, hapus file di direktori
                if ($image != null) {
                    File::delete(storage_path('app/public/'.$tugas->file));
                }
                // $filename = $request->image->store('tugas','public');

                 //JIKA FOLDERNYA BELUM ADA
                // if (!File::isDirectory($this->path)) {
                //     //MAKA FOLDER TERSEBUT AKAN DIBUAT
                //     File::makeDirectory($this->path);
                // }

                //MENGAMBIL FILE IMAGE DARI FORM
                $file = $request->file('image');
                //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
                $fileName =  Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                //UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
                Image::make($file)->save($this->path . '/' . $fileName);

                //LOOPING ARRAY DIMENSI YANG DI-INGINKAN
                //YANG TELAH DIDEFINISIKAN PADA CONSTRUCTOR
                // foreach ($this->dimensions as $row) {
                //     dd($row);
                // }
                //MEMBUAT CANVAS IMAGE SEBESAR DIMENSI YANG ADA DI DALAM ARRAY 
                $canvas = Image::canvas(245, 245);
                //RESIZE IMAGE SESUAI DIMENSI YANG ADA DIDALAM ARRAY 
                //DENGAN MEMPERTAHANKAN RATIO
                $resizeImage  = Image::make($file)->resize(245, 245, function($constraint) {
                    $constraint->aspectRatio();
                });
                
                //CEK JIKA FOLDERNYA BELUM ADA
                // if (!File::isDirectory($this->path . '/' . $row)) {
                //     //MAKA BUAT FOLDER DENGAN NAMA DIMENSI
                //     File::makeDirectory($this->path . '/' . $row);
                // }
                
                //MEMASUKAN IMAGE YANG TELAH DIRESIZE KE DALAM CANVAS
                $canvas->insert($resizeImage, 'center');
                //SIMPAN IMAGE KE DALAM MASING-MASING FOLDER (DIMENSI)
                $canvas->save($this->path . '/' . $fileName);

                $fileName = 'tugas/'.$fileName;
				}else{
					$fileName = $image;
				}
			}else{
				$fileName = $image;
			}

            

            if ($request->hasFile('docx')) {
                //  cek jika file tidak kosong, hapus file di direktori
                if ($docx != null) {
                    File::delete(storage_path('app/public/'.$tugas->file_doc));
                }
                $fileDocx = $request->docx->store('tugas/docx','public');
                $fileDocxName = $request->docx->getClientOriginalName();
            }else{
                $fileDocx = NULL;
                $fileDocxName = NULL;
            }

            // GET NILAI_POINT DARI MASTER_TUGAS
            $master_tugas = TugasModel::select('nilai_point')->where('id',  $tugas->master_tugas_id)->first(); 
            $tugas->update([
                'status' => $status,
                'alasan' => $alasan,
                'nilai_point' => $master_tugas->nilai_point,
                'file' =>  $fileName,
                'file_doc' => $fileDocx,
                'file_doc_name' => $fileDocxName,
                'nilai_akhir' => $nilai_akhir * $master_tugas->nilai_point,
                'create_by'   => Auth::user()->id,
                'created_at'   => $tugas->created_at,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();

            return redirect()->back()->with(['success' => 'Berhasil Simpan Tugas SOP']);


        } catch (\Exception $e) {
            DB::rollback();
			return $e->getMessage();
            return redirect()->back()->with(['warning' => 'Gagal Simpan Tugas SOP']);
        }
    }

    public function uploadPelaksanaanTanpaResizaImage(Request $request)
    {
		// return $request->all();
		
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
               'image' => 'nullable|image|mimes:jpg,png,jpeg,JPG',
                'docx' => 'mimes:doc,pdf,docx,zip,ppt,pptx'

            ]);

            // $this->validate($request, [
                // 'image' => 'nullable|image|mimes:jpg,png,jpeg,JPG',
                // 'docx' => 'mimes:doc,pdf,docx,zip,ppt,pptx'
            // ]);

            if ($validator->fails()) {
                return redirect()->back()->with(['warning' => 'Cek kembali format file yang di upload, coba lagi']);
            }

            $id = $request->id;
            $status = $request->status;

            $tugasModel = new DetailAktivitasUmrahModel();
    
            // jika status Y, tidak perlu alasan, jadi kosongkan saja
            $aktitivitas_umrah_id = $request->aktivitasUmrahId;

            $tugas = $tugasModel->where('id', $id)->first();
            $image = $tugas->file;
            $docx = $tugas->file_doc;
            $alasan=  $request->note == '' ? $tugas->alasan : $request->note;

            $require_image = $tugas->require_image;
            $nilai_akhir   = 0;

            if ($require_image == 'Y') { // JIKA REEQUIRE FILE UPLOAD FOTO
                if ($request->image != '' AND $status == 'Y') {
                    $nilai_akhir = 2; // 1 + 1 (NILAI TAMBAHAN KUSUSUS UNTUK REQUIRE FOTO)
                }else{
                    $nilai_akhir = 0;
                }
            }else{
                if ($status == 'Y') {
                    $nilai_akhir  = 2;
                }else {
                    $nilai_akhir  = 0;
                }
            }
			
			if($request->image != ''){
			 if ($request->file('image')) {
                //  cek jika file tidak kosong, hapus file di direktori
                if ($image != null) {
                    File::delete(storage_path('app/public/'.$tugas->file));
                }
                $filename = $request->image->store('tugas','public');

                $fileName = $filename;
				}else{
					$fileName = $image;
				}
			}else{
				$fileName = $image;
			}

            
            if ($request->hasFile('docx')) {
                //  cek jika file tidak kosong, hapus file di direktori
                if ($docx != null) {
                    File::delete(storage_path('app/public/'.$tugas->file_doc));
                }
                $fileDocx = $request->docx->store('tugas/docx','public');
                $fileDocxName = $request->docx->getClientOriginalName();
            }else{
                $fileDocx = NULL;
                $fileDocxName = NULL;
            }

            // GET NILAI_POINT DARI MASTER_TUGAS
            $master_tugas = TugasModel::select('nilai_point')->where('id',  $tugas->master_tugas_id)->first(); 
            $tugas->update([
                'status' => $status,
                'alasan' => $alasan,
                'nilai_point' => $master_tugas->nilai_point,
                'file' =>  $fileName,
                'file_doc' => $fileDocx,
                'file_doc_name' => $fileDocxName,
                'nilai_akhir' => $nilai_akhir * $master_tugas->nilai_point,
                'create_by'   => Auth::user()->id,
                'created_at'   => $tugas->created_at,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();

            return redirect()->back()->with(['success' => 'Berhasil Simpan Tugas SOP']);


        } catch (\Exception $e) {
            DB::rollback();
			return $e->getMessage();
            return redirect()->back()->with(['warning' => 'Gagal Simpan Tugas SOP']);
        }
    }

    public function saveJumlahPotensialJamaahByPembimbing(Request $request)
    {
       
        DB::beginTransaction();
        try {
                
            $id = $request->id;
            $status = $request->status;
            $umrah = AktivitasUmrahModel::where('id', $id)->first();
            if($status == 'before'){
                $umrah->update([
                    'jumlah_potensial_jamaah_before' => $request->count
                ]);
            }else{
                $umrah->update([
                    'jumlah_potensial_jamaah_after' => $request->count
                ]);
            }

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Sukses'
            ],200); 

        } catch (\Exception $th) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }

        return redirect()->back()->with(['success' => 'Berhasil simpan']);
    }

    public function saveJumlahPotensialJamaahAfter(Request $request)
    {
        $request->validate([
            'count' => 'required',
        ]);

        $id = $request->id;
        $umrah = DB::table('aktivitas_umrah')->where('id', $id)->first();
        $umrah->update([
            'jumlah_potensial_jamaah_after' => $request->count
        ]);

        return redirect()->back()->with(['success' => 'Berhasil simpan']);
    }

    public function validasiTugasUmrah(Request $request)
    {
            
        DB::beginTransaction();
        try {

            $id          =   $request->id;
            $aktivitasId = $request->aktivitasId;

            foreach ($id as $key => $value) {
                DB::table('detail_aktivitas_umrah')->where('aktivitas_umrah_id', $aktivitasId)->where('id', $value)
                   ->update(['validate' => 'Y']);

            }

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil validasi'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function updateNilaiAkhirPertimbangan()
    {
       
        DB::beginTransaction();
        try {
                
            $id    = request()->id;
            $nilai = request()->nilai;

            // GET DETAIL AKTIVITAS UMRAH BY ID
            $tugasModel = DetailAktivitasUmrahModel::where('id', $id)->first();
            // UPDATE NILAI AKHIR NYA
            $tugasModel->update([
                'nilai_akhir' => $nilai,
                'note' => 'Nilai telah dipertimbangkan'
            ]);
            
            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Sukses'
            ],200); 

        } catch (\Exception $th) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }

        return redirect()->back()->with(['success' => 'Berhasil simpan']);
    }
	
	public function storeCatatanEvaluasi(Request $request, $id)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'catatan' => 'string'
             ]);

           $aktitivitas = AktivitasUmrahModel::where('id', $id)->first(); 

           $aktitivitas->update([
                'catatan' => $request->catatan,
            ]);

            DB::commit();
                        
            return redirect()->back()->with(['success' => 'Catatan telah disimpan']);
            
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function detailSopNByAktivitasUmrah($id)
    {
        $aktitivitas = new AktivitasUmrahModel;
        $judul_sop   = $aktitivitas->getDetailSopNByAktivitasUmrah($id);
        $status      = 'N';
        
        $results    = [];
        foreach ($judul_sop as $value) {
            $sop = $aktitivitas->getListSopByStatus($value->id,$status, $value->id_judul);
            $results[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'judul' => $value->nama,
                'sop' => $sop
            ];
        }

        if (count($results) == 0) {
            return redirect()->route('dashbaord.analytics');
        }else{
            return view('dashboard.analitik.detail-sop', compact('results'));
        }

    }

    public function ReNilaiSop(Request $request)
    {
            
        DB::beginTransaction();
        try {

            $id          =   $request->id;
            $aktivitasId = $request->aktivitasId;

            // GET master_tugas_id where id
            foreach ($id as $key => $value) {
                
                // DB::table('detail_aktivitas_umrah')->where('aktivitas_umrah_id', $aktivitasId)->where('id', $value)
                //    ->update(['validate' => 'Y']);
                $master_tugas  = DB::table('detail_aktivitas_umrah as a')
                                        ->select('b.nilai_point')
                                        ->join('master_tugas as b','a.master_tugas_id','=','b.id')
                                        ->where('a.aktivitas_umrah_id', $aktivitasId)
                                        ->where('a.id', $value)
                                        ->first();

               $update =  DB::table('detail_aktivitas_umrah')
                    ->where('aktivitas_umrah_id', $aktivitasId)
                    ->where('id', $value)
                    ->update([
                        'validate' => 'Y',
                        'status' => 'Y',
                        'nilai_point' => $master_tugas->nilai_point,
                        'nilai_akhir' => $master_tugas->nilai_point
                    ]);
            }

            DB::commit();
            return ResponseFormatter::success([
                'data' => $update,
                'message' => 'Berhasil ubah nilai'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function kuisionerByTourcodePembimbing($umrah_id, $aktivitasumrahId){

        $kuisionerModel = new KuisionerModel();

        $gf = new Globalprovider();
        
        #get kuisioner by umrah id dan aktivitas umrah id
        $kuisioner = $kuisionerModel->getKuisionerByAktivitasUmrah($umrah_id, $aktivitasumrahId);

        #get pertanyaan by umrah_id dan aktivitas_umrah_id
        $pertanyaan = $kuisionerModel->getPertanyaanByUmrahIdAndAktivitasUmrahId($kuisioner->kuisioner_umrah_id, $kuisioner->aktivitas_umrah_id);



        $result_kuisioner = [];
        foreach ($pertanyaan as $value) {
            $jawaban = $kuisionerModel->getJumlahJawaban($umrah_id,$kuisioner->aktivitas_umrah_id, $value->id);
            $result_kuisioner[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'isi' => $value->isi,
                'jawaban' => $jawaban
            ];
        }

        #pertanyaan essay
        $pertanyaan_essay = $kuisionerModel->getPertanyaanEssayByUmrahIdAndAktivitasUmrahId($kuisioner->kuisioner_umrah_id, $kuisioner->aktivitas_umrah_id);

        $result_kuisioner_essay = [];
        foreach ($pertanyaan_essay as $key => $value) {
            $jawaban_essay = $kuisionerModel->getJumlahJawabanEssay($umrah_id,$kuisioner->aktivitas_umrah_id, $value->id);
            $result_kuisioner_essay[] = [
                'id' => $value->id,
                'isi' => $value->isi,
                'jawaban' => $jawaban_essay
            ];

        }

        return view('aktivitasumrah.detail-kuisioner', compact('kuisioner','result_kuisioner','gf','result_kuisioner_essay'));

    }
}
