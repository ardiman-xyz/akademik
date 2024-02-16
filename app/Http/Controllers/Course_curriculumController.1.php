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

class Course_curriculumController1 extends Controller
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
      dd(1);
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
       $class_program = Input::get('class_program');
       $curriculum = Input::get('curriculum');
       $semester = Input::get('semester');


       $select_class_program = DB::table('mstr_class_program')
       ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
       ->get();
       $select_curriculum = DB::table('mstr_curriculum')
       ->orderBy('mstr_curriculum.Curriculum_Name', 'desc')
       ->get();
       $select_semester = DB::table('mstr_study_level')
       ->orderBy('mstr_study_level.Study_Level_Code', 'asc')
       ->get();
       if($FacultyId==""){
         if($DepartmentId == ""){
          $select_department = DB::table('mstr_department')
          ->wherenotnull('Faculty_Id')
          ->orderBy('mstr_department.department_code', 'asc')
          ->get();
         }else{
          $select_department = DB::table('mstr_department')
          ->wherenotnull('Faculty_Id')
          ->where('Department_Id',$DepartmentId)
          ->orderBy('mstr_department.department_code', 'asc')
          ->get();
         }
       }else{
         if($DepartmentId == ""){
          $select_department = DB::table('mstr_department')
          ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->orderBy('mstr_department.department_code', 'asc')
          ->get();
         }else{
          $select_department = DB::table('mstr_department')
          ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->where('Department_Id',$DepartmentId)
          ->orderBy('mstr_department.department_code', 'asc')
          ->get();
         }
       }

  if ($search == null) {
    $data = DB::table('acd_course_curriculum')
    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_course_curriculum.Class_Prog_Id')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_course_curriculum.Department_Id')

    ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','acd_course_curriculum.Curriculum_Id')
    ->join('acd_course','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
    ->leftjoin('acd_course_group','acd_course_group.Course_Group_Id','=','acd_course_curriculum.Course_Group_Id')
    ->leftjoin('mstr_study_level','mstr_study_level.Study_Level_Id','=','acd_course_curriculum.Study_Level_Id')
    ->leftjoin('mstr_curriculum_type','mstr_curriculum_type.Curriculum_Type_Id','=','acd_course_curriculum.Curriculum_Type_Id')
    ->where('acd_course_curriculum.Department_Id', $department)
    ->where('acd_course_curriculum.Class_Prog_Id', $class_program)
    ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
    ->where('acd_course_curriculum.Study_Level_Id',$semester)
    ->orderBy('acd_course.Course_Code', 'asc')
    ->paginate($rowpage);
  }else {
    $data = DB::table('acd_course_curriculum')
    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_course_curriculum.Class_Prog_Id')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_course_curriculum.Department_Id')

    ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','acd_course_curriculum.Curriculum_Id')
    ->join('acd_course','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
    ->leftjoin('acd_course_group','acd_course_group.Course_Group_Id','=','acd_course_curriculum.Course_Group_Id')
    ->leftjoin('mstr_study_level','mstr_study_level.Study_Level_Id','=','acd_course_curriculum.Study_Level_Id')
    ->leftjoin('mstr_curriculum_type','mstr_curriculum_type.Curriculum_Type_Id','=','acd_course_curriculum.Curriculum_Type_Id')
    ->where('acd_course_curriculum.Department_Id', $department)
    ->where('acd_course_curriculum.Class_Prog_Id', $class_program)
    ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
    ->where('acd_course_curriculum.Study_Level_Id',$semester)
    ->where(function($query){
      $search = Input::get('search');
      $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
      $query->orwhereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
    })
    ->orderBy('acd_course.Course_Name', 'desc')
    ->paginate($rowpage);
  }


       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'curriculum'=> $curriculum, 'semester'=> $semester, 'department'=> $department]);
       return view('acd_course_curriculum/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_semester',$select_semester)->with('select_department', $select_department)->with('semester',$semester)->with('department', $department)->with('select_curriculum', $select_curriculum)->with('curriculum', $curriculum);
     }
     // public function modal()
     // {
     //   return view('mstr_term_year/modal');
     // }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         $search = Input::get('search');
         $rowpage = Input::get('rowpage');
         $FacultyId = Auth::user()->Faculty_Id;

         if ($rowpage == null) {
           $rowpage = 10;
         }

         $current_search = Input::get('current_search');
         $current_page = Input::get('current_page');
         $current_rowpage = Input::get('current_rowpage');
         $department = Input::get('department');
         $class_program = Input::get('class_program');
         $curriculum = Input::get('curriculum');
         $semester = Input::get('semester');

         $departmentn = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->where('department_id', $department)
         ->first();

         $departmentname = $departmentn->Department_Name;


