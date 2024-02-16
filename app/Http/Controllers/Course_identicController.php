<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use Auth;

class Course_identicController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','destroycourse']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','destroycourse']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','destroycourse']]);
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
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');

  if($FacultyId==""){
    if($DepartmentId ==""){
      $select_Department_Id = DB::table('mstr_department')
      ->wherenotnull('Faculty_Id')
      ->orderBy('mstr_department.Department_Id', 'desc')
      ->get();
    }else{
      $select_Department_Id = DB::table('mstr_department')
      ->wherenotnull('Faculty_Id')
      ->where('Department_Id',$DepartmentId)
      ->orderBy('mstr_department.Department_Id', 'desc')
      ->get();
    }
  }else{
    if($DepartmentId == ""){
      $select_Department_Id = DB::table('mstr_department')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->orderBy('mstr_department.Department_Id', 'desc')
      ->get();
    }else{
      $select_Department_Id = DB::table('mstr_department')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->where('mstr_department.Department_Id',$DepartmentId)
      ->orderBy('mstr_department.Department_Id', 'desc')
      ->get();
    }
  }

  if ($search == null) {
    $data = DB::table('acd_course_identic')

    ->where('acd_course_identic.Department_Id', $department)
    ->select('acd_course_identic.*',
    DB::raw('(SELECT Group_Concat(acd_course.Course_Name SEPARATOR "|") FROM acd_course_identic_detail LEFT JOIN acd_course ON acd_course_identic_detail.Course_Id = acd_course.Course_Id WHERE acd_course_identic_detail.Course_Identic_Id = acd_course_identic.Course_Identic_Id) as matakuliah'),
    DB::raw('(SELECT Group_Concat(acd_course.Course_Code SEPARATOR "|") FROM acd_course_identic_detail LEFT JOIN acd_course ON acd_course_identic_detail.Course_Id = acd_course.Course_Id WHERE acd_course_identic_detail.Course_Identic_Id = acd_course_identic.Course_Identic_Id) as kodematakuliah'))
    ->orderBy('acd_course_identic.Course_Identic_Id', 'asc')
    ->paginate($rowpage);
  }else {
    $data = DB::table('acd_course_identic')
    ->where('acd_course_identic.Department_Id', $department)
    ->whereRaw("lower(acd_course_identic.Identic_Name) like '%" . strtolower($search) . "%'")
    //->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'")
    // ->where('acd_course_identic.Identic_Name', 'LIKE', '%'.$search.'%')
    // ->orwhere('acd_course.Course_Name', 'LIKE', '%'.$search.'%')
    ->select('acd_course_identic.*',
    DB::raw('(SELECT Group_Concat(acd_course.Course_Name SEPARATOR "|") FROM acd_course_identic_detail LEFT JOIN acd_course ON acd_course_identic_detail.Course_Id = acd_course.Course_Id WHERE acd_course_identic_detail.Course_Identic_Id = acd_course_identic.Course_Identic_Id ) as matakuliah'),
    DB::raw('(SELECT Group_Concat(acd_course.Course_Code SEPARATOR "|") FROM acd_course_identic_detail LEFT JOIN acd_course ON acd_course_identic_detail.Course_Id = acd_course.Course_Id WHERE acd_course_identic_detail.Course_Identic_Id = acd_course_identic.Course_Identic_Id) as kodematakuliah'))
    ->orderBy('acd_course_identic.Course_Identic_Id', 'asc')
    ->paginate($rowpage);
  }


       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
       return view('acd_course_identic/index')->with('select_Department_Id', $select_Department_Id)->with('department', $department)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
       $department = Input::get('department');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $FacultyId = Auth::user()->Faculty_Id;

      $mstr_department = DB::table('mstr_department')
      ->wherenotnull('Faculty_Id')
      ->where('Department_Id', $department)->get();

       $acd_course_identic_detail = DB::table('acd_course_identic_detail')
       ->select('Course_Id');
       $select_course = DB::table('acd_course')
       ->join('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
       ->where('acd_course.Department_Id', $department)
       ->whereNotIn('Course_Id', $acd_course_identic_detail)->get();
       return view('acd_course_identic/create')->with('department', $department)->with('mstr_department', $mstr_department)->with('select_course', $select_course)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Department_Id'=>'required',
         'Identic_Name'=>'required',
         'Course_Id'=>'required',


       ]);
             // $Functional_Position_Curriculum_Id  = Input::get('Functional_Position_Curriculum_Id');
             $Identic_Name = Input::get('Identic_Name');
             $Department_Id = Input::get('Department_Id');
             $Course_Id = Input::get('Course_Id');




       DB::table('acd_course_identic')
       ->insert(
       ['Identic_Name' => $Identic_Name, 'Department_Id' => $Department_Id]);

       $Course_Identic_Id = DB::getPdo()->lastInsertId();

       foreach ($Course_Id as $data) {
         DB::table('acd_course_identic_detail')
         ->insert(
         ['Course_Id' => $data, 'Course_Identic_Id' => $Course_Identic_Id]);
       }

       return Redirect::back()->withErrors('Berhasil Menambah Matakuliah Setara');
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
       $department = Input::get('department');
       $FacultyId = Auth::user()->Faculty_Id;

      $course = DB::table('acd_course_identic')
      ->join('mstr_department','mstr_department.Department_Id','=','acd_course_identic.Department_Id')
      ->join('acd_course_identic_detail', 'acd_course_identic_detail.Course_Identic_Id','=','acd_course_identic.Course_Identic_Id')
      ->join('acd_course', 'acd_course.Course_Id','=','acd_course_identic_detail.Course_Id')
      ->where('acd_course_identic.Course_Identic_Id', $id)
      ->orderBy('acd_course_identic_detail.Crs_Identic_Dtl_Id', 'asc')
      ->get();
      $data = DB::table('acd_course_identic')
      ->join('mstr_department','mstr_department.Department_Id','=','acd_course_identic.Department_Id')
      ->where('acd_course_identic.Course_Identic_Id', $id)
      ->get();


        $acd_course_identic_detail = DB::table('acd_course_identic_detail')
        ->select('Course_Id');
        $select_course = DB::table('acd_course')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
        ->where('acd_course.Department_Id', $department)
        ->whereNotIn('Course_Id', $acd_course_identic_detail)->get();
       return view('acd_course_identic/edit')->with('query_edit', $data)->with('query_edit_course',$course)->with('select_course', $select_course)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Course_Id'=>'required',
       ]);
             $Course_Id = Input::get('Course_Id');

         try {
           foreach ($Course_Id as $data) {
             DB::table('acd_course_identic_detail')
             ->insert(
             ['Course_Id' => $data, 'Course_Identic_Id' => $id]);
           }

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
         $q=DB::table('acd_course_identic_detail')->where('Course_Identic_Id', $id)->delete();
         $q=DB::table('acd_course_identic')->where('Course_Identic_Id', $id)->delete();
         echo json_encode($q);
     }
     public function destroy_course(Request $request, $id)
     {
         $rs = array();
         $aci_id = DB::table('acd_course_identic_detail')->where('Crs_Identic_Dtl_Id', $id)->first()->Course_Identic_Id;
         $departmentid = DB::table('acd_course_identic')->where('Course_Identic_Id', $aci_id)->first()->Department_Id;
         $rs['data']=DB::table('acd_course_identic_detail')->where('Crs_Identic_Dtl_Id', $id)->delete();

         $acil = DB::table('acd_course_identic_detail')->where('Course_Identic_Id', $aci_id)->get();
         if ($acil->count() == 0) {
           $rs['data']=DB::table('acd_course_identic')->where('Course_Identic_Id', $aci_id)->delete();
           $rs['Redirect'] = true;
         }
           echo json_encode($rs);
     }
 }
