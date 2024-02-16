<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use App\Http\Controllers\KrsMatakuliahDibukaController;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;
use DateTime;
use App\Http\Controllers\ApiStrukturalController;

class Client_CetakController extends Controller
{
  public function check_user(Request $request, $nim)
  {
    // dd($request->ch);
    $check['success'] = false;
    $check['download'] = false;
    $check['from'] = 'admin';
    if (Auth::check()) {
      $check['success'] = true;
    }else{
      if(isset($request->ch)){
        if($request->ch == 'true'){
          $mhs = DB::table('acd_student')->where('Nim',$nim)->first();
          // SEND UL TO = public/client/krs_mahasiswa/3112200001/20201?ch=true&id=d4d887f225ed0089afe0684ea6743542
          //Nim, Student_Id ,Register_Number, 
          // d4d887f225ed0089afe0684ea6743542 //md5 true
          // nim = 3112200001
          $md5 = md5($mhs->Nim.$mhs->Student_Id.$mhs->Register_Number);
          if(isset($request->id)){
            if($request->id == $md5){
              $check['success'] = true;
              $check['download'] = false;
              $check['from'] = 'mhs';
            }
          }
        }
      }
    }
    return $check;
  }
  public function export_krs(Request $request, $id,$term_year){
    $check = $this->check_user($request,$id);
    if($check['success'] == false){
      return view(403);
    }

    $term_year_session =  $request->session()->get('term_year');
    $nim = $id;
    $student_data = DB::table('acd_student')
    ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
    ->join('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
    ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
    ->where('Nim',$nim)
    ->first();

    $term_years = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->first();
    
    $curriculum = DB::table('acd_curriculum_entry_year')
    ->where('Term_Year_Id',$term_year)
    ->where('Department_Id',$student_data->Department_Id)
    ->where('Class_Prog_Id',$student_data->Class_Prog_Id)
    ->where('Entry_Year_Id',$student_data->Entry_Year_Id)
    ->first();  
    
    $data = DB::table('acd_offered_course')
    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
    ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
    // ->where('acd_course_curriculum.Study_Level_Id',$semester)
    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
    ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
    ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
    ->where('acd_offered_course.Department_Id', $student_data->Department_Id)
    ->where('acd_offered_course.Class_Prog_Id', $student_data->Class_Prog_Id)
    ->where('acd_offered_course.Term_Year_Id', $term_year)
    // ->where('acd_offered_course.Curriculum_Id',$curriculum->Curriculum_Id)
    ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.*',
    DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
    DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
    ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
    ->orderBy('acd_course.Course_Name', 'asc')
    ->orderBy('acd_offered_course.Class_Id', 'asc')
    ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
    ->get();

    $krs = DB::table('acd_student_krs as ask')
    ->join('acd_student as as','ask.Student_Id','=','as.Student_Id')
    ->join('acd_course as ac','ask.Course_Id','=','ac.Course_Id')
    ->join('mstr_class as mc','ask.Class_Id','=','mc.Class_Id')
    ->where('ask.Student_Id',$student_data->Student_Id)
    ->where('ask.Term_Year_Id',$term_year)
    ->where('ask.Is_Approved',1)
    ->select('ask.*','as.Department_Id','ac.Course_Code','ac.Course_Name','mc.Class_Name')
    ->get();
    // dd($krs);

    $databaru = [];
    $x = 1;
    foreach ($krs as $key ) {
      $aoc = DB::table('acd_offered_course')
          ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
          // ->where('acd_course_curriculum.Study_Level_Id',$semester)
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->where('acd_offered_course.Department_Id', $key->Department_Id)
          ->where('acd_offered_course.Class_Prog_Id', $key->Class_Prog_Id)
          ->where('acd_offered_course.Course_Id', $key->Course_Id)
          ->where('acd_offered_course.Class_Id', $key->Class_Id)
          ->where('acd_offered_course.Term_Year_Id', $term_year)
          // ->where('acd_offered_course.Curriculum_Id',$curriculum->Curriculum_Id)
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
          ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->where('acd_offered_course.Department_Id', $key->Department_Id)
          ->where('acd_offered_course.Class_Prog_Id', $key->Class_Prog_Id)
          ->where('acd_offered_course.Course_Id', $key->Course_Id)
          ->where('acd_offered_course.Class_Id', $key->Class_Id)
          ->where('acd_offered_course.Term_Year_Id', $term_year)
          // ->where('acd_offered_course.Curriculum_Id',$curriculum->Curriculum_Id)
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

      if(isset($aoc[0]->Offered_Course_id)){
        $dosen = DB::table('acd_offered_course_lecturer as a')
              ->join('emp_employee as b','a.Employee_Id','b.Employee_Id')
              ->where('a.Offered_Course_id',$aoc[0]->Offered_Course_id)->get();

        $aocs = DB::table('acd_offered_course_sched')
              ->where('Offered_course_id',$aoc[0]->Offered_Course_id)->get();
      }else{
        $dosen = '';
        $aocs = '';
      }
          // dd($key);

      $array = [
        'Nim' => $student_data->Nim,
        'dosen' => (isset($aoc[0]->id_dosen) ? $aoc[0]->id_dosen: ''),
        'jadwal' => (isset($jadwal[0]->jadwal)? $jadwal[0]->jadwal:''),
        'ruang' => (isset($jadwal[0]->room)? $jadwal[0]->room:''), 
        'ruangcd' => (isset($jadwal[0]->room_code)? $jadwal[0]->room_code:''),
        'Course_Code' => $key->Course_Code,
        'Course_Name' => $key->Course_Name,
        'day_id' => (isset($jadwal[0]->day_id)? $jadwal[0]->day_id:''),
        'time_start' => (isset($jadwal[0]->time_start)? $jadwal[0]->time_start:''),
        'time_end' => (isset($jadwal[0]->time_end)? $jadwal[0]->time_end:''),
        'Class_Id' => (isset($aoc[0]->Class_Id) ? $aoc[0]->Class_Id: ''),
        'jadwal' => (isset($jadwal[0]->jadwal)? $jadwal[0]->jadwal:''),
        'ssi' => (isset($jadwal[0]->ssi)? $jadwal[0]->ssi:''),
        'Applied_Sks' => (isset($aoc[0]->Applied_Sks) ? $aoc[0]->Applied_Sks: $key->Sks),
        'smt' => (isset($aoc[0]->Study_Level_Id) ? $aoc[0]->Study_Level_Id: ''),
        'Class_Name' => (isset($aoc[0]->Class_Name) ? $aoc[0]->Class_Name: $key->Class_Name),
      ];

      array_push($databaru, $array);
      $x++;
    }
    // dd($databaru);

    $semester = DB::table('acd_student_krs as ask')
      ->join('mstr_term_year as mty','mty.Term_Year_Id','=','ask.Term_Year_Id')
      ->where([['ask.Student_Id',$student_data->Student_Id],['ask.Term_Year_Id','<=',$term_year]])
      // ->whereBetween('mty.Term_Year_Id', [$student_data->Entry_Year_Id.'1', $term_year])
      ->groupby('ask.Term_Year_Id')
      ->get();
    // $semester = $student_data->Entry_Year_Id.'1';
    // if($semester == $term_year){
    //   $smt = 1;
    // }else{
    //   $smt = ($term_year-$semester)+1;
    // }
    $smt = count($semester);

    $sksmax = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($term_year,$student_data->Student_Id));
    // dd($sksmax);

    $dosenpa = DB::table('acd_student_supervision as a')
    ->join('emp_employee as b','a.Employee_Id','=','b.Employee_Id')
    ->where('Student_Id',$student_data->Student_Id)->first();

    $functional_name = '';
    $functional_nidn = '';
    $functional = DB::table('acd_functional_position_term_year as func')
      ->join('emp_functional_position as efp','func.Functional_Position_Id','=','efp.Functional_Position_Id')
      ->join('emp_employee as ee','func.Employee_Id','=','ee.Employee_Id')
      // ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',20201]])
      ->where([['efp.Functional_Position_Code','KP'],['func.Department_Id',$student_data->Department_Id],['func.Term_Year_Id',$term_year]])
      ->first();
    if($functional){
      $functional_name = ($functional->First_Title == null ? '':$functional->First_Title.'. ').$functional->Name.($functional->Last_Title == null ? '':', '.$functional->Last_Title);
    }

    $functional_names = ApiStrukturalController::new_struktural('Kaprodi',$student_data->Faculty_Id,$student_data->Department_Id);
    // dd($functional_names);
    $functional_name = ($functional_names ? $functional_names[0]->Full_Name:'');
    $functional_nidn = ($functional_names ? $functional_names[0]->Nidn:'');
    // dd($functional_names);

    $Bnk_Value = DB::table('acd_transcript')->where('Student_Id',$student_data->Student_Id)->sum('Bnk_Value');
    $Sks_Value = DB::table('acd_transcript')->where('Student_Id',$student_data->Student_Id)->sum('Sks');
    $ipk = 0;
    if($Bnk_Value > 0 && $Sks_Value > 0){
      $ipk = $Bnk_Value / $Sks_Value;
    }

    // $tester = ApiStrukturalController::dosen_prodi($student_data->Faculty_Id,$student_data->Department_Id);
    // dd($tester,$student_data->Faculty_Id,$student_data->Department_Id);
  
    View()->share([
      'data'=>$databaru,
      'smt'=>$smt,
      'ipk'=>$ipk,
      'sksmax'=>$sksmax,
      'functional_name'=>$functional_name,
      'functional_nidn'=>$functional_nidn,
      'dosenpa'=>$dosenpa,
      'term_years'=>$term_years,
      'student_data'=>$student_data]);
      $pdf = PDF::loadView('client_cetak/export_krsmahasiswa');
      if($check['from'] == 'admin'){
        return $pdf->stream('KRS.pdf');
      }else{
        return $pdf->download('KRS.pdf');
      }
  }

