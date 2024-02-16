<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use App\User;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;

class UbahPasssayaController extends Controller
{

  // public function __construct()
  // {
  //   $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
  // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $id = Auth::user()->id;
      $pass = Auth::user()->password;
       // dd($pass);
      return view('ubahpasswordsaya/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request,[
        'oldpass'=>'required',
        'password'=>'required',
        'confirm'=>'required|same:password',
      ]);
      $oldpass  = Input::get('oldpass');
      $password = Input::get('password');

      $oldpass2 = bcrypt($oldpass);
      $hash = bcrypt($password);

      $id = Auth::user()->id;
      $pass = Auth::user()->password;
      if (Hash::check($oldpass, $pass)) {
        $ubah = DB::table('_user')
          ->where('id', $id)
          ->update(['password' => $hash ]);
        return Redirect::back()->withErrors('Password Berhasil diubah');
      }else {
        return Redirect::back()->withErrors('Password Lama Salah');
      }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
