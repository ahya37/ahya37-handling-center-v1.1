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
        $sql = DB::table('aps_menus')->select('menu_name','menu_route','menu_icon','menu_type')->where('menu_parent_id', $id)->get();
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
}
