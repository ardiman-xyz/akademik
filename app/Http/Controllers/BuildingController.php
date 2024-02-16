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


class BuildingController extends Controller
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
         $data = DB::table('mstr_building')
         ->orderBy('mstr_building.Building_Code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_building')
         ->where('Building_Name', 'LIKE', '%'.$search.'%')
         ->orderBy('mstr_building.Building_Code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_building/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
       return view('mstr_building/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Building_Code'=>'required|max:6|unique:mstr_building',
         'Building_Name'=>'required',
       ]);
             $Building_Code = Input::get('Building_Code');
             $Building_Name = Input::get('Building_Name');

try {
       $u =  DB::table('mstr_building')
       ->insert(
       ['Building_Code' => $Building_Code, 'Building_Name' => $Building_Name]);
       return Redirect::back()->withErrors('Berhasil Menambah Gedung');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Gedung');
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
       $data = DB::table('mstr_building')
       ->where('Building_Id',$id)
       ->orderBy('mstr_building.Building_Code', 'asc')
       ->get();
       return view('mstr_building/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
       $data = DB::table('mstr_building')
       ->where('Building_Id',$id)
       ->orderBy('mstr_building.Building_Code', 'asc')
       ->get();
       return view('mstr_building/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Building_Code'=>'required|max:6',
         'Building_Name'=>'required',
       ]);
             $Building_Code = Input::get('Building_Code');
             $Building_Name = Input::get('Building_Name');

             try {
               $u =  DB::table('mstr_building')
               ->where('Building_Id',$id)
               ->update(
               ['Building_Code' => $Building_Code, 'Building_Name' => $Building_Name]);
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
         $q=DB::table('mstr_building')->where('Building_Id', $id)->delete();
         echo json_encode($q);
     }
}
