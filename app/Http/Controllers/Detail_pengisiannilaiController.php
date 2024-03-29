<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use App\AcdSchedReal;
use App\Http\Models\StrukturalData;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;
use Excel;
use DateTime;
use App\GetDepartment;

class Detail_pengisiannilaiController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
    // $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
    // $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
    // $this->middleware('access:CanDelete', ['except' => ['index','create','update','store','show','edit','destroy_peserta','store_peserta','create_peserta','peserta','export']]);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
   public function index(Request $request)
   {
     ini_set('max_execution_time', 600);
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
      ->where(function($query){
        $search = Input::get('search');
        $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
        $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
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
      ->paginate($rowpage);

    foreach ($data as $key) {
      $startdate = explode('|', $key->start_date);
      $eti = explode('|', $key->eti);
      $jenis = "";
      foreach ($startdate as $key) {
        $start = explode(" ",$key);
        $s_date = $start[0];
        if($jenis == $eti){
        }else{
          if($jenis == 1){
          }else{ 
            $l_date = date('Y-m-d', strtotime($s_date. ' + 6 days'));
            while ($s_date <= $l_date){
              $hari = date('l', strtotime($s_date));
              if($hari == 'Saturday' || $hari == 'Sunday'){
                $l_date = date('Y-m-d', strtotime($l_date. ' + 1 days'));
              }
              $s_date = date('Y-m-d', strtotime($s_date. ' + 1 days'));;
            } 
          }
        }
      }
    }

    $struktural = StrukturalData::getkaprodiS1mesin();
    // dd($struktural);

     $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
     return view('acd_offered_course_exam_nilai/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year);
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

      return view('acd_offered_course_exam_nilai_nilai/create')
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
                      $totalpertemuan = AcdSchedReal::where('Course_Id',$dt->Course_Id)->where('Term_Year_Id',$dt->Term_Year_Id)->where('Class_Prog_Id',$dt->Class_Prog_Id)->count();
                      if($totalpertemuan == 0){
                        return Redirect::back()->withErrors('Belum Ada Presensi Masuk');
                      }
                      $data_presensi['Persen'] = round(DB::table('acd_sched_real_detail')->where([['Student_Id',$dt->Student_Id]])->count()/$totalpertemuan*100,2);
                      
                      if($data_presensi['Persen'] > 74 ){
                        if ($i > $room->Capacity_Exam) {
                          break;
                        } else {
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
                        }
                      }else{
                        break;
                      }
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
                      
                      if($data_presensi['Persen'] > 74 ){
                        if ($i > $room->Capacity_Exam) {
                          break;
                        } else {
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
                        }
                      }else{
                        break;
                      }
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
        (DB::raw("(SELECT COUNT(*) FROM acd_offered_course_exam_member WHERE acd_offered_course_exam_member.Offered_Course_Exam_Id = acd_offered_course_exam.Offered_Course_Exam_Id) as Jml_Peserta")))->paginate($rowpage);
      // dd($data);
        $data->appends(['currentsearch'=> $currentsearch, 'currentrowpage'=> $currentrowpage, 'currentpage'=> $currentpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);

        return view('acd_offered_course_exam_nilai_nilai/show')->with('query', $data)->with('ma', $ma)->with('Offered_Course_id', $id)->with('department', $department)->with('class_program', $class_program)->with('term_year', $term_year)->with('currentsearch',$currentsearch)->with('currentpage', $currentpage)->with('currentrowpage', $currentrowpage)->with('rowpage', $rowpage);
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
      //dd($data);

       $member = DB::table('acd_offered_course_exam_member')
       ->join('acd_student','acd_student.Student_Id','acd_offered_course_exam_member.Student_Id')
       ->where('Offered_Course_Exam_Id', $id)->get();

       return view('acd_offered_course_exam_nilai_nilai/peserta')
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

      $member = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$id)->select('Student_Id');
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

      return view('acd_offered_course_exam_nilai_nilai/create_peserta')->with('offered_course_id',$offered_course_id)->with('Offered_Course_Exam_Id', $id)->with('Entry_Year_Id', $entry_year)->with('query', $data)->with('select_entry_year', $select_entry_year)->with('department', $department)->with('term_year', $term_year)->with('class_program', $class_program)->with('currentsearch',$currentsearch)->with('currentpage',$currentpage)->with('currentrowpage',$currentrowpage);

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

      return view('acd_offered_course_exam_nilai_nilai/edit')->with('Offered_Course_id', $id)->with('query', $data)->with('select_exam_type', $select_exam_type)->with('select_room', $select_room)->with('select_employee', $select_employee)->with('currentsearch',$currentsearch)->with('currentpage',$currentpage)->with('currentrowpage',$currentrowpage)->with('page', $page)->with('rowpage', $rowpage);
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
      ini_set('max_execution_time', 60);
      $department = $request->department;
      $term_year = $request->term_year;
      $class_program = $request->class_program;
      $exam = $request->exam_type;
      $exam_type = DB::table('mstr_exam_type')->where('Exam_Type_Code','like','%'.$exam.'%')->select('Exam_Type_Id')->first();

      $term = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->first();

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
      ->select('mc.Class_Name','mstr_education_program_type.Acronym','acd_offered_course_exam.*', 'mstr_department.Department_Name', 'acd_course.*', 'mstr_room.Room_Name','mstr_faculty.Faculty_Name','employee1.First_Title as Pengawas_1F', 'employee2.First_Title as Pengawas_2F','employee1.Name as Pengawas_1','employee2.Name as Pengawas_2', 'employee1.Last_Title as Pengawas_1L', 'employee2.Last_Title as Pengawas_2L',
      DB::raw('(SELECT  Group_Concat( emp_employee.Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
      DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
      ->get();

      $member = [];
      $i = 0;
      foreach ($data as $key) {
        $mhs = DB::table('acd_offered_course_exam_member')->join('acd_student','acd_student.Student_Id','acd_offered_course_exam_member.Student_Id')->where('Offered_Course_Exam_Id', $key->Offered_Course_Exam_Id)->get();
        $member[$i]['mhs'] = $mhs;
        $member[$i]['data'] = $key;
        $i++;
      }

      // dd($member);

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
        ini_set('max_execution_time', 300);
        Excel::create('Jadwal Pengisian Nilai Dosen', function ($excel) use($department, $class_program,$term_year){       
          $items = DB::table('acd_offered_course')
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
              ->orderBy('start_date', 'asc')
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
            $data = [
                [
                    'Kode Matakuliah' => '',
                    'Nama Matakuliah' => '',
                    'Kelas' => '',
                    'Jumlah Mahasiswa' => '',
                    'Dosen' => '',
                    'Tanggal Ujian' => '',
                    'Tanggal akhir Pengisian' => '',
                    'Peserta Ujian' => '',
                    'Sudah Diisi Nilai' => '',
                ]
            ];
        }

        $i = 1;
        foreach ($items as $item) {
          $dosen = explode('|',$item->dosen);
          $id_dosen = explode('|',$item->id_dosen);
          $n_dosen = "";
            // dd($data);
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
                    $nl_dosen = $firstitle." ".$name." ".$lasttitle;
                    $n_dosen = $n_dosen." ".$nl_dosen." | ";
                    // echo "<div class='btn btn-sm' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                  }else{
                      $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                    $firstitle = $dosennya->First_Title;
                    $name = $dosennya->Name;
                    $lasttitle = $dosennya->Last_Title;
                    $nl_dosen = $firstitle." ".$name." ".$lasttitle;
                    $n_dosen = $n_dosen." ".$nl_dosen." | ";
                    // echo "<div class='btn btn-sm' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                  }
                }
            }

            $startdate = explode('|', $item->start_date);
            $eti = explode('|', $item->eti);
            $n = 0;
            $date = "";
            $date_uts = "";
            $date_uts_nilai = "";
            $date_uas = "";
            $date_uas_nilai = "";
            if ($item->start_date != "") {
              $cti = 0;
              $simpan_date_uts = "";
              $simpan_date_uas = "";
              foreach ($eti as $ddate) {
                if($ddate == 1){
                  $uas = 'UAS / ';
                  $startd = $startdate[$cti];
                    $start = explode(" ",$startd);
                    $s_date = $start[0];
                    if($simpan_date_uas == $s_date){
                      $date = $date." ";
                      // echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                    }else{
                      $date_uas = tanggal_indo($s_date,false)." ";

                      $event_sched_uas = DB::table('mstr_event_sched')->where([['Term_Year_Id',$term_year],['Department_Id',$department],['Event_Id',7]])->first();
                      if($event_sched_uas){
                        $last_date = date('Y-m-d H:i:s', strtotime($s_date. ' + '.($event_sched_uas->Day).' days'));
                      }else{
                        $last_date = date('Y-m-d H:i:s', strtotime($s_date. ' + 8 days'));
                      }
                      
                      while ($s_date <= $last_date){
                        $hari = date('l', strtotime($s_date));
                        if($hari == 'Saturday' || $hari == 'Sunday'){
                          $last_date = date('Y-m-d', strtotime($last_date. ' + 1 days'));
                        }
                        $s_date = date('Y-m-d', strtotime($s_date. ' + 1 days'));
                      }
                      $date_uas_nilai = tanggal_indo($last_date,false)." ";
                      // echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";                              
                    }
                    $simpan_date_uas = $s_date;
                }else{
                  $uts = 'UTS / ';
                  $startd = $startdate[$cti];
                    $start = explode(" ",$startd);
                    $s_date = $start[0];  
                    if($simpan_date_uts == $s_date){
                      $date = $date." ";
                      // echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                    }else{
                      $date = $date." ".$uts.tanggal_indo($s_date,false)." ";
                      $date_uts = tanggal_indo($s_date,false)." ";

                      $event_sched_uts = DB::table('mstr_event_sched')->where([['Term_Year_Id',$term_year],['Department_Id',$department],['Event_Id',6]])->first();
                      if($event_sched_uts){
                        $last_date = date('Y-m-d H:i:s', strtotime($s_date. ' + '.($event_sched_uts->Day).' days'));
                      }else{
                        $last_date = date('Y-m-d H:i:s', strtotime($s_date. ' + 8 days'));
                      }
                      
                      while ($s_date <= $last_date){
                        $hari = date('l', strtotime($s_date));
                        if($hari == 'Saturday' || $hari == 'Sunday'){
                          $last_date = date('Y-m-d', strtotime($last_date. ' + 1 days'));
                        }
                        $s_date = date('Y-m-d', strtotime($s_date. ' + 1 days'));
                      }
                      $date_uts_nilai = tanggal_indo($last_date,false)." ";
                      // dd([[$date_uts],[$date_uts_nilai],[$item]]);
                      
                      // echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                    }
                    $simpan_date_uas = $s_date;
                }
                $cti++;
              }
            }

          $startdate = explode('|', $item->start_date);
          $eti = explode('|', $item->eti);
          $ikt_ujians = 0;
          $ikut_ujian = 0;
          $ikut_ujian_uas_diisi = 0;
          $ikut_ujian_uas = 0;
          $ikut_ujian_uts_diisi = 0;
          $ikut_ujian_uts = 0;
          if($eti[0] != ""){
          $type_uts = 0;
          $type_uas = 0;
          $cti = 0;
          $simpan_date = "";
          foreach ($eti as $ddate) {
            if($ddate == 1){
              $uas = 'UAS / ';
              $startd = $startdate[$cti];
              $start = explode(" ",$startd);
              $s_date = $start[0];  
              $datax = DB::table('acd_offered_course_exam as a')
              ->where('a.Offered_Course_Id',$item->Offered_Course_id)
              ->where('a.Exam_Type_Id',1)
              ->get();
              $ikut_ujian = 0;
              // dd($datax);
              foreach ($datax as $keys) {
                // dd($keys->Offered_Course_Exam_Id);
                $cekdata = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->select('Student_Id')->get();
                // dd($cekdata);
                foreach ($cekdata as $cekdatas) {
                  $krs_id = DB::table('acd_student_krs')
                    ->where('Student_Id',$cekdatas->Student_Id)
                    ->where('Course_Id',$item->Course_Id)
                    ->where('Term_Year_Id',$item->Term_Year_Id)
                    ->where('Class_Prog_Id',$item->Class_Prog_Id)
                    ->where('Class_Id',$item->Class_Id)
                    ->where('Is_Approved',1)
                    ->first();
                  $krs_ids = DB::table('acd_student_krs')
                    ->where('Student_Id',$cekdatas->Student_Id)
                    ->where('Course_Id',$item->Course_Id)
                    ->where('Term_Year_Id',$item->Term_Year_Id)
                    ->where('Class_Prog_Id',$item->Class_Prog_Id)
                    ->where('Class_Id',$item->Class_Id)
                    ->where('Is_Approved',1)
                    ->count();
                  if($krs_ids <= 0){
                  }else{
                    $cekisi = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$krs_id->Krs_Id)->first();
                    // dd($cekisi);
                    if($cekisi == null){
                        $ikut_ujian_uas_diisi = $ikut_ujian_uas_diisi;
                      }else{
                        if($simpan_date != $s_date){
                          if($cekisi->Uas >0 || $cekisi->Uas == '0'){
                            $ikut_ujian_uas_diisi = $ikut_ujian_uas_diisi+1;
                          }
                        }
                    }

                    $cekpresence = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->where('Student_Id',$cekdatas->Student_Id)->first();
                    // dd($cekisi);
                    if($cekpresence == null){
                        $ikut_ujian_uas = $ikut_ujian_uas;
                      }else{
                        if($simpan_date != $s_date){
                          if($simpan_date != $s_date){
                            if($cekpresence->Is_Presence == 1){
                              $ikut_ujian_uas = $ikut_ujian_uas+1;
                            }
                          }
                        }
                    }
                  }
                }
              }

              if($simpan_date == $s_date){
                    $ikut_ujian_uas_diisi = $ikut_ujian_uas_diisi;
                    $ikut_ujian_uas = $ikut_ujian_uas;
                    // echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                  }else{
                    $ikut_ujian_uas_diisi = $ikut_ujian_uas_diisi;
                    $ikut_ujian_uas = $ikut_ujian_uas;
                    // echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.$ikut_ujian."</div>";
                  }
              $simpan_date = $s_date;
            }elseif($ddate == 2){
              $uas = 'UTS / ';
              $startd = $startdate[$cti];
              $start = explode(" ",$startd);
              $s_date = $start[0];  
              $datax = DB::table('acd_offered_course_exam as a')
              ->where('a.Offered_Course_Id',$item->Offered_Course_id)
              ->where('a.Exam_Type_Id',2)
              ->get();
              $ikut_ujian = 0;
              // dd($datax);
              foreach ($datax as $keys) {
                // dd($keys->Offered_Course_Exam_Id);
                $cekdata = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->select('Student_Id')->get();
                // dd($cekdata);
                foreach ($cekdata as $cekdatas) {
                  $krs_id = DB::table('acd_student_krs')
                    ->where('Student_Id',$cekdatas->Student_Id)
                    ->where('Course_Id',$item->Course_Id)
                    ->where('Term_Year_Id',$item->Term_Year_Id)
                    ->where('Class_Prog_Id',$item->Class_Prog_Id)
                    ->where('Class_Id',$item->Class_Id)
                    ->where('Is_Approved',1)
                    ->first();
                  $krs_ids = DB::table('acd_student_krs')
                    ->where('Student_Id',$cekdatas->Student_Id)
                    ->where('Course_Id',$item->Course_Id)
                    ->where('Term_Year_Id',$item->Term_Year_Id)
                    ->where('Class_Prog_Id',$item->Class_Prog_Id)
                    ->where('Class_Id',$item->Class_Id)
                    ->where('Is_Approved',1)
                    ->count();
                  if($krs_ids <= 0){
                  }else{
                    $cekisi = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$krs_id->Krs_Id)->first();
                    if($cekisi == null){
                        $ikut_ujian_uts_diisi = $ikut_ujian_uts_diisi;
                      }else{
                        if($simpan_date != $s_date){
                          if($cekisi->Uas >0 || $cekisi->Uas == '0'){
                            $ikut_ujian_uts_diisi = $ikut_ujian_uts_diisi+1;
                          }
                        }
                    }

                    $cekpresence = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->where('Student_Id',$cekdatas->Student_Id)->first();
                    // dd($cekisi);
                    if($cekpresence == null){
                        $ikut_ujian_uts = $ikut_ujian_uts;
                      }else{
                        if($simpan_date != $s_date){
                          if($cekpresence->Is_Presence == 1){
                            $ikut_ujian_uts = $ikut_ujian_uts+1;
                          }
                        }
                    }
                  }
                }
              }

                if($simpan_date == $s_date){
                    $ikut_ujian_uts_diisi = $ikut_ujian_uts_diisi;
                    $ikut_ujian_uts = $ikut_ujian_uts;
                    // echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                  }else{
                    $ikut_ujian_uts_diisi = $ikut_ujian_uts_diisi;
                    $ikut_ujian_uts = $ikut_ujian_uts;
                    // echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.$ikut_ujian."</div>";
                  }
                  $simpan_date = $s_date;
                }
                $cti++;
              }
            }
            
            $data_uts[] = [
                'NO' => $i,
                'Kode Matakuliah' => $item->Course_Code,
                'Nama Matakuliah' => $item->Course_Name,
                'Kelas' => $item->Class_Name,
                'Jumlah Mahasiswa' => $item->jml_peserta,
                'Dosen' => $n_dosen,
                'Tanggal Ujian' => $date_uts,
                'Tanggal akhir Pengisian' => $date_uts_nilai,
                'Peserta Ujian' => $ikut_ujian_uts,
                'Sudah Diisi Nilai' => $ikut_ujian_uts_diisi,
            ];
            $data_uas[] = [
                'NO' => $i,
                'Kode Matakuliah' => $item->Course_Code,
                'Nama Matakuliah' => $item->Course_Name,
                'Kelas' => $item->Class_Name,
                'Jumlah Mahasiswa' => $item->jml_peserta,
                'Dosen' => $n_dosen,
                'Tanggal Ujian' => $date_uas,
                'Tanggal akhir Pengisian' => $date_uas_nilai,
                'Peserta Ujian' => $ikut_ujian_uas,
                'Sudah Diisi Nilai' => $ikut_ujian_uas_diisi,
            ];
          $i++;
        }

        $excel->sheet('Jadwal UTS', function ($sheet) use ($data_uts,$items) {
            $sheet->fromArray($data_uts, null, 'A1');

            $num_rows = sizeof($data_uts) + 1;

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
            
            $sheet->setBorder('A1:J' . (sizeof($data_uts) + 1), 'thin');

            $sheet->setHorizontalCentered(true);

            $sheet->cells('A1:J1', function ($cells) {
                $cells->setBackground('#97D86E');
                $cells->setFontWeight('bold');
                $cells->setAlignment('center');
            });
            
            foreach ($data_uts as $dt) {
                  $no = ($dt['NO'] + 1);
                  if ($dt['Tanggal Ujian'] < $dt['Tanggal akhir Pengisian'] && $dt['Sudah Diisi Nilai'] < $dt['Peserta Ujian']) {
                      $sheet->cells('A' . $no . ':J' . $no, function ($cells) {
                          $cells->setBackground('#FFD6D6');
                          $cells->setFontColor('#000');
                          $cells->setAlignment('center');
                      });
                  }else{
                    $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
                          $cells->setAlignment('center');
                  });
                }
              }

            foreach ($data_uts as $dt) {
                $sheet->cells('D' . $i . ':E' . sizeof($data_uts), function ($cells) {
                    $cells->setAlignment('center');
                });
              }
        });

        $excel->sheet('Jadwal UAS', function ($sheet) use ($data_uas,$items) {
            $sheet->fromArray($data_uas, null, 'A1');

            $num_rows = sizeof($data_uas) + 1;

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
            
            $sheet->setBorder('A1:J' . (sizeof($data_uas) + 1), 'thin');

            $sheet->setHorizontalCentered(true);

            $sheet->cells('A1:J1', function ($cells) {
                $cells->setBackground('#97D86E');
                $cells->setFontWeight('bold');
                $cells->setAlignment('center');
            });
            foreach ($data_uas as $dt) {
                  $no = ($dt['NO'] + 1);
                  if ($dt['Tanggal Ujian'] < $dt['Tanggal akhir Pengisian'] && $dt['Sudah Diisi Nilai'] < $dt['Peserta Ujian']) {
                      $sheet->cells('A' . $no . ':J' . $no, function ($cells) {
                          $cells->setBackground('#FFD6D6');
                          $cells->setFontColor('#000');
                          $cells->setAlignment('center');
                      });
                  }else{
                    $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
                          $cells->setAlignment('center');
                  });
                }
              }

            foreach ($data_uas as $dt) {
                $sheet->cells('D' . $i . ':E' . sizeof($data_uas), function ($cells) {
                    $cells->setAlignment('center');
                });
              }
        });
    })->export('xls');
  }

}
