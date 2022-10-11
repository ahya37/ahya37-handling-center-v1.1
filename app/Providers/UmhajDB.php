<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use mysqli;

class UmhajDB extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
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
	
	  public function __construct()
    {
        
    }
	

    public function getApsDB($execute)
    {
        $config         = new mysqli("bigcarica4.fastcloud.id","percscoi_percik","percik123456");
        if ($config->connect_error) {
        die("Connection failed: " . $config->connect_error);
        } 
        $result     = $config->query($execute);
        // $config->close();
        return $result;
    }
}
