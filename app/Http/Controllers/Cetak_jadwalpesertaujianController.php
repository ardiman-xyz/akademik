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

class Cetak_jadwalpesertaujianController extends Controller
{
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
      $select_department = GetDepartment::getDepartment();

         $select_class_program = DB::table('mstr_department_class_program')
         ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
         ->join('mstr_department','mstr_department.Department_Id','=','mstr_department_class_program.Department_Id')
         ->where('mstr_department_class_program.Department_Id', $department)
         ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
         ->get();


  if ($search == null) {
    $data = DB::table('acd_offered_course')
    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')

    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
    // ->leftjoin('acd_offered_course_exam', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
    // ->leftjoin('mstr_exam_type', 'mstr_exam_type.Exam_Type_Id', '=', 'acd_offered_course_exam.Exam_Type_Id')
    // ->leftjoin('mstr_room' , 'mstr_room.Room_Id' , '=' , 'acd_offered_course_exam.Room_Id')
    ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
    ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
    ->where('acd_offered_course.Department_Id', $department)
    ->where('acd_offered_course.Class_Prog_Id', $class_program)
    ->where('acd_offered_course.Term_Year_Id', $term_year)
    ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name',
    DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as start_date'),
    DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_End_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as end_date'),
    DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Type_Id SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as jenis_ujian'))
    ->orderBy('acd_course.Course_Name', 'asc')
    ->orderBy('mstr_class.class_Name', 'asc')
    ->paginate($rowpage);
    // dd($data);
  }else {
    $data = DB::table('acd_offered_course')
    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')

    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
    // ->leftjoin('acd_offered_course_exam', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
    // ->leftjoin('mstr_exam_type', 'mstr_exam_type.Exam_Type_Id', '=', 'acd_offered_course_exam.Exam_Type_Id')
    // ->leftjoin('mstr_room' , 'mstr_room.Room_Id' , '=' , 'acd_offered_course_exam.Room_Id')
    ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
    ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
    ->where('acd_offered_course.Department_Id', $department)
    ->where('acd_offered_course.Class_Prog_Id', $class_program)
    ->where('acd_offered_course.Term_Year_Id', $term_year)
    ->where(function($query){
      $search = Input::get('search');
      $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
      $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
    })
    ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name',
    DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as start_date'),
    DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_End_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as end_date'),
    DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Type_Id SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as jenis_ujian'))
    ->orderBy('acd_course.Course_Name', 'asc')
    ->orderBy('mstr_class.class_Name', 'asc')
    ->paginate($rowpage);
  }

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
       return view('cetak/index_jadwaldanpesertaujian')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year);
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
      public function show($id)
      {
          $currentsearch = Input::get('currentsearch');
          $currentpage = Input::get('currentpage');
          $currentrowpage = Input::get('currentrowpage');
          $department = Input::get('department');
          $class_program = Input::get('class_program');
          $term_year = Input::get('term_year');

          $rowpage = Input::get('rowpage');

          if ($rowpage == null) {
            $rowpage = 10;
          }

          $ma = $data = DB::table('acd_course')
          ->join('acd_offered_course' ,'acd_offered_course.Course_Id', '=', 'acd_course.Course_Id')
          ->where('Offered_Course_id', $id)
          ->leftjoin('mstr_class' ,'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')->first();
          // ->join('acd_offered_course' ,'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
          // ->leftjoin('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')
          // ->join('mstr_class' ,'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')->first();

          $data = DB::table('acd_offered_course_exam')
          ->join('mstr_exam_type', 'mstr_exam_type.Exam_Type_Id', '=', 'acd_offered_course_exam.Exam_Type_Id')
          ->join('mstr_room','mstr_room.Room_Id','=','acd_offered_course_exam.Room_Id')
          ->leftjoin('emp_employee as employee1' , 'employee1.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_1')
          ->leftjoin('emp_employee as employee2' , 'employee2.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_2')
          ->where('Offered_Course_id', $id)
          ->select('acd_offered_course_exam.*','mstr_exam_type.Exam_Type_Code','mstr_room.Room_Name','employee1.Full_Name as Pengawas_1','employee2.Full_Name as Pengawas_2',
          (DB::raw("(SELECT COUNT(*) FROM acd_offered_course_exam_member WHERE acd_offered_course_exam_member.Offered_Course_Exam_Id = acd_offered_course_exam.Offered_Course_Exam_Id) as Jml_Peserta")))->paginate($rowpage);
          $data->appends(['currentsearch'=> $currentsearch, 'currentrowpage'=> $currentrowpage, 'currentpage'=> $currentpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);

          return view('cetak/show_jadwaldanpesertaujian')->with('query', $data)->with('ma', $ma)->with('Offered_Course_id', $id)->with('department', $department)->with('class_program', $class_program)->with('term_year', $term_year)->with('currentsearch',$currentsearch)->with('currentpage', $currentpage)->with('currentrowpage', $currentrowpage)->with('rowpage', $rowpage);
      }



      public function create()
      {

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

      }

      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */

      public function peserta($id)
      {

      }
      public function create_peserta()
      {

      }

      public function store_peserta(Request $request)
      {

      }


      public function edit($id)
      {

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

      }

      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function destroy($id)
      {

      }

      public function destroy_peserta($id)
      {

      }

      public function export($id)
      {

        $data = DB::table('acd_offered_course_exam')
        ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
        ->join('mstr_department', 'mstr_department.Department_Id', '=' , 'acd_offered_course.Department_Id')
        ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
        ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')
        ->join('mstr_room','mstr_room.Room_Id','=','acd_offered_course_exam.Room_Id')
        ->leftjoin('emp_employee as employee1' , 'employee1.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_1')
        ->leftjoin('emp_employee as employee2' , 'employee2.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_2')
        ->leftjoin('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Offered_Course_id',  '=' , 'acd_offered_course_exam.Offered_Course_id')
        ->leftjoin('emp_employee as dosen_matakuliah', 'dosen_matakuliah.Employee_Id', '=', 'acd_offered_course_lecturer.Employee_Id')

        // ->join('mstr_exam_type', 'mstr_exam_type.Exam_Type_Id', '=', 'acd_offered_course_exam.Exam_Type_Id')
        // ->join('mstr_room','mstr_room.Room_Id','=','acd_offered_course_exam.Room_Id')
        // ->join('emp_employee as employee1' , 'employee1.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_1')
        // ->join('emp_employee as employee2' , 'employee2.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_2')
        // ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')
        // ->join('mstr_class' ,'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')
        // ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
        // ->join('mstr_department', 'mstr_department.Department_Id', '=' , 'acd_offered_course.Department_Id')
        // ->leftjoin('acd_Offered_Course_Lecturer', 'acd_Offered_Course_Lecturer.Offered_Course_id',  '=' , 'acd_offered_course.Offered_Course_id')
        // ->leftjoin('emp_employee', 'emp_employee.Employee_Id', '=', 'acd_Offered_Course_Lecturer.Employee_Id')
        ->where('Offered_Course_Exam_Id', $id)
        ->select('acd_offered_course_exam.*', 'dosen_matakuliah.Name as Dosen','dosen_matakuliah.First_Title as DosenF','dosen_matakuliah.Last_Title as DosenL' , 'mstr_department.Department_Name', 'acd_course.*', 'mstr_room.Room_Name','mstr_faculty.Faculty_Name','employee1.First_Title as Pengawas_1F', 'employee2.First_Title as Pengawas_2F','employee1.Name as Pengawas_1','employee2.Name as Pengawas_2', 'employee1.Last_Title as Pengawas_1L', 'employee2.Last_Title as Pengawas_2L')->first();

        // ,'acd_offered_course.*', 'mstr_department.Department_Name', 'emp_employee.Full_Name as Dosen', 'acd_course.*','mstr_class.*','mstr_exam_type.Exam_Type_Code','mstr_room.Room_Name','employee1.Full_Name as Pengawas_1','employee2.Full_Name as Pengawas_2

        $member = DB::table('acd_offered_course_exam_member')->join('acd_student','acd_student.Student_Id','acd_offered_course_exam_member.Student_Id')->where('Offered_Course_Exam_Id', $id)->get();

        $exam_type = DB::table('acd_offered_course_exam')->where('Offered_Course_Exam_Id', $id)->select('Exam_Type_Id')->first();
        $faculty=DB::table('acd_student')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
        ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

        View()->share(['faculty'=>$faculty,'data'=> $data,'member' => $member,'exam_type'=>$exam_type]);
        $pdf = PDF::loadView('cetak/export_jadwaldanpesertaujian');
        return $pdf->stream('jadwal_peserta_ujian.pdf');
        // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);

      }

  }
