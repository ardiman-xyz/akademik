<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use Auth;

class Short_TermController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
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
       $rowpage = Input::get('rowpage');
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $Event_Id = Input::get('event_id');
       $FacultyId = Auth::user()->Faculty_Id;
       $fakultas = input::get('faculty');
       $DepartmentId = Auth::user()->Department_Id;

       $select_event = DB::table('mstr_event')
       ->orderBy('mstr_event.Event_Id', 'asc')
       ->get();

       if($FacultyId==""){
        $select_fakultas = DB::table('mstr_faculty')->get();
       }else{
        $select_fakultas = DB::table('mstr_faculty')->where('Faculty_Id',$FacultyId)->get();
       }

       $acd_short_term_krs = DB::table('acd_short_term_krs')
       ->join('mstr_department','mstr_department.Department_Id','=','acd_short_term_krs.Department_Id')
       ->join('mstr_faculty','mstr_department.Faculty_Id','=','mstr_faculty.Faculty_Id')
       ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_short_term_krs.Grade_Letter_Minimum_Id')
       ->join('mstr_taking_rule','mstr_taking_rule.Taking_Rule_Id','=','acd_short_term_krs.Taking_Rule_Id')
       ->where('mstr_department.Faculty_Id', $fakultas)
       ->whereNotNUll('mstr_department.Faculty_Id')
       ->orderBy('acd_short_term_krs.Department_Id')->get();

