<?php

namespace App\Http\Controllers;

use App\PilihanModel;
use App\KuisionerModel;
use Illuminate\Http\Request;
use App\PertanyaanKuisionerModel;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use App\JawabanKuisionerUmrahModel;
use App\EssayJawabanKuisionerUmrahModel;
use App\KategoriPilihanJawaban;
use Yajra\DataTables\Facades\DataTables;

class KuisionerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return view('kuisioner.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('kuisioner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
                       'name' => 'required',
                       'lokasi' => 'required',
                   ]);

        KuisionerModel::create([
            'nama' => $request->name,
            'lokasi' => $request->lokasi,
        ]);

        return redirect()->route('kusioner.index')->with(['success' => 'Kuisioner telah ditambahkan']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pertanyaanModel = new PertanyaanKuisionerModel();
        $pertanyaan = $pertanyaanModel->select('id','nomor','isi')
                    ->where('nomor','!=',null)->where('kuisioner_id', $id)->get();
        $result     = [];

        $jawabanModel = new JawabanKuisionerUmrahModel();
        $jawabanEssayModel = new EssayJawabanKuisionerUmrahModel();

        foreach ($pertanyaan as $value) {
           $pilihan      = $jawabanModel->getJumlahJawabanByPilihan($value->id);

            $result[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'pertanyaan' => $value->isi,
                'pilihan' => $pilihan
            ];
        }

        $pertanyaan_essay = $pertanyaanModel->select('id','isi')
                    ->where('nomor','=',null)->where('kuisioner_id', $id)->get();
        return view('kuisioner.show', compact('result','jawabanModel','pertanyaan_essay','jawabanEssayModel'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kuisioner  = KuisionerModel::where('id', $id)->first();
        $pertanyaanModel = new PertanyaanKuisionerModel(); 
        $pertanyaan = $pertanyaanModel->where('nomor','!=',null)->where('kuisioner_id', $id)->orderBy('nomor','asc')->get();
        $essay = $pertanyaanModel->where('nomor','=',null)->where('kuisioner_id', $id)->get();
        $pilihanModel = new PilihanModel();
        $no = 1;
        return view('kuisioner.edit', compact('kuisioner','pertanyaan','no','pilihanModel','essay'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $kuisioner = KuisionerModel::where('id', $request->id)->first();
        $kuisioner->update(['nama' => $request->name,'lokasi' => $request->lokasi]);
        return redirect()->back()->with(['success' => 'Kuisioner telah diubah']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            
            // GET KUISIONER_UMRAH BY KUISIONER_ID = $id
            KuisionerModel::where('id', $id)->delete();
            // LOOPING
                // GET DATA RESPONDEN_KUISIONER_UMRAH BY KUISIONER_UMRAH_ID

            // 

            
            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus pertanyaan'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deletePertanyaan()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            // GET PERTANYAAN BY ID
            // HAPUS PERTANYAAN BY ID
            PertanyaanKuisionerModel::where('id', $id)->delete();

            // HAPUS PILIHAN BY PERTANYAAN_ID
            PilihanModel::where('pertanyaan_id', $id)->delete();

            // HAPUS JAWABAN BY PERTANYAAN_ID
            JawabanKuisionerUmrahModel::where('pertanyaan_id', $id)->delete();
            
            // HAPUS ESSAY BY PERTANYAAN_ID
            EssayJawabanKuisionerUmrahModel::where('pertanyaan_id', $id)->delete();
            
            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus pertanyaan'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function listData()
    {
        $kusioner = KuisionerModel::select('id','nama')->orderBy('nama','asc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($kusioner)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '
                        <a href="'.route('kuisioner.edit', $item->id).'"><i class="fa fa-edit"></i></a> 
                        <button onclick="onCopy(this)" id="'.$item->id.'" data-name="'.$item->nama.'" class="fa fa-copy text-secondary" title="Copy"></button>
                        <button onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->nama.'" class="fa fa-trash text-danger" title="Hapus"></button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function createPertanyaan($id)
    {
        return view('pertanyaan.create', ['kuisioner_id' => $id]);
    }

   public function getDataJawabanPilihan(Request $request)
    {
            $KategoriPilihanModel = new KategoriPilihanJawaban();
            $data = $KategoriPilihanModel->select('id','nama')->get();
           
            if($request->has('q')){
                $search = $request->q;
                $data = $KategoriPilihanModel->where('nama','LIKE',"%$search%")->get();

        }
        return response()->json($data);

    }

    public function addElementFormEssayJawaban()
    {
        // Add element
        $request = request()->data;
        if($request == '2'){

            $html = "<div class='col-md-12 fieldGroupEssay'>
                        <div class='row mb-2'>
                        <div class='col-md-1'>
                          <button type='button' class='remove-essay'><i class='fas fa-trash text-danger'></i></button>
                        </div>
                       
                        <div class='col-md-9'>
                            <textarea type='text' name='essay[]' class='form-control form-control-sm' required/></textarea>
                        </div>                            
                        </div>
                    </div>";
            echo $html;
            exit;

        }
    }

    public function savePilihanPertanyaan(Request $request, $kuisioner_id)
    {

        DB::beginTransaction();
        try {
            //code...

            $pilihan = $request->pilihan;
            $essay = $request->essay;

           

            if ($pilihan != '') {
                 // HITUNG PENOMORAN PERTANYAAN BERDASARKAN KUISIONER ID
            $pertanyaanModel = new PertanyaanKuisionerModel();
            $count = $pertanyaanModel->where('kuisioner_id', $kuisioner_id)->count();
                $savePertanyaan = $pertanyaanModel->create([
                    'kuisioner_id' => $kuisioner_id,
                    'nomor' => $count+1,
                    'isi' => $request->isi,
                    'required' => $request->required
                ]);
    
                foreach ($pilihan as $key => $value) {
                    // GET KATEGORI PILIHAN
                    $kategori_pilihan = KategoriPilihanJawaban::select('id','nama')->where('id', $value)->first();
                    $pilihanModel = new PilihanModel();
                    $pilihanModel->pertanyaan_id = $savePertanyaan->id;
                    $pilihanModel->kategori_pilihan_jawaban_id = $kategori_pilihan->id;
                    $pilihanModel->nomor = $key+1;
                    $pilihanModel->isi = $kategori_pilihan->nama;
                    $pilihanModel->save();
                }
            }

            if ($essay != '') {
                foreach ($essay as $key => $value) {
                   $pertanyaanModel = new PertanyaanKuisionerModel();
                   $pertanyaanModel->kuisioner_id = $kuisioner_id;
                   $pertanyaanModel->isi = $value;
                   $pertanyaanModel->nomor = null;
                   $pertanyaanModel->save();
                   $savePertanyaan = $pertanyaanModel;

                    $pilihanModel = new PilihanModel();
                    $pilihanModel->pertanyaan_id = $savePertanyaan->id;
                    $pilihanModel->nomor = null;
                    $pilihanModel->isi = null;
                    $pilihanModel->save();
                }
            }

            DB::commit();
            return redirect()->back()->with(['success' => 'Pertanyaan telah ditambahkan']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['warning' => 'Pilihan tidak boleh kosong']);
        }


    }

    public function saveKategoriPilihanJawaban()
    {
        $id = request()->id;
        $isi = request()->isi;
        
        DB::beginTransaction();

        try {

            // CEK JIKA ADA PILIHAN JAWABAN YANG SAMA
            $kategoriModel = new KategoriPilihanJawaban();
            $cek = $kategoriModel->where('nama', $isi)->count();

            if ($cek == 0) {
                $kategoriModel->create(['nama' => $isi]);
            }

            DB::commit();
            return ResponseFormatter::success([
                   'data' => $cek,
                   'message' => $cek == 1 ? 'Sudah ada pilihan yang sama' : 'Sukses'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function showPilihanJawabanKuisioner()
    {
        return view('kuisioner.pilihan-jawaban');
    }

    public function listDataKategoriPilihan()
    {
        $pilihan  = KategoriPilihanJawaban::select('id','nama')->orderBy('nama','asc')->get();
        if (request()->ajax()) 
        {
            return DataTables::of($pilihan)
                    ->addIndexColumn()
                    ->addColumn('action', function($item){
                        return '
                        <button onclick="editKategoriPilihanJawaban(this)" id="'.$item->id.'" class="fa fa-edit" title="Edit"></button> 
                        <button onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->nama.'" class="text-danger fa fa-trash" title="Hapus"></button>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
    }

    public function updateKategoriPilihan()
    {
        $id = request()->id;
        $isi = request()->isi;
        
        DB::beginTransaction();

        try {

            // CEK JIKA ADA PILIHAN JAWABAN YANG SAMA
            $pilihanModel  = new PilihanModel();
            $kategoriModel = new KategoriPilihanJawaban();
            $cek = $kategoriModel->where('nama', $isi)->where('id','!=',$id)->count();
            
            if ($cek == 0) {
                $update =  $kategoriModel->where('id', $id)->first();
                $update->update(['nama' => $isi]);
                $pilihan = $pilihanModel->where('kategori_pilihan_jawaban_id', $id)->get();
                foreach ($pilihan as $key => $value) {
                    $updatePilihan = $pilihanModel->where('id', $value->id)->first();
                    $updatePilihan->isi = $isi;
                    $updatePilihan->save();

                }
            }

            DB::commit();
            return ResponseFormatter::success([
                   'data' => $cek,
                   'message' => $cek == 1 ? 'Sudah ada pilihan yang sama' : 'Sukses'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function deleteKategoriPilihan()
    {
        $id = request()->id;        
        DB::beginTransaction();

        try {

            // CEK JIKA ADA PILIHAN JAWABAN YANG SAMA
            $pilihan = PilihanModel::where('kategori_pilihan_jawaban_id', $id)->count();
            
            if ($pilihan == 0) {
                $update =  KategoriPilihanJawaban::where('id', $id)->first();
                $update->delete();
            }

            DB::commit();
            return ResponseFormatter::success([
                   'message' => $pilihan == 0 ? 'Terhapus' : 'Gagal, Pilihan terpakai dipertanyaan'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function updatePertanyaan()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            $nomor = request()->nomor;
            $isi = request()->isi;

            $pertanyaanModel = PertanyaanKuisionerModel::where('id', $id)->first();
            $pertanyaanModel->update([
                'nomor' => $nomor,
                'isi' => $isi
            ]);

            DB::commit();
            return ResponseFormatter::success([
                    'message' => 'Berhasil edit pertanyaan'
            ]); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function kategoriPilihanJawaban()
    {
        try {
            // $kategori_pilihan = KategoriPilihanJawaban::select('id','nama')->orderBy('nama','asc')->get();
            $kategori_pilihan = KategoriPilihanJawaban::pluck('nama','id');
            // $result = [];
            // foreach ($kategori_pilihan as $key => $value) {
            //     $result[] = [
            //         $value->id => $value->nama
            //     ];
            // }

            return ResponseFormatter::success([
                'data' => $kategori_pilihan
            ]); 

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function savePilihanJawabanPertanyaan()
    {
        $id = request()->id;
        $pilihan_id = request()->pilihan;
        
        DB::beginTransaction();

        try {

            // GET nama berdasarkan pilihan id
            $ketegori        = KategoriPilihanJawaban::select('nama','id')->where('id', $pilihan_id)->first();


            $cek_pilihan_jawaban = DB::table('pilihan')->select('kategori_pilihan_jawaban_id')
                                                   ->where('kategori_pilihan_jawaban_id', $pilihan_id)
                                                   ->where('pertanyaan_id', $id)
                                                   ->first();

            if ($cek_pilihan_jawaban == null) {
                $count_jawaban = DB::table('pilihan')->select(DB::raw('max(nomor) as total_nomor'))->where('pertanyaan_id', $id)->first();
                $count         = $count_jawaban->total_nomor;
    
                if ($count > 0) {
                    $nomor = $count + 1;
                }else{
                    $nomor = 1;
                }
    
                // SAVE PILIHAN JAWABAN KUISIONER
                PilihanModel::create([
                    'pertanyaan_id' => $id,
                    'kategori_pilihan_jawaban_id' => $pilihan_id,
                    'nomor' => $nomor,
                    'isi' => $ketegori->nama
                ]);

                DB::commit();
                return ResponseFormatter::success([
                        'data' => 1,
                       'message' => 'Berhasil tambah pilihan jawaban',
                ],200); 

            }else{
                return ResponseFormatter::success([
                    'data'    => 0,
                    'message' => 'Pilihan jawaban sudah tersedia',
             ],200); 
            }

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function deletePilihanJawaban()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;
            // HAPUS PILIHAN BY PERTANYAAN_ID
            PilihanModel::where('id', $id)->delete();
            
            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus pilihan'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function copyKuisioner()
    {

        DB::beginTransaction();
        try {

            $id   = request()->id;
            $nama = request()->nama;

            $kusionerModel = new KuisionerModel(); 
            // get kusioner where id
            $kuisioner  = $kusionerModel->select('lokasi')->where('id', $id)->first();
            // create kuisioner isi nya dari kuisioner yg di get by id
            $save = $kusionerModel->create([
                'nama' => $nama[0],
                'lokasi' => $kuisioner->lokasi
            ]);

            // create pertanyaan_kuisioner dengan kuisoner_id terbaru $save
            $pertanyaanKuisionerModel = new PertanyaanKuisionerModel();
            $pertanyaan               = $pertanyaanKuisionerModel->where('kuisioner_id', $id)->get();
            $pilihanModel             = new PilihanModel();

            foreach($pertanyaan as $value) {
               $save_pertantanyaan = $pertanyaanKuisionerModel->create([
                    'kuisioner_id' => $save->id, // dari id kuisioner terbaru yg di copy
                    'nomor' => $value->nomor,
                    'isi' => $value->isi,
                    'required' => $value->required
                ]);

                // get pilihan berdasarkan pertanyaan id 
                $pilihan            = $pilihanModel->where('pertanyaan_id', $value->id)->get();
                // create pilihan kuisioner
                foreach ($pilihan as $item) {
                    $pilihanModel->create([
                        'pertanyaan_id' =>  $save_pertantanyaan->id, // dari id pertanyaan yg baru di copy
                        'kategori_pilihan_jawaban_id' => $item->kategori_pilihan_jawaban_id,
                        'nomor' => $item->nomor,
                        'isi' => $item->isi
                    ]);
                }

            }
            
            DB::commit();
            return ResponseFormatter::success([
                'message' => 'Berhasil copy Kuisioner'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ]);
        }
    }
   
}
