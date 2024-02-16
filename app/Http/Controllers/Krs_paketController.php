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

class Krs_paketController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','show']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','export']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','export']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','export']]);

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
       $FacultyId = Auth::user()->Faculty_Id;
       $curriculum = Input::get('curriculum');

       $select_class_program = DB::table('mstr_department_class_program')
       ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
       ->join('mstr_department','mstr_department.Department_Id','=','mstr_department_class_program.Department_Id')

       ->where('mstr_department_class_program.Department_Id', $department)
       ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
       ->get();

       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
       ->get();

       $select_curriculum = DB::table('mstr_curriculum')
       ->orderBy('mstr_curriculum.Curriculum_Name', 'desc')
       ->get();

       if($FacultyId==""){
         $select_department = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->orderBy('mstr_department.department_code', 'asc')
         ->get();
       }else{
         $select_department = DB::table('mstr_department')
         ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
         ->where('mstr_faculty.Faculty_Id', $FacultyId)
         ->orderBy('mstr_department.department_code', 'asc')
         ->get();
       }

if($FacultyId==""){
  $data_paket['paket11'] = DB::table('acd_offered_course_package')
                        ->where('acd_offered_course_package.Department_Id', $department)
                        ->where('acd_offered_course_package.Class_Prog_Id', $class_program)
                        ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                        ->where('acd_offered_course_package.Curriculum_Id', $curriculum)
                        ->where('acd_offered_course_package.Package_Type', 1)
                        ->groupBy('acd_offered_course_package.Course_Id')
                        ->get();
  $data_paket['paket1'] = count($data_paket['paket11']);
  $data_paket['paket2'] = DB::table('acd_offered_course_package')
                        ->where('acd_offered_course_package.Department_Id', $department)
                        ->where('acd_offered_course_package.Class_Prog_Id', $class_program)
                        ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                        ->where('acd_offered_course_package.Curriculum_Id', $curriculum)
                        ->where('acd_offered_course_package.Package_Type', 2)
                        ->count();
  $data_paket['paket3'] = DB::table('acd_offered_course_package')
                        ->where('acd_offered_course_package.Department_Id', $department)
                        ->where('acd_offered_course_package.Class_Prog_Id', $class_program)
                        ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                        ->where('acd_offered_course_package.Curriculum_Id', $curriculum)
                        ->where('acd_offered_course_package.Package_Type', 3)
                        ->count();
  $data_paket['paket4'] = DB::table('acd_offered_course_package')
                        ->where('acd_offered_course_package.Department_Id', $department)
                        ->where('acd_offered_course_package.Class_Prog_Id', $class_program)
                        ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                        ->where('acd_offered_course_package.Curriculum_Id', $curriculum)
                        ->where('acd_offered_course_package.Package_Type', 4)
                        ->count();
  $data_paket['paket5'] = DB::table('acd_offered_course_package')
                        ->where('acd_offered_course_package.Department_Id', $department)
                        ->where('acd_offered_course_package.Class_Prog_Id', $class_program)
                        ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                        ->where('acd_offered_course_package.Curriculum_Id', $curriculum)
                        ->where('acd_offered_course_package.Package_Type', 5)
                        ->count();
    // dd($data_paket);
              
    $data = DB::table('acd_offered_course')
    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')

    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
    ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
    ->where('acd_offered_course.Department_Id', $department)
    ->where('acd_offered_course.Class_Prog_Id', $class_program)
    ->where('acd_offered_course.Term_Year_Id', $term_year)
    ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
    ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
    ->orderBy('acd_course.Course_Name', 'asc')
    ->orderBy('mstr_class.class_Name', 'asc')
    ->paginate($rowpage);
} else{
  if ($search == null) {
    $data = DB::table('acd_offered_course')
    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
    ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
    ->where('mstr_faculty.Faculty_Id', $FacultyId)
    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
    ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
    ->where('acd_offered_course.Department_Id', $department)
    ->where('acd_offered_course.Class_Prog_Id', $class_program)
    ->where('acd_offered_course.Term_Year_Id', $term_year)
    ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
    ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
    ->orderBy('acd_course.Course_Name', 'asc')
    ->orderBy('mstr_class.class_Name', 'asc')
    ->paginate($rowpage);
  }else {
    $data = DB::table('acd_offered_course')
    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
    ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
    ->where('mstr_faculty.Faculty_Id', $FacultyId)
    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
    ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
    ->where('acd_offered_course.Department_Id', $department)
    ->where('acd_offered_course.Class_Prog_Id', $class_program)
    ->where('acd_offered_course.Term_Year_Id', $term_year)
    ->where(function($query){
      $search = Input::get('search');
      $query->whereRaw("lower(Course_Name) like '%" . strtolower($search) . "%'");
      $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
    })
    ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
    ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
    ->orderBy('acd_course.Course_Name', 'asc')
    ->orderBy('mstr_class.class_Name', 'asc')
    ->paginate($rowpage);
  }
}

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
       return view('krs_paket/index')
       ->with('query',$data)
       ->with('search',$search)
       ->with('rowpage',$rowpage)
       ->with('select_class_program', $select_class_program)
       ->with('class_program', $class_program)
       ->with('select_department', $select_department)
       ->with('department', $department)
       ->with('select_term_year', $select_term_year)       
        ->with('select_curriculum', $select_curriculum)
        ->with('curriculum', $curriculum)
        ->with('data_paket', $data_paket)
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
      public function create()
      {
        $id = Input::get('id');
        $currentsearch = Input::get('current_search');
        $currentpage = Input::get('current_page');
        $currentrowpage = Input::get('current_rowpage');
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $term_year = Input::get('term_year');
        $FacultyId = Auth::user()->Faculty_Id;

        $entry_year = Input::get('entry_year');
        $curriculum = Input::get('curriculum');

        if($FacultyId==""){

        } else{

        }


        $select_entry_year = DB::table('mstr_entry_year')->orderBy('Entry_Year_Code','desc')->get();
        // $getofferedcoursekrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($department,$term_year,$class_program,$entry_year,$Offered_Course->Course_Id));
        $mhs_out = DB::table('acd_student_out')
        ->where('Department_Id', $department)
        ->where('Class_Prog_Id', $class_program)
        ->where('Entry_Year_Id', $entry_year)
        ->select('Student_Id');
        // $member = DB::table('acd_student_krs')
        // ->where('acd_student_krs.Course_Id', $Offered_Course->Course_Id)
        // ->where('acd_student_krs.Class_Prog_Id', $Offered_Course->Class_Prog_Id)
        // ->where('acd_student_krs.Class_Id', $Offered_Course->Class_Id)
        // ->where('Term_Year_Id', $Offered_Course->Term_Year_Id)
        // ->select('acd_student_krs.Student_Id');
        $equivalen=DB::table('acd_student')
        ->where('Nim', 'LIKE', '%P%')
        ->select('Student_Id');
        $data = DB::table('acd_student')
        ->where('Entry_Year_Id', $entry_year)
        ->where('Department_Id', $department)
        ->where('Class_Prog_Id', $class_program)
        ->WhereNotIn('Student_Id',$equivalen)
        // ->WhereNotIn('Student_Id', $member)
        ->WhereNotIn('Student_Id', $mhs_out)
        ->orderBy('Nim','ASC')
        ->get();
        

        $data_course = DB::table('acd_offered_course')
            ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
            // ->where('acd_course_curriculum.Study_Level_Id',$semester)
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
            ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            ->join('fnc_course_cost_type','fnc_course_cost_type.Course_Id','=','acd_offered_course.Course_Id')
            ->where('acd_offered_course.Department_Id', $department)
            ->where('acd_offered_course.Class_Prog_Id', $class_program)
            ->where('acd_offered_course.Term_Year_Id', $term_year)
            ->where('acd_course_curriculum.Curriculum_Id',$curriculum)
            // ->where('acd_offered_course.Package','=',null)
            // ->where('fnc_course_cost_type.Is_Sks','=',0)
            ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id')
            ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
            ->orderBy('acd_course.Course_Code', 'asc')
            ->groupBy('acd_offered_course.Course_Id')
            ->get();
        // dd($data_course);

        return view('krs_paket/create_peserta')
        ->with('Entry_Year_Id', $entry_year)
        ->with('query', $data)
        ->with('select_entry_year', $select_entry_year)
        ->with('Offered_Course_id', $id)
        ->with('entry_year',$entry_year)
        ->with('term_year',$term_year)
        ->with('class_program',$class_program)
        ->with('data_course',$data_course)
        ->with('curriculum',$curriculum)
        ->with('department', $department);
      }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
        $class_program = Input::get('class_program');
        $department = Input::get('department');
        $term_year = Input::get('term_year');
        $curriculum = Input::get('curriculum');
        $paket = Input::get('paket');
        $offered_course = Input::get('offered_course');

        if ($offered_course == null || $paket == 0) {
          return Redirect::back()->withErrors('Pilih Paket / Data Terlebih Dahulu');
        }else{
          foreach ($offered_course as $key) {
            $insert = DB::table('acd_offered_course_package')
                    ->insert(['Package_Type'=>$paket,
                               'Term_Year_Id'=>$term_year,
                               'Department_Id'=>$department,
                               'Class_Prog_Id'=>$class_program,
                               'Curriculum_Id'=>$curriculum,
                               'Course_Id'=>$key]);
          }
        }

       return Redirect::to('/proses/krs_paket?term_year='.$term_year.'&department='.$department.'&curriculum='.$curriculum.'&class_program='.$class_program)->withErrors('Sukses');

     }

     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
       $page = Input::get('page');
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       if ($rowpage == null) {
         $rowpage = 10;
       }

       $current_search = Input::get('current_search');
       $current_page = Input::get('current_page');
       $current_rowpage = Input::get('current_rowpage');
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       $term_year = Input::get('term_year');
       $curriculum = Input::get('curriculum');
       $FacultyId = Auth::user()->Faculty_Id;

       $data_offered_course = DB::table('acd_offered_course_package')
                            ->leftjoin('acd_offered_course','acd_offered_course_package.Course_Id','=','acd_offered_course.Course_Id')
                            ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                            ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
                            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                            ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                            ->where('acd_offered_course_package.Department_Id', $department)
                            ->where('acd_offered_course_package.Class_Prog_Id', $class_program)
                            ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                            ->where('acd_offered_course_package.Curriculum_Id',$curriculum)
                            ->where('acd_offered_course_package.Package_Type',$id)
                            ->groupBy('acd_offered_course_package.Course_Id')
                            ->get();
       $detail_offered_course = DB::table('acd_offered_course_package')
                            ->leftjoin('acd_offered_course','acd_offered_course_package.Course_Id','=','acd_offered_course.Course_Id')
                            ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                            ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
                            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                            ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                            ->where('acd_offered_course_package.Department_Id', $department)
                            ->where('acd_offered_course_package.Class_Prog_Id', $class_program)
                            ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                            ->where('acd_offered_course_package.Curriculum_Id',$curriculum)
                            ->where('acd_offered_course_package.Package_Type',$id)
                            ->groupBy('acd_offered_course_package.Course_Id')
                            ->get();
       $d_matkul = DB::table('acd_offered_course_package')
                            ->leftjoin('acd_offered_course','acd_offered_course_package.Course_Id','=','acd_offered_course.Course_Id')
                            ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                            ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
                            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                            ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                            ->where('acd_offered_course_package.Department_Id', $department)
                            ->where('acd_offered_course_package.Class_Prog_Id', $class_program)
                            ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                            ->where('acd_offered_course_package.Curriculum_Id',$curriculum)
                            ->where('acd_offered_course_package.Package_Type',$id)
                            ->groupBy('acd_offered_course_package.Course_Id')
                            ->select('acd_offered_course_package.Course_Id');
                            // dd($d_matkul); 
      $select_entry_year = DB::table('mstr_entry_year')->orderBy('Entry_Year_Code','desc')->limit(10)->get();

      // $acd_student_krss = DB::table('acd_student');
      // $no = 1;
      // foreach ($d_matkul as $value) {
      //   $acd_student_krss = $acd_student_krss->join('acd_student_krs as '.$no, function($a) use($value,$no){
      //     $a->on($no.'.Student_Id','=','acd_student.Student_Id')->where($no.'.Course_Id',$value->Course_Id);
      //   });
      //   $no++;
      // }
      // $acd_student_krss = $acd_student_krss->get();

      $student_ids = [];
      $acd_student_krs = [];
      $i = 0;
      $acd_student_krs = DB::table('acd_student_krs as  a')
                        // ->join('acd_offered_course as b','a.Course_Id','=','b.Course_Id')
                        ->wherein('a.Course_Id',$d_matkul)
                        ->groupby('a.Student_Id')
                        ->select('a.Student_Id',DB::raw('count(Krs_Id) as Krs_Ids'))
                        // ->where('Krs_Ids',$d_matkul->count())
                        ->get();
        foreach ($acd_student_krs as  $data){
          if($data->Krs_Ids == $data_offered_course->count()){
            $student_ids[$i] = $data->Student_Id;
            $i++;
          }
        }
        
        $acd_student_krs = DB::table('acd_student_krs as  a')
                        ->rightjoin('acd_offered_course as b','a.Course_Id','=','b.Course_Id')
                        ->rightjoin('acd_course as c','c.Course_Id','=','b.Course_Id')
                        ->leftjoin('acd_student as d','a.Student_Id','=','d.Student_Id')
                        ->leftjoin('mstr_class as e','b.Class_Id','=','e.Class_Id')
                        ->wherein('a.Course_Id',$d_matkul)
                        ->wherein('a.Student_Id',$student_ids)
                        // ->groupby('a.Student_Id')
                        // ->select('a.Student_Id',DB::raw('count(Krs_Id) as Krs_Ids'))
                        ->orderby('a.Student_Id')
                        ->orderby('c.Course_Code')
                        ->groupby('a.Krs_Id')
                        ->get();

      // $query->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department, 'currentpage' => $current_page, 'currentsearch' => $current_search, 'currentrowpage' => $current_rowpage ]);
      return view('krs_paket/show')
      ->with('Offered_Course_id', $id)
      // ->with('query',$query)
      // ->with('data', $data)
      ->with('page', $page)
      ->with('search',$search)
      ->with('rowpage',$rowpage)
      ->with('class_program', $class_program)
      ->with('department', $department)
      ->with('term_year', $term_year)
      ->with('currentsearch', $current_search)
      ->with('currentpage', $current_page)
      ->with('data_offered_course', $data_offered_course)
      ->with('detail_offered_course', $detail_offered_course)
      ->with('curriculum', $curriculum)
      ->with('select_entry_year', $select_entry_year)
      ->with('id', $id)
      ->with('acd_student_krs', $acd_student_krs)
      ->with('currentrowpage', $current_rowpage);

     }

     public function create_datapeserta(Request $request, $id)
      {
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $term_year = Input::get('term_year');
        $curriculum = Input::get('curriculum');
        $course_id = Input::get('course_id'); 
        $paket = $id;
        $entry_year = Input::get('entry_year');

        $data_mhs = [];

        $data_offered_course = DB::table('acd_offered_course_package')
                            ->leftjoin('acd_offered_course','acd_offered_course_package.Course_Id','=','acd_offered_course.Course_Id')
                            ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                            ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
                            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                            ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                            ->where('acd_offered_course_package.Department_Id', $department)
                            ->where('acd_offered_course.Class_Prog_Id', $class_program)
                            ->where('acd_offered_course_package.Term_Year_Id', $term_year)
                            ->where('acd_offered_course_package.Curriculum_Id',$curriculum)
                            ->where('acd_offered_course_package.Package_Type',$id)
                            ->groupBy('acd_offered_course_package.Course_Id')
                            ->get();
      $select_entry_year = DB::table('mstr_entry_year')->orderBy('Entry_Year_Code','desc')->limit(10)->get();
    if($entry_year != null){
      if($course_id != null){
        $member = [];
        $class = [];
        $i= 0;
        $a= 0;
      foreach ($course_id as $key) {
          $members = DB::table('acd_student_krs')
                ->where('acd_student_krs.Course_Id', $key)
                ->where('acd_student_krs.Class_Prog_Id', $class_program)
                ->where('Term_Year_Id', $term_year)->select('acd_student_krs.Student_Id')->get();
                foreach ($members as $data_member) {
                  $member[$i] = $data_member->Student_Id;
                  $i++;
                }

          $classes = DB::table('acd_offered_course_package')
              ->leftjoin('acd_offered_course','acd_offered_course_package.Course_Id','=','acd_offered_course.Course_Id')
              ->where('acd_offered_course_package.Course_Id', $key)
              ->where('acd_offered_course_package.Department_Id', $department)
              ->where('acd_offered_course.Class_Prog_Id', $class_program)
              ->where('acd_offered_course_package.Term_Year_Id', $term_year)
              ->where('acd_offered_course_package.Curriculum_Id',$curriculum)
              ->where('acd_offered_course_package.Package_Type',$id)
              ->groupby('acd_offered_course.Offered_Course_Id')->select('Offered_Course_id')->get();
              // dd($classes);
              foreach ($classes as $data_class) {
                $class[$a] = $data_class->Offered_Course_id;
                $a++;
              }
            }
      $mhs_out = DB::table('acd_student_out')
              ->where('Department_Id', $department)
              ->where('Class_Prog_Id', $class_program)
              ->where('Entry_Year_Id','like','%'.$entry_year.'%')
              ->select('Student_Id');      
      $equivalen=DB::table('acd_student')
              ->where('Nim', 'LIKE', '%P%')
              ->select('Student_Id');
      $data_mhs = DB::table('acd_student')
            ->where('Entry_Year_Id',$entry_year)
            ->where('Department_Id', $department)
            ->where('Class_Prog_Id', $class_program)
            ->WhereNotIn('Student_Id',$equivalen)
            ->WhereNotIn('Student_Id', $member)
            ->WhereNotIn('Student_Id', $mhs_out)
            ->orderBy('Nim','ASC')
            ->get();    

      $data_kelas = [];
      $data_kelas = [];
      $ii = 0;
      foreach ($class as $oci) {
        $data_kelases = DB::table('acd_offered_course_package')
                    ->leftjoin('acd_offered_course','acd_offered_course_package.Course_Id','=','acd_offered_course.Course_Id')
                    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                    ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')

                    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                    ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
                    ->where('acd_offered_course.Offered_Course_id', $oci)
                    ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
                    ->groupBy('acd_offered_course.Offered_Course_Id','acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
                    ->orderBy('acd_course.Course_Name', 'asc')
                    ->orderBy('mstr_class.class_Name', 'asc')
                    ->get();
          foreach ($data_kelases as $data) {
            $data_kelas[$ii] = $data;
            $ii++;
          }
        }
      }
    }else{
      $data_kelas = [];
    }

        return view('krs_paket/create_datapeserta')
              ->with('class_program', $class_program)
              ->with('department', $department)
              ->with('curriculum', $curriculum)
              ->with('paket', $paket)
              ->with('term_year', $term_year)
              ->with('data_offered_course', $data_offered_course)
              ->with('entry_year', $entry_year)
              ->with('data_mhs', $data_mhs)
              ->with('data_kelas', $data_kelas)
              ->with('select_entry_year', $select_entry_year);
      }
     

      public function update_datapeserta(Request $request)
      {
        $department = $request->department;
        $class_program = $request->class_program;
        $term_year = $request->term_year;
        $curriculum = $request->curriculum;
        $course_id = $request->course_id;
        $entry_year = $request->entry_year;
        $Student_Id = $request->Student_Id;
        $Offered_Course_id = $request->Offered_Course_id;

        $cek_kapasitas = [];        
        $x = 0;
        foreach ($Offered_Course_id as $oci) {
          $cek_kapasitas[$x] = DB::table('acd_offered_course')->where('Offered_Course_id', $oci)->min('Class_Capacity');
          $x++;
        }
        $kuota_terendah = min($cek_kapasitas);

        // $nama_matkul = [];
        // $xx = 0;
        // foreach ($Offered_Course_id as $oci) {
        //   $nama_matkul[$xx] = DB::table('acd_offered_course')
        //   ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
        //   ->where('Offered_Course_id', $oci)->where('Class_Capacity',$kuota_terendah)->select('Course_Name')->first();
        //   $xx++;
        // }

        foreach ($Offered_Course_id as $oci) {
          $offeredcourse = DB::table('acd_offered_course_package')
                        ->leftjoin('acd_offered_course','acd_offered_course_package.Course_Id','=','acd_offered_course.Course_Id')
                        ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                        ->where('Offered_Course_id', $oci)->first();
          $count_student = DB::table('acd_student_krs')
                        ->where('Term_Year_Id', $offeredcourse->Term_Year_Id)
                        ->where('Class_Prog_Id', $offeredcourse->Class_Prog_Id)
                        ->where('Course_Id', $offeredcourse->Course_Id)
                        ->where('Class_Id', $offeredcourse->Class_Id)
                        ->select('Student_Id')->count();
          $sisakuota = $kuota_terendah - $count_student;
          if ( $sisakuota < count($Student_Id)) {
            return response()->json([
                'status' => 304,
                'message' => 'Kapasitas Kurang Untuk '.count($Student_Id).' orang',
                'data' => [$sisakuota,count($Student_Id)],
            ]);
          }

          foreach ($Student_Id as $StudentId) {
            $student = Db::table('acd_student')->where('Student_Id', $StudentId)->first();            
            $coursecur=DB::table('acd_course_curriculum')->select('Course_Id');
            if($coursecur==""){
              return response()->json([
                  'status' => 304,
                  'message' => 'Mata Kuliah Kurikulum Belum DIisi.',
                  'data' => [$coursecur],
              ]);
            }else{
              $getcoursecostkrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($department,$term_year,$class_program,$entry_year,$offeredcourse->Course_Id));
            }            
            $sksallowed = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($term_year,$StudentId));
            $sksambil = DB::table('acd_student_krs')
                      ->where('Student_Id', $StudentId)
                      ->where('Term_Year_Id', $term_year)
                      ->select(DB::raw('(SUM(acd_student_krs.Sks)) as SKS'))
                      ->get();            
            $allowedsks = 0;
            $ambilsks = 0;
            $Sks = 0;
            $Amount = 0;
            foreach ($sksallowed as $a) { $allowedsks = $a->AllowedSKS; }
            foreach ($sksambil as $b) { $ambilsks = $b->SKS; }
            foreach ($getcoursecostkrs as $c) { $Sks = $c->applied_sks; $Amount = $c->amount; } // MASIH ADA ERROR DISINI
            $saldo = 0;
            $sisasaldo = 0;
            
            $curentryyear = DB::table('acd_curriculum_entry_year')
                          ->where('Entry_Year_Id', $student->Entry_Year_Id)
                          ->where('Department_Id', $department)
                          ->where('Term_Year_Id', $term_year)
                          ->first();            
            $curentryyearcount = DB::table('acd_curriculum_entry_year')
                              ->where('Entry_Year_Id', $student->Entry_Year_Id)
                              ->where('Department_Id', $department)
                              ->where('Term_Year_Id', $term_year)
                              ->count();
            
            if($curentryyear == null){
              return response()->json([
                  'status' => 304,
                  'message' => 'Kurikulum Angkatan Belum diset.',
                  'data' => [$curentryyear],
              ]);
            }else{
              $matakuliahkurikulum = DB::table('acd_course_curriculum')
                                  ->where('Curriculum_Id', $curentryyear->Curriculum_Id)
                                  ->where('Course_Id', $offeredcourse->Course_Id)
                                  ->where('Class_Prog_Id', $class_program)
                                  ->get();
              
              $matakuliahkurikulum2 = DB::table('acd_course_curriculum')
                                    ->WhereNotNull('Applied_Sks')
                                    ->where('Curriculum_Id', $curentryyear->Curriculum_Id)
                                    ->where('Course_Id', $offeredcourse->Course_Id)
                                    ->where('Class_Prog_Id', $class_program)
                                    ->get();
      
              if (($allowedsks - $ambilsks) < $Sks) {
                $message = "SKS tidak Cukup";
              }
              elseif (count($matakuliahkurikulum)==0) {
                return response()->json([
                  'status' => 304,
                  'message' => 'Matakuliah & Kurikulum Belum Diisi.',
                  'data' => [$matakuliahkurikulum],
                ]);
              }elseif (count($matakuliahkurikulum2)==0) {
                return response()->json([
                  'status' => 304,
                  'message' => 'Matakuliah & Kurikulum Belum Lengkap.',
                  'data' => [$matakuliahkurikulum2],
                ]);
              }else {
                $acd_course_get = DB::table('acd_course')->where('Course_Id',$Course_Id)->first();
                if($acd_course_get->Course_Type_Id == 12){
                  $date = Date('Y-m-d');
                  $insert = DB::table('acd_student_krs')
                    ->insert(
                      ['Student_Id' => $StudentId,
                      'Class_Prog_Id' => $class_program,
                      'Term_Year_Id' => $term_year,
                      'Course_Id' => $offeredcourse->Course_Id, 
                      'Class_Id' => $offeredcourse->Class_Id, 
                      'Sks' => $Sks, 
                      //  'Amount' => NULL, 
                      'Amount' => $Amount, 
                      'Created_Date' => $date, 
                      'Cost_Item_Id' => 2, 
                      'Is_Approved' => 1, 
                      'Approved_By' => 'Admin', 
                      'Krs_Date' => $date, 
                      'Modified_Date' => $date ]);       
                }elseif ($acd_course_get->Course_Type_Id == 13) {
                  $date = Date('Y-m-d');
                  $insert = DB::table('acd_student_krs')
                    ->insert(
                      ['Student_Id' => $StudentId,
                      'Class_Prog_Id' => $class_program,
                      'Term_Year_Id' => $term_year,
                      'Course_Id' => $offeredcourse->Course_Id, 
                      'Class_Id' => $offeredcourse->Class_Id, 
                      'Sks' => $Sks, 
                      //  'Amount' => NULL, 
                      'Amount' => $Amount, 
                      'Created_Date' => $date, 
                      'Cost_Item_Id' => 105, 
                      'Is_Approved' => 1, 
                      'Approved_By' => 'Admin', 
                      'Krs_Date' => $date, 
                      'Modified_Date' => $date ]);    
                }            
              }
            }
          }          
        }
        return response()->json([
            'status' => 200,
            'message' => 'Berhasil diinput.',
            'data' => [$insert],
          ]);   
      }
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */

      public function export($id)
      {
        $type = Input::get('type');
        $FacultyId = Auth::user()->Faculty_Id;

        $data = DB::table('acd_offered_course')
        ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
        ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('mstr_term','mstr_term.Term_Id','=','mstr_term_year.Term_Id')
        ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','mstr_term_year.Year_Id')
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
        ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name', 'mstr_faculty.Faculty_Id' , 'mstr_term_year.Term_Year_Name','mstr_term.Term_Name','mstr_entry_year.Entry_Year_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
        ->orderBy('acd_course.Course_Name', 'asc')
        ->orderBy('mstr_class.class_Name', 'asc')
        ->first();

        $jadwal = "";
        $jadwal_q = DB::table('acd_sched_session')->join('mstr_day' , 'acd_sched_session.Day_Id' , '=', 'mstr_day.Day_Id')
        ->join('acd_offered_course_sched', 'acd_sched_session.Sched_Session_Id', '=', 'acd_offered_course_sched.Sched_Session_Id')
        ->join('mstr_room' , 'acd_offered_course_sched.Room_Id', '=', 'mstr_room.Room_Id')
        ->join('acd_offered_course' , 'acd_offered_course_sched.Offered_Course_id', '=', 'acd_offered_course.Offered_Course_id')
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->first();
        if ($jadwal_q) {
          $jadwal = $jadwal_q;
        }

        $dosen = DB::table('emp_employee')->join('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Employee_Id' , '=', 'emp_employee.Employee_Id')
        ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_lecturer.Offered_Course_id')
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->orderBy('acd_offered_course_lecturer.Order_Id' , 'asc')
        ->get();

        $grade = DB::table('acd_grade_department')->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_grade_department.Grade_Letter_Id')->where('Department_Id', $data->Department_Id)->get();

        $acd_student_krs = DB::table('acd_student_krs')
                 ->join('acd_offered_course' ,function ($join)
                 {
                   $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                   ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                   ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
                   ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
                 })
                 ->join('acd_student' , function ($join)
                 {
                   $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                   ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
                 })
                 ->where('acd_offered_course.Offered_Course_id', $id)
                 ->orderby('acd_student.Nim');

        $query = $acd_student_krs
          ->select('acd_student_krs.Krs_Id','acd_student.*')
          ->get();

        $prog_type = $acd_student_krs
          ->join('mstr_department', 'mstr_department.Department_Id' , '=' , 'acd_offered_course.Department_Id')
          ->join('mstr_education_program_type' , 'mstr_education_program_type.Education_Prog_Type_Id', '=' , 'mstr_department.Education_Prog_Type_Id')
          ->select('mstr_education_program_type.*')
          ->first();

        $ttd = "";
        $pejabat = "";
        if ($prog_type->Education_Prog_Type_Code == 2 || $prog_type->Education_Prog_Type_Code == 3) {
          $ttd = "Ketua Program Studi";
          $pejabat = DB::table('acd_functional_position_term_year')->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')->where('Year_Id', $data->Term_Year_Id)->where('acd_functional_position_term_year.Department_Id', $data->Department_Id)->where('Functional_Position_Code', 'KP')
          ->select('emp_employee.Full_Name')->get();
        }else {
          $ttd = "Wakil Dekan I";
          $pejabat = DB::table('acd_functional_position_term_year')->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')->where('Year_Id', $data->Term_Year_Id)->where('Faculty_Id', $data->Faculty_Id)->where('Functional_Position_Code', 'WD1')
          ->select('emp_employee.Full_Name')->get();
        }

        $typ = "";
        if ($type == "BeritaAcaraUTS") {
          $typ = "Ujian Tengah Semester";
        }elseif ($type == "BeritaAcaraUAS") {
          $typ = "Ujian Akhir Semester";
        }


        View()->share(['data'=> $data,'query' => $query, 'jadwal' => $jadwal , 'dosen' => $dosen, 'grade' => $grade, 'ttd' => $ttd, 'pejabat' => $pejabat, 'typ' => $typ ]);
        if ($type == "Presensi") {
          $pdf = PDF::loadView('krs_paket/export_presensi');
          return $pdf->stream('Presensi.pdf');
        }elseif ($type == "FormNilai") {
          $pdf = PDF::loadView('krs_paket/export_form_nilai');
          return $pdf->stream('Form_Nilai.pdf');
        }elseif ($type == "BeritaAcaraUTS") {
          $pdf = PDF::loadView('krs_paket/export_berita_acara');
          return $pdf->stream('Berita_Acara_UTS.pdf');
        }elseif ($type == "BeritaAcaraUAS") {
          $pdf = PDF::loadView('krs_paket/export_berita_acara');
          return $pdf->stream('Berita_Acara_UAS.pdf');
        }
        // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);

      }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy(Request $request, $id)
     {
          $q =DB::table('acd_offered_course_package')
             ->where([['Package_Type', $id],
                      ['Term_Year_Id',$request->term_year],
                      ['Curriculum_Id',$request->curriculum],
                      ['Department_Id',$request->department],
                      ['Class_Prog_Id',$request->class_program],
                      ])
             ->delete();

        echo json_encode($q);

     }
 }
