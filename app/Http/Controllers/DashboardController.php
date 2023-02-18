<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\KategoriPilihanJawaban;
use DB;
use App\KuisionerModel;

class DashboardController extends Controller
{
    public function dashboardKuisioner()
    {
        return view('dashboard.kuisioner.index');
    }

    public function grafikKuisioner()
    {
        
        try {
           
            $tourcode = request()->tourcode;
            $pembimbing_id = request()->pembimbing_id;

            $kuisioner = DB::table('kategori_pilihan_jawaban')
                        ->select('id','nama')->get();

            $result = [];

            foreach ($kuisioner as $key => $value) {
                $data = DB::table('pilihan as a')
                    ->select(DB::raw('COUNT(DISTINCT(b.id)) as total'))
                    ->leftJoin('jawaban_kuisioner_umrah as b','a.id','=','b.pilihan_id')
                    ->join('umrah as c','b.umrah_id','=','c.id')
                    ->join('aktivitas_umrah as d','d.umrah_id','c.id');
                
                if(request()->daterange != ''){
                    $daterange = request()->daterange;
                    $date      = explode('/', $daterange);
                    $start     = $date[0];
                    $end     = $date[1];

                    $data->whereBetween('b.created_at', [$start, $end]);
                }

                if($tourcode != ''){
                    $data->where('c.tourcode', $tourcode);
                }

                if($pembimbing_id != ''){
                         $data->where('d.pembimbing_id', $pembimbing_id);
                }

                $data->whereNotNull('a.isi')
                    ->where('a.kategori_pilihan_jawaban_id', $value->id);
                
                $data = $data->first();

            //    $result[] = [$value->nama,$data->total];
               $result[] = [
                   'id'   => $value->id,
                   'nama' => $value->nama,
                   'total' => $data->total
               ];
            }

            // SORTIR BERDASRKAN TOTAL TERTINGGI
            usort($result, function ($a, $b) {
                return ($a['total'] > $b['total']) ? -1 : 1;
            });

            // $data = DB::table('pilihan as a')
            //         ->select('a.isi', DB::raw('COUNT(DISTINCT(b.id)) as total'))
            //         ->leftJoin('jawaban_kuisioner_umrah as b','a.id','=','b.pilihan_id');
                   

            // $data = DB::table('kategori_pilihan_jawaban as a')
            //             ->select('a.id','a.nama', DB::raw('COUNT(DISTINCT(b.id)) as total'))
            //             ->join('pilihan as b','a.id','=','b.kategori_pilihan_jawaban_id')
            //             ->join('jawaban_kuisioner_umrah as c','b.id','=','c.pilihan_id')
            //             ->join('umrah as d','c.umrah_id','=','d.id')
            //             ->join('aktivitas_umrah as e','e.umrah_id','=','d.id');
                        

            // if(request()->daterange != ''){
            //     $daterange = request()->daterange;
            //     $date      = explode('/', $daterange);
            //     $start     = $date[0];
            //     $end     = $date[1];

            //     $data->whereBetween('b.created_at', [$start, $end]);
            // }

            // if($tourcode != ''){
            //     $data->join('umrah as c','b.umrah_id','=','c.id')
            //         ->where('c.tourcode', $tourcode);
            // }

            // if($pembimbing_id != ''){
            //     $data->join('umrah as c','b.umrah_id','=','c.id')
            //          ->join('aktivitas_umrah as d','d.umrah_id','c.id')
            //          ->where('d.pembimbing_id', $pembimbing_id);
            // }

            // $data =  $data->whereNotNull('a.isi')
            //         ->groupBy('a.isi')
            //         ->orderBy(\DB::raw('COUNT(b.id)'),'DESC')->get();

            // $result = [];

            // foreach ($data as $key => $value) {
            //     $result[] = [$value->isi, $value->total];
            // }

            $nilai      = [];
            $dataResult = [];
            
            foreach ($result as $key => $value) {
                // $dataResult[] = [$value['nama'], $value['total']];
                $dataResult[]    = $value['nama'];
                $nilai[] = [
                    'y' => $value['total'],
                    'url' => route('kusioner.detail.jawaban', $value['id'])
                ];
            };
           
            return ResponseFormatter::success([
                   'data' => $dataResult,
                   'nilai' => $nilai 
            ]); 

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);

        }
    }

    public function detailKuisionerByDashboard($id)
    {
        return view('dashboard.kuisioner.detail');
    }

    public function listDetailKuisionerByDashboard(Request $request, $id)
    {
        try {

            $tourcode  = request()->tourcode;

            $orderBy = 'jml_jawaban';
            switch ($request->input('order.0.column')) {
                case '0':
                    $orderBy = 'jml_jawaban';
                    break;
                case '1':
                    $orderBy = 'c.tourcode';
                    break;
            }

            // $data = DB::table('jawaban_kuisioner_umrah as a')
            //         ->select('c.isi as pertanyaan','d.tourcode','b.isi as kategori', DB::raw('count(a.pilihan_id) as jml_jawaban'))
            //         ->join('pilihan as b','a.pilihan_id','=','b.id')
            //         ->join('pertanyaan_kuisioner as c','b.pertanyaan_id','=','c.id')
            //         ->join('umrah as d','a.umrah_id','=','d.id')
            //         ->join('responden_kuisioner_umrah as e','a.responden_kuisioner_umrah_id','=','e.id')
            //         ->where('b.kategori_pilihan_jawaban_id', $id);

            $data = DB::table('pilihan as a')
                ->select('e.isi as pertanyaan','c.tourcode','a.isi as kategori',
                DB::raw('count(distinct(b.id)) as jml_jawaban'))
                ->join('jawaban_kuisioner_umrah as b','a.id','=','b.pilihan_id')
                ->join('umrah as c','b.umrah_id','=','c.id')
                ->join('aktivitas_umrah as d','d.umrah_id','=','c.id')
                ->join('pertanyaan_kuisioner as e','b.pertanyaan_id','=','e.id')
                ->where('a.kategori_pilihan_jawaban_id', $id); 
                   
            if($tourcode != ''){
                        $data->where('c.tourcode', $tourcode);
            }

            $recordsFiltered = $data->groupBy('e.isi','c.tourcode','a.isi')->get()->count();
            if($request->input('length')!=-1) $data = $data->skip($request->input('start'))->take($request->input('length'));
            $data = $data->orderBy($orderBy,$request->input('order.0.dir'))->get();

            $recordsTotal = $data->count();

            return response()->json([
                    'draw'=>$request->input('draw'),
                    'recordsTotal'=>$recordsTotal,
                    'recordsFiltered'=>$recordsFiltered,
                    'data'=> $data
                ]);

            // $data = $data->distinct()->groupBy('c.isi','d.tourcode')->get();
            
            // return $data;

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function dashboardAnalitycs()
    {
        return view('dashboard.analitik.index');
    }

    public function dataGradeByPembimbing()
    {
        DB::beginTransaction();
        try {

            $id = request()->id;

            $grade = DB::table('aktivitas_umrah as a')
                    ->select('c.tourcode','c.start_date',DB::raw('sum(b.nilai_akhir) as nilai'))
                    ->join('detail_aktivitas_umrah as b','a.id','=','b.aktivitas_umrah_id')
                    ->join('umrah as c','a.umrah_id','=','c.id')
                    ->where('a.pembimbing_id', $id)
                    ->where('a.nonaktif', 0)
                    ->groupBy('c.tourcode','c.start_date')
                    ->orderBy('c.start_date','asc')
                    ->get();

            // SOP TIDAK DILAKSANAKAN
            $sop_n = DB::table('aktivitas_umrah as a')
                        ->select('a.id','b.tourcode', 
                            DB::raw('count(c.status) as total_tidak_dilaksanakan'))
                        ->join('umrah as b','a.umrah_id','=','b.id')
                        ->join('detail_aktivitas_umrah as c','c.aktivitas_umrah_id','=','a.id')
                        ->where('c.status','N')
                        ->where('a.nonaktif', 0)
                        ->where('a.pembimbing_id',$id)
                        ->groupBy('a.id','b.tourcode')
                        ->orderBy('b.start_date','asc')
                        ->get();

            // $result = [];
            $tourcode = [];
            $nilai    = [];
            foreach ($grade as $value) {
                $tourcode[]= $value->tourcode;
                $nilai[] = (int)$value->nilai;
            }

            return ResponseFormatter::success([
                'tourcode' => $tourcode,
                'nilai' => $nilai,
                'sop_n' => $sop_n,
                'count_sop_n' => count($sop_n)
            ],200);

        } catch (\Exception $e) {
            return ResponseFormatter::error([
                'message' => 'Gagal!',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function resumeKuisioner(){

        // - tampilkan tourcode
        $tourcode = DB::table('aktivitas_umrah as a')
                    ->join('umrah as b','b.id','=','a.umrah_id')
                    ->select('b.tourcode','b.id')
                    ->groupBy('b.tourcode','b.id')
                    ->get();
        

        $results = [];
        foreach($tourcode as $item){

            #get pembimbing by umrah
            $pembimbing = DB::table('aktivitas_umrah as a')
                          ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                          ->join('umrah as c','a.umrah_id','=','c.id')
                          ->select('b.nama','a.status_tugas')
                          ->where('c.id', $item->id)
                          ->groupBy('b.nama','a.status_tugas')
                          ->get();

            $kuisioner = DB::table('kuisioner_umrah as a')
                        ->join('kuisioner as b','b.id','=','a.kuisioner_id')
                        ->select('a.url', 'b.nama')
                        ->where('a.umrah_id', $item->id)
                        ->get();

            #get kategori pertanyaan
            $results[] = [
                'tourcode' => $item->tourcode,
                'pembimbing' => $pembimbing,
                'kuisioner' => $kuisioner
            ];
        }


        return view('dashboard.kuisioner.resume-kuisioner', compact('results'));

	    // - RESUME
		// - tampilkan nama pembimbing by  tourcode
		// - tampilkan resume kuisioner by tourcode
    }

    public function getDetailResumeByTourcode(){

        $tourcode = request('tourcode');

        $kategori = DB::table('kategori_pertanyaan_kuisioner');

        #hitung jumlah responden by tourcode
        $responden  = DB::table('kuisioner_umrah as a')
                        ->join('umrah as b','a.umrah_id','=','b.id')
                        ->where('b.tourcode', $tourcode)
                        ->orderBy('a.jumlah_responden','desc')
                        ->select('a.jumlah_responden','b.count_jamaah','b.tourcode')
                        ->first();
                              
        $kategori_pertanyaan = DB::table('kategori_pertanyaan_kuisioner')
                            ->select('id','number','nama')
                            ->orderBy('number','asc')
                            ->whereNull('parent_id')->get();

        $result_kategori       = [];
        foreach($kategori_pertanyaan as $item){

            #get sub kategori
            $sub_kategori = DB::table('kategori_pertanyaan_kuisioner')
                            ->where('parent_id', $item->id)
                            ->select('id','nama')
                            ->get();

            # get pertanyaan by subkategori
            # get pilihan jawaban by pertanyan
            $result_pertanyaan = [];
            foreach($sub_kategori as $sub){

                // $pertanyaan = DB::table('pertanyaan_kuisioner')
                //                     ->select('id as id_pertanyaan','kuisioner_id','nomor','isi as pertanyaan')
                //                     ->where('kategori_id', $sub->id)
                //                     ->get();

               $pertanyaan = DB::select("SELECT b.isi, count(a.jawaban)  as jml_jawaban
                            from jawaban_kuisioner_umrah as a 
                            join pilihan as b on a.pilihan_id = b.id
                            join umrah as c on a.umrah_id = c.id
                            join pertanyaan_kuisioner as d on a.pertanyaan_id = d.id
                            where c.tourcode = '$tourcode' and d.kategori_id = $sub->id  group by b.isi order by count(a.jawaban) desc");

                $total_jawaban = collect($pertanyaan)->sum(function($q){
                    return $q->jml_jawaban;
                });

                $rata_rata_pertanyaan = [];
                foreach($pertanyaan as $itempertanyaan){

                    $rata_rata_pertanyaan[] = [
                        'jawaban' => $itempertanyaan->isi,
                        // 'jml_jawaban' => $itempertanyaan->jml_jawaban,
                        'avg' => round(($itempertanyaan->jml_jawaban/$total_jawaban)*100)
                    ];
                }

                $result_pertanyaan[] = [
                    'id_sub' => $sub->id,
                    'nama'  => $sub->nama,
                    'pertanyaan' => $rata_rata_pertanyaan,
                ];
            }

            #kuisioner per tourcode
            // $kuisioner_tourcode = DB::select("SELECT e.nama as kuisioner , d.nama as pembimbing, a.id  from kuisioner_umrah as a
            //                         join umrah as b on b.id = a.umrah_id
            //                         join aktivitas_umrah as c on c.umrah_id = b.id
            //                         join pembimbing as d on d.id = c.pembimbing_id
            //                         join kuisioner as e on e.id = a.kuisioner_id
            //                         WHERE b.tourcode = '$tourcode'
            //                         GROUP by e.nama , d.nama , a.id,");

            $kuisioner_tourcode = DB::table('kuisioner_umrah as a')
                                  ->join('umrah as b','a.umrah_id','=','b.id')
                                  ->join('kuisioner as c','c.id','=','a.kuisioner_id')
                                  ->select('b.id as umrah_id','c.nama as kuisioner','a.id as kuisioner_id')
                                  ->where('b.tourcode', $tourcode)
                                  ->get();
            //  $kuisioner_tourcode = array_unique($kuisioner_tourcode);                

            //  $kuisioner_tourcode =  array_map("unserialize", array_unique(array_map("serialize", $kuisioner_tourcode)));
            #get pembimbing by umrah
            $pembimbing = DB::table('aktivitas_umrah as a')
                          ->join('pembimbing as b','b.id','=','a.pembimbing_id')
                          ->join('umrah as c','a.umrah_id','c.id')
                          ->select('b.nama','a.status_tugas')
                          ->where('c.tourcode', $tourcode)
                          ->groupBy('b.nama','a.status_tugas')
                          ->get();

            #get essay 
            $essay = DB::select("SELECT a.essay  from essay_jawaban_kuisioner_umrah  as a 
                                join umrah as b on a.umrah_id = b.id 
                                where b.tourcode = '$tourcode' and a.essay is not null group by a.essay");

            $result_kategori[] = [
                'id_kategori' => $item->id,
                'nomor' => $item->number,
                'kategori' => $item->nama,
                'sub_kategori' => $result_pertanyaan
            ];
        }


        return view('dashboard.kuisioner.detail-resume-kuisioner', compact('result_kategori','responden','pembimbing','essay','kuisioner_tourcode'));
    }

}
