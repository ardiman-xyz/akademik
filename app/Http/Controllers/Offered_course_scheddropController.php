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
use Excel;
use App\GetDepartment;

class Offered_course_scheddropController extends Controller
{
  public function __construct()
  {
    // $this->middleware('access:CanView', ['only' => ['index','show']]);
    // $this->middleware('access:CanEdit', ['only' => ['create','store']]);
    // $this->middleware('access:CanDelete', ['only' => ['destroy']]);
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
       
        $page = Input::get('page');
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;
       $curriculum = Input::get('curriculum');
       $Sched_Session_Group_Id = Input::get('sched_session_group_id');

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       
       $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }
       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
       ->get();
       $select_curriculum = DB::table('mstr_curriculum')
       ->orderBy('mstr_curriculum.Curriculum_Name', 'desc')
       ->get();

        $select_department = GetDepartment::getDepartment();

         $select_class_program = DB::table('mstr_department_class_program')
         ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
         ->where('mstr_department_class_program.Department_Id', $department)
         ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
         ->get();

         $select_sched_session_group = DB::table('acd_sched_session_group')
          ->orderBy('Sched_Session_Group_Name', 'asc')
          ->where('Department_Id','like','%'.$request->department.'%')
          ->orwhere('Department_Id',null)
          ->get();
         
