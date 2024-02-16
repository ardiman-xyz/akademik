<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use Storage;

class Student_passwordController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       $this->validate($request,[
         'rowpage'=>'numeric|nullable'
       ]);
       $search = Input::get('search');
       $Nim = Input::get('nim');
       $department = Input::get('department');

       if ($Nim == null && $search==null) {
         $dat = DB::table('acd_student')
         ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
         ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
         ->where('acd_student.Nim', $Nim)
         ->get();
         $search = "";
       }elseif ($Nim != null) {
         $dat = DB::table('acd_student')
         ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
         ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
         ->where('acd_student.Nim', $Nim)
         ->get();
         $search = "";
       }elseif($Nim == null && $search != null ) {
         $dat = DB::table('acd_student')
         ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
         ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
         ->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'")
         ->get();
         $Nim = "";
       }
       return view('student_password/index')->with('query',$dat)->with('nim',$Nim)->with('search',$search);
     }
     // public function modal()
     // {
     //   return view('mstr_entry_year/modal');
     // }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {

     }


     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {

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
       $nim = Input::get('nim');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');

       $data = DB::table('acd_student')
       ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
       ->where('Student_Id', $id)
       ->get();
       if(count($data) == 0) { return view('404'); }
       return view('student_password/edit')->with('query_edit', $data)
       ->with('nim',$nim)->with('page', $page)->with('rowpage', $rowpage);
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
       $this->validate($request,[
         'Student_Password' => 'nullable|min:5|max:15|regex:/^[a-zA-Z1-9][a-zA-Z0-9.,;]+$/',
         'Parent_Password' => 'nullable|min:5|max:15||regex:/^[a-zA-Z1-9][a-zA-Z0-9.,;]+$/'

       ]);

             $Student_Password = Input::get('Student_Password');
             $Parent_Password = Input::get('Parent_Password');


             try {
               if ($Student_Password == null && $Parent_Password == null) {
                 return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
               }elseif ($Student_Password == null) {
                 $u =  DB::table('acd_student')
                 ->where('Student_Id',$id)
                 ->update(
                   ['Parent_Password' => md5($Parent_Password)]);
               }elseif($Parent_Password == null) {
                 $u =  DB::table('acd_student')
                 ->where('Student_Id',$id)
                 ->update(
                   ['Student_Password' => md5($Student_Password)]);
               }else {
                 $u =  DB::table('acd_student')
                 ->where('Student_Id',$id)
                 ->update(
                   ['Student_Password' => md5($Student_Password), 'Parent_Password' => md5($Parent_Password)]);
               }


               return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');
             } catch (\Exception $e) {
               return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
             }
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
       try {
         DB::table('acd_student')->where('Student_Id', $id)->delete();
         Alert::success('Berhasil Menghapus Data', 'Success');
         // return Redirect::back()->withErrors('Berhasil Menghapus Data');
         return Redirect::back();
       } catch (\Exception $e) {
         Alert::error('Gagal Menghapus Data, Kemungkinan data msih digunakan', 'Failed');
         // return Redirect::back()->withErrors('Gagal Menghapus Data, Kemungkinan data msih digunakan');
         return Redirect::back();

       }
     }
 }
