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

class Graduate_predicateController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','show']]);
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
        $data = DB::table('mstr_graduate_predicate')
        ->orderBy('mstr_graduate_predicate.Graduate_Predicate_Code', 'asc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('mstr_graduate_predicate')
        ->whereRaw("lower(Predicate_Name) like '%" . strtolower($search) . "%'")
        ->orwhere('Graduate_Predicate_Code', 'LIKE', '%'.$search.'%')
        ->orderBy('mstr_graduate_predicate.Graduate_Predicate_Code', 'asc')
        ->paginate($rowpage);
      }
      $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('mstr_graduate_predicate/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
      return view('mstr_graduate_predicate/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
        'Graduate_Predicate_Code'=>'required',
        'Predicate_Name'=>'required',
        'Predicate_Name_Eng'=>'required',
      ]);
            $Graduate_Predicate_Code = Input::get('Graduate_Predicate_Code');
            $Predicate_Name = Input::get('Predicate_Name');
            $Predicate_Name_Eng = Input::get('Predicate_Name_Eng');

try {
      $u =  DB::table('mstr_graduate_predicate')
      ->insert(
      ['Graduate_Predicate_Code' => $Graduate_Predicate_Code, 'Predicate_Name' => $Predicate_Name,'Predicate_Name_Eng' => $Predicate_Name_Eng]);
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
      $data = DB::table('mstr_graduate_predicate')
      ->where('Graduate_Predicate_Id',$id)
      ->get();
      return view('mstr_graduate_predicate/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
      $data = DB::table('mstr_graduate_predicate')
      ->where('Graduate_Predicate_Id',$id)
      ->get();
      return view('mstr_graduate_predicate/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
        'Graduate_Predicate_Code'=>'required',
        'Predicate_Name'=>'required',
        'Predicate_Name_Eng'=>'required',
      ]);
            $Graduate_Predicate_Code = Input::get('Graduate_Predicate_Code');
            $Predicate_Name = Input::get('Predicate_Name');
            $Predicate_Name_Eng = Input::get('Predicate_Name_Eng');

            try {
              $u =  DB::table('mstr_graduate_predicate')
              ->where('Graduate_Predicate_Id',$id)
              ->update(
              ['Graduate_Predicate_Code' => $Graduate_Predicate_Code, 'Predicate_Name' => $Predicate_Name,'Predicate_Name_Eng' => $Predicate_Name_Eng]);
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
        $a=DB::table('mstr_graduate_predicate')->where('Graduate_Predicate_Id', $id)->delete();
      echo json_encode($a);
    }
}