          // groupsesi
        if ($Sched_Session_Group_Id != '99999') {
          //search
         if ($search == null) {
           $data = DB::table('acd_offered_course')
           ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
           ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
           ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
           ->where('acd_offered_course.Department_Id', $department)
           ->where('acd_offered_course.Class_Prog_Id', $class_program)
           ->where('acd_offered_course.Term_Year_Id', $term_year)
          //  ->where('cd.Sched_Session_Group_Id', $schedsession)
          //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
           ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
            DB::raw("(SELECT Group_Concat(acd_sched_session.Description SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id
                      AND acd_sched_session.Sched_Session_Group_Id = '$Sched_Session_Group_Id') as jadwal"),
            DB::raw("(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id 
                      LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id 
                      AND acd_sched_session.Sched_Session_Group_Id = '$Sched_Session_Group_Id') as room"),
            DB::raw("(SELECT Group_Concat(acd_offered_course_sched.Offered_Course_Sched_id SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id
                      AND acd_sched_session.Sched_Session_Group_Id = '$Sched_Session_Group_Id') as ocs"),
            DB::raw("(SELECT Group_Concat(acd_offered_course_sched.Offered_Course_id SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id
                      AND acd_sched_session.Sched_Session_Group_Id = '$Sched_Session_Group_Id') as oc"),
            DB::raw("(SELECT Group_Concat(acd_sched_session.Sched_Session_Group_Id SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id
                      AND acd_sched_session.Sched_Session_Group_Id = '$Sched_Session_Group_Id') as sg" )
            )
          ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('acd_offered_course.Class_Id', 'asc')
          ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
           ->paginate($rowpage);
          //  dd($data);
         }
         //ens search
         else {
            $data = DB::table('acd_offered_course')
           ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
           ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
           ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
           ->where('acd_offered_course.Department_Id', $department)
           ->where('acd_offered_course.Class_Prog_Id', $class_program)
           ->where('acd_offered_course.Term_Year_Id', $term_year)
          //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
           ->where(function($query){
             $search = Input::get('search');
             $query->whereRaw("lower(Course_Name) like '%" . strtolower($search) . "%'");
             $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
           })
           ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
            DB::raw("(SELECT Group_Concat(acd_sched_session.Description SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id
                      AND acd_sched_session.Sched_Session_Group_Id = '$Sched_Session_Group_Id') as jadwal"),
            DB::raw("(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id 
                      LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id 
                      AND acd_sched_session.Sched_Session_Group_Id = '$Sched_Session_Group_Id') as room"))
          ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('acd_offered_course.Class_Id', 'asc')
          ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
           ->paginate($rowpage);
           $data->appends(['Sched_Session_Group_Id'=>$Sched_Session_Group_Id,'select_sched_session_group'=>$select_sched_session_group,'search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department,'curriculum'=> $curriculum]);
         }
        }
        //endall groupsesi
        else{
            $data = DB::table('acd_offered_course')
           ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
           ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
           ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
           ->where('acd_offered_course.Department_Id', $department)
           ->where('acd_offered_course.Class_Prog_Id', $class_program)
           ->where('acd_offered_course.Term_Year_Id', $term_year)
          //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
           ->where(function($query){
             $search = Input::get('search');
             $query->whereRaw("lower(Course_Name) like '%" . strtolower($search) . "%'");
             $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
           })
           ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
            DB::raw("(SELECT Group_Concat(acd_sched_session.Description SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as jadwal"),
            DB::raw("(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR '|') 
                      FROM acd_offered_course_sched 
                      LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                      LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id 
                      LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                      WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as room"))
          ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('acd_offered_course.Class_Id', 'asc')
          ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
           ->paginate($rowpage);
           $data->appends(['Sched_Session_Group_Id'=>$Sched_Session_Group_Id,'select_sched_session_group'=>$select_sched_session_group,'search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department,'curriculum'=> $curriculum]);
        }

        $data->appends(['page'=> $page, 
        'rowpage'=> $rowpage, 
        'query'=> $data, 
        'sched_session_group_id'=> $Sched_Session_Group_Id, 
        'select_sched_session_group'=> $select_sched_session_group, 
        'select_curriculum'=> $select_curriculum, 
        'curriculum'=> $curriculum, 
        'select_class_program'=> $select_class_program, 
        'class_program'=> $class_program, 
        'select_department'=> $select_department, 
        'select_term_year'=> $select_term_year, 
        'term_year'=> $term_year, 
        'department'=> $department]);

       return view('acd_offered_course_scheddrop/index')
       ->with('page',$page)
       ->with('query',$data)
       ->with('Sched_Session_Group_Id',$Sched_Session_Group_Id)
       ->with('select_sched_session_group',$select_sched_session_group)
       ->with('select_curriculum', $select_curriculum)
       ->with('curriculum', $curriculum)
       ->with('search',$search)
       ->with('rowpage',$rowpage)
       ->with('select_class_program', $select_class_program)
       ->with('class_program', $class_program)
       ->with('select_department', $select_department)
       ->with('department', $department)
       ->with('select_term_year', $select_term_year)
       ->with('term_year', $term_year);
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



      public function create(Request $request)
      {
        $from = $request->from;
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');
        $id = Input::get('offered_course_id');
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $term_year = Input::get('term_year');
        $offered_course_id = Input::get('offered_course_id');
        $Sched_Type_Id = Input::get('sched_type_id');
        $sched_session_group_id = Input::get('sched_session_group_id');
        $curriculum = Input::get('curriculum');
        $FacultyId = Auth::user()->Faculty_Id;
        
        if($FacultyId==""){
          $data = DB::table('acd_offered_course')
          ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
          ->leftjoin('acd_course_curriculum','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->select('acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Applied_Sks')
          ->where('acd_offered_course.Offered_Course_id', $id)
          ->groupby('acd_offered_course.Offered_Course_id')
          ->get();
          // dd($data);
          $dosen = DB::table('acd_offered_course_lecturer as cl')
          ->join('emp_employee as e', 'cl.Employee_Id','=', 'e.Employee_Id')
          ->select('e.First_Title', 'e.Name', 'e.Last_Title','e.Employee_Id')
          ->where('Offered_Course_id',$id)
          ->get();

          // dd($dosen);

          $dosen2 = DB::table('acd_offered_course_lecturer as cl')
          ->join('emp_employee as e', 'cl.Employee_Id','=', 'e.Employee_Id')
          ->select('e.First_Title', 'e.Name', 'e.Last_Title')
          ->where('Offered_Course_id',$id)
          ->count();

          foreach($dosen as $dsn){
            $jdwl = DB::table('acd_offered_course_sched as a')
            ->leftjoin('acd_offered_course as b', 'a.Offered_Course_id', '=', 'b.Offered_Course_id')
            ->leftjoin('acd_offered_course_lecturer as c', 'a.Offered_Course_id' ,'=' ,'c.Offered_Course_id')
            ->groupBy('a.Offered_Course_Sched_id')
            ->where('c.Employee_Id',$dsn->Employee_Id)
            ->select('a.Sched_Session_Id','a.Room_Id')
            ->get();
          }

          $jdwl="";
          if($dosen == null){
            $jadwal = "";
          }else{
            $jadwal = $jdwl;
          }

          // dd($jadwal);
        }else{
          $data = DB::table('acd_offered_course')
          ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
          ->leftjoin('acd_course_curriculum','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->select('acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Applied_Sks')
          ->where('acd_offered_course.Offered_Course_id', $id)
          ->groupby('acd_offered_course.Offered_Course_id')
          ->get();
          $dosen = DB::table('acd_offered_course_lecturer as cl')
          ->join('emp_employee as e', 'cl.Employee_Id','=', 'e.Employee_Id')
          ->select('e.First_Title', 'e.Name', 'e.Last_Title','e.Employee_Id')
          ->where('Offered_Course_id',$id)
          ->get();

          // dd($dosen);

          $dosen2 = DB::table('acd_offered_course_lecturer as cl')
          ->join('emp_employee as e', 'cl.Employee_Id','=', 'e.Employee_Id')
          ->select('e.First_Title', 'e.Name', 'e.Last_Title')
          ->where('Offered_Course_id',$id)
          ->count();

          foreach($dosen as $dsn){
            $jdwl = DB::table('acd_offered_course_sched as a')
            ->leftjoin('acd_offered_course as b', 'a.Offered_Course_id', '=', 'b.Offered_Course_id')
            ->leftjoin('acd_offered_course_lecturer as c', 'a.Offered_Course_id' ,'=' ,'c.Offered_Course_id')
            ->groupBy('a.Offered_Course_Sched_id')
            ->where('c.Employee_Id',$dsn->Employee_Id)
            ->select('a.Sched_Session_Id','a.Room_Id')
            ->get();
          }

          $jdwl="";
          if($dosen == null){
            $jadwal = "";
          }else{
            $jadwal = $jdwl;
          }
        }

        $sched = DB::table('acd_offered_course_sched')
         ->join('acd_sched_session', 'acd_sched_session.Sched_Session_Id', '=', 'acd_offered_course_sched.Sched_Session_Id')
         ->join('mstr_room', 'mstr_room.Room_Id', '=', 'acd_offered_course_sched.Room_Id')
         ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_sched.Offered_Course_id')
         
         ->where('acd_offered_course_sched.Offered_Course_id', $id);
        //  ->where('acd_sched_session.Sched_Type_Id', $Sched_Type_Id);
        //  ->where('acd_sched_session.Sched_Session_Group_Id', $sched_session_group_id);
        //  ->where('acd_offered_course.Curriculum_Id', $curriculum);

         $scheds = $sched
         ->select('acd_offered_course_sched.*','mstr_room.Room_Name','acd_sched_session.Description','acd_sched_session.Sched_Session_Group_Id')
         ->orderBy('acd_sched_session.Description', 'asc')
         ->get();
        //  dd($scheds);

         $select_sched_session = DB::table('acd_sched_session')->get();

         $select_session_group = DB::table('acd_sched_session_group')
          ->orderBy('Sched_Session_Group_Name', 'asc')
          ->where('Department_Id','like','%'.$request->department.'%')
          ->orwhere('Department_Id',null)
          ->get();
          
         $select_sched_type = DB::table('mstr_sched_type')
         ->orderBy('Sched_Type_Name', 'asc')
         ->get();

         $notroom = DB::table('acd_offered_course_sched')->select('Room_Id');

         $select_room = DB::table('mstr_room')
         ->orderBy('Room_Name','desc')
         ->get();

         $Sched_Session_Group_Id = Input::get('sched_session_group_id');
       $Sched_Type_Id = Input::get('sched_type_id');

         $select_sched_session_group = DB::table('acd_sched_session_group')
       ->orderBy('Sched_Session_Group_Name', 'asc')
       ->get();
       $select_sched_type = DB::table('mstr_sched_type')
       ->orderBy('Sched_Type_Name', 'asc')
       ->get();

         $getjadwal = DB::table('acd_sched_session')
         ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','acd_sched_session.Sched_Session_Group_Id')
         ->join('mstr_sched_type','mstr_sched_type.Sched_Type_Id','=','acd_sched_session.Sched_Type_Id')
         ->join('mstr_day', 'mstr_day.Day_Id' ,'=' , 'acd_sched_session.Day_Id')
         ->where('acd_sched_session.Sched_Session_Group_Id', $Sched_Session_Group_Id)
         ->where('acd_sched_session.Sched_Type_Id', $Sched_Type_Id)
         ->select('acd_sched_session.*','mstr_day.Day_Name')
         ->groupBy('acd_sched_session.Day_Id')
         ->orderBy('acd_sched_session.Day_Id', 'asc')
         ->orderBy('Order_Id', 'asc')
         ->get();
         
         $order = DB::table('acd_sched_session')
         ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','acd_sched_session.Sched_Session_Group_Id')
         ->join('mstr_sched_type','mstr_sched_type.Sched_Type_Id','=','acd_sched_session.Sched_Type_Id')
         ->join('mstr_day', 'mstr_day.Day_Id' ,'=' , 'acd_sched_session.Day_Id')
         ->where('acd_sched_session.Sched_Session_Group_Id', $Sched_Session_Group_Id)
         ->where('acd_sched_session.Sched_Type_Id', $Sched_Type_Id)
         ->orderBy('Order_Id', 'asc')
         ->orderBy('acd_sched_session.Day_Id', 'asc')
         ->get();

        $ruangs = DB::table('mstr_room')->Where('mstr_room.Sched_Session_Group_Id',$sched_session_group_id)
          ->get();
          $cek_course = DB::table('acd_offered_course as a')
                    ->join('acd_course as b','a.Course_Id','=','b.Course_Id')
                    ->where('a.Offered_Course_id',$id)
                    ->select('b.Course_Name')
                    ->first();

         $cek_jadwal = DB::table('acd_offered_course')
           ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
           ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
           ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
           ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          //  ->where('acd_offered_course.Department_Id', $department)
           ->where('acd_offered_course.Class_Prog_Id', $class_program)
           ->where('acd_offered_course.Term_Year_Id', $term_year)
           ->where(function($query) use($cek_course){
              $search = $cek_course->Course_Name;
              $exp = explode("'",$search);
              if(count($exp)>0){
                $search="";
                for ($i=0; $i < count($exp); $i++) { 
                  if($i==0){
                    $search = $exp[$i];
                  }else {
                    $search = $search . "\\" . $exp[$i]; 
                  }
                }
              }
              $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
            })
          //  ->where("lower(Course_Name) like '%" . strtolower($cek_course->Course_Name) . "%'")
          //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
           ->select( 'acd_offered_course.*','mstr_department.Department_Name','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
            DB::raw('(SELECT Group_Concat(acd_sched_session.Description SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as jadwal'),
            DB::raw('(SELECT Group_Concat(acd_offered_course_sched.Offered_Course_Sched_id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as ocsi'),
            DB::raw('(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as room'))
          ->orderBy('acd_offered_course.Department_Id', 'asc')
          ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('acd_offered_course.Class_Id', 'asc')
          ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
           ->get();

        return view('acd_offered_course_scheddrop/create')
        ->with('request', $request)
        ->with('from', $from)
        ->with('query_edit', $data)
        ->with('getjadwal', $getjadwal)
        ->with('order', $order)
        ->with('select_sched_session_group', $select_sched_session_group)
        ->with('select_sched_type', $select_sched_type)
        ->with('Sched_Session_Group_Id', $Sched_Session_Group_Id)
        ->with('Sched_Type_Id', $Sched_Type_Id)
        ->with('dosen',$dosen)
        ->with('dosen2',$dosen2)
        ->with('select_session_group', $select_session_group)
        ->with('sched_session_group_id', $sched_session_group_id)
        ->with('query', $scheds)
        ->with('Sched_Type_Id', $Sched_Type_Id)
        ->with('select_sched_type', $select_sched_type)
        ->with('select_session_group', $select_session_group)
        ->with('select_sched_session', $select_sched_session)
        ->with('select_room', $select_room)
        ->with('department', $department)
        ->with('class_program', $class_program)
        ->with('term_year', $term_year)
        ->with('offered_course_id', $offered_course_id)
        ->with('search',$search)
        ->with('page', $page)
        ->with('curriculum', $curriculum)
        ->with('cek_jadwal',$cek_jadwal)
        ->with('rowpage', $rowpage);
      }

      public function findgrubsesi(Request $request){

  		//it will get price if its id match with product id
  		$p=DB::table('acd_sched_session_group')
      ->where('Sched_Session_Group_Id',$request->Sched_Session_Group_Id)->first();

        	return response()->json($p);
    	}

      public function findtype(Request $request){

      //it will get price if its id match with product id
      $p=DB::table('mstr_sched_type')
      ->where('Sched_Type_Id',$request->Sched_Type_Id)->first();

          return response()->json($p);
      }

      public function findroom(Request $request){
        $p=DB::table('mstr_room')
        ->where('Room_Id',$request->Room_Id)
        ->first();

          return response()->json($p);
      }

      public function findroomwithsched(Request $request,$sched){
        $p=DB::table('mstr_room')
        ->where('Sched_Session_Group_Id',$sched)
        ->where('Term_Year_Id',$request->term_year)
        ->get();
        return response()->json($p);
      }

      public function findjadwal(Request $request, $id1, $id2, $id3, $id4){

      $notin=DB::table('acd_offered_course_sched')
      ->join('acd_offered_course as b','acd_offered_course_sched.Offered_Course_id','=','b.Offered_Course_id')
      ->select('Sched_Session_Id')
      ->where('b.Term_Year_Id',$request->term_year)
      ->where('Room_Id',$id3);

      
      $dosen = DB::table('acd_offered_course_lecturer as cl')
      ->join('emp_employee as e', 'cl.Employee_Id','=', 'e.Employee_Id')
      ->select('e.First_Title', 'e.Name', 'e.Last_Title','e.Employee_Id')
      ->where('Offered_Course_id',$id4)
      ->get();
      
      $jdwl="";
      if($dosen == null){
        $jadwal = "";
          foreach($dosen as $dsn){
            $jdwl = DB::table('acd_offered_course_sched as a')
            ->leftjoin('acd_offered_course as b', 'a.Offered_Course_id', '=', 'b.Offered_Course_id')
            ->leftjoin('acd_offered_course_lecturer as c', 'a.Offered_Course_id' ,'=' ,'c.Offered_Course_id')
            ->groupBy('a.Offered_Course_Sched_id')
            ->where('c.Employee_Id',$dsn->Employee_Id)
            ->select('a.Sched_Session_Id');
            // ->get();
          }
          
      }else{
        $jadwal = $jdwl;
      }
        if($jadwal ==""){
          $p=DB::table('acd_sched_session')
          ->select('Sched_Session_Id','Description','Order_Id')
          ->where('Sched_Session_Group_Id',$id1)
          ->where('Sched_Type_Id',$id2)
          ->where('Term_Year_Id',$request->term_year)
          ->whereNotIn('acd_sched_session.Sched_Session_Id',$notin)
          ->orderBy('acd_sched_session.Day_Id','asc')
          ->orderBy('acd_sched_session.Time_Start','asc')
          ->get();
        }else{
          $p=DB::table('acd_sched_session')
          ->select('Sched_Session_Id','Description','Order_Id')
          ->where('Sched_Session_Group_Id',$id1)
          ->where('Sched_Type_Id',$id2)
          ->where('Term_Year_Id',$request->term_year)
          ->whereNotIn('acd_sched_session.Sched_Session_Id',$notin)
          ->whereNotIn('acd_sched_session.Sched_Session_Id',$jadwal)
          ->orderBy('acd_sched_session.Day_Id','asc')
          ->orderBy('acd_sched_session.Time_Start','asc')
          ->get();
        }
        return response()->json($p);
      }

      /**
       * Update the specified resource in storage.
       *
       * @param  \Illuminate\Http\Request  $request
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function store(Request $request)
      {
        $this->validate($request,[
          'Offered_Course_id' => 'required',
          // 'Sched_Session_Group_Id' => 'required',
          'Sched_Session_Id' => 'required',
          'Room_Id' => 'required',
        ]);
              $Offered_Course_id = Input::get('Offered_Course_id');
              // $Sched_Session_Group_Id = Input::get('Sched_Session_Group_Id');
              $Sched_Session_Id = Input::get('Sched_Session_Id');
              $Room_Id = Input::get('Room_Id');

              try {
                foreach($Sched_Session_Id as $data){
                  DB::table('acd_offered_course_sched')
                  ->insert(
                    ['Offered_Course_id' => $Offered_Course_id,'Sched_Session_Id' => $data, 'Room_Id' => $Room_Id]);
                }

                return Redirect::back()->withErrors('Berhasil Menambah Jadwal Kuliah');
              } catch (\Exception $e) {
                return Redirect::back()->withErrors('Gagal Menambah Jadwal Kuliah');
              }
      }

      public function store_detail(Request $request)
      {
              $Offered_Course_id = $request->offered_course_id;
              $Sched_Session_Id = $request->Sched_Session_Id;
              $Room_Id = $request->Room_Id;

              $cek_data = DB::table('acd_offered_course_sched')
                ->where([
                  ['Offered_Course_id', $Offered_Course_id],['Sched_Session_Id', $Sched_Session_Id], ['Room_Id', $Room_Id]])->count();
          
              if($cek_data != 0){
                  $insert = DB::table('acd_offered_course_sched')
                            ->where([
                              ['Offered_Course_id', $Offered_Course_id],['Sched_Session_Id', $Sched_Session_Id], ['Room_Id', $Room_Id]])->delete();
              }else{
                  $insert = DB::table('acd_offered_course_sched')
                            ->insert(
                              ['Offered_Course_id' => $Offered_Course_id,'Sched_Session_Id' => $Sched_Session_Id, 'Room_Id' => $Room_Id]);
              }                        

               return json_encode($insert);

      }



      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function destroy(Request $request,$id)
      {
          $q=DB::table('acd_offered_course_sched')->where('Offered_Course_Sched_id', $id)->delete();
          echo json_encode($q);
      }

          
public function exportdata($department,$term_year,$class_program){
          Excel::create('Jadwal Kuliah', function ($excel) use($department, $class_program,$term_year){       
            $items  = DB::table('acd_offered_course')
           ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
           ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','acd_course_curriculum.Curriculum_Id')
           ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
           ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
           ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
           ->where('acd_offered_course.Department_Id', $department)
           ->where('acd_offered_course.Class_Prog_Id', $class_program)
           ->where('acd_offered_course.Term_Year_Id', $term_year)
          //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
           ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id','mstr_curriculum.Curriculum_Name',
            DB::raw('(SELECT Group_Concat(acd_sched_session.Description SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as jadwal'),
            DB::raw('(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as room'))
          ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('acd_offered_course.Class_Id', 'asc')
          ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
          ->get();

          $aoc = DB::table('acd_offered_course')
              ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
              ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
              // ->where('acd_course_curriculum.Study_Level_Id',$semester)
              ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
              ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
              ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
              ->where('acd_offered_course.Department_Id', $department)
              ->where('acd_offered_course.Class_Prog_Id', $class_program)
              ->where('acd_offered_course.Term_Year_Id', $term_year)
              // ->where('acd_offered_course.Curriculum_Id',$curriculum)
              ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.*',
              DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
              DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
              ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
              ->orderBy('acd_course.Course_Name', 'asc')
              ->orderBy('acd_offered_course.Class_Id', 'asc')
              ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
              ->get();

        $jadwal =  DB::table('acd_offered_course')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
            ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
            ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            ->where('acd_offered_course.Department_Id', $department)
            ->where('acd_offered_course.Class_Prog_Id', $class_program)
            ->where('acd_offered_course.Term_Year_Id', $term_year)
            // // ->where('acd_offered_course.Curriculum_Id',$curriculum)
            ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
                DB::raw('(SELECT Group_Concat(acd_sched_session.Description SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as jadwal'),
                DB::raw('(SELECT Group_Concat(acd_sched_session.Day_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as day_id'),
                DB::raw('(SELECT Group_Concat(acd_sched_session.Time_Start SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as time_start'),
                DB::raw('(SELECT Group_Concat(acd_sched_session.Time_End SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as time_end'),
                DB::raw('(SELECT Group_Concat(acd_sched_session.Sched_Session_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as ssi'),
                DB::raw('(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as room'),
                DB::raw('(SELECT Group_Concat(mstr_room.Room_Code SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as room_code'))
            ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
            ->orderBy('acd_course.Course_Name', 'asc')
            ->orderBy('acd_offered_course.Class_Id', 'asc')
            ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
            ->get();

            // dd($jadwal);
            
          $dosen = DB::table('acd_offered_course_lecturer as a')
                ->join('emp_employee as b','a.Employee_Id','b.Employee_Id')
                ->where('a.Offered_Course_id',$aoc[0]->Offered_Course_id)->get();

          $aocs = DB::table('acd_offered_course_sched')
                ->where('Offered_course_id',$aoc[0]->Offered_Course_id)->get();

          $databaru = [];
          $s = 0;
          foreach($aoc as $key){
            $array = [
              'dosen' => $aoc[$s]->id_dosen,
              'jadwal' => $jadwal[$s]->jadwal,
              'ruang' => $jadwal[$s]->room,
              'ruangcd' => $jadwal[$s]->room_code,
              'Course_Code' => $jadwal[$s]->Course_Code,
              'Course_Name' => $jadwal[$s]->Course_Name,
              'day_id' => $jadwal[$s]->day_id,
              'time_start' => $jadwal[$s]->time_start,
              'time_end' => $jadwal[$s]->time_end,
              'Class_Id' => $aoc[$s]->Class_Id,
              'jadwal' => $jadwal[$s]->jadwal,
              'ssi' => $jadwal[$s]->ssi,
              'Applied_Sks' => $aoc[$s]->Applied_Sks,
              'Class_Name' => $aoc[$s]->Class_Name,
              'Study_Level_Id' => $aoc[$s]->Study_Level_Id,
              'Term_Year_Id' => $aoc[$s]->Term_Year_Id,
              'Department_Id' => $aoc[$s]->Department_Id,
              'Class_Prog_Id' => $aoc[$s]->Class_Prog_Id,
              'Course_Id' => $aoc[$s]->Course_Id,
            ];

            array_push($databaru, $array);
            $s++;
          }

          function tanggal_indo($tanggal, $cetak_hari = false)
          {
              $hari = array ( 1 =>    'Senin',
                          'Selasa',
                          'Rabu',
                          'Kamis',
                          'Jumat',
                          'Sabtu',
                          'Minggu'
                      );

              $bulan = array (1 =>   'Januari',
                          'Februari',
                          'Maret',
                          'April',
                          'Mei',
                          'Juni',
                          'Juli',
                          'Agustus',
                          'September',
                          'Oktober',
                          'November',
                          'Desember'
                      );
              $split 	  = explode('-', $tanggal);
              $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

              // if ($cetak_hari) {
              //     $num = date('N', strtotime($tanggal));
              //     return $hari[$num] . ', ' . $tgl_indo;
              // }
              return $tgl_indo;
          }

            if ($items->count() == 0) {
              $data = [
                  [
                      'NO' => '',
                      'Kode Matakuliah' => '',
                      'Nama Matakuliah' => '',
                      'Kelas' => '',
                      'Semester' => '',
                      'Jadwal' => '',
                      'Ruang' => '',
                      'Kurikulum' => '',
                  ]
              ];
          }
          $i = 1;
          foreach ($databaru as $item) {
            $jadwal = explode('|',$item['jadwal']);
            $day = explode('|',$item['day_id']);  
            $ssi = explode('|',$item['ssi']); 
            $room = explode('|',$item['ruang']);
            $id_dosen = explode('|',$item['dosen'] );
            
            $count_student = DB::table('acd_student_krs')
              ->where([
                ['Term_Year_Id',$item['Term_Year_Id']],
                ['Course_Id',$item['Course_Id']],
                ['Class_Prog_Id',$item['Class_Prog_Id']],
                ['Class_Id',$item['Class_Id']],
                ['Is_Approved',1]
              ])
              ->count();

            if ($item['jadwal'] == ""){
              $dosen = [];
              $nd = 0;
              foreach ($id_dosen as $key) {
                if ($key != null) {
                  $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                    ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                    ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                    ->first();
                      $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                      $firstitle = $dosennya->First_Title;
                      $name = $dosennya->Name;
                      $lasttitle = $dosennya->Last_Title;
                      $dosen[$nd] = $firstitle." ".$name." ".$lasttitle;
                  }
                  $nd++;
                }

                $ndosen = "";
                for ($ndi=0; $ndi < sizeof($dosen); $ndi++) { 
                  $ndosen = $ndosen."".$dosen[$ndi] .", ";
                }

              $data[] = [
                  'NO' => $i,
                  'Kode Matakuliah' => $item['Course_Code'],
                  'Nama Matakuliah' => $item['Course_Name'],
                  'Kelas' => $item['Class_Name'],
                  'Semester' => $item['Study_Level_Id'],
                  'Peserta' => $count_student,
                  'Hari' => '',
                  'Jam' => '',
                  'Ruang' => '',
                  'Dosen' => $ndosen,
              ];
            }else{
              $n = 0;
              $start = "";
              $end = "";
              $days="";
              $ruangan = "";
              $dosen = [];
              $nd = 0;
              foreach ($jadwal as $key) {
                    $name_day = DB::table('mstr_day')->where('Day_Id',$day[$n])->first();
                    $sesi = DB::table('acd_sched_session')->where('Sched_Session_Id',$ssi[$n])->first();
                    if($days ==  $sesi->Day_Id){
                      $end = $sesi->Time_End;
                    }else{
                      $days = $sesi->Day_Id;
                      $start = $sesi->Time_Start;
                      $end = $sesi->Time_End;
                    }

                    if($ruangan == $room[$n]){
                    }else{
                      $ruangan = $room[$n];
                    }

                    $hari = $name_day->Day_Name;
                    $jam = $start."-".$end;
                    $n++;
                  }

              foreach ($id_dosen as $key) {
                if ($key != null) {
                  $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                    ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                    ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                    ->first();
                      $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                      $firstitle = $dosennya->First_Title;
                      $name = $dosennya->Name;
                      $lasttitle = $dosennya->Last_Title;
                      $dosen[$nd] = $firstitle." ".$name." ".$lasttitle;
                  }
                  $nd++;
                }

                $ndosen = "";
                for ($ndi=0; $ndi < sizeof($dosen); $ndi++) { 
                  $ndosen = $ndosen."".$dosen[$ndi] .", ";
                }
                // $count_student = DB::table('acd_student_krs')->where([['']])->count('Krs_Id')
              $data[] = [
                            'NO' => $i,
                            'Kode Matakuliah' => $item['Course_Code'],
                            'Nama Matakuliah' => $item['Course_Name'],
                            'Kelas' => $item['Class_Name'],
                            'Semester' => $item['Study_Level_Id'],
                            'Peserta' => $count_student,
                            'Hari' => $hari,
                            'Jam' => $jam,
                            'Ruang' => $ruangan,
                            'Dosen' => $ndosen,
                        ];
            }
              $i++;
          }

          $excel->sheet('Jadwal Kuliah', function ($sheet) use ($data,$items) {
              $sheet->fromArray($data, null, 'A1');

              $num_rows = sizeof($data) + 1;

              for ($i = 1; $i <= $num_rows; $i++) { 
                  $rows[$i] = 18;
              }

              $rows[1] = 30;

              $sheet->setAutoSize(true);

              $sheet->setStyle([
                  'font' => [
                      'name' => 'Arial',
                      'size' => 10
                  ]
              ]);

              $sheet->setAllBorders('none');

              $sheet->setHeight($rows);

              $sheet->setWidth([
                  'A' => 6,
                  'B' => 20,
                  'C' => 40,
                  'D' => 6,
                  'E' => 10,
                  'F' => 10,
              ]);
              
              $sheet->setHorizontalCentered(true);

              for ($i = 1; $i <= $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $row->setValignment('center');
                  });
              }

              for ($i = 1; $i > $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $cells->setAlignment('center');
                  });
              }
              
              $sheet->setBorder('A1:J' . (sizeof($data) + 1), 'thin');

              $sheet->setHorizontalCentered(true);

              $sheet->cells('A1:J1', function ($cells) {
                  $cells->setBackground('#97D86E');
                  $cells->setFontWeight('bold');
                  $cells->setAlignment('center');
              });
              // $sheet->cells('Q1', function ($cells) {
              //     $cells->setBackground('#F0FF00');
              //     $cells->setFontWeight('bold');
              //     $cells->setAlignment('center');
              // });
              // $sheet->cells('R1:T1', function ($cells) {
              //     $cells->setBackground('#FF3939');
              //     $cells->setFontWeight('bold');
              //     $cells->setAlignment('center');
              // });
              // foreach ($data as $dt) {
              //       $no = ($dt['NO'] + 1);
              //       if ($dt['SKS'] == null || $dt['Semester'] == null || $dt['SKS Transkrip'] == null) {
              //           $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
              //               $cells->setBackground('#ff0000');
              //               $cells->setFontColor('#ffffff');
              //               $cells->setAlignment('center');
              //           });
              //       }else{
              //         $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
              //               $cells->setAlignment('center');
              //       });
              //     }
              //   }

              foreach ($data as $dt) {
                  $sheet->cells('D' . $i . ':F' . sizeof($data), function ($cells) {
                      $cells->setAlignment('center');
                  });
                }

              // $last = $i+1;
              // $sheet->cells('E2:E9999', function ($cells) {
              //             $cells->setAlignment('center');
              //     });
              // $sheet->setCellValue('B'.$last, 'STIKES MUHAMMADIYAH PALEMBANG');
          });
      })->export('xls');
    }

    public function exportdatacsv(Request $request){
    // $course = DB::table('acd_course')->where([['Course_Id',$request->Course_Id],['Department_Id',$request->department]])->first();
    $prodi = DB::table('mstr_department')->where('Department_Id',$request->Department_Id)->first();
    Excel::create($request->Term_Year_Id.'-'.$prodi->Department_Name, function ($excel) use($request){      
      $items = DB::table('acd_offered_course as a')
        ->join('acd_course as b' ,function ($join)
        {
          $join
          ->on('b.Course_Id','=','a.Course_Id')
          ->on('b.Department_Id','=','a.Department_Id');
        })
        ->where([
          ['a.Term_Year_Id',$request->Term_Year_Id],
          ['a.Department_Id',$request->Department_Id],
          ['a.Class_Prog_Id',$request->Class_Prog_Id]
        ])
        ->select('a.Offered_Course_id','b.Course_Id','b.Course_Code','b.Course_Name')
        ->orderby('a.Course_Id')
        ->get(); 

      if ($items->count() == 0) {
        $data = [
          [
            'shortname' => '',
            'fullname' => '',
            'category' => '',
            'summary' => '',
            'format' => '',
            'enrolment_1' => '',
            'enrolment_1_role' => ''
          ]
        ];
      }

      $i = 1;
      foreach ($items as $item) {
        $data[] = [
          'shortname' => $item->Course_Code,
          'fullname' => $item->Course_Name,
          'category' => '',
          'summary' => $item->Course_Name,
          'format' => '',
          'enrolment_1' => '',
          'enrolment_1_role' => ''
        ];
        $i++;
      }

      $excel->sheet('Data Course', function ($sheet) use ($data,$items) {
          $sheet->fromArray($data, null, 'A1');
      });
    })->export('csv');
  }
  }