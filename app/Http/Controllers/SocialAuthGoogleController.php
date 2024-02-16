<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Auth\User;
use Socialite;
use Auth;
use Exception;
use Redirect;

class SocialAuthGoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            
        
            $googleUser = Socialite::driver('google')->user();
            $existUser = User::where('email',$googleUser->email)->first();
            $cekUser = User::where('email',$googleUser->email)
                        ->where('app','Akademik')
                        ->join('_role_user','_role_user.user_id','=','_user.id')
                        ->join('_role','_role_user.role_id','=','_role.id')
                        ->first();
            

            if($existUser) {
                if($cekUser){
                    Auth::loginUsingId($existUser->id);
                }else{
                    return redirect()->to('/login')->withErrors('Email Tidak Punya Akses Simakad');
                }
            }
            else {
                // return redirect()->to('proses/transcript_equivalensi/'.$Student_Id.'?department='.$department.'&entry_year='.$entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search);
                return redirect()->to('/login')->withErrors('Email Tidak Terdaftar');

                // $name = $googleUser->email;
                // dd($name);
                // $user = new User;
                // $user->name = $googleUser->name;
                // $user->email = $googleUser->email;
                // $user->google_id = $googleUser->id;
                // $user->password = md5(rand(1,10000));
                // $user->save();
                // Auth::loginUsingId($user->id);
            }
            return redirect()->to('/home');
        } 
        catch (Exception $e) {
            return 'error';
        }
    }
}
