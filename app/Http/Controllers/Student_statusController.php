<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Input;
use DB;
use Redirect;
use Alert;

class Student_statusController extends Controller
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
        $data = DB::table('mstr_status')
        ->orderBy('Status_Id', 'asc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('mstr_status')
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(Staus_Name) like '%" . strtolower($search) . "%'");
        })
        ->orderBy('Status_Id', 'asc')
        ->paginate($rowpage);
      }
      $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('mstr_status/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
       return view('mstr_status/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Status_Code'=>'required',
         'Status_Name'=>'required',
       ]);
             $Status_Code = Input::get('Status_Code');
             $Status_Name = Input::get('Status_Name');

try {
       $u =  DB::table('mstr_status')
       ->insert(
       ['Status_Code' => $Status_Code, 'Status_Name' => $Status_Name]);
       return Redirect::back()->withErrors('Berhasil Menambah Data');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Data');
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
       $data = DB::table('mstr_status')
       ->where('Status_Id',$id)
       ->get();
       return view('mstr_status/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Status_Code'=>'required',
         'Status_Name'=>'required',
       ]);
             $Status_Code = Input::get('Status_Code');
             $Status_Name = Input::get('Status_Name');

             try {
               $u =  DB::table('mstr_status')
               ->where('Status_Id',$id)
               ->update(
               ['Status_Code' => $Status_Code, 'Status_Name' => $Status_Name]);
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
     public function destroy(Request $request, $id)
     {
       // try {
       //   DB::table('mstr_status')->where('Status_Id', $id)->delete();
       //   //Alert::success('Berhasil Menghapus Data', 'Success');
       //   return Redirect::back()->withErrors('Berhasil Menghapus Data');
       //   //return Redirect::back();
       // } catch (\Exception $e) {
       //   Alert::error('Gagal Menghapus Data, Kemungkinan data msih digunakan', 'Failed');
       //   // return Redirect::back()->withErrors('Gagal Menghapus Data, Kemungkinan data msih digunakan');
       //
       // }
       $rs = DB::table('mstr_status')->where('Status_Id', $id)->delete();
       // echo json_encode($id);
       echo json_encode($rs);
       // return Redirect::back();
     }
}