  public function export_khs(Request $request, $nim,$term_year)
  {    
    $check = $this->check_user($request,$nim);
    if($check['success'] == false){
      return view(403);
    }
    $term = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->first();

    $data_std = DB::table('acd_student as a')
    ->join('mstr_department as b','a.Department_Id','=','b.Department_Id')
    ->join('mstr_faculty as mf','b.Faculty_Id','=','mf.Faculty_Id')
    ->join('mstr_education_program_type as c','b.Education_Prog_Type_Id','=','c.Education_Prog_Type_Id')
    ->join('mstr_class_program as d','a.Class_Prog_Id','=','d.Class_Prog_Id')
    ->where('a.Nim',$nim)
    ->select('a.Student_Id','a.Nim','a.Full_Name','a.Department_Id','a.Class_Prog_Id','a.Photo','b.Department_Name','mf.Faculty_Id','c.Acronym','c.Study_Period_Semester','a.Entry_Year_Id','d.Class_Program_Name')
    ->first();

    $sks_kumulatif = DB::table('acd_transcript')->where('Student_Id', $data_std->Student_Id)->where('Grade_Letter_Id', '!=', null)->where('Term_Year_Id','<=',$term_year)->sum('Sks');

    $modelitem = DB::table('acd_transcript')->where('Student_Id', $data_std->Student_Id)->where('Grade_Letter_Id', '!=', null)->where('Term_Year_Id','<=',$term_year)->get();
    $total_sksXbobot = 0;
    foreach ($modelitem as $key) {
      $total_sksXbobot = $total_sksXbobot +  $key->Sks * $key->Weight_Value;
    }
    $ipk = 0;
    if ($total_sksXbobot <= 0 AND $sks_kumulatif <= 0) {

    }else {
      $ipk = $total_sksXbobot / $sks_kumulatif;
    }

    // dd($term);
    if($term->Term_Id == 1){
      $year_id = $term->Year_Id - 1;
      $term_allow = $year_id.'2';
    }
    if($term->Term_Id == 2){
      $term_allow = $term->Term_Year_Id - 1;
    }
    // dd($term_allow);

    $get_khs_sebelum = DB::table('acd_student_khs as a')
    ->join('acd_student_krs as b','a.Krs_Id','=','b.Krs_Id')
    ->where('b.Term_Year_Id',$term_allow)
    ->where('b.Student_Id',$data_std->Student_Id)
    ->get();
    $bnk_smt_sebelum = 0;
    $sks_smt_sebelum = 0;
    $l = 0;
    foreach ($get_khs_sebelum as $key) {
      $bnk_smt_sebelum = $bnk_smt_sebelum + $key->Bnk_Value;
      $sks_smt_sebelum = $sks_smt_sebelum + $key->Sks;
      $l++;
    }
    // $ip_sebelum = $bnk_smt_sebelum / $sks_smt_sebelum;

    // $allowedsksforkhs = DB::select('CALL usp_GetAllowedSKSForKS(?,?)',array($data_std->Student_Id,$ip_sebelum));
    $allowedsksforkhs = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($term_allow,$data_std->Student_Id));
    $sksmax = 0;
    foreach ($allowedsksforkhs as $k) {
      $sksmax = $k->AllowedSKS;
      // $sksmax = $k->AllowedSKS;
    }

