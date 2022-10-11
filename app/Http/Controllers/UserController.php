<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\PembimbingModel;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;

class UserController extends Controller
{
    public function myProfile()
    {
        $user_id = Auth::user()->id;
        $user    = User::select('email','id','name')->where('id', $user_id)->first();

        return view('users.profile', ['user' => $user]);
    }

    public function updateProfile(Request $request, $user_id)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                    'email' => 'required|email|max:255',
            ]);

            $pembimbing = PembimbingModel::where('user_id', $user_id)->first();

            $userModel  = new User();
            $user       = $userModel->where('id', $user_id)->first();

            // cek email
            $cek_email = $userModel->where('email', $request->email)
                        ->where('id','!=',$user_id)->count();

            // BUAT AKUN BARU DI USERS
            $password = $request->password == '' ? $user->password : Hash::make($request->password);
            
            if($cek_email == 0){
                $user->update([
                     'name' => $request->name,
                     'email' => $request->email,
                     'password' => $password,
                     'password_alias' => $request->password
                 ]);

                  $pembimbing->update([
                     'nama' => $request->name,
                 ]);
            }else{
                return redirect()->back()->with(['warning' => 'Email sudah terdaftar']);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
           echo 'Message: ' .$e->getMessage();
        }

        return redirect()->route('user.myprofile')->with(['success' => 'Berhasil ubah profil']);
    }
}
