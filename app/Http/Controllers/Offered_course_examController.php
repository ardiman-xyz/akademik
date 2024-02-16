<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use App\AcdSchedReal;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;
use Excel;
use App\GetDepartment;

class Offered_course_examController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','update','store','show','edit','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
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
       $date = Input::get('date');
       $rowpage = Input::get('rowpage');
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

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
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_department_class_program.Department_Id', $department)
      ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
      ->get();

      // if ($search == null) {
        $data = DB::table('acd_offered_course')
        ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')

        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
        ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
        ->leftjoin('acd_offered_course_exam','acd_offered_course_exam.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
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
        });

        if($search!=null){
          $data = $data->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
          });
        } 

       if(isset($_GET['date'])&&$_GET['date']!=null){
          $data = $data->whereDate('acd_offered_course_exam.Exam_Start_Date',$_GET['date']);
       }

        $data = $data
        ->where('acd_offered_course.Department_Id', $department)
        ->where('acd_offered_course.Class_Prog_Id', $class_program)
        ->where('acd_offered_course.Term_Year_Id', $term_year)
        ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name',
        DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as start_date'),
        DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_End_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as end_date'),
        DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Room_Id SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as room_id'),
        DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Type_Id SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as eti'),
        DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'),
        DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
        DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
        ->orderBy('start_date', 'desc')
        ->orderBy('acd_course.Course_Name', 'asc')
        ->orderBy('mstr_class.class_Name', 'asc')->paginate($rowpage);

        
      // }else {
      //   // dd('maintenance');
      //   $data = DB::table('acd_offered_course')
      //   ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
      //   ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')

      //   ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
      //   ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
      //   ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
      //   ->join('acd_offered_course_exam','acd_offered_course_exam.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
      //   ->leftjoin('acd_student_krs' ,function ($join)
      //   {
      //     $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
      //     ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
      //     ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
      //     ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
      //   })
      //   ->leftjoin('acd_student' , function ($join)
      //   {
      //     $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
      //     ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
      //   })
      //   ->where('acd_offered_course.Department_Id', $department)
      //   ->where('acd_offered_course.Class_Prog_Id', $class_program)
      //   ->where('acd_offered_course.Term_Year_Id', $term_year)
      //   ->select( 'acd_offered_course.*','acd_course.*', 'acd_offered_course_exam.Exam_Start_Date', 'mstr_class.Class_Name',
      //   DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as start_date'),
      //   DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_End_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as end_date'),
      //   DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Room_Id SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as room_id'),
      //   DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Type_Id SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as eti'),
      //   DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'),
      //   DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
      //   DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
      //   ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
      //   ->orderBy('start_date', 'asc')
      //   ->orderBy('acd_course.Course_Name', 'asc')
      //   ->orderBy('mstr_class.class_Name', 'asc')
      //   ->paginate($rowpage);
        
      // } 
       $data->appends(['search'=> $search, 'date'=>$date, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);

       return view('acd_offered_course_exam/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('date',$date);
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
        $currentsearch = Input::get('currsearch');
        $currentpage = Input::get('currpage');
        $currentrowpage = Input::get('currrowpage');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');
        $id = Input::get('id');
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $term_year = Input::get('term_year');

        $ma = DB::table('acd_course')
          ->join('acd_offered_course' ,'acd_offered_course.Course_Id', '=', 'acd_course.Course_Id')
          ->leftjoin('mstr_class' ,'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')
          ->where('Offered_Course_id', $id)->first();

        $count_mahasiswa = DB::table('acd_offered_course')
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
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->select(DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
    ->orderBy('acd_course.Course_Name', 'asc')
    ->orderBy('mstr_class.class_Name', 'asc')
    ->first();

         $ruang = DB::table('acd_offered_course_exam')->select('Room_Id');
         $select_exam_type = DB::table('mstr_exam_type')->get();
         $select_room = DB::table('mstr_room')->get();
         $select_employee = DB::table('emp_employee')->get();

        return view('acd_offered_course_exam/create')
        ->with('Offered_Course_id', $id)
        ->with('ma', $ma)
        ->with('count_mahasiswa', $count_mahasiswa)
        ->with('select_exam_type', $select_exam_type)
        ->with('select_room', $select_room)
        ->with('select_employee', $select_employee)
        ->with('department', $department)
        ->with('class_program', $class_program)
        ->with('term_year', $term_year)
        ->with('currentsearch',$currentsearch)
        ->with('currentpage',$currentpage)
        ->with('currentrowpage',$currentrowpage)
        ->with('page', $page)->with('rowpage', $rowpage);
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
          'Room_Number' => 'required',
          'Offered_Course_id' => 'required',
          'Exam_Type_Id' => 'required',
          'Room_Id' => 'required',
          'Exam_Start_Date' => 'required',
          // 'Exam_End_Date' => 'required',
          // 'Inspector_Id_1' => 'required',
          // 'Inspector_Id_2' => 'required',
        ]);

        $term_year1 = Input::get('term_year');
          if($term_year1 == null){
            $term_year =  $request->session()->get('term_year');
          }else{
            $term_year = Input::get('term_year');
        }

              $Room_Number = Input::get('Room_Number');
              $Offered_Course_id = Input::get('Offered_Course_id');
              $Exam_Type_Id = Input::get('Exam_Type_Id');
              $Room_Id = Input::get('Room_Id');
              $Exam_Start_Date = Input::get('Exam_Start_Date');
              $Exam_End_Date = Input::get('Exam_End_Date');
              $Inspector_Id_1 = Input::get('Inspector_Id_1');
              $Inspector_Id_2 = Input::get('Inspector_Id_2');
              $class_program = Input::get('class_program');
              $department = Input::get('department');
              $jml_peserta = Input::get('jml_peserta');

              
              $exam_today = DB::table('acd_offered_course_exam')->where('Exam_Start_Date',$Exam_Start_Date)->select('Room_Id')->get();
              
              foreach ($exam_today as $key) {
                if(in_array($key->Room_Id,$Room_Id)){
                  $cek_room = DB::table('mstr_room')->where('Room_Id',$key->Room_Id)->first();
                  return Redirect::back()->withErrors('Sudah ada Ujian Pada '.$cek_room->Room_Name.' Tanggal '.$Exam_Start_Date);
                }
              }
              
              if($Exam_Type_Id != 3){
              $cek_kuota = 0;
              foreach ($Room_Id as $key) {
                $cek_room = DB::table('mstr_room')->where('Room_Id',$key)->first();
                $cek_kuota = $cek_kuota + $cek_room->Capacity_Exam;
              }
              if($cek_kuota < $jml_peserta){
                return Redirect::back()->withErrors('Total Kapasitas Ruang Ujian Kurang dari Jumlah peserta '.$jml_peserta);
              }

              $data_krs = DB::table('acd_student_krs')
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
                ->where('acd_student_krs.Is_Approved', 1)
                ->where('acd_offered_course.Offered_Course_id', $Offered_Course_id)
                ->select('acd_student_krs.Krs_Id','acd_student.*','acd_offered_course.*')
                ->orderBy('acd_student.Nim')
                ->get();

                foreach ($data_krs as $dtx) {
                      //start uas
                      if($Exam_Type_Id == 1){
                        $totalpertemuan = AcdSchedReal::where('Course_Id',$dtx->Course_Id)->where('Term_Year_Id',$dtx->Term_Year_Id)->where('Class_Prog_Id',$dtx->Class_Prog_Id)->count();
                        if($totalpertemuan == 0){
                          return Redirect::back()->withErrors('Belum Ada Presensi Masuk');
                        }
                      }
                    }

                $ii=1;
                foreach ($Room_Id as $keyroom) {
                  $cek_data = DB::table('acd_offered_course_exam')->where('Room_Id',$keyroom)->first();
                  $roomnya = DB::table('mstr_room')->where('Room_Id',$keyroom)->first();
                  $cek_data_c = DB::table('acd_offered_course_exam')->where('Room_Id',$keyroom)->count();
                  if($cek_data_c > 0){
                    $date_input = Date("Y-m-d H:i:s",strtotime($Exam_Start_Date));
                    if($date_input == $cek_data->Exam_Start_Date){
                      // dd([$date_input,$cek_data->Exam_Start_Date]);
                      return Redirect::back()->withErrors($date_input.' Sudah Digunakan Pada Ruang '.$roomnya->Room_Name);
                    }else{
                      DB::table('acd_offered_course_exam')->insert([
                      'Offered_Course_Id' => $Offered_Course_id,
                      'Exam_Type_Id' => $Exam_Type_Id,
                      'Exam_Start_Date' => $Exam_Start_Date, 
                      'Room_Number' => $ii,
                      'Room_Id' => $keyroom,
                      'Created_Date' => date('Y-m-d H:i:s')
                    ]);

                    $room = DB::table('acd_offered_course_exam as a')
                    ->join('mstr_room as b','a.Room_Id','=','b.Room_Id')
                    ->select('a.Created_Date','a.Offered_Course_Exam_Id','b.Capacity_Exam')
                    ->orderBy('Offered_Course_Exam_Id', 'desc')->first();

                      $i = 1;
                      $data_presensi = [];
                    foreach ($data_krs as $dt) {
                      //start uas
                      if($Exam_Type_Id == 1){
                        $totalpertemuan = AcdSchedReal::where('Course_Id',$dt->Course_Id)->where('Term_Year_Id',$dt->Term_Year_Id)->where('Class_Prog_Id',$dt->Class_Prog_Id)->where('Class_Id',$dt->Class_Id)->count();
                        $totalpertemuan_data = AcdSchedReal::where('Course_Id',$dt->Course_Id)->where('Term_Year_Id',$dt->Term_Year_Id)->where('Class_Prog_Id',$dt->Class_Prog_Id)->where('Class_Id',$dt->Class_Id)->get();
                        if($totalpertemuan == 0){
                          return Redirect::back()->withErrors('Belum Ada Presensi Masuk');
                        }
                        $pertemuan_mhs = 0;
                        foreach ($totalpertemuan_data as $t_pertemuan) {
                          // $data_presensi['Persen'] = round(DB::table('acd_sched_real_detail')->where([['Student_Id',$dt->Student_Id]])->count()/$totalpertemuan*100,2);
                          $pertemuan_mhs_c = DB::table('acd_sched_real_detail')->where([['Student_Id',$dt->Student_Id],['Sched_Real_Id',$t_pertemuan->Sched_Real_Id]])->count();
                          if($pertemuan_mhs_c > 0){
                            $pertemuan_mhs++;
                          }
                        }
                        // dd($pertemuan_mhs);
                        $data_presensi['Persen'] = round(($pertemuan_mhs/$totalpertemuan)*100,2);
                        
                        // if($data_presensi['Persen'] > 74 ){
                          if ($i > $room->Capacity_Exam) {
                            break;
                          } else {
                            if($ii == 1){
                              $check_student = DB::table('acd_offered_course_exam_member')->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id],
                                ['Student_Id', $dt->Student_Id]
                              ])->count();
                              // dd($check_student);
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 2){
                              $check_student = DB::table('acd_offered_course_exam_member')->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 3){
                              $check_student = DB::table('acd_offered_course_exam_member')
                              ->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                                ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 2],
                                ['Student_Id', $dt->Student_Id]
                                ])
                              ->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 4){
                              $check_student = DB::table('acd_offered_course_exam_member')
                              ->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 2],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 3],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->count();
                              
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                  ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }
                          }
                          $pertemuan_mhs = 0;
                        // }else{
                        //   $pertemuan_mhs = 0;
                        //   continue;
                        // }
                      }else{
                        if ($i > $room->Capacity_Exam) {
                            break;
                          } else {
                            if($ii == 1){
                              $check_student = DB::table('acd_offered_course_exam_member')->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id],
                                ['Student_Id', $dt->Student_Id]
                              ])->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 2){
                              $check_student = DB::table('acd_offered_course_exam_member')->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 3){
                              $check_student = DB::table('acd_offered_course_exam_member')
                              ->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                                ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 2],
                                ['Student_Id', $dt->Student_Id]
                                ])
                              ->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 4){
                              $check_student = DB::table('acd_offered_course_exam_member')
                              ->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 2],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 3],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->count();
                              
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                  ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }
                          }
                      }
                      //end uas
                    }
                    $ii++;
                    }
                  }else{
                    DB::table('acd_offered_course_exam')->insert([
                      'Offered_Course_Id' => $Offered_Course_id,
                      'Exam_Type_Id' => $Exam_Type_Id,
                      'Exam_Start_Date' => $Exam_Start_Date, 
                      'Room_Number' => $ii,
                      'Room_Id' => $keyroom,
                      'Created_Date' => date('Y-m-d H:i:s')
                    ]);

                    $room = DB::table('acd_offered_course_exam as a')
                    ->join('mstr_room as b','a.Room_Id','=','b.Room_Id')
                    ->select('a.Created_Date','a.Offered_Course_Exam_Id','b.Capacity_Exam')
                    ->orderBy('Offered_Course_Exam_Id', 'desc')->first();

                      $i = 1;
                      $data_presensi = [];
                    foreach ($data_krs as $dt) {
                      //start uas
                      if($Exam_Type_Id == 1){
                        $totalpertemuan = AcdSchedReal::where('Course_Id',$dt->Course_Id)->where('Term_Year_Id',$dt->Term_Year_Id)->where('Class_Prog_Id',$dt->Class_Prog_Id)->count();     
                        $data_presensi['Persen'] = round(DB::table('acd_sched_real_detail')->where([['Student_Id',$dt->Student_Id]])->count()/$totalpertemuan*100,2);
                        
                        // if($data_presensi['Persen'] > 74 ){
                          if ($i > $room->Capacity_Exam) {
                            break;
                          } else {
                            if($ii == 1){
                              $check_student = DB::table('acd_offered_course_exam_member')->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id],
                                ['Student_Id', $dt->Student_Id]
                              ])->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 2){
                              $check_student = DB::table('acd_offered_course_exam_member')->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 3){
                              $check_student = DB::table('acd_offered_course_exam_member')
                              ->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 2],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 4){
                              $check_student = DB::table('acd_offered_course_exam_member')
                              ->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 2],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 3],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }
                          }
                        // }else{
                        //   continue;
                        // }
                      }else{
                        if ($i > $room->Capacity_Exam) {
                            break;
                          } else {
                            if($ii == 1){
                              $check_student = DB::table('acd_offered_course_exam_member')->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id],
                                ['Student_Id', $dt->Student_Id]
                              ])->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 2){
                              $check_student = DB::table('acd_offered_course_exam_member')->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 3){
                              $check_student = DB::table('acd_offered_course_exam_member')
                              ->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 2],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }elseif($ii == 4){
                              $check_student = DB::table('acd_offered_course_exam_member')
                              ->where([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 1],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 2],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->orwhere([
                                ['Offered_Course_Exam_Id', $room->Offered_Course_Exam_Id - 3],
                                ['Student_Id', $dt->Student_Id]
                              ])
                              ->count();
        
                              if ($check_student == 0) {
                                DB::table('acd_offered_course_exam_member')->insert([
                                  'Offered_Course_Exam_Id' => $room->Offered_Course_Exam_Id,
                                  'Student_Id' => $dt->Student_Id
                                ]);
        
                                $i++;
                              } else {
                                continue;
                              }
                            }
                          }
                      }
                      //end uas
                    }
                    $ii++;
                  }                  
              }

              }else{
              $is = 1;
              foreach ($Room_Id as $key) {
                // dd([[$Offered_Course_id],[$Exam_Type_Id],[$Exam_Start_Date],[$is],[$key]]);
                DB::table('acd_offered_course_exam')->insert([
                        'Offered_Course_Id' => $Offered_Course_id,
                        'Exam_Type_Id' => $Exam_Type_Id,
                        'Exam_Start_Date' => $Exam_Start_Date, 
                        'Room_Number' => $is,
                        'Room_Id' => $key,
                        'Created_Date' => date('Y-m-d H:i:s')
                      ]);
                $is++;
              }
            }
              //   try {
              //    DB::table('acd_offered_course_exam')
              //    ->insertGetId(
              //    ['Offered_Course_id' => $Offered_Course_id, 
              //    'Exam_Type_Id' => $Exam_Type_Id,
              //    'Room_Number' => $Room_Number, 
              //    'Room_Id' => $Room_Id, 
              //    'Exam_Start_Date' => $Exam_Start_Date, 
              //    'Exam_End_Date' => $Exam_End_Date, 
              //    'Inspector_Id_1' => $Inspector_Id_1, 
              //    'Inspector_Id_2' => $Inspector_Id_2]);
  
                  return Redirect::to('/setting/offered_course_exam/'.$Offered_Course_id.'?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department)->withErrors('Berhasil Menambah Jadwal Peserta Ujian');
              //   } catch (\Exception $e) {
              //     return Redirect::back()->withErrors('Gagal Menambah Jadwal Kuliah');
              //   }
              // }
      }


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

          $ma = DB::table('acd_course')
          ->join('acd_offered_course' ,'acd_offered_course.Course_Id', '=', 'acd_course.Course_Id')
          ->leftjoin('mstr_class' ,'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')
          ->where('Offered_Course_id', $id)->first();
          // ->join('acd_offered_course' ,'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
          // ->leftjoin('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')
          // ->join('mstr_class' ,'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')->first();

          $data = DB::table('acd_offered_course_exam')
          ->join('mstr_exam_type', 'mstr_exam_type.Exam_Type_Id', '=', 'acd_offered_course_exam.Exam_Type_Id')
          ->join('mstr_room','mstr_room.Room_Id','=','acd_offered_course_exam.Room_Id')
          ->leftjoin('emp_employee as employee1' , 'employee1.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_1')
          ->leftjoin('emp_employee as employee2' , 'employee2.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_2')
          ->where('Offered_Course_id', $id)
          ->select('acd_offered_course_exam.*','mstr_exam_type.Exam_Type_Code','mstr_room.Room_Name', 'employee1.First_Title as Pengawas_1F', 'employee2.First_Title as Pengawas_2F','employee1.Name as Pengawas_1','employee2.Name as Pengawas_2', 'employee1.Last_Title as Pengawas_1L', 'employee2.Last_Title as Pengawas_2L',
          (DB::raw("(SELECT COUNT(*) FROM acd_offered_course_exam_member WHERE acd_offered_course_exam_member.Offered_Course_Exam_Id = acd_offered_course_exam.Offered_Course_Exam_Id) as Jml_Peserta")))
          ->paginate($rowpage);
          // ->get();
        // dd($data);
          $data->appends(['currentsearch'=> $currentsearch, 'currentrowpage'=> $currentrowpage, 'currentpage'=> $currentpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);

          return view('acd_offered_course_exam/show')->with('query', $data)->with('ma', $ma)->with('Offered_Course_id', $id)->with('department', $department)->with('class_program', $class_program)->with('term_year', $term_year)->with('currentsearch',$currentsearch)->with('currentpage', $currentpage)->with('currentrowpage', $currentrowpage)->with('rowpage', $rowpage);
      }

      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */

      public function peserta($id)
      {
        $currentsearch = Input::get('currsearch');
        $currentpage = Input::get('currpage');
        $currentrowpage = Input::get('currrowpage');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');

        $jenis = DB::table('acd_offered_course_exam as a')
        ->where('Offered_Course_Exam_Id',$id)
        ->first();

        $room = DB::table('acd_offered_course_exam as a')
        ->join('mstr_room as b','a.Room_Id','=','b.Room_Id')
        ->select('a.Created_Date','a.Offered_Course_Exam_Id','b.Capacity_Exam')
        ->where('Offered_Course_Exam_Id',$id)
        ->orderBy('Offered_Course_Exam_Id', 'desc')->first();

         $data = DB::table('acd_offered_course_exam')
         ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
         ->join('mstr_exam_type', 'mstr_exam_type.Exam_Type_Id', '=', 'acd_offered_course_exam.Exam_Type_Id')
         ->join('mstr_room','mstr_room.Room_Id','=','acd_offered_course_exam.Room_Id')
         ->leftjoin('emp_employee as employee1' , 'employee1.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_1')
         ->leftjoin('emp_employee as employee2' , 'employee2.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_2')
         ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')->join('mstr_class' ,'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')
         ->where('Offered_Course_Exam_Id', $id)
         ->select('acd_offered_course_exam.*','acd_offered_course.*','acd_course.*','mstr_class.*','mstr_exam_type.Exam_Type_Code','mstr_room.Room_Name','employee1.First_Title as Pengawas_1F', 'employee2.First_Title as Pengawas_2F','employee1.Name as Pengawas_1','employee2.Name as Pengawas_2', 'employee1.Last_Title as Pengawas_1L', 'employee2.Last_Title as Pengawas_2L')->first();

         if($jenis->Exam_Type_Id == 3){
          $member = DB::table('acd_offered_course_exam_member')
         ->join('acd_student','acd_student.Student_Id','acd_offered_course_exam_member.Student_Id')
          ->join('acd_offered_course_exam','acd_offered_course_exam.Offered_Course_Exam_Id','=','acd_offered_course_exam_member.Offered_Course_Exam_Id')
          ->join('acd_offered_course','acd_offered_course.Offered_Course_Id','=','acd_offered_course_exam.Offered_Course_Id')
          ->join('acd_student_krs' ,function ($join)
          {
            $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
            ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id')
            ->on('acd_student_krs.Student_Id','=','acd_student.Student_Id');
          })
          ->where('acd_offered_course_exam.Offered_Course_Exam_Id',$id)      
          ->where('acd_student_krs.Is_Remediasi',1)   
         ->orderby('acd_student.Nim','asc')
         ->get();
         }else{
          $member = DB::table('acd_offered_course_exam_member')
         ->join('acd_student','acd_student.Student_Id','acd_offered_course_exam_member.Student_Id')
          ->join('acd_offered_course_exam','acd_offered_course_exam.Offered_Course_Exam_Id','=','acd_offered_course_exam_member.Offered_Course_Exam_Id')
          ->join('acd_offered_course','acd_offered_course.Offered_Course_Id','=','acd_offered_course_exam.Offered_Course_Id')
          ->join('acd_student_krs' ,function ($join)
          {
            $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
            ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id')
            ->on('acd_student_krs.Student_Id','=','acd_student.Student_Id');
          })
          ->where('acd_offered_course_exam.Offered_Course_Exam_Id',$id)      
          // ->where('acd_student_krs.Is_Remediasi',1)   
         ->orderby('acd_student.Nim','asc')
         ->get();
         }
         

         return view('acd_offered_course_exam/peserta')
         ->with('Offered_Course_id', $id)
         ->with('room', $room)
         ->with('query', $data)
         ->with('member', $member)
         ->with('currentsearch',$currentsearch)
         ->with('currentpage',$currentpage)
         ->with('currentrowpage',$currentrowpage)
         ->with('page', $page)
         ->with('rowpage', $rowpage);
      }
      public function create_peserta(Request $request)
      {
        $id = Input::get('id');
        $currentsearch = Input::get('currentsearch');
        $currentpage = Input::get('currentpage');
        $currentrowpage = Input::get('currentrowpage');
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $term_year = Input::get('term_year');
        $FacultyId = Auth::user()->Faculty_Id;
        $entry_year = Input::get('entry_year');
        $offered_course_id = $request->offered_course_id;

        $exam = DB::table('acd_offered_course_exam')->where('Offered_Course_Exam_Id',$id)->first();

        $member = DB::table('acd_offered_course_exam_member as a')
        ->join('acd_offered_course_exam as b','a.Offered_Course_Exam_Id','=','b.Offered_Course_Exam_Id')
        ->where('b.Offered_Course_Id',$offered_course_id)
        ->where('b.Exam_Type_Id',$exam->Exam_Type_Id)
        ->select('a.Student_Id');
        $select_entry_year = DB::table('mstr_entry_year')->orderBy('Entry_Year_Code','desc')->get();

        $data = DB::table('acd_student_krs')
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
                ->where('acd_student_krs.Is_Approved', 1)
                ->where('acd_offered_course.Offered_Course_id', $request->offered_course_id)
                ->where('acd_student.Entry_Year_Id', $entry_year)
                ->where('acd_student.Department_Id', $department)
                ->where('acd_student.Class_Prog_Id', $class_program)
                ->where(function($query)use($member){
                  $query->wherenotin('acd_student.Student_Id',$member);
                })
                // ->WhereNotIn('Student_Id', $member)
                ->select('acd_student_krs.Krs_Id','acd_student.*','acd_offered_course.*')
                ->orderBy('acd_student.Nim')
                ->get();
                // dd($data);

        return view('acd_offered_course_exam/create_peserta')->with('offered_course_id',$offered_course_id)->with('Offered_Course_Exam_Id', $id)->with('Entry_Year_Id', $entry_year)->with('query', $data)->with('select_entry_year', $select_entry_year)->with('department', $department)->with('term_year', $term_year)->with('class_program', $class_program)->with('currentsearch',$currentsearch)->with('currentpage',$currentpage)->with('currentrowpage',$currentrowpage);

      }

      public function store_peserta(Request $request)
      {
        $this->validate($request,[
          'Offered_Course_Exam_Id' => 'required',
        ]);

        $Offered_Course_Exam_Id = Input::get('Offered_Course_Exam_Id');
        $Student_Id = Input::get('Student_Id');
        $room = DB::table('acd_offered_course_exam as a')
        ->where('Offered_Course_Exam_Id',$request->Offered_Course_Exam_Id)
        ->join('mstr_room as b','a.Room_Id','=','b.Room_Id')
        ->select('a.Created_Date','a.Offered_Course_Exam_Id','b.Capacity_Exam')
        ->orderBy('Offered_Course_Exam_Id', 'desc')->first();

        // try {
          $i = 1;
          foreach ($Student_Id as $data) {
            $check_student = DB::table('acd_offered_course_exam_member')->where([
                          ['Offered_Course_Exam_Id', $Offered_Course_Exam_Id],
                        ])->count();
            if($room->Capacity_Exam == 0  || $i > $room->Capacity_Exam || $check_student == $room->Capacity_Exam){
              return Redirect::back()->withErrors('Kelas Penuh');
            }else{
              DB::table('acd_offered_course_exam_member')
              ->insert(
                ['Offered_Course_Exam_Id' => $Offered_Course_Exam_Id, 'Student_Id' => $data]);
            }
            
          $i++;
          }  
          return Redirect::back()->withErrors('Berhasil Menambah Peserta Ujian');
        // } catch (\Exception $e) {
        //   return Redirect::back()->withErrors('Gagal Menambah Peserta Ujian');
        // }
      }

      public function store_presence(Request $request){
        $offered_course_id = $request->ocei;
        $Student_Id = $request->Student_Id;
        $i=1;

        try {
            $deleted = DB::table('acd_offered_course_exam_member')->where([['Offered_Course_Exam_Id',$offered_course_id]])->update(['Is_Presence'=>null]);
          foreach ($Student_Id as $key) {
            $insert = DB::table('acd_offered_course_exam_member')->where([['Offered_Course_Exam_Id',$offered_course_id],['Student_Id',$key]])->update(['Is_Presence'=>true]);
            $i++;
          }
          return response()->json([
                  'status' => 200,
                  'message' => 'Presensi Ujian Telah Diupdate.',
                  'data' => $i,
              ]);
          } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Menambah Peserta Ujian');
        }
      }


      public function edit($id)
      {
        $currentsearch = Input::get('currsearch');
        $currentpage = Input::get('currpage');
        $currentrowpage = Input::get('currrowpage');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');


         $data = DB::table('acd_offered_course_exam')
         ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
         ->join('mstr_exam_type', 'mstr_exam_type.Exam_Type_Id', '=', 'acd_offered_course_exam.Exam_Type_Id')
         ->join('mstr_room','mstr_room.Room_Id','=','acd_offered_course_exam.Room_Id')
         ->leftjoin('emp_employee as employee1' , 'employee1.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_1')
         ->leftjoin('emp_employee as employee2' , 'employee2.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_2')
         ->where('Offered_Course_Exam_Id', $id)->select('acd_offered_course_exam.*','acd_offered_course.*','mstr_exam_type.Exam_Type_Code','mstr_room.Room_Name','employee1.Full_Name as Pengawas_1','employee2.Full_Name as Pengawas_2')->first();

         $select_exam_type = DB::table('mstr_exam_type')->get();
         $select_room = DB::table('mstr_room')->get();
         $select_employee = DB::table('emp_employee')->get();

        return view('acd_offered_course_exam/edit')->with('Offered_Course_id', $id)->with('query', $data)->with('select_exam_type', $select_exam_type)->with('select_room', $select_room)->with('select_employee', $select_employee)->with('currentsearch',$currentsearch)->with('currentpage',$currentpage)->with('currentrowpage',$currentrowpage)->with('page', $page)->with('rowpage', $rowpage);
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
          'Room_Number' => 'required|numeric',
          // 'Offered_Course_id' => 'required',
          'Exam_Type_Id' => 'required',
          'Room_Id' => 'required',
          'Exam_Start_Date' => 'required',

        ]);

              $Room_Number = Input::get('Room_Number');
              $Offered_Course_id = Input::get('Offered_Course_id');
              $Exam_Type_Id = Input::get('Exam_Type_Id');
              $Room_Id = Input::get('Room_Id');
              $Exam_Start_Date = Input::get('Exam_Start_Date');
              $Exam_End_Date = Input::get('Exam_End_Date');
              $Inspector_Id_1 = Input::get('Inspector_Id_1');
              $Inspector_Id_2 = Input::get('Inspector_Id_2');



              try {

              $exam_today = DB::table('acd_offered_course_exam')->where('Exam_Start_Date',$Exam_Start_Date)->select('Room_Id','Offered_Course_Id')->get();
              // dd($exam_today,$Offered_Course_id);
              
              foreach ($exam_today as $key) {
                if($key->Offered_Course_Id == $Offered_Course_id){
                  continue;
                }else{
                  $cek_room = DB::table('mstr_room')->where('Room_Id',$key->Room_Id)->first();
                  if($key->Room_Id == $Room_Id){
                    return Redirect::back()->withErrors('Sudah ada Ujian Pada '.$cek_room->Room_Name.' Tanggal '.$Exam_Start_Date);
                  }                  
                }
              }

               DB::table('acd_offered_course_exam')
               ->where('Offered_Course_Exam_Id', $id)
               ->update(
               ['Exam_Type_Id' => $Exam_Type_Id,'Room_Number' => $Room_Number, 'Room_Id' => $Room_Id, 'Exam_Start_Date' => $Exam_Start_Date, 'Exam_End_Date' => $Exam_End_Date, 'Inspector_Id_1' => $Inspector_Id_1, 'Inspector_Id_2' => $Inspector_Id_2]);

                return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');
              } catch (\Exception $e) {
                return Redirect::back()->withErrors('Gagal menyimpan Perubahan');
              }
      }

      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return \Illuminate\Http\Response
       */
      public function destroy($id)
      {
        $q = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id', $id)->delete();
        $rs=DB::table('acd_offered_course_exam')->where('Offered_Course_Exam_Id', $id)->delete();
        echo json_encode($rs);
      }

      public function destroy_peserta(Request $request,$id)
       {
      //   try {
          $q = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Member', $id)->delete();
          echo json_encode($q);
        //   Alert::success('Berhasil Menghapus Data', 'Success');
        //   // return Redirect::back()->withErrors('Berhasil Menghapus Data');
        //   return Redirect::back();
        // } catch (\Exception $e) {
        //   Alert::error('Gagal Menghapus Data, Kemungkinan data msih digunakan', 'Failed');
        //   // return Redirect::back()->withErrors('Gagal Menghapus Data, Kemungkinan data msih digunakan');
        //   return Redirect::back();
        // }
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
        $pdf = PDF::loadView('acd_offered_course_exam/export');
        return $pdf->stream('jadwal_peserta_ujian.pdf');
        // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);

      }

      public function exportall(Request $request,$id)
      {
        ini_set('max_execution_time', 160);
        $department = $request->department;
        $term_year = $request->term_year;
        $class_program = $request->class_program;
        $exam = $request->exam_type;
        $exam_type = DB::table('mstr_exam_type')->where('Exam_Type_Code','like','%'.$exam.'%')->select('Exam_Type_Id')->first();

        $term = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->first();

        if($exam_type->Exam_Type_Id == 3){
          $data = DB::table('acd_offered_course_exam')
          ->join('acd_offered_course_exam_member','acd_offered_course_exam.Offered_Course_Exam_Id','=','acd_offered_course_exam_member.Offered_Course_Exam_Id')
          ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
          ->join('acd_student_krs' ,function ($join)
          {
            $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
            ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id')
            ->on('acd_student_krs.Student_Id','=','acd_offered_course_exam_member.Student_Id');
          })
          ->join('mstr_department', 'mstr_department.Department_Id', '=' , 'acd_offered_course.Department_Id')
          ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=' , 'mstr_education_program_type.Education_Prog_Type_Id')
          ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
          ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')
          ->join('mstr_room','mstr_room.Room_Id','=','acd_offered_course_exam.Room_Id')
          ->leftjoin('emp_employee as employee1' , 'employee1.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_1')
          ->leftjoin('emp_employee as employee2' , 'employee2.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_2')
          ->leftjoin('mstr_class as mc' , 'mc.Class_Id', '=', 'acd_offered_course.Class_Id')
          // ->leftjoin('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Offered_Course_id',  '=' , 'acd_offered_course_exam.Offered_Course_id')
          // ->leftjoin('emp_employee as dosen_matakuliah', 'dosen_matakuliah.Employee_Id', '=', 'acd_offered_course_lecturer.Employee_Id')

          ->where('acd_offered_course.Offered_Course_Id', $id)
          ->where('acd_offered_course_exam.Exam_Type_Id', $exam_type->Exam_Type_Id)
          ->where('acd_student_krs.Is_Remediasi',1) 
          ->select('acd_offered_course.Class_Prog_Id','acd_offered_course.Class_Id','mc.Class_Name','mstr_education_program_type.Acronym','acd_offered_course_exam.*', 'mstr_department.Department_Name', 'acd_course.*', 'mstr_room.Room_Name','mstr_faculty.Faculty_Name','employee1.First_Title as Pengawas_1F', 'employee2.First_Title as Pengawas_2F','employee1.Name as Pengawas_1','employee2.Name as Pengawas_2', 'employee1.Last_Title as Pengawas_1L', 'employee2.Last_Title as Pengawas_2L',
          DB::raw('(SELECT  Group_Concat( emp_employee.Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
          DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
          ->get();
          // dd($data);
          $member = [];
          $i = 0;
          foreach ($data as $key) {
            // dd($key);
            $mhs = DB::table('acd_offered_course_exam_member')
            ->join('acd_student_krs','acd_offered_course_exam_member.Student_Id','acd_student_krs.Student_Id')
            ->join('acd_student','acd_student.Student_Id','acd_offered_course_exam_member.Student_Id')
            ->where('Offered_Course_Exam_Id', $key->Offered_Course_Exam_Id)
            ->where('acd_student_krs.Class_Prog_Id', $key->Class_Prog_Id)
            ->where('acd_student_krs.Course_Id', $key->Course_Id)
            ->where('acd_student_krs.Class_Id', $key->Class_Id)
            ->where('acd_student_krs.Is_Remediasi', 1)
            ->orderby('acd_student.Nim','asc')
            ->get();
            $member['mhs'] = $mhs;
            $member['data'] = $key;
            $i++;
          }
          // dd($member);

          // $data_member = [];
          // $iis = 0;
          // foreach ($member as $key) {
          //   $iii = 0;
          //   foreach ($key['mhs'] as $key2) {
          //     $data_member['mhs'][$iis]= $key2;
          //     $data_member['data']= $key['data'];
          //     $iii++;
          //   }
          //   $iis++;
          // }

          // dd($data_member);

          $kprodi = DB::table('emp_employee_structural as a')
                  ->join('emp_structural', 'emp_structural.Structural_Id','=','a.Structural_Id')
                  ->join('emp_employee', 'emp_employee.Employee_Id','=','a.Employee_Id')
                  ->leftjoin('mstr_work_unit','mstr_work_unit.Work_Unit_Id','=','a.Work_Unit_Id')
                  ->leftjoin('mstr_department as e','mstr_work_unit.Department_Code','=','e.Department_Id')
                  ->where('mstr_work_unit.Department_Code',$department)
                  ->where(function($query){
                      $query->whereRaw("a.Sk_Date = (
                      SELECT MAX(Sk_Date) FROM emp_employee_structural 
                        WHERE 
                        Structural_Id = a.Structural_Id AND Work_Unit_Id = NULL OR
                        Structural_Id = a.Structural_Id AND Work_Unit_Id = a.Work_Unit_Id 
                      )");
                    })
                    ->orderby('mstr_work_unit.Work_Unit_Name','asc')->first();

          $faculty=DB::table('acd_student')
          ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
          ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
          ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

          View()->share(['kprodi'=>$kprodi,'faculty'=>$faculty,'data'=> $data,'member' => $member,
          'exam_type'=>$exam_type,
          'term'=>$term,
          'term_year'=>$term_year,
          'class_prog'=>$class_program]);
          $pdf = PDF::loadView('acd_offered_course_exam/exportremidiall');
          return $pdf->stream('jadwal_peserta_ujian.pdf');

        }else{
        $data = DB::table('acd_offered_course_exam')
        ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_exam.Offered_Course_id')
        ->join('mstr_department', 'mstr_department.Department_Id', '=' , 'acd_offered_course.Department_Id')
        ->join('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=' , 'mstr_education_program_type.Education_Prog_Type_Id')
        ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
        ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')
        ->join('mstr_room','mstr_room.Room_Id','=','acd_offered_course_exam.Room_Id')
        ->leftjoin('emp_employee as employee1' , 'employee1.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_1')
        ->leftjoin('emp_employee as employee2' , 'employee2.Employee_Id', '=', 'acd_offered_course_exam.Inspector_Id_2')
        ->leftjoin('mstr_class as mc' , 'mc.Class_Id', '=', 'acd_offered_course.Class_Id')
        // ->leftjoin('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Offered_Course_id',  '=' , 'acd_offered_course_exam.Offered_Course_id')
        // ->leftjoin('emp_employee as dosen_matakuliah', 'dosen_matakuliah.Employee_Id', '=', 'acd_offered_course_lecturer.Employee_Id')

        ->where('acd_offered_course.Offered_Course_Id', $id)
        ->where('acd_offered_course_exam.Exam_Type_Id', $exam_type->Exam_Type_Id)
        ->select('acd_offered_course.Class_Id','mc.Class_Name','mstr_education_program_type.Acronym','acd_offered_course_exam.*', 'mstr_department.Department_Name', 'acd_course.*', 'mstr_room.Room_Name','mstr_faculty.Faculty_Name','employee1.First_Title as Pengawas_1F', 'employee2.First_Title as Pengawas_2F','employee1.Name as Pengawas_1','employee2.Name as Pengawas_2', 'employee1.Last_Title as Pengawas_1L', 'employee2.Last_Title as Pengawas_2L',
        DB::raw('(SELECT  Group_Concat( emp_employee.Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
        DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
        ->get();
        

        $member = [];
        $i = 0;
        foreach ($data as $key) {
          $mhs = DB::table('acd_offered_course_exam_member')
          ->join('acd_student','acd_student.Student_Id','acd_offered_course_exam_member.Student_Id')
          ->where('Offered_Course_Exam_Id', $key->Offered_Course_Exam_Id)
          ->orderby('acd_student.Nim','asc')
          ->get();
          $member[$i]['mhs'] = $mhs;
          $member[$i]['data'] = $key;
          $i++;
        }

        $data_member = [];
        $iis = 0;
        foreach ($member as $key) {
          $iii = 0;
          foreach ($key['mhs'] as $key2) {
            $data_member[$iis]['mhs'][$iii]= $key2;
            $data_member[$iis]['data']= $key['data'];
            $iii++;
          }
          $iis++;
        }

      }

        $kprodi = DB::table('emp_employee_structural as a')
                  ->join('emp_structural', 'emp_structural.Structural_Id','=','a.Structural_Id')
                  ->join('emp_employee', 'emp_employee.Employee_Id','=','a.Employee_Id')
                  ->leftjoin('mstr_work_unit','mstr_work_unit.Work_Unit_Id','=','a.Work_Unit_Id')
                  ->leftjoin('mstr_department as e','mstr_work_unit.Department_Code','=','e.Department_Id')
                  ->where('mstr_work_unit.Department_Code',$department)
                  ->where(function($query){
                      $query->whereRaw("a.Sk_Date = (
                      SELECT MAX(Sk_Date) FROM emp_employee_structural 
                        WHERE 
                        Structural_Id = a.Structural_Id AND Work_Unit_Id = NULL OR
                        Structural_Id = a.Structural_Id AND Work_Unit_Id = a.Work_Unit_Id 
                      )");
                    })
                    ->orderby('mstr_work_unit.Work_Unit_Name','asc')->first();
        // $data_full = [];
        // foreach ($data_member['mhs'] as $key) {
        //   $s=0;
        //   foreach ($key as $key2) {
        //     $dept = DB::table('mstr_department')->where('Department_Id',$key2->Department_Id)->first();
        //     $data_full[$s]['data'] = $data_member['data'];
        //     $data_full[$s]['Nim'] = $key2->Nim;
        //     $data_full[$s]['Department_Name'] = $dept->Department_Name;
        //     $s++;
        //   }
        // }
        // dd($data_member);
        // dd($data_full);

        // $exam_type = DB::table('acd_offered_course_exam')->where('Offered_Course_Exam_Id', $id)->select('Exam_Type_Id')->first();
        $faculty=DB::table('acd_student')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
        ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

        View()->share(['kprodi'=>$kprodi,'faculty'=>$faculty,'data'=> $data,'member' => $data_member,
        'exam_type'=>$exam_type,
        'term'=>$term,
        'term_year'=>$term_year,
        'class_prog'=>$class_program]);
        $pdf = PDF::loadView('acd_offered_course_exam/exportall');
        return $pdf->stream('jadwal_peserta_ujian.pdf');
      }

      public function exportdata($department,$term_year,$class_program){
          Excel::create('Jadwal Kuliah', function ($excel) use($department, $class_program,$term_year){       
            $items  = DB::table('acd_offered_course')
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
                  ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name',
                  DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as start_date'),
                  DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_End_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as end_date'),
                  DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Room_Id SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as room_id'),
                  DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Type_Id SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course.Offered_Course_id = acd_offered_course_exam.Offered_Course_id) as eti'),
                  DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'),
                  DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
                  DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
                  ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
                  ->orderBy('start_date', 'desc')
                  ->orderBy('acd_course.Course_Name', 'asc')
                  ->orderBy('mstr_class.class_Name', 'asc')
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
              $datauts = [
                  [
                      'NO' => '',
                      'Kode Matakuliah' => '',
                      'Nama Matakuliah' => '',
                      'Kelas' => '',
                      'Jumlah Mahasiswa' => '',
                      'Dosen' => '',
                      'Tanggal' => '',
                      'Jam' => '',
                      'Ruang' => '',
                  ]
              ];
              $datauas = [
                  [
                      'NO' => '',
                      'Kode Matakuliah' => '',
                      'Nama Matakuliah' => '',
                      'Kelas' => '',
                      'Jumlah Mahasiswa' => '',
                      'Dosen' => '',
                      'Tanggal' => '',
                      'Jam' => '',
                      'Ruang' => '',
                  ]
              ];
          }

          $i = 1;
          foreach ($items as $item) {
            $dosen = explode('|',$item->dosen);
            $id_dosen = explode('|',$item->id_dosen);
              // dd($item);
              $n_dosen = "";
              foreach ($id_dosen as $key) {
                  if ($key != null) {
                    $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                    ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                    ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                    ->first();
                    // dd($anu->Department_Id);
                    if($anu->Department_Id != $department){
                      $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                      $firstitle = $dosennya->First_Title;
                      $name = $dosennya->Name;
                      $lasttitle = $dosennya->Last_Title;
                      $n_dosen = $n_dosen.$firstitle." ".$name." ".$lasttitle.' ';
                      // echo "<div class='btn btn-sm' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                    }else{
                      $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                      $firstitle = $dosennya->First_Title;
                      $name = $dosennya->Name;
                      $lasttitle = $dosennya->Last_Title;
                      $n_dosen = $n_dosen.$firstitle." ".$name." ".$lasttitle.' ';
                      // echo "<div class='btn btn-sm' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                    }
                  }
              }


              $startdate = explode('|', $item->start_date);
              $etis = explode('|', $item->eti);
              $roomid = explode('|', $item->room_id);
              $eti_c = 0;
              $date = "";
              $time = "";
              $room = "";
              foreach ($etis as $eti) {
                if($eti == 2){
                  if ($item->start_date != "") {
                        $start = explode(" ",$startdate[$eti_c]);
                        $s_date = $start[0];
                        $date = tanggal_indo($s_date,false);              
                  }
                  if ($item->start_date != "") {
                      $start = explode(" ",$startdate[$eti_c]);
                      $s_date = $start[0];
                      $s_time = explode(":",$start[1]);
                      $s_hour = $s_time[0];
                      $s_minute = $s_time[1];
                      $s_time = $s_hour.'.'.$s_minute;
                      $time = $s_time;
                  }
                if ($item->room_id != "") {
                        $start = explode(" ",$roomid[$eti_c]);
                        $s_room = $start[0];
                        $n_room = DB::table('mstr_room')->where('Room_Id',$s_room)->select('Room_Code')->first();
                        $room = $room.$n_room->Room_Code.', ';
                  }
                }else{
                  $eti_c++;
                  continue;
                }
                $eti_c++;
              }

              $startdate_uas = explode('|', $item->start_date);
              $etis_uas = explode('|', $item->eti);
              $roomid_uas = explode('|', $item->room_id);
              $eti_c_uas = 0;
              $date_uas = "";
              $time_uas = "";
              $room_uas = "";
              foreach ($etis_uas as $eti_uas) {
                if($eti_uas == 2){
                  $eti_c_uas++;
                  continue;
                }else{
                    if ($item->start_date != "") {
                          $start_uas = explode(" ",$startdate_uas[$eti_c_uas]);
                          $s_date_uas = $start_uas[0];
                          $date_uas = tanggal_indo($s_date_uas,false);     
                    }
                    if ($item->start_date != "") {
                        $start_uas = explode(" ",$startdate_uas[$eti_c_uas]);
                        $s_date_uas = $start_uas[0];
                        $s_time_uas = explode(":",$start_uas[1]);
                        $s_hour_uas = $s_time_uas[0];
                        $s_minute_uas = $s_time_uas[1];
                        $s_time_uas = $s_hour_uas.'.'.$s_minute_uas;
                        $time_uas = $s_time_uas;
                    }
                  if ($item->room_id != "") {
                          $start_uas = explode(" ",$roomid_uas[$eti_c_uas]);
                          $s_room_uas = $start_uas[0];
                          $n_room_uas = DB::table('mstr_room')->where('Room_Id',$s_room_uas)->select('Room_Code')->first();
                          $room_uas = $room_uas.$n_room_uas->Room_Code.', ';
                    }
                }
                $eti_c_uas++;
              }

              $datauts[] = [
                  'NO' => $i,
                  'Kode Matakuliah' => $item->Course_Code,
                  'Nama Matakuliah' => $item->Course_Name,
                  'Kelas' => $item->Class_Name,
                  'Jumlah Mahasiswa' => $item->jml_peserta,
                  'Dosen' => $n_dosen,
                  'Tanggal' => $date,
                  'Jam' => $time,
                  'Ruang' => $room,
              ];
              $datauas[] = [
                  'NO' => $i,
                  'Kode Matakuliah' => $item->Course_Code,
                  'Nama Matakuliah' => $item->Course_Name,
                  'Kelas' => $item->Class_Name,
                  'Jumlah Mahasiswa' => $item->jml_peserta,
                  'Dosen' => $n_dosen,
                  'Tanggal' => $date_uas,
                  'Jam' => $time_uas,
                  'Ruang' => $room_uas,
              ];
            $i++;
          }

          $excel->sheet('Jadwal Ujian UTS', function ($sheet) use ($datauts,$items) {
              $sheet->fromArray($datauts, null, 'A1');

              $num_rows = sizeof($datauts) + 1;

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
                  'F' => 20,
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
              
              $sheet->setBorder('A1:I' . (sizeof($datauts) + 1), 'thin');

              $sheet->setHorizontalCentered(true);

              $sheet->cells('A1:I1', function ($cells) {
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
              // foreach ($datauts as $dt) {
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

              foreach ($datauts as $dt) {
                  $sheet->cells('D' . $i . ':E' . sizeof($datauts), function ($cells) {
                      $cells->setAlignment('center');
                  });
                }

              // $last = $i+1;
              // $sheet->cells('E2:E9999', function ($cells) {
              //             $cells->setAlignment('center');
              //     });
              // $sheet->setCellValue('B'.$last, 'STIKES MUHAMMADIYAH PALEMBANG');
          });
          $excel->sheet('Jadwal Ujian UAS', function ($sheet) use ($datauas,$items) {
              $sheet->fromArray($datauas, null, 'A1');

              $num_rows = sizeof($datauas) + 1;

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
                  'F' => 20,
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
              
              $sheet->setBorder('A1:I' . (sizeof($datauas) + 1), 'thin');

              $sheet->setHorizontalCentered(true);

              $sheet->cells('A1:I1', function ($cells) {
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
              // foreach ($datauas as $dt) {
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

              foreach ($datauas as $dt) {
                  $sheet->cells('D' . $i . ':E' . sizeof($datauas), function ($cells) {
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

  }
