<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Helpers\ResponseFormatter;
use App\PembimbingModel;
use App\User;
use DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        //set validation
        $validator = Validator::make($request->all(), [
            'email'     => 'required',
            'password'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email','password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return ResponseFormatter::error([
                'message' => 'Email atau password salah'
            ], 401);

        }

        return ResponseFormatter::success([
            'user' => auth()->user(),
            'token' => $token
            ],200);

    }

    public function logout(Request $request)
    {
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Logout'
            ]);
        }
        
    }

    public function updateProfile(Request $request)
    {

        DB::beginTransaction();
        try {
            
            $user_id =  auth()->user()->id;
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
                 ]);

                  $pembimbing->update([
                     'nama' => $request->name,
                 ]);

                 DB::commit();
                 return ResponseFormatter::success([
                    'message' => 'Profil berhasil di ubah!'
                 ],200);

            }else{
                return ResponseFormatter::error(null,'Email sudah terdaftar');
            }
            
        } catch (\Exception $e) {
            DB::rollback();
            return ResponseFormatter::error([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
