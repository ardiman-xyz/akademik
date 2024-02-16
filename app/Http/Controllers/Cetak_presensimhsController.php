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
use App\Http\Controllers\ApiStrukturalController;

class Cetak_presensimhsController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
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
      $select_department = GetDepartment::getDepartment();

      $select_class_program = DB::table('mstr_department_class_program')
      ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_department_class_program.Department_Id')
      ->where('mstr_department_class_program.Department_Id', $department)
      ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
      ->get();

       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
       ->get();

      if ($search == null) {


      if($term_year== null || $term_year== 0 || $department == null || $department == 0 || $class_program == null || $class_program == 0){
        $data[] = [
            'NO' => '',
            'Kode Matakuliah' => '',
            'Nama Matakuliah' => '',
            'Kelas' =>'',
            'Semester' =>'',
            'Hari' => '',
            'Jam' => '',
            'Ruang' => '',
            'Dosen' => '',
            'Jumlah Peserta' => '',
            'Class_Capacity' => '',
            'Offered_Course_id' => '',
        ];
      }else{
        $aoc = DB::table('acd_offered_course')
        ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
        ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
        ->join('acd_course_curriculum' , function ($join)
        {
          $join->on('acd_course_curriculum.Department_Id', '=', 'acd_offered_course.Department_Id')
          ->on('acd_course_curriculum.Class_Prog_Id', '=', 'acd_offered_course.Class_Prog_Id')
          ->on('acd_course_curriculum.Course_Id', '=', 'acd_offered_course.Course_Id');
        })
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
        // ->join('acd_course_curriculum','acd_course_curriculum.Curriculum_Id','=','acd_offered_course.Curriculum_Id')
        ->where('acd_offered_course.Department_Id', $department)
        ->where('acd_offered_course.Class_Prog_Id', $class_program)
        ->where('acd_offered_course.Term_Year_Id', $term_year)
        ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name',
        // 'acd_course_curriculum.*',
        DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'),
        DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
        DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
        // DB::raw('(SELECT acd_course_curriculum.Study_Level_Id FROM acd_course_curriculum LEFT JOIN acd_offered_course on acd_offered_course.Curriculum_Id = acd_course_curriculum.Curriculum_Id WHERE acd_course_curriculum.Curriculum_Id = acd_offered_course.Curriculum_Id AND acd_course_curriculum.Course_Id = acd_offered_course.Course_Id AND acd_course_curriculum.Class_Prog_Id = acd_offered_course.Class_Prog_Id AND acd_course_curriculum.Department_Id = acd_offered_course.Department_Id GROUP BY(Course_Cur_Id))'))
        // ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
        ->orderBy('acd_course.Course_Name', 'asc')
        ->orderBy('acd_offered_course.Class_Id', 'asc')
        ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
        ->get();
  
        $jadwal =  DB::table('acd_offered_course')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->join('acd_course_curriculum' , function ($join)
              {
                $join->on('acd_course_curriculum.Department_Id', '=', 'acd_offered_course.Department_Id')
                ->on('acd_course_curriculum.Class_Prog_Id', '=', 'acd_offered_course.Class_Prog_Id')
                ->on('acd_course_curriculum.Course_Id', '=', 'acd_offered_course.Course_Id');
              })
            ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
            ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            ->where('acd_offered_course.Department_Id', $department)
            ->where('acd_offered_course.Class_Prog_Id', $class_program)
            ->where('acd_offered_course.Term_Year_Id', $term_year)
            ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
              DB::raw('(SELECT Group_Concat(acd_sched_session.Description SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as jadwal'),
              DB::raw('(SELECT Group_Concat(acd_sched_session.Day_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as day_id'),
              DB::raw('(SELECT Group_Concat(acd_sched_session.Time_Start SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as time_start'),
              DB::raw('(SELECT Group_Concat(acd_sched_session.Time_End SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as time_end'),
              DB::raw('(SELECT Group_Concat(acd_sched_session.Sched_Session_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as ssi'),
              DB::raw('(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as room'),
              DB::raw('(SELECT Group_Concat(mstr_room.Room_Code SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as room_code'))
            // ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
            ->orderBy('acd_course.Course_Name', 'asc')
            ->orderBy('acd_offered_course.Class_Id', 'asc')
            ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
            ->get();
        $dosen = DB::table('acd_offered_course_lecturer as a')
                  ->join('emp_employee as b','a.Employee_Id','b.Employee_Id')
                  ->where('a.Offered_Course_id',$aoc[0]->Offered_Course_id)->get();

        $aocs = DB::table('acd_offered_course_sched')
            ->where('Offered_course_id',$aoc[0]->Offered_Course_id)->get();

        $databaru = [];
          $s = 0;
          $jml_peserta = 0;
          foreach($aoc as $key){
            $jml_peserta = $aoc[$s]->jml_peserta;
            $cur = DB::table('acd_course_curriculum')
                ->join('acd_offered_course','acd_offered_course.Curriculum_Id','=','acd_course_curriculum.Curriculum_Id')
                ->where('acd_course_curriculum.Curriculum_Id',$key->Curriculum_Id)
                ->where('acd_course_curriculum.Course_Id',$key->Course_Id)
                ->where('acd_course_curriculum.Class_Prog_Id',$key->Class_Prog_Id)
                ->where('acd_course_curriculum.Department_Id',$key->Department_Id)
                ->select('Applied_Sks','Study_Level_Id')
                ->groupby('Course_Cur_Id')
                ->first();
            
            if($cur == null){
              $array = [
                'dosen' => $aoc[$s]->id_dosen,
                'jml_peserta' => $jml_peserta,
                'Class_Capacity' => $aoc[$s]->Class_Capacity,
                'jadwal' => $jadwal[$s]->jadwal,
                'ruang' => $jadwal[$s]->room,
                'ruangcd' => $jadwal[$s]->room_code,
                'Course_Code' => $jadwal[$s]->Course_Code,
                'Course_Id' => $jadwal[$s]->Course_Id,
                'Course_Name' => $jadwal[$s]->Course_Name,
                'day_id' => $jadwal[$s]->day_id,
                'time_start' => $jadwal[$s]->time_start,
                'time_end' => $jadwal[$s]->time_end,
                'Class_Id' => $aoc[$s]->Class_Id,
                'jadwal' => $jadwal[$s]->jadwal,
                'ssi' => $jadwal[$s]->ssi,
                'Applied_Sks' => '',
                'Class_Name' => $aoc[$s]->Class_Name,
                'Offered_Course_id' => $aoc[$s]->Offered_Course_id,
                'Study_Level_Id' => '',
              ];
            }else{
              $array = [
                'dosen' => $aoc[$s]->id_dosen,
                'jml_peserta' => $jml_peserta,
                'Class_Capacity' => $aoc[$s]->Class_Capacity,
                'jadwal' => $jadwal[$s]->jadwal,
                'ruang' => $jadwal[$s]->room,
                'ruangcd' => $jadwal[$s]->room_code,
                'Course_Code' => $jadwal[$s]->Course_Code,
                'Course_Id' => $jadwal[$s]->Course_Id,
                'Course_Name' => $jadwal[$s]->Course_Name,
                'day_id' => $jadwal[$s]->day_id,
                'time_start' => $jadwal[$s]->time_start,
                'time_end' => $jadwal[$s]->time_end,
                'Class_Id' => $aoc[$s]->Class_Id,
                'jadwal' => $jadwal[$s]->jadwal,
                'ssi' => $jadwal[$s]->ssi,
                'Applied_Sks' => $cur->Applied_Sks,
                'Class_Name' => $aoc[$s]->Class_Name,
                'Study_Level_Id' => $cur->Study_Level_Id,
                'Offered_Course_id' => $aoc[$s]->Offered_Course_id,
              ];
            }

            $jml_peserta = 0;
            array_push($databaru, $array);
            $s++;
          }
          // dd($databaru);
      
        $i = 1;
          foreach ($databaru as $item) {
            $jadwal = explode('|',$item['jadwal']);
            $day = explode('|',$item['day_id']);  
            $ssi = explode('|',$item['ssi']); 
            $room = explode('|',$item['ruang']);
            $id_dosen = explode('|',$item['dosen'] );
            
            if ($item['jadwal'] == ""){
              $dosen = [];
              $nd = 0;
              foreach ($id_dosen as $key) {
                if ($key != null) {
                  // $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                  //   ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                  //   ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                  //   ->first();
                  //     $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                  //     $firstitle = $dosennya->First_Title;
                  //     $name = $dosennya->Name;
                  //     $lasttitle = $dosennya->Last_Title;
                  //     $dosen[$nd] = $firstitle." ".$name." ".$lasttitle;
                $anu = DB::table('emp_employee')
                  ->join(
                    DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"),
                    'emp_employee.Employee_Id',
                    'max_placement.Employee_Id'
                  )
                  ->join('emp_placement', function ($golru) {
                    $golru->on('emp_placement.Employee_Id', 'emp_employee.Employee_Id')
                    ->on('emp_placement.Tmt_Date', 'max_placement.Tmt_Date');
                  })
                  ->where('emp_placement.Department_Id', $department)
                  ->where('emp_employee.Department_Id', $key)
                  ->first();

                if ($anu) {
                  $dosennya = DB::table('emp_employee')->where('Employee_Id', $anu->Employee_Id)->first();
                  $firstitle = $dosennya->First_Title;
                  $name = $dosennya->Name;
                  $lasttitle = $dosennya->Last_Title;
                  $dosen[$nd] = $firstitle . " " . $name . " " . $lasttitle;
                } else {
                  $dosen[$nd] = '';
                }
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
                  'Hari' => '',
                  'Jam' => '',
                  'Ruang' => '',
                  'Jumlah Peserta' => $item['jml_peserta'],
                  'Class_Capacity' => $item['Class_Capacity'],
                  'Offered_Course_id' => $item['Offered_Course_id'],
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
                  // $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                  //   ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                  //   ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                  //   ->first();

                    $anu = DB::table('emp_employee')
                    ->join(DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"), 'emp_employee.Employee_Id', 'max_placement.Employee_Id'
                    )
                    ->join('emp_placement',function($golru){
                        $golru->on('emp_placement.Employee_Id','emp_employee.Employee_Id')
                        ->on('emp_placement.Tmt_Date','max_placement.Tmt_Date');
                    })
                    ->where('emp_placement.Department_Id', $department)
                    ->where('emp_employee.Department_Id', $key)
                    ->first();

                      if($anu){
                        $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                        $firstitle = $dosennya->First_Title;
                        $name = $dosennya->Name;
                        $lasttitle = $dosennya->Last_Title;
                        $dosen[$nd] = $firstitle." ".$name." ".$lasttitle;

                      }else{
                        $dosen[$nd] = '';
                      }
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
                            'Hari' => $hari,
                            'Jam' => $jam,
                            'Ruang' => $ruangan,
                            'Dosen' => $ndosen,
                            'Jumlah Peserta' => $item['jml_peserta'],
                            'Class_Capacity' => $item['Class_Capacity'],
                            'Offered_Course_id' => $item['Offered_Course_id'],
                        ];
            }
              $i++;
        }

    // $collection = collect($data);
    // $datass = $collection->paginate($rowpage);
    }

    }else {
      $aoc = DB::table('acd_offered_course')
      ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
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
      // ->join('acd_course_curriculum','acd_course_curriculum.Curriculum_Id','=','acd_offered_course.Curriculum_Id')
      ->where('acd_offered_course.Department_Id', $department)
      ->where('acd_offered_course.Class_Prog_Id', $class_program)
      ->where('acd_offered_course.Term_Year_Id', $term_year)
      ->where(function($query){
        $search = Input::get('search');
        $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
        $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
      })
      ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name',
      // 'acd_course_curriculum.*',
      DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'),
      DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
      DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
      // DB::raw('(SELECT acd_course_curriculum.Study_Level_Id FROM acd_course_curriculum LEFT JOIN acd_offered_course on acd_offered_course.Curriculum_Id = acd_course_curriculum.Curriculum_Id WHERE acd_course_curriculum.Curriculum_Id = acd_offered_course.Curriculum_Id AND acd_course_curriculum.Course_Id = acd_offered_course.Course_Id AND acd_course_curriculum.Class_Prog_Id = acd_offered_course.Class_Prog_Id AND acd_course_curriculum.Department_Id = acd_offered_course.Department_Id GROUP BY(Course_Cur_Id))'))
      // ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
      ->orderBy('acd_course.Course_Name', 'asc')
      ->orderBy('acd_offered_course.Class_Id', 'asc')
      ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
      ->get();

      if($aoc->count() > 0){
        $jadwal =  DB::table('acd_offered_course')
              ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
              ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
              ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
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
              ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
                DB::raw('(SELECT Group_Concat(acd_sched_session.Description SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as jadwal'),
                DB::raw('(SELECT Group_Concat(acd_sched_session.Day_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as day_id'),
                DB::raw('(SELECT Group_Concat(acd_sched_session.Time_Start SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as time_start'),
                DB::raw('(SELECT Group_Concat(acd_sched_session.Time_End SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as time_end'),
                DB::raw('(SELECT Group_Concat(acd_sched_session.Sched_Session_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as ssi'),
                DB::raw('(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as room'),
                DB::raw('(SELECT Group_Concat(mstr_room.Room_Code SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id AND dd.Curriculum_Id = acd_course_curriculum.Curriculum_Id) as room_code'))
              ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
              ->orderBy('acd_course.Course_Name', 'asc')
              ->orderBy('acd_offered_course.Class_Id', 'asc')
              ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
              ->get();

    $dosen = DB::table('acd_offered_course_lecturer as a')
              ->join('emp_employee as b','a.Employee_Id','b.Employee_Id')
              ->where('a.Offered_Course_id',$aoc[0]->Offered_Course_id)->get();

    $aocs = DB::table('acd_offered_course_sched')
          ->where('Offered_course_id',$aoc[0]->Offered_Course_id)->get();

    $databaru = [];
        $s = 0;
        foreach($aoc as $key){
          $cur = DB::table('acd_course_curriculum')
              ->join('acd_offered_course','acd_offered_course.Curriculum_Id','=','acd_course_curriculum.Curriculum_Id')
              ->where('acd_course_curriculum.Curriculum_Id',$key->Curriculum_Id)
              ->where('acd_course_curriculum.Course_Id',$key->Course_Id)
              ->where('acd_course_curriculum.Class_Prog_Id',$key->Class_Prog_Id)
              ->where('acd_course_curriculum.Department_Id',$key->Department_Id)
              ->select('Applied_Sks','Study_Level_Id')
              ->groupby('Course_Cur_Id')
              ->first();
          
          if($cur == null){
            $array = [
              'dosen' => $aoc[$s]->id_dosen,
              'jml_peserta' => $aoc[$s]->jml_peserta,
              'Class_Capacity' => $aoc[$s]->Class_Capacity,
              'jadwal' => $jadwal[$s]->jadwal,
              'ruang' => $jadwal[$s]->room,
              'ruangcd' => $jadwal[$s]->room_code,
              'Course_Code' => $jadwal[$s]->Course_Code,
              'Course_Id' => $jadwal[$s]->Course_Id,
              'Course_Name' => $jadwal[$s]->Course_Name,
              'day_id' => $jadwal[$s]->day_id,
              'time_start' => $jadwal[$s]->time_start,
              'time_end' => $jadwal[$s]->time_end,
              'Class_Id' => $aoc[$s]->Class_Id,
              'jadwal' => $jadwal[$s]->jadwal,
              'ssi' => $jadwal[$s]->ssi,
              'Applied_Sks' => '',
              'Class_Name' => $aoc[$s]->Class_Name,
              'Offered_Course_id' => $aoc[$s]->Offered_Course_id,
              'Study_Level_Id' => '',
            ];
          }else{
            $array = [
              'dosen' => $aoc[$s]->id_dosen,
              'jml_peserta' => $aoc[$s]->jml_peserta,
              'Class_Capacity' => $aoc[$s]->Class_Capacity,
              'jadwal' => $jadwal[$s]->jadwal,
              'ruang' => $jadwal[$s]->room,
              'ruangcd' => $jadwal[$s]->room_code,
              'Course_Code' => $jadwal[$s]->Course_Code,
              'Course_Id' => $jadwal[$s]->Course_Id,
              'Course_Name' => $jadwal[$s]->Course_Name,
              'day_id' => $jadwal[$s]->day_id,
              'time_start' => $jadwal[$s]->time_start,
              'time_end' => $jadwal[$s]->time_end,
              'Class_Id' => $aoc[$s]->Class_Id,
              'jadwal' => $jadwal[$s]->jadwal,
              'ssi' => $jadwal[$s]->ssi,
              'Applied_Sks' => $cur->Applied_Sks,
              'Class_Name' => $aoc[$s]->Class_Name,
              'Study_Level_Id' => $cur->Study_Level_Id,
              'Offered_Course_id' => $aoc[$s]->Offered_Course_id,
            ];
          }

          array_push($databaru, $array);
          $s++;
        }
    
    $i = 1;
        foreach ($databaru as $item) {
          $jadwal = explode('|',$item['jadwal']);
          $day = explode('|',$item['day_id']);  
          $ssi = explode('|',$item['ssi']); 
          $room = explode('|',$item['ruang']);
          $id_dosen = explode('|',$item['dosen'] );
          
          if ($item['jadwal'] == ""){
            $dosen = [];
            $nd = 0;
            foreach ($id_dosen as $key) {
              if ($key != null) {
                // $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                //   ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                //   ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                //   ->first();
                //     $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                //     $firstitle = $dosennya->First_Title;
                //     $name = $dosennya->Name;
                //     $lasttitle = $dosennya->Last_Title;
                //     $dosen[$nd] = $firstitle." ".$name." ".$lasttitle;
                $anu = DB::table('emp_employee')
                  ->join(
                    DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"),
                    'emp_employee.Employee_Id',
                    'max_placement.Employee_Id'
                  )
                  ->join('emp_placement', function ($golru) {
                    $golru->on('emp_placement.Employee_Id', 'emp_employee.Employee_Id')
                    ->on('emp_placement.Tmt_Date', 'max_placement.Tmt_Date');
                  })
                  ->where('emp_placement.Department_Id', $department)
                  ->where('emp_employee.Department_Id', $key)
                  ->first();

                if ($anu) {
                  $dosennya = DB::table('emp_employee')->where('Employee_Id', $anu->Employee_Id)->first();
                  $firstitle = $dosennya->First_Title;
                  $name = $dosennya->Name;
                  $lasttitle = $dosennya->Last_Title;
                  $dosen[$nd] = $firstitle . " " . $name . " " . $lasttitle;
                } else {
                  $dosen[$nd] = '';
                }
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
                'Hari' => '',
                'Jam' => '',
                'Ruang' => '',
                'Jumlah Peserta' => $item['jml_peserta'],
                'Class_Capacity' => $item['Class_Capacity'],
                'Offered_Course_id' => $item['Offered_Course_id'],
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
                // $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                //   ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                //   ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                //   ->first();
                //     $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                //     $firstitle = $dosennya->First_Title;
                //     $name = $dosennya->Name;
                //     $lasttitle = $dosennya->Last_Title;
                //     $dosen[$nd] = $firstitle." ".$name." ".$lasttitle;
                $anu = DB::table('emp_employee')
                  ->join(
                    DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"),
                    'emp_employee.Employee_Id',
                    'max_placement.Employee_Id'
                  )
                  ->join('emp_placement', function ($golru) {
                    $golru->on('emp_placement.Employee_Id', 'emp_employee.Employee_Id')
                    ->on('emp_placement.Tmt_Date', 'max_placement.Tmt_Date');
                  })
                  ->where('emp_placement.Department_Id', $department)
                  ->where('emp_employee.Department_Id', $key)
                  ->first();

                if ($anu) {
                  $dosennya = DB::table('emp_employee')->where('Employee_Id', $anu->Employee_Id)->first();
                  $firstitle = $dosennya->First_Title;
                  $name = $dosennya->Name;
                  $lasttitle = $dosennya->Last_Title;
                  $dosen[$nd] = $firstitle . " " . $name . " " . $lasttitle;
                } else {
                  $dosen[$nd] = '';
                }
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
                          'Hari' => $hari,
                          'Jam' => $jam,
                          'Ruang' => $ruangan,
                          'Dosen' => $ndosen,
                          'Jumlah Peserta' => $item['jml_peserta'],
                          'Class_Capacity' => $item['Class_Capacity'],
                          'Offered_Course_id' => $item['Offered_Course_id'],
                      ];
          }
            $i++;
        }           
      }else{
        $data[] = [
                    'NO' => '',
                    'Kode Matakuliah' => '',
                    'Nama Matakuliah' => '',
                    'Kelas' => '',
                    'Semester' => '',
                    'Hari' =>'',
                    'Jam' => '',
                    'Ruang' => '',
                    'Dosen' => '',
                    'Jumlah Peserta' => '',
                    'Class_Capacity' => '',
                    'Offered_Course_id' => '',
                ];
        Alert::error('Maaf yang anda cari tidak ada', 'Tidak Ada Data')->persistent('Close')->autoclose(50000);
      }

    }

    //  $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
     return view('cetak/index_presensimhs')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year);
  }


  public function export(Request $request,$id)
  {
    // dd($request->all());
     $type = Input::get('type');
     $kolom = Input::get('kolom');
     $kolom2 = Input::get('kolom2');

    $acd_offered_course = DB::table('acd_offered_course')->where('Offered_Course_id', $id)->first();
    $data_krs =  DB::table('acd_student_krs')
    ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
    // ->join('mstr_gender','acd_student.Gender_Id','=','mstr_gender.Gender_Id')
    ->where([
      ['acd_student_krs.Term_Year_Id',$acd_offered_course->Term_Year_Id],
      ['acd_student_krs.Class_Prog_Id',$acd_offered_course->Class_Prog_Id],
      ['acd_student_krs.Class_Id',$acd_offered_course->Class_Id],
      ['acd_student_krs.Course_Id',$acd_offered_course->Course_Id]
    ])
    ->get();
    $sched_real = DB::table('acd_sched_real')
    ->where([
      ['Term_Year_Id',$acd_offered_course->Term_Year_Id],
      ['Class_Prog_Id',$acd_offered_course->Class_Prog_Id],
      ['Class_Id',$acd_offered_course->Class_Id],
      ['Course_Id',$acd_offered_course->Course_Id]
    ])
    ->orderBy('Meeting_Order','asc')
    ->get();

    $new_data = [];
    $p = 0;
    foreach ($data_krs as $data_siswa) {
      $new_data[$p]['Nim'] = $data_siswa->Nim;
      $new_data[$p]['Full_Name'] = $data_siswa->Full_Name;
      $new_data[$p]['Gender_Id'] = ($data_siswa->Gender_Id == 1 ? 'L':'P');
      $new_data[$p]['Presence'] = [];
      $q = 0;
      foreach ($sched_real as $presence) {
        $check_presence = DB::table('acd_sched_real_detail')
        ->where([
          ['Sched_Real_Id',$presence->Sched_Real_Id],
          ['Student_Id',$data_siswa->Student_Id]
        ])
        ->first();
        $new_data[$p]['Presence'][$q] = ($check_presence ? 'v':''); 
        $q++;
      }
      $p++;
    }

    // dd($sched_real,$data_krs,$new_data);

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
     ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name', 'mstr_faculty.Faculty_Id','mstr_faculty.Faculty_Name' , 'mstr_term_year.Term_Year_Name','mstr_term_year.Year_Id','mstr_term.Term_Name','mstr_entry_year.Entry_Year_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
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
     ->get();
     if ($jadwal_q) {
       $jadwal = $jadwal_q;
     }

      $n = 0;
      $start = "";
      $end = "";
      $days="";
      $ruangan = "";
      $Day_Name = "";
      $Room_Name = "";
      $jdwl = [];

      if($jadwal->count() == 0){
        $jdwl[] = [
            'Day_Name' => '',
            'Jam' => '',
            'Room_Name' =>'',
        ];
      }else{
        foreach ($jadwal as $key) {
           $name_day = DB::table('mstr_day')->where('Day_Id',$key->Day_Id)->first();
           $sesi = DB::table('acd_sched_session')->where('Sched_Session_Id',$key->Sched_Session_Id)->first();
           if($days ==  $sesi->Day_Id){
             $end = $sesi->Time_End;
           }else{
             $days = $sesi->Day_Id;
             $start = $sesi->Time_Start;
             $end = $sesi->Time_End;
           }
 
           if($ruangan == $key->Room_Id){
           }else{
             $ruangan = $key->Room_Id;
           }
 
           $hari = $name_day->Day_Name;
           $jam = $start."-".$end;
           $Day_Name = $key->Day_Name;
           $Room_Name = $key->Room_Name;
           $n++;
         }
         $jdwl[] = [
            'Day_Name' => $Day_Name,
            'Jam' => $jam,
            'Room_Name' =>$Room_Name,
        ];
      }

     $dosen = DB::table('emp_employee')
     ->join('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Employee_Id' , '=', 'emp_employee.Employee_Id')
     ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_lecturer.Offered_Course_id')
     ->where('acd_offered_course.Offered_Course_id', $id)
     ->orderBy('acd_offered_course_lecturer.Order_Id' , 'asc')
     ->get();
    //  dd($dosen);

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

     $faculty=DB::table('acd_student')
     ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
     ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
     ->select('mstr_faculty.Faculty_Name','mstr_faculty.Faculty_Id','acd_student.Department_Id')->where('Student_Id',$id)->first();

     // dd($data);
     $functional_names = ApiStrukturalController::new_struktural('Kaprodi',$data->Faculty_Id,$data->Department_Id);
     // dd($functional_names);

     View()->share(['jdwl'=>$jdwl,'kolom2'=>$kolom2,'kolom'=>$kolom,'faculty'=>$faculty,'data'=> $data,'query' => $query, 'jadwal' => $jadwal , 'dosen' => $dosen, 'grade' => $grade, 'ttd' => $ttd, 'pejabat' => $pejabat,'new_data'=>$new_data,'functional_names'=>$functional_names]);
     
       $pdf = PDF::loadView('cetak/export_presensimhs');
       return $pdf->stream('Presensi.pdf');
     // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);

   }

  public function exportttd($id)
   {
     $type = Input::get('type');
     $term_year = Input::get('term_year');
     $department = Input::get('department');
     $class_program = Input::get('class_program');
     
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
     ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name', 'mstr_faculty.Faculty_Id','mstr_faculty.Faculty_Name' , 'mstr_term_year.Term_Year_Name','mstr_term.Term_Name','mstr_entry_year.Entry_Year_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
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

     $dosen = DB::table('emp_employee')
     ->join('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Employee_Id' , '=', 'emp_employee.Employee_Id')
     ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_lecturer.Offered_Course_id')
     ->where('acd_offered_course.Offered_Course_id', $id)
     ->orderBy('acd_offered_course_lecturer.Order_Id' , 'asc')
     ->get();

     $grade = DB::table('acd_grade_department')->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_grade_department.Grade_Letter_Id')->where('Department_Id', $data->Department_Id)->get();
     

     $acd_student_krs2 = DB::table('acd_student_krs')
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
              ->where('acd_student_krs.Term_Year_Id',$term_year)
            ->where('acd_student.Department_Id',$department)
            ->where('acd_student.Class_Prog_Id',$class_program)
            ->orderby('acd_student.Nim');
     $query2 = $acd_student_krs2
       ->select('acd_student_krs.Krs_Id','acd_student.*')
       ->get();

       $datamhs = [];
       $i=0;
       foreach ($query2 as $dat) {
         $studentbill = DB::select('CALL usp_GetStudentBill(?,?,?)',array($dat->Register_Number,'',''));
          $ii = 0;
          $ListTagihan = [];
            $total=0;
          if($studentbill!=null){
            foreach ($studentbill as $key) {
              $ListTagihan[$ii]['Amount'] = $key->Amount;
              $ListTagihan[$ii]['Cost_Item_Name'] = $key->Cost_Item_Name;
              $ii++;
            }

            $sumAmount =0;
                  foreach ($ListTagihan as $tagihan) {
                    $sumAmount += $tagihan['Amount'];
                  }
            $total = number_format($sumAmount,'0',',','.');
          }
          // $datamhs[$i]['total'] = $total;
          // $datamhs[$i]['nim'] = $dat->Nim;
          if($total > 0){
          }else{
            $datamhs[$i] = $dat->Student_Id;
          }
          $i++;
       }
      //  dd($datamhs);

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
            //   ->where('acd_student_krs.Term_Year_Id',$term_year)
            // ->where('acd_student.Department_Id',$department)
            // ->where('acd_student.Class_Prog_Id',$class_program)
            // ->wherein('acd_student.Student_Id',$datamhs)
            ->orderby('acd_student.Nim');
     $query = $acd_student_krs
       ->select('acd_student_krs.Krs_Id','acd_student.*')
       ->get();
      //  dd($query);

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

     $faculty=DB::table('acd_student')
     ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
     ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
     ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();


     View()->share(['faculty'=>$faculty,
                      'data'=> $data,
                      'query' => $query, 
                      'datamhs' => $datamhs, 
                      'jadwal' => $jadwal , 
                      'dosen' => $dosen, 
                      'grade' => $grade, 
                      'ttd' => $ttd, 
                      'pejabat' => $pejabat]);
       $pdf = PDF::loadView('cetak/export_presensimhsttd');
       return $pdf->stream('TTDPresensi.pdf');
     // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);

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
