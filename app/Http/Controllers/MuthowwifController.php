<?php

namespace App\Http\Controllers;

use App\User;
use App\PembimbingModel;
use App\SopModel;
use App\MuthowwifModel;
use App\AktivitasUmrahModel;
use App\KuisionerModel;
use App\AktivitasUmrahMuthowwifModel;
use App\DetailAktivitasUmrahMuthowwifModel;
use App\TugasModel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use App\Providers\Globalprovider;
use Validator;

class MuthowwifController extends Controller
{
    public function index()
    {
        return view('muthowwif.index');
    }

    public function listData()
    {
        $muthowwif = MuthowwifModel::select('id','nama')->where('isdelete',0)->orderBy('nama','asc')->get();

        if (request()->ajax()) 
        {
            return DataTables::of($muthowwif)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '<a href="'.route('muthowwif.edit', $item->id).'" class="btn btn-sm fa fa-edit text-primary" title="Edit"></a> 
                        <button onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->nama.'" class="btn btn-sm fa fa-trash text-danger" title="Hapus"></button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function create()
    {
        return view('muthowwif.create');
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $request->validate([
                    'email' => 'required|email|unique:users|max:255',
                ]);
            // BUAT AKUN BARU DI USERS
            $password = $request->password == '' ? '12345678' : $request->password;
     
            $user = User::create([
                 'name' => $request->name,
                 'email' => $request->email,
                 'password' => Hash::make($password),
                 'aps_level_id' => 3
             ]);
     
             MuthowwifModel::create([
                 'user_id' => $user->id,
                 'nama' => $request->name,
                 'status' => 'MUTHOWWIF',
                 'expired_passport' => date('Y-m-d', strtotime($request->expired_passport)),
                 'create_by' => Auth::user()->id
             ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }
        return redirect()->route('muthowwif.create')->with(['success' => 'Muthowwif berhasil disimpan']);

    }

    public function show($id)
    {
        // GET TOURCODE DAN NAMA MUTHOWWIF
        $aktitivitasModel = new AktivitasUmrahMuthowwifModel();
        $aktitivitas      = AktivitasUmrahMuthowwifModel::getNameTourcodeAndPembimbing($id);
        $judul_sop        = AktivitasUmrahMuthowwifModel::getListTugasByAktivitasUmrahId($id);


        // jika status tugas adalah pembimbing, get sop pembimnbing dgn cara relasi
        $status_tugas = $aktitivitas->status_tugas; 
        $sop          = SopModel::select('name')->where('id', $aktitivitas->master_sop_id)->first();
        $title        = 'Muthowwif';

        return view('aktivitasumrahmuthowwif.detail', compact('aktitivitas','judul_sop','aktitivitasModel','sop','title'));
    }

    public function updateStatusAktifitasUmrah(Request $request)
    {
            
        DB::beginTransaction();
        try {

            $id = $request->id;            
            $aktitivitas              = AktivitasUmrahMuthowwifModel::where('id', $id)->first();
            # update status aktivitas umrah = finish / selesai
            $aktitivitas->update(['status' => 'finish']);        

            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Tugas telah selesai'
            ],200);
            
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
           $tugas = AktivitasUmrahMuthowwifModel::where('id', $id)->first();
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

    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = $request->id;
            
            // cek ke aktivitas umrah apakah id ini masih active
            $aktivitasUmrah = AktivitasUmrahModel::where('pembimbing_id', $id)
                            ->where('status','active')->count();
            // jika aktif tidak bisa di hapus
            // hanya jika sudah finsh boleh di hapus
            if($aktivitasUmrah == 0){
                $umrah = MuthowwifModel::where('id', $id)->first();
                $umrah->delete();
                $user = User::where('id', $umrah->user_id)->first();
                $user->delete();
            }else{
                return ResponseFormatter::error([
                   null,
                   'message' => ''
                ]); 
            }
            
            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus tugas'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function edit($id)
    {
        $pembimbing = MuthowwifModel::where('id', $id)->first();
        $user       = User::where('id', $pembimbing->user_id)->first();
        return view('muthowwif.edit', compact('pembimbing','user'));
    }

    public function update(Request $request, $id)
    {
        
        DB::beginTransaction();
        try {

            $request->validate([
                    'email' => 'required|email|max:255',
            ]);

            $pembimbing = MuthowwifModel::where('id', $id)->first();

            $userModel  = new User();
            $user       = $userModel->where('id', $pembimbing->user_id)->first();

            // cek email
            $cek_email = $userModel->where('email', $request->email)
                        ->where('id','!=',$pembimbing->user_id)->count();

            // BUAT AKUN BARU DI USERS
            $password = $request->password == '' ? $user->password : Hash::make($request->password);
            
            if($cek_email == 0){
                $user->update([
                     'name' => $request->name,
                     'email' => $request->email,
                     'password' => $password,
                 ]);

                  $pembimbing->update([
                     'nama' => $request->name,
                     'expired_passport' => date('Y-m-d', strtotime($request->expired_passport)),
                 ]);
            }else{
                return redirect()->back()->with(['warning' => 'Email sudah terdaftar']);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('muthowwif.index')->with(['success' => 'Muthowwif berhasil diubah']);

    }

    public function JadwalTugasMuthowwif()
    {
        return view('aktivitasumrahmuthowwif.index');
    }

    public function CreateJadwalTugasMuthowwif()
    {
        return view('aktivitasumrahmuthowwif.create');
    }

    public function getDataOptionMuthowwifForElement(Request $request)
    {
            $muthowwifModel = new MuthowwifModel();
            $data = $muthowwifModel->select('id','nama')->get();
           
            if($request->has('q')){
                $search = $request->q;
                $data = $muthowwifModel->where('nama','LIKE',"%$search%")->get();

        }

        return response()->json($data);

    }

    public function addFormMuthowiif()
    {
        // Add element
        $request = request()->data;
        if($request == '2'){

            $html = "<div class='col-md-12 mt-3 mb-4 fieldGroupEssay'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <select name='muthowwif_id[]'  class='form-control single-select muthowwif' required></select>
                            </div>
                            <div class='col-md-5'>
                                <select name='muthowwif_sop_id[]'  class='form-control single-select muthowwifsop' required></select>
                            </div>
                            <div class='col-md-1 mt-1'>
                            <button type='button' class='remove-essay'><i class='fas fa-trash text-danger'></i></button>
                            </div>
                        </div>
                    </div>";
            echo $html;
            exit;

        }
    }

    public function getDataOptionSopForElement(Request $request)
    {
            // $sopModel = new SopModel();
            $data     =  SopModel::getDataSopMuthowwif();
           
            if($request->has('q')){
                $search = $request->q;
                $data = $pembimbingModel->where('name','LIKE',"%$search%")->get();

        }

        return response()->json($data);

    }

    public function dataTableListData(Request $request)
    {
        $orderBy = 'c.tourcode';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'c.tourcode';
                break;
        }

        $data    = MuthowwifModel::getDataTableListData();    

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

        if($request->input('muthowwif') != ''){
                            $data->where('b.id', $request->muthowwif);
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

    public function getDataMuthowwif(Request $request)
    {

            $data = MuthowwifModel::select('id','nama')->where('isdelete',0)->get();
           
            if($request->has('q')){
            $search = $request->q;

            $data = MuthowwifModel::select("id","nama")

            		->where('nama','LIKE',"%$search%")

            		->get();

        }
        return response()->json($data);

    }

    public function getMuthowwifUmrahByMonth(Request $request, $month, $year)
    {
        try {

            $data = MuthowwifModel::getDataMuthowwifUmrahByMonth($month, $year);

            if($request->has('q')){
                $search = $request->q;
                $data   = MuthowwifModel::getDataMuthowwifUmrahByMonthAndSearch($month, $year, $search);

            }

            return response()->json($data);

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    // GET PAGE TUGAS BERDASARKAN LOGIN PEMBIMBNING , YANG DI SET OLEH ADMIN
    public function pageTahapanTugasByPembimbing()
    {
        $user_id = Auth::user()->id;

        $kuisionerModel = new KuisionerModel();

        $aktitivitasModel = new AktivitasUmrahMuthowwifModel();
        $jadwal      = $aktitivitasModel->getHistoryNameTourcodeByPembimbingListJudulNew($user_id);

        $gf         = new Globalprovider();
        $result     = [];
        foreach ($jadwal as $value) {

            #get kuisioner by umrah_id
            $kuisioner = $kuisionerModel->getKuisionerByUmrahIdPanelPembimbing($value->id);
            $result[] = [
                'id' => $value->id,
                'aktivitas_umrah_id' => $value->aktivitas_umrah_id,
                'tourcode' => $value->tourcode,
                'kuisioner' => $kuisioner
            ];
        }
        
        return view('users.muthowwif.index', compact('jadwal','aktitivitasModel','result'));
    }

    public function pageFormTugasByPembimbingByJudul($aktitivitas_umrah_id)
    {

        $user_id = Auth::user()->id;
        $aktitivitasModel = new AktivitasUmrahMuthowwifModel();
        $jadwal      = $aktitivitasModel->getNameTourcodeByPembimbingByAkunMuthowwif($user_id, $aktitivitas_umrah_id);
		$catatan     = $aktitivitasModel->select('catatan')->where('id', $aktitivitas_umrah_id)->first();

        // dd($jadwal);

        return view('users.muthowwif.listjudul', compact('jadwal','aktitivitasModel','aktitivitas_umrah_id','catatan'));
    }

    public function pageDetaiTugasByJudul($aktitivitas_umrah_id, $id)
    {
        // $user_id = Auth::user()->id;
        // $aktitivitasModel = new AktivitasUmrahModel();
        // $jadwal      = $aktitivitasModel->getNameTourcodeByAktivitasUmrahId($id);
        // return $jadwal;
        $judul = DB::table('master_judul_tugas')->select('nama')->where('id', $id)->first();
        $user_id = Auth::user()->id;

        return view('users.muthowwif.form', ['judul' => $judul,'user_id' => $user_id,'aktitivitas_umrah_id' => $aktitivitas_umrah_id]);
    }

    public function getDetailTugasByMuthowwif($aktitivitas_umrah_id, $id)
    {
        $aktitivitasModel = new AktivitasUmrahMuthowwifModel();
        $data             = $aktitivitasModel->getListTugasMuthowwifByJudul($aktitivitas_umrah_id, $id);
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

    public function createStatusTugasByPembimbingWithFile(Request $request)
    {
        // return $request->all();
               
        DB::beginTransaction();
        try {

            $id = $request->id;
            $status = $request->status;
            $user_id = $request->user_id;

            $tugasModel = new DetailAktivitasUmrahMuthowwifModel();
    
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

    public function createStatusTugasByPembimbing(Request $request)
    {
        $id = $request->id;
        $status = $request->status;

        // jika status Y, tidak perlu alasan, jadi kosongkan saja
        $alasan  = $status == 'Y' ? '' : $request->alasan;
        $aktitivitas_umrah_id = $request->aktivitasUmrahId;
        
        DB::beginTransaction();
        try {

            $tugasModel = new DetailAktivitasUmrahMuthowwifModel();

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

            $id     = $request->id;
            $status = $request->status;

            $tugasModel = new DetailAktivitasUmrahMuthowwifModel();
    
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
    
    public function updateNilaiAkhirPertimbangan()
    {
       
        DB::beginTransaction();
        try {
                
            $id    = request()->id;
            $nilai = request()->nilai;

            // GET DETAIL AKTIVITAS UMRAH BY ID
            $tugasModel = DetailAktivitasUmrahMuthowwifModel::where('id', $id)->first();
            // UPDATE NILAI AKHIR NYA
            $tugasModel->update([
                'nilai_akhir' => $nilai,
                'note' => 'Nilai telah dipertimbangkan'
            ]);  

            
            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Sukses'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }

        return redirect()->back()->with(['success' => 'Berhasil simpan']);
    }

    public function updateValidate(Request $request)
	{
		DB::beginTransaction();
		try{
			
			 // get data id dari client
			// tampung dalam sebuah array
			$id['id'] = $request->id;

			$idUser = Auth::user()->id;

			// looping array nya
			foreach ($id['id'] as $validateValue){
						$detail_aktivitas_umrah = DB::table('detail_aktivitas_umrah_muthowwif')
							->select('id', 'validate','nilai_akhir')
							->where('id', $validateValue)
							->first();

			if ($detail_aktivitas_umrah->validate == 'N'){
				DB::table('detail_aktivitas_umrah_muthowwif')
						->where('id', $detail_aktivitas_umrah->id)
						->update([
							'nilai_akhir' => $detail_aktivitas_umrah->nilai_akhir + 1,
							'nilai_validate' => 1,
							'validate' => 'Y',
							'validate_by' => $idUser,
							'updated_at' => now()
						]);
						
				}
						
			}
			
			DB::commit();
			return ResponseFormatter::success([
				'message' => 'Berhasil Validasi'
			], 200);
			
		}catch(\Exception $e){
			DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal Validasi!',
                'error'   => $e->getMessage()
            ]);
		}

       
    }
	
	public function updateValidateAll(Request $request){
		
			DB::beginTransaction();
			try {
				  
			$idUser = Auth::user()->id;  
				 
			// get data detail_aktivitas_umrah where aktivitas_umrah_id 
			$aktivitas_umrah_id = $request->activitasId;
			$detail_aktivitas_umrah = DB::table('detail_aktivitas_umrah_muthowwif')->where('aktivitas_umrah_id', $aktivitas_umrah_id)->get();
			foreach($detail_aktivitas_umrah as $value){
				// melakukan validasi hanya untuk status pelaksanaan = 'Y' dan belum di validasi
				if ($value->validate == 'N' AND $value->status == 'Y'){
					DB::table('detail_aktivitas_umrah_muthowwif')
							->where('id', $value->id)
							->update([
								'nilai_akhir' => $value->nilai_akhir + 1,
								'nilai_validate' => 1, 
								'validate' => 'Y',
								'validate_by' => $idUser,
								'updated_at' => now()
							]);
							
					}
							
				}
			
			
            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil validasi'
            ],200);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal Validasi!',
                'error' => $e->getMessage()
            ]);
        }
		
    }
	
	public function detailSopNByAktivitasUmrah($id)
    {
        $judul_sop   = AktivitasUmrahMuthowwifModel::getDetailSopNByAktivitasUmrah($id);
        $status      = 'N';
        
        $results    = [];
        foreach ($judul_sop as $value) {
            $sop = AktivitasUmrahMuthowwifModel::getListSopByStatus($value->id,$status, $value->id_judul);
            $results[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'judul' => $value->nama,
                'sop' => $sop
            ];
        }

        if (count($results) == 0) {
            return redirect()->route('dashbaord.muthowwif.analytics');
        }else{
			return view('dashboard.analitikmuthowwif.detail-sop', compact('results'));
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
                $master_tugas  = DB::table('detail_aktivitas_umrah_muthowwif as a')
                                        ->select('b.nilai_point')
                                        ->join('master_tugas as b','a.master_tugas_id','=','b.id')
                                        ->where('a.aktivitas_umrah_id', $aktivitasId)
                                        ->where('a.id', $value)
                                        ->first();

               $update =  DB::table('detail_aktivitas_umrah_muthowwif')
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

}