    $supervision = DB::table('acd_student_supervision as a')
    ->join('emp_employee as b','a.Employee_Id','b.Employee_Id')
    ->where('a.Student_Id',$data_std->Student_Id)
    ->select('b.First_Title','b.Name','b.Last_Title')
    ->first();

    $khs_std_total = DB::table('acd_student_krs')
      ->where('Student_Id', $data_std->Student_Id)
      ->where('Term_Year_Id','<=',$term_year)
      ->sum('Sks');

    $check_curriculum = DB::table('acd_curriculum_entry_year')->where([['Department_Id',$data_std->Department_Id],['Class_Prog_Id',$data_std->Class_Prog_Id],['Entry_Year_Id',$data_std->Entry_Year_Id],['Term_Year_Id',$term_year]])->first();

    $khs_std = DB::table('acd_student_krs')
      ->leftjoin('acd_course' ,'acd_course.Course_Id','=','acd_student_krs.Course_Id')
      ->leftjoin('acd_course_curriculum', 'acd_course_curriculum.Course_Id', '=', 'acd_course.Course_Id')
      ->leftjoin('mstr_study_level', 'mstr_study_level.Study_Level_Id', '=', 'acd_course_curriculum.Study_Level_Id')
      ->leftjoin('acd_student', 'acd_student.Student_Id','=','acd_student_krs.Student_Id')
      ->leftjoin('mstr_class', 'mstr_class.Class_Id', '=' ,'acd_student_krs.Class_Id')
      ->leftjoin('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student_krs.Class_Prog_Id')
      ->leftjoin('mstr_term_year','mstr_term_year.Term_Year_Id', '=', 'acd_student_krs.Term_Year_Id')
      ->leftjoin('acd_student_khs', 'acd_student_khs.Krs_Id' , '=' , 'acd_student_krs.Krs_Id')
      ->leftjoin('acd_grade_letter' ,'acd_grade_letter.Grade_Letter_Id', '=', 'acd_student_khs.Grade_Letter_Id')
      ->where('acd_student_krs.Student_Id', $data_std->Student_Id)->where('acd_student_krs.Term_Year_Id', $term_year)
      ->select(
        'acd_student_krs.Krs_Id as Krs',
        'acd_student.*',
        'acd_student_khs.*',
        'acd_grade_letter.Grade_Letter',
        'acd_course.*',
        'mstr_study_level.Level_Name',
        'acd_student_khs.Weight_Value as weightvalue',
      //   DB::raw('(SELECT Is_For_Transcript FROM acd_course_curriculum WHERE Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
      // AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_student_krs.Term_Year_Id AND Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Is_For_Transcript' ),
      // DB::raw('(SELECT Transcript_Sks FROM acd_course_curriculum WHERE Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
      // AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_student_krs.Term_Year_Id AND Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Transcript_Sks' )
      )
      ->groupBy('acd_student_krs.Krs_Id')
      ->orderBy('acd_course.Course_Code','asc')
      ->get();

