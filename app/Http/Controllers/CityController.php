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

class CityController extends Controller
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
       $province = Input::get('province');

       $select_province = DB::table('mstr_province')
       ->orderBy('mstr_province.Province_Code', 'asc')
       ->get();


       if ($search == null) {
         $data = DB::table('mstr_city')
         ->join('mstr_province','mstr_province.Province_Id','=','mstr_city.Province_Id')
         ->where('mstr_city.Province_Id', $province)
         ->select('mstr_city.*','mstr_province.*','mstr_city.Order_Id')
         ->orderBy('mstr_city.City_Code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_city')
         ->join('mstr_province','mstr_province.Province_Id','=','mstr_city.Province_Id')
         ->where('mstr_city.Province_Id', $province)
         ->whereRaw("lower(City_Name) like '%" . strtolower($search) . "%'")
         ->select('mstr_city.*','mstr_province.*','mstr_city.Order_Id')
         ->orderBy('mstr_city.City_Code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'province'=> $province]);
       return view('mstr_city/index')->with('select_province', $select_province)->with('province', $province)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
       $province = Input::get('province');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $select_province = DB::table('mstr_province')->where('Province_Id', $province)->get();
       return view('mstr_city/create')->with('province', $province)->with('select_province', $select_province)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'City_Code'=>'required|max:6',
         'City_Name'=>'required',


       ]);
             $Province_Id  = Input::get('Province_Id');
             $City_Code = Input::get('City_Code');
             $City_Name = Input::get('City_Name');
             $Order_Id = Input::get('Order_Id');

try{
       $u =  DB::table('mstr_city')
       ->insert(
       ['City_Code' => $City_Code, 'City_Name' => $City_Name, 'Province_Id' => $Province_Id, 'Order_Id' => $Order_Id]);
       return Redirect::back()->withErrors('Berhasil Menambah Kota');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Kota');
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
       $data = DB::table('mstr_city')
       ->where('City_Id',$id)
       ->join('mstr_province','mstr_province.Province_Id','=','mstr_city.Province_Id')
       ->select('mstr_city.*','mstr_province.*','mstr_city.Order_Id')
       ->orderBy('mstr_city.City_Code', 'asc')
       ->get();
       $select_province = DB::table('mstr_province')->get();
       return view('mstr_city/edit')->with('query_edit',$data)->with('select_province', $select_province)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'City_Code'=>'required|max:6',
         'City_Name'=>'required',


       ]);
             $Province_Id  = Input::get('Province_Id');
             $City_Code = Input::get('City_Code');
             $City_Name = Input::get('City_Name');
             $Order_Id = Input::get('Order_Id');

             try {
               $u =  DB::table('mstr_city')
               ->where('City_Id',$id)
               ->update(
                ['City_Code' => $City_Code, 'City_Name' => $City_Name, 'Province_Id' => $Province_Id, 'Order_Id' => $Order_Id]);
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
        $q=DB::table('mstr_city')->where('City_Id', $id)->delete();
        echo json_encode($q);
     }
 }
