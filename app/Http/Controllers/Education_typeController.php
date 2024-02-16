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

class Education_typeController extends Controller
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
         $data = DB::table('mstr_education_type')
         ->orderBy('mstr_education_type.education_type_code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_education_type')
         ->whereRaw("lower(Education_Type_Name) like '%" . strtolower($search) . "%'")
         ->orderBy('mstr_education_type.education_type_code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_education_type/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);
     }
     // public function modal()
     // {
     //   return view('mstr_education_type/modal');
     // }
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
         return view('mstr_education_type/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Education_Type_Code'=>'required|max:3',
         'Education_Type_Name' => 'required',
       ]);
             $Education_Type_Code = Input::get('Education_Type_Code');
             $Education_Type_Name = Input::get('Education_Type_Name');

    try {
       $u =  DB::table('mstr_education_type')
       ->insert(
       ['Education_Type_Code' => $Education_Type_Code,'Education_Type_Name' => $Education_Type_Name]);
       return Redirect::back()->withErrors('Berhasil Menambah Jenjang Pendidikan');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menyimpan Jenjang Pendidikan');
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
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $data = DB::table('mstr_education_type')
       ->where('Education_Type_Id',$id)
       ->orderBy('mstr_education_type.education_type_code', 'asc')
       ->get();
       return view('mstr_education_type/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);;
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
         'Education_Type_Code'=>'required|max:3',
         'Education_Type_Name' => 'required',
       ]);
             $Education_Type_Code = Input::get('Education_Type_Code');
             $Education_Type_Name = Input::get('Education_Type_Name');

             try {
               $u =  DB::table('mstr_education_type')
               ->where('Education_Type_Id',$id)
               ->update(
               ['Education_Type_Code' => $Education_Type_Code,'Education_Type_Name' => $Education_Type_Name]);
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
         $rs=DB::table('mstr_education_type')->where('Education_Type_Id', $id)->delete();
        echo json_encode($rs);
     }
}
