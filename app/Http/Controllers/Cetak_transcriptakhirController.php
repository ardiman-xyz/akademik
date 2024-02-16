<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;

class Cetak_transcriptakhirController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $nim_ = Input::get('nim');
    $FacultyId = Auth::user()->Faculty_Id;

    if($FacultyId==""){
      $student = DB::table('acd_student')
      ->where('Nim', $nim_)->first();
    }else{
      $student = DB::table('acd_student')
      ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->where('Nim', $nim_)->first();
    }
    if($FacultyId==""){
    $data = DB::table('acd_transcript')
    ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
    ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->where('acd_student.Nim',$nim_)
    ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
    DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
    ->get();
  } else{
    $data = DB::table('acd_transcript')
    ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
    ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
    ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
    ->where('mstr_faculty.Faculty_Id', $FacultyId)
    ->where('acd_student.Nim',$nim_)
    ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
    DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
    ->get();
  }

if($FacultyId==""){
    $query=DB::table('acd_transcript')
    ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
    DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
    DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Nim',$nim_)->first();
} else{
  $query=DB::table('acd_transcript')
  ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
  DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
  DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
  ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
  ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
  ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
  ->where('mstr_faculty.Faculty_Id', $FacultyId)
  ->where('acd_student.Nim',$nim_)->first();
}
    $jumlahdata=DB::table('acd_transcript')->select(
    DB::raw('count(acd_transcript.Transcript_Id) as jmldata'))->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->where('acd_student.Nim',$nim_)->first();



    return view('cetak/index_transcriptakhir')->with('jmldata', $jumlahdata)->with('student',$student)->with('query_', $query)->with('query',$data)->with('nim',$nim_);
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

   public function export($id)
   {
     $type = Input::get('type');

     $nim = Input::get('nim');
     $student=DB::table('acd_student')
     ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
     ->leftjoin('acd_yudisium','acd_yudisium.Student_Id','=','acd_student.Student_Id')
     ->where('Nim',$id)
     ->select('mstr_department.*','acd_student.*','acd_yudisium.*',
     DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
     ->first();

     $faculty=DB::table('acd_student')
     ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
     ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
     ->select('mstr_faculty.Faculty_Name')
     ->where('Nim',$id)
     ->first();

     $query=DB::table('acd_transcript')
     ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
     DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
     DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
     ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Nim',$id)->first();

     $data = DB::table('acd_transcript')
     ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
      DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
     ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
     ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
     ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
      ->where('acd_student.Nim',$id)
     ->get();

     $predikat ="";
     $predikateng ="";
     $ip=$query->ipk;
     if ($ip >= 3.71 && $ip <= 4) {
       $predikat ="Pujian";
       $predikateng ="Pujian";
     }
     elseif ($ip >= 3.41 && $ip <= 3.70) {
       $predikat ="Sangat Memuaskan";
       $predikateng ="Sangat Memuaskan";
     }
     elseif ($ip >= 2.75 && $ip <= 3.40) {
       $predikat ="Memuaskan";
       $predikateng ="Memuaskan";
     }
     else {
        $predikat ="";
        $predikateng ="";
     }

     $program_type=DB::table('acd_student')
     ->where('acd_student.Nim',$id)
     ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
     ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
     ->select('mstr_education_program_type.Program_Name','mstr_education_program_type.Acronym')
     ->first();

     $education_prog=DB::table('acd_transcript')
     ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
     ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
     ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
     ->where('acd_student.Nim',$id)
     ->groupBy('Education_Prog_Type_Id')
     ->select('mstr_department.Education_Prog_Type_Id')
    ->first();

    $date = date('Y-m-d H:i:s');
    $term_yearcount=DB::table('mstr_term_year')
    ->where('Start_Date','<=',$date)
    ->where('End_Date','>=',$date)
    ->select('Start_Date','End_Date')
    ->count();

    if($term_yearcount == 0){
      $term_year1 ="";
    }else{
      $term_year1=DB::table('mstr_term_year')
      ->where('Start_Date','<=',$date)
      ->where('End_Date','>=',$date)
      ->select('Term_Year_Id')
      ->first();
      $term_year1=$term_year1->Term_Year_Id;
    }

     $namadekan="";
     $nidn="";
     $ttd="";
     $ttdeng="";
     switch ($education_prog->Education_Prog_Type_Id) {
       case 2:
         //$namadekan="Dekan Nya";
         // $namadekan=DB::table('acd_functional_position_term_year')
         // ->join('emp_employee','emp_employee.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
         // ->join('emp_functional_position','acd_functional_position_term_year.Functional_Position_Id','=','emp_functional_position.Functional_Position_Id')
         // ->where('acd_functional_position_term_year.Faculty_Id',$education_prog->Faculty_Id)
         // ->where('emp_functional_position.Functional_Position_Code','D')
         // ->select('emp_employee.Full_Name')
         $ttd="Wakil Ketua 1";
         $ttdeng="Vice Director";
         try{
           $countnamadekan=DB::table('emp_employee')
           ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
           ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
           ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
           ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
           ->where('emp_functional_position.Functional_Position_Code','WK1')
           ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
           ->groupBy('emp_employee.Employee_Id')
           ->count();

           $namadekan=DB::table('emp_employee')
           ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
           ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
           ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
           ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
           ->where('emp_functional_position.Functional_Position_Code','WK1')
           ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
           ->groupBy('emp_employee.Employee_Id')
           ->select('emp_employee.Full_Name')
           ->first();
           
           $nik=DB::table('emp_employee')
           ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
           ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
           ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
           ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
           ->where('emp_functional_position.Functional_Position_Code','WK1')
           ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
           ->groupBy('emp_employee.Employee_Id')
           ->select('emp_employee.Nik')
           ->first();


           if($countnamadekan == 0){
             $namadekan="";
             $nidn = "";
           } else {
             $namadekan=$namadekan->Full_Name;
             $nidn=$nik->Nik;
             // dd($nidn);
           }
         } catch(EXCEPTION $e){
           $namadekan="";
         }
         break;

         case 3:
           $ttd="Ketua Program Studi";
           try{
             $countnamadekan=DB::table('emp_employee')
             ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
             ->join('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
             ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
             ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
             ->where('emp_functional_position.Functional_Position_Code','KP')
             ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
             ->groupBy('emp_employee.Employee_Id')
             ->count();

             $namadekan=DB::table('emp_employee')
             ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
             ->join('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
             ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
             ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
             ->where('emp_functional_position.Functional_Position_Code','KP')
             ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
             ->groupBy('emp_employee.Employee_Id')
             ->select('emp_employee.Full_Name')
             ->first();

             if($countnamadekan == 0){
               $namadekan="";
             } else {
               $namadekan=$namadekan->Full_Name;
             }
           } catch(EXCEPTION $e){
             $namadekan="";
           }
           break;

       default:
       $ttd="Kepala Program Studi";
       try{
         $countnamadekan=DB::table('emp_employee')
         ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
         ->join('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
         ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
         ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
         ->where('emp_functional_position.Functional_Position_Code','KP')
         ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
         ->groupBy('emp_employee.Employee_Id')
         ->count();

         $namadekan=DB::table('emp_employee')
         ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
         ->join('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
         ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
         ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
         ->where('emp_functional_position.Functional_Position_Code','KP')
         ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
         ->groupBy('emp_employee.Employee_Id')
         ->select('emp_employee.Full_Name')
         ->first();

         if($countnamadekan == 0){
           $namadekan="";
         } else {
           $namadekan=$namadekan->Full_Name;
         }
       } catch(EXCEPTION $e){
         $namadekan="";
       }
       break;
     }

     $dosen = DB::table('emp_employee')->join('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Employee_Id' , '=', 'emp_employee.Employee_Id')
     ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_lecturer.Offered_Course_id')
     ->where('acd_offered_course.Offered_Course_id', $id)
     ->orderBy('acd_offered_course_lecturer.Order_Id' , 'asc')
     ->get();

     // $enddate_thesis=DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')->where('acd_student.Nim',$id)->select('acd_thesis.Thesis_Complete_Date')->first();
     //
     $thesis_count=DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')->where('acd_student.Nim',$id)->select('acd_thesis.Thesis_Title')->count();
     $thesiseng_count=DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')->where('acd_student.Nim',$id)->select('acd_thesis.Thesis_Title_Eng')->count();

     $thesis_title = "";
     $thesiseng_title = "";
     if($thesis_count==0){
       $thesis_title = "";
     }else{
       $thesis_=DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')->where('acd_student.Nim',$id)->select('acd_thesis.Thesis_Title')->first();
       $thesis_title = $thesis_->Thesis_Title;
       if($thesiseng_count>0){
         $thesiseng_=DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')->where('acd_student.Nim',$id)->select('acd_thesis.Thesis_Title_Eng')->first();
         $thesiseng_title = $thesiseng_->Thesis_Title_Eng;
       }else{
         $thesiseng_title = "";
       }
     }

     View()->share(['nidn'=>$nidn,'thesiseng_title'=>$thesiseng_title,'thesis_title'=>$thesis_title, 'predikateng'=>$predikateng, 'predikat'=>$predikat,'program_type'=>$program_type,'faculty'=>$faculty,'ttd'=>$ttd,'ttdeng'=>$ttdeng,'dekan'=>$namadekan,'dosen'=>$dosen,'query_'=>$query,'data'=> $data,'nim'=>$nim,'student'=>$student]);
     if ($type == "transkripakhir") {
       $pdf = PDF::loadView('cetak/export_transcriptakhir');
       return $pdf->stream('Transkrip_akhir.pdf');
     }
     // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);

   }


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
