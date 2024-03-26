<?php

namespace App\Http\Controllers;

use App\AktivitasUmrahModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->aps_level_id == 1 || Auth::user()->aps_level_id == 2) {
            return view('home');
        }elseif(Auth::user()->aps_level_id == 4){

            return redirect()->route('user.aktivitas.petugas.index');
            
        }elseif(Auth::user()->aps_level_id == 8) { // muthowwif
            
            return redirect()->route('user.aktivitas.muthowwif.index');

         }else{
            return redirect()->route('user.aktivitas.index'); // pembimbing
        }
    }
}
