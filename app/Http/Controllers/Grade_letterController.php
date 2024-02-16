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

class Grade_letterController extends Controller
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
        $data = DB::table('acd_grade_letter')
        ->orderBy('acd_grade_letter.Grade_Letter', 'asc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('acd_grade_letter')
        ->whereRaw("lower(Grade_Letter) like '%" . strtolower($search) . "%'")
        ->orderBy('acd_grade_letter.Grade_Letter', 'asc')
        ->paginate($rowpage);
      }
      $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('acd_grade_letter/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
      return view('acd_grade_letter/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
        'grade_letter'=>'required|unique:acd_grade_letter',
      ]);
            $grade_letter = Input::get('grade_letter');

  try {
      $u =  DB::table('acd_grade_letter')
      ->insert(
      ['Grade_Letter' => $grade_letter]);
      return Redirect::back()->withErrors('Berhasil Menambah Nilai Huruf');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menambah Nilai Huruf');
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
      $data = DB::table('acd_grade_letter')
      ->where('Grade_Letter_Id',$id)
      ->orderBy('acd_grade_letter.Grade_Letter', 'asc')
      ->get();
      return view('acd_grade_letter/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
        'grade_letter'=>'required',

      ]);
            $grade_letter = Input::get('grade_letter');

            try {
              $u =  DB::table('acd_grade_letter')
              ->where('Grade_Letter_Id',$id)
              ->update(
              ['Grade_Letter' => $grade_letter]);
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
      $rs=DB::table('acd_grade_letter')->where('Grade_Letter_Id', $id)->delete();
      echo json_encode($rs);
    }
}
