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

class FacultyController extends Controller
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
        $fakultas = DB::table('mstr_faculty')
        ->orderBy('mstr_faculty.faculty_code', 'asc')
        ->paginate($rowpage);
      }else {
        $fakultas = DB::table('mstr_faculty')
        ->whereRaw("lower(Faculty_Name) like '%" . strtolower($search) . "%'")
        ->orderBy('mstr_faculty.faculty_code', 'asc')
        ->paginate($rowpage);
      }
      $fakultas->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('mstr_faculty/index')->with('FacultyId', $FacultyId)->with('fakultas',$fakultas)->with('search',$search)->with('rowpage',$rowpage);
    }
    // public function modal()
    // {
    //   return view('mstr_faculty/modal');
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
        return view('mstr_faculty/create')->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);
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
        'Faculty_Code'=>'required',
        'Faculty_Name' => 'required',
        'Faculty_Acronym' => 'required',

      ]);
            $Faculty_Code = Input::get('Faculty_Code');
            $Faculty_Name = Input::get('Faculty_Name');
            $Faculty_Name_Eng = Input::get('Faculty_Name_Eng');
            $Faculty_Acronym = Input::get('Faculty_Acronym');
            $Order_Id = Input::get('Order_Id');

try {
      $u =  DB::table('mstr_faculty')
      ->insert(
      ['Faculty_Code' => $Faculty_Code,'Faculty_Name' => $Faculty_Name,'Faculty_Name_Eng' => $Faculty_Name_Eng,'Faculty_Acronym' => $Faculty_Acronym, 'Order_Id' => $Order_Id]);
      return Redirect::back()->withErrors('Berhasil Menambah Departemen');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
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
      $fakultas = DB::table('mstr_faculty')
      ->where('Faculty_Id',$id)
      ->get();
      return view('mstr_faculty/edit')->with('query_edit',$fakultas)->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);
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
        'Faculty_Code'=>'required',
        'Faculty_Name' => 'required',
        'Faculty_Acronym' => 'required',

      ]);
            $Faculty_Code = Input::get('Faculty_Code');
            $Faculty_Name = Input::get('Faculty_Name');
            $Faculty_Name_Eng = Input::get('Faculty_Name_Eng');
            $Faculty_Acronym = Input::get('Faculty_Acronym');
            $Order_Id = Input::get('Order_Id');

            try {
              $u =  DB::table('mstr_faculty')
              ->where('Faculty_Id',$id)
              ->update(
              ['Faculty_Code' => $Faculty_Code,'Faculty_Name' => $Faculty_Name,'Faculty_Name_Eng' => $Faculty_Name_Eng,'Faculty_Acronym' => $Faculty_Acronym, 'Order_Id' => $Order_Id]);
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
      $rs=DB::table('mstr_faculty')->where('Faculty_Id', $id)->delete();
      echo json_encode($rs);
    }
}
