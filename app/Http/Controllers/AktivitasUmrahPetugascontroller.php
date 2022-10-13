<?php

namespace App\Http\Controllers;

use App\TugasModel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\AktivitasUmrahPetugasModel;
use App\SopPetugasModel;
use App\TugasForPetugasModel;
use App\DetailAktivitasUmrahPetugasModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Providers\Globalprovider;
use Carbon\Carbon;
use File;
use PDF;
use Image;

class AktivitasUmrahPetugascontroller extends Controller
{
    public $path;
    public function __construct()
    {
        //DEFINISIKAN PATH
        $this->path = storage_path('app/public/petugas/tugas');
    }

    public function indexPetugas()
    {
        return view('aktivitasumrahpetugas.index');
    }

    public function pageTahapanTugasByPetugas()
    {
        // $user_id = Auth::user()->id;
        // $aktitivitasModel = new AktivitasUmrahModel();
        // $jadwal      = $aktitivitasModel->getNameTourcodeByPembimbing($user_id);

        $user_id = Auth::user()->id;
        $aktitivitasModel = new AktivitasUmrahPetugasModel();
        $jadwal      = $aktitivitasModel->getHistoryNameTourcodeByPetugasListJudul($user_id);

        $gf         = new Globalprovider();
        $result     = [];

        foreach ($jadwal as $value) {
            $result[] = [
                'id' => $value->id,
                'tourcode' => $value->tourcode,
            ];
        }

        return view('users.petugas.index', compact('jadwal','aktitivitasModel','result'));
    }

    public function pageFormTugasByPetugasByJudul($aktitivitas_umrah_petugas_id)
    {

        $user_id = Auth::user()->id;

        $aktitivitasModel = new AktivitasUmrahPetugasModel();
        $jadwal      = $aktitivitasModel->getNameTourcodeByPetugasByAkunPetugas($user_id, $aktitivitas_umrah_petugas_id);
        $catatan     = $aktitivitasModel->select('catatan')->where('id', $aktitivitas_umrah_petugas_id)->first();

        return view('users.petugas.listjudul', compact('jadwal','aktitivitasModel','aktitivitas_umrah_petugas_id','catatan'));
    }

    public function pageDetaiTugasPetugasByJudul($aktitivitas_umrah_petugas_id, $id)
    {
        // $user_id = Auth::user()->id;
        // $aktitivitasModel = new AktivitasUmrahModel();
        // $jadwal      = $aktitivitasModel->getNameTourcodeByAktivitasUmrahId($id);
        // return $jadwal;
        $judul   = DB::table('master_judul_tugas_petugas')->select('nama')->where('id', $id)->first();
        $user_id = Auth::user()->id;

        return view('users.tugas.form-petugas', ['judul' => $judul,'user_id' => $user_id,'aktitivitas_umrah_petugas_id' => $aktitivitas_umrah_petugas_id]);
    }

    public function getDetailTugasByPetugas($aktitivitas_umrah_petugas_id, $id)
    {
        $aktitivitasModel = new AktivitasUmrahPetugasModel();
        $data             = $aktitivitasModel->getListTugasByPembimbingByJudul($aktitivitas_umrah_petugas_id, $id);
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

    public function uploadPelaksanaanPetugas(Request $request)
    {
		
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
               'image' => 'nullable|image|mimes:jpg,png,jpeg,JPG',
                'docx' => 'mimes:doc,pdf,docx,zip,ppt,pptx'

            ]);

            if ($validator->fails()) {
                return redirect()->back()->with(['warning' => 'Cek kembali format file yang di upload, coba lagi']);
            }

            $id = $request->id;
            $status = $request->status;