if($DepartmentId==""){
  if ($search == null) {
    $data = DB::table('mstr_department')
      ->leftjoin('acd_short_term_krs','mstr_department.Department_Id','=','acd_short_term_krs.Department_Id')
      ->leftjoin('mstr_taking_rule','acd_short_term_krs.Taking_Rule_Id','=','mstr_taking_rule.Taking_Rule_Id')
      ->leftjoin('acd_grade_letter','acd_short_term_krs.Grade_Letter_Minimum_Id','=','acd_grade_letter.Grade_Letter_Id')
      ->where('mstr_department.Faculty_Id',$fakultas)
      ->whereNotNUll('mstr_department.Faculty_Id')
      ->select(DB::Raw('
        mstr_department.Department_Id as Department_Id,
        mstr_department.Department_Name as Department_Name,
        acd_short_term_krs.Is_Active_Student as Is_Active_Student,
        acd_short_term_krs.Is_All_Year as Is_All_Year,
        acd_short_term_krs.Sks_Limit as Sks_Limit,
        acd_short_term_krs.Course_Limit as Course_Limit,
        mstr_taking_rule.Taking_Rule_Name as Taking_Rule,
        acd_short_term_krs.Short_Term_Krs_Id as Short_Term_Krs_Id,
        acd_grade_letter.Grade_Letter as Grade_Letter
      '))
      ->OrderBy('Department_Id')
    ->paginate($rowpage);
  }else {
    $data = DB::table('mstr_department')
      ->leftjoin('acd_short_term_krs','mstr_department.Department_Id','=','acd_short_term_krs.Department_Id')
      ->leftjoin('mstr_taking_rule','acd_short_term_krs.Taking_Rule_Id','=','mstr_taking_rule.Taking_Rule_Id')
      ->leftjoin('acd_grade_letter','acd_short_term_krs.Grade_Letter_Minimum_Id','=','acd_grade_letter.Grade_Letter_Id')
      ->where('mstr_department.Faculty_Id',$fakultas)
      ->where(function($query){
           $search = Input::get('search');
           $query->whereRaw("lower(mstr_department.Department_Name) like '%" . strtolower($search) . "%'");
         })
      ->whereNotNUll('mstr_department.Faculty_Id')
      ->select(DB::Raw('
        mstr_department.Department_Id as Department_Id,
        mstr_department.Department_Name as Department_Name,
        acd_short_term_krs.Is_Active_Student as Is_Active_Student,
        acd_short_term_krs.Is_All_Year as Is_All_Year,
        acd_short_term_krs.Sks_Limit as Sks_Limit,
        acd_short_term_krs.Course_Limit as Course_Limit,
        mstr_taking_rule.Taking_Rule_Name as Taking_Rule,
        acd_short_term_krs.Short_Term_Krs_Id as Short_Term_Krs_Id,
        acd_grade_letter.Grade_Letter as Grade_Letter
      '))
      ->OrderBy('Department_Id')
    ->paginate($rowpage);
  }
}else{
  if ($search == null) {
    $data = DB::table('mstr_department')
      ->leftjoin('acd_short_term_krs','mstr_department.Department_Id','=','acd_short_term_krs.Department_Id')
      ->leftjoin('mstr_taking_rule','acd_short_term_krs.Taking_Rule_Id','=','mstr_taking_rule.Taking_Rule_Id')
      ->leftjoin('acd_grade_letter','acd_short_term_krs.Grade_Letter_Minimum_Id','=','acd_grade_letter.Grade_Letter_Id')
      ->where('mstr_department.Faculty_Id',$fakultas)
      ->where('mstr_department.Department_Id',$DepartmentId)
      ->whereNotNUll('mstr_department.Faculty_Id')
      ->select(DB::Raw('
        mstr_department.Department_Id as Department_Id,
        mstr_department.Department_Name as Department_Name,
        acd_short_term_krs.Is_Active_Student as Is_Active_Student,
        acd_short_term_krs.Is_All_Year as Is_All_Year,
        acd_short_term_krs.Sks_Limit as Sks_Limit,
        acd_short_term_krs.Course_Limit as Course_Limit,
        mstr_taking_rule.Taking_Rule_Name as Taking_Rule,
        acd_short_term_krs.Short_Term_Krs_Id as Short_Term_Krs_Id,
        acd_grade_letter.Grade_Letter as Grade_Letter
      '))
      ->OrderBy('Department_Id')
    ->paginate($rowpage);
  }else {
    $data = DB::table('mstr_department')
      ->leftjoin('acd_short_term_krs','mstr_department.Department_Id','=','acd_short_term_krs.Department_Id')
      ->leftjoin('mstr_taking_rule','acd_short_term_krs.Taking_Rule_Id','=','mstr_taking_rule.Taking_Rule_Id')
      ->leftjoin('acd_grade_letter','acd_short_term_krs.Grade_Letter_Minimum_Id','=','acd_grade_letter.Grade_Letter_Id')
      ->where('mstr_department.Faculty_Id',$fakultas)
      ->where('mstr_department.Department_Id',$DepartmentId)
      ->where(function($query){
           $search = Input::get('search');
           $query->whereRaw("lower(mstr_department.Department_Name) like '%" . strtolower($search) . "%'");
         })
      ->whereNotNUll('mstr_department.Faculty_Id')
      ->select(DB::Raw('
        mstr_department.Department_Id as Department_Id,
        mstr_department.Department_Name as Department_Name,
        acd_short_term_krs.Is_Active_Student as Is_Active_Student,
        acd_short_term_krs.Is_All_Year as Is_All_Year,
        acd_short_term_krs.Sks_Limit as Sks_Limit,
        acd_short_term_krs.Course_Limit as Course_Limit,
        mstr_taking_rule.Taking_Rule_Name as Taking_Rule,
        acd_short_term_krs.Short_Term_Krs_Id as Short_Term_Krs_Id,
        acd_grade_letter.Grade_Letter as Grade_Letter
      '))
      ->OrderBy('Department_Id')
    ->paginate($rowpage);
  }
}


       $data->appends(['event_id'=> $Event_Id,'search'=> $search, 'rowpage'=> $rowpage]);
       return view('mstr_short_term/index')->with('select_fakultas', $select_fakultas)->with('fakultas', $fakultas)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_event', $select_event)->with('event_id', $Event_Id);
     }
     // public function modal()
     // {
     //   return view('mstr_faculty/modal');
     // }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         $Event_Id = Input::get('event_id');
         $event = DB::table('mstr_event')->where('Event_Id', $Event_Id)->get();

         $search = Input::get('search');
         $page = Input::get('page');
         $rowpage = Input::get('rowpage');
         $FacultyId = Auth::user()->Faculty_Id;

if($FacultyId==""){
  $select_department = DB::table('mstr_department')->wherenotnull('Faculty_Id')->get();
}else{
  $select_department = DB::table('mstr_department')
  ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
  ->where('mstr_faculty.Faculty_Id', $FacultyId)->get();
}

         $select_term_year = DB::table('mstr_term_year')->orderBy('Term_Year_Name', 'desc')->get();

         return view('mstr_event_sched/create')->with('event', $event)->with('event_id', $Event_Id)->with('select_department',$select_department)->with('select_term_year', $select_term_year)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Event_Id'=>'required',
         'Department_Id' => 'required',
         'Term_Year_Id' => 'required',
         'Is_Open' => 'required',
         'Start_Date' => 'required',
         'End_Date' => 'required',
       ]);
             $Event_Id = Input::get('Event_Id');
             $Department_Id = Input::get('Department_Id');
             $Term_Year_Id = Input::get('Term_Year_Id');
             $Is_Open = Input::get('Is_Open');
             $Start_Date = Input::get('Start_Date');
             $End_Date = Input::get('End_Date');
       $u =  DB::table('mstr_event_sched')
       ->insert(
       ['Event_Id' => $Event_Id,'Department_Id' => $Department_Id,'Term_Year_Id' => $Term_Year_Id,'Is_Open' => $Is_Open,'Start_Date' => $Start_Date,'End_Date' => $End_Date]);
       return Redirect::back()->withErrors('Berhasil Menambah Jadwal Pengisian');
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
       $department = input::get('department');

       $fakultas = DB::table('mstr_department')->where('Department_Id',$department)->first();

       $data = DB::table('mstr_department')
         ->leftjoin('acd_short_term_krs','mstr_department.Department_Id','=','acd_short_term_krs.Department_Id')
         ->leftjoin('mstr_taking_rule','acd_short_term_krs.Taking_Rule_Id','=','mstr_taking_rule.Taking_Rule_Id')
         ->leftjoin('acd_grade_letter','acd_short_term_krs.Grade_Letter_Minimum_Id','=','acd_grade_letter.Grade_Letter_Id')
         ->where('mstr_department.Department_Id',$department)
         ->get();

         $aktif = DB::table('mstr_department')
           ->leftjoin('acd_short_term_krs','mstr_department.Department_Id','=','acd_short_term_krs.Department_Id')
           ->leftjoin('mstr_taking_rule','acd_short_term_krs.Taking_Rule_Id','=','mstr_taking_rule.Taking_Rule_Id')
           ->get();

        $taking_rule = DB::table('mstr_taking_rule')->get();

        $grade= DB::table('acd_grade_letter')->get();
         // dd($data);
       return view('mstr_short_term/edit')->with('fakultas', $fakultas)->with('grade', $grade)->with('Taking_Rule', $taking_rule)->with('query_edit',$data)->with('aktif', $aktif)->with('department',$department)->with('search',$search)->with('rowpage', $rowpage);
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
       // $this->validate($request,[
       //   'Department_Id' => 'required',
       //   'Term_Year_Id' => 'required',
       //   'Is_Open' => 'required',
       //   'Start_Date' => 'required',
       //   'End_Date' => 'required',
       // ]);
             $Department_Id = Input::get('Department_Id');
             $sksmax = Input::get('sksmax');
             $coursemax = Input::get('coursemax');
             $aturan_ambil = Input::get('aturan_ambil');
             $status_aktif = Input::get('aktif');
             $tahun_ambil = Input::get('tahun_ambil');
             $nilai_minimum = Input::get('nilai_minimum');

             try {
               if($status_aktif == null && $coursemax == null && $tahun_ambil == null){
                 $u =  DB::table('acd_short_term_krs')
                 ->where('Department_Id',$Department_Id)
                 ->update(
                 ['Is_Active_Student' => null,'Is_All_Year' => null, 'Is_Remidi'=>0,'Course_Limit' => null,'Grade_Letter_Minimum_Id' => null]);
               }else{
                 $u =  DB::table('acd_short_term_krs')
                 ->where('Department_Id',$Department_Id)
                 ->update(
                 ['Is_Active_Student' => $status_aktif,'Is_All_Year' => $tahun_ambil, 'Is_Remidi'=>1,'Course_Limit' => $coursemax,'Grade_Letter_Minimum_Id' => $nilai_minimum]);
               }
               return Redirect::back()->withErrors('Berhasil Mengubah Data Option Semester Pendek')->with('success', true);
               } catch (\Exception $e) {
                 return Redirect::back()->withErrors('Gagal Mengubah Data Option Semester Pendek')->with('success', false);
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
      $q = DB::table('mstr_event_sched')->where('Event_Sched_Id', $id)->delete();
      echo json_encode($q);
     }
 }
