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

class Blodd_typeController extends Controller
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
         $data = DB::table('mstr_blood_type')
         ->orderBy('mstr_blood_type.Blood_Code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_blood_type')
         ->where(function($query){
           $search = Input::get('search');
           $query->whereRaw("lower(Blood_Type) like '%" . strtolower($search) . "%'");
         })
         ->orderBy('mstr_blood_type.Blood_Code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_blood_type/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
       return view('mstr_blood_type/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Blood_Code'=>'required|max:3|unique:mstr_blood_type',
         'Blood_Type'=>'required|max:10|unique:mstr_blood_type',
         'Order_Id'=>'required|max:3',
       ]);
             $Blood_Code = Input::get('Blood_Code');
             $Blood_Type = Input::get('Blood_Type');
             $Order_Id = Input::get('Order_Id');

try {
       $u =  DB::table('mstr_blood_type')
       ->insert(
       ['Blood_Code' => $Blood_Code, 'Blood_Type' => $Blood_Type,'Order_Id' => $Order_Id]);
       return Redirect::back()->withErrors('Berhasil Menambah Golongan Darah');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Golongan Darah');
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
       $data = DB::table('mstr_blood_type')
       ->where('Blood_Type_Id',$id)
       ->orderBy('mstr_blood_type.Blood_Code', 'asc')
       ->get();
       return view('mstr_blood_type/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
       $data = DB::table('mstr_blood_type')
       ->where('Blood_Type_Id',$id)
       ->orderBy('mstr_blood_type.Blood_Code', 'asc')
       ->get();
       return view('mstr_blood_type/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Blood_Code'=>'required|max:3',
         'Blood_Type'=>'required|max:10',
         'Order_Id'=>'required|max:3',
       ]);
             $Blood_Code = Input::get('Blood_Code');
             $Blood_Type = Input::get('Blood_Type');
             $Order_Id = Input::get('Order_Id');

             try {
               $u =  DB::table('mstr_blood_type')
               ->where('Blood_Type_Id',$id)
               ->update(
               ['Blood_Code' => $Blood_Code, 'Blood_Type' => $Blood_Type,'Order_Id' => $Order_Id]);
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
         $q=DB::table('mstr_blood_type')->where('Blood_Type_Id', $id)->delete();
        echo json_encode($q);
     }
}
