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

class CurriculumController extends Controller
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
         $data = DB::table('mstr_curriculum')
         ->orderBy('mstr_curriculum.Order_Id', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_curriculum')
         ->where(function($query){
           $search = Input::get('search');
           $query->whereRaw("lower(Curriculum_Code) like '%" . strtolower($search) . "%'");
           $query->orwhereRaw("lower(Curriculum_Name) like '%" . strtolower($search) . "%'");
         })
         //->where('Curriculum_Code', 'LIKE', '%'.$search.'%')
         //->orwhere('Curriculum_Name', 'LIKE', '%'.$search.'%')
         ->orderBy('mstr_curriculum.Order_Id', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_curriculum/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
       return view('mstr_curriculum/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Curriculum_Code'=>'required',
         'Curriculum_Name'=>'required',
       ]);
             $Curriculum_Code = Input::get('Curriculum_Code');
             $Curriculum_Name = Input::get('Curriculum_Name');
             $Order_Id = Input::get('Order_Id');

 try {
       $u =  DB::table('mstr_curriculum')
       ->insert(
       ['Curriculum_Code' => $Curriculum_Code, 'Curriculum_Name' => $Curriculum_Name,'Order_Id' => $Order_Id]);
       return Redirect::back()->withErrors('Berhasil Menambah Jenis Matakuliah');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Jenis Matakuliah');
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
       $data = DB::table('mstr_curriculum')
       ->where('Curriculum_Id',$id)
       ->orderBy('mstr_curriculum.Curriculum_Code', 'asc')
       ->get();
       return view('mstr_curriculum/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
       $data = DB::table('mstr_curriculum')
       ->where('Curriculum_Id',$id)
       ->orderBy('mstr_curriculum.Curriculum_Code', 'asc')
       ->get();
       return view('mstr_curriculum/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Curriculum_Code'=>'required',
         'Curriculum_Name'=>'required',
       ]);
             $Curriculum_Code = Input::get('Curriculum_Code');
             $Curriculum_Name = Input::get('Curriculum_Name');
             $Order_Id = Input::get('Order_Id');

             try {
               $u =  DB::table('mstr_curriculum')
               ->where('Curriculum_Id',$id)
               ->update(
               ['Curriculum_Code' => $Curriculum_Code, 'Curriculum_Name' => $Curriculum_Name,'Order_Id' => $Order_Id]);
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
         $q=DB::table('mstr_curriculum')->where('Curriculum_Id', $id)->delete();
         echo json_encode($q);
     }
}
