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

class ConcentrationController extends Controller
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
     public function index(Request $request, $fakultas = 0)
     {
       $this->validate($request,[
         'rowpage'=>'numeric|nullable'
       ]);
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }

       $department = Input::get('department');

       $FacultyId = Auth::user()->Faculty_Id;
       if ($FacultyId != null) {
         $select_fakultas = DB::table('mstr_faculty')
         ->where('Faculty_Id', $FacultyId)
         ->orderBy('mstr_faculty.faculty_code', 'asc')
         ->get();
       }else {
         $select_fakultas = DB::table('mstr_faculty')
         ->orderBy('mstr_faculty.faculty_code', 'asc')
         ->get();
       }
       $select_department = DB::table('mstr_department')
       ->where('Faculty_Id', $fakultas)
       ->orderBy('mstr_department.department_code', 'asc')
       ->get();

       if ($search == null) {
         $data = DB::table('mstr_concentration')
         ->join('mstr_department','mstr_department.Department_Id','=','mstr_concentration.Department_Id')
         ->where('mstr_concentration.Department_Id', $department)
         ->orderBy('department_code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_concentration')
         ->join('mstr_department','mstr_department.Department_Id','=','mstr_concentration.Department_Id')
         ->where('mstr_concentration.Department_Id', $department)
         ->whereRaw("lower(Concentration_Name) like '%" . strtolower($search) . "%'")
         ->orderBy('department_code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
       return view('mstr_concentration/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_fakultas', $select_fakultas)->with('fakultas', $fakultas)->with('select_department', $select_department)->with('department', $department);
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
     public function create($Faculty_Id = "")
     {
         $search = Input::get('search');
         $page = Input::get('page');
         $rowpage = Input::get('rowpage');
         $deparment = Input::get('department');


         $cek = DB::table('mstr_department')->where('Department_Id', $deparment)->where('Faculty_Id', $Faculty_Id)->get();
         if(count($cek) == 0) { return view('404'); }
         return view('mstr_concentration/create')->with('Department_Id', $deparment)->with('Faculty_Id', $Faculty_Id)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Concentration_Code'=>'required',
         'Concentration_Name' => 'required',
         'Concentration_Acronym' => 'required',
         'Faculty_Id' => 'required',
         'Department_Id' => 'required',
       ]);
             $Department_Id = Input::get('Department_Id');
             $Concentration_Code = Input::get('Concentration_Code');
             $Concentration_Name = Input::get('Concentration_Name');
             $Concentration_Name_Eng = Input::get('Concentration_Name_Eng');
             $Concentration_Acronym = Input::get('Concentration_Acronym');
             $Order_Id = Input::get('Order_Id');

try {
       $u =  DB::table('mstr_concentration')
       ->insert(
       ['Department_Id' => $Department_Id,'Concentration_Code' => $Concentration_Code,'Concentration_Name' => $Concentration_Name,'Concentration_Name_Eng' => $Concentration_Name_Eng,'Concentration_Acronym' => $Concentration_Acronym, 'Order_Id' => $Order_Id]);
       return Redirect::back()->withErrors('Berhasil Menambah Konsentrasi');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menyimpan Konsentrasi');
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
     public function edit($id,$fakultas = "")
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $department = Input::get('department');

       $data = DB::table('mstr_concentration')->join('mstr_department','mstr_department.Department_Id','=','mstr_concentration.Department_Id')->where('Concentration_Id', $id)->where('mstr_concentration.Department_Id', $department)->where('mstr_department.Faculty_Id', $fakultas)->get();
       if(count($data) == 0) { return view('404'); }
       return view('mstr_concentration/edit')->with('query_edit', $data)->with('Faculty_Id', $fakultas)->with('Department_Id', $department)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Concentration_Code'=>'required',
         'Concentration_Name' => 'required',
         'Concentration_Acronym' => 'required',
       ]);
             $Concentration_Code = Input::get('Concentration_Code');
             $Concentration_Name = Input::get('Concentration_Name');
             $Concentration_Name_Eng = Input::get('Concentration_Name_Eng');
             $Concentration_Acronym = Input::get('Concentration_Acronym');
             $Order_Id = Input::get('Order_Id');

             try {
               $u =  DB::table('mstr_concentration')
               ->where('Concentration_Id',$id)
               ->update(
               ['Concentration_Code' => $Concentration_Code,'Concentration_Name' => $Concentration_Name,'Concentration_Name_Eng' => $Concentration_Name_Eng,'Concentration_Acronym' => $Concentration_Acronym, 'Order_Id' => $Order_Id]);
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
     public function destroy(request $request,$id)
     {
         $rs=DB::table('mstr_concentration')->where('Concentration_Id', $id)->delete();
         echo json_encode($rs);
     }
}
