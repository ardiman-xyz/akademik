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

class Khs_mahasiswaController extends Controller
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
       $nim = Input::get('nim');
       $search = Input::get('search');
       $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       $select_term_year = DB::table('mstr_term_year')->orderby('Term_Year_Id','desc')->get();

      //  if($FacultyId==""){
      //    $student = DB::table('acd_student')
      //    ->where('Nim', $nim)->first();
      //  }else{
         $student = DB::table('acd_student')
         ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
         ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
        //  ->where('mstr_faculty.Faculty_Id', $FacultyId)
         ->where('Nim', $nim)->first();
      //  }

      if($student){
        $check_curriculum = DB::table('acd_curriculum_entry_year')->where([
          ['Department_Id',$student->Department_Id],
          ['Class_Prog_Id',$student->Class_Prog_Id],
          ['Entry_Year_Id',$student->Entry_Year_Id],
          ['Term_Year_Id',$term_year]
          ])->first();
      }

       $student_id = "";
       $dat = "";
       $saldo = "";
       if ($student != null) {
         if($FacultyId != null || $DepartmentId != null){
           if($student->Department_Id != $DepartmentId || $student->Faculty_Id != $FacultyId){
              return Redirect::back()->withErrors('Anda tidak Memiliki Akses Prodi tersebut')->with('success', false);
            }
         }
         $student_id = $student->Student_Id;

         $dat = DB::table('acd_student')
         ->join('mstr_department' , 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
         ->join('mstr_class_program' , 'mstr_class_program.Class_Prog_Id' , '=', 'acd_student.Class_Prog_Id')
         ->where('acd_student.Department_Id', $student->Department_Id)
         ->where('acd_student.Class_Prog_Id', $student->Class_Prog_Id)
         ->first();
        //  dd($dat);

    //Ini dimatikan dulu kalau uspnya error
         $saldo = DB::select('call usp_saldo(?, ?)',[$student->Student_Id , $term_year]);
         // $saldo = 0;

       }else {
         $student_id = "";
       }

       $acd_student_krs = DB::table('acd_student_krs')
       ->leftjoin('acd_course' ,'acd_course.Course_Id','=','acd_student_krs.Course_Id')
       ->leftjoin('acd_course_curriculum', 'acd_course_curriculum.Course_Id', '=', 'acd_course.Course_Id')
       ->leftjoin('mstr_study_level', 'mstr_study_level.Study_Level_Id', '=', 'acd_course_curriculum.Study_Level_Id')
       ->leftjoin('acd_student', 'acd_student.Student_Id','=','acd_student_krs.Student_Id')
       ->leftjoin('mstr_class', 'mstr_class.Class_Id', '=' ,'acd_student_krs.Class_Id')
       ->leftjoin('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student_krs.Class_Prog_Id')
       ->leftjoin('mstr_term_year','mstr_term_year.Term_Year_Id', '=', 'acd_student_krs.Term_Year_Id')
       ->leftjoin('acd_student_khs', 'acd_student_khs.Krs_Id' , '=' , 'acd_student_krs.Krs_Id')
       ->leftjoin('acd_grade_letter' ,'acd_grade_letter.Grade_Letter_Id', '=', 'acd_student_khs.Grade_Letter_Id')
       ->where('acd_student_krs.Student_Id', $student_id)
       ->where('acd_student_krs.Term_Year_Id', $term_year);
       // if($student && isset($check_curriculum)){
       //  $acd_student_krs = $acd_student_krs->where('acd_course_curriculum.Curriculum_Id', $check_curriculum->Curriculum_Id);
       // }
       // $acd_student_krs = $acd_student_krs->select('acd_student_krs.Krs_Id as Krs','acd_student.*','acd_student_khs.*','acd_grade_letter.Grade_Letter','acd_course.*','mstr_study_level.Level_Name','acd_student_khs.Weight_Value as weightvalue',
       // DB::raw('(SELECT Is_For_Transcript FROM acd_course_curriculum WHERE Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
       // AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_student_krs.Term_Year_Id AND Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Is_For_Transcript' ),
       // DB::raw('(SELECT Transcript_Sks FROM acd_course_curriculum WHERE Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
       // AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_student_krs.Term_Year_Id AND Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Transcript_Sks' )
       // )
       $acd_student_krs = $acd_student_krs->groupBy('acd_student_krs.Krs_Id')
       ->get();

      $new_array = [];
      $p = 0;
      // dd($acd_student_krs);
      // foreach ($acd_student_krs as $key) if($key->Krs_Id == 527552){
      //  $bobots=DB::table('acd_student_khs_item_bobot')
      //   ->where([
      //     ['Department_Id',$key->Department_Id],
      //     ['Entry_Year_Id',$key->Entry_Year_Id],
      //     ['Course_Type_Id',$key->Course_Type_Id]
      //   ])->get();
      //   $new_array[$p]['Nim'] = $key->Nim;
      //   $new_array[$p]['Full_Name'] = $key->Full_Name;
      //   $new_array[$p]['Krs_Id'] = $key->Krs_Id;
      //   $new_array[$p]['Course_Code'] = $key->Course_Code;
      //   $new_array[$p]['Course_Name'] = $key->Course_Name;
      //   $new_array[$p]['Sks'] = $key->Sks;
      //   $new_array[$p]['C_Bobot'] = count($bobots);
      //   $total = 0;
      //   foreach ($bobots as $bobot) {
      //     $nilai=DB::table('acd_student_khs_nilai_detail')
      //     ->where([
      //       ['Krs_Id', $key->Krs_Id],
      //       ['Student_Khs_Item_Bobot_Id',$bobot->Student_Khs_Item_Bobot_Id]
      //     ])
      //     ->first();
      //     $new_array[$p]['bobot'][$bobot->Item_Name] = ($nilai ? $nilai->Score:'');
      //     $total = $total+($nilai ? $nilai->Score:0);
      //     $new_array[$p]['Total'] = $total;

      //     $grade_letter = DB::table('acd_grade_department')
      //     ->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
      //     // ->select('acd_grade_letter.Grade_Letter')
      //     ->where([
      //         ['acd_grade_department.Department_Id',$student->Department_Id],
      //         ['acd_grade_department.Entry_Year_Id',$student->Entry_Year_Id],
      //         ['acd_grade_department.Scale_Numeric_Max','>=',$total],
      //         ['acd_grade_department.Scale_Numeric_Min','<=',$total]
      //       ])
      //     ->first();
      //     // dd($grade_letter);
      //     if($grade_letter != null){
      //         $letter = $grade_letter->Grade_Letter;
      //         $Weight_Value = $grade_letter->Weight_Value;
      //     }else{
      //         $letter = "Belum Disetting";
      //         $Weight_Value = "Belum Disetting";
      //     }

      //     $new_array[$p]['Letter'] = $letter;
      //     $new_array[$p]['Weight_Value'] = $Weight_Value;
      //   }
      //   $p++;
      // }
      // dd($new_array);
      $new_array = [];
      $p = 0;
      foreach ($acd_student_krs as $key) {
        $bobots=DB::table('acd_student_khs_item_bobot')
        ->where([
          ['Department_Id',$key->Department_Id],
          ['Entry_Year_Id',$key->Entry_Year_Id],
          ['Course_Type_Id',$key->Course_Type_Id]
        ])->get();

        $new_array[$p]['Weight_Value'] = $key->Weight_Value;
        $new_array[$p]['Nim'] = $key->Nim;
        $new_array[$p]['Full_Name'] = $key->Full_Name;
        $new_array[$p]['Krs_Id'] = $key->Krs_Id;
        $new_array[$p]['Course_Code'] = $key->Course_Code;
        $new_array[$p]['Course_Name'] = $key->Course_Name;
        $new_array[$p]['Letter'] = $key->Grade_Letter;
        $new_array[$p]['Sks'] = $key->Sks;
        $new_array[$p]['C_Bobot'] = count($bobots);
        $p++;
      }
      // dd($new_array);

      $footer['bobot_x_sks'] = 0;
      $footer['total_sks'] = 0;
      $footer['ip_semester'] = 0;
      foreach ($new_array as $key) {
        $footer['bobot_x_sks'] = $footer['bobot_x_sks'] + ($key['Weight_Value'] * $key['Sks']);
        $footer['total_sks'] = $footer['total_sks'] + $key['Sks'];
      }
      if($nim != ''){
        if($footer['total_sks'] == 0 && $footer['ip_semester'] == 0){
          $footer['ip_semester'] = 0;
        }else{
          $footer['ip_semester'] = $footer['bobot_x_sks']/$footer['total_sks'];
        }
      }

       return view('khs_mahasiswa/index')->with('query',$acd_student_krs)->with('saldo', $saldo)->with('student', $student)
       ->with('dat', $dat)
       ->with('search',$search)
       ->with('select_term_year', $select_term_year)
       ->with('term_year', $term_year)
       ->with('new_array', $new_array)
       ->with('footer', $footer)
       ->with('nim', $nim);
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
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       $department = Input::get('dep_id');
       $term_year = Input::get('term_year');
       $class_program = Input::get('class_program');
       $Offered_Course_id = Input::get('Offered_Course_id');

       $page = Input::get('page');
       if ($rowpage == null) {
         $rowpage = 10;
       }



       $krs = DB::table('acd_student_krs')
       ->leftjoin('acd_offered_course' ,function ($join)
       {
         $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
         ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
         ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
         ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
       })
       ->join('acd_student',  'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
       ->leftjoin('acd_student_khs', 'acd_student_khs.Krs_Id' , '=' , 'acd_student_krs.Krs_Id')
       ->leftjoin('acd_grade_letter' ,'acd_grade_letter.Grade_Letter_Id', '=', 'acd_student_khs.Grade_Letter_Id')
       ->join('acd_course' ,'acd_course.Course_Id', '=', 'acd_student_krs.Course_Id')
       ->join('acd_course_curriculum' , 'acd_course_curriculum.Course_Id' , '=', 'acd_course.Course_Id')
       ->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student_krs.Class_Id')
       ->leftjoin('acd_transcript','acd_transcript.Student_Id','=','acd_student_krs.Student_Id')
       ->where('acd_student_krs.Krs_Id', $id)
       ->select('acd_transcript.*','acd_student_krs.Krs_Id as Krs', 'acd_offered_course.Department_Id', 'acd_course_curriculum.Transcript_Sks' , 'acd_course_curriculum.Is_For_Transcript as is_transcript','acd_course.Course_Id', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_student.Student_Id as student', 'acd_student.*','acd_student_khs.*','acd_grade_letter.Grade_Letter_Id','acd_grade_letter.Grade_Letter')
       ->first();

       $grade_letter = DB::table('acd_grade_letter')->join('acd_grade_department', 'acd_grade_department.Grade_Letter_Id' , '=', 'acd_grade_letter.Grade_Letter_Id')->where('Department_Id', $krs->Department_Id)->get();


       return view('khs_mahasiswa/edit')->with('query',$krs)->with('Offered_Course_id', $Offered_Course_id)->with('grade_letter', $grade_letter)->with('search',$search)->with('rowpage',$rowpage)->with('class_program', $class_program)->with('department', $department)->with('term_year', $term_year)->with('page', $page);

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
        // 'Grade_Letter_Id'=>'required',
      ]);

      $Term_Year_Id = input::get('Term_Year_Id');
      $Department_Id = input::get('Department_Id');
      $Khs_Id = Input::get('Khs_Id');
      $transcript_id = Input::get('transcript_id');
      $Grade_Letter_Id = Input::get('Grade_Letter_Id');
      $Student_Id = Input::get('Student_Id');
      $Course_Id = Input::get('Course_Id');
      $transcript_id=DB::table('acd_student_khs')->join('acd_transcript','acd_transcript.Student_Id','=','acd_student_khs.Student_Id')
      ->where('acd_student_khs.Krs_Id',$id)->select('acd_transcript.Transcript_Id')->first();

      $Sks = Input::get('Sks');
      $acd_grade_department = DB::table('acd_grade_department')->where('Grade_Letter_Id', $Grade_Letter_Id)->where('Department_Id', $Department_Id)
      ->select('Weight_Value',DB::raw('count(Weight_Value) as Weight_value'))->first();


      $weight_value = 0;
      if ($acd_grade_department->Weight_value > 0) {
       $weight_value = $acd_grade_department->Weight_Value;
      }


      $Bnk_Value = $acd_grade_department->Weight_Value * $Sks;
      $Is_For_Transkrip = Input::get('Is_For_Transkrip');

      if ($Khs_Id == "") {
        if ($Grade_Letter_Id == "") {
          try {
            return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
          } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
          }

        }else {
          if($Is_For_Transkrip==0){
            try {
              DB::table('acd_student_khs')
              ->insert(
                ['Krs_Id' => $id, 'Student_Id' => $Student_Id,'Grade_Letter_Id' => $Grade_Letter_Id,'Sks' => $Sks, 'Weight_Value' => $weight_value, 'Is_For_Transkrip' => $Is_For_Transkrip, 'Bnk_Value' => $Bnk_Value ]);
                return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);;
            } catch (\Exception $e) {
              return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
            }

            }else {
              try {
                $insert=DB::table('acd_student_khs')
                ->insertGetId(
                  ['Krs_Id' => $id, 'Student_Id' => $Student_Id,'Grade_Letter_Id' => $Grade_Letter_Id,'Sks' => $Sks, 'Weight_Value' => $weight_value, 'Is_For_Transkrip' => $Is_For_Transkrip, 'Bnk_Value' => $Bnk_Value ]);

                  $khs_Id = DB::getPdo()->lastInsertId();
                  $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_Id,''));
                  // $insert2=DB::table('acd_transcript')
                  // ->insert(
                  //   ['Khs_Id'=>$insert, 'Weight_Value' => $weight_value,'is_Use' => 1,'Student_Id' => $Student_Id,'Sks' => $Sks,'Course_Id' => $Course_Id,'Grade_Letter_Id' =>$Grade_Letter_Id, 'Bnk_Value' => $Bnk_Value]);

                    return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);;
              } catch (\Exception $e) {
                return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
              }

          }
        }

     }else{
       if ($Grade_Letter_Id == "") {
         try {
           // DB::table('acd_transcript')->where('acd_transcript.Transcript_Id',$transcript_id->Transcript_Id)->delete();
           // $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($khs_Id,''));
           DB::table('acd_student_khs')->where('Khs_Id', $Khs_Id)->delete();

           return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);;
         } catch (\Exception $e) {
           return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
         }


       }else {
         if($Is_For_Transkrip==0){
           try {
             DB::table('acd_student_khs')
             ->where('acd_student_khs.Khs_Id' , $Khs_Id)
             ->update(
               ['Student_Id' => $Student_Id,'Grade_Letter_Id' => $Grade_Letter_Id,'Sks' => $Sks, 'Weight_Value' => $acd_grade_department->Weight_Value, 'Is_For_Transkrip' => $Is_For_Transkrip, 'Bnk_Value' => $Bnk_Value ]);
               return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);;
           } catch (\Exception $e) {
             return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
           }

         }else{
           try {
             DB::table('acd_student_khs')
             ->where('acd_student_khs.Khs_Id' , $Khs_Id)
             ->update(
               ['Student_Id' => $Student_Id,'Grade_Letter_Id' => $Grade_Letter_Id,'Sks' => $Sks, 'Weight_Value' => $acd_grade_department->Weight_Value, 'Is_For_Transkrip' => $Is_For_Transkrip, 'Bnk_Value' => $Bnk_Value ]);

               // $khs_Id = DB::getPdo()->lastInsertId();
               $saldo =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($Khs_Id,''));
               // DB::table('acd_transcript')
               // ->where('Student_Id',$Student_Id)->where('Course_Id', $Course_Id)
               // ->update(
               //   ['Weight_Value' => $acd_grade_department->Weight_Value,'is_Use' => 1,'Student_Id' => $Student_Id,'Sks' => $Sks,'Course_Id' => $Course_Id,'Grade_Letter_Id' =>$Grade_Letter_Id, 'Bnk_Value' => $Bnk_Value]);
                 DB::table('acd_thesis')
                 ->where('Student_Id',$Student_Id)->where('Course_Id', $Course_Id)
                 ->update(['Thesis_Exam_Score' => $Grade_Letter_Id]);

                 return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);;
           } catch (\Exception $e) {
             return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
           }

         }

       }
     }

    }


    public function export(Request $request, $nim)
    {
      $ipsemester = Input::get('IP');
      $term_year = Input::get('term_year');
      
      $term = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->first();

      $data_std = DB::table('acd_student as a')
      ->select('a.Student_Id','a.Nim','a.Full_Name','a.Department_Id','a.Class_Prog_Id','a.Photo','b.Department_Name','c.Acronym','a.Entry_Year_Id','d.Class_Program_Name')
      ->join('mstr_department as b','a.Department_Id','=','b.Department_Id')
      ->join('mstr_education_program_type as c','b.Education_Prog_Type_Id','=','c.Education_Prog_Type_Id')
      ->join('mstr_class_program as d','a.Class_Prog_Id','=','d.Class_Prog_Id')
      ->where('a.Nim',$nim)
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
        // $sksmax = $k->v_AllowedSKS;
        $sksmax = $k->AllowedSKS;
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
       ->select('acd_student_krs.Krs_Id as Krs','acd_student.*','acd_student_khs.*','acd_grade_letter.Grade_Letter','acd_course.*','mstr_study_level.Level_Name','acd_student_khs.Weight_Value as weightvalue',
       DB::raw('(SELECT Is_For_Transcript FROM acd_course_curriculum WHERE Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
       AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_student_krs.Term_Year_Id AND Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Is_For_Transcript' ),
       DB::raw('(SELECT Transcript_Sks FROM acd_course_curriculum WHERE Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id
       AND Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = acd_student_krs.Term_Year_Id AND Department_Id = acd_student.Department_Id AND Class_Prog_Id = acd_student_krs.Class_Prog_Id AND Course_Id = acd_student_krs.Course_Id AND Entry_Year_Id = acd_student.Entry_Year_Id)) as Transcript_Sks' )
       )
       ->groupBy('acd_student_krs.Krs_Id')
       ->get();

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
        $term_year_use = Input::get('term_year');
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
        // ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',20201]])
        ->where([['efp.Functional_Position_Code','D'],['func.Term_Year_Id',$term_year_use]])
        ->first();
      if($functional_dekan){
        $functional_name_dekan = ($functional_dekan->First_Title == null ? '':$functional_dekan->First_Title.', ').$functional_dekan->Name.($functional_dekan->Last_Title == null ? '':', '.$functional_dekan->Last_Title);
        $functional_jenis_dekan = $functional_dekan->Functional_Position_Name;
      }
      $functional_name_kp = '';
      $functional_jenis_kp = '';
      $functional_kp = DB::table('acd_functional_position_term_year as func')
        ->join('emp_functional_position as efp','func.Functional_Position_Id','=','efp.Functional_Position_Id')
        ->join('emp_employee as ee','func.Employee_Id','=','ee.Employee_Id')
        // ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',20201]])
        ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',$term_year_use]])
        ->first();
      if($functional_kp){
        $functional_name_kp = ($functional_kp->First_Title == null ? '':$functional_kp->First_Title.', ').$functional_kp->Name.($functional_kp->Last_Title == null ? '':', '.$functional_kp->Last_Title);
        $functional_jenis_kp = $functional_kp->Functional_Position_Name;
      }
      
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
        ['agd.Department_Id',$data_std->Department_Id],
        ['agd.Term_Year_Id',$term_year_use]
      ])
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

      $print['Year'] = $term->Year_Id .' / '. (($term->Year_Id)+1);
      $print['Smt'] = ($term->Term_Id == 1 ? 'Ganjil':'Genap');
      $print['Full_Name'] = ucwords(strtolower($data_std->Full_Name));
      $print['Nim'] = $data_std->Nim;
      $print['class_prog'] = $data_std->Class_Program_Name;
      $print['Photo'] = env('APP_URL').$data_std->Photo;
      $print['Prodi'] = $data_std->Acronym.' - '.$data_std->Department_Name;
      $print['Dpa'] = $supervision->First_Title.' '.$supervision->Name.($supervision->Last_Title == null? '':', ').$supervision->Last_Title;
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
      $print['Grade_Department'] = $acd_grade_department;
      // dd($print);

      View()->share(['print'=>$print,'sks_kumulatif' => $sks_kumulatif , 'ipk' => $ipk , 'sksmax' => $sksmax]);
      $pdf = PDF::loadView('khs_mahasiswa/export');
      return $pdf->stream('matakuliah.pdf');
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
