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

class PeriodewisudaController extends Controller
{
    public function __construct()
    {
      $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
      $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
      $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
      $this->middleware('access:CanDelete', ['except' => ['index','create','update','store','show','edit','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
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
        $data = DB::table('acd_graduation_period')
        ->orderBy('Graduation_Period_Id', 'asc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('acd_graduation_period')
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(Period_Name) like '%" . strtolower($search) . "%'");
        })
        ->orderBy('Graduation_Period_Id', 'asc')
        ->paginate($rowpage);
      }
      $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('mstr_periodewisuda/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);
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
        return view('mstr_periodewisuda/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          $Period_Name = Input::get('Period_Name');
          $Graduation_Place = Input::get('Graduation_Place');
          $Graduation_Date = Input::get('Graduation_Date');
          $Yudisium_Date = Input::get('Yudisium_Date');

          try {
                $u =  DB::table('acd_graduation_period')
                ->insert(
                ['Period_Name' => $Period_Name, 'Graduation_Date' => $Graduation_Date,'Graduation_Place'=>$Graduation_Place,'End_Date_Yudisium'=>$Yudisium_Date]);
                return Redirect::back()->withErrors('Berhasil Menambah Periode Wisuda');
              } catch (\Exception $e) {
                return Redirect::back()->withErrors('Gagal Menambah Periode Wisuda');
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
      $data = DB::table('acd_graduation_period')->where('Graduation_Period_Id',$id)->orderBy('Graduation_Period_Id', 'asc')
      ->get();;
        return view('mstr_periodewisuda/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
      $Period_Name = Input::get('Period_Name');
      $Graduation_Place = Input::get('Graduation_Place');
      $Graduation_Date = Input::get('Graduation_Date');
      $Yudisium_Date = Input::get('Yudisium_Date');

      try {
        $u =  DB::table('acd_graduation_period')
        ->where('Graduation_Period_Id',$id)
        ->update(
         ['Period_Name' => $Period_Name, 'Graduation_Date' => $Graduation_Date,'Graduation_Place'=>$Graduation_Place,'End_Date_Yudisium'=>$Yudisium_Date]);
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
    public function destroy($id)
    {
      $q=DB::table('acd_graduation_period')->where('Graduation_Period_Id', $id)->delete();
      echo json_encode($q);
    }
}
