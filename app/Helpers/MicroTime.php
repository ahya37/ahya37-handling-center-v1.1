<?php 

namespace App\Helpers;

class MicroTime
{

    public static function generate($lenid=20) 
    {  
        $minid	= 20;
        $maxid	= 30; // canot change max 30
        if($lenid < $minid){$lenid = $minid;}
        if($lenid > $maxid){$lenid = $maxid;}

        $sel = $lenid - $minid;
        $u_ts = microtime();
        if (  strpos($u_ts, ' ') === false   ) { $ts = trim($u_ts); $u 	= '';} 
        else{ list($ts, $u) = explode(" ", $u_ts);}
        
        $digitmt	= 2;
        $ts = str_replace(".", mt_rand(1, 9), substr($ts, 2, $digitmt));
        if(strlen($ts) < $digitmt){$ts = mt_rand(  str_repeat("1", $digitmt - strlen($ts) ) ,  str_repeat("9", $digitmt - strlen($ts) ) ).$ts;}
        $dt = date('YmdHis', $u);
        $ran = "";
        if($sel > 0){$ran = mt_rand(  str_repeat("1", $sel ) ,  str_repeat("9", $sel ) );}	
        
        return $dt.$ts.$ran;
        
    }
}