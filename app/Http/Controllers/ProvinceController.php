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

class ProvinceController extends Controller
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
         $data = DB::table('mstr_province')
         ->join('mstr_country','mstr_province.Country_Id','=','mstr_country.Country_Id')
         ->orderBy('mstr_province.Province_Code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_province')
         ->join('mstr_country','mstr_province.Country_Id','=','mstr_country.Country_Id')
         ->where(function($query){
           $search = Input::get('search');
           $query->whereRaw("lower(Province_Name) like '%" . strtolower($search) . "%'");
           $query->orwhereRaw("lower(Province_Acronym) like '%" . strtolower($search) . "%'");
           $query->orwhereRaw("lower(mstr_country.Country_Name) like '%" . strtolower($search) . "%'");
         })
         ->orderBy('mstr_province.Province_Code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_province/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
       $select_country = DB::table('mstr_country')->get();
       return view('mstr_province/create')->with('select_country', $select_country)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Province_Code'=>'required',
         'Province_Name'=>'required',
         'Country_Id'=>'required',

       ]);
             $Country_Id = Input::get('Country_Id');
             $Province_Code = Input::get('Province_Code');
             $Province_Name = Input::get('Province_Name');
             $Province_Acronym = Input::get('Province_Acronym');
             $Order_Id = Input::get('Order_Id');

try {
       $u =  DB::table('mstr_province')
       ->insert(
       ['Country_Id' => $Country_Id,'Province_Code' => $Province_Code, 'Province_Name' => $Province_Name,'Province_Acronym' => $Province_Acronym,'Order_Id' => $Order_Id]);
       return Redirect::back()->withErrors('Berhasil Menambah Provinsi');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Provinsi');
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
       $data = DB::table('mstr_province')
       ->join('mstr_country','mstr_province.Country_Id','=','mstr_country.Country_Id')
       ->where('Province_Id',$id)
       ->select('mstr_country.*','mstr_province.*','mstr_province.Order_Id')
       ->orderBy('mstr_province.Province_Code', 'asc')
       ->get();
       return view('mstr_province/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
       $data = DB::table('mstr_province')
       ->join('mstr_country','mstr_province.Country_Id','=','mstr_country.Country_Id')
       ->where('Province_Id',$id)
       ->select('mstr_country.*','mstr_province.*','mstr_province.Order_Id')
       ->get();
       $select_country = DB::table('mstr_country')->get();
       return view('mstr_province/edit')->with('query_edit',$data)->with('select_country', $select_country)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Province_Code'=>'required',
         'Province_Name'=>'required',
         'Country_Id'=>'required',

       ]);
             $Country_Id = Input::get('Country_Id');
             $Province_Code = Input::get('Province_Code');
             $Province_Name = Input::get('Province_Name');
             $Province_Acronym = Input::get('Province_Acronym');
             $Order_Id = Input::get('Order_Id');

             try {
               $u =  DB::table('mstr_province')
               ->where('Province_Id',$id)
               ->update(
               ['Country_Id' => $Country_Id,'Province_Code' => $Province_Code, 'Province_Name' => $Province_Name,'Province_Acronym' => $Province_Acronym,'Order_Id' => $Order_Id]);
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
         $q=DB::table('mstr_province')->where('Province_Id', $id)->delete();
         echo json_encode($q);
     }
}
