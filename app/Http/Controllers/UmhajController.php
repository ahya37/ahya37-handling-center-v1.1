<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\UmhajDB;
// use mysqli;
// use GuzzleHttp\Client;
use GuzzleHttp\Client;

class UmhajController extends Controller
{
    public function getRoomlist()
	{
		// $sql = new UmhajDB();
		// $execute = "select * from  percscoi_percikan.umrah"; 
		// $db  = $sql->getApsDB($execute);
		// return $db;
		// $config         = new mysqli("bigcarica4.fastcloud.id","percscoi_percik","percscoi_percikan","percik123456");
		// // return $config;
        // if ($config->connect_error) {
			// return "Connection failed: " . $config->connect_error;
        // }else{
			// return 'ok';
		// }
        // $result     = $config->query($execute);
        // $config->close();
        // return $result;
		$client = new Client(); //GuzzleHttp\Client
        $url = "https://api.perciktours.com/jadwalumrahbyyeard?year=2021";


        $response = $client->request('GET', $url);

        $responseBody = json_decode($response->getBody());

        return $responseBody;
	}
}

