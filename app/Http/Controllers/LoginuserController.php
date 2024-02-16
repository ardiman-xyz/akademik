<?php

namespace App\Http\Controllers;
use App\Model\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Input;
use DB;
use Redirect;
use Alert;

class LoginuserController extends Controller
{
  public function index(){
      if(!Session::get('login')){
          return redirect('ceklogin')->with('alert','Kamu harus login dulu');
      }
      else{
          return view('ceklogin');
      }
  }

  public function login(){
        return view('ceklogin');
    }

    public function loginPost(Request $request){
        $email = $request->email;
        $password = $request->password;
        $data = User::where('email',$email)->first();
        $datac = User::where('email',$email)->count();
        // dd($password);
        if($datac > 0){ //apakah email tersebut ada atau tidak
            if(Hash::check($password,$data->password)){
                Session::put('name',$data->name);
                Session::put('email',$data->email);
                Session::put('login',TRUE);
                return redirect('/home');
            }
            else{
                return redirect('login')->with('alert','Password atau Email, Salah !'.Hash::check($password,$data->password));
            }
        }
        else{
            return redirect('login')->with('alert','Password atau Email, Salahaa!');
        }
    }
    public function logout(){
        Session::flush();
        return redirect('login')->with('alert','Kamu sudah logout');
    }
}