    // dd($khs_std);

    $x = 0;
    $total_sks = 0;
    $total_sksxnilai = 0;
    foreach ($khs_std as $key) {
      $total_sks = $total_sks + $key->Sks;
      $total_sksxnilai = $total_sksxnilai + ($key->Sks * $key->Weight_Value);
      $x++;
    }

    $ips = ($total_sksxnilai == 0?0:($total_sksxnilai / $total_sks));

    $Datetimenow = Date('Y-m-d');
    $active = DB::Table('mstr_term_year')->where('Start_Date','<=',$Datetimenow)->where('End_Date','>=',$Datetimenow)->select('Term_Year_Id')->first();
    if($active != null){
      $atv = $active->Term_Year_Id;
    }else{
      $atv = 20191;
    }

    $term_year_use = $request->term_year;
    if($term_year_use == null){
      $term_year_use =  $request->session()->get('term_year');
      }else{
      $term_year_use = $term_year;
      }

    $dosens = DB::table('acd_functional_position_term_year')
        ->join('emp_functional_position', 'emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
        ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
        ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','acd_functional_position_term_year.Faculty_Id')
        ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_functional_position_term_year.Department_Id')
        //  ->where('acd_functional_position_term_year.Term_Year_Id', 20191)
        ->where('acd_functional_position_term_year.Term_Year_Id', $atv)
        ->orderBy('mstr_faculty.Faculty_Name', 'asc')
        ->orderBy('mstr_department.Department_Name', 'asc')
        ->orderBy('acd_functional_position_term_year.Functional_Position_Id', 'asc')
        ->get();

    $functional_name_dekan = '';
    $functional_jenis_dekan = '';
    $functional_dekan = DB::table('acd_functional_position_term_year as func')
      ->join('emp_functional_position as efp','func.Functional_Position_Id','=','efp.Functional_Position_Id')
      ->join('emp_employee as ee','func.Employee_Id','=','ee.Employee_Id')
      ->join('mstr_faculty as mf','func.Faculty_Id','=','mf.Faculty_Id')
      // ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',20201]])
      ->where([['efp.Functional_Position_Code','D'],['func.Faculty_Id',$data_std->Faculty_Id],['func.Term_Year_Id',$term_year_use]])
      ->select('func.*','efp.*','ee.*')
      ->first();
    if($functional_dekan){
      $functional_name_dekan = ($functional_dekan->First_Title == null ? '':$functional_dekan->First_Title.'. ').$functional_dekan->Name.($functional_dekan->Last_Title == null ? '':', '.$functional_dekan->Last_Title);
      $functional_jenis_dekan = $functional_dekan->Functional_Position_Name;
    }
    $functional_name_kp = '';
    $functional_jenis_kp = '';
    $functional_kp = DB::table('acd_functional_position_term_year as func')
      ->join('emp_functional_position as efp','func.Functional_Position_Id','=','efp.Functional_Position_Id')
      ->join('emp_employee as ee','func.Employee_Id','=','ee.Employee_Id')
      ->join('mstr_department as md','func.Department_Id','=','md.Department_Id')
      // ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',20201]])
      ->where([['efp.Functional_Position_Code','KP'],['func.Department_Id',$data_std->Department_Id],['func.Term_Year_Id',$term_year_use]])
      ->select('func.*','efp.*','ee.*')
      ->first();
    if($functional_kp){
      $functional_name_kp = ($functional_kp->First_Title == null ? '':$functional_kp->First_Title.'. ').$functional_kp->Name.($functional_kp->Last_Title == null ? '':', '.$functional_kp->Last_Title);
      $functional_jenis_kp = $functional_kp->Functional_Position_Name;
    }

// dd($data_std->Faculty_Id,$data_std->Department_Id);
    $struktural_kaprodi = ApiStrukturalController::new_struktural('Kaprodi',$data_std->Faculty_Id,$data_std->Department_Id);
    $functional_name_kp = ($struktural_kaprodi ? $struktural_kaprodi[0]->Full_Name:'');
    $functional_jenis_kp = ($struktural_kaprodi ? $struktural_kaprodi[0]->Structural_Name:'');
    $functional_nidn_kp = ($struktural_kaprodi ? $struktural_kaprodi[0]->Nidn:'');
    
    $struktural_dekan = ApiStrukturalController::new_struktural('04.01',$data_std->Faculty_Id,'');
    // dd($struktural_dekan,$struktural_kaprodi);
    $functional_name_dekan = ($struktural_dekan ? $struktural_dekan[0]->Full_Name:'');
    $functional_jenis_dekan = ($struktural_dekan ? $struktural_dekan[0]->Structural_Name:'');
    $functional_nidn_dekan = ($struktural_dekan ? $struktural_dekan[0]->Nidn:'');
    
    $kba_name = '';
    $kba_nik = '';
    foreach ($dosens as $dosen) if($dosen->Functional_Position_Code == 'KBA') {
      $kba_name = $dosen->First_Title.' '.$dosen->Name.($dosen->Last_Title == null? '':', ').$dosen->Last_Title;
      $kba_nik = $dosen->Nik;
    }

    $smt_std =  DB::table('acd_student_krs')
    ->where('acd_student_krs.Student_Id', $data_std->Student_Id)
    ->where('acd_student_krs.Term_Year_Id', '<=' ,$term->Term_Year_Id)
    ->select('Term_Year_Id')->groupby('Term_Year_Id')->get();

    $c_smt = 0;
    foreach ($smt_std as $key) if($key->Term_Year_Id != 0){
      $c_smt++;
    }

    $acd_grade_departments = DB::table('acd_grade_department as agd')
    ->join('acd_grade_letter as agl','agd.Grade_Letter_Id','=','agl.Grade_Letter_Id')
    ->where([
      // ['agd.Department_Id',$data_std->Department_Id],
      // ['agd.Term_Year_Id',$term_year_use]
      ['agd.Department_Id',$data_std->Department_Id],
      ['agd.Entry_Year_Id',$data_std->Entry_Year_Id]
    ])
    ->groupby('agd.Department_Id','agd.Entry_Year_Id','agd.Grade_Letter_Id','Term_Year_Id')
    ->orderby('agd.Weight_Value','desc')
    ->get();
    
    $bagian = ceil(count($acd_grade_departments)/2);
    $acd_grade_department = [];
    $q = 0;
    foreach (array_chunk($acd_grade_departments->toArray(), $bagian) as $x => $val) {
      $acd_grade_department_in = [];
      $z = 0;
      foreach ($val as $i => $key) {
        $acd_grade_department_in[$z]['Grade_Letter'] = $key->Grade_Letter;
        $acd_grade_department_in[$z]['Weight_Value'] = $key->Weight_Value;
        $acd_grade_department_in[$z]['Predicate'] = $key->Predicate;
        $z++;
      }
      $acd_grade_department[$q] = $acd_grade_department_in;
      $q++;
    }

    $supervision = '';
    if($supervision){
      $supervision->First_Title.' '.$supervision->Name.($supervision->Last_Title == null? '':', ').$supervision->Last_Title;
    }

    $print['Year'] = $term->Year_Id .' / '. (($term->Year_Id)+1);
    $print['Smt'] = ($term->Term_Id == 1 ? 'Ganjil':'Genap');
    $print['Full_Name'] = ucwords(strtolower($data_std->Full_Name));
    $print['Entry_Year_Id'] = $data_std->Entry_Year_Id;
    $print['Nim'] = $data_std->Nim;
    $print['class_prog'] = $data_std->Class_Program_Name;
    $print['Photo'] = env('APP_URL').$data_std->Photo;
    $print['Prodi'] = $data_std->Acronym.' - '.$data_std->Department_Name;
    $print['Dpa'] = $supervision;
    $print['Data_krs'] = $khs_std;
    $print['Ipk'] = number_format($ipk,2);
    $print['Ips'] = ($ips == 0 ? '':(number_format($ips,2)));
    $print['Sks'] = number_format($total_sks,1);
    $print['SksTotal'] = number_format($khs_std_total,1);
    $print['Sksmax'] = $sksmax;
    $print['Total_sksxnilai'] = number_format($total_sksxnilai,2);
    $print['Dosen_name'] = $kba_name;
    $print['Dosen_nik'] = $kba_nik;
    $print['Semester'] = $c_smt;
    $print['functional_name_dekan'] = $functional_name_dekan;
    $print['functional_jenis_dekan'] = $functional_jenis_dekan;
    $print['functional_name_kp'] = $functional_name_kp;
    $print['functional_jenis_kp'] = $functional_jenis_kp;
    $print['functional_nidn_kp'] = 'NIDN. '.$functional_nidn_kp;
    $print['functional_nidn_dekan'] = 'NIDN. '.$functional_nidn_dekan;
    $print['Grade_Department'] = $acd_grade_department;
    $print['Study_Period_Semester'] = $data_std->Study_Period_Semester;
    // dd($print);

    View()->share(['print'=>$print,'sks_kumulatif' => $sks_kumulatif , 'ipk' => $ipk , 'sksmax' => $sksmax]);
    $pdf = PDF::loadView('client_cetak/export_khsmahasiswa');
    if($check['from'] == 'admin'){
      return $pdf->stream('KHS.pdf');
    }else{
      return $pdf->download('KHS.pdf');
    }
  }

