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

class Register_statusController extends Controller
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
         $data = DB::table('mstr_register_status')
         ->orderBy('mstr_register_status.Register_Status_Code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_register_status')
         ->whereRaw("lower(Register_Status_Name) like '%" . strtolower($search) . "%'")
         ->orderBy('mstr_register_status.Register_Status_Code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_register_status/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
       return view('mstr_register_status/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Register_Status_Code'=>'required|max:6',
         'Register_Status_Name'=>'required',
         'Register_Status_Acronym'=>'required|max:5',
       ]);
             $Register_Status_Code = Input::get('Register_Status_Code');
             $Register_Status_Name = Input::get('Register_Status_Name');
             $Register_Status_Acronym = Input::get('Register_Status_Acronym');
             $Order_Id = Input::get('Order_Id');

try {
       $u =  DB::table('mstr_register_status')
       ->insert(
       ['Register_Status_Code' => $Register_Status_Code, 'Register_Status_Name' => $Register_Status_Name, 'Register_Status_Acronym' => $Register_Status_Acronym ,'Order_Id' => $Order_Id]);
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
       $data = DB::table('mstr_register_status')
       ->where('Register_Status_Id',$id)
       ->orderBy('mstr_register_status.Register_Status_Code', 'asc')
       ->get();
       return view('mstr_register_status/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
       $data = DB::table('mstr_register_status')
       ->where('Register_Status_Id',$id)
       ->orderBy('mstr_register_status.Register_Status_Code', 'asc')
       ->get();
       return view('mstr_register_status/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Register_Status_Code'=>'required|max:6',
         'Register_Status_Name'=>'required',
         'Register_Status_Acronym'=>'required|max:5',
       ]);
             $Register_Status_Code = Input::get('Register_Status_Code');
             $Register_Status_Name = Input::get('Register_Status_Name');
             $Register_Status_Acronym = Input::get('Register_Status_Acronym');
             $Order_Id = Input::get('Order_Id');

             try {
               $u =  DB::table('mstr_register_status')
               ->where('Register_Status_Id',$id)
               ->update(
               ['Register_Status_Code' => $Register_Status_Code, 'Register_Status_Name' => $Register_Status_Name, 'Register_Status_Acronym' => $Register_Status_Acronym ,'Order_Id' => $Order_Id]);
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
         $q=DB::table('mstr_register_status')->where('Register_Status_Id', $id)->delete();
         echo json_encode($q);
     }
}
