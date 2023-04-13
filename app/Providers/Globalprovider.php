<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Globalprovider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function __construct()
    {
        
    }


    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function numberFormat($data)
    {
        $show = number_format($data,1);
        return $show;
    }

    public function userMenus()
    {
        $user_id = Auth::user()->id;
        $sql = DB::table('users as a')
                ->join('aps_level as b','a.aps_level_id','=','b.id')
                ->join('aps_previlage as c','b.id','=','c.level_id')
                ->join('aps_menus as d','c.menu_id','=','d.id')
                ->whereNull('d.menu_parent_id')
                ->select('d.menu_name','d.id','d.menu_route','d.menu_icon','d.menu_type')
                ->where('a.id', $user_id)
                ->orderBy('d.menu_order','asc')
                ->get();
        return $sql;
            
    }

    public function userSubMenus($id)
    {
        $sql = DB::table('aps_menus')->select('menu_name','menu_route','menu_icon','menu_type')
                    ->where('menu_parent_id', $id)
                    ->orderBy('menu_order','asc')
                    ->get();
        return $sql;
            
    }

    public function persen($data)
    {
        $show = number_format($data,1);
        return $show;
    }

    public function calculateGradeByUmrah($data)
    {
        $grade = 'Sedang Dikalkulasi';
        if ($data >= 909 AND $data >= 957) {
            $grade = 'A';
        }
		if ($data >= 814 AND $data <= 908 ) {
            $grade = 'B';
        }
		if ($data >= 622 AND $data <= 813 ) {
            $grade = 'C';
        }if($data <= 621){
            $grade = 'D';
        }

        return $grade;
    }

    public function generateNilaiKuisioner($nomor){

        $nilai = 0;

        if ($nomor == 1) {

             $nilai = 100;

        }elseif ($nomor == 2) {

             $nilai = 50;

        }elseif ($nomor == 3) {

             $nilai = 50;

        }elseif ($nomor == 4) {

         $nilai = 100;

        }

        return $nilai;
    }

    public function generateNilaiAkhirKuisioner($data, $kuisioner, $no_jawaban_rumus, $result_nilai){

        // $sum = collect($data)->sum(function($q) use ($kuisioner) {
        //     $avg = ($q->jml_jawaban/$kuisioner->jumlah_responden)*100;

        //     return $avg;
        // });

        // #hitung jawaban
        $count = count($data);

        // if ($count = 2) {
            
        // }

        $result_nilai = 0;
        foreach ($data as $val) {

            $avg = ($val->jml_jawaban/$kuisioner->jumlah_responden)*100;
            $r_persentage = $this->generateNilaiKuisioner($no_jawaban_rumus++);
            $n_avg = round($avg);
            $result_nilai = ($n_avg*$r_persentage)/$n_avg;
        }

        if ($couunt = 2) {
            $result_nilai = $result_nilai;  
        }elseif ($count = 3) {
            $result_nilai = ($result_nilai + 50) - 100;  
        }elseif ($count = 4) {
            $result_nilai = ($result_nilai + 50) - 50 - 100;  
        }

        return $result_nilai;

    }

    public function generateNilaiKuisionerV2($jml_jawaban, $rata_rata){

        // if ($nomor == 0) {

        //      $nilai = 100;

        // }elseif ($nomor == 1) {

        //      $nilai = 50;

        // }elseif ($nomor == 2) {

        //      $nilai = 50;

        // }elseif ($nomor == 3) {

        //  $nilai = 100;

        // }

        // return $nilai;

        $result = 0;

        if ($jml_jawaban == 1) {
            #jika jumlah jawaban = 1, maka tampilkan nilai $rata_rata langsung
            $result = (($rata_rata[0]*100)/100);

        }elseif ($jml_jawaban == 2) {
            # jika jumlah jawaban = 2, maka 
                #(($rata_rata[0] * 100)/100) + (($rata_rata[1] * 50)/100) - 0 - 0;
            $result = (($rata_rata[0]*100)/100) + (($rata_rata[1]*50)/100) - 0 - 0;

        }elseif ($jml_jawaban == 3) {
            #jika jumlah jawaban = 3, maka
                #(($rata_rata[0] * 100) / 100) + (($rata_rata[1] * 50) / 100) - (($rata_rata[2]*50)/100) - 0;
            $result = (($rata_rata[0]*100)/100) + (($rata_rata[1]*50)/100) - (($rata_rata[2]*50)/100) - 0;

        }elseif ($jml_jawaban == 4) {
            #jika jumlah jawaban = 4, maka
                #(($rata_rata[0] * 100) / 100) + (($rata_rata[1] * 50) / 100) - (($rata_rata[2]*50)/100) - 0 - 0;
            $result = (($rata_rata[0]*100)/100) + (($rata_rata[1]*50)/100) - (($rata_rata[2]*50)/100) - (($rata_rata[3]*100)/100);

        }

       return ceil($result);
    }
}
