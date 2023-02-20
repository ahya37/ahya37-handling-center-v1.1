<?php

namespace App\Http\Controllers;

use App\AktivitasUmrahModel;
use App\EssayJawabanKuisionerUmrahModel;
use App\UmrahModel;
use App\SopModel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\JawabanKuisionerUmrahModel;
use App\KategoriPilihanJawaban;
use App\KuisionerModel;
use App\KuisionerUmrahModel;
use App\PertanyaanKuisionerModel;
use App\PilihanModel;
use App\SopPetugasModel;
use App\RespondenKuisionerUmrahModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UmrahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('umrah.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kuisioner = KuisionerModel::pluck('nama','id');
        $sop = SopModel::pluck('name','id');
        $sop_petugas = SopPetugasModel::pluck('name','id');

        $MaxTahun=date('Y')+5;
        $MinTahun=1900;
        $i=1;
        while($MaxTahun>=$MinTahun){
            $ArrTahun[$i-1]=$MaxTahun;
            $i++;
            $MaxTahun--;
        }

        return view('umrah.create', ['kuisioner' => $kuisioner, 'sop' => $sop,'ArrTahun' => $ArrTahun, 'sop_petugas' => $sop_petugas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                       'tourcode' => 'required',
                       'kuisioner' => 'required',
                       'sop' => 'required',
                       'dates' => 'required',
                       'count' => 'required',
                   ]);
                   
           $dates_request = explode('-', $request->dates);
           
           $kuisioner = KuisionerModel::select('id','nama','lokasi')->where('id', $request->kuisioner)->first();

           $umrah = UmrahModel::create([
               'master_sop_id' => $request->sop,
               'asisten_master_sop_id' => $request->asisten_sop,
               'master_sop_petugas_id' => $request->sop_petugas,
               'tourcode' => $request->tourcode,
               'count_jamaah' => $request->count,
               'dates' => $request->dates,
               'start_date' => date('Y-m-d', strtotime($dates_request[1])),
               'end_date' => date('Y-m-d', strtotime($dates_request[0])),
               'create_by' => Auth::user()->id
           ]);
   
           // PROSES MEMBUAT KUISIONER BERDASARKAN JADWAL UMRAH / TOURCODE
           $label     = "PERCIK Tours. Kuisioner  $kuisioner->lokasi $request->dates (hanya berlaku untuk satu kali pengisian)";
   
           KuisionerUmrahModel::create([
               'umrah_id' => $umrah->id,
               'label'    => $label,
               'url' => Str::random(10),
               'kuisioner_id' => $kuisioner->id,
           ]);
           
            DB::commit();
            return redirect()->route('umrah.index')->with(['success' => 'Umrah berhasil disimpan']);

        } catch (\Exception $e) {
            DB::rollBack();
            echo $e->getMessage();
            return redirect()->route('umrah.create')->with(['warning' => 'Umrah gagal disimpan']);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $umrah = UmrahModel::where('id', $id)->first();
        return view('umrah.edit',['umrah' => $umrah]);
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
        $umrah = UmrahModel::where('id', $id)->first();
         $request->validate([
                    'tourcode' => 'required',
                    'count_jamaah' => 'required',
                    // 'dates' => 'required',
                ]);
        $umrah->update([
            'tourcode' => $request->tourcode,
            'count_jamaah' => $request->count_jamaah,
            // 'tujuan' => $request->tujuan,
        ]);

        return redirect()->route('umrah.index')->with(['success' => 'Umrah berhasil diubah']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::beginTransaction();
        try {

            $id = $request->id;
            
            // cek ke aktivitas umrah apakah id ini masih active
            $aktivitasUmrah = AktivitasUmrahModel::where('umrah_id', $id)
                            ->where('status','active')->count();
            // jika aktif tidak bisa di hapus
            // hanya jika sudah finsh boleh di hapus
            if($aktivitasUmrah == 0){
                $umrah = UmrahModel::where('id', $id)->first();
                $umrah->delete();
            }else{
                return ResponseFormatter::error([
                   null,
                   'message' => 'Gagal, ada pembimbing yang masih aktif mengerjakan'
                ]); 
            }
            
            DB::commit();
            return ResponseFormatter::success([
                   null,
                   'message' => 'Berhasil hapus Umrah'
            ],200); 

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function listData(Request $request)
    {
        // $umrahModel = new UmrahModel();
        // $umrah   = $umrahModel->getDataUmrah();
        // if (request()->ajax()) 
        // {
        //     return DataTables::of($umrah)
        //             ->addIndexColumn()
        //             ->addColumn('action', function($item){
        //                 $url = $item->url == null ? '<span class="text-danger">Dihapus</span>' : '<a class="btn btn-sm text-primary" href="'.route('umrah.kuisioner.show', $item->id).'">Responden Kuisoner</a>';
        //                 $kuisioner = $item->url == null ? '<span class="text-danger">Dihapus</span>' : '<a class="btn btn-sm text-primary" href="'.route('umrah.kuisioner.result', $item->id).'">Hasil Kuisoner</a>';
        //                 return '
        //                 '.$url.'
        //                 '.$kuisioner.'
        //                 <a href="'.route('umrah.edit', $item->id).'" class="btn btn-sm fa fa-edit text-primary" title="Edit"></a>
        //                 <button onclick="onDelete(this)" id="'.$item->id.'" value="'.$item->tourcode.'" title="Hapus" class="fa fa-trash text-danger"></button>
        //                 ';
        //             })
        //             ->addColumn('createdAt', function($item){
        //                 return date('d-m-Y H:i', strtotime($item->created_at));
        //             })
        //             ->addColumn('urlKuisioner', function($item){
        //                 $url = $item->url == null ? '<span class="text-danger">Dihapus</span>' : '<a target="_blank" href="'.route('kuisioner.umrah.view', $item->url).'">'.$item->url.'</a>';
        //                 return $url;
        //             })
        //             ->rawColumns(['action','createdAt','urlKuisioner'])
        //             ->make(true);
        // }

        $orderBy = 'a.tourcode';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'a.tourcode';
                break;
            case '1':
                $orderBy = 'a.dates';
                break;
        }

        $data =  DB::table('umrah as a')
            ->leftJoin('kuisioner_umrah as b','b.umrah_id','=','a.id')
            ->where('a.isdelete',0)
            ->select('a.id','a.tourcode','a.dates','a.created_at','b.url','a.count_jamaah');

        if($request->input('search.value')!=null){
                $data = $data->where(function($q)use($request){
                    $q->whereRaw('LOWER(a.tourcode) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                });
        }

        if($request->input('month') != '' AND $request->input('year') != ''){
                $data->whereMonth('a.start_date', $request->month);
                $data->whereYear('a.start_date', $request->year);
        }

        if($request->input('tourcode') != ''){
            $data->where('a.tourcode', $request->tourcode);
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

    public function getDataUmrah(Request $request)
    {

            $data = UmrahModel::select('id','tourcode')->get();
           
            if($request->has('q')){
            $search = $request->q;

            $data = UmrahModel::select("id","tourcode")

            		->where('tourcode','LIKE',"%$search%")

            		->get();

        }
        return response()->json($data);

    }

    public function getDataAsistenSopByIdUmrah()
    {
        try {
            $data = UmrahModel::select('asisten_master_sop_id')->where('id', request()->id)->first();

            return ResponseFormatter::success([
                        'data' => $data->asisten_master_sop_id
                    ],200); 

        } catch (\Exception $th) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => 'Gagal !',
                'error' => $e->getMessage()
            ]);

        }

    }
    
    public function getDataTourcodeByPembimbing(Request $request, $pembimbingId)
    {
        try {
           
            $data = DB::table('aktivitas_umrah as a')
            ->select('b.id','b.tourcode')
            ->join('umrah as b','a.umrah_id','=','b.id')
            ->where('a.pembimbing_id', $pembimbingId)
            ->get();
   
            if($request->has('q')){
                $search = $request->q;
            
                $data = DB::table('aktivitas_umrah as a')
                ->select('b.id','b.tourcode')
                ->join('umrah as b','a.umrah_id','=','b.id')
                ->where('a.pembimbing_id', $pembimbingId)
                ->where('b.tourcode','LIKE',"%$search%")
                ->get();

                }

            return response()->json($data);

        } catch (\Exception $th) {
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);
        }


    }

    public function getDataUmrahByTourcode(Request $request,$month,$year)
    {

            $data = UmrahModel::select('id','tourcode')
                    ->whereMonth('start_date', $month)
                    ->whereYear('end_date', $year)
                    ->get();
           
            if($request->has('q')){
            $search = $request->q;

            $data = UmrahModel::select("id","tourcode")
            		->where('tourcode','LIKE',"%$search%")
                    ->whereMonth('start_date', $month)
                    ->whereYear('start_date', $year)
            		->get();

        }
        return response()->json($data);

    }

    public function showKuisionerByUmrahId($id)
    {
        $kuisionerUmrahModel = new KuisionerUmrahModel();
        $kuisioner           = $kuisionerUmrahModel->getDataKuisionerByUmrahId($id);
        $responden           = RespondenKuisionerUmrahModel::select('nama','jenis_kelamin','usia','id')->where('kuisioner_umrah_id', $kuisioner->id)->get();
        $no = 1;        
        return view('umrah.show-kuisioner', compact('kuisioner','responden','no'));
    }

    public function detailResponden($id)
    {
        $responden           = RespondenKuisionerUmrahModel::select('nama')->where('id', $id)->first();
        $jawaban = JawabanKuisionerUmrahModel::select('id','pertanyaan_id','jawaban')->where('responden_kuisioner_umrah_id', $id)->get();
        $data = [];
        $no   = 1;

        foreach ($jawaban as $val) {
            $pertanyaan = PertanyaanKuisionerModel::select('isi','id')->where('id', $val->pertanyaan_id)->get();
            foreach ($pertanyaan as $value) {
                $pilihan    = PilihanModel::select('nomor','isi')->where('pertanyaan_id', $value->id)->get();
                $data[] = [
                    'pertanyaan' => $value->isi,
                    'pilihan' => $pilihan,
                    'jawaban' => $val->jawaban
                ];
            }
        }

        $essayModel = new EssayJawabanKuisionerUmrahModel();
        $essay = $essayModel->getDataEssayByRespondenId($id);


        return view('umrah.detail-responden', compact('data','no','responden','essay'));

    }

    public function hasilKuisionerByUmrahId($id)
    {
        $pilihanModel = new PilihanModel();
        $kuisionerUmrahModel = new KuisionerUmrahModel();

        // GET DATA KUISIONER BY UMRAH_ID
        $umrah_id            = $id;
        $kuisioner           = $kuisionerUmrahModel->getDataKuisionerByUmrahId($umrah_id);

        // GET PERTANYAAN BY KUISIONER_ID
        $pertanyaanModel = new PertanyaanKuisionerModel();
        $pertanyaan = $pertanyaanModel->select('id','nomor','isi')
                    ->where('nomor','!=',null)->where('kuisioner_id', $kuisioner->kuisioner_id)->get();

        // JUMLAH RESPONDEN BERSASARKAN UMRAH
        $responden       = $kuisionerUmrahModel->getRespondenByUmrahId($umrah_id);
        $jumlah_responden_umrah = $responden->jumlah_responden_umrah ?? 0; 

        // JUMLAH PERTANYAAN BERDASARKAN UMRAH
        $count_pertanyaan = count($pertanyaan); 
        
        $pembagi = $jumlah_responden_umrah * $count_pertanyaan;

         // LIST PILIHAN JAWABAN BERDASARKAN UMRAH
        $kategoriPilihan = $pilihanModel->getKategoriByUmrahId($umrah_id);
        $persentasi_jawaban = [];
        
        foreach ($kategoriPilihan as $key => $value) {
            // $count_pertanyaanByPilihan = $pilihanModel->where('kategori_pilihan_jawaban_id', $value->id)->count();
            $persentasi_jawaban[] = [
                'pilihan' => $value->pilihan,
                'jumlah' => $value->jumlah,
                'persentage' => round(($value->jumlah/$pembagi)*100),
            ];
        }



        $result     = [];

        $jawabanModel = new JawabanKuisionerUmrahModel();
        $jawabanEssayModel = new EssayJawabanKuisionerUmrahModel();

        foreach ($pertanyaan as $value) {
            $result[] = [
                'id' => $value->id,
                'nomor' => $value->nomor,
                'pertanyaan' => $value->isi,
            ];
        }

        $pertanyaan_essay = $pertanyaanModel->select('id','isi')
                    ->where('nomor','=',null)->where('kuisioner_id', $kuisioner->kuisioner_id)->get();
        return view('umrah.result-kuisioner', compact('result','jawabanModel','pertanyaan_essay','jawabanEssayModel','kuisioner','umrah_id','pilihanModel','persentasi_jawaban'));

    }

    public function umrahDataTable(Request $request)
    {
        $orderBy = 'tourcode';
        switch ($request->input('order.0.column')) {
            case '1':
                $orderBy = 'tourcode';
                break;
        }

        $data = DB::table('umrah')->select('id','tourcode','dates');

        if($request->input('search.value')!=null){
            $data = $data->where(function($q)use($request){
                $q->whereRaw('LOWER(tourcode) like ? ',['%'.strtolower($request->input('search.value')).'%']);
                ;
            });
        }

        if($request->input('month') != '' AND $request->input('year') != ''){
                            $data->whereMonth('start_date', $request->month);
                            $data->whereYear('end_date', $request->year);
                        }

        $recordsFiltered = $data->get()->count();
        if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
        $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();
        // $data = $data->get();

        $recordsTotal = $data->count();

        return response()->json([
                'draw'=>$request->input('draw'),
                'recordsTotal'=>$recordsTotal,
                'recordsFiltered'=>$recordsFiltered,
                'data'=> $data
            ]);
    }

    public function countJumlahJamaahByUmrahId()
    {
        try {
            $id = request()->id;

            $umrah = DB::table('aktivitas_umrah as a')
                        ->join('umrah as b','a.umrah_id','=','b.id')
                        ->select('b.count_jamaah')->first();
            return ResponseFormatter::success([
                'data' => $umrah,
            ]);

        } catch (\Exception $th) {
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);
        }
    }
}
