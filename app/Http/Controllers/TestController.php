<?php

namespace App\Http\Controllers;

use App\TagOrangeModel;
use App\PilihanModel;
use App\DetailTagOrangeModel;
use App\AbsensiModel;
use App\AktivitasUmrahModel;
use Illuminate\Http\Request;
use DB;
use Maatwebsite\Excel\Excel;
use App\Exports\AbsensiExportExcel;
use PDF;

class TestController extends Controller
{
    public $excel;
    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function updatePilihan()
    {
        $pilihan = PilihanModel::all();

        foreach ($pilihan as $key => $value) {
            $isi      = $value->isi;

            switch ($isi) {
                case 'Cepat':
                    $kategori = 1;
                    break;
                case 'Memuaskan':
                    $kategori = 2;
                    break;
                case 'Enak':
                    $kategori = 3;
                    break;
                case 'Ya':
                    $kategori = 4;
                    break;
                case 'Ya':
                    $kategori = 5;
                    break;
                case 'Ya':
                    $kategori = 6;
                    break;


                default:
                    # code...
                    break;
            }
            $update = PilihanModel::where('id', $value->id)->first();
            $update->update(['kategori_pilihan_jawaban_id']);
        }

    }

    public function testListTugas($id)
    {
        $aktitivitasModel = new AktivitasUmrahModel();
        $data = $aktitivitasModel->getListTugasByAktivitasUmrahIdTest($id);
        return $data;
    }

    public function TagOrangePdf($id)
    {
        // $start = request()->start;
        // $end  = request()->end;
        $label = strtoupper(request()->label);

        // // GET DATA DETAIL GROUP BY ID
        $tag  = TagOrangeModel::select('group_date')->where('id', $id)->first();
        // // $data = DetailTagOrangeModel::where('tag_orange_id', $id)->whereBetween('no_urut',[$start, $end])->get();
        $sql  = "select * from detail_tag_orange where tag_orange_id = $id";
        $data = DB::select($sql);
        // // $pdf = PDF::LoadView('report.tagorange',compact('data','tag'));
        // // return $pdf->stream($id.'.pdf');

        // // get no.telp TL / nomor urut 1
        $tl =  DetailTagOrangeModel::select('telp_jamaah')->where('tag_orange_id', $id)->where('no_urut','01')->first();

        // $pdf = PDF::LoadView('report.tagorangepdf', compact('data','tag','label','tl'))->setPaper('a4', 'landscape');
        // return $pdf->stream('TAG.pdf');
        
        // hitung jumlah data
        $count_data = count($data);
        $pembagi    = 4;
        $sisa_bagi  = $count_data%$pembagi;
        $hasil_bagi = ($count_data-$sisa_bagi) / $pembagi;

        
        $jml_tags = $hasil_bagi + $sisa_bagi;

        $start = 1;
        $end   = 4;
       
        $card_tags   = [];

        for ($i=0; $i <= $jml_tags ; $i++) { 

            // tampilkan data tag orang per variabel adalah 4 array

            if ($i != '') {
                $no_start = ($end * $i) - 3;
                $no_end   = $end * $i;

                $card_tags[] = [
                    'tags' => [
                        $i => DetailTagOrangeModel::select('no_urut','nama_jamaah','foto_jamaah','telp_jamaah','email_jamaah','alamat_jamaah','tl')->where('tag_orange_id', $id)
                              ->where('no_urut','>=', $no_start)
                              ->where('no_urut','<=', $no_end)
                              ->get()
                    ]
                ];
            }
        }

        // buat variabel sesuai dengan jumlah data

        // $jml_data = jml_data / 4; 
        // jika ada sisa, maka tambahkan ke hasil bagi data
        // return $card_tags;
        $pdf = PDF::LoadView('report.tagorangepdf', compact('card_tags','tag','tl','label'))->setPaper('a4','landscape');
        return $pdf->download('UMRAH GROUP '.$tag->group_date.'.pdf');
        
    }


}
