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

class Course_typeController extends Controller
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
        $data = DB::table('acd_course_type')
        ->orderBy('acd_course_type.Course_Type_Code', 'asc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('acd_course_type')
        ->whereRaw("lower(Course_Type_Name) like '%" . strtolower($search) . "%'")
        //->where('Course_Type_Name', 'LIKE', '%'.$search.'%')
        ->orderBy('acd_course_type.Course_Type_Code', 'asc')
        ->paginate($rowpage);
      }
      $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('acd_course_type/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
      return view('acd_course_type/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
        'Course_Type_Code'=>'required',
        'Course_Type_Name'=>'required',
        'Id_Character'=>'required|max:3',
      ]);
            $Course_Type_Code = Input::get('Course_Type_Code');
            $Course_Type_Name = Input::get('Course_Type_Name');
            $Id_Character = Input::get('Id_Character');

try {
      $u =  DB::table('acd_course_type')
      ->insert(
      ['Course_Type_Code' => $Course_Type_Code, 'Course_Type_Name' => $Course_Type_Name,'Id_Character' => $Id_Character]);
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
      $data = DB::table('acd_course_type')
      ->where('Course_Type_Id',$id)
      ->orderBy('acd_course_type.Course_Type_Code', 'asc')
      ->get();
      return view('acd_course_type/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
        'Course_Type_Code'=>'required',
        'Course_Type_Name'=>'required',
        'Id_Character'=>'required|max:3',
      ]);
            $Course_Type_Code = Input::get('Course_Type_Code');
            $Course_Type_Name = Input::get('Course_Type_Name');
            $Id_Character = Input::get('Id_Character');

            try {
              $u =  DB::table('acd_course_type')
              ->where('Course_Type_Id',$id)
              ->update(
              ['Course_Type_Code' => $Course_Type_Code, 'Course_Type_Name' => $Course_Type_Name,'Id_Character' => $Id_Character]);
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
        $rs=DB::table('acd_course_type')->where('Course_Type_Id', $id)->delete();
        echo json_encode($rs);
    }
}
