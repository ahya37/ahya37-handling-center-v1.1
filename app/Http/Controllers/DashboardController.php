<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\KategoriPilihanJawaban;
use DB;

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

}
