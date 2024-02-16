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
use App\GetDepartment;


class History_nilaimhsController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $nim = Input::get('nim');
        $search = Input::get('search');
        $term_year = Input::get('term_year');
        $entry_year = Input::get('entry_year');
        $department = Input::get('department');
        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;

        $select_entry_year = DB::table('mstr_entry_year')->orderBy('Entry_Year_Id','desc')->get();

        $select_department = GetDepartment::getDepartment();

          $select_nim = DB::table('acd_student')
           ->join('mstr_department', 'acd_student.department_id', '=', 'mstr_department.department_id')
           ->where('mstr_department.department_id', $department)
           ->where('acd_student.Entry_Year_Id', $entry_year)
           ->get();

           $acd_student_krs = DB::table('acd_student_krs')
           ->join('acd_course' ,'acd_course.Course_Id','=','acd_student_krs.Course_Id')
           ->leftjoin('acd_course_curriculum', 'acd_course_curriculum.Course_Id', '=', 'acd_course.Course_Id')
           ->leftjoin('mstr_study_level', 'mstr_study_level.Study_Level_Id', '=', 'acd_course_curriculum.Study_Level_Id')
           ->join('acd_student', 'acd_student.Student_Id','=','acd_student_krs.Student_Id')
           ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')

           ->join('mstr_class', 'mstr_class.Class_Id', '=' ,'acd_student_krs.Class_Id')
           ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student_krs.Class_Prog_Id')
           ->join('mstr_term_year','mstr_term_year.Term_Year_Id', '=', 'acd_student_krs.Term_Year_Id')

           ->leftjoin('acd_student_khs', 'acd_student_khs.Krs_Id' , '=' , 'acd_student_krs.Krs_Id')
           ->leftjoin('acd_grade_letter' ,'acd_grade_letter.Grade_Letter_Id', '=', 'acd_student_khs.Grade_Letter_Id')

           ->where('acd_student.Nim', $nim)
           ->where('acd_student.department_id',$department)
           ->where('acd_student.Entry_Year_Id',$entry_year)
           // ->where('acd_student_krs.Is_Approved', 1)
           ->select('acd_student_krs.Krs_Id as Krs','mstr_term_year.Term_Year_Name','acd_student.*','acd_student.Full_Name','acd_student_khs.*','acd_grade_letter.Grade_Letter','acd_course.*','mstr_study_level.Level_Name')
           ->groupBy('acd_student_krs.Krs_Id')
           ->get();
           // dd($acd_student_krs);


        //$student = DB::table('acd_student')->where('Nim', $nim)->first();



        return view('laporan_history_nilaimhs/index')->with('entry_year',$entry_year)->with('select_entry_year',$select_entry_year)->with('nim',$nim)->with('select_nim',$select_nim)->with('query',$acd_student_krs)->with('department',$department)->with('select_department',$select_department)->with('search',$search)->with('nim', $nim);
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
        //
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
        //
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
}