            $tugasModel = new DetailAktivitasUmrahPetugasModel();
    
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
                    File::delete(storage_path('app/public/petugas/'.$tugas->file));
                }
                

                //MENGAMBIL FILE IMAGE DARI FORM
                $file = $request->file('image');
                //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
                $fileName =  Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                //UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
                Image::make($file)->save($this->path . '/' . $fileName);

                //LOOPING ARRAY DIMENSI YANG DI-INGINKAN
                //YANG TELAH DIDEFINISIKAN PADA CONSTRUCTOR
                
                //MEMBUAT CANVAS IMAGE SEBESAR DIMENSI YANG ADA DI DALAM ARRAY 
                $canvas = Image::canvas(245, 245);
                //RESIZE IMAGE SESUAI DIMENSI YANG ADA DIDALAM ARRAY 
                //DENGAN MEMPERTAHANKAN RATIO
                $resizeImage  = Image::make($file)->resize(245, 245, function($constraint) {
                    $constraint->aspectRatio();
                });
                
                //MEMASUKAN IMAGE YANG TELAH DIRESIZE KE DALAM CANVAS
                $canvas->insert($resizeImage, 'center');
                //SIMPAN IMAGE KE DALAM MASING-MASING FOLDER (DIMENSI)
                $canvas->save($this->path. '/' . $fileName);

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
                $fileDocx = $request->docx->store('/petugas/tugas/docx','public');
                $fileDocxName = $request->docx->getClientOriginalName();
            }else{
                $fileDocx = NULL;
                $fileDocxName = NULL;
            }
            
            // GET NILAI_POINT DARI MASTER_TUGAS
            $master_tugas = TugasForPetugasModel::select('nilai_point')->where('id',  $tugas->master_tugas_petugas_id)->first(); 

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

    public function dataTableListData(Request $request)
    {
        $orderBy = 'c.tourcode';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'c.tourcode';
                break;
        }

        // $aktitivitasModel = new AktivitasUmrahModel();
        $data             = DB::table('aktivitas_umrah_petugas as a')
                            ->join('petugas as b','b.id','=','a.petugas_id')
                            ->join('umrah as c','c.id','=','a.umrah_id')
                            ->select('a.id','b.nama as petugas','c.tourcode', 'a.status','c.dates','c.id as umrah_id','c.start_date','c.end_date','a.status_tugas',
                            DB::raw('(select sum(nilai_akhir) from detail_aktivitas_umrah_petugas where aktivitas_umrah_petugas_id = a.id) as nilai_akhir'))
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

    public function show($id)
    {
        // GET TOURCODE DAN NAMA PEMBIMBING
        $aktitivitasModel = new AktivitasUmrahPetugasModel();
        $aktitivitas      = $aktitivitasModel->getNameTourcodeAndPetugas($id);
        $judul_sop        = $aktitivitasModel->getListTugasByAktivitasUmrahId($id);

        $sopModel     = SopPetugasModel::select('name');
        $sop = $sopModel->where('id', $aktitivitas->master_sop_petugas_id)->first();
        $title = 'Petugas';

        return view('aktivitasumrahpetugas.detail', compact('aktitivitas','judul_sop','aktitivitasModel','sop','title'));
    }

    public function updateStatusAktifitasUmrah(Request $request)
    {
            
        DB::beginTransaction();
        try {

            $id = $request->id;

            // HITUNG TAHAPAN TUGAS
            $tugasModel = new DetailAktivitasUmrahPetugasModel();
            $count_tugas = $tugasModel->where('aktivitas_umrah_petugas_id', $id)
                            ->where('status','=','')->count();
            // JIKA SAMA DENGAN 0, MAKA UPDATE STATUS DI AKTIVITAS_UMRAH MENJADI = FINISH
            $updateStatus = false;

            $count_validate = $tugasModel->where('aktivitas_umrah_petugas_id', $id)
                            ->where('validate','=','N')->count();

            if ($count_tugas  > 0) {
                return ResponseFormatter::success([
                    'data' => 'status',
                    'message' => 'Gagal, Petugas belum meyelesaikan tahapan tugas'
                ],200);
            }
            else{
                $aktitivitas = AktivitasUmrahPetugasModel::where('id', $id)->first();
                $updateStatus =  $aktitivitas->update(['status' => 'finish']); 
            }
            
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
           $tugas = AktivitasUmrahPetugasModel::where('id', $id)->first();
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
}
