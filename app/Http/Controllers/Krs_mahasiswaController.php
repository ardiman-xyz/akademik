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

class Krs_mahasiswaController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','export']]);
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
       $nim = $request->nim;
       $term_year1 = $request->term_year;
       $Faculty_Id=Auth::user()->Faculty_Id;
       $Department_Id = Auth::user()->Department_Id;

       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }

      $cetak = false;
      $select_term_year = DB::table('mstr_term_year')->orderBy('Term_Year_Id', 'desc')->get();
      $student = DB::table('acd_student')
            ->join('mstr_department' , 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
            ->join('mstr_class_program' , 'mstr_class_program.Class_Prog_Id' , '=', 'acd_student.Class_Prog_Id')
            // ->where('Nim','like' ,'%'.$nim.'%')
            ->where('Nim', $nim)
            ->where('acd_student.Department_Id','like' ,'%'.$Department_Id.'%')
            ->first();
      if(!$student){
      // return Redirect::back()->withErrors('Tidak ada data dengan NIM tersebut')->with('success', false);
        $countsks = 0;
        $acd_student_krs = [];
        $saldo = 0;
        $sks = 0;
        $regnum = '';
      }else{
        $acd_student_krs = DB::table('acd_student_krs')
          ->join('acd_course' ,'acd_course.Course_Id','=','acd_student_krs.Course_Id')
          ->leftjoin('acd_course_curriculum', 'acd_course_curriculum.Course_Id', '=', 'acd_course.Course_Id')
          ->leftjoin('mstr_study_level', 'mstr_study_level.Study_Level_Id', '=', 'acd_course_curriculum.Study_Level_Id')
          ->join('acd_student', 'acd_student.Student_Id','=','acd_student_krs.Student_Id')
          ->join('mstr_department', 'mstr_department.Department_Id','=','acd_student.Department_Id')
          ->join('mstr_class', 'mstr_class.Class_Id', '=' ,'acd_student_krs.Class_Id')
          ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student_krs.Class_Prog_Id')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id', '=', 'acd_student_krs.Term_Year_Id')
          ->where('acd_student_krs.Student_Id','like' ,'%'.$student->Student_Id.'%')
          // ->where('acd_student_krs.Term_Year_Id','like', '%'.$term_year.'%')
        ->where('acd_student_krs.Term_Year_Id', $term_year)
          // ->where('acd_student_krs.Class_Prog_Id', $student->Class_Prog_Id)
          //  ->where('acd_student_krs.Is_Approved', 1)
          ->orderBy('Course_Code')
          ->groupBy('acd_course.Course_Id');

        $can_add = true;
        if($Faculty_Id == null){
          $acd_student_krs = $acd_student_krs->get();
        }else{
            if($Department_Id == null){
              if($student->Faculty_Id != $Faculty_Id){
                return Redirect::back()->withErrors('Anda tidak Memiliki Akses Fakultas tersebut')->with('success', false);
              }
              $acd_student_krs = $acd_student_krs->where([['mstr_department.Faculty_Id','like','%'.$Faculty_Id.'%'],['mstr_department.Faculty_Id','!=',null]])->get();
            }else{
              if($student->Department_Id != $Department_Id && $student->Faculty_Id != $Faculty_Id){
                return Redirect::back()->withErrors('Anda tidak Memiliki Akses Prodi tersebut')->with('success', false);
              }
              $acd_student_krs = $acd_student_krs->where([['mstr_department.Faculty_Id','like','%'.$Faculty_Id.'%'],['mstr_department.Department_Id','like','%'.$Department_Id.'%'],['mstr_department.Faculty_Id','!=',null]])->get();
            }
        }

        if($student){
          $sks = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($term_year,$student->Student_Id)); 
          // dd($term_year,$student->Student_Id,$sks);
        }else{
          $sks = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($term_year,$nim));
        }
        $countsks = DB::table('acd_student_krs')->where('Student_Id', $student->Student_Id)->where('Term_Year_Id', $term_year)->where('acd_student_krs.Is_Approved', 1)->sum('Sks');

        $saldo =  DB::select('CALL usp_saldo(?,?)',array($student->Student_Id,$term_year));

        if(count($acd_student_krs) > 0){
          $cetak = true;
        }
        $regnum = $student->Register_Number;
      }
    
      return view('krs_mahasiswa/index')
      ->with('countsks', $countsks)
      ->with('query',$acd_student_krs)
      ->with('saldo', $saldo)
      ->with('sks', $sks)
      ->with('regnum', $regnum)
      ->with('student', $student)
      ->with('dat', $student)
      ->with('select_term_year', $select_term_year)
      ->with('term_year', $term_year)
      ->with('cetak', $cetak)
      ->with('nim', $nim);
     }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $nim = Input::get('nim');
      $term_year = Input::get('term_year');
      $course = Input::get('course');
      $class = Input::get('class');
      $FacultyId=Auth::user()->Faculty_Id;
      // dd($nim);

      $student = DB::table('acd_student')->where('Nim', $nim)->first();

      $notif = null;

      $student_id = "";
      $dat = "";

      $SKS = 0;
      $amount = 0;
      $kapasitas = "";
      $terdaftar = "";
      $sisakuota = "";

      if($FacultyId==""){
        if ($student != null) {
          $student_id = $student->Student_Id;
            $dat = DB::table('acd_student')
            ->join('mstr_department' , 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
            ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
            ->join('mstr_class_program' , 'mstr_class_program.Class_Prog_Id' , '=', 'acd_student.Class_Prog_Id')
            ->where('acd_student.Student_Id', $student->Student_Id)->first();

            //Ini dimatikan dulu
          $saldo = DB::select('call usp_saldo(?, ?)',[$student->Student_Id , $term_year]);
          // $saldo = 0;
        }else {
          $student_id = "";
        }
      }else {
        if ($student != null) {
          $student_id = $student->Student_Id;
            $dat = DB::table('acd_student')
            ->join('mstr_department' , 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
            ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
            ->where('mstr_faculty.Faculty_Id', $FacultyId)
            ->join('mstr_class_program' , 'mstr_class_program.Class_Prog_Id' , '=', 'acd_student.Class_Prog_Id')
            ->where('acd_student.Student_Id', $student->Student_Id)->first();
          $saldo = DB::select('call usp_saldo(?, ?)',[$student->Student_Id , $term_year]);
        }else {
          $student_id = "";
        }
      }


    $datamhs=DB::table('acd_student')->select('Class_Prog_Id','Full_Name','Entry_Year_Id','Department_Id')
    ->where('acd_student.Student_Id', $student->Student_Id)->first();
  if($datamhs->Class_Prog_Id==null){
     Alert::error('Program kelas mahasiswa atas nama '.$datamhs->Full_Name.' belum di isi', 'Data Mahasiswa Belum Lengkap')->persistent('Close')->autoclose(50000);
     $previousUrl = app('url')->previous();
    return Redirect::back();
    // return redirect()->to('setting/student/'.$student->Student_Id.'/edit?entry_year_id='.$datamhs->Entry_Year_Id.'&department='.$datamhs->Department_Id);
    // $notifmessage = "Program kelas mahasiswa $datamhs->Full_Name belum di isi";
    // return Redirect::back()->withErrors($notifmessage);
  }
      $select_course = DB::select('CALL usp_GetOfferredCourseForKRSByStudent(?,?,?,?,?)',array($term_year,$dat->Department_Id,$dat->Class_Prog_Id,$dat->Entry_Year_Id,$student_id));
      $select_course1 = DB::table('acd_course')->get();
      $select_class = "";
      $mstr_term_year = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->first();

      $course_curriculum = DB::table('acd_course_curriculum')->where('Course_Id', $course)->first();
      if ($course_curriculum != null) {
        $department_id = $course_curriculum->Department_Id;
        $curriculum_id = $course_curriculum->Curriculum_Id;
      }else {
        $department_id = "";
        $curriculum_id = "";
      }

      $short_term = db::table('acd_short_term_krs')->where('Department_Id', $dat->Department_Id)->count();

      // dd($short_term);

$KrsMatakuliahDibukaController = new KrsMatakuliahDibukaController();
      // jika semester pendek
      if($mstr_term_year->Term_Id == 3){
        if ($short_term == 0)
        {
          $notif = "Opsi Untuk Semester Pendek Belum diatur ";
          $select_course = DB::select('CALL usp_GetOfferredCourseForKRSByStudent(?,?,?,?,?)',array($term_year,$dat->Department_Id,$dat->Class_Prog_Id,$dat->Entry_Year_Id,$student_id));
        } else{
            $acd_short_term_krs = db::table('acd_short_term_krs')->where('Department_Id', $dat->Department_Id)->first();
            $datamkdibuka = $KrsMatakuliahDibukaController->getOpenedCourse($dat->Nim,$term_year);
            // dd($datamkdibuka);
            $select_course = DB::select('CALL usp_GetOfferredCourseForKRSByStudent(?,?,?,?,?)',array($term_year,$dat->Department_Id,$dat->Class_Prog_Id,$dat->Entry_Year_Id,$student_id));
            if($course != null){
              $cost_sks = DB::table('fnc_course_cost_sks')->where('Department_Id', $dat->Department_Id)->where('Term_Year_Id', $term_year)->where('Class_Prog_Id', $dat->Class_Prog_Id)->where('Entry_Year_Id', $dat->Entry_Year_Id)->count();
              // dd($cost_sks);
              if($cost_sks == 0){
                $notif = "Biaya Per SKS di Keuangan untuk Angkatan ".$dat->Entry_Year_Id." Belum di Set";
              }else{
                if($acd_short_term_krs->Taking_Rule_Id == 2){
                  if($acd_short_term_krs->Is_All_Year == 0){
                    $datamkdibukaulangalltahun = $KrsMatakuliahDibukaController->getOpenedCourseAllYearYbs($dat->Nim,$term_year);
                    // dd($datamkdibukaulangalltahun);
                    $id_mk = [];
                    $i = 0;
                    foreach ($datamkdibukaulangalltahun as $item) {
                      $id_mk[$i] = $item->ID_MK;
                      $i++;
                    }
                    // dd($datamkdibukaulangalltahun);
                    if(!in_array($course,$id_mk)){
                      $notif = "Matakuliah Belum Pernah diambil ditahun ini ";
                    }else{

                    }
                  }else{
                    $datamkdibukaulangalltahun = $KrsMatakuliahDibukaController->getOpenedCourseAllYear($dat->Nim,$term_year);
                    $id_mk = [];
                    $i = 0;
                    foreach ($datamkdibukaulangalltahun as $item) {
                      $id_mk[$i] = $item->ID_MK;
                      $i++;
                    }
                    // dd($datamkdibukaulangalltahun);
                    if(!in_array($course,$id_mk)){
                      $notif = "Matakuliah Belum Pernah diambil sebelumnya";
                    }else{

                    }
                  }
                }
                else if($acd_short_term_krs->Taking_Rule_Id == 3){
                 if($acd_short_term_krs->Is_All_Year == 0){
                   $datamkdibukaulangalltahun = $KrsMatakuliahDibukaController->getOpenedCourseNilaiAllYearYbs($dat->Nim,$term_year,$acd_short_term_krs->Grade_Letter_Minimum_Id);
                   $id_mk = [];
                   $i = 0;
                   foreach ($datamkdibukaulangalltahun as $item) {
                     $id_mk[$i] = $item->ID_MK;
                     $i++;
                   }
                   // dd($datamkdibukaulangalltahun);
                   if(!in_array($course,$id_mk)){
                     $notif = "Matakuliah Belum Pernah diambil tahun ini atau nilai kurang dari nilai minimum";
                   }else{

                   }
                 }else{
                   $datamkdibukaulangalltahun = $KrsMatakuliahDibukaController->getOpenedCourseNilaiAllYear($dat->Nim,$term_year,$acd_short_term_krs->Grade_Letter_Minimum_Id);
                   $id_mk = [];
                   $i = 0;
                   foreach ($datamkdibukaulangalltahun as $item) {
                     $id_mk[$i] = $item->ID_MK;
                     $i++;
                   }
                   // dd($acd_short_term_krs->Grade_Letter_Minimum_Id);
                   // dd($datamkdibukaulangalltahun);
                   if(!in_array($course,$id_mk)){
                     $notif = "Matakuliah Belum Pernah diambil sebelumnya atau nilai kurang dari nilai minimum";
                   }else{

                   }
                  }
                }
              }
            }
        }

  if ($course != null) {
        if ($notif == null) {
          $courecostforkrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($student->Department_Id,$term_year,$dat->Class_Prog_Id,$student->Entry_Year_Id,$course));
          foreach ($courecostforkrs as $cforkrs) {
            $SKS = $cforkrs->applied_sks;
            $amount = $cforkrs->amount;
          }
          $select_class = DB::table('acd_offered_course')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->where('acd_offered_course.Term_Year_Id', $term_year)->where('acd_offered_course.Course_Id', $course)->where('acd_offered_course.Class_Prog_Id', $dat->Class_Prog_Id)->get();
        }else {

        }
      }
          if ($class != null) {
            $classinfo = DB::select('CALL usp_GetClassInfoForKRS(?,?,?,?,?)',array($term_year,$student->Department_Id,$dat->Class_Prog_Id,$course,$class));
            foreach ($classinfo as $clsinfo) {
              $dosen = "";
              $kapasitas = $clsinfo->Capacity;
              $terdaftar = $clsinfo->Used;
              $sisakuota = $clsinfo->Free;
            }
            // dd($classinfo);
      }

      // dd($datamkdibukaulangalltahun);
    }
      //bukan semester pendek
      else{
      if ($course != null) {

        $prerequisite = DB::table('acd_prerequisite')->where('Department_Id', $department_id)->where('Curriculum_Id', $curriculum_id)->where('Course_Id', $course)->first();
        $prerequisite_id = "";
        if ($prerequisite != null)  {
          $prerequisite_id = $prerequisite->Prerequisite_Id;
        }
        $count_prasyarat = DB::table('acd_prerequisite_detail')->where('Prerequisite_Id', $prerequisite_id)->get();

        //Jika terdapat prasyarat
        if ($count_prasyarat->count() > 0) {

          //JIKA PERNAH DIAMBIL
          foreach ($count_prasyarat as $prereq) {
            if ($prereq->Prerequisite_Type_Id == 1) {
              $cekdata = DB::table('acd_transcript')->where('Course_Id', $prereq->Course_Id)->where('Student_Id', $student_id)->count();
              $grade = DB::table('acd_transcript')
              ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
              ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
              ->where('acd_transcript.Course_Id', $prereq->Course_Id)->where('acd_transcript.Student_Id', $student_id)
              ->where('acd_grade_department.Department_Id', $student->Department_Id)
              ->select('acd_grade_department.Weight_Value')->first();

              $weight_val = DB::table('acd_prerequisite_detail')
              ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_prerequisite_detail.Grade_Letter_Id')
              ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
              ->where('Prerequisite_Id', $prereq->Prerequisite_Id)
              ->where('acd_grade_department.Department_Id', $student->Department_Id)
              ->select('acd_grade_department.Weight_Value')->first();



              $cour = DB::table('acd_course')->where('Course_Id', $prereq->Course_Id)->first();
              $gra = DB::table('acd_grade_letter')->where('Grade_Letter_Id', $prereq->Grade_Letter_Id)->first();

              // dd($cekdata);

              if ($cekdata <= 0) {
                $notif = "Anda belum mengambil matakuliah ".$cour->Course_Name." atau Nilai masih kosong.";
              }

              // if ($cekdata > 0) {
              //   if($weight_val->Weight_Value != null){
              //     if ($grade->Weight_Value < $weight_val->Weight_Value) {
              //       $notif = "Nilai Matakuliah ".$cour->Course_Name." kurang dari ".$gra->Grade_Letter.".";
              //     }
              //   }
              // }else {
                // $notif = "Anda belum mengambil matakuliah ".$cour->Course_Name." atau Nilai masih kosong.";
                //OR
                //ViewBag.Notif = ViewBag.Notif + " Matakuliah " + item.Acd_Course.Course_Name + " belum pernah diambil " + "<br/>";
                //JIKA PERLU DICEK KE KRS, SUDAH AMBIL KS ATAU BELUM ?
              // }
              // dd($grade);

            }

            //JIKA DIAMBIL BERSAMAAN
            elseif($prereq->Prerequisite_Type_Id == 2){
              $cour = DB::table('acd_course')->where('Course_Id', $prereq->Course_Id)->first();
              $count_course = DB::table('acd_student_krs')->where('Student_Id', $student_id)->where('Course_Id', $cour->Course_Id)->where('Class_Prog_Id', $student->Class_Prog_Id)->where('Term_Year_Id', $term_year)->count();
              if ($count_course > 0) {

              }else {
                $notif = "Matakuliah ".$cour->Course_Name." harus diambil terlebih dahulu.";
              }
            }

            //JIKA PERNAH ATAU SEDANG DIAMBIL
            elseif ($prereq->Prerequisite_Type_Id == 3) {
              $cekdata = DB::table('acd_transcript')->where('Course_Id', $prereq->Course_Id)->where('Student_Id', $student_id)->count();
              $grade = DB::table('acd_transcript')
              ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
              ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
              ->where('acd_transcript.Course_Id', $prereq->Course_Id)->where('acd_transcript.Student_Id', $student_id)
              ->where('acd_grade_department.Department_Id', $student->Department_Id)
              ->select('acd_grade_department.Weight_Value')->first();
              $weight_val = DB::table('acd_prerequisite_detail')
              ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_prerequisite_detail.Grade_Letter_Id')
              ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
              ->where('Prerequisite_Id', $prereq->Prerequisite_Id)
              ->where('acd_grade_department.Department_Id', $student->Department_Id)
              ->select('acd_grade_department.Weight_Value')->first();

              $cour = DB::table('acd_course')->where('Course_Id', $prereq->Course_Id)->first();
              $gra = DB::table('acd_grade_letter')->where('Grade_Letter_Id', $prereq->Grade_Letter_Id)->first();

              if ($cekdata > 0) {
                if ($grade->Weight_Value < $weight_val->Weight_Value) {
                  $notif = "Nilai Matakuliah ".$cour->Course_Name." kurang dari ".$gra->Grade_Letter.".";
                }
              }else {

                //ViewBag.Notif = ViewBag.Notif + " Nilai Matakuliah " + item.Acd_Course.Course_Name + " kosong. " + "<br/>";
                //OR
                //ViewBag.Notif = ViewBag.Notif + " Matakuliah " + item.Acd_Course.Course_Name + " belum pernah diambil " + "<br/>";
                $count_course = DB::table('acd_student_krs')->where('Student_Id', $student_id)->where('Course_Id', $cour->Course_Name)->where('Class_Prog_Id', $student->Class_Prog_Id)->where('Term_Year_Id', $term_year)->count();
                if ($count_course > 0) {

                }else {
                  $notif = "Nilai Matakuliah ".$cour->Course_Name." kurang dari ".$gra->Grade_Letter.".";
                }
              }
            }

            //JIKA SEMESTER (MINIMAL)
            elseif ($prereq->Prerequisite_Type_Id == 4) {
              $entry_year = $student->Entry_Year_Id."".$student->Entry_Term_Id;
              $term_years = DB::table('acd_student_krs')->where('Student_Id', $student_id)->select('Term_Year_Id')->orderby('Term_Year_Id', 'DESC')->first();

              // result = hasil semester
              $result = 0;
              $result = $term_years->Term_Year_Id - $entry_year;
              if ($result % 2 == 1) {
                $result = $result - 1;
                $result = $result / 5;
                $result = $result + 2;

              }elseif ($result % 2 == 0) {
                $result = $result / 5;
                $result = $result + 1;
              }
              $value = $prereq->Value;
              if ($result < $value) { // CEK apakah sudah masuk pada semester "sesuai prasyarat"
                $notif = $notif." Anda Belum masuk Semester ".$value.".";
              }else {

              }
              // dd($notif);
            }

            //JIKA TOTAL SKS (MINIMAL)
            elseif ($prereq->Prerequisite_Type_Id == 5) {
              $total_sks = DB::table('acd_transcript')->where('Student_Id', $student_id)->where('Grade_Letter_Id','!=', null)->sum('Sks');
              $value = $prereq->Value;
              if ($total_sks < $value) {
                $notif = $notif." Total SKS yang ditempuh belum mencukupi ".$value." SKS.";
              }else {

              }
            }

            //JIKA TOTAL SKS Nilai D (MAKSIMAL)
            elseif ($prereq->Prerequisite_Type_Id == 6) {
              $total_sks_nilai_d = DB::table('acd_transcript')->where('Student_Id', $student_id)->where('Grade_Letter_Id', "D")->sum('Sks');
              $value = $prereq->Value;
              if ($total_sks_nilai_d > $value) {
                $notif = $notif." Total SKS nilai D yang telah ditempuh melebihi ".$value." SKS.";
              }else {

              }
            }

            //JIKA IPK (MINIMAL)
            elseif ($prereq->Prerequisite_Type_Id == 7) {
              $total_sks_xbbt = 0;
              $xbbt = DB::table('acd_transcript')->where('Student_Id', $student_id)->where('Grade_Letter_Id','!=', null)->get();
              // dd($xbbt);
              foreach ($xbbt as $val) {
                $total_sks_xbbt = $total_sks_xbbt + ($val->Sks * $val->Weight_Value);
              }

              $total_sks = DB::table('acd_transcript')->where('Student_Id', $student_id)->where('Grade_Letter_Id','!=', null)->sum('Sks');
              // dd($total_sks);
              $ipk_transkript = $total_sks_xbbt / $total_sks;
              $ipk_prasyarat = $prereq->Value;
              if ($ipk_transkript < $ipk_prasyarat) {
                $notif = $notif." IPK anda kurang dari ".$ipk_prasyarat.".";
              }else {

              }
            }else { // Jika item.Prerequisite_Type_Id bernilai lain

            }
          }

          if ($notif == null) {
            $courecostforkrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($student->Department_Id,$term_year,$dat->Class_Prog_Id,$student->Entry_Year_Id,$course));
            foreach ($courecostforkrs as $cforkrs) {
              $SKS = $cforkrs->applied_sks;
              $amount = $cforkrs->amount;
            }
            $select_class = DB::table('acd_offered_course')
            ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            ->where('acd_offered_course.Term_Year_Id', $term_year)->where('acd_offered_course.Course_Id', $course)->where('acd_offered_course.Class_Prog_Id', $dat->Class_Prog_Id)->get();
          }



        }

        //jika tidak ada prasyarat
        else {

          if ($notif == null) {
            $courecostforkrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($student->Department_Id,$term_year,$dat->Class_Prog_Id,$student->Entry_Year_Id,$course));
            foreach ($courecostforkrs as $cforkrs) {
              $SKS = $cforkrs->applied_sks;
              $amount = $cforkrs->amount;
            }
            $select_class = DB::table('acd_offered_course')
            ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            ->where('acd_offered_course.Term_Year_Id', $term_year)->where('acd_offered_course.Course_Id', $course)->where('acd_offered_course.Class_Prog_Id', $dat->Class_Prog_Id)->get();
          }else {

          }

        }
      }
      if ($class != null) {
        $classinfo = DB::select('CALL usp_GetClassInfoForKRS(?,?,?,?,?)',array($term_year,$student->Department_Id,$dat->Class_Prog_Id,$course,$class));
        foreach ($classinfo as $clsinfo) {
          $dosen = "";
          $kapasitas = $clsinfo->Capacity;
          $terdaftar = $clsinfo->Used;
          $sisakuota = $clsinfo->Free;
        }
      }
    }
    // dd($dat->Class_Prog_Id);
      return view('krs_mahasiswa/create')->with('select_course',$select_course)->with('select_class', $select_class)->with('notif', $notif)->with('course', $course)->with('class', $class)->with('SKS', $SKS)->with('amount', $amount)->with('kapasitas', $kapasitas)->with('terdaftar', $terdaftar)->with('sisakuota', $sisakuota)->with('dat', $dat)->with('nim', $nim)->with('student_id', $student_id)->with('term_year', $term_year)->with('course', $course)->with('class',$class)->with('notif', $notif);
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
        'course_id'=>'required',
        'class_id'=>'required',
      ]);

      $student_id = Input::get('Student_Id');
      $nim = Input::get('Nim');
      $term_year = Input::get('term_year');
      $department_id = Input::get('department_id');
      $class_prog_id = Input::get('class_prog_id');
      $course_id = Input::get('course_id');
      $class_id = Input::get('class_id');
      $sks = Input::get('Sks');
      $amount = Input::get('Amount');
      $capacity = Input::get('Capacity');
      $terdaftar = Input::get('Terdaftar');
      $sisa_kuota = Input::get('Sisa_Kuota');

      $notif = null;

      $student = DB::table('acd_student')->where('Student_Id', $student_id)->first();
      if ($student) {
        $entry_year = $student->Entry_Year_Id;
      }else {
        $entry_year = "";
      }

      $classinfo = DB::select('CALL usp_GetClassInfoForKRS(?,?,?,?,?)',array($term_year,$department_id,$class_prog_id,$course_id,$class_id));
      $acd_curriculum = DB::table('acd_course_curriculum')->where('Course_Id', $course_id)->first();
      if ($acd_curriculum) {
        $curriculum_id = $acd_curriculum->Curriculum_Id;
      }else {
        $curriculum_id = "";
      }

      $prerequisite = DB::table('acd_prerequisite')->where('Department_Id', $department_id)->where('Curriculum_Id', $curriculum_id)->where('Course_Id', $course_id)->first();
      if ($prerequisite) {
        $prerequisite_id = $prerequisite->Prerequisite_Id;
      }else {
        $prerequisite_id = "";
      }


      $acd_prerequisite_detail = DB::table('acd_prerequisite_detail')->where('Prerequisite_Id', $prerequisite_id)->get();
      $count_prasyarat = $acd_prerequisite_detail->count();
      if ($count_prasyarat > 0) {
        foreach ($acd_prerequisite_detail as $prereq) {
          if ($prereq->Prerequisite_Type_Id == 1) {
            $cekdata = DB::table('acd_transcript')->where('Course_Id', $prereq->Course_Id)->where('Student_Id', $student_id)->count();
            $grade = DB::table('acd_transcript')
            ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
            ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
            ->where('acd_transcript.Course_Id', $prereq->Course_Id)->where('acd_transcript.Student_Id', $student_id)
            ->where('acd_grade_department.Department_Id', $student->Department_Id)
            ->select('acd_grade_department.Weight_Value')->first();

            $weight_val = DB::table('acd_prerequisite_detail')
            ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_prerequisite_detail.Grade_Letter_Id')
            ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
            ->where('Prerequisite_Id', $prereq->Prerequisite_Id)
            ->where('acd_grade_department.Department_Id', $student->Department_Id)
            ->select('acd_grade_department.Weight_Value')->first();

            $cour = DB::table('acd_course')->where('Course_Id', $prereq->Course_Id)->first();
            $gra = DB::table('acd_grade_letter')->where('Grade_Letter_Id', $prereq->Grade_Letter_Id)->first();

            if ($cekdata > 0) {
              if ($grade->Weight_Value < $weight_val->Weight_Value) {
                $notif = "Nilai Matakuliah ".$cour->Course_Name." kurang dari ".$gra->Grade_Letter.".";
              }
            }else {
              $notif = "Data tidak ditemukan atau nilai matakuliah ".$cour->Course_Name." kosong.";
            }

          }elseif($prereq->Prerequisite_Type_Id == 2){
            $count_course = DB::table('acd_student_krs')->where('Student_Id', $student_id)->where('Course_Id', $cour->Course_Id)->where('Class_Prog_Id', $student->Class_Prog_Id)->where('Term_Year_Id', $term_year)->count();
            if ($count_course > 0) {

            }else {
              $notif = "Matakuliah ".$cour->Course_Name." harus diambil terlebih dahulu.";
            }
          }elseif ($prereq->Prerequisite_Type_Id == 3) {
            $cekdata = DB::table('acd_transcript')->where('Course_Id', $prereq->Course_Id)->where('Student_Id', $student_id)->count();
            $grade = DB::table('acd_transcript')
            ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
            ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
            ->where('acd_transcript.Course_Id', $prereq->Course_Id)->where('acd_transcript.Student_Id', $student_id)
            ->where('acd_grade_department.Department_Id', $student->Department_Id)
            ->select('acd_grade_department.Weight_Value')->first();
            $weight_val = DB::table('acd_prerequisite')
            ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
            ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
            ->where('Prerequisite_Id', $prereq->Prerequisite_Id)
            ->where('acd_grade_department.Department_Id', $student->Department_Id)
            ->select('acd_grade_department.Weight_Value')->first();

            $cour = DB::table('acd_course')->where('Course_Id', $prereq->Course_Id)->first();
            $gra = DB::table('acd_grade_letter')->where('Grade_Letter_Id', $prereq->Grade_Letter_Id)->first();

            if ($cekdata > 0) {
              if ($grade->Weight_Value < $weight_val->Weight_Value) {
                $notif = "Nilai Matakuliah ".$cour->Course_Name." kurang dari ".$gra->Grade_Letter.".";
              }
            }else {
              $count_course = DB::table('acd_student_krs')->where('Student_Id', $student_id)->where('Course_Id', $cour->Course_Name)->where('Class_Prog_Id', $student->Class_Prog_Id)->where('Term_Year_Id', $term_year)->count();
              if ($count_course > 0) {

              }else {
                $notif = "Nilai Matakuliah ".$cour->Course_Name." kurang dari ".$gra->Grade_Letter.".";
              }
            }
          }elseif ($prereq->Prerequisite_Type_Id == 4) {
            $entry_year = $student->Entry_Year_Id."".$student->Entry_Year_Id;
            $term_year = DB::table('acd_student_krs')->where('Student_Id', $student_id)->select('Term_Year_Id')->orderby('Term_Year_Id', 'DESC')->first();
            $result = 0;
            $result = $term_year->Term_Year_Id - $entry_year;
            if ($result % 2 == 1) {
              $result = $result - 1;
              $result = $result / 5;
              $result = $result + 2;

            }elseif ($result % 2 == 0) {
              $result = $result / 5;
              $result = $result + 1;
            }
            $value = $prereq->Value;
            if ($result < $value) {
              $notif = $notif." Anda Belum masuk Semester ".$value.".";
            }else {

            }
          }elseif ($prereq->Prerequisite_Type_Id == 5) {
            $total_sks = DB::table('acd_transcript')->where('Student_Id', $student_id)->where('Grade_Letter_Id','!=', null)->sum('Sks');
            $value = $prereq->Value;
            if ($total_sks < $value) {
              $notif = $notif." Total SKS yang ditempuh belum mencukupi ".$value." SKS.";
            }else {

            }
          }elseif ($prereq->Prerequisite_Type_Id == 6) {
            $total_sks_nilai_d = DB::table('acd_transcript')->where('Student_Id', $student_id)->where('Grade_Letter_Id', D)->sum('Sks');
            $value = $prereq->Value;
            if ($total_sks_nilai_d > $value) {
              $notif = $notif." Total SKS nilai D yang telah ditempuh melebihi ".$value." SKS.";
            }else {

            }
          }elseif ($prereq->Prerequisite_Type_Id == 7) {
            $total_sks_xbbt = 0;
            $xbbt = DB::table('acd_transcript')->where('Student_Id', $student_id)->where('Grade_Letter_Id','!=', null)->get();
            foreach ($xbbt as $val) {
              $total_sks_xbbt = $total_sks_xbbt + ($val->Sks * $val->Weight_Value);
            }

            $total_sks = DB::table('acd_transcript')->where('Student_Id', $student_id)->where('Grade_Letter_Id','!=', null)->sum('Sks');
            $ipk_transkript = $total_sks_xbbt / $total_sks;
            $ipk_prasyarat = $prereq->Value;
            if ($ipk_transkript < $ipk_prasyarat) {
              $notif = $notif." IPK anda kurang dari ".$ipk_prasyarat.".";
            }else {

            }
          }else {

          }
        }

        if ($notif != null) {
        return Redirect::back()->withErrors($notif)->with('success', false);
        }

      }

      if ($notif != null) {
        return Redirect::back()->withErrors($notif)->with('success', false);
      }

      //Ini dimatikan dulu
      $saldo = DB::select('CALL usp_Saldo(?,?)',array($student_id,$term_year));
      // $saldo = 0;
      $usedSKS = DB::table('acd_student_krs')->where('Student_Id', $student_id)->where('Term_Year_Id', $term_year)->sum('Sks');
      $allowedSKS = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($term_year,$student->Nim));
      $coursecostforkrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($department_id,$term_year,$class_prog_id,$entry_year,$course_id));
      $studentbill = DB::select('CALL usp_GetStudentBill(?,?,?)',array($student->Register_Number,'',''));

      $Free = 0;
      $Allowed_sks = 0;
      $Applied_sks = 0;
      $Amount_coursecost = 0;
      $Sisasaldoini = 0;

      foreach ($classinfo as $clsinfo) {
        $Free = $clsinfo->Free;
      }
      foreach ($allowedSKS as $alwdsks) {
        $Allowed_sks = $alwdsks->AllowedSKS;
      }
      foreach ($coursecostforkrs as $coursecostkrs) {
        $Applied_sks = $coursecostkrs->applied_sks;
        $Amount_coursecost = $coursecostkrs->amount;
      }

      //Ini dimatikan dulu
      // foreach ($saldo as $sald) {
      //   $Sisasaldoini = $sald->SisaSaldoSaatIni;
      // }


      // if ($Free <= 0 || ( $Allowed_sks - $usedSKS ) < $Applied_sks || $Sisasaldoini < $Amount_coursecost || $notif != "") {
         if($Free <= 0) {
           $notifmessage = "Kelas Sudah Penuh";
           return Redirect::back()->withErrors($notifmessage)->with('success', false);;
         }
         if (($Allowed_sks - $usedSKS) < $Applied_sks) {
           $notifmessage = "Sisa SKS Tidak Mencukupi";
           return Redirect::back()->withErrors($notifmessage)->with('success', false);;
         }

         //TANPA SALDO
         // if($Sisasaldoini < $Amount_coursecost) {
         //   $notifmessage = "Sisa Saldo Tidak Mencukupi";
         //   return Redirect::back()->withErrors($notifmessage)->with('success', false);;
         // }
         if($amount==null){
           $notifmessage = "Biaya SKS matakuliah belum diset";
           return Redirect::back()->withErrors($notifmessage)->with('success', false);;
         }
         $i = 0;
         $ListTagihan = [];
         if($studentbill!=null){
           foreach ($studentbill as $key) {
             $ListTagihan[$i]['Amount'] = $key->Amount;
             $ListTagihan[$i]['Cost_Item_Name'] = $key->Cost_Item_Name;
             $i++;
           }

           $sumAmount =0;
                 foreach ($ListTagihan as $tagihan) {
                   $sumAmount += $tagihan['Amount'];
                 }
          $total = number_format($sumAmount,'0',',','.');
           $notifmessage ='Anda Masih Memilik Tagihan Sebesar ' .$total;
           return Redirect::back()->withErrors($notifmessage)->with('success', false);;
         }
      // }
      $date = Date('Y-m-d');

      $cost_item_id = 3; //deposit KRS, nanti dikembangkan bisa 9(KKN)
      DB::table('acd_student_krs')
      ->insert(
      ['Student_Id' => $student_id,'Term_Year_Id' => $term_year,'Course_Id' => $course_id,'Class_Prog_Id' => $class_prog_id, 'Class_Id' => $class_id, 'Sks' => $sks, 'Amount' => $amount, 'Krs_Date' => $date, 'Created_Date' => $date, 'Modified_Date' => $date]);
      return Redirect::back()->withErrors("Berhasil menambah data")->with('success', true);;

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
      $nim = Input::get('nim');
      $term_year = Input::get('term_year');
      $class = Input::get('class');

      $acd_student_krs = DB::table('acd_student_krs')->where('Krs_Id', $id)->first();
      $class_id = $acd_student_krs->Class_Id;
      if ($class != "") {
        $class_id = $class;
      }

      if (!$acd_student_krs) {
         return Response(view('404'));
      }
      $student = DB::table('acd_student')->where('Nim', $nim)->first();
      $course = DB::table('acd_course')->where('Course_Id', $acd_student_krs->Course_Id)->first();
      $select_class = DB::table('acd_offered_course')->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')->where('Course_Id', $acd_student_krs->Course_Id)->where('Term_Year_Id', $acd_student_krs->Term_Year_Id)->where('Class_Prog_Id', $acd_student_krs->Class_Prog_Id)->get();

      $classinfo = DB::select('CALL usp_GetClassInfoForKRS(?,?,?,?,?)', array($acd_student_krs->Term_Year_Id,$student->Department_Id,$acd_student_krs->Class_Prog_Id,$acd_student_krs->Course_Id,$class_id));
      $notif = null;
      $kapasitas = "";
      $terdaftar = "";
      $sisakuota = "";
      $dosen = "";

      foreach ($classinfo as $clsinfo) {
        $kapasitas = $clsinfo->Capacity;
        $terdaftar = $clsinfo->Used;
        $sisakuota = $clsinfo->Free;
      }
      if($sisakuota <= 0){ $notif = "Kelas Sudah Penuh" ; }

      return view('krs_mahasiswa/edit')->with('acd_student_krs', $acd_student_krs)->with('course',$course)->with('select_class', $select_class)->with('class', $class_id)->with('kapasitas', $kapasitas)->with('terdaftar', $terdaftar)->with('sisakuota', $sisakuota)->with('nim', $nim)->with('term_year', $term_year)->with('student', $student)->with('notif', $notif);
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
        'class_id'=>'required',
      ]);
      $student_id = Input::get('Student_Id');
      $class_id = Input::get('class_id');
      $sks = Input::get('Sks');
      $amount = Input::get('Amount');
      $capacity = Input::get('Capacity');
      $terdaftar = Input::get('Terdaftar');
      $sisa_kuota = Input::get('Sisa_Kuota');

      $acd_student_krs = DB::table('acd_student_krs')->where('Krs_Id', $id)->first();
      $student = DB::table('acd_student')->where('Student_Id', $student_id)->first();

      $classinfo = DB::select('CALL usp_GetClassInfoForKRS(?,?,?,?,?)', array($acd_student_krs->Term_Year_Id,$student->Department_Id,$acd_student_krs->Class_Prog_Id,$acd_student_krs->Course_Id,$class_id));

      $free = 0;
      foreach ($classinfo as $clsinfo) {
        $free = $clsinfo->Free;
      }
      if($free <= 0){
        $notif = "Kelas Sudah Penuh" ;
        return Redirect::back()->withErrors($notif)->with('success', false);;
      }else {
        $date = Date('Y-m-d');
        DB::table('acd_student_krs')
        ->where('Krs_Id', $id)
        ->update(
        ['Student_Id' => $student_id, 'Class_Id' => $class_id, 'Sks' => $sks, 'Amount' => $amount, 'Krs_Date' => $date,'Modified_Date' => $date]);

        return Redirect::back()->withErrors('Berhasil Merubah Data')->with('success', true);;
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
        $selectkhs=DB::table('acd_student_khs')->where('Krs_Id', $id)->Select('Khs_Id')->first();
        $selectweight=DB::table('acd_student_khs')->where('Krs_Id', $id)->Select('Weight_Value')->first();
        // dd($selectweight);

        if($selectweight == null){
          $q=DB::table('acd_student_krs')->where('Krs_Id', $id)->delete();
        }

         echo json_encode($q);
     }

     public function export(Request $request, $id){
       $term_year = Input::get('term_year');
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       $term_year_session =  $request->session()->get('term_year');
       $nim = $id;
       $student_data = DB::table('acd_student')
       ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
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

        $semester = DB::table('mstr_term_year')
            ->whereBetween('Term_Year_Id', [$student_data->Entry_Year_Id.'1', $term_year_session])
            ->get();
        // $semester = $student_data->Entry_Year_Id.'1';
        // if($semester == $term_year){
        //   $smt = 1;
        // }else{
        //   $smt = ($term_year-$semester)+1;
        // }
        $smt = count($semester);
        // dd($semester);

        $sksmax = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($term_year,$student_data->Student_Id));

        $dosenpa = DB::table('acd_student_supervision as a')
        ->join('emp_employee as b','a.Employee_Id','=','b.Employee_Id')
        ->where('Student_Id',$student_data->Student_Id)->first();

        $functional_name = '';
        $functional = DB::table('acd_functional_position_term_year as func')
          ->join('emp_functional_position as efp','func.Functional_Position_Id','=','efp.Functional_Position_Id')
          ->join('emp_employee as ee','func.Employee_Id','=','ee.Employee_Id')
          // ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',20201]])
          ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',$term_year_session]])
          ->first();
        if($functional){
          $functional_name = ($functional->First_Title == null ? '':$functional->First_Title.', ').$functional->Name.($functional->Last_Title == null ? '':', '.$functional->Last_Title);
        }

        $Bnk_Value = DB::table('acd_transcript')->where('Student_Id',$student_data->Student_Id)->sum('Bnk_Value');
        $Sks_Value = DB::table('acd_transcript')->where('Student_Id',$student_data->Student_Id)->sum('Sks');
        $ipk = 0;
        if($Bnk_Value > 0 && $Sks_Value > 0){
          $ipk = $Bnk_Value / $Sks_Value;
        }

      
       View()->share([
         'data'=>$databaru,
         'department'=>$department,
         'smt'=>$smt,
         'ipk'=>$ipk,
         'sksmax'=>$sksmax,
         'functional_name'=>$functional_name,
         'dosenpa'=>$dosenpa,
         'term_years'=>$term_years,
         'student_data'=>$student_data]);
         $pdf = PDF::loadView('krs_mahasiswa/export_krsmahasiswa');
         return $pdf->stream('TTDPresensi.pdf');
     }
}