  public function export_trnascript_akhir(Request $request, $nim)
  {
    $check = $this->check_user($request,$nim);
    if($check['success'] == false){
      return view(403);
    }

    $nim = $request->nim;
    $student=DB::table('acd_student')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
    ->join('mstr_education_program_type','mstr_education_program_type.Education_Prog_Type_Id','=','mstr_department.Education_Prog_Type_Id')
    ->join('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
    ->leftjoin('acd_yudisium','acd_yudisium.Student_Id','=','acd_student.Student_Id')
    ->leftjoin('acd_thesis','acd_thesis.Student_Id','=','acd_student.Student_Id')
    ->leftjoin('mstr_graduate_predicate','acd_yudisium.Graduate_Predicate_Id','=','mstr_graduate_predicate.Graduate_Predicate_Id')
    ->where('Nim',$nim)
    ->select('mstr_department.*','mstr_department.First_Title as Department_First_Title','mstr_department.Last_Title as Department_Last_Title','acd_student.*','acd_yudisium.*','mstr_education_program_type.*','mstr_faculty.*','mstr_graduate_predicate.Predicate_Name','mstr_graduate_predicate.Predicate_Name_Eng','acd_thesis.*',
      DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
    ->first();

    $transcript_nilai=DB::table('acd_transcript')
    ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
    DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
    DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Nim',$nim)
    ->first();

    $data_transcripts = DB::table('acd_transcript')
    ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
      DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
    ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
    ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->where('acd_student.Nim',$nim)
    ->get();

    $date = date('Y-m-d H:i:s');
    $term_yearcount=DB::table('mstr_term_year')
    ->where('Start_Date','<=',$date)
    ->where('End_Date','>=',$date)
    ->select('Start_Date','End_Date')
    ->count();

    $term_year1 ="";
    if($term_yearcount > 0){
      $term_year1=DB::table('mstr_term_year')
      ->where('Start_Date','<=',$date)
      ->where('End_Date','>=',$date)
      ->select('Term_Year_Id')
      ->first();
      $term_year1=$term_year1->Term_Year_Id;
    }

    $namadekan = '';
    $nidn = '';
    $functional_dekan = DB::table('acd_functional_position_term_year as func')
      ->join('emp_functional_position as efp','func.Functional_Position_Id','=','efp.Functional_Position_Id')
      ->join('emp_employee as ee','func.Employee_Id','=','ee.Employee_Id')
      // ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',20201]])
      ->where([['efp.Functional_Position_Code','D'],['func.Term_Year_Id',$term_year1]])
      ->first();
    if($functional_dekan){
      $namadekan = ($functional_dekan->First_Title == null ? '':$functional_dekan->First_Title.', ').$functional_dekan->Name.($functional_dekan->Last_Title == null ? '':', '.$functional_dekan->Last_Title);
      $nidn = $functional_dekan->Nip;
    }

    $struktural_dekan = ApiStrukturalController::new_struktural('04.01',$student->Faculty_Id,'');
    // dd($struktural_dekan);
    $functional_name_dekan = ($struktural_dekan ? $struktural_dekan[0]->Full_Name:'');
    $functional_jenis_dekan = ($struktural_dekan ? $struktural_dekan[0]->Structural_Name:'');
    $functional_nidn_dekan = ($struktural_dekan ? $struktural_dekan[0]->Nidn:'');

    $bagian = ceil(count($data_transcripts)/2);
    $sum_sks = 0;
    $sum_bnk = 0;
    $data = [];
    $q = 0;
    foreach (array_chunk($data_transcripts->toArray(), $bagian) as $x => $val) {
      $data_in = [];
      $z = 0;
      foreach ($val as $i => $key) {
        $data_in[$z]['Course_Code'] = $key->Course_Code;
        $data_in[$z]['Course_Name'] = $key->Course_Name;
        $data_in[$z]['Sks'] = $key->Sks;
        $data_in[$z]['Grade_Letter'] = $key->Grade_Letter;
        $data_in[$z]['Weight_Value'] = $key->Weight_Value;
        $data_in[$z]['Bnk_Value'] = ($key->Sks * $key->Weight_Value);
        $sum_sks = $sum_sks + $key->Sks;
        $sum_bnk = $sum_bnk + ($key->Sks * $key->Weight_Value);
        $z++;
      }
      $data[$q] = $data_in;
      $q++;
    }

    $date_cetak = strtotime($date);
    $date_cetak = Date('d-m-Y',$date_cetak);
    $date_cetak = $this->tgl_indo($date_cetak);

    $Graduate_Date = $date_cetak;
    if(isset($student->Graduate_Date)){
      $Graduate_Date = strtotime($student->Graduate_Date);
      $Graduate_Date = Date('d-m-Y',$Graduate_Date);
      $Graduate_Date = $this->tgl_indo($Graduate_Date);
    }

    $print['Transcript'] = $data;
    $print['Transcript_Number'] = $student->Transcript_Num;
    $print['Full_Name'] = ucwords(strtolower($student->Full_Name));
    $print['National_Certificate_Number'] = $student->National_Certificate_Number;
    $print['Nim'] = $student->Nim;
    $print['Register_Number'] = $student->Register_Number;
    if($student->Birth_Date != null){
      $print['TTL'] = $student->Birth_Place.', '. ($student->Birth_Date == "00-00-0000" ? $date_cetak:$this->tgl_indo($student->Birth_Date));
    }else{
      $print['TTL'] = $student->Birth_Place.', '. $date_cetak;
    }
    $print['Faculty_Name'] = $student->Faculty_Name;
    $print['Department_Name'] = $student->Department_Name;
    $print['Title'] = ($student->Department_First_Title ? $student->Department_First_Title.', ':'').$student->Department_Last_Title;
    $print['Graduate_Date'] = $Graduate_Date;
    $print['sum_sks'] = $sum_sks;
    $print['sum_bnk'] = $sum_bnk;
    $print['Thesis_Title'] = $student->Thesis_Title;
    $print['Thesis_Title_Eng'] = $student->Thesis_Title_Eng;
    $print['ipk'] =  (number_format($sum_bnk / $sum_sks,2));
    // $print['ipk_terbilang'] = ucwords($this->terbilang('2.01'));
    $print['ipk_terbilang'] = ucwords($this->terbilang((number_format($sum_bnk / $sum_sks,2))));
    $print['Thesis_Title_Eng'] = $student->Thesis_Title_Eng;
    $print['Date_Cetak'] = $date_cetak;
    $print['namadekan'] = $functional_name_dekan;
    $print['nidn'] = $functional_nidn_dekan;
    $print['predikat_lulus'] = $student->Predicate_Name;
    // Predicate_Name
    // Predicate_Name_Eng

    View()->share([
      'print'=>$print
      ]);
    if ($request->to == "download") {
      $pdf = PDF::loadView('client_cetak/export_transcriptakhir');
      return $pdf->download('Transkrip_akhir.pdf');
    }else{
      $pdf = PDF::loadView('client_cetak/export_transcriptakhir');
      if($check['from'] == 'admin'){
        return $pdf->stream('Transkrip_akhir.pdf');
      }else{
        return $pdf->download('Transkrip_akhir.pdf');
      }
    }
  }

  function tgl_indo($tanggal){
    $bulan = array (
      1 =>   'Januari',
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
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[0] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[2];
  }

  function konversi($x){
    $x = abs($x);
    $angka = array ("nol","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    
    if($x < 12){
      $temp = " ".$angka[$x];
    }else if($x<20){
      $temp = $this->konversi($x - 10)." ";
    }else if ($x<100){
      $temp = $this->konversi($x/10)." ". $this->konversi($x%10);
    }else if($x<200){
      $temp = " ".$this->konversi($x-100);
    }else if($x<1000){
      $temp = $this->konversi($x/100)." ".$this->konversi($x%100);   
    }else if($x<2000){
      $temp = " ".$this->konversi($x-1000);
    }else if($x<1000000){
      $temp = $this->konversi($x/1000)." ".$this->konversi($x%1000);   
    }else if($x<1000000000){
      $temp = $this->konversi($x/1000000)." ".$this->konversi($x%1000000);
    }else if($x<1000000000000){
      $temp = $this->konversi($x/1000000000)." ".$this->konversi($x%1000000000);
    }
    
    return $temp;
  }
    
  function tkoma($x){
    $str = stristr($x,".");
    $ex = explode('.',$x);
    
    if(($ex[1]/10) >= 0.1){
      $a = abs($ex[1]);
    }
    $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
    $temp = "";
  
    $a2 = $ex[1]/10;
    $pjg = strlen($str);
    $i =1;
      
    if($ex[1] == 00){ 
      $temp .= "Nol";
    }else if($a>=1 && $a< 12){  
      $temp .= "Nol ".$string[$a];
    }else if($a>=12 && $a < 20){   
      $temp .= $this->konversi($a - 10)." ";
    }else if ($a>20 && $a<100){   
      $temp .= $this->konversi($a / 10)." ". $this->konversi($a % 10);
    }else{
      if($a2<1){
        while ($i<$pjg){     
          $char = substr($str,$i,1);     
          $i++;
          $temp .= " ".$string[$char];
        }
      }
    }
    return $temp;
  }
  
  function terbilang($x){
    if($x<0){
      $hasil = "minus ".trim($this->konversi(x));
    }else{
      $poin = trim($this->tkoma($x));
      $hasil = trim($this->konversi($x));
    }
    
    if($poin){
      $hasil = $hasil." koma ".$poin;
    }else{
      $hasil = $hasil;
    }
    return $hasil;  
  }
}
