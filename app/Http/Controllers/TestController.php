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
                        $i => DetailTagOrangeModel::select('no_urut','nama_jamaah','foto_jamaah','telp_jamaah','email_jamaah','alamat_jamaah')->where('tag_orange_id', $id)
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

    public function mergeDataKuisioner(Request $request){

        DB::beginTransaction();
        try{

            #tourcode
            $tourcode           = $request->tourcode;
            #umrah aktif
            $umrah_id_aktif     = $request->umrah_id_aktif;
            #umrah non aktif
            $umrah_id_non_aktif = $request->umrah_id_non_aktif;
            #aktivitas umrah id
    
            #get kuisioner by tourcode
            $kuisioner = DB::select("SELECT a.id as kuisioner_umrah_id, a.kuisioner_id, b.id as umrah_id , a.jumlah_responden  from kuisioner_umrah as a
                            join umrah as b on b.id = a.umrah_id
                            where b.tourcode = '$tourcode'");
            
            #get id / kuisioner_umrah_id di tb kuisioner_umrah where id  umrah non aktif
            $kuisioner_umrah_id = DB::select("SELECT a.id as kuisioner_umrah_id, a.kuisioner_id, b.id as umrah_id , a.jumlah_responden  from kuisioner_umrah as a
                                    join umrah as b on b.id = a.umrah_id
                                    where b.tourcode = '$tourcode' and b.id = $umrah_id_non_aktif");
            $kuisioner_umrah_id = collect($kuisioner_umrah_id)->first();
                
            if($kuisioner_umrah_id != null){
                    #update umrah_id = $umrah_id_aktif  di tb kuisioner_umrah where kuisioner_umrah_id
                    // $update_kuisioner_umrah = DB::update("UPDATE kuisioner_umrah set umrah_id = $umrah_id_aktif where id = $kuisioner_umrah_id->kuisioner_umrah_id");
                    $update_kuisioner_umrah = DB::table('kuisioner_umrah')->where('id', $kuisioner_umrah_id->kuisioner_umrah_id)->update(['umrah_id' => $umrah_id_aktif]);

                    // #update umrah_id di tb jawaban kuisioner umrah where kuisioner_umrah_id
                    $update_jawaban_kuisioner = DB::update("UPDATE jawaban_kuisioner_umrah set umrah_id = $umrah_id_aktif where kuisioner_umrah_id = $kuisioner_umrah_id->kuisioner_umrah_id");
                    
                    // #update jawaban essay
                    $update_jawaban_essay = DB::update("UPDATE essay_jawaban_kuisioner_umrah set umrah_id = $umrah_id_aktif where kuisioner_umrah_id = $kuisioner_umrah_id->kuisioner_umrah_id");

    
            }
            
    
            #get id / aktivitas_umrah_id di tb aktivitas_umrah where id umrah non aktif
            $aktivitas_umrah_id = collect(\DB::select("SELECT id from aktivitas_umrah where umrah_id = $umrah_id_non_aktif"))->first();

            if($aktivitas_umrah_id != null){

                #delete detail_aktivitas_umrah where aktivitas_umrah_id
                $delete_detail_aktivitas_umrah = DB::table('detail_aktivitas_umrah')->where('aktivitas_umrah_id', $aktivitas_umrah_id->id)->delete();
                
                // #delete aktivitas_umrah where aktivitas_umrah_id
                $delete_aktivitas_umrah = DB::delete("DELETE from aktivitas_umrah where id = $aktivitas_umrah_id->id");
                
            }

            #lihat hasil
            $kuisioner_umrah = DB::select("SELECT b.id , b.umrah_id , c.nama as kuisioner, b.jumlah_responden, f.count_jamaah , e.nama as pembimbing  from jawaban_kuisioner_umrah as a 
                                join kuisioner_umrah as b on b.id = a.kuisioner_umrah_id
                                join kuisioner as c on c.id = b.kuisioner_id
                                join aktivitas_umrah as d on d.umrah_id = b.umrah_id
                                join pembimbing as e on e.id = d.pembimbing_id 
                                join umrah as f on f.id = d.umrah_id
                                where a.umrah_id = $umrah_id_aktif
                                group by b.id , b.umrah_id , c.nama, e.nama, b.jumlah_responden , f.count_jamaah");

            $jadwal_aktif = DB::table('aktivitas_umrah')->where('umrah_id', $umrah_id_aktif)->get();
            $jadwal_non_aktif = DB::table('aktivitas_umrah')->where('umrah_id', $umrah_id_non_aktif)->get();

            $result = [
                'kuisioner_umrah' => $kuisioner_umrah,
                'jadwal_aktif' => $jadwal_aktif,
                'jadwal_non_aktif' => $jadwal_non_aktif
            ];

            
            DB::commit();
           return response()->json([
                'message' => 'Sukses!',
                'data' => $result
           ]);

        }catch(\Exception $e){
            DB::rollback();
            return $e->getMessage();
        }


    }


}
