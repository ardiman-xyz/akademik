<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;

class High_school_majorController extends Controller
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
       $rowpage = Input::get('rowpage');
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }


       if ($search == null) {
         $data = DB::table('mstr_high_school_major')
         ->orderBy('mstr_high_school_major.High_School_Major_Code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_high_school_major')
         ->whereRaw("lower(High_School_Major_Name) like '%" . strtolower($search) . "%'")
         ->orderBy('mstr_high_school_major.High_School_Major_Code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_high_school_major/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       return view('mstr_high_school_major/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'High_School_Major_Code'=>'required|max:6|unique:mstr_high_school_major',
         'High_School_Major_Name'=>'required',
       ]);
             $High_School_Major_Code = Input::get('High_School_Major_Code');
             $High_School_Major_Name = Input::get('High_School_Major_Name');
             $Order_Id = Input::get('Order_Id');

try {
       $u =  DB::table('mstr_high_school_major')
       ->insert(
       ['High_School_Major_Code' => $High_School_Major_Code, 'High_School_Major_Name' => $High_School_Major_Name,'Order_Id' => $Order_Id]);
       return Redirect::back()->withErrors('Berhasil Menambah Status Daftar');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Status Daftar');
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
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $data = DB::table('mstr_high_school_major')
       ->where('High_School_Major_Id',$id)
       ->orderBy('mstr_high_school_major.High_School_Major_Code', 'asc')
       ->get();
       return view('mstr_high_school_major/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $data = DB::table('mstr_high_school_major')
       ->where('High_School_Major_Id',$id)
       ->orderBy('mstr_high_school_major.High_School_Major_Code', 'asc')
       ->get();
       return view('mstr_high_school_major/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'High_School_Major_Code'=>'required|max:6',
         'High_School_Major_Name'=>'required',
       ]);
             $High_School_Major_Code = Input::get('High_School_Major_Code');
             $High_School_Major_Name = Input::get('High_School_Major_Name');
             $Order_Id = Input::get('Order_Id');

             try {
               $u =  DB::table('mstr_high_school_major')
               ->where('High_School_Major_Id',$id)
               ->update(
               ['High_School_Major_Code' => $High_School_Major_Code, 'High_School_Major_Name' => $High_School_Major_Name,'Order_Id' => $Order_Id]);
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
     public function destroy(Request $request,$id)
     {
         $q=DB::table('mstr_high_school_major')->where('High_School_Major_Id', $id)->delete();
         echo json_encode($q);
     }
}
