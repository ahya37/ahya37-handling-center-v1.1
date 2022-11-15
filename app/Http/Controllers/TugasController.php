<?php

namespace App\Http\Controllers;

use App\TugasModel;
use Illuminate\Http\Request;
use App\DetailAktivitasUmrahModel;
use App\Helpers\ResponseFormatter;
use App\RecycleMasterTugasModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\SopModel;
use App\JudulSopModel;
use App\SopPetugasModel;
use App\JudulSopPetugasModel;
use App\TugasForPetugasModel;
use PDF;
use Maatwebsite\Excel\Excel;
use App\Exports\SopExportExcel;

class TugasController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function index()
    {
        return view('tugas.sop.index');
    }

    public function detailSop($id)
    {
        $sop = SopModel::select('id','name')->where('id', $id)->first();
        $user_id = Auth::user()->id;
        
        // JUDUL BY SOP ID
        $judul = JudulSopModel::select('id','nomor','nama')->orderBy('nomor','asc')->where('master_sop_id', $id)->get();
        $result_judul = [];
        foreach ($judul as $value) {
            $tugas          = TugasModel::select('nomor','nama','nilai_point','id','require_image','require_doc')->where('master_judul_tugas_id', $value->id)->get();
            $result_judul[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'nama' => $value->nama,
                'tugas' => $tugas
            ];
        }

        return view('tugas.sop.detail', compact('sop','result_judul','user_id'));
    }

    public function listDataSop()
    {
        $sop = SopModel::select('name','id')->orderBy('name','asc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($sop)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '<a href="'.route('tugas.sop.detail', $item->id).'" class="btn btn-sm btn-warning mb-1" title="Detail">Detail</a>
                                <a href="'.route('sop-export-pdf', $item->id).'" class="btn btn-sm btn-primary" title="PDF">PDF</a>
                                <a href="'.route('sop-export-excel', $item->id).'" class="btn btn-sm btn-success" title="Excel">Excel</a>
                                <button  class="btn btn-sm text-danger fa fa-trash" onclick="onDelete(this)" id="'.$item->id.'" data-name="'.$item->name.'" title="Hapus"></button>
                                '
                                ;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function exportDataSopPDF($id)
    {
        $sop       = SopModel::select('id','name')->where('id', $id)->first();

        $judul_sop = JudulSopModel::select('id','nomor','nama')->where('master_sop_id', $sop->id)->orderBy('nomor','asc')->get();
        $data    = [];

        foreach ($judul_sop as $value) {
            $tugas     = TugasModel ::select('nomor','nama','nilai_point')->where('master_judul_tugas_id', $value->id)->orderBy('nomor','asc')->get();
            $data[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'nama' => $value->nama,
                'tugas' => $tugas
            ];
        }

        $pdf = PDF::LoadView('report.sop', compact('sop','data'));
        return $pdf->download($sop->name.'.pdf');
    }

    public function exportDataSopExcel($id)
    {
        $sop       = SopModel::select('id','name')->where('id', $id)->first();

        $judul_sop = JudulSopModel::select('id','nomor','nama')->where('master_sop_id', $sop->id)->orderBy('nomor','asc')->get();
        $data      = [];

        foreach ($judul_sop as $value) {
            $tugas     = TugasModel ::select('nomor','nama','nilai_point','require_image')->where('master_judul_tugas_id', $value->id)->orderBy('nomor','asc')->get();
            $data[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'nama' => $value->nama,
                'tugas' => $tugas
            ];
        }
        

        return $this->excel->download(new SopExportExcel($data, $sop), $sop->name.'.xls');
    }



    public function listData()
    {
        $tugas = TugasModel::select('id','nomor','nama')->orderBy('id','asc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($tugas)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '<a href="'.route('tugas.edit', $item->id).'" class="btn btn-sm text-primary fa fa-edit" title="Edit"></a>
                                <button onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->nomor.'" class="btn btn-sm text-danger fa fa-trash" title="Hapus"></button>'
                                ;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function create()
    {
        return view('tugas.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
            'name' => 'required',
            ]);

            $tugas = SopModel::create([
                'name' => ucfirst($request->name),
                'created_by' => Auth::user()->id
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('tugas.index')->with(['success' => 'SOP berhasil disimpan']);

    }

    public function edit($id)
    {
        $tugasModel = new TugasModel();
        $tugas = $tugasModel->where('id', $id)->first();
        $nomor_tugas = $tugasModel->select('id','nomor')->orderBy('nomor','asc')->get();
        return view('tugas.edit', compact('tugas','nomor_tugas'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
            'nama' => 'required',
            ]);

            $tugas = TugasModel::where('id', $id)->first();
            $tugas->update([
                'nomor' => $request->nomor,
                'nama' => ucfirst($request->nama),
                'create_by' => Auth::user()->id
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('tugas.index')->with(['success' => 'Tugas berhasil diubah']);

    }


    public function tukarNomor(Request $request, $id)
    {
        DB::beginTransaction();

        try {

            $tugasModel = new TugasModel();
    
            $nomor_awal = $tugasModel->where('id', $id)->first();
            $nomor_tukar = $tugasModel->where('id', $request->nomor_tukar)->first();
            // update nomor tukar dengan nomor awal
            $nomor_awal->update(['nomor' => $nomor_tukar->nomor]);
            $nomor_tukar->update(['nomor' => $request->nomor_awal]);
            // update nomor awal dengan nomor tukar

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('tugas.index')->with(['success' => 'Nomor tugas berhasil ditukar']);

        
    }
    
    public function delete()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            TugasModel::where('id', $id)->delete();

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

    public function deleteSop()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            SopModel::where('id', $id)->delete();

            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus sop'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function updateSop()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $name = request()->name;

            $sop = SopModel::where('id', $id)->first();
            $sop->update(['name' => $name]);
           
            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil hapus tugas'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function countAlphabetMasterJudul($id)
    {
        DB::beginTransaction();
        try {

            $sop = JudulSopModel::select('nomor')->where('master_sop_id', $id)
                                    ->orderBy('nomor','desc')->first();
           
            DB::commit();
            return ResponseFormatter::success([
                    'nomor' => $sop->nomor ?? 'NULL'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function saveJudulTugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $name = request()->name;
            $userId = request()->userId;

            JudulSopModel::create([
                'master_sop_id' => $id,
                'nomor' => $nomor,
                'nama' => $name,
                'create_by' => $userId
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil tambah judul'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function updateJudulTugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $nama = request()->name;

            $judul = JudulSopModel::where('id', $id)->first();
            $judul->update([
                'nomor' => $nomor,
                'nama' => $nama
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil edit judul'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function countNumberMasteTugasByJudul($id)
    {
        DB::beginTransaction();
        try {

            $tugas = TugasModel::select('nomor')->where('master_judul_tugas_id', $id)
                                    ->orderBy('nomor','desc')->first();
           
            DB::commit();
            return ResponseFormatter::success([
                    'nomor' => $tugas->nomor ?? 0
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function saveTugasByJudul()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $name= request()->name;
            $nilai = request()->nilai;
            $sopId = request()->sopId;
            $userId = request()->userId;

            TugasModel::create([
                'master_sop_id' => $sopId,
                'master_judul_tugas_id' => $id,
                'nomor' => $nomor,
                'nama' => $name,
                'nilai_point' => $nilai,
                'create_by' => $userId
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil tambah tugas'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function updateTugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $name= request()->name;
            $nilai = request()->nilai;

            $tugas = TugasModel::where('id', $id)->first();
            $tugas->update([
                'nomor' => $nomor,
                'nama' => $name,
                'nilai_point' => $nilai,
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil edit tugas'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function settingUploadFileSop(Request $request)
    {
        DB::beginTransaction();

        try {

            $type = $request->type;
            $id   = $request->id;
            $tugas = TugasModel::where('id', $id)->first();

            if ($type == 'image') {
                $tugas->update([
                    'require_image' => $request->require,
                ]);
            }else{
                $tugas->update([
                    'require_doc' => $request->require,
                ]);
            }

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil ubah setting file'
        ]); 
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('tugas.index')->with(['success' => 'Tugas berhasil diubah']);

    }

    public function sopPetugas()
    {
        return view('tugas.create-sop-petugas');
    }

    public function storeSopPetugas(Request $request)
    {
        
        DB::beginTransaction();

        try {
            $request->validate([
            'name' => 'required',
            ]);

            $tugas = SopPetugasModel::create([
                'name' => ucfirst($request->name),
                'created_by' => Auth::user()->id
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('tugas.index')->with(['success' => 'SOP Petugas berhasil disimpan']);

    }

    public function listDataSopPetugas()
    {
        $sop = SopPetugasModel::select('name','id')->orderBy('name','asc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($sop)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '<a href="'.route('tugas.sop.petugas.detail', $item->id).'" class="btn btn-sm text-primary fa fa-eye" title="Detail"></a>
                                <button  class="btn btn-sm text-danger fa fa-trash" onclick="onDelete(this)" id="'.$item->id.'" data-name="'.$item->name.'" title="Hapus"></button>
                                '
                                ;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function detailSopPetugas($id)
    {

        $sop = SopPetugasModel::select('id','name')->where('id', $id)->first();
        $user_id = Auth::user()->id;
        
        // // JUDUL BY SOP ID
        $judul = JudulSopPetugasModel::select('id','nomor','nama')->orderBy('nomor','asc')->where('master_sop_petugas_id', $id)->get();
        $result_judul = [];
        foreach ($judul as $value) {
            $tugas          = TugasForPetugasModel::select('nomor','nama','nilai_point','id','require_image','require_doc')->where('master_judul_tugas_id', $value->id)->get();
            $result_judul[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'nama' => $value->nama,
                'tugas' => $tugas
            ];
        }

        return view('tugas.sop.detail-sop-petugas', compact('sop','result_judul','user_id'));

    }

    public function updateSopPetugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $name = request()->name;

            $sop = SopPetugasModel::where('id', $id)->first();
            $sop->update(['name' => $name]);
           
            DB::commit();
            return ResponseFormatter::success([
                   'message' => 'Berhasil hapus tugas'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function countAlphabetMasterJudulSopPetugas($id)
    {
        DB::beginTransaction();
        try {

            $sop = JudulSopPetugasModel::select('nomor')->where('master_sop_petugas_id', $id)
                                    ->orderBy('nomor','desc')->first();
           
            DB::commit();
            return ResponseFormatter::success([
                    'nomor' => $sop->nomor ?? 'NULL'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function saveJudulTugasSopPetugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $name = request()->name;
            $userId = request()->userId;

            JudulSopPetugasModel::create([
                'master_sop_petugas_id' => $id,
                'nomor' => $nomor,
                'nama' => $name,
                'create_by' => $userId
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil tambah judul'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function updateJudulTugasPetugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $nama = request()->name;

            $judul = JudulSopPetugasModel::where('id', $id)->first();
            $judul->update([
                'nomor' => $nomor,
                'nama' => $nama
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil edit judul'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function countNumberMasteTugasByJudulSopPetugas($id)
    {
        DB::beginTransaction();
        try {

            $tugas = TugasForPetugasModel::select('nomor')->where('master_judul_tugas_id', $id)
                                    ->orderBy('nomor','desc')->first();
           
            DB::commit();
            return ResponseFormatter::success([
                    'nomor' => $tugas->nomor ?? 0
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function saveTugasByJudulSopPetugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $name= request()->name;
            $nilai = request()->nilai;
            $sopId = request()->sopId;
            $userId = request()->userId;

            TugasForPetugasModel::create([
                'master_sop_petugas_id' => $sopId,
                'master_judul_tugas_id' => $id,
                'nomor' => $nomor,
                'nama' => $name,
                'nilai_point' => $nilai,
                'create_by' => $userId
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil tambah tugas'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function updateTugasPetugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $name= request()->name;
            $nilai = request()->nilai;

            $tugas = TugasForPetugasModel::where('id', $id)->first();
            $tugas->update([
                'nomor' => $nomor,
                'nama' => $name,
                'nilai_point' => $nilai,
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil edit tugas'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function deleteTugasForPetugas()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            TugasForPetugasModel::where('id', $id)->delete();

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

    public function settingUploadFileSopPetugas(Request $request)
    {
        DB::beginTransaction();

        try {

            $type = $request->type;
            $id   = $request->id;
            $tugas = TugasForPetugasModel::where('id', $id)->first();

            if ($type == 'image') {
                $tugas->update([
                    'require_image' => $request->require,
                ]);
            }else{
                $tugas->update([
                    'require_doc' => $request->require,
                ]);
            }

            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil ubah setting file'
        ]); 
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('tugas.index')->with(['success' => 'Tugas berhasil diubah']);

    }

    public function copySop()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;

            // SopModel::where('id', $id)->delete();

            // get sop where id

            // simpan sop baru dari sop yg di get
            
            // get judul berdasasrkan sop yang di get

            // simpan dalam looping judul yang di get berdasrkan sop id

            // get id judul dari sop yang baru


            DB::commit();
            return ResponseFormatter::success([
                   'data' => $id,
                   'message' => 'Berhasil copy sop'
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
