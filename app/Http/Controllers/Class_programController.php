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
use Auth;

class Class_programController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','show']]);
    $this->middleware('access:CanAdd', ['only' => ['create','store']]);
    $this->middleware('access:CanEdit', ['only' => ['edit','update']]);
    $this->middleware('access:CanDelete', ['only' => ['destroy']]);
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
       $FacultyId = Auth::user()->Faculty_Id;

       if ($search == null) {
         $data = DB::table('mstr_class_program')
         ->orderBy('mstr_class_program.class_prog_code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_class_program')
         ->whereRaw("lower(Class_Program_Name) like '%" . strtolower($search) . "%'")
         ->orderBy('mstr_class_program.class_prog_code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_class_program/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);
     }
     // public function modal()
     // {
     //   return view('mstr_class_program/modal');
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
         return view('mstr_class_program/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Class_Prog_Code'=>'required',
         'Class_Program_Name' => 'required',
       ]);
             $Class_Prog_Code = Input::get('Class_Prog_Code');
             $Class_Program_Name = Input::get('Class_Program_Name');
             $Class_Program_Name_Eng = Input::get('Class_Program_Name_Eng');
             $Order_Id = Input::get('Order_Id');

try {
       $u =  DB::table('mstr_class_program')
       ->insert(
       ['Class_Prog_Code' => $Class_Prog_Code,'Class_Program_Name' => $Class_Program_Name,'Class_Program_Name_Eng' => $Class_Program_Name_Eng, 'Order_Id' => $Order_Id]);
       return Redirect::back()->withErrors('Berhasil Menambah Program Kelas');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menyimpan Program Kelas');
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
       $data = DB::table('mstr_class_program')
       ->where('Class_Prog_Id',$id)
       ->orderBy('mstr_class_program.class_prog_code', 'asc')
       ->get();
       return view('mstr_class_program/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);;
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
         'Class_Prog_Code'=>'required',
         'Class_Program_Name' => 'required',
       ]);
             $Class_Prog_Code = Input::get('Class_Prog_Code');
             $Class_Program_Name = Input::get('Class_Program_Name');
             $Class_Program_Name_Eng = Input::get('Class_Program_Name_Eng');
             $Class_Program_Acronym = Input::get('Class_Program_Acronym');
             $Order_Id = Input::get('Order_Id');

             try {
               $u =  DB::table('mstr_class_program')
               ->where('Class_Prog_Id',$id)
               ->update(
               ['Class_Prog_Code' => $Class_Prog_Code,'Class_Program_Name' => $Class_Program_Name,'Class_Program_Name_Eng' => $Class_Program_Name_Eng, 'Order_Id' => $Order_Id]);
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
     public function destroy(request $request,$id)
     {
         $rs=DB::table('mstr_class_program')->where('Class_Prog_Id', $id)->delete();
         echo json_encode($rs);
     }
}
