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
use PDF;
use Auth;
use DateTime;
use Excel;
use App\GetDepartment;

class Khs_matakuliahController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index']]);
    $this->middleware('access:CanViewDetail', ['only' => ['show']]);
    $this->middleware('access:CanEditDetail', ['only' => ['edit','update']]);

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
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }
       $FacultyId=Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

      $select_department = GetDepartment::getDepartment();

      $select_class_program = DB::table('mstr_department_class_program')
      ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_department_class_program.Department_Id')

      ->where('mstr_department_class_program.Department_Id', $department)
      ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
      ->get();

      $data = DB::table('acd_offered_course')
        ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')

        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
        ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
        ->leftjoin('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
        ->leftjoin('emp_employee', 'emp_employee.Employee_Id', '=' , 'acd_offered_course_lecturer.Employee_Id')
        ->leftjoin('acd_student_krs' ,function ($join)
        {
          $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
          ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
        })
        ->leftjoin('acd_student' , function ($join)
        {
          $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
        })
        ->where('acd_offered_course.Department_Id', $department)
        ->where('acd_offered_course.Class_Prog_Id', $class_program)
        ->where('acd_offered_course.Term_Year_Id', $term_year)
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(Course_Name) like '%" . strtolower($search) . "%'");
          $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
        })
        ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 
        DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'),
        DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
        DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
        ->orderBy('acd_course.Course_Name', 'asc')
        ->orderBy('mstr_class.class_Name', 'asc')
        ->paginate($rowpage); 
        
       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
       ->get();



       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
       return view('khs_matakuliah/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year);
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
      if($request->count == 0){
        Alert::warning('Peserta 0');
        return redirect()->back();
      }
      $from = $request->from;
      $search = Input::get('search');
      $rowpage = Input::get('rowpage');
      $department = Input::get('department');
      $term_year = Input::get('term_year');
      $class_program = Input::get('class_program');
      
      $current_search = $request->current_search;
      $current_page = $request->currentpage;
      $current_rowpage = Input::get('current_rowpage');
      $course_type=$request->course_type;
      
      $page = $request->page;
      if ($rowpage == null) {
        $rowpage = 10;
      }
      //jika manual penilaiannya
      if($request->course_type == 12){
          $data = DB::table('acd_offered_course')
          ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->leftjoin('acd_student_krs' ,function ($join)
          {
            $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
            ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
          })
          ->leftjoin('acd_student' , function ($join)
          {
            $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
            ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
          })
          ->where('acd_offered_course.Offered_Course_id', $id)
          ->where('acd_student_krs.Is_Approved', 1)
          ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name' , 'mstr_term_year.Term_Year_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))          
          ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')          
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('mstr_class.class_Name', 'asc')->first();

          $countdatas = DB::table('acd_student_krs')
          ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
          ->where('acd_student_krs.Term_Year_Id',$term_year)
          ->where('acd_student_krs.Class_Prog_Id',$class_program)
          ->where('acd_student_krs.Course_Id',$data->Course_Id)
          ->where('acd_student_krs.Class_Id',$data->Class_Id)
          ->where('acd_student_krs.Is_Approved', 1)
          ->groupBy('acd_student_krs.Student_Id')          
          ->get();

          $countdata = count($countdatas);
       
          $krs = DB::table('acd_student_krs')
          ->leftjoin('acd_offered_course' ,function ($join)
          {
            $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
            ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
          })
          ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->leftjoin('acd_student_khs', 'acd_student_khs.Krs_Id' , '=' , 'acd_student_krs.Krs_Id')
          ->leftjoin('acd_grade_letter' ,'acd_grade_letter.Grade_Letter_Id', '=', 'acd_student_khs.Grade_Letter_Id')

          ->join('acd_course' ,'acd_course.Course_Id', '=', 'acd_student_krs.Course_Id')->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student_krs.Class_Id')
          ->leftjoin('acd_student_khs_nilai_component','acd_student_krs.Krs_Id','=','acd_student_khs_nilai_component.Krs_Id')
          ->where('acd_offered_course.Offered_Course_id', $id)
          ->select('acd_student_krs.Krs_Id as Krs','acd_student.*','acd_student_khs.*','acd_student_khs_nilai_component.*','acd_grade_letter.Grade_Letter', 'acd_offered_course.Department_Id',
            DB::raw('(SELECT Weight_Value FROM acd_grade_department WHERE acd_grade_department.Department_Id = acd_offered_course.Department_Id AND acd_grade_department.Grade_Letter_Id = acd_student_khs.Grade_Letter_Id GROUP BY acd_grade_department.Grade_Letter_Id) as weightvalue' ),
            DB::raw('(SELECT Is_For_Transcript FROM acd_course_curriculum WHERE Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
            AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_offered_course.Term_Year_Id AND Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Is_For_Transcript' ),
            DB::raw('(SELECT Transcript_Sks FROM acd_course_curriculum WHERE Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
            AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_offered_course.Term_Year_Id AND Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Transcript_Sks' )
            )
          ->orderBy('acd_student.Nim')
          ->get();
          // dd($krs);

          $fortranscript = DB::table('acd_student_krs')
          ->leftjoin('acd_offered_course' ,function ($join)
          {
            $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
            ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
          })
          ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->leftjoin('acd_student_khs', 'acd_student_khs.Krs_Id' , '=' , 'acd_student_krs.Krs_Id')
          ->leftjoin('acd_grade_letter' ,'acd_grade_letter.Grade_Letter_Id', '=', 'acd_student_khs.Grade_Letter_Id')

          ->join('acd_course' ,'acd_course.Course_Id', '=', 'acd_student_krs.Course_Id')->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student_krs.Class_Id')
          ->leftjoin('acd_student_khs_nilai_component','acd_student_krs.Krs_Id','=','acd_student_khs_nilai_component.Krs_Id')
          ->where('acd_offered_course.Offered_Course_id', $id)
          ->select('acd_student_krs.Krs_Id as Krs','acd_student.*','acd_student_khs.*','acd_student_khs_nilai_component.*','acd_grade_letter.Grade_Letter', 'acd_offered_course.Department_Id',
            DB::raw('(SELECT Weight_Value FROM acd_grade_department WHERE acd_grade_department.Department_Id = acd_offered_course.Department_Id AND acd_grade_department.Grade_Letter_Id = acd_student_khs.Grade_Letter_Id GROUP BY acd_grade_department.Grade_Letter_Id) as weightvalue' ),
            DB::raw('(SELECT Is_For_Transcript FROM acd_course_curriculum WHERE Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
            AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_offered_course.Term_Year_Id AND Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Is_For_Transcript' ),
            DB::raw('(SELECT Transcript_Sks FROM acd_course_curriculum WHERE Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
            AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_offered_course.Term_Year_Id AND Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Transcript_Sks' )
            )
          ->orderBy('acd_student.Nim')
          ->first();

          $cekfortranscript = DB::table('acd_course_curriculum')
          ->join('acd_offered_course', 'acd_offered_course.Course_Id', '=', 'acd_course_curriculum.Course_Id')
          ->where('acd_offered_course.Offered_Course_id', $id)
          ->where('acd_offered_course.Department_Id', $department)
          ->where('acd_offered_course.Class_Prog_Id', $class_program)
          ->where('acd_offered_course.Term_Year_Id', $term_year)
          ->first();

          
          $mahasiswa = $krs->count();
          
          $jadwalujian = DB::table('acd_offered_course_exam')
          ->where('Offered_Course_Id',$data->Offered_Course_id)
          ->where('Exam_type_Id',2)
          ->first();
          $jadwalujianuas = DB::table('acd_offered_course_exam')
          ->where('Offered_Course_Id',$data->Offered_Course_id)
          ->where('Exam_type_Id',1)
          ->first();
          if($jadwalujian){
            $event_sched_uts = DB::table('mstr_event_sched')->where([['Term_Year_Id',$term_year],['Department_Id',$department],['Event_Id',6]])->first();
            if($event_sched_uts){
              $Exam_Start_Date = $jadwalujian->Exam_Start_Date;
              $last_date = date('Y-m-d H:i:s', strtotime($Exam_Start_Date. ' + '.($event_sched_uts->Day - 1).' days'));
            }else{
              $Exam_Start_Date = $jadwalujian->Exam_Start_Date;
              $last_date = date('Y-m-d H:i:s', strtotime($Exam_Start_Date. ' + 8 days'));
            }
          }else{
            $last_date = '';
          }
          
          $jadwalujianuas = DB::table('acd_offered_course_exam')
          ->where('Offered_Course_Id',$data->Offered_Course_id)
          ->where('Exam_type_Id',1)
          ->first();
          if($jadwalujianuas){
            $event_sched_uas = DB::table('mstr_event_sched')->where([['Term_Year_Id',$term_year],['Department_Id',$department],['Event_Id',7]])->first();
            if($event_sched_uas){
              $Exam_Start_Date = $jadwalujianuas->Exam_Start_Date;
              $last_dateuas = date('Y-m-d H:i:s', strtotime($Exam_Start_Date. ' + '.($event_sched_uas->Day - 1).' days'));
            }else{
              $Exam_Start_Date = $jadwalujianuas->Exam_Start_Date;
              $last_dateuas = date('Y-m-d H:i:s', strtotime($Exam_Start_Date. ' + 8 days'));
            }
          }else{
            $last_dateuas = '';
          }        

          $message = '';
          $adauts = '';
          $adauas = '';
          $q = 1;
          $messagefinal = '';
          $qq = 1;
          $now = date('Y-m-d');

          //uts
          if($jadwalujian == null){
            $jadwalpengisian = 
            [
              'Start_Date' => '',
              'End_Date' => '',
              'Interval' => '',
              'Now' => '',
              'Start_Dateuas' => '',
              'End_Dateuas' => '',
              'Intervaluas' => '',
            ];
          }else{
            $event_sched_uts = DB::table('mstr_event_sched')->where([['Term_Year_Id',$term_year],['Department_Id',$department],['Event_Id',6]])->first();

            $Exam_Start_Date = $jadwalujian->Exam_Start_Date;
            $start = explode(" ",$Exam_Start_Date);
            $str_date = $start[0];
            $s_date = $start[0];
            if($event_sched_uts){
              $l_date = date('Y-m-d', strtotime($s_date. ' + '.($event_sched_uts->Day - 1).'days'));
            }else{
              $l_date = date('Y-m-d', strtotime($s_date. ' + 6 days'));
            }
            $cuti = DB::table('emp_holiday')->get();
            while ($s_date <= $l_date){
              $hari = date('l', strtotime($s_date));
              // foreach($cuti as $d_cuti){
              //   $holiday_date = date('Y-m-d',strtotime($d_cuti->Holiday_Date));
              //   if($s_date <= $holiday_date && $l_date >= $holiday_date){
              //   }
              // }
              if($hari == 'Saturday' || $hari == 'Sunday'){
                $l_date = date('Y-m-d', strtotime($l_date. ' + 1 days'));
              }
              
              $s_date = date('Y-m-d', strtotime($s_date. ' + 1 days'));
            }
            // dd($s_date);
            $start_date = new DateTime($str_date);
            $last_date = new DateTime($s_date);
            $now_date = new DateTime($now);
            $diff=date_diff($now_date,$last_date); 
            $interval = $diff->d;

            $jadwalpengisian = 
            [
              'Start_Date' => $str_date,
              'End_Date' => $s_date,
              'Interval' => $interval,
              'Now' => $now,
              'Start_Dateuas' => '',
              'End_Dateuas' => '',
              'Intervaluas' => '',
            ];
          }

          //uas
          if($jadwalujianuas == null){
            $s_dateuas = '';
            [
              'Start_Date' => '',
              'End_Date' => '',
              'Interval' => '',
              'Now' => '',
              'Start_Dateuas' => '',
              'End_Dateuas' => '',
              'Intervaluas' => '',
            ];
          }else{
            $event_sched_uas = DB::table('mstr_event_sched')->where([['Term_Year_Id',$term_year],['Department_Id',$department],['Event_Id',7]])->first();
            $Exam_Start_Date_uas = $jadwalujianuas->Exam_Start_Date;
            $startuas = explode(" ",$Exam_Start_Date_uas);
            $str_dateuas = $startuas[0];
            $s_dateuas = $startuas[0];
            if($event_sched_uas){
              $l_dateuas = date('Y-m-d', strtotime($s_dateuas. ' + '.($event_sched_uas->Day - 1).' days'));
            }else{
              $l_dateuas = date('Y-m-d', strtotime($s_dateuas. ' + 6 days'));
            }
            $cuti = DB::table('emp_holiday')->get();
            while ($s_dateuas <= $l_dateuas){
              $hariuas = date('l', strtotime($s_dateuas));
              if($hariuas == 'Saturday' || $hariuas == 'Sunday'){
                $l_dateuas = date('Y-m-d', strtotime($l_dateuas. ' + 1 days'));
              }
              $s_dateuas = date('Y-m-d', strtotime($s_dateuas. ' + 1 days'));
            }
            $start_dateuas = new DateTime($str_dateuas);
            $last_dateuas = new DateTime($s_dateuas);
            $diffuas=date_diff($now_date,$last_dateuas); 
            $intervaluas = $diffuas->d;

            $jadwalpengisian = 
            [
              'Start_Date' => $str_date,
              'End_Date' => $s_date,
              'Interval' => $interval,
              'Now' => $now,
              'Start_Dateuas' => $str_dateuas,
              'End_Dateuas' => $s_dateuas,
              'Intervaluas' => $intervaluas,
            ];
          }

          // dd($krs);
          $l = 0;
          $countuas = 0;
          foreach ($krs as $key) {
            $component = DB::table('acd_student_khs_nilai_component')->where([['Krs_Id',$key->Krs_Id]])->first();
            // dd($component);
            if($component){
              if($component->Uas != null){
                $countuas++;
              }
            }
            $l++;
          }
          // dd([[$start_date],[$last_date],[$now_date],[$interval]]);
          if($s_dateuas != ''){
            $bisadefault = strtotime($s_dateuas) < strtotime($now);    
            // dd($bisadefault);      
            if($bisadefault){
              $default = 1;
            }else{
              $default = 0;
            }
          }else{
            $default = 0;
          }
          

          // $krs->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program, 'department'=> $department, 'currentpage' => $current_page, 'currentsearch' => $current_search, 'currentrowpage' => $current_rowpage]);

          return view('khs_matakuliah/show')
          ->with('course_type',$course_type)
          ->with('default',$default)
          ->with('countdata',$countdata)
          ->with('countuas',$countuas)
          ->with('enddateuas',$s_dateuas)
          ->with('now',$now)
          ->with('from',$from)
          ->with('adauas',$adauas)
          ->with('adauts',$adauts)
          ->with('last_dateuas',$last_dateuas)
          ->with('last_date',$last_date)
          ->with('jadwalujian',$jadwalujian)
          ->with('cekfortranscript',$cekfortranscript)
          ->with('fortranscript',$fortranscript)
          ->with('krs',$krs)
          ->with('messagefinal',$messagefinal)
          ->with('message',$message)
          ->with('mahasiswa',$mahasiswa)
          ->with('data', $data)
          ->with('Offered_Course_id', $id)
          ->with('search',$search)
          ->with('rowpage',$rowpage)
          ->with('class_program', $class_program)
          ->with('department', $department)
          ->with('term_year', $term_year)
          ->with('page', $page)
          ->with('currentsearch', $current_search)
          ->with('currentpage', $current_page)
          ->with('currentrowpage', $current_rowpage)
          ->with('jadwalpengisian', $jadwalpengisian);
      }else{
        $aoc = DB::table('acd_offered_course as a')
                ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','a.Class_Prog_Id')
                ->leftjoin('mstr_department','mstr_department.Department_Id','=','a.Department_Id')
                ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','a.Term_Year_Id')
                ->leftjoin('acd_course','acd_course.Course_Id','=','a.Course_Id')
                ->join('mstr_class','mstr_class.Class_Id','=','a.Class_Id')
                ->where('a.Offered_course_Id',$id)->first();

        $det = DB::table('acd_student_krs')
          ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_student_krs.Class_Id')
          ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student_krs.Class_Prog_Id')
          ->join('acd_course','acd_course.Course_Id','=','acd_student_krs.Course_Id')
          ->join('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
          ->select('mstr_class.Class_Name',
            'mstr_class_program.Class_Program_Name',
            'acd_course.Course_Code',
              'mstr_department.Department_Name','acd_student.*','acd_student_krs.Krs_Id')
          ->where('acd_student_krs.Class_Id', $aoc->Class_Id)
          ->where('acd_student_krs.Class_Prog_Id', $class_program)
          ->where('acd_student_krs.Course_Id', $aoc->Course_Id)
          ->where('acd_student_krs.Term_Year_Id', $term_year)
          ->where('mstr_department.Department_Id', $department)
          ->where('acd_student_krs.Is_Approved', 1)
          ->orderBy('acd_student.Nim','asc')
          ->get();
        
          $data = [];
          $i = 0;
          foreach ($det as $row){
            $bobot=DB::table('acd_student_khs_item_bobot')
              ->where([
                ['Department_Id',$department],
                ['Entry_Year_Id',$row->Entry_Year_Id],
                // ['Entry_Year_Id',$det[0]->Entry_Year_Id],
                ['Course_Type_Id',$request->course_type]
              ])
              ->orderby('Order_Id','asc')
              ->get();
              // dd($bobot,$department,$row->Entry_Year_Id,$request->course_type);
              
              // if(count($bobot) <= 0){
              //   Alert::warning('Bobot Belum Diseting--');
              //   return redirect()->back();
              // }
              // $total =0;
              $data[$i]['Nim'] = $row->Nim;
              $data[$i]['Full_Name'] = $row->Full_Name;
              $data[$i]['Krs_Id'] = $row->Krs_Id;
              $data[$i]['isi'] = [];
              $ii = 0;
              $total=0;
              foreach ($bobot  as $col){
                  $cek_khs=DB::table('acd_student_khs_nilai_detail')->where([['Krs_Id', $row->Krs_Id],['Student_Khs_Item_Bobot_Id',$col->Student_Khs_Item_Bobot_Id]])->first();
                  // dd($cek_khs,$col);
                  $data[$i]['isi'][$ii]['Bobot'] = $col->Bobot;
                  $data[$i]['isi'][$ii]['Value'] =  ($cek_khs != null? $cek_khs->Value:null);
                  $data[$i]['isi'][$ii]['Bobot_id'] = $col->Student_Khs_Item_Bobot_Id;
                  $data[$i]['isi'][$ii]['status'] = ($cek_khs != null? $cek_khs->Student_khs_nilai_detail_id:'0');
                  $data[$i]['isi'][$ii]['Score'] = ($cek_khs != null? $cek_khs->Score:'-');
                  $ii++;
                $total += ($cek_khs != null? $cek_khs->Score:0);
              }
              // dd($data);

              $data[$i]['Total']=$total;
              $letter = "BL";
              if($total != null || $total == 0){
                  $grade_letter = DB::table('acd_grade_department')
                  ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                  // ->select('acd_grade_letter.Grade_Letter')
                  ->where([
                      ['acd_grade_department.Department_Id',$department],
                      ['acd_grade_department.Entry_Year_Id',$row->Entry_Year_Id],
                      ['acd_grade_department.Scale_Numeric_Max','>',($total-0.01)],
                      ['acd_grade_department.Scale_Numeric_Min','<',($total+0.01)]
                    ])
                  ->first();
                  // dd($grade_letter,$total);
                  if($grade_letter != null){
                      $letter = $grade_letter->Grade_Letter;
                  }else{
                      $letter = "Belum Disetting";
                  }


              }
              $data[$i]['Grade']=$letter;
              $i++;
          }
          // dd($bobot); 
          return view('khs_matakuliah/showprak')
          ->with('oci',$id)
          ->with('aoc',$aoc)
          ->with('data', $data)
          ->with('from', $from)
          ->with('bobot',$bobot)
          ->with('D_id', $department)
          ->with('term_year', $term_year)
          ->with('class_program', $class_program )
          ->with('department', $department )
          ->with('page', $page)->with('currentsearch', $current_search)
          ->with('currentpage', $current_page)
          ->with('currentrowpage', $current_rowpage)
          ;
        }
        //disini
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
      $rowpage = Input::get('rowpage');
      $department = Input::get('department');
      $term_year = Input::get('term_year');
      $class_program = Input::get('class_program');
      $Offered_Course_id = Input::get('Offered_Course_id');


      $page = Input::get('page');
      if ($rowpage == null) {
        $rowpage = 10;
      }

      $current_search = Input::get('current_search');
      $current_page = Input::get('current_page');
      $current_rowpage = Input::get('current_rowpage');


      $krs = DB::table('acd_student_krs')
      ->leftjoin('acd_offered_course' ,function ($join)
      {
        $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
        ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
      })
      ->join('acd_student',  'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
      ->leftjoin('acd_student_khs', 'acd_student_khs.Krs_Id' , '=' , 'acd_student_krs.Krs_Id')
      ->leftjoin('acd_grade_letter' ,'acd_grade_letter.Grade_Letter_Id', '=', 'acd_student_khs.Grade_Letter_Id')
      ->join('acd_course' ,'acd_course.Course_Id', '=', 'acd_student_krs.Course_Id')
      ->join('acd_course_curriculum' , 'acd_course_curriculum.Course_Id' , '=', 'acd_student_krs.Course_Id')
      ->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student_krs.Class_Id')
      ->where('acd_student_krs.Krs_Id', $id)
      ->select('acd_student_krs.Krs_Id as Krs', 'acd_offered_course.Department_Id', 'acd_course_curriculum.Transcript_Sks' , 'acd_course_curriculum.Is_For_Transcript as is_transcript', 'acd_course.Course_Name','acd_course.Course_Id', 'mstr_class.Class_Name', 'acd_student.Student_Id as student', 'acd_student.*','acd_student_khs.*','acd_grade_letter.Grade_Letter_Id','acd_grade_letter.Grade_Letter')
      ->first();

      $grade_letter = DB::table('acd_grade_letter')->join('acd_grade_department', 'acd_grade_department.Grade_Letter_Id' , '=', 'acd_grade_letter.Grade_Letter_Id')->where('Department_Id', $krs->Department_Id)->get();


      return view('khs_matakuliah/edit')->with('id', $id)->with('query',$krs)->with('Offered_Course_id', $Offered_Course_id)->with('grade_letter', $grade_letter)->with('search',$search)->with('rowpage',$rowpage)->with('class_program', $class_program)->with('department', $department)->with('term_year', $term_year)->with('page', $page)->with('currentsearch', $current_search)->with('currentpage', $current_page)->with('currentrowpage', $current_rowpage);
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
        // 'Grade_Letter_Id'=>'required',
      ]);

      $search = Input::get('search');
      $rowpage = Input::get('rowpage');
      $Term_Year_Id = input::get('Term_Year_Id');
      $Department_Id = input::get('Department_Id');
      $Khs_Id = Input::get('Khs_Id');
      $Grade_Letter_Id = Input::get('Grade_Letter_Id');
      $Student_Id = Input::get('Student_Id');
      $Course_Id = Input::get('Course_Id');
      $Offered_Course_id = Input::get('idnya');
      $Class_Prog_Id = Input::get('Class_Prog_Id');
      $transcript_id=DB::table('acd_student_khs')->join('acd_transcript','acd_transcript.Student_Id','=','acd_student_khs.Student_Id')
      ->where('acd_student_khs.Krs_Id',$id)->select('acd_transcript.Transcript_Id')->first();

      $Sks = Input::get('Sks');
      $acd_grade_department = DB::table('acd_grade_department')->where('Grade_Letter_Id', $Grade_Letter_Id)->where('Department_Id', $Department_Id)
      ->select('Weight_Value',DB::raw('count(Weight_Value) as Weight_value'))->first();

       $weight_value = 0;
       if ($acd_grade_department->Weight_value > 0) {
        $weight_value = $acd_grade_department->Weight_Value;
       }

      $Bnk_Value = $weight_value * $Sks;
      $Is_For_Transkrip = Input::get('Is_For_Transkrip');

      if ($Khs_Id == "") {
        if ($Grade_Letter_Id == "") {
          try {
            return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
          } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
          }
        }else {
          if($Is_For_Transkrip==0){
            try {
              DB::table('acd_student_khs')
              ->insert(
                ['Krs_Id' => $id, 'Student_Id' => $Student_Id,'Grade_Letter_Id' => $Grade_Letter_Id,'Sks' => $Sks, 'Weight_Value' => $weight_value, 'Is_For_Transkrip' => $Is_For_Transkrip, 'Bnk_Value' => $Bnk_Value ]);
                return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
            } catch (\Exception $e) {
              return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
            }

          }else {
            try {
              DB::table('acd_student_khs')
              ->insert(
                ['Krs_Id' => $id, 'Student_Id' => $Student_Id,'Grade_Letter_Id' => $Grade_Letter_Id,'Sks' => $Sks, 'Weight_Value' => $weight_value, 'Is_For_Transkrip' => $Is_For_Transkrip, 'Bnk_Value' => $Bnk_Value ]);

                $khs_Id = DB::getPdo()->lastInsertId();
                $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_Id,''));
                // DB::table('acd_transcript')
                // ->insert(
                //   ['Weight_Value' => $weight_value,'is_Use' => 1,'Student_Id' => $Student_Id,'Sks' => $Sks,'Course_Id' => $Course_Id,'Grade_Letter_Id' =>$Grade_Letter_Id, 'Bnk_Value' => $Bnk_Value]);

                  return Redirect::back()->to()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
            } catch (\Exception $e) {
              return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
            }

          }

        }

     }else{
       if ($Grade_Letter_Id == "") {
         try {
           // DB::table('acd_transcript')->where('acd_transcript.Transcript_Id',$transcript_id->Transcript_Id)->delete();
           DB::table('acd_student_khs')->where('Khs_Id', $Khs_Id)->delete();
           // $transcript =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($Khs_Id,''));
           return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
         } catch (\Exception $e) {
           return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
         }


       }else {
         if($Is_For_Transkrip==0){
           try {
             DB::table('acd_student_khs')
             ->where('acd_student_khs.Khs_Id' , $Khs_Id)
             ->update(
               ['Student_Id' => $Student_Id,'Grade_Letter_Id' => $Grade_Letter_Id,'Sks' => $Sks, 'Weight_Value' => $weight_value, 'Is_For_Transkrip' => $Is_For_Transkrip, 'Bnk_Value' => $Bnk_Value ]);
               return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
           } catch (\Exception $e) {
             return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
           }

         }else {
           // try {
             DB::table('acd_student_khs')
             ->where('acd_student_khs.Khs_Id' , $Khs_Id)
             ->update(
               ['Student_Id' => $Student_Id,'Grade_Letter_Id' => $Grade_Letter_Id,'Sks' => $Sks, 'Weight_Value' => $weight_value, 'Is_For_Transkrip' => $Is_For_Transkrip, 'Bnk_Value' => $Bnk_Value ]);

            $transcript =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($Khs_Id,''));
               // DB::table('acd_transcript')
               // ->where('Student_Id',$Student_Id)->where('Course_Id', $Course_Id)
               // ->update(
               //   ['Weight_Value' => $weight_value,'is_Use' => 1,'Student_Id' => $Student_Id,'Sks' => $Sks,'Course_Id' => $Course_Id,'Grade_Letter_Id' =>$Grade_Letter_Id, 'Bnk_Value' => $Bnk_Value]);

               return redirect()->to('proses/khs_matakuliah/'.$Offered_Course_id.'?term_year='.$Term_Year_Id.'&class_program='.$Class_Prog_Id.'&department='.$Department_Id.'&current_page='.$rowpage.'&current_rowpage='.$rowpage.'&current_search='.$search);
              //   return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
           // } catch (\Exception $e) {
           //   return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
           // }

         }

       }
     }

    }

    public function getSetting(Request $request,$offer){
      $term_year = $request->term_year;
      $department = $request->department;
      $class_program = $request->class_program;
      $course_type = $request->course_type;
      $l= Auth::user()->email;
      $data=[];
      $emp = DB::table('emp_employee')->where('emp_employee.Email_Corporate',$l)->first();
      $bobot= DB::table('acd_student_khs_bobot')
      ->where('acd_student_khs_bobot.Offered_Course_id',$offer)->first();
      // dd($emp);
      if ($bobot==null) {
              $data['Student_khs_bobot_id']="";
              $data['Offered_Course_id']=$offer;
              $data['Presensi']="";
              $data['Tugas_2']="";
              $data['Tugas_1']="";
              $data['Tugas_3']="";
              $data['Tugas_4']="";
              $data['Tugas_5']="";
              $data['UTS']="";
              $data['UAS']="";
      }elseif ($bobot !=null) {
          $data['Student_khs_bobot_id']=$bobot->Student_khs_bobot_id;
          $data['Offered_Course_id']=$bobot->Offered_Course_id;
          $data['Presensi']=$bobot->Presensi;
          $data['Tugas_1']=$bobot->Tugas_1;
          $data['Tugas_2']=$bobot->Tugas_2;
          $data['Tugas_3']=$bobot->Tugas_3;
          $data['Tugas_4']=$bobot->Tugas_4;
          $data['Tugas_5']=$bobot->Tugas_5;
          $data['UTS']=$bobot->UTS;
          $data['UAS']=$bobot->UAS;

      }
      return view('khs_matakuliah/settingbobot')
      ->with('course_type', $course_type)
      ->with('data', $data)
      ->with('term_year', $term_year)
      ->with('department', $department)
      ->with('offer', $offer)
      ->with('class_program', $class_program);
    }


  public function storeSetting(Request $request,$offer)
  {
    $term_year = Input::get('term_year');
    $class_program = Input::get('class_program');
    $department = Input::get('department');
      $l= Auth::user()->email;

      $bobot= DB::table('acd_student_khs_bobot')->where('acd_student_khs_bobot.Student_khs_bobot_id',$request->id)->first();
      $su =$request->Presensi + $request->Tugas_1 + $request->Tugas_2 + $request->Tugas_3 + $request->Tugas_4 + $request->Tugas_5 + $request->UTS + $request->UAS;
      // dd($su);
      if ($su==100) {
          if ($bobot == null) {
              $data=[
                  'Presensi'=>$request->Presensi,
                  'Tugas_1'=>$request->Tugas_1,
                  'Tugas_2'=>$request->Tugas_2,
                  'Tugas_3'=>$request->Tugas_3,
                  'Tugas_4'=>$request->Tugas_4,
                  'Tugas_5'=>$request->Tugas_5,
                  'UTS'=>$request->UTS,
                  'UAS'=>$request->UAS,
                  'Offered_Course_id'=>$offer,

              ];
              DB::table('acd_student_khs_bobot')->insert($data);
                Alert::success('Selamat','Anda Berhasil meyimpan data bobot');
                return redirect()->back();
                // return Redirect::to('/proses/khs_matakuliah/'.$offer.'?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program.'&course_type='.$request->course_type);
          }elseif ($bobot!= null) {
              $data=[
                  'Presensi'=>$request->Presensi,
                  'Tugas_1'=>$request->Tugas_1,
                  'Tugas_2'=>$request->Tugas_2,
                  'Tugas_3'=>$request->Tugas_3,
                  'Tugas_4'=>$request->Tugas_4,
                  'Tugas_5'=>$request->Tugas_5,
                  'UTS'=>$request->UTS,
                  'UAS'=>$request->UAS,

              ];

              DB::table('acd_student_khs_bobot')->where('Student_khs_bobot_id', $request->id)->update($data);
              $krs=  DB::table('acd_student_krs')
              ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
              ->join('acd_offered_course' ,function ($join)
              {
                $join->on('acd_offered_course.Term_Year_Id','=','acd_student_krs.Term_Year_Id')
                ->on('acd_offered_course.Class_Prog_Id','=','acd_student_krs.Class_Prog_Id')
                ->on('acd_offered_course.Course_Id','=','acd_student_krs.Course_Id')
                ->on('acd_offered_course.Class_Id','=','acd_student_krs.Class_Id');
              })
              ->join('acd_student_khs_nilai_component','acd_student_khs_nilai_component.Krs_Id','=','acd_student_krs.Krs_Id')
              ->select('acd_student.Student_Id','acd_student.Department_Id','acd_student.NIM','acd_student.Full_Name','acd_student_khs_nilai_component.*',
              'acd_student_krs.Krs_Id','acd_student_krs.Sks')->where('acd_offered_course.Offered_Course_id', $offer)->get();
              $bob  =DB::table('acd_student_khs_bobot')->where('Student_khs_bobot_id', $request->id)->first();
  // dd($krs);
              foreach ($krs as $key) {

                      $tot1= $key->Presensi * ($bob->Presensi /100);$tot2= $key->Tugas_1 * ($bob->Tugas_1 /100);
                      $tot3= $key->Tugas_2 * ($bob->Tugas_2 /100);$tot4= $key->Tugas_3 * ($bob->Tugas_3 /100);
                      $tot5= $key->Tugas_4 * ($bob->Tugas_4 /100);$tot6= $key->Tugas_5 * ($bob->Tugas_5 /100);
                      $totUTS= $key->Uts * ($bob->UTS /100); $totUAS= $key->Uas * ($bob->UAS /100);
                      $score=$tot1 +$tot2+$tot3+$tot5+$tot6+$totUTS+$totUAS;

                      $data=[
                          'Presensi_Socre'=>$tot1,
                          'Tugas_1Score'=>$tot2,
                          'Tugas_2Score'=>$tot3,
                          'Tugas_3Score'=>$tot4,
                          'Tugas_4Score'=>$tot5,
                          'Tugas_5Score'=>$tot6,
                          'UTS_Score'=>$totUTS,
                          'UAS_Score'=>$totUAS,
                          'Total_score'=>$score,
                      ];

                      $proses =DB::table('acd_student_khs_nilai_component')->where('Krs_Id', $key->Krs_Id)->update($data);
              }

              Alert::success('Selamat','Anda Berhasil merubah data Bobot');
              return redirect()->back();
              // return Redirect::to('/proses/khs_matakuliah/'.$offer.'?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program);
          }
      }else {
          Alert::warning('Maaf','Total semua bobot harus 100');
          return redirect()->back();
      }      
  }

  public function getNilaiAkhir(Request $request,$id,$id2,$id3,$id4)
  {
      $l= Auth::user()->email;
      $array = [];
      $i = 0 ;
      $cekdata = DB::table('acd_student_krs')
          ->where('Term_Year_Id',$id4)
          ->where('Class_Prog_Id',$id2)
          ->where('Course_Id',$id3)
          ->where('Class_Id',$id)
          ->where('acd_student_krs.Is_Approved', 1)
          ->get();
          // dd($cekdata);
        $uts=  DB::table('acd_offered_course')
              ->leftjoin('acd_offered_course_lecturer','acd_offered_course_lecturer.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
              // ->leftjoin('acd_offered_course_exam as c','acd_offered_course.Offered_Course_Id','=','c.Offered_Course_Id')
              // ->leftjoin('acd_offered_course_exam_member as d','c.Offered_Course_Exam_Id','=','d.Offered_Course_Exam_Id')
              ->leftjoin('emp_employee','emp_employee.Employee_Id','=','acd_offered_course_lecturer.Employee_Id')
              ->leftjoin('acd_student_krs' ,function ($join)
              {
                $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
                ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
              })
              ->leftjoin('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
              ->Leftjoin('acd_student_khs_nilai_component','acd_student_khs_nilai_component.Krs_Id','=','acd_student_krs.Krs_Id')
              ->Leftjoin('acd_student_khs_bobot','acd_student_khs_bobot.Student_khs_bobot_id','=','acd_student_khs_nilai_component.Student_khs_nilai_component_id')
              ->select('acd_student.Nim',
                      'acd_student.Full_Name',
                      'acd_student.Photo',
                      'acd_student.Department_Id',
                      'acd_student_khs_nilai_component.*',
                      'acd_student_krs.Is_Remediasi',
                      'acd_student_krs.Krs_Id',
                      'acd_student.Student_Id',
                      'acd_offered_course.Offered_Course_Id'
                )
                ->where('acd_student_krs.Class_Id', $id)
                ->where('acd_student_krs.Class_Prog_Id', $id2)
                ->where('acd_student_krs.Course_Id',$id3 )
                ->where('acd_student_krs.Term_Year_Id',$id4)
                ->where('acd_student_krs.Is_Approved', 1)
                ->groupby('acd_student.Student_Id')
                ->get();
                if($uts->count() > 0){
                    foreach ($uts as $item){
                      $aoc_uts = DB::table('acd_offered_course_exam as a')
                      ->join('acd_offered_course_exam_member as b','a.Offered_Course_Exam_Id','=','b.Offered_Course_Exam_Id')
                      ->where('a.Offered_Course_Id',$item->Offered_Course_Id)
                      ->where('Student_Id',$item->Student_Id)
                      ->where('a.Exam_Type_Id',2)
                      ->first();
                      $aoc_uas = DB::table('acd_offered_course_exam as a')
                      ->join('acd_offered_course_exam_member as b','a.Offered_Course_Exam_Id','=','b.Offered_Course_Exam_Id')
                      ->where('a.Offered_Course_Id',$item->Offered_Course_Id)
                      ->where('Student_Id',$item->Student_Id)
                      ->where('a.Exam_Type_Id',1)
                      ->first();
                      if($aoc_uts != null){  
                        if($aoc_uas != null){
                          $array[$i]['Photo'] = $item->Photo;
                          $array[$i]['Nim'] = $item->Nim;
                          $array[$i]['Full_Name'] = $item->Full_Name;
                          $array[$i]['Ujian_uts'] = 1;
                          $array[$i]['Ujian_uas'] = 1;
                          $array[$i]['Presensi_Socre'] = $item->Presensi_Socre;
                          $array[$i]['Tugas_1'] = $item->Tugas_1;
                          $array[$i]['Tugas_2'] = $item->Tugas_2;
                          $array[$i]['Tugas_3'] = $item->Tugas_3;
                          $array[$i]['Tugas_4'] = $item->Tugas_4;
                          $array[$i]['Tugas_5'] = $item->Tugas_5;
                          $array[$i]['Uts'] = $item->Uts;
                          $array[$i]['Uas'] = $item->Uas;
                          $array[$i]['Uas_Remidi'] = $item->Uas_Remidi;
                          $array[$i]['Tugas_1Score'] = $item->Tugas_1Score;
                          $array[$i]['Tugas_2Score'] = $item->Tugas_2Score;
                          $array[$i]['Tugas_3Score'] = $item->Tugas_3Score;
                          $array[$i]['Tugas_4Score'] = $item->Tugas_4Score;
                          $array[$i]['Tugas_5Score'] = $item->Tugas_5Score;
                          $array[$i]['UTS_Score'] = $item->UTS_Score;
                          $array[$i]['UAS_Score'] = $item->UAS_Score;
                          $array[$i]['UAS_Remidi_Score'] = $item->UAS_Remidi_Score;
                          $array[$i]['Total_score'] = $item->Total_score;
                          $array[$i]['Krs_Id'] = $item->Krs_Id;
                          $array[$i]['Presence'] = $aoc_uts->Is_Presence;
                          $array[$i]['Presence_uas'] = $aoc_uas->Is_Presence;
                          $letter = "BL";
                          if($item->Total_score != null){
                              $grade_letter = DB::table('acd_grade_department')
                              ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                              ->select('acd_grade_letter.Grade_Letter','acd_grade_department.Scale_Numeric_Max','acd_grade_department.Scale_Numeric_Min')
                              ->where([
                                  ['acd_grade_department.Term_Year_Id',$id4],
                                  ['acd_grade_department.Department_Id',$item->Department_Id],
                                  ['acd_grade_department.Scale_Numeric_Max','>',($item->Total_score-0.01)],
                                  ['acd_grade_department.Scale_Numeric_Min','<',($item->Total_score+0.01)]
                                ])
                              ->first();
                              // dd($grade_letter);
                              if($grade_letter != null){
                                if($item->Is_Remediasi == 1){
                                  // if(strpos($grade_letter->Grade_Letter, "A") !== false ){
                                  //   $letter = "B+";
                                  // }else{
                                    $letter = $grade_letter->Grade_Letter;
                                  // }
                                }else{
                                  $letter = $grade_letter->Grade_Letter;
                                }
                              }else{
                                  $letter = "Belum Disetting";
                              }

                              $array[$i]['Grade_Letter'] = $letter;
                              //endif total score != null
                          }else{
                            $khs = DB::table('acd_student_khs')->where('Krs_Id',$item->Krs_Id)->get();
                            if($khs){
                              $array[$i]['Grade_Letter'] = 'NULL';
                            }else{
                              $array[$i]['Grade_Letter'] = $letter;
                            }
                          }
                          //endif uas not null
                        }else{
                          $array[$i]['Photo'] = $item->Photo;
                          $array[$i]['Nim'] = $item->Nim;
                          $array[$i]['Full_Name'] = $item->Full_Name;
                          $array[$i]['Ujian_uts'] = 1;
                          $array[$i]['Ujian_uas'] = 0;
                          $array[$i]['Presensi_Socre'] = $item->Presensi_Socre;
                          $array[$i]['Tugas_1'] = $item->Tugas_1;
                          $array[$i]['Tugas_2'] = $item->Tugas_2;
                          $array[$i]['Tugas_3'] = $item->Tugas_3;
                          $array[$i]['Tugas_4'] = $item->Tugas_4;
                          $array[$i]['Tugas_5'] = $item->Tugas_5;
                          $array[$i]['Uts'] = $item->Uts;
                          $array[$i]['Uas'] = $item->Uas;
                          $array[$i]['Uas_Remidi'] = $item->Uas_Remidi;
                          $array[$i]['Tugas_1Score'] = $item->Tugas_1Score;
                          $array[$i]['Tugas_2Score'] = $item->Tugas_2Score;
                          $array[$i]['Tugas_3Score'] = $item->Tugas_3Score;
                          $array[$i]['Tugas_4Score'] = $item->Tugas_4Score;
                          $array[$i]['Tugas_5Score'] = $item->Tugas_5Score;
                          $array[$i]['UTS_Score'] = $item->UTS_Score;
                          $array[$i]['UAS_Score'] = $item->UAS_Score;
                          $array[$i]['UAS_Remidi_Score'] = $item->UAS_Remidi_Score;
                          $array[$i]['Total_score'] = $item->Total_score;
                          $array[$i]['Krs_Id'] = $item->Krs_Id;
                          $array[$i]['Presence'] = $aoc_uts->Is_Presence;
                          $array[$i]['Presence_uas'] = 0;
                          $letter = "BL";
                          if($item->Total_score != null){
                              $grade_letter = DB::table('acd_grade_department')
                              ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                              ->select('acd_grade_letter.Grade_Letter')
                              ->where([
                                  ['acd_grade_department.Term_Year_Id',$id4],
                                  ['acd_grade_department.Department_Id',$item->Department_Id],
                                  ['acd_grade_department.Scale_Numeric_Max','>',($item->Total_score-0.01)],
                                  ['acd_grade_department.Scale_Numeric_Min','<',($item->Total_score+0.01)]
                                ])
                              ->first();
                              if($grade_letter != null){
                                if($item->Is_Remediasi == 1){
                                  if(strpos($grade_letter->Grade_Letter, "A") !== false ){
                                    $letter = "B";
                                  }else{
                                    $letter = $grade_letter->Grade_Letter;
                                  }
                                }else{
                                  $letter = $grade_letter->Grade_Letter;
                                }
                              }else{
                                  $letter = "Belum Disetting";
                              }

                              $array[$i]['Grade_Letter'] = $letter;
                          }else{
                            $khs = DB::table('acd_student_khs')->where('Krs_Id',$item->Krs_Id)->get();
                            if($khs){
                              $array[$i]['Grade_Letter'] = 'NULL';
                            }else{
                              $array[$i]['Grade_Letter'] = $letter;
                            }
                          }
                          // dd($letter);
                        }
                        //end if aoc_uts not null
                      }else{
                        if($aoc_uas != null){
                          $array[$i]['Photo'] = $item->Photo;
                          $array[$i]['Nim'] = $item->Nim;
                          $array[$i]['Full_Name'] = $item->Full_Name;
                          $array[$i]['Ujian_uts'] = 0;
                          $array[$i]['Ujian_uas'] = 1;
                          $array[$i]['Presensi_Socre'] = $item->Presensi_Socre;
                          $array[$i]['Tugas_1'] = $item->Tugas_1;
                          $array[$i]['Tugas_2'] = $item->Tugas_2;
                          $array[$i]['Tugas_3'] = $item->Tugas_3;
                          $array[$i]['Tugas_4'] = $item->Tugas_4;
                          $array[$i]['Tugas_5'] = $item->Tugas_5;
                          $array[$i]['Uts'] = $item->Uts;
                          $array[$i]['Uas'] = $item->Uas;
                          $array[$i]['Uas_Remidi'] = $item->Uas_Remidi;
                          $array[$i]['Tugas_1Score'] = $item->Tugas_1Score;
                          $array[$i]['Tugas_2Score'] = $item->Tugas_2Score;
                          $array[$i]['Tugas_3Score'] = $item->Tugas_3Score;
                          $array[$i]['Tugas_4Score'] = $item->Tugas_4Score;
                          $array[$i]['Tugas_5Score'] = $item->Tugas_5Score;
                          $array[$i]['UTS_Score'] = $item->UTS_Score;
                          $array[$i]['UAS_Score'] = $item->UAS_Score;
                          $array[$i]['UAS_Remidi_Score'] = $item->UAS_Remidi_Score;
                          $array[$i]['Total_score'] = $item->Total_score;
                          $array[$i]['Krs_Id'] = $item->Krs_Id;
                          $array[$i]['Presence'] = 0;
                          $array[$i]['Presence_uas'] = $aoc_uas->Is_Presence;
                          $letter = "BL";
                          if($item->Total_score != null){
                              $grade_letter = DB::table('acd_grade_department')
                              ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                              ->select('acd_grade_letter.Grade_Letter')
                              ->where([
                                  ['acd_grade_department.Term_Year_Id',$id4],
                                  ['acd_grade_department.Department_Id',$item->Department_Id],
                                  ['acd_grade_department.Scale_Numeric_Max','>',($item->Total_score-0.01)],
                                  ['acd_grade_department.Scale_Numeric_Min','<',($item->Total_score+0.01)]
                                ])
                              ->first();
                              if($grade_letter != null){
                                  $letter = $grade_letter->Grade_Letter;
                              }else{
                                  $letter = "Belum Disetting";
                              }

                          }
                          $array[$i]['Grade_Letter'] = $letter;
                        }else{
                          $array[$i]['Nim'] = $item->Nim;
                          $array[$i]['Full_Name'] = $item->Full_Name;
                          $array[$i]['Ujian_uts'] = 0;
                          $array[$i]['Ujian_uas'] = 0;
                          $array[$i]['Presensi_Socre'] = $item->Presensi_Socre;
                          $array[$i]['Tugas_1'] = $item->Tugas_1;
                          $array[$i]['Tugas_2'] = $item->Tugas_2;
                          $array[$i]['Tugas_3'] = $item->Tugas_3;
                          $array[$i]['Tugas_4'] = $item->Tugas_4;
                          $array[$i]['Tugas_5'] = $item->Tugas_5;
                          $array[$i]['Uts'] = $item->Uts;
                          $array[$i]['Uas'] = $item->Uas;
                          $array[$i]['Uas_Remidi'] = $item->Uas_Remidi;
                          $array[$i]['Tugas_1Score'] = $item->Tugas_1Score;
                          $array[$i]['Tugas_2Score'] = $item->Tugas_2Score;
                          $array[$i]['Tugas_3Score'] = $item->Tugas_3Score;
                          $array[$i]['Tugas_4Score'] = $item->Tugas_4Score;
                          $array[$i]['Tugas_5Score'] = $item->Tugas_5Score;
                          $array[$i]['UTS_Score'] = $item->UTS_Score;
                          $array[$i]['UAS_Score'] = $item->UAS_Score;
                          $array[$i]['UAS_Remidi_Score'] = $item->UAS_Remidi_Score;
                          $array[$i]['Total_score'] = $item->Total_score;
                          $array[$i]['Krs_Id'] = $item->Krs_Id;
                          $array[$i]['Presence'] = 0;
                          $array[$i]['Presence_uas'] = 0;
                          $letter = "BL";
                          if($item->Total_score != null){
                              $grade_letter = DB::table('acd_grade_department')
                              ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                              ->select('acd_grade_letter.Grade_Letter')
                              ->where([
                                  ['acd_grade_department.Term_Year_Id',$id4],
                                  ['acd_grade_department.Department_Id',$item->Department_Id],
                                  ['acd_grade_department.Scale_Numeric_Max','>',($item->Total_score-0.01)],
                                  ['acd_grade_department.Scale_Numeric_Min','<',($item->Total_score+0.01)]
                                ])
                              ->first();
                              if($grade_letter != null){
                                  $letter = $grade_letter->Grade_Letter;
                              }else{
                                  $letter = "Belum Disetting";
                              }

                          }
                          $array[$i]['Grade_Letter'] = $letter;
                        }
                      }
                        $i++;
                        // dd($array);
                        //end foreach
                    }
                    //end count >0 
                }
                //ada disini

                // dd($array);
                if(count($array)<=0){
                  $data['data']=[];
                  $data['total']=0;
                }elseif (count($array)>0) {
                  $data['data']=$array;
                  $data['total']=count($array);
                }
      return json_encode($data);
  }

  public function updateNilaiAkhir(Request $request)
  {
    $today = date("Y-m-d");
    $startuas = $request->startuas;
    $enduas = $request->enduas;

    $l= Auth::user()->email;
    $std = DB::table('acd_student')->where('Nim',$request->data['Nim'])->first();
    $offer = DB::table('acd_offered_course')->where('Offered_Course_id',$request->offer)->first();

    $cek = DB::table('acd_student_khs_bobot')->where('Offered_Course_id',$request->offer)->first();


    if ($cek == null) {
        $result = [];
        $result['status'] = 0;
        $result['message'] = 'Bobot belum disetting';

        return json_encode($result);
    }else {
        $krs=DB::table('acd_student_khs_nilai_component')->where('Krs_Id', $request->data['Krs_Id'])->first();
        $krsStudent=  DB::table('acd_student_krs')
        ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
        ->where('Krs_Id', $request->data['Krs_Id'])->first();
        $Ujian_uts = $request->data['Ujian_uts'];
        $Ujian_uas = $request->data['Ujian_uas'];
        $Presence = $request->data['Presence'];
        $Presence_uas = $request->data['Presence_uas'];
        
        if($krs == null){
            $Tugas_1= $request->data['Tugas_1'] * ($cek->Tugas_1 /100);
            $Tugas_2= $request->data['Tugas_2'] * ($cek->Tugas_2 /100);
            $Tugas_3= $request->data['Tugas_3'] * ($cek->Tugas_3 /100);
            $Tugas_4= $request->data['Tugas_4'] * ($cek->Tugas_4 /100);
            $Tugas_5= $request->data['Tugas_5'] * ($cek->Tugas_5 /100);
            if($Ujian_uts == 1){
              if($Presence == 1){
                $Uts_score= $request->data['Uts'] * ($cek->UTS /100);
                $Uts = $request->data['Uts'];
              }else{
                $Uts_score= null;
                $Uts= null;
              }
            }else{
                $Uts_score= null;
                $Uts= null;
            }
            if($Ujian_uas == 1){
              if($Presence_uas == 1){
                $Uas_score= $request->data['Uas'] * ($cek->UAS /100);
                $Uas= $request->data['Uas'];
              }else{
                $Uas_score= null;
                $Uas= null;
              }
            }else{
                $Uas_score= null;
                $Uas= null;
            }
            // $Uts= $request->data['Uts'] * ($cek->UTS /100);
            // $Uas= $request->data['Uas'] * ($cek->UAS /100);
            $Uas_Remidi= $request->data['Uas_Remidi'] * ($cek->UAS /100);
            $real = ($Uas_score >= $Uas_Remidi? $Uas_score : $Uas_Remidi);
            $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts_score+$real;

            $data=[
            'Krs_Id'=> $request->data['Krs_Id'],
            'Tugas_1'=> $request->data['Tugas_1'],
            'Tugas_2'=> $request->data['Tugas_2'],
            'Tugas_3'=> $request->data['Tugas_3'],
            'Tugas_4'=> $request->data['Tugas_4'],
            'Tugas_5'=> $request->data['Tugas_5'],
            'Uts'=> $Uts,
            'Uas'=> $Uas,
            'Uas_Remidi'=> $request->data['Uas_Remidi'],
            'Tugas_1Score'=> $Tugas_1,
            'Tugas_2Score'=> $Tugas_2,
            'Tugas_3Score'=> $Tugas_3,
            'Tugas_4Score'=> $Tugas_4,
            'Tugas_5Score'=> $Tugas_5,
            'UTS_Score'=>$Uts_score,
            'UAS_Score'=>$Uas_score,
            'UAS_Remidi_Score'=> $Uas_Remidi,
            'Total_score'=>$total,
            ];
            $proses =DB::table('acd_student_khs_nilai_component')->insertGetId($data);
            // $this->khsSimpan($request->data['Krs_Id,$total,$krsStudent->Department_Id);

            $check_khs = DB::table('acd_student_khs')->where('Krs_Id',$krs->Krs_Id)->first();
            if(!$check_khs){
              $khsIsi=[
                'Sks'=>$krsStudent->Sks,
                'Student_Id'=>$krsStudent->Student_Id,
                'Krs_Id'=>$krs->Krs_Id,
                'Is_For_Transkrip'=>'1',
                'Is_Published'=>false,
                'Created_By' => auth()->user()->email,
                'Created_Date' => date('Y-m-d H:i:s')
              ];
              $tambahkhs = DB::table('acd_student_khs')->insert($khsIsi);
            }
                return json_encode($proses);
        }else {
          if($Ujian_uts == 1){
              if($Presence == 1){
                $Uts_score= $request->data['Uts'] * ($cek->UTS /100);
                $Uts = $request->data['Uts'];
              }else{
                $Uts_score= null;
                $Uts= null;
              }
            }else{
                $Uts_score= null;
                $Uts= null;
            }
            if($Ujian_uas == 1){
              if($Presence_uas == 1){
                $Uas_score= $request->data['Uas'] * ($cek->UAS /100);
                $Uas= $request->data['Uas'];
              }else{
                $Uas_score= null;
                $Uas= null;
              }
            }else{
                $Uas_score= null;
                $Uas= null;
            }

            $Tugas_1= $request->data['Tugas_1'] * ($cek->Tugas_1 /100);
            $Tugas_2= $request->data['Tugas_2'] * ($cek->Tugas_2 /100);
            $Tugas_3= $request->data['Tugas_3'] * ($cek->Tugas_3 /100);
            $Tugas_4= $request->data['Tugas_4'] * ($cek->Tugas_4 /100);
            $Tugas_5= $request->data['Tugas_5'] * ($cek->Tugas_5 /100);
            // $Uts= $request->data['Uts'] * ($cek->UTS /100);
            // $Uas= $request->data['Uas'] * ($cek->UAS /100);
            $Uas_Remidi= $request->data['Uas_Remidi'] * ($cek->UAS /100);
            // if($Uas_Remidi > $Uas_score){
            if($request->data['Uas_Remidi'] != null){
              $real = ($Uas_score >= $Uas_Remidi? $Uas_score : $Uas_Remidi);     
              $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts_score+$real;
              $grade_dept = DB::table('acd_grade_department')->where([['Department_Id',$std->Department_Id],['Term_Year_Id',$offer->Term_Year_Id]])->first();
              $grade_dept_b = DB::table('acd_grade_department')->where([['Department_Id',$std->Department_Id],['Term_Year_Id',$offer->Term_Year_Id],['Grade_Letter_Id',39]])->first();
              if($grade_dept && $grade_dept_b){
                if($total > $grade_dept_b->Scale_Numeric_Max){
                  $total=$grade_dept_b->Scale_Numeric_Max;
                }else{
                  $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts_score+$real;
                }
              }
            }else{
              $real = ($Uas_score >= $Uas_Remidi? $Uas_score : $Uas_Remidi);              
              $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts_score+$real;
            }

             //update here
             if($today >= $startuas && $today <= $enduas){
              $isinilai = true;
            }else{
              $isinilai = false;
             }
            $cek_letter_out = DB::table('acd_grade_department as a')
            ->join('acd_grade_letter as b','a.Grade_Letter_Id','=','b.Grade_Letter_Id')
            ->where('a.Term_Year_Id',$request->term_year)
            ->where('a.Department_Id',$request->department)
            ->where('b.Grade_Letter','E')
            ->first();
            // dd($cek_letter_out->Scale_Numeric_Min);
            
            if($Uas == null && $isinilai == false){
              $data=[
                // 'Krs_Id'=> $request->data['Krs_Id,
                'Tugas_1'=> $request->data['Tugas_1'],
                'Tugas_2'=> $request->data['Tugas_2'],
                'Tugas_3'=> $request->data['Tugas_3'],
                'Tugas_4'=> $request->data['Tugas_4'],
                'Tugas_5'=> $request->data['Tugas_5'],
                'Uts'=> $Uts,
                'Uas'=> $Uas,
                'Uas_Remidi'=> $request->data['Uas_Remidi'],
                'Tugas_1Score'=> $Tugas_1,
                'Tugas_2Score'=> $Tugas_2,
                'Tugas_3Score'=> $Tugas_3,
                'Tugas_4Score'=> $Tugas_4,
                'Tugas_5Score'=> $Tugas_5,
                'UTS_Score'=>$Uts_score,
                'UAS_Score'=>$Uas_score,
                'UAS_Remidi_Score'=> $Uas_Remidi,
                'Total_score'=>$cek_letter_out->Scale_Numeric_Min,
              ];
            }else{
              $data=[
                // 'Krs_Id'=> $request->data['Krs_Id,
                'Tugas_1'=> $request->data['Tugas_1'],
                'Tugas_2'=> $request->data['Tugas_2'],
                'Tugas_3'=> $request->data['Tugas_3'],
                'Tugas_4'=> $request->data['Tugas_4'],
                'Tugas_5'=> $request->data['Tugas_5'],
                'Uts'=> $Uts,
                'Uas'=> $Uas,
                'Uas_Remidi'=> $request->data['Uas_Remidi'],
                'Tugas_1Score'=> $Tugas_1,
                'Tugas_2Score'=> $Tugas_2,
                'Tugas_3Score'=> $Tugas_3,
                'Tugas_4Score'=> $Tugas_4,
                'Tugas_5Score'=> $Tugas_5,
                'UTS_Score'=>$Uts_score,
                'UAS_Score'=>$Uas_score,
                'UAS_Remidi_Score'=> $Uas_Remidi,
                'Total_score'=>$total,
              ];
            }

           
            $proses =DB::table('acd_student_khs_nilai_component')->where('Krs_Id', $krs->Krs_Id)->update($data);
                // $this->khsSimpan($krs->Krs_Id,$total,$krsStudent->Department_Id);
            // dd('stop');
            
            $check_khs = DB::table('acd_student_khs')->where('Krs_Id',$krs->Krs_Id)->first();
            if(!$check_khs){
              $khsIsi=[
                'Sks'=>$krsStudent->Sks,
                'Student_Id'=>$krsStudent->Student_Id,
                'Krs_Id'=>$krs->Krs_Id,
                'Is_For_Transkrip'=>'1',
                'Is_Published'=>false,
                'Created_By' => auth()->user()->email,
                'Created_Date' => date('Y-m-d H:i:s')
              ];
              $tambahkhs = DB::table('acd_student_khs')->insert($khsIsi);
            }
                return json_encode($proses);
        }
    }
  }

  // public function publishNilai(Request $request)
  // {
  //   $uts=  DB::table('acd_offered_course')
  //       ->join('acd_offered_course_lecturer','acd_offered_course_lecturer.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
  //       ->join('emp_employee','emp_employee.Employee_Id','=','acd_offered_course_lecturer.Employee_Id')
  //       ->join('acd_student_krs','acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
  //       ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
  //       ->Leftjoin('acd_student_khs_nilai_component','acd_student_khs_nilai_component.Krs_Id','=','acd_student_krs.Krs_Id')
  //       ->select('acd_student.Nim',
  //               'acd_student.Student_Id',
  //               'acd_student.Full_Name',
  //               'acd_student.Department_Id',
  //               'acd_student_khs_nilai_component.*',
  //               'acd_student_krs.Krs_Id'
  //         )->where('acd_offered_course.Class_Id', $request->Class_Id)
  //         ->where('acd_offered_course.Course_Id', $request->Course_Id)
  //         ->where('acd_student_krs.Class_Id', $request->Class_Id)
  //         ->where('acd_student_krs.Class_Prog_Id', $request->class_program)
  //         ->where('acd_student_krs.Course_Id',$request->Course_Id )
  //         ->where('acd_student_krs.Term_Year_Id',$request->term_year)
  //         ->where('acd_student_krs.Is_Approved', 1)
  //         ->groupby('acd_student.Student_Id')->get();
          
  //     foreach ($uts as $key) {
  //     $data_krs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->first();
  //     $data_krsc = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->count();
  //     $cek_presence = DB::table('acd_offered_course_exam_member')
  //     ->join('acd_offered_course_exam','acd_offered_course_exam_member.Offered_Course_Exam_Id','=','acd_offered_course_exam.Offered_Course_Exam_Id')
  //     ->where('acd_offered_course_exam.Offered_Course_Id',$request->Offered_Course_id)
  //     ->where('acd_offered_course_exam_member.Student_Id',$key->Student_Id)->first();

  //         $insertkhs = DB::table('acd_student_khs_nilai_component')->insertGetId($data_insert);
  //         $data_nilai = DB::table('acd_student_khs_nilai_component')->where('Student_khs_nilai_component_id',$insertkhs)->get();
  //         $Tugas_1n= ($data_nilai[0]->Tugas_1Score == null ? 0 : $data_nilai[0]->Tugas_1Score);
  //         $Tugas_2n= ($data_nilai[0]->Tugas_2Score == null ? 0 :  $data_nilai[0]->Tugas_2Score);
  //         $Tugas_3n= ($data_nilai[0]->Tugas_3Score == null ? 0 : $data_nilai[0]->Tugas_3Score);
  //         $Tugas_4n= ($data_nilai[0]->Tugas_4Score == null ? 0 : $data_nilai[0]->Tugas_4Score);
  //         $Tugas_5n= ($data_nilai[0]->Tugas_5Score == null ? 0 : $data_nilai[0]->Tugas_5Score);
  //         $Utsn= ($data_nilai[0]->UTS_Score == null ? 0 : $data_nilai[0]->UTS_Score);
  //         $Uasn= ($data_nilai[0]->UAS_Score == null ? 0 : $data_nilai[0]->UAS_Score);
  //         $Uas_Remidin= ($data_nilai[0]->UAS_Remidi_Score == null ? 0 : $data_nilai[0]->UAS_Remidi_Score);
  //         $realn = ($Uasn >= $Uas_Remidin? $Uasn : $Uas_Remidin);
  //         $totaln=$Tugas_1n+$Tugas_2n+$Tugas_3n+$Tugas_4n+$Tugas_5n+$Utsn+$Uasn+$realn;
  //         $krsStudent=  DB::table('acd_student_krs')
  //         ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
  //         ->where('Krs_Id',$key->Krs_Id)->first();
  //         $grade_letter = DB::table('acd_grade_department')
  //         ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
  //         ->select('acd_grade_letter.Grade_Letter_Id','acd_grade_department.Weight_Value')
  //         ->where([
  //             ['acd_grade_department.Department_Id',$key->Department_Id],
  //             ['acd_grade_department.Term_Year_Id',$request->term_year],
  //             ['acd_grade_department.Scale_Numeric_Max','>=',$totaln],
  //             ['acd_grade_department.Scale_Numeric_Min','<=',$totaln]
  //             ])
  //         ->first();
  
  //           $letter = "BL";
  //         if($grade_letter != null){
  //             $letter = $grade_letter->Grade_Letter_Id;
  //         }else{
  //             $m['message']='Nilai Huruf belum di setting';
  //             $m['status']=1;
  //                 return $m ;
  //         }
  
  //         $khsIsi=['Grade_Letter_Id'=>$letter,
  //                   'Weight_Value'=>$grade_letter->Weight_Value,
  //                   'Sks'=>$krsStudent->Sks,
  //                   'Student_Id'=>$key->Student_Id,
  //                   'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
  //                   'Krs_Id'=>$key->Krs_Id,
  //                   'Is_For_Transkrip'=>'1',
  //                   'Created_By' => auth()->user()->email,
  //                   'Created_Date' => date('Y-m-d H:i:s')
  //                   ];
  //         $tambahkhs=    DB::table('acd_student_khs')->insertGetId($khsIsi);
  //         $khs_Id = DB::getPdo()->lastInsertId();
  //         $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($tambahkhs,''));  
  //       }
  //     //   continue;
  //     // }else{
  //     //   if($key->Uts == null){
  //     //     if($cek_presence->Is_Presence == 1){
  //     //       $Tugas_1= $key->Tugas_1Score;
  //     //       $Tugas_2= $key->Tugas_2Score;
  //     //       $Tugas_3= $key->Tugas_3Score;
  //     //       $Tugas_4= $key->Tugas_4Score;
  //     //       $Tugas_5= $key->Tugas_5Score;
  //     //       $Uts= $key->UTS_Score;
  //     //       $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts;
  //     //       $data_insert = [
  //     //         'Krs_Id' => $key->Krs_Id,
  //     //         'Uts' => $key->Uts,
  //     //         'UTS_Score' => $Uts,
  //     //         'Total_score' => $total
  //     //       ];
  //     //     }
  //     //     $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update($data_insert);

  //     //     $data_nilai = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->first();
  //     //     $Tugas_1n= $data_nilai->Tugas_1Score;
  //     //     $Tugas_2n= $data_nilai->Tugas_2Score;
  //     //     $Tugas_3n= $data_nilai->Tugas_3Score;
  //     //     $Tugas_4n= $data_nilai->Tugas_4Score;
  //     //     $Tugas_5n= $data_nilai->Tugas_5Score;
  //     //     $Utsn= $data_nilai->UTS_Score;
  //     //     $Uasn= $data_nilai->UAS_Score;
  //     //     $Uas_Remidin= $data_nilai->UAS_Remidi_Score;
  //     //     $realn = ($Uasn >= $Uas_Remidin? $Uasn : $Uas_Remidin);
  //     //     $totaln=$Tugas_1n+$Tugas_2n+$Tugas_3n+$Tugas_4n+$Tugas_5n+$Utsn+$Uasn+$realn;
  //     //     $krsStudent=  DB::table('acd_student_krs')
  //     //       ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
  //     //       ->where('Krs_Id',$key->Krs_Id)->first();
  //     //     $grade_letter = DB::table('acd_grade_department')
  //     //     ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
  //     //     ->select('acd_grade_letter.Grade_Letter_Id','acd_grade_department.Weight_Value')
  //     //     ->where([
  //     //         ['acd_grade_department.Department_Id',$key->Department_Id],
  //     //         ['acd_grade_department.Term_Year_Id',$request->term_year],
  //     //         ['acd_grade_department.Scale_Numeric_Max','>=',$totaln],
  //     //         ['acd_grade_department.Scale_Numeric_Min','<=',$totaln]
  //     //         ])
  //     //     ->first();

  //     //       $letter = "BL";
  //     //     if($grade_letter != null){
  //     //         $letter = $grade_letter->Grade_Letter_Id;
  //     //     }else{
  //     //         $m['message']='Nilai Huruf belum di setting';
  //     //         $m['status']=1;
  //     //             return $m ;
  //     //     }

  //     //     $khsIsi=['Grade_Letter_Id'=>$letter,
  //     //             'Weight_Value'=>$grade_letter->Weight_Value,
  //     //             'Sks'=>$krsStudent->Sks,
  //     //             'Student_Id'=>$key->Student_Id,
  //     //             'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
  //     //             'Is_For_Transkrip'=>'1'
  //     //             ];
  //     //     $tambahkhs=     DB::table('acd_student_khs')->where('Krs_Id', $key->Krs_Id)->update($khsIsi);
  //     //     $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs->Khs_Id,''));
  //     //   }
  //     // }      
  //     // continue;
    
  //   return response()->json([
  //               'status' => 200,
  //               'message' => 'sukses.',
  //           ]);
            
  // }

  public function defaultnilaiuts(Request $request)
  {
    $cek = DB::table('acd_student_khs_bobot')->where('Offered_Course_id',$request->Offered_Course_id)->first();
    if ($cek == null) {
        return response()->json([
                'status' => 200,
                'message' => 'Bobot Belum Diseting.',
            ]);
    }else {
    $uts=  DB::table('acd_offered_course')
        ->join('acd_offered_course_lecturer','acd_offered_course_lecturer.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
        ->join('emp_employee','emp_employee.Employee_Id','=','acd_offered_course_lecturer.Employee_Id')
        ->join('acd_student_krs','acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
        ->Leftjoin('acd_student_khs_nilai_component','acd_student_khs_nilai_component.Krs_Id','=','acd_student_krs.Krs_Id')
        ->select('acd_student.Nim',
                'acd_student.Student_Id',
                'acd_student.Full_Name',
                'acd_student.Department_Id',
                'acd_student_khs_nilai_component.*',
                'acd_student_krs.Krs_Id'
          )->where('acd_offered_course.Class_Id', $request->Class_Id)
          ->where('acd_offered_course.Course_Id', $request->Course_Id)
          ->where('acd_student_krs.Class_Id', $request->Class_Id)
          ->where('acd_student_krs.Class_Prog_Id', $request->class_program)
          ->where('acd_student_krs.Course_Id',$request->Course_Id )
          ->where('acd_student_krs.Term_Year_Id',$request->term_year)
          ->where('acd_student_krs.Is_Approved', 1)
          ->groupby('acd_student.Student_Id')->get();
      foreach ($uts as $key) {
      $data_krs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->first();
      $data_krsc = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->count();
      $cek_presence = DB::table('acd_offered_course_exam_member')
      ->join('acd_offered_course_exam','acd_offered_course_exam_member.Offered_Course_Exam_Id','=','acd_offered_course_exam.Offered_Course_Exam_Id')
      ->where('acd_offered_course_exam.Offered_Course_Id',$request->Offered_Course_id)
      ->where('acd_offered_course_exam_member.Student_Id',$key->Student_Id)->first();
      
      if($data_krsc == 0){
        $Uts= 65 * ($cek->UTS /100);
        if($cek_presence->Is_Presence == 1){
          $data_insert = [
            'Krs_Id' => $key->Krs_Id,
            'Uts' => 65,
            'UTS_Score' => $Uts,
            'Total_score' => $Uts,
            'Created_By' => auth()->user()->email,
            'Role' => 'Admin'
          ];
          $insertkhs = DB::table('acd_student_khs_nilai_component')->insertGetId($data_insert);
          $data_nilai = DB::table('acd_student_khs_nilai_component')->where('Student_khs_nilai_component_id',$insertkhs)->get();
          $Tugas_1n= ($data_nilai[0]->Tugas_1Score == null ? 0 : $data_nilai[0]->Tugas_1Score);
          $Tugas_2n= ($data_nilai[0]->Tugas_2Score == null ? 0 :  $data_nilai[0]->Tugas_2Score);
          $Tugas_3n= ($data_nilai[0]->Tugas_3Score == null ? 0 : $data_nilai[0]->Tugas_3Score);
          $Tugas_4n= ($data_nilai[0]->Tugas_4Score == null ? 0 : $data_nilai[0]->Tugas_4Score);
          $Tugas_5n= ($data_nilai[0]->Tugas_5Score == null ? 0 : $data_nilai[0]->Tugas_5Score);
          $Utsn= ($data_nilai[0]->UTS_Score == null ? 0 : $data_nilai[0]->UTS_Score);
          $Uasn= ($data_nilai[0]->UAS_Score == null ? 0 : $data_nilai[0]->UAS_Score);
          $Uas_Remidin= ($data_nilai[0]->UAS_Remidi_Score == null ? 0 : $data_nilai[0]->UAS_Remidi_Score);
          $realn = ($Uasn >= $Uas_Remidin? $Uasn : $Uas_Remidin);
          $totaln=$Tugas_1n+$Tugas_2n+$Tugas_3n+$Tugas_4n+$Tugas_5n+$Utsn+$Uasn+$realn;
          $krsStudent=  DB::table('acd_student_krs')
          ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
          ->where('Krs_Id',$key->Krs_Id)->first();
          $khsStudent=  DB::table('acd_student_khs')
              ->where('Krs_Id',$key->Krs_Id)->get();
          $grade_letter = DB::table('acd_grade_department')
          ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
          ->select('acd_grade_letter.Grade_Letter_Id','acd_grade_department.Weight_Value')
          ->where([
              ['acd_grade_department.Department_Id',$key->Department_Id],
              ['acd_grade_department.Term_Year_Id',$request->term_year],
              ['acd_grade_department.Scale_Numeric_Max','>',($totaln-0.01)],
              ['acd_grade_department.Scale_Numeric_Min','<',($totaln+0.01)]
              ])
          ->first();
  
            $letter = "BL";
          if($grade_letter != null){
              $letter = $grade_letter->Grade_Letter_Id;
          }else{
              $m['message']='Nilai Huruf belum di setting';
              $m['status']=1;
                  return $m ;
          }

          if($khsStudent->count() <= 0){
          $khsIsi=['Grade_Letter_Id'=>$letter,
                    'Weight_Value'=>$grade_letter->Weight_Value,
                    'Sks'=>$krsStudent->Sks,
                    'Student_Id'=>$key->Student_Id,
                    'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
                    'Krs_Id'=>$key->Krs_Id,
                    'Is_For_Transkrip'=>'1',
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')
                    ];
          $tambahkhs=    DB::table('acd_student_khs')->insertGetId($khsIsi);
          $khs_Id = DB::getPdo()->lastInsertId();
          $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($tambahkhs,''));  
          }else{
            $khsIsi=['Grade_Letter_Id'=>$letter,
                      'Weight_Value'=>$grade_letter->Weight_Value,
                      'Sks'=>$krsStudent->Sks,
                      'Student_Id'=>$key->Student_Id,
                      'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
                      'Krs_Id'=>$key->Krs_Id,
                      'Is_For_Transkrip'=>'1',
                      'Created_By' => auth()->user()->email,
                      'Created_Date' => date('Y-m-d H:i:s')
                      ];
            $khs_idd= DB::table('acd_student_khs')->where('Krs_Id',$key->Krs_Id)->first();
            $tambahkhs= DB::table('acd_student_khs')->where('Khs_Id',$khs_idd->Khs_Id)->update($khsIsi);
            $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_idd->Khs_Id,''));
          }
        }

      }else{
        continue;
      }
      //   continue;
      // }else{
      //   if($key->Uts == null){
      //     if($cek_presence->Is_Presence == 1){
      //       $Tugas_1= $key->Tugas_1Score;
      //       $Tugas_2= $key->Tugas_2Score;
      //       $Tugas_3= $key->Tugas_3Score;
      //       $Tugas_4= $key->Tugas_4Score;
      //       $Tugas_5= $key->Tugas_5Score;
      //       $Uts= $key->UTS_Score;
      //       $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts;
      //       $data_insert = [
      //         'Krs_Id' => $key->Krs_Id,
      //         'Uts' => $key->Uts,
      //         'UTS_Score' => $Uts,
      //         'Total_score' => $total
      //       ];
      //     }
      //     $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update($data_insert);

      //     $data_nilai = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->first();
      //     $Tugas_1n= $data_nilai->Tugas_1Score;
      //     $Tugas_2n= $data_nilai->Tugas_2Score;
      //     $Tugas_3n= $data_nilai->Tugas_3Score;
      //     $Tugas_4n= $data_nilai->Tugas_4Score;
      //     $Tugas_5n= $data_nilai->Tugas_5Score;
      //     $Utsn= $data_nilai->UTS_Score;
      //     $Uasn= $data_nilai->UAS_Score;
      //     $Uas_Remidin= $data_nilai->UAS_Remidi_Score;
      //     $realn = ($Uasn >= $Uas_Remidin? $Uasn : $Uas_Remidin);
      //     $totaln=$Tugas_1n+$Tugas_2n+$Tugas_3n+$Tugas_4n+$Tugas_5n+$Utsn+$Uasn+$realn;
      //     $krsStudent=  DB::table('acd_student_krs')
      //       ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
      //       ->where('Krs_Id',$key->Krs_Id)->first();
      //     $grade_letter = DB::table('acd_grade_department')
      //     ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
      //     ->select('acd_grade_letter.Grade_Letter_Id','acd_grade_department.Weight_Value')
      //     ->where([
      //         ['acd_grade_department.Department_Id',$key->Department_Id],
      //         ['acd_grade_department.Term_Year_Id',$request->term_year],
      //         ['acd_grade_department.Scale_Numeric_Max','>=',$totaln],
      //         ['acd_grade_department.Scale_Numeric_Min','<=',$totaln]
      //         ])
      //     ->first();

      //       $letter = "BL";
      //     if($grade_letter != null){
      //         $letter = $grade_letter->Grade_Letter_Id;
      //     }else{
      //         $m['message']='Nilai Huruf belum di setting';
      //         $m['status']=1;
      //             return $m ;
      //     }

      //     $khsIsi=['Grade_Letter_Id'=>$letter,
      //             'Weight_Value'=>$grade_letter->Weight_Value,
      //             'Sks'=>$krsStudent->Sks,
      //             'Student_Id'=>$key->Student_Id,
      //             'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
      //             'Is_For_Transkrip'=>'1'
      //             ];
      //     $tambahkhs=     DB::table('acd_student_khs')->where('Krs_Id', $key->Krs_Id)->update($khsIsi);
      //     $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs->Khs_Id,''));
      //   }
      // }      
      // continue;
    }
    return response()->json([
                'status' => 200,
                'message' => 'sukses.',
            ]);
    }

  }

  public function defaultnilaiuas(Request $request)
  {
    $cek = DB::table('acd_student_khs_bobot')->where('Offered_Course_id',$request->Offered_Course_id)->first();
    if ($cek == null) {
        return response()->json([
                'status' => 200,
                'success' =>false,
                'message' => 'Bobot Belum Diseting.',
            ]);
    }else {
    $uas=  DB::table('acd_offered_course')
        ->join('acd_offered_course_lecturer','acd_offered_course_lecturer.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
        ->join('emp_employee','emp_employee.Employee_Id','=','acd_offered_course_lecturer.Employee_Id')
        ->join('acd_student_krs','acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
        ->Leftjoin('acd_student_khs_nilai_component','acd_student_khs_nilai_component.Krs_Id','=','acd_student_krs.Krs_Id')
        ->select('acd_student.Nim',
                'acd_student.Student_Id',
                'acd_student.Department_Id',
                'acd_student.Full_Name',
                'acd_student.Department_Id',
                'acd_student_khs_nilai_component.*',
                'acd_student_krs.Krs_Id'
          )->where('acd_offered_course.Class_Id', $request->Class_Id)
          ->where('acd_offered_course.Course_Id', $request->Course_Id)
          ->where('acd_student_krs.Class_Id', $request->Class_Id)
          ->where('acd_student_krs.Class_Prog_Id', $request->class_program)
          ->where('acd_student_krs.Course_Id',$request->Course_Id )
          ->where('acd_student_krs.Term_Year_Id',$request->term_year)
          ->where('acd_student_krs.Is_Approved', 1)
          ->groupby('acd_student.Student_Id')->get();
      foreach ($uas as $key){
      $data_krs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->first();
      $cek_presences = DB::table('acd_offered_course_exam_member')
      ->join('acd_offered_course_exam','acd_offered_course_exam_member.Offered_Course_Exam_Id','=','acd_offered_course_exam.Offered_Course_Exam_Id')
      ->where('acd_offered_course_exam.Offered_Course_Id',$request->Offered_Course_id)
      ->where('acd_offered_course_exam.Exam_Type_Id',1)
      ->where('acd_offered_course_exam_member.Student_Id',$key->Student_Id)
      ->select('acd_offered_course_exam_member.Is_Presence')
      ->get();

      $cek_presence = '';
      foreach ($cek_presences as $presence) {
        $cek_presence = $presence->Is_Presence;
      }

      $cek_letter_in = DB::table('acd_grade_department as a')
      ->join('acd_grade_letter as b','a.Grade_Letter_Id','=','b.Grade_Letter_Id')
      ->where('a.Term_Year_Id',$request->term_year)
      ->where('a.Department_Id',$key->Department_Id)
      ->where('b.Grade_Letter','B')
      ->first();
      $cek_letter_out = DB::table('acd_grade_department as a')
      ->join('acd_grade_letter as b','a.Grade_Letter_Id','=','b.Grade_Letter_Id')
      ->where('a.Term_Year_Id',$request->term_year)
      ->where('a.Department_Id',$key->Department_Id)
      ->where('b.Grade_Letter','E')
      ->first();

      //komponen null
      if($data_krs == null){
        if($cek_presence == 1){
          $Uas= $cek_letter_in->Scale_Numeric_Min * ($cek->UAS /100);
          $data_insert = [
            'Krs_Id' => $key->Krs_Id,
            'Uas' => $cek_letter_in->Scale_Numeric_Min,
            'UAS_Score' => $Uas,
            'Total_score' => $Uas,
            'Default_UAS' => true
          ];
          $insertkhs = DB::table('acd_student_khs_nilai_component')->insert($data_insert); 
          // $khsIsi=['Grade_Letter_Id'=>$letter,
          //           'Weight_Value'=>$grade_letter->Weight_Value,
          //           'Sks'=>$krsStudent->Sks,
          //           'Student_Id'=>$key->Student_Id,
          //           'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
          //           'Krs_Id'=>$key->Krs_Id,
          //           'Is_For_Transkrip'=>'1',
          //           'Created_By' => auth()->user()->email,
          //           'Created_Date' => date('Y-m-d H:i:s')
          //               ];
          // $tambahkhs=    DB::table('acd_student_khs')->insertGetId($khsIsi);
          // $khs_Id = DB::getPdo()->lastInsertId();
          // $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($tambahkhs,'')); 
        }else{
          $Uas= $cek_letter_out->Scale_Numeric_Min * ($cek->UAS /100);
          $data_insert = [
            'Krs_Id' => $key->Krs_Id,
            'Uas' => $cek_letter_out->Scale_Numeric_Min,
            'UAS_Score' => $Uas,
            'Total_score' => $Uas,
            'Default_UAS' => true
          ];
          $insertkhs = DB::table('acd_student_khs_nilai_component')->insert($data_insert);
        }
      }
      //komponen not null
      else{
        $now = Date('Y-m-d');
        $enduas = $request->enduas;

        //diluar tanggal pengisian
        if($now > $enduas){
          //presensi
          if($cek_presence == 1){
            if($key->Uas == null){
              $Uas= $cek_letter_in->Scale_Numeric_Min * ($cek->UAS /100);
              $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update(
                [
                  // 'Uas' => $cek_letter_in->Scale_Numeric_Min,
                  // 'UAS_Score' => $cek_letter_in->Scale_Numeric_Min,
                  'Total_score' => $cek_letter_in->Scale_Numeric_Min,
                  'Default_UAS' => true
                ]);
            }else{
              $Tugas_1= $key->Tugas_1Score;
              $Uts= $key->UTS_Score;
              $Uas= $key->UAS_Score;
              $Uas_Remidi= $key->UAS_Remidi_Score;
              $real = ($Uas >= $Uas_Remidi? $Uas : $Uas_Remidi);
              $total = $Tugas_1 + $Uts + $real;
              // $Uas= $cek_letter_in->Scale_Numeric_Min * ($cek->UAS /100);
              $Uas= $key->Total_score;
              $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update(
                [
                  // 'Uas' => $cek_letter_in->Scale_Numeric_Min,
                  // 'UAS_Score' => $Uas,
                  'Total_score' => $total,
                  'Default_UAS' => true
                ]);
            }
          }
          //tidak presensi
          else{
            $Uas= $cek_letter_out->Scale_Numeric_Min * ($cek->UAS /100);
            $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update(
                [
                  // 'Uas' => $cek_letter_out->Scale_Numeric_Min,
                  // 'UAS_Score' => $Uas,
                  'Total_score' => $cek_letter_out->Scale_Numeric_Min,
                  'Default_UAS' => true
                ]);
          }
        }
        //masuk tanggal pengisian
        else{
          if($cek_presence == 1){
            // $Uas= $cek_letter_in->Scale_Numeric_Min * ($cek->UAS /100);
            $Uas= $cek_letter_in->Scale_Numeric_Min;
            $Tugas_1= $data_krs->Tugas_1Score;
            $Tugas_2= $data_krs->Tugas_2Score;
            $Tugas_3= $data_krs->Tugas_3Score;
            $Tugas_4= $data_krs->Tugas_4Score;
            $Tugas_5= $data_krs->Tugas_5Score;
            $Uts= $data_krs->UTS_Score;
            $Uas_Remidi= $data_krs->UAS_Remidi_Score;
            $real = ($Uas >= $Uas_Remidi? $Uas : $Uas_Remidi);
            // $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts+$real; 
            $total=$Uas; 
              if($key->Uas == null){
                $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update(
                [
                  // 'Uas' => $cek_letter_in->Scale_Numeric_Min,
                  // 'UAS_Score' => $Uas,
                  'Total_score' => $total,
                  'Default_UAS' => true
                ]);

                $krsStudent=  DB::table('acd_student_krs')
                ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
                ->where('Krs_Id',$key->Krs_Id)->first();
                $khsStudent=  DB::table('acd_student_khs')
                ->where('Krs_Id',$key->Krs_Id)->get();
                $grade_letter = DB::table('acd_grade_department')
                ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                ->select('acd_grade_letter.Grade_Letter_Id','acd_grade_department.Weight_Value')
                ->where([
                    ['acd_grade_department.Department_Id',$key->Department_Id],
                    ['acd_grade_department.Term_Year_Id',$request->term_year],
                    ['acd_grade_department.Scale_Numeric_Max','>',($totaln-0.01)],
                    ['acd_grade_department.Scale_Numeric_Min','<',($totaln+0.01)]
                    ])
                ->first();
        
                  $letter = "BL";
                if($grade_letter != null){
                    $letter = $grade_letter->Grade_Letter_Id;
                }else{
                    $m['message']='Nilai Huruf belum di setting';
                    $m['status']=1;
                        return $m ;
                }
        
                if($khsStudent->count() <= 0){
                $khsIsi=['Grade_Letter_Id'=>$letter,
                          'Weight_Value'=>$grade_letter->Weight_Value,
                          'Sks'=>$krsStudent->Sks,
                          'Student_Id'=>$key->Student_Id,
                          'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
                          'Krs_Id'=>$key->Krs_Id,
                          'Is_For_Transkrip'=>'1',
                          'Created_By' => auth()->user()->email,
                          'Created_Date' => date('Y-m-d H:i:s')
                          ];
                $tambahkhs=    DB::table('acd_student_khs')->insertGetId($khsIsi);
                $khs_Id = DB::getPdo()->lastInsertId();
                $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($tambahkhs,''));  
                }else{
                  $khsIsi=['Grade_Letter_Id'=>$letter,
                            'Weight_Value'=>$grade_letter->Weight_Value,
                            'Sks'=>$krsStudent->Sks,
                            'Student_Id'=>$key->Student_Id,
                            'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
                            'Krs_Id'=>$key->Krs_Id,
                            'Is_For_Transkrip'=>'1',
                            'Created_By' => auth()->user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                            ];
                  $khs_idd= DB::table('acd_student_khs')->where('Krs_Id',$key->Krs_Id)->first();
                  $tambahkhs= DB::table('acd_student_khs')->where('Khs_Id',$khs_idd->Khs_Id)->update($khsIsi);
                  $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_idd->Khs_Id,''));
                }
              } else{
                $Uas= $data_krs->UAS_Score;
                $Tugas_1= $data_krs->Tugas_1Score;
                $Tugas_2= $data_krs->Tugas_2Score;
                $Tugas_3= $data_krs->Tugas_3Score;
                $Tugas_4= $data_krs->Tugas_4Score;
                $Tugas_5= $data_krs->Tugas_5Score;
                $Uts= $data_krs->UTS_Score;
                $Uas_Remidi= $data_krs->UAS_Remidi_Score;
                $real = ($Uas >= $Uas_Remidi? $Uas : $Uas_Remidi);
                $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts+$real;
                // $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update(
                // [
                //   'Uas' => $Uas,
                //   'UAS_Score' => $Uas,
                //   'Total_score' => $total
                // ]);

                $krsStudent=  DB::table('acd_student_krs')
                ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
                ->where('Krs_Id',$key->Krs_Id)->first();
                $khsStudent=  DB::table('acd_student_khs')
                ->where('Krs_Id',$key->Krs_Id)->get();
                  
                $grade_letter = DB::table('acd_grade_department')
                ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                ->select('acd_grade_letter.Grade_Letter_Id','acd_grade_department.Weight_Value')
                ->where([
                    ['acd_grade_department.Department_Id',$key->Department_Id],
                    ['acd_grade_department.Term_Year_Id',$request->term_year],
                    ['acd_grade_department.Scale_Numeric_Max','>',($total-0.01)],
                    ['acd_grade_department.Scale_Numeric_Min','<',($total+0.01)]
                    ])
                ->first();
        
                  $letter = "BL";
                if($grade_letter != null){
                    $letter = $grade_letter->Grade_Letter_Id;
                }else{
                    $m['message']='Nilai Huruf belum di setting';
                    $m['status']=1;
                        return $m ;
                }
                if($khsStudent->count() <= 0){
                  $khsIsi=['Grade_Letter_Id'=>$letter,
                            'Weight_Value'=>$grade_letter->Weight_Value,
                            'Sks'=>$krsStudent->Sks,
                            'Student_Id'=>$key->Student_Id,
                            'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
                            'Krs_Id'=>$key->Krs_Id,
                            'Is_For_Transkrip'=>'1',
                            'Created_By' => auth()->user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                            ];
                  $tambahkhs=    DB::table('acd_student_khs')->insertGetId($khsIsi);
                  $khs_Id = DB::getPdo()->lastInsertId();
                  $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($tambahkhs,'')); 
                }else{
                  $khsIsi=['Grade_Letter_Id'=>$letter,
                            'Weight_Value'=>$grade_letter->Weight_Value,
                            'Sks'=>$krsStudent->Sks,
                            'Student_Id'=>$key->Student_Id,
                            'Bnk_Value'=>$grade_letter->Weight_Value *$krsStudent->Sks,
                            'Krs_Id'=>$key->Krs_Id,
                            'Is_For_Transkrip'=>'1',
                            'Created_By' => auth()->user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                            ];
                  $khs_idd= DB::table('acd_student_khs')->where('Krs_Id',$key->Krs_Id)->first();
                  $tambahkhs= DB::table('acd_student_khs')->where('Khs_Id',$khs_idd->Khs_Id)->update($khsIsi);
                  $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_idd->Khs_Id,'')); 
                }
              }   
          }
          else{
            $Uas= $cek_letter_out->Scale_Numeric_Min * ($cek->UAS /100);
            $Tugas_1= $data_krs->Tugas_1Score;
            $Tugas_2= $data_krs->Tugas_2Score;
            $Tugas_3= $data_krs->Tugas_3Score;
            $Tugas_4= $data_krs->Tugas_4Score;
            $Tugas_5= $data_krs->Tugas_5Score;
            $Uts= $data_krs->UTS_Score;
            $Uas_Remidi= $data_krs->UAS_Remidi_Score;
            $real = ($Uas >= $Uas_Remidi? $Uas : $Uas_Remidi);
            $total=$Tugas_1+$Tugas_2+$Tugas_3+$Tugas_4+$Tugas_5+$Uts+$real;   
      
              if($key->Uas == null){
                $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update(
                [
                  'Uas' => $cek_letter_out->Scale_Numeric_Min,
                  'UAS_Score' => $Uas,
                  'Total_score' => $total,
                  'Default_UAS' => true
                ]);
              }  
          }
        }
        
        
      }
      // if($data_krs == null){
      //   $insertkhs = DB::table('acd_student_khs_nilai_component')->insert($data_insert);        
      // }else{
      //   if($key->Uts == null){
      //     $insertkhs = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update($data_insert);
      //   }
      // } 
    }
    return response()->json([
                'status' => 200,
                'message' => 'sukses.',
            ]);
    }
  }

  public function publishNilai(Request $request)
  {
    // dd(1);
    //for
    $courses = DB::table('acd_course')->where([['Course_Id',$request->Course_Id],['Department_Id',$request->department]])->first();
    if($courses->Course_Type_Id == 12){
      for ($double=0; $double < 2; $double++) { 
        $uts=  DB::table('acd_offered_course')
            ->join('acd_offered_course_lecturer','acd_offered_course_lecturer.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
            ->join('emp_employee','emp_employee.Employee_Id','=','acd_offered_course_lecturer.Employee_Id')
            ->join('acd_student_krs','acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
            ->join('acd_student_khs_nilai_component','acd_student_khs_nilai_component.Krs_Id','=','acd_student_krs.Krs_Id')
            ->select('acd_student.Nim',
                    'acd_student.Student_Id',
                    'acd_student.Full_Name',
                    'acd_student.Department_Id',
                    'acd_student.Entry_Year_Id',
                    'acd_student_khs_nilai_component.*',
                    'acd_offered_course.Offered_Course_Id',
                    'acd_student_krs.Is_Remediasi',
                    'acd_student_krs.Krs_Id'
              )
              // ->where('acd_offered_course.Class_Id', $request->Class_Id)
              // ->where('acd_offered_course.Course_Id', $request->Course_Id)
              ->where('acd_student_krs.Class_Id', $request->Class_Id)
              ->where('acd_student_krs.Class_Prog_Id', $request->class_program)
              ->where('acd_student_krs.Course_Id',$request->Course_Id )
              ->where('acd_student_krs.Term_Year_Id',$request->term_year)
              ->where('acd_student_krs.Is_Approved', 1)
              ->groupby('acd_student.Student_Id')->get();
              // dd($uts);
        foreach ($uts as $key)if($key->Total_score != null){
          // dd($key);
          $data_khs = DB::table('acd_student_khs')->where('Krs_Id',$key->Krs_Id)->first();
          $data_componen = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->first();
          $weight_value = DB::table('acd_grade_department')
                          ->where('Department_Id',$request->department)
                          ->where('Term_Year_Id',$request->term_year)
                          ->where('Scale_Numeric_Max','>=',$key->Total_score)
                          ->where('Scale_Numeric_Min','<=',$key->Total_score)->first();
          // dd($weight_value);

          $semester = DB::table('acd_curriculum_entry_year')
                ->join('acd_course_curriculum','acd_curriculum_entry_year.Curriculum_Id','=','acd_course_curriculum.Curriculum_Id')
                ->where('acd_curriculum_entry_year.Term_Year_Id',$request->term_year)
                ->where('acd_curriculum_entry_year.Department_Id',$request->department)
                ->where('acd_curriculum_entry_year.Class_Prog_Id',$request->class_program)
                ->where('acd_curriculum_entry_year.Entry_Year_Id',$key->Entry_Year_Id)
                ->where('acd_course_curriculum.Course_Id',$request->Course_Id)
                ->select('acd_curriculum_entry_year.Curriculum_Id','acd_course_curriculum.Study_Level_Id')
                ->first();
                // dd($semester);

          $bnk_value = $request->Transcript_Sks * $weight_value->Weight_Value;

          $acd_course = DB::table('acd_course')->where('Course_Id',$request->Course_Id)->first();
          
          if($data_componen == null){
            $now = Date('Y-m-d');
            $enduas = $request->enduas;
            if($now > $enduas){
              $cek_letter_in = DB::table('acd_grade_department as a')
                ->join('acd_grade_letter as b','a.Grade_Letter_Id','=','b.Grade_Letter_Id')
                ->where('a.Term_Year_Id',$request->term_year)
                ->where('a.Department_Id',$key->Department_Id)
                ->where('b.Grade_Letter','B')
                ->first();
              $cek_letter_out = DB::table('acd_grade_department as a')
                ->join('acd_grade_letter as b','a.Grade_Letter_Id','=','b.Grade_Letter_Id')
                ->where('a.Term_Year_Id',$request->term_year)
                ->where('a.Department_Id',$key->Department_Id)
                ->where('b.Grade_Letter','E')
                ->first();
              //presensi
              if($cek_presence == 1){
                $update_component = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update(
                  [
                    'Total_score' => $cek_letter_in->Scale_Numeric_Max
                  ]);
              }else{
                $update_component = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$key->Krs_Id)->update(
                  [
                    'Total_score' => $cek_letter_out->Scale_Numeric_Max
                  ]);
              }
            }
          }else{
            if($data_khs == null){
              $insertkhs = DB::table('acd_student_khs')->insertGetId(
                [
                'Krs_Id' => $key->Krs_Id,
                'Student_Id' => $key->Student_Id,
                'Grade_Letter_Id'=> ($key->Total_score == null ? '':$weight_value->Grade_Letter_Id),
                'Sks' => $request->Transcript_Sks,
                'Weight_Value' => ($key->Total_score == null ? '':$weight_value->Weight_Value),
                'Is_For_Transkrip'=>$request->Is_For_Transcript,
                'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                'Created_By' => auth()->user()->email,
                'Created_Date' => date('Y-m-d H:i:s')
                ]);
              
                $khs_Id = DB::getPdo()->lastInsertId();
                $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_Id,''));

                $check_transcript_final = DB::table('acd_transcript_final')->where([['Student_Id',$key->Student_Id],['Course_Code',$acd_course->Course_Code]])->get();
                $Grade_Letter = DB::table('acd_grade_letter')->where('Grade_Letter_Id',$weight_value->Grade_Letter_Id)->first();
                if(count($check_transcript_final) == 0){
                  $insert_transcript_final = DB::table('acd_transcript_final')->insert(
                    [
                      'Student_Id' => $key->Student_Id,
                      'Course_Code' => $acd_course->Course_Code,
                      'Course_Name' => $acd_course->Course_Name,
                      'Course_Name_Eng' => $acd_course->Course_Name_Eng,
                      'Grade_Letter'=> ($key->Total_score == null ? '':$Grade_Letter->Grade_Letter),
                      'Term_Id'=> ($semester ? $semester->Study_Level_Id:''),
                      'Sks' => $request->Transcript_Sks,
                      'Weight_Value' => ($key->Total_score == null ? '':$weight_value->Weight_Value),
                      'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                      'Created_By' => auth()->user()->email,
                      'Created_Date' => date('Y-m-d H:i:s')
                    ]);
                }else{
                  $insert_transcript_final = DB::table('acd_transcript_final')
                  ->where([['Student_Id',$key->Student_Id],['Course_Code',$acd_course->Course_Code]])
                  ->update(
                    [
                      'Course_Code' => $acd_course->Course_Code,
                      'Course_Name' => $acd_course->Course_Name,
                      'Course_Name_Eng' => $acd_course->Course_Name_Eng,
                      'Grade_Letter'=> ($key->Total_score == null ? '':$Grade_Letter->Grade_Letter),
                      'Term_Id'=> ($semester ? $semester->Study_Level_Id:''),
                      'Sks' => $request->Transcript_Sks,
                      'Weight_Value' => ($key->Total_score == null ? '':$weight_value->Weight_Value),
                      'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                      'Created_By' => auth()->user()->email,
                      'Created_Date' => date('Y-m-d H:i:s')
                    ]);
                }
            }else{
              $khs_id = DB::table('acd_student_khs')->where('Krs_Id',$key->Krs_Id)->first();
              $insertkhs = DB::table('acd_student_khs')->where('Khs_Id',$khs_id->Khs_Id)->update(
                [
                'Grade_Letter_Id'=>$weight_value->Grade_Letter_Id,
                'Sks' => $request->Transcript_Sks,
                'Weight_Value' => $weight_value->Weight_Value,
                'Is_For_Transkrip'=>$request->Is_For_Transcript,
                'Bnk_Value'=>$weight_value->Weight_Value * $request->Transcript_Sks,
                'Is_Published'=>1
                ]);
              $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_id->Khs_Id,''));

              $check_transcript_final = DB::table('acd_transcript_final')->where([['Student_Id',$key->Student_Id],['Course_Code',$acd_course->Course_Code]])->get();
              // dd($check_transcript_final);
              $Grade_Letter = DB::table('acd_grade_letter')->where([['Grade_Letter_Id',$weight_value->Grade_Letter_Id]])->first();
              if(count($check_transcript_final) == 0){
                $insert_transcript_final = DB::table('acd_transcript_final')->insert(
                  [
                    'Student_Id' => $key->Student_Id,
                    'Course_Code' => $acd_course->Course_Code,
                    'Course_Name' => $acd_course->Course_Name,
                    'Course_Name_Eng' => $acd_course->Course_Name_Eng,
                    'Grade_Letter'=> ($key->Total_score == null ? '':$Grade_Letter->Grade_Letter),
                      'Term_Id'=> ($semester ? $semester->Study_Level_Id:''),
                    'Sks' => $request->Transcript_Sks,
                    'Weight_Value' => ($key->Total_score == null ? '':$weight_value->Weight_Value),
                    'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')
                  ]);
              }else{
                $insert_transcript_final = DB::table('acd_transcript_final')
                ->where([['Student_Id',$key->Student_Id],['Course_Code',$acd_course->Course_Code]])
                ->update(
                  [
                    'Course_Code' => $acd_course->Course_Code,
                    'Course_Name' => $acd_course->Course_Name,
                    'Course_Name_Eng' => $acd_course->Course_Name_Eng,
                    'Grade_Letter'=> ($key->Total_score == null ? '':$Grade_Letter->Grade_Letter),
                      'Term_Id'=> ($semester ? $semester->Study_Level_Id:''),
                    'Sks' => $request->Transcript_Sks,
                    'Weight_Value' => ($key->Total_score == null ? '':$weight_value->Weight_Value),
                    'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')
                  ]);
              }
            }
          }
        }
      }
    }else{
      for ($double=0; $double < 2; $double++) { 
        $uts=  DB::table('acd_offered_course')
            ->join('acd_offered_course_lecturer','acd_offered_course_lecturer.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
            ->join('emp_employee','emp_employee.Employee_Id','=','acd_offered_course_lecturer.Employee_Id')
            ->join('acd_student_krs','acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
            ->join('acd_student_khs_nilai_detail','acd_student_khs_nilai_detail.Krs_Id','=','acd_student_krs.Krs_Id')
            ->select('acd_student.Nim',
                    'acd_student.Student_Id',
                    'acd_student.Full_Name',
                    'acd_student.Department_Id',
                    'acd_student.Entry_Year_Id',
                    'acd_student_khs_nilai_detail.*',
                    'acd_offered_course.Offered_Course_Id',
                    'acd_student_krs.Is_Remediasi',
                    'acd_student_krs.Krs_Id',
                    // DB::raw('SUM(acd_student_khs_nilai_detail.Score) as Total_Score'),
                    // DB::raw('(SELECT SUM(acd_student_khs_nilai_detail.score) as Total_Score FROM acd_student_khs_nilai_detail WHERE acd_student_khs_nilai_detail.Krs_Id = acd_student_krs.Krs_Id ) as Total_score')
                    DB::raw('(select sum(score) from (
                      SELECT DISTINCT(Student_Khs_Item_Bobot_Id),score
                      FROM acd_student_khs_nilai_detail 
                      WHERE acd_student_khs_nilai_detail.Krs_Id = acd_student_krs.Krs_Id )
                      as Total_Score) as Total_score')
              )
              // ->where('acd_offered_course.Class_Id', $request->Class_Id)
              // ->where('acd_offered_course.Course_Id', $request->Course_Id)
              ->where('acd_student_krs.Class_Id', $request->Class_Id)
              ->where('acd_student_krs.Class_Prog_Id', $request->class_program)
              ->where('acd_student_krs.Course_Id',$request->Course_Id )
              ->where('acd_student_krs.Term_Year_Id',$request->term_year)
              ->where('acd_student_krs.Is_Approved', 1)
              ->groupby('acd_student.Student_Id')->get();
              // dd($uts);
        foreach ($uts as $key)if($key->Total_score != null){
          // dd($key);
          $data_khs = DB::table('acd_student_khs')->where('Krs_Id',$key->Krs_Id)->first();
          $data_componen = DB::table('acd_student_khs_nilai_detail')->where('Krs_Id',$key->Krs_Id)->first();
          $weight_value = DB::table('acd_grade_department')
                          ->where([
                            ['acd_grade_department.Department_Id',$request->department],
                            ['acd_grade_department.Term_Year_Id',$key->Entry_Year_Id],
                            ['acd_grade_department.Scale_Numeric_Max','>',($key->Total_score-0.001)],
                            ['acd_grade_department.Scale_Numeric_Min','<',($key->Total_score+0.001)]
                          ])
                          ->first();
          $weight_value = DB::select("select * from acd_grade_department 
              where Department_Id = ".$request->department."
              and Entry_Year_Id = ".$key->Entry_Year_Id."
              and Scale_Numeric_Max >= ".(number_format($key->Total_score,2)-0.001)."
              and Scale_Numeric_Min <= ".(number_format($key->Total_score,2)+0.001)."");

          $semester = DB::table('acd_curriculum_entry_year')
                ->join('acd_course_curriculum','acd_curriculum_entry_year.Curriculum_Id','=','acd_course_curriculum.Curriculum_Id')
                ->where('acd_curriculum_entry_year.Term_Year_Id',$request->term_year)
                ->where('acd_curriculum_entry_year.Department_Id',$request->department)
                ->where('acd_curriculum_entry_year.Class_Prog_Id',$request->class_program)
                ->where('acd_curriculum_entry_year.Entry_Year_Id',$key->Entry_Year_Id)
                ->where('acd_course_curriculum.Course_Id',$request->Course_Id)
                ->select('acd_curriculum_entry_year.Curriculum_Id','acd_course_curriculum.Study_Level_Id')
                ->first();
          if(!$weight_value){
          // dd($key,$weight_value,$key->Total_score,number_format($key->Total_score,2));
            // dd($key->Total_score,$key,$weight_value);
          }
          $bnk_value = $request->Transcript_Sks * $weight_value[0]->Weight_Value;

          $acd_course = DB::table('acd_course')->where('Course_Id',$request->Course_Id)->first();
          
          if($data_componen == null){
          }else{
            if($data_khs == null){
              $insertkhs = DB::table('acd_student_khs')->insertGetId(
                [
                'Krs_Id' => $key->Krs_Id,
                'Student_Id' => $key->Student_Id,
                'Grade_Letter_Id'=> ($key->Total_score == null ? '':$weight_value[0]->Grade_Letter_Id),
                'Sks' => $request->Transcript_Sks,
                'Weight_Value' => ($key->Total_score == null ? '':$weight_value[0]->Weight_Value),
                'Is_For_Transkrip'=>$request->Is_For_Transcript,
                'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                'Created_By' => auth()->user()->email,
                'Created_Date' => date('Y-m-d H:i:s')
                ]);
              
                $khs_Id = DB::getPdo()->lastInsertId();
                $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_Id,''));

                $check_transcript_final = DB::table('acd_transcript_final')->where([['Student_Id',$key->Student_Id],['Course_Code',$acd_course->Course_Code]])->get();
                $Grade_Letter = DB::table('acd_grade_letter')->where('Grade_Letter_Id',$weight_value[0]->Grade_Letter_Id)->first();
                if(count($check_transcript_final) == 0){
                  // $insert_transcript_final = DB::table('acd_transcript_final')->insert(
                  //   [
                  //     'Student_Id' => $key->Student_Id,
                  //     'Course_Code' => $acd_course->Course_Code,
                  //     'Course_Name' => $acd_course->Course_Name,
                  //     'Course_Name_Eng' => $acd_course->Course_Name_Eng,
                  //     'Grade_Letter'=> ($key->Total_score == null ? '':$Grade_Letter->Grade_Letter),
                  //     'Term_Id'=> ($semester ? $semester->Study_Level_Id:''),
                  //     'Sks' => $request->Transcript_Sks,
                  //     'Weight_Value' => ($key->Total_score == null ? '':$weight_value[0]->Weight_Value),
                  //     'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                  //     'Created_By' => auth()->user()->email,
                  //     'Created_Date' => date('Y-m-d H:i:s')
                  //   ]);
                }else{
                  // $insert_transcript_final = DB::table('acd_transcript_final')
                  // ->where([['Student_Id',$key->Student_Id],['Course_Code',$acd_course->Course_Code]])
                  // ->update(
                  //   [
                  //     'Course_Code' => $acd_course->Course_Code,
                  //     'Course_Name' => $acd_course->Course_Name,
                  //     'Course_Name_Eng' => $acd_course->Course_Name_Eng,
                  //     'Grade_Letter'=> ($key->Total_score == null ? '':$Grade_Letter->Grade_Letter),
                  //     'Term_Id'=> ($semester ? $semester->Study_Level_Id:''),
                  //     'Sks' => $request->Transcript_Sks,
                  //     'Weight_Value' => ($key->Total_score == null ? '':$weight_value[0]->Weight_Value),
                  //     'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                  //     'Created_By' => auth()->user()->email,
                  //     'Created_Date' => date('Y-m-d H:i:s')
                  //   ]);
                }
            }else{
              $khs_id = DB::table('acd_student_khs')->where('Krs_Id',$key->Krs_Id)->first();
              $insertkhs = DB::table('acd_student_khs')->where('Khs_Id',$khs_id->Khs_Id)->update(
                [
                'Grade_Letter_Id'=>$weight_value[0]->Grade_Letter_Id,
                'Sks' => $request->Transcript_Sks,
                'Weight_Value' => $weight_value[0]->Weight_Value,
                'Is_For_Transkrip'=>$request->Is_For_Transcript,
                'Bnk_Value'=>$weight_value[0]->Weight_Value * $request->Transcript_Sks,
                'Is_Published'=>1
                ]);
              $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_id->Khs_Id,''));

              $check_transcript_final = DB::table('acd_transcript_final')->where([['Student_Id',$key->Student_Id],['Course_Code',$acd_course->Course_Code]])->get();
              $Grade_Letter = DB::table('acd_grade_letter')->where([['Grade_Letter_Id',$weight_value[0]->Grade_Letter_Id]])->first();
              if(count($check_transcript_final) == 0){
                // $insert_transcript_final = DB::table('acd_transcript_final')->insert(
                //   [
                //     'Student_Id' => $key->Student_Id,
                //     'Course_Code' => $acd_course->Course_Code,
                //     'Course_Name' => $acd_course->Course_Name,
                //     'Course_Name_Eng' => $acd_course->Course_Name_Eng,
                //     'Grade_Letter'=> ($key->Total_score == null ? '':$Grade_Letter->Grade_Letter),
                //       'Term_Id'=> ($semester ? $semester->Study_Level_Id:''),
                //     'Sks' => $request->Transcript_Sks,
                //     'Weight_Value' => ($key->Total_score == null ? '':$weight_value[0]->Weight_Value),
                //     'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                //     'Created_By' => auth()->user()->email,
                //     'Created_Date' => date('Y-m-d H:i:s')
                //   ]);
              }else{
                // $insert_transcript_final = DB::table('acd_transcript_final')
                // ->where([['Student_Id',$key->Student_Id],['Course_Code',$acd_course->Course_Code]])
                // ->update(
                //   [
                //     'Course_Code' => $acd_course->Course_Code,
                //     'Course_Name' => $acd_course->Course_Name,
                //     'Course_Name_Eng' => $acd_course->Course_Name_Eng,
                //     'Grade_Letter'=> ($key->Total_score == null ? '':$Grade_Letter->Grade_Letter),
                //       'Term_Id'=> ($semester ? $semester->Study_Level_Id:''),
                //     'Sks' => $request->Transcript_Sks,
                //     'Weight_Value' => ($key->Total_score == null ? '':$weight_value[0]->Weight_Value),
                //     'Bnk_Value'=>($key->Total_score == null ? '':$bnk_value),
                //     'Created_By' => auth()->user()->email,
                //     'Created_Date' => date('Y-m-d H:i:s')
                //   ]);
              }
            }
          }
        }
      }
    }
    //endfor
    return response()->json([
                'status' => 200,
                'message' => 'sukses.',
            ]);
  }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function storeNilaiPrak(Request $request)
    {
        if ($request->hitung) {
            if ($request->hitungKrs) {
                for ($i=0; $i < count($request->hitungKrs); $i++) {
                    // dd($request->hitungKrs[$i],$request->total[$i],$request->hitung);
                    $this->khsSimpan($request->hitungKrs[$i],$request->total[$i],$request->hitung);
                }
                Alert::success('Berhasil','Anda berhasil Menyimpan Perubahan dan Menghitung Khs');
                  return redirect()->back();
            }
        }else {
          // foreach ($request->hitungKrs as $keys) {
          //   // dd($keys);
          //   DB::table('acd_student_khs_nilai_detail')->where('Krs_Id',$keys)->delete();
          // }
          
          $oci_check = DB::table('acd_offered_course as a')
          ->join('acd_course as b','a.Course_Id','=','b.Course_Id')
          ->where('Offered_Course_id',$request->oci)
          ->select('b.Course_Type_Id')
          ->first();
            if ($request->bobot) {
                $cek = count($request->bobot);
                for ($i=0; $i < $cek; $i++) {
                  if ($request->status[$i] ==0) {
                      if($request->value[$i]>0 || $request->value[$i]!= null){
                        // dd($request->status[$i],$request->value[$i],$request->all());
                        $krs_student = DB::table('acd_student_krs')
                        ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
                        ->where('Krs_Id',$request->krs[$i])
                        ->select('acd_student.*')
                        ->first();
                        // dd($krs_student,$request->bobot[$i],$request->all());
                          // $bobot = DB::table('acd_student_khs_item_bobot')
                          // ->where('Entry_Year_Id',$krs_student->Entry_Year_Id)
                          // ->where('Department_Id',$krs_student->Department_Id)
                          // ->where('Course_Type_Id',$oci_check->Course_Type_Id)
                          // ->first();
                          $cek_data = DB::table('acd_student_khs_nilai_detail')
                            ->where([
                                ['Krs_Id', $request->krs[$i]],
                                ['Student_Khs_Item_Bobot_Id', $request->Bobot_id[$i]],
                            ])
                            ->get();

                          $data=[
                            'Krs_Id'=>$request->krs[$i],
                            'Student_Khs_Item_Bobot_Id'=>$request->Bobot_id[$i],
                            'Value'=>$request->value[$i],
                            'Score'=>($request->value[$i] * $request->bobot[$i] )/100
                          ];
                          if(count($cek_data) > 1){
                            $cek_data = DB::table('acd_student_khs_nilai_detail')
                            ->where([
                                ['Krs_Id', $request->krs[$i]],
                                ['Student_Khs_Item_Bobot_Id', $request->Bobot_id[$i]],
                            ])
                            ->delete();
                            DB::table('acd_student_khs_nilai_detail')->insert($data);
                          }else{
                              DB::table('acd_student_khs_nilai_detail')->insert($data);
                          }
                      }

                  }elseif ($request->status[$i] !=0){
                    if($request->value[$i]>0 || $request->value[$i]!= null){
                        $data=[
                            'Value'=>$request->value[$i],
                           'Score'=>(($request->value[$i] * $request->bobot[$i] )/100)
                        ];

                        $cek_data = DB::table('acd_student_khs_nilai_detail')
                            ->where([
                                ['Krs_Id', $request->krs[$i]],
                                ['Student_Khs_Item_Bobot_Id', $request->Bobot_id[$i]],
                            ])
                            ->get();

                            if(count($cek_data) > 1){
                              // dd($request->Bobot_id[$i]);
                              $cek_data = DB::table('acd_student_khs_nilai_detail')
                              ->where([
                                  ['Krs_Id', $request->krs[$i]],
                                  ['Student_Khs_Item_Bobot_Id', $request->Bobot_id[$i]],
                              ])
                              ->delete();

                              $data = [
                                  'Krs_Id' => $request->krs[$i],
                                  'Student_Khs_Item_Bobot_Id' => $request->Bobot_id[$i],
                                  'Value' => $request->value[$i],
                                  'Score' => ($request->value[$i] * $request->bobot[$i]) / 100
                              ];
                              // dd($data);
                              DB::table('acd_student_khs_nilai_detail')->insert($data);
                          }else{
                              DB::table('acd_student_khs_nilai_detail')->where('Student_khs_nilai_detail_id', $request->status[$i])->update($data);
                          }
                    }else{
                      $test = DB::table('acd_student_khs_nilai_detail')->where('Student_khs_nilai_detail_id', $request->status[$i])->delete();
                      // dd($request->status[$i]);
                    }

                  }else {
                    Alert::error('eror','Data Aneh');
                  }
                }

                // $cek = DB::table('acd_student_khs_nilai_detail')->get();
                // dd($cek);

                $als = 0;
                foreach ($request->hitungKrs as $keys) {
                  $cek_krs=DB::table('acd_student_khs_nilai_detail')->where('Krs_Id',$keys)->get();
                  // dd($cek_krs);
                  if($cek_krs->count() > 0){
                    $data_krs = DB::table('acd_student_krs as a')
                    ->join('acd_student as  b','a.Student_Id','=','b.Student_Id')->where('a.Krs_Id',$cek_krs[0]->Krs_Id)
                    ->join('acd_course as c','a.Course_Id','=','c.Course_Id')
                    ->join('acd_course_type as d','c.Course_Type_Id','=','d.Course_Type_Id')
                    ->select('a.*','b.Entry_Year_Id','b.Department_Id','d.Course_Type_Id')->first();
                  // dd($data_krs);

                    $bobot=DB::table('acd_student_khs_item_bobot')
                    ->where([
                      ['Department_Id',$data_krs->Department_Id],
                      ['Entry_Year_Id',$data_krs->Entry_Year_Id],
                      // ['Term_Year_Id',$data_krs->Term_Year_Id],
                      ['Course_Type_Id',$data_krs->Course_Type_Id]
                    ])
                    ->orderby('Order_Id','asc')
                    ->get();

                    $aoc = DB::table('acd_offered_course as a')
                    ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','a.Class_Prog_Id')
                    ->leftjoin('mstr_department','mstr_department.Department_Id','=','a.Department_Id')
                    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','a.Term_Year_Id')
                    ->leftjoin('acd_course','acd_course.Course_Id','=','a.Course_Id')
                    ->join('mstr_class','mstr_class.Class_Id','=','a.Class_Id')
                    ->where('a.Offered_course_Id',$request->oci)->first();

                    $det = DB::table('acd_student_krs')
                    ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
                    ->join('mstr_class','mstr_class.Class_Id','=','acd_student_krs.Class_Id')
                    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student_krs.Class_Prog_Id')
                    ->join('acd_course','acd_course.Course_Id','=','acd_student_krs.Course_Id')
                    ->join('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
                    ->select('mstr_class.Class_Name',
                        'mstr_class_program.Class_Program_Name',
                        'acd_course.Course_Code',
                        'mstr_department.Department_Name','acd_student.*','acd_student_krs.Krs_Id')
                    ->where('acd_student_krs.Class_Id', $aoc->Class_Id)
                    ->where('acd_student_krs.Class_Prog_Id', $data_krs->Class_Prog_Id)
                    ->where('acd_student_krs.Course_Id', $aoc->Course_Id)
                    ->where('acd_student_krs.Term_Year_Id', $data_krs->Term_Year_Id)
                    ->where('mstr_department.Department_Id', $data_krs->Department_Id)
                    ->where('acd_student_krs.Krs_Id', $cek_krs[0]->Krs_Id)
                    ->get();

                    $data = [];
                    $i = 0;
                    foreach ($det as $row){
                        $data[$i]['Nim'] = $row->Nim;
                        $data[$i]['Full_Name'] = $row->Full_Name;
                        $data[$i]['Krs_Id'] = $row->Krs_Id;
                        $ii = 0;
                        $total=0;
                        // dd($bobot);
                        foreach ($bobot  as $col) {
                            $cek_khs=DB::table('acd_student_khs_nilai_detail')->where([['Krs_Id', $row->Krs_Id],['Student_Khs_Item_Bobot_Id',$col->Student_Khs_Item_Bobot_Id]])->first();
                            // dd($cek_khs,$col,$row);
                            $data[$i]['isi'][$ii]['Bobot'] = $col->Bobot;
                            $data[$i]['isi'][$ii]['Value'] =  ($cek_khs != null? $cek_khs->Value:null);
                            $data[$i]['isi'][$ii]['Bobot_id'] = $col->Student_Khs_Item_Bobot_Id;
                            $data[$i]['isi'][$ii]['status'] = ($cek_khs != null? $cek_khs->Student_khs_nilai_detail_id:'0');
                            $data[$i]['isi'][$ii]['Score'] = ($cek_khs != null? $cek_khs->Score:'-');
                            $ii++;
                            $total += ($cek_khs != null? $cek_khs->Score:0);
                        }
                        $data[$i]['Total']=$total;
                        $letter = "BL";
                        // if($total != null){
                            $grade_letter = DB::table('acd_grade_department')
                            ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                            ->select('acd_grade_letter.Grade_Letter')
                            ->where([
                                ['acd_grade_department.Department_Id',$data_krs->Department_Id],
                                ['acd_grade_department.Entry_Year_Id',$data_krs->Entry_Year_Id],
                                ['acd_grade_department.Scale_Numeric_Max','>',($total-0.01)],
                                ['acd_grade_department.Scale_Numeric_Min','<',($total+0.01)]
                              ])
                            ->first();
                            // dd($grade_letter,$total);
                            if($grade_letter != null){
                                $letter = $grade_letter->Grade_Letter;
                            }else{
                                $letter = "Belum Disetting";
                            }
                            // dd($grade_letter);

                            $acey = DB::table('acd_curriculum_entry_year')
                            ->where('Term_Year_Id',$data_krs->Term_Year_Id)
                            ->where('Department_Id',$data_krs->Department_Id)
                            ->where('Class_Prog_Id',$data_krs->Class_Prog_Id)
                            ->where('Entry_Year_Id',$row->Entry_Year_Id)
                            ->select('Curriculum_Id')
                            ->first();
                            $acc = DB::table('acd_course_curriculum')
                            ->where('Department_Id',$data_krs->Department_Id)
                            ->where('Class_Prog_Id',$data_krs->Class_Prog_Id)
                            ->where('Curriculum_Id',$acey->Curriculum_Id)
                            ->where('Course_Id',$data_krs->Course_Id)
                            ->select('Transcript_Sks','Is_For_Transcript')
                            ->first();
                            // $weight_value = DB::table('acd_grade_department')
                            //               ->where('Department_Id',$data_krs->Department_Id)
                            //               ->where('Entry_Year_Id',$data_krs->Entry_Year_Id)
                            //               ->where('Scale_Numeric_Max','>=',$total)
                            //               ->where('Scale_Numeric_Min','<=',$total)
                            //   ->first();
                              $weight_value = DB::select("
                                SELECT*FROM acd_grade_department
                                WHERE department_id=$data_krs->Department_Id
                                AND entry_year_id=$data_krs->Entry_Year_Id
                                AND Scale_Numeric_Min<=$total
                                AND Scale_Numeric_Max>=$total
                                ");
                              if(count($weight_value) == 0){
                                // Alert::warning('Berhasil','berhasil Menyimpan, Grade Nilai Belum diseting');
                                return redirect()->back();
                              }
                              $weight_value = $weight_value[0];
                              $bnk_value = $acc->Transcript_Sks * $weight_value->Weight_Value;
                              $data[$i]['Grade']=$letter;
                              $data[$i]['Grade_Id']=$weight_value->Grade_Letter_Id;
                              $data[$i]['weight_value']=$weight_value->Weight_Value;
                              $data[$i]['Bnk_Value']=$bnk_value;
                              $data[$i]['Transcript_Sks']=$acc->Transcript_Sks;
                              $data[$i]['Is_For_Transcript']=$acc->Is_For_Transcript;                        
                            // }
                              // dd($data);
                        
                        $data_khs = DB::table('acd_student_khs')->where('Krs_Id',$row->Krs_Id)->first();
                        if($data_khs == null){ 
                          $insertkhs = DB::table('acd_student_khs')->insertGetId(
                            [
                            'Krs_Id' => $row->Krs_Id,
                            'Student_Id' => $row->Student_Id,
                            // 'Grade_Letter_Id'=> ($data[0]['Total'] == null ? '':$data[0]['Grade_Id']),
                            'Grade_Letter_Id'=>$data[0]['Grade_Id'],
                            'Sks' => $data[0]['Transcript_Sks'],
                            // 'Weight_Value' => ($data[0]['Total'] == null ? '':$data[0]['weight_value']),
                            'Weight_Value' => $data[0]['weight_value'],
                            'Is_For_Transkrip'=>$data[0]['Is_For_Transcript'],
                            // 'Bnk_Value'=>($data[0]['Total'] == null ? '':$data[0]['Bnk_Value']),
                            'Bnk_Value'=>$data[0]['Bnk_Value'],
                            'Is_Published'=>1,
                            'Created_By' => auth()->user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                            ]);
                          
                            $khs_Id = DB::getPdo()->lastInsertId();
                            $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_Id,''));
                        }else{
                          $khs_id = DB::table('acd_student_khs')->where('Krs_Id',$row->Krs_Id)->first();
                          $insertkhs = DB::table('acd_student_khs')->where('Krs_Id',$row->Krs_Id)->update(
                          [
                          'Grade_Letter_Id'=>$data[0]['Grade_Id'],
                          'Sks' => $data[0]['Transcript_Sks'],
                          'Weight_Value' => $data[0]['weight_value'],
                          'Is_For_Transkrip'=>$data[0]['Is_For_Transcript'],
                          'Is_Published'=>1,
                          'Bnk_Value'=>$data[0]['Bnk_Value']
                          ]);
                          $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_id->Khs_Id,''));
                        }
                        // dd($data[0]);
                        $i++;

                    }
                  }else{
                    $als++;
                    continue;
                  }
                  $als++;
                }
                Alert::success('Berhasil','Anda berhasil Menyimpan Perubahan');
                  return redirect()->back();
            }else {
              Alert::warning('data tiidak ada','Maaf');
              return redirect()->back();
            }
        }
    }

    public function exportdata($oci){
      Excel::create('Nilai', function ($excel) use($oci){       
            $items = DB::table('acd_student_krs')
              ->leftjoin('acd_offered_course' ,function ($join)
              {
                $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
                ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
              })
              ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
              ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student.Class_Prog_Id')
              ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
              ->leftjoin('acd_student_khs', 'acd_student_khs.Krs_Id' , '=' , 'acd_student_krs.Krs_Id')
              ->leftjoin('acd_grade_letter' ,'acd_grade_letter.Grade_Letter_Id', '=', 'acd_student_khs.Grade_Letter_Id')
              ->join('acd_course' ,'acd_course.Course_Id', '=', 'acd_student_krs.Course_Id')
              ->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student_krs.Class_Id')
              ->leftjoin('acd_student_khs_nilai_component','acd_student_krs.Krs_Id','=','acd_student_khs_nilai_component.Krs_Id')
              ->where('acd_offered_course.Offered_Course_id', $oci)
              ->select('acd_student_krs.Krs_Id as Krs','acd_student.Student_Id','acd_student.Nim','acd_student.Full_Name','acd_student.Class_Prog_Id','acd_student.Department_Id','acd_student_khs.Khs_Id','acd_student_khs_nilai_component.Uts','acd_student_khs_nilai_component.Uas','acd_grade_letter.Grade_Letter', 'acd_offered_course.Department_Id', 'acd_course.Course_Code','acd_course.Course_Name','mstr_department.Department_Name','mstr_class_program.Class_Program_Name','mstr_class.Class_Name as name_kelas','acd_offered_course.Term_Year_Id','mstr_term_year.Term_Year_Name',
                DB::raw('(SELECT Weight_Value FROM acd_grade_department WHERE acd_grade_department.Department_Id = acd_offered_course.Department_Id AND acd_grade_department.Grade_Letter_Id = acd_student_khs.Grade_Letter_Id GROUP BY acd_grade_department.Grade_Letter_Id) as weightvalue' ),
                DB::raw('(SELECT Is_For_Transcript FROM acd_course_curriculum WHERE Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
                AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_offered_course.Term_Year_Id AND Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Is_For_Transcript' ),
                DB::raw('(SELECT Transcript_Sks FROM acd_course_curriculum WHERE Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
                AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_offered_course.Term_Year_Id AND Department_Id = acd_offered_course.Department_Id AND Class_Prog_Id = acd_offered_course.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Transcript_Sks' )
                )
              ->orderBy('acd_student.Nim')
              ->get();

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
                      'Nim' => '',
                      'Nama Sisma' => '',
                      'UTS' => '',
                      'UAS' => '',
                  ]
              ];
          }

          $i = 1;
          foreach ($items as $item) {
            $data[] = [
                    'NO' =>$i,
                    'NIM' =>$item->Nim,
                    'Nama Mahasiswa' =>$item->Full_Name,
                    'UTS' =>($item->Uts == NULL ? '' : $item->Uts),
                    'UAS' =>($item->Uas == NULL ? '' : $item->Uas ),
                ];
              $i++;
          }

          $excel->sheet('Nilai', function ($sheet) use ($data,$items) {
              $sheet->fromArray($data, null, 'A5');

              $num_rows = sizeof($data) + 5;

              for ($i = 1; $i <= $num_rows; $i++) { 
                  $rows[$i] = 18;
              }

              $rows[5] = 30;

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
                  'F' => 20,
              ]);
              
              $sheet->setHorizontalCentered(true);

              for ($i = 5; $i <= $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $row->setValignment('center');
                  });
              }

              for ($i = 5; $i > $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $cells->setAlignment('center');
                  });
              }
              
              $sheet->setBorder('A5:F' . (sizeof($data) + 5), 'thin');

              $sheet->setHorizontalCentered(true);

              $sheet->cells('A5:E5', function ($cells) {
                  $cells->setBackground('#97D86E');
                  $cells->setFontWeight('bold');
                  $cells->setAlignment('center');
              });
              foreach ($data as $dt) {
                  $sheet->cells('D' . $i . ':E' . (sizeof($data) + 5), function ($cells) {
                      $cells->setAlignment('center');
                  });
                }

              $last = $i+1;
              // $sheet->cells('E2:E9999', function ($cells) {
              //             $cells->setAlignment('center');
              //     });
              $sheet->mergeCells('A1:B1');
              $sheet->setCellValue('A1', 'Prodi');
              $sheet->setCellValue('C1', $items[0]->Department_Name);
              
              $sheet->mergeCells('A2:B2');
              $sheet->setCellValue('A2', 'Program Kelas');
              $sheet->setCellValue('C2', $items[0]->Class_Program_Name);
              
              $sheet->mergeCells('A3:B3');
              $sheet->setCellValue('A3', 'Matakuliah / Kelas');
              $sheet->setCellValue('C3', $items[0]->Course_Name .' / '.$items[0]->name_kelas);
              
              $sheet->mergeCells('A4:B4');
              $sheet->setCellValue('A4', 'Th. Akademik');
              $sheet->setCellValue('C4', $items[0]->Term_Year_Name);
          });
      })->export('xls');

      return $krs;
    }

    public function updateKurikulum(Request $request)
    {
      $oci = DB::table('acd_offered_course')->where('Offered_Course_Id',$request->Offered_Course_id)->first();
      $krss = DB::table('acd_student_krs')
      ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id' )
      ->where('acd_student_krs.Term_Year_Id',$request->term_year)
      ->where('acd_student_krs.Course_Id',$request->Course_Id)
      ->where('acd_student_krs.Class_Prog_Id',$request->class_program)
      ->where('acd_student_krs.Class_Id',$request->Class_Id)    
      ->select('acd_student_krs.*','acd_student.Department_Id','acd_student.Entry_Year_Id','acd_student.Nim')
      ->get();
      foreach ($krss as $krs){
        $cur = DB::table('acd_curriculum_entry_year')
        ->where('Term_Year_Id',$krs->Term_Year_Id)
        ->where('Department_Id',$krs->Department_Id)
        ->where('Class_Prog_Id',$krs->Class_Prog_Id)
        ->where('Entry_Year_Id',$krs->Entry_Year_Id)
        ->first();
        
        $ccur = DB::table('acd_course_curriculum')
        ->where('Department_Id',$krs->Department_Id)
        ->where('Class_Prog_Id',$krs->Class_Prog_Id)
        ->where('Curriculum_Id',$cur->Curriculum_Id)
        ->where('Course_Id',$krs->Course_Id)
        ->where('Department_Id',$krs->Department_Id)
        ->first();

        $data=[
          'Sks'=> $ccur->Applied_Sks,
          ];
        $update =DB::table('acd_student_krs')->where('Krs_Id', $krs->Krs_Id)->update($data);

        $khs = DB::table('acd_student_khs')->where('Krs_Id',$krs->Krs_Id)->first();
        if($khs){
          $check_course = DB::table('acd_course')->where('Course_Id',$krs->Course_Id)->where('Department_Id',$krs->Department_Id)->select('Course_Type_Id')->first();
          
          if($check_course->Course_Type_Id == 12){
            $nilai_detail = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$krs->Krs_Id)->first();
            $grade_letter = DB::table('acd_grade_department')
            ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
            ->select('acd_grade_letter.Grade_Letter','acd_grade_letter.Grade_Letter_Id','acd_grade_department.Weight_Value')
            ->where([
              ['acd_grade_department.Department_Id',$krs->Department_Id],
              ['acd_grade_department.Scale_Numeric_Max','>',($nilai_detail->Total_score-0.01)],
              ['acd_grade_department.Scale_Numeric_Min','<',($nilai_detail->Total_score+0.01)]
              ])
              ->first();

            $data_khs=[
              'Sks'=> $ccur->Applied_Sks,
              'Grade_Letter_Id'=> $grade_letter->Grade_Letter_Id,
              'Weight_Value'=> $grade_letter->Weight_Value,
              'Bnk_Value'=> ($ccur->Applied_Sks * $grade_letter->Weight_Value),
              ];
            $khs_update = DB::table('acd_student_khs')->where('Krs_Id',$krs->Krs_Id)->update($data_khs);
      
            $transcript =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs->Khs_Id,''));
            
          }else{
            $nilai_details = DB::table('acd_student_khs_nilai_detail')->where('Krs_Id',$krs->Krs_Id)->get();
            
            $totalscore = 0;
            foreach ($nilai_details as $nilai_detail) {
              $totalscore = $totalscore + $nilai_detail->Score;
            }

            $grade_letter = DB::table('acd_grade_department')
            ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
            ->select('acd_grade_letter.Grade_Letter','acd_grade_letter.Grade_Letter_Id','acd_grade_department.Weight_Value')
            ->where([ 
              ['acd_grade_department.Department_Id',$krs->Department_Id],
              ['acd_grade_department.Entry_Year_Id',$krs->Entry_Year_Id],
              ['acd_grade_department.Scale_Numeric_Max','>',($totalscore-0.01)],
              ['acd_grade_department.Scale_Numeric_Min','<',($totalscore+0.01)]
              ])
              ->first();
              
            $data_khs=[
              'Sks'=> $ccur->Applied_Sks,
              'Grade_Letter_Id'=> $grade_letter->Grade_Letter_Id,
              'Weight_Value'=> $grade_letter->Weight_Value,
              'Bnk_Value'=> ($ccur->Applied_Sks * $grade_letter->Weight_Value),
              ];
            $khs_update = DB::table('acd_student_khs')->where('Krs_Id',$krs->Krs_Id)->update($data_khs);
      
            $transcript =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs->Khs_Id,'')); 
          }
      }    
    }
    return response()->json([
        'status' => 200,
        'message' => 'sukses.',
    ]);
  }
}
