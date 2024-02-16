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

class Transcript_sementaraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $nim_ = Input::get('nim');
      $student=DB::table('acd_student')
      ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
      ->select('acd_student.*')->where('Nim',$nim_)->first();
      $departement=DB::table('acd_student')
      ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
      ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
      ->select('mstr_faculty.Faculty_Name')->where('Nim',$nim_)->first();
      $data = DB::table('acd_transcript')
      ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
      ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
      ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
      ->where('acd_student.Nim',$nim_)
      ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
      DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
      ->get();

      $query=DB::table('acd_transcript')
      ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
      DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
      DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
      ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Nim',$nim_)->first();




      return view('cetak/index_transcriptsementara')->with('student',$student)->with('query_', $query)->with('query',$data)->with('nim',$nim_);
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
       $student=DB::table('acd_student')->where('Nim',$id)->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')->select('mstr_department.Department_Name','acd_student.*')->first();
       $faculty=DB::table('acd_student')
       ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
       ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
       ->select('mstr_faculty.Faculty_Name')->where('Nim',$id)->first();
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

        $education_prog=DB::table('acd_transcript')
        ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
        ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
        ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
        ->where('acd_student.Nim',$id)
        ->groupBy('Education_Prog_Type_Id')
        ->select('mstr_department.Education_Prog_Type_Id')
       ->first();

       $date = date('Y-m-d H:i:s');
       $term_year1=DB::table('mstr_term_year')
       ->where('Start_Date','<=',$date)
       ->where('End_Date','>=',$date)
       ->select('Term_Year_Id')
       ->first();

       $namadekan="";
       $ttd="";
       switch ($education_prog->Education_Prog_Type_Id) {
         case 2:
           //$namadekan="Dekan Nya";
           // $namadekan=DB::table('acd_functional_position_term_year')
           // ->join('emp_employee','emp_employee.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
           // ->join('emp_functional_position','acd_functional_position_term_year.Functional_Position_Id','=','emp_functional_position.Functional_Position_Id')
           // ->where('acd_functional_position_term_year.Faculty_Id',$education_prog->Faculty_Id)
           // ->where('emp_functional_position.Functional_Position_Code','D')
           // ->select('emp_employee.Full_Name')
           $ttd="Dekan";
           try{
             $namadekan=DB::table('emp_employee')
             ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
             ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
             ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
             ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
             ->leftjoin('acd_student' , 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
             ->where('acd_student.Nim',$id)
             ->where('emp_functional_position.Functional_Position_Code','D')
             ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1->Term_Year_Id)
             ->groupBy('emp_employee.Employee_Id')
             ->select('emp_employee.Full_Name')
             ->first();
           } catch(EXCEPTION $e){
             $namadekan="";
           }
           break;

           case 3:
             $ttd="Ketua Program Studi";
             try{
               $namadekan=DB::table('emp_employee')
               ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
               ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
               ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
               ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
               ->leftjoin('acd_student' , 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
               ->where('acd_student.Nim',$id)
               ->where('emp_functional_position.Functional_Position_Code','KP')
               ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1->Term_Year_Id)
               ->groupBy('emp_employee.Employee_Id')
               ->select('emp_employee.Full_Name')
               ->first();
             } catch(EXCEPTION $e){
               $namadekan="";
             }
             break;

         default:
         $ttd="Kepala Program Studi";
         try{
           $namadekan=DB::table('emp_employee')
           ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
           ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
           ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
           ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
           ->leftjoin('acd_student' , 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
           ->where('acd_student.Nim',$id)
           ->where('emp_functional_position.Functional_Position_Code','KP')
           ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1->Term_Year_Id)
           ->groupBy('emp_employee.Employee_Id')
           ->select('emp_employee.Full_Name')
           ->first();
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

       View()->share(['faculty'=>$faculty,'ttd'=>$ttd,'dekan'=>$namadekan,'dosen'=>$dosen,'query_'=>$query,'data'=> $data,'nim'=>$nim,'student'=>$student]);
       if ($type == "transkripsementara") {
         $pdf = PDF::loadView('cetak/export_transcriptsementara');
         return $pdf->stream('Transkrip_sementara.pdf');
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