if($FacultyId==""){
  $course_curriculum = DB::table('acd_course_curriculum')
  ->join('mstr_department','mstr_department.Department_Id','=','acd_course_curriculum.Department_Id')
  ->where('acd_course_curriculum.Department_Id', $department)
  ->where('Class_Prog_Id', $class_program)->where('Curriculum_Id', $curriculum)->select('Course_Id');

}else{
  $course_curriculum = DB::table('acd_course_curriculum')
  ->join('mstr_department','mstr_department.Department_Id','=','acd_course_curriculum.Department_Id')
  ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
  ->where('mstr_faculty.Faculty_Id', $FacultyId)
  ->where('acd_course_curriculum.Department_Id', $department)
  ->where('Class_Prog_Id', $class_program)->where('Curriculum_Id', $curriculum)->select('Course_Id');

}

         if($FacultyId==""){
           if ($search == null) {
             $course = DB::table('acd_course')
             ->where('acd_course.Department_Id', $department)
             ->whereNotIn('acd_course.Course_Id', $course_curriculum)
             ->paginate($rowpage);
           } else {
             $course = DB::table('acd_course')
             ->where('acd_course.Department_Id', $department)
             ->whereNotIn('acd_course.Course_Id', $course_curriculum)
             ->where(function($query){
               $search = Input::get('search');
               $query->whereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
               $query->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
             })
             ->paginate($rowpage);
           }
         }else{
           if ($search == null) {
             $course = DB::table('acd_course')
             ->join('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
             ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
             ->where('mstr_faculty.Faculty_Id', $FacultyId)
             ->where('acd_course.Department_Id', $department)
             ->whereNotIn('acd_course.Course_Id', $course_curriculum)
             ->paginate($rowpage);
           } else {
             $course = DB::table('acd_course')
             ->join('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
             ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
             ->where('mstr_faculty.Faculty_Id', $FacultyId)
             ->where('acd_course.Department_Id', $department)
             ->whereNotIn('acd_course.Course_Id', $course_curriculum)
             ->where(function($query){
               $search = Input::get('search');
               $query->whereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
               $query->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
             })
             ->paginate($rowpage);
           }
         }

         $km = DB::table('acd_course_group')->count();

         $notif = null;
         if($km<1){
           $notif = "Kelompok mata kuliah belum ada data ";
         }

         $cccount = DB::table('acd_course_group')->select('Course_Group_Id')->count();

         if($cccount == 0){
           $notif = "Kelompok mata kuliah belum ada data ";
           $ccc="";
         }else{
           $cc = DB::table('acd_course_group')->select('Course_Group_Id')->first();
           $ccc=$cc->Course_Group_Id;
         }
         // dd($cc);


         $course->appends(['class_program'=> $class_program,'curriculum'=> $curriculum,'semester'=>$semester, 'department'=> $department,'current_page'=> $current_page,'current_rowpage'=>$current_rowpage,'current_search'=>$current_search,'search'=> $search, 'rowpage'=> $rowpage]);
         return view('acd_course_curriculum/create')->with('departmentname', $departmentname)->with('ccc', $ccc)->with('notif', $notif)->with('course', $course)->with('class_program', $class_program)->with('department', $department)->with('curriculum', $curriculum)->with('semester',$semester)->with('search',$search)->with('rowpage', $rowpage)->with('current_page', $current_page)->with('current_rowpage', $current_rowpage)->with('current_search', $current_search);
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
         'Class_Prog_Id'=>'required',
         'Department_Id' => 'required',
         'Curriculum_Id' => 'required',
       ]);
             $Department_Id = Input::get('Department_Id');
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Curriculum_Id = Input::get('Curriculum_Id');
             $Study_Level_Id = Input::get('Study_Level_Id');
             $Course_Id = Input::get('Course_Id');
             $cc = Input::get('cc');
             $Datetimenow = Date('Y-m-d');
             $FacultyId = Auth::user()->Faculty_Id;

