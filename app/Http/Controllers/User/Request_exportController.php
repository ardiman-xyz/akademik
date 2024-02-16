<?php

namespace App\Http\Controllers\User;

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

class Request_exportController extends Controller
{
   public function export(Request $request,$id)
   {
     $password = $request->password;
     $sid = 'backterial';
     $type = Input::get('type');

     $nim = Input::get('nim');

     $student=DB::table('acd_student')
     ->where('Nim',$id)
     ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
     ->join('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
     ->select('mstr_department.*','acd_student.*','mstr_education_program_type.Acronym','mstr_education_program_type.Program_Name')->first();

    if(md5($sid) != $request->sid){
      return response()->json(['success'=>false,'message'=>'Not Access']); 
    }
    if(!$student){
      return response()->json(['success'=>false,'message'=>'No Data']); 
    }
    if(md5($password) != $student->Student_Password){
      return response()->json(['success'=>false,'message'=>'Not Allowed']); 
    }

     $faculty=DB::table('acd_student')
     ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
     ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
     ->select('mstr_faculty.Faculty_Name')->where('Nim',$id)->first();

     $querys=DB::table('acd_transcript')
      ->select('acd_transcript.Sks','acd_transcript.Weight_Value','acd_transcript.Khs_Id')
      ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
      ->where('acd_student.Nim',$id)
      ->where('acd_transcript.is_Use',true)
      ->get();
      // 6112200001
      $query['jml_sks'] = 0;
      $query['jml_mutu'] = 0;
      $query['ipk'] = 0;
      $r = 0;
      foreach ($querys as $key) {
        // if($key->Khs_Id != null){
        //   $krs = DB::table('acd_student_khs')->where('Khs_Id',$key->Khs_Id)->first();
        //   $component = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$krs->Krs_Id)->first();
        //   if($component == null){
        //     $query['jml_sks'] = $query['jml_sks'] + $key->Sks;
        //     $query['jml_mutu'] = $query['jml_mutu'] + ($key->Sks * $key->Weight_Value );
        //     $query['ipk'] = $query['jml_mutu']/$query['jml_sks'];
        //   }else{
        //     if($component->UAS_Score != null){
        //       $query['jml_sks'] = $query['jml_sks'] + $key->Sks;
        //       $query['jml_mutu'] = $query['jml_mutu'] + ($key->Sks * $key->Weight_Value );
        //       $query['ipk'] = $query['jml_mutu']/$query['jml_sks'];
        //     }
        //   }
        // }else{
          $query['jml_sks'] = $query['jml_sks'] + $key->Sks;
          $query['jml_mutu'] = $query['jml_mutu'] + ($key->Sks * $key->Weight_Value );
          $query['ipk'] = $query['jml_mutu']/$query['jml_sks'];
        // }
      }
      $query['ipk'] = number_format($query['ipk'],2);

     
     $Datetimenow = Date('Y-m-d');
     $active = DB::Table('mstr_term_year')->where('Start_Date','<=',$Datetimenow)->where('End_Date','>=',$Datetimenow)->select('Term_Year_Id')->first();

     $curiculum = DB::table('acd_curriculum_entry_year')
     ->where('Department_Id',$student->Department_Id)
     ->where('Class_Prog_Id',$student->Class_Prog_Id)
     ->where('Entry_Year_Id',$student->Entry_Year_Id)
     ->first();

     $data = DB::table('acd_transcript')
     ->select('acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id','acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
      DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
     ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
     ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
     ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
     ->join('acd_course_curriculum','acd_course.Course_Id','acd_course_curriculum.Course_Id')
      ->where('acd_student.Nim',$id)
    ->where('acd_transcript.is_Use',true)
      // ->where('acd_course_curriculum.Curriculum_Id',$curiculum->Curriculum_Id)
      ->groupby('acd_course.Course_Id')
     ->get();
     $data_d = DB::table('acd_transcript')
    //  ->select('acd_course_curriculum.Study_Level_Id','acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
    //   DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
     ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
     ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
     ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
     ->join('acd_student_khs','acd_transcript.Khs_Id','acd_student_khs.Khs_Id')
     ->where('acd_student.Nim',$id)
    ->where('acd_transcript.is_Use',true)
     // ->where('acd_course_curriculum.Curriculum_Id',$curiculum->Curriculum_Id)
     ->where('acd_grade_letter.Grade_Letter','D')
     ->groupby('acd_course.Course_Id')
     ->get();

     if(count($data_d) > 0){
       $nilai_d = (count($data_d) / count($data))* 100;
       $nilai_d = number_format($nilai_d,2);
     }else{
      $nilai_d = 0;
     }

     $datas = DB::table('acd_transcript')
    ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
    ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->join('acd_course_curriculum' ,function ($join)
      {
        $join
        ->on('acd_student.Class_Prog_Id','=','acd_course_curriculum.Class_Prog_Id')
        ->on('acd_transcript.Course_Id','=','acd_course_curriculum.Course_Id')
        ->on('acd_student.Department_Id','=','acd_course_curriculum.Department_Id');
      })
    ->where('acd_student.Nim',$id)
    ->where('acd_transcript.is_Use',true)
    ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*','acd_course_curriculum.Study_Level_Id',
      DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
    ->orderby('acd_course.Course_Code')
    ->groupby('acd_transcript.Student_Id','acd_transcript.Course_Id')
    ->get();

    // $datas = DB::table('acd_transcript')
    // ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
    // ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
    // ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    // ->where('acd_student.Nim',$id)
    // // ->where('acd_student.Department_Id','like', '%'.$DepartmentId.'%')
    // ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
    // DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
    // // ->select('acd_transcript.*')
    // ->orderby('acd_course.Course_Code')
    // ->get();
    // dd($datas);
     $p = 0;
    $smt = [];
    foreach ($datas as $key) {
      if($key->Khs_Id != null){
        $krs = DB::table('acd_student_khs')->where('Khs_Id',$key->Khs_Id)->first();
        $component = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$krs->Krs_Id)->first();
        if($component == null){
          $smt[$p]['Sks'] = $key->Sks;
          $smt[$p]['Is_Transfer'] = $key->Is_Transfer;
          $smt[$p]['Weight_Value'] = $key->Weight_Value;
          $smt[$p]['is_Use'] = $key->is_Use;
          $smt[$p]['Is_Required'] = $key->Is_Required;
          $smt[$p]['Bnk_Value'] = $key->Bnk_Value;
          $smt[$p]['Grade_Letter'] = $key->Grade_Letter;
          $smt[$p]['Course_Code'] = $key->Course_Code;
          $smt[$p]['Course_Name'] = $key->Course_Name;
          $smt[$p]['Course_Name_Eng'] = $key->Course_Name_Eng;
          $smt[$p]['weightvalue'] = $key->weightvalue;
          $smt[$p]['Study_Level_Id'] = $key->Study_Level_Id;
        }else{
          // if($component->UAS_Score != null){
            $smt[$p]['Sks'] = $key->Sks;
            $smt[$p]['Is_Transfer'] = $key->Is_Transfer;
            $smt[$p]['Weight_Value'] = $key->Weight_Value;
            $smt[$p]['is_Use'] = $key->is_Use;
            $smt[$p]['Is_Required'] = $key->Is_Required;
            $smt[$p]['Bnk_Value'] = $key->Bnk_Value;
            $smt[$p]['Grade_Letter'] = $key->Grade_Letter;
            $smt[$p]['Course_Code'] = $key->Course_Code;
            $smt[$p]['Course_Name'] = $key->Course_Name;
            $smt[$p]['Course_Name_Eng'] = $key->Course_Name_Eng;
            $smt[$p]['weightvalue'] = $key->weightvalue;
            $smt[$p]['Study_Level_Id'] = $key->Study_Level_Id;
          // }
        }
      }else{
        $smt[$p]['Sks'] = $key->Sks;
        $smt[$p]['Is_Transfer'] = $key->Is_Transfer;
        $smt[$p]['Weight_Value'] = $key->Weight_Value;
        $smt[$p]['is_Use'] = $key->is_Use;
        $smt[$p]['Is_Required'] = $key->Is_Required;
        $smt[$p]['Bnk_Value'] = $key->Bnk_Value;
        $smt[$p]['Grade_Letter'] = $key->Grade_Letter;
        $smt[$p]['Course_Code'] = $key->Course_Code;
        $smt[$p]['Course_Name'] = $key->Course_Name;
        $smt[$p]['Course_Name_Eng'] = $key->Course_Name_Eng;
        $smt[$p]['weightvalue'] = $key->weightvalue;
        $smt[$p]['Study_Level_Id'] = $key->Study_Level_Id;
      }

      $p++;
    }

    // dd($smt);
    $nilai_d = 0;
    foreach ($smt as $key)if($key['Grade_Letter'] == 'D' && $key['is_Use'] == true){
      $nilai_d = $nilai_d+$key['Sks'];
    }
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

     $dosen = DB::table('emp_employee')->join('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Employee_Id' , '=', 'emp_employee.Employee_Id')
     ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_lecturer.Offered_Course_id')
     ->where('acd_offered_course.Offered_Course_id', $id)
     ->orderBy('acd_offered_course_lecturer.Order_Id' , 'asc')
     ->get();

     
    //  dd($active);
    if($active != null){
      $atv = $active->Term_Year_Id;
    }else{
      $atv = 20191;
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

    // $keperluans = $reason = DB::table('mstr_transcript_reason')->where('Transcript_Reason_Id',$request->keperluan)->first();
    $keperluan = $request->keperluan;

    $complete_sks = 0;
    if($student){
      $curiculum = DB::table('acd_curriculum_entry_year')
       ->where('Department_Id',$student->Department_Id)
       ->where('Class_Prog_Id',$student->Class_Prog_Id)
       ->where('Entry_Year_Id',$student->Entry_Year_Id)
       ->first();
       if($curiculum){
         $complete_skss = DB::table('mstr_curriculum_applied')
         ->where('Department_Id',$student->Department_Id)
         ->where('Class_Prog_Id',$student->Class_Prog_Id)
         ->where('Curriculum_Id',$curiculum->Curriculum_Id)
         ->select('Sks_Completion')
         ->first();
         if($complete_skss != null){
           $complete_sks = $complete_skss->Sks_Completion;
         }else{
           $complete_sks = 0;
         }
       }else{
        $complete_sks = 0;
       }
    }

     View()->share(['complete_sks'=>$complete_sks,'keperluan'=>$keperluan,'nilai_d'=>$nilai_d,'faculty'=>$faculty,'dosen'=>$dosen,'dosens'=>$dosens,'query_'=>$query,'data'=> $data,'nim'=>$nim,'student'=>$student,'smt'=>$smt]);
     if ($type == "transkripsementara") {
       $pdf = PDF::loadView('cetak/export_transcriptsementara');
       return $pdf->stream('Transkrip_sementara.pdf');
     }else{
       return response()->json(['success'=>false,'message'=>'Notfound']);
     }
   }
}
