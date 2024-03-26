<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\Providers\Globalprovider;
use App\AktivitasUmrahModel;
use App\DetailAktivitasUmrahModel;
use App\TugasModel;
use App\Helpers\ResponseFormatter;
use DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Image;
use Auth;
use File;
use Str;

class JadwalUmrahController extends Controller
{
    public function listJadwalUmrah()
    {
        try {

            $user_id =  auth()->user()->id;
            $aktitivitasModel = new AktivitasUmrahModel();
            $jadwal      = $aktitivitasModel->getHistoryNameTourcodeByPembimbingListJudul($user_id);
    
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
    
            return ResponseFormatter::success([
                'message' => 'jadwal umrah',
                'umrah' => $result,
            ]);

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => $e-getMessage(),
            ]);
        }

    }

    public function listJudulBySOP()
    {
        try {

            $id = request('id');

            $user_id =  auth()->user()->id;

            $aktitivitasModel = new AktivitasUmrahModel();
            $jadwal      = $aktitivitasModel->getNameTourcodeByPembimbingByAkunPembimbingNew($user_id, $id);
            $catatan     = $aktitivitasModel->select('catatan')->where('id', $id)->first();
            $judul       = $aktitivitasModel->getListSopByAktivitasUmrahId($id);

            return ResponseFormatter::success([
                'message' => 'List judul',
                'jadwal' => $jadwal,
                'judul' => $judul,
                'catatan' => $catatan
            ]);

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => $e-getMessage(),
            ]);
        }
    }

    public function listSopByJudul()
    {
        try {

            $judul_id = request('judul_id');
            $aktivitas_umrah_id = request('aktivitas_umrah_id');

            $user_id =  auth()->user()->id;

            $judul = DB::table('master_judul_tugas')->select('nama')->where('id', $judul_id)->first();

            $aktitivitasModel = new AktivitasUmrahModel();
            $sop   = $aktitivitasModel->getListTugasByPembimbingByJudul($aktivitas_umrah_id, $judul_id);
            
            return ResponseFormatter::success([
                'message' => 'List SOP',
                'aktivitas_umrah_id' => $aktivitas_umrah_id,
                'judul' => $judul,
                'sop' => $sop
            ]);

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => $e-getMessage(),
            ]);
        }
    }

    public function createTugasSop(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'image' => 'nullable|image|mimes:jpg,png,jpeg,JPG',
                'docx' => 'nullable|mimes:doc,pdf,docx,zip,ppt,pptx'
 
             ]);

             if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $path = storage_path('app/public/tugas');

            $id = $request->id;
            $status = $request->status;

            if ($status == 'N' && $request->note == '') {
                return ResponseFormatter::error([
                    'message' => 'Sampaikan alasan jika tidak melaksanakan',
                ]);
            }

            $tugasModel = new DetailAktivitasUmrahModel();
            $aktitivitas_umrah_id = $request->aktivitasUmrahId;
            $tugas = $tugasModel->where('id', $id)->first();
            $image = $tugas->file;
            $docx  = $tugas->file_doc;
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
   
                   //MENGAMBIL FILE IMAGE DARI FORM
                   $file = $request->file('image');
                   //MEMBUAT NAME FILE DARI GABUNGAN TIMESTAMP DAN UNIQID()
                   $fileName =  Carbon::now()->timestamp . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                   //UPLOAD ORIGINAN FILE (BELUM DIUBAH DIMENSINYA)
                   Image::make($file)->save($path . '/' . $fileName);
   
                   
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
                   $canvas->save($path . '/' . $fileName);
   
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
                'create_by'   => auth()->user()->id,
                'created_at'   => $tugas->created_at,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Sukses'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => $e->getMessage(),
            ]);
        }
    }

    // Create SOP tanpa resize image
    public function createTugasSopWithOutResizeImage(Request $request)
    {
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'image' => 'nullable|image|mimes:jpg,png,jpeg,JPG',
                'docx' => 'nullable|mimes:doc,pdf,docx,zip,ppt,pptx'
 
             ]);

             if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $id = $request->id;
            $status = $request->status;

            if ($status == 'N' && $request->note == '') {
                return ResponseFormatter::error([
                    'message' => 'Sampaikan alasan jika tidak melaksanakan',
                ]);
            }

            $tugasModel = new DetailAktivitasUmrahModel();
            $aktitivitas_umrah_id = $request->aktivitasUmrahId;
            $tugas = $tugasModel->where('id', $id)->first();
            $image = $tugas->file;
            $docx  = $tugas->file_doc;
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
                'create_by'   => auth()->user()->id,
                'created_at'   => $tugas->created_at,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Behrasil menyimpan SOP'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => $e->getMessage(),
            ]);
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
                'message' => 'Berhasil menyimpan'
            ],200); 

        } catch (\Exception $th) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal menyimpan!',
                'error' => $e->getMessage()
            ],500);

        }

    }

    public function storeCatatanEvaluasi(Request $request)
    {
        DB::beginTransaction();
        try {

            $id         = $request->id;
            $catatan    = $request->catatan;

            $validator = Validator::make($request->all(), [
                'catatan' => 'string'
             ]);

           $aktitivitas = AktivitasUmrahModel::where('id', $id)->first(); 

           $aktitivitas->update([
                'catatan' => $catatan,
            ]);

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Catatan berhasil disimpan!'
            ],200);
            
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal menyimpan!',
                'error' => $e->getMessage()
            ],500);
        }
    }

    public function historyTugasJadwalUmrah()
    {
        try {
            $user_id = auth()->user()->id;
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
            return ResponseFormatter::success([
                'message' => 'List histori!',
                'data' => $result
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ],500);
        }
    }

    public function pageDetaiHistoryTugasByPembimbing($id)
    {
        try {

            $aktitivitasModel = new AktivitasUmrahModel();
            $jadwal      = $aktitivitasModel->getNameTourcodeByAktivitasUmrahId($id);
            $judul_sop   =  $aktitivitasModel->getListTugasByAktivitasUmrahId($id);

            $result = [];
            foreach ($judul_sop as $value) {
                $result[] = [
                    'id' => $value->id,
                    'nama' => $value->nama,
                    'tugas' => $aktitivitasModel->getListTugasByMasterJudulId($value->id)
                ];
            }

            return ResponseFormatter::success([
                'message' => 'List histori!',
                'jadwal' => $jadwal,
                'sop' => $result
            ],200);

        }catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ],500);
        }

    }
	
	
	public function migrationAktivitasUmrahToMthowwif(Request $request)
	{
		DB::beginTransaction(); 
		 try {

           //get data muthowwif dari table pembimbing
		   $data = DB::table('pembimbing')->where('id','>=',27)->get();
		   
		   // get aktivitas umrah by pembimbin_id
		   $results = [];
		   foreach($data as $item){
			   $aktivitas = DB::table('aktivitas_umrah')->where('pembimbing_id', $item->id)->get();
			   if(count($aktivitas) != 0){
				   
				   // $results[] = [
					// 'pembimbing_id' => $item->id,
					// 'pembimbing_name' => $item->nama,
					// 'aktivitas'  => $aktivitas
			   // ];
			   
			   // get detail_aktivitas_umrah
			   $result_details = [];
			   foreach($aktivitas as $val){
				   $detail_aktivitas_umrah = DB::table('detail_aktivitas_umrah')->where('aktivitas_umrah_id', $val->id)->get();
				  
				   
				   // save to tabel aktivitas_umrah_muthoowwif
				  $saves = DB::table('aktivitas_umrah_muthowwif')->insertGetId([
						'id' => Str::random(30),
						'idx' => $val->id, // from id aktivitas umrah lama
						'muthowwif_id' => $val->pembimbing_id, // from id pembimbin_id 
						'umrah_id' => $val->umrah_id,
						'master_sop_id' => $val->master_sop_id,
						'status' => $val->status,
						'status_tugas' => $val->status_tugas,
						'jumlah_potensial_jamaah_before' => $val->jumlah_potensial_jamaah_before,
						'jumlah_potensial_jamaah_after' => $val->jumlah_potensial_jamaah_after,
						'catatan' => $val->catatan,
						'create_by' => $val->create_by,
						'isdelete' => $val->isdelete,
						'nonaktif' => $val->nonaktif,
						'updated_by' => $val->updated_by,
						'created_at' => $val->created_at,
						'updated_at' => $val->updated_at
				   ]);
				    
				   // save to table detail_aktivitas_umrah_muthowwif
				   foreach($detail_aktivitas_umrah as $detail){
					   DB::table('detail_aktivitas_umrah_muthowwif')->insert([
							'id' => Str::random(30),
							'idx' => $detail->id, // id detail lama,
							'aktivitas_umrah_id' => $detail->aktivitas_umrah_id,
							'master_sop_id' => $detail->master_sop_id,
							'master_judul_tugas_id' => $detail->master_judul_tugas_id,
							'master_tugas_id' => $detail->master_tugas_id,
							'nomor_tugas' => $detail->nomor_tugas,
							'nama_tugas' => $detail->nama_tugas,
							'nilai_point' => $detail->nilai_point,
							'require_image' => $detail->require_image,
							'status' => $detail->status,
							'alasan' => $detail->alasan,
							'file' => $detail->file,
							'file_doc' => $detail->file_doc,
							'file_doc_name' => $detail->file_doc_name,
							'nilai_akhir' => $detail->nilai_akhir,
							'nilai_validate' => $detail->nilai_validate,
							'note' => $detail->note,
							'validate' => $detail->validate,
							'validate_by' => $detail->validate_by,
							'create_by' => $detail->create_by,
							'created_at' => $detail->created_at,
							'updated_at' => $detail->updated_at
					   ]);
				   }
				   
				}
				
			   
			   }
		   }
			
			DB::commit();
            return ResponseFormatter::success([
                'message' => 'migrasi ok!',
				// 'data' => $results
            ],200);

        }catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'error' => $e->getMessage()
            ],500);
        }

	}
}






