try{
       foreach ($Course_Id as $data) {
         DB::table('acd_course_curriculum')
         ->insert(
         ['Department_Id' => $Department_Id,'Class_Prog_Id' => $Class_Prog_Id,'Curriculum_Id' => $Curriculum_Id,'Study_Level_Id' => $Study_Level_Id,'Course_Id' => $data,'Is_For_Transcript' => true, 'Is_Required' => true, 'Course_Group_Id' => $cc, 'Curriculum_Type_Id' => 1, 'Is_Valid' => false, 'Created_Date' => $Datetimenow ]);
       }

       return Redirect::back()->withErrors('Berhasil Menambah Matakuliah Kurikulum');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Matakuliah Kurikulum');
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
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       $curriculum = Input::get('curriculum');
       $semester = Input::get('semester');
       $FacultyId = Auth::user()->Faculty_Id;

       $select_course_group = DB::table('acd_course_group')->get();
       $select_course_group = DB::table('acd_course_group')->get();
       $select_study_level = DB::table('mstr_study_level')->get();
       $select_curriculum_type = DB::table('mstr_curriculum_type')->get();


       $data = DB::table('acd_course_curriculum')
       ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_course_curriculum.Class_Prog_Id')
       ->join('mstr_department','mstr_department.Department_Id','=','acd_course_curriculum.Department_Id')
       ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','acd_course_curriculum.Curriculum_Id')
       ->join('acd_course','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
       ->leftjoin('acd_course_group','acd_course_group.Course_Group_Id','=','acd_course_curriculum.Course_Group_Id')
       ->leftjoin('mstr_study_level','mstr_study_level.Study_Level_Id','=','acd_course_curriculum.Study_Level_Id')
       ->leftjoin('mstr_curriculum_type','mstr_curriculum_type.Curriculum_Type_Id','=','acd_course_curriculum.Curriculum_Type_Id')
       ->where('acd_course_curriculum.Course_Cur_Id', $id)
       ->get();

       return view('acd_course_curriculum/edit')->with('query_edit', $data)->with('department', $department)->with('class_program', $class_program)->with('curriculum', $curriculum)->with('semester',$semester)->with('select_course_group', $select_course_group)->with('select_study_level', $select_study_level)->with('select_curriculum_type', $select_curriculum_type)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Applied_Sks' => 'numeric',
         'Transcript_Sks' => 'numeric',
         'Study_Level_Sub' => 'numeric',

       ]);
             $Applied_Sks = Input::get('Applied_Sks');
             $Transcript_Sks = Input::get('Transcript_Sks');
             $Is_For_Transcript = Input::get('Is_For_Transcript');
             $Is_Required = Input::get('Is_Required');
             $Course_Group_Id = Input::get('Course_Group_Id');
             $Study_Level_Id = Input::get('Study_Level_Id');
             $Study_Level_Sub = Input::get('Study_Level_Sub');
             $Curriculum_Type_Id = Input::get('Curriculum_Type_Id');


             try {
               $u =  DB::table('acd_course_curriculum')
               ->where('Course_Cur_Id',$id)
               ->update(
               ['Applied_Sks' => $Applied_Sks,'Transcript_Sks' => $Transcript_Sks,'Is_For_Transcript' => $Is_For_Transcript,'Is_Required' => $Is_Required, 'Course_Group_Id' => $Course_Group_Id, 'Study_Level_Id' => $Study_Level_Id, 'Study_Level_Sub' => $Study_Level_Sub, 'Curriculum_Type_Id' => $Curriculum_Type_Id]);
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
         $q=DB::table('acd_course_curriculum')->where('Course_Cur_Id', $id)->delete();
         echo json_encode($q);
     }
 }
