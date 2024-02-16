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
use Auth;

class Prerequisite_detailController  extends Controller
{
  // public function __construct()
  // {
  //   $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
  //   $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
  //   $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
  //   $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);
  //
  // }
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
    $FacultyId = Auth::user()->Faculty_Id;
    $semester = Input::get('semester');
    $cekpage = Input::get('cekpage');


    if ($rowpage == null || $rowpage <= 0) {
      $rowpage = 10;
    }
    $department = Input::get('department');
    $class_program = Input::get('class_program');
    $curriculum = Input::get('curriculum');
    $course_id = Input::get('course_id');

    $departmentpra = DB::table('mstr_department')->where('Department_Id', $department)->first();
    $curprasyarat = DB::table('mstr_curriculum')->where('Curriculum_Id', $curriculum)->first();
    $coursepra = DB::table('acd_course')->where('Course_Id', $course_id)->first();

    $prerequisite_detail = DB::table('acd_prerequisite_detail')
    ->leftjoin('acd_course','acd_course.Course_Id','=','acd_prerequisite_detail.Course_Id')
    ->leftjoin('acd_course_curriculum','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
    ->leftjoin('acd_prerequisite','acd_prerequisite.Prerequisite_Id','=','acd_prerequisite_detail.Prerequisite_Id')
    ->leftjoin('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_prerequisite_detail.Grade_Letter_Id')
    ->join('mstr_prerequisite_type','mstr_prerequisite_type.Prerequisite_Type_Id','=','acd_prerequisite_detail.Prerequisite_Type_Id')
    ->where('acd_prerequisite.Course_Id', $course_id)
    ->where('acd_prerequisite.Class_Prog_Id',$class_program)
    ->where('acd_prerequisite.Curriculum_Id',$curriculum)
    ->select('acd_prerequisite_detail.*','mstr_prerequisite_type.Prerequisite_Type_Name','acd_course.Course_Name','acd_course.Course_Code','acd_grade_letter.Grade_Letter')
    ->groupby('acd_prerequisite_detail.Prerequisite_Detail_Id')->paginate($rowpage);


    $prerequisite_detail->appends(['search'=> $search, 'rowpage'=> $rowpage,'curriculum'=> $curriculum, 'department'=> $department]);
    return view('acd_prerequisite_detail/index')->with('semester',$semester)->with('course_id', $course_id)->with('prerequisite_detail', $prerequisite_detail)->with('coursepra', $coursepra)->with('curprasyarat', $curprasyarat)->with('departmentpra', $departmentpra)->with('search',$search)->with('rowpage',$rowpage)->with('class_program', $class_program)->with('department', $department)->with('curriculum', $curriculum)->with('cekpage', $cekpage);
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
    $search = Input::get('search');
    $rowpage = Input::get('rowpage');
    $FacultyId = Auth::user()->Faculty_Id;
    $semester = Input::get('semester');
    // $course_id_awal = input::get('course_id');

    if ($rowpage == null) {
      $rowpage = 10;
    }

    $current_search = Input::get('current_search');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $department = Input::get('department');
    $class_program = Input::get('class_program');
    $curriculum = Input::get('curriculum');
    $course_id = Input::get('course_id');

    $jenis_prasyarat = DB::table('mstr_prerequisite_type')->get();
    $grade_department = DB::table('acd_grade_letter')->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')->where('acd_grade_department.Department_Id',$department)->get();
    $cour = DB::table('acd_prerequisite_detail')
    ->join('acd_prerequisite','acd_prerequisite_detail.Prerequisite_Id','=','acd_prerequisite.Prerequisite_Id')
    ->where('acd_prerequisite.Course_Id',$course_id)
    ->select('acd_prerequisite_detail.Course_Id')
    ->get();

    // dd($cour);

    $array = [];
    $i=0;
    foreach ($cour as $item_cour) {
      $array[$i] = $item_cour->Course_Id;
      $i++;
    }

    $course = DB::table('acd_course_curriculum')
    ->join('acd_course','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
    ->where([
    ['acd_course_curriculum.Department_Id',$department],
    ['acd_course_curriculum.Class_Prog_Id',$class_program],
    ['acd_course_curriculum.Curriculum_Id',$curriculum],
    ['acd_course_curriculum.Course_Id','!=',$course_id]
    ])
    ->whereNotIn('acd_course_curriculum.Course_Id',$array)
    ->select('acd_course_curriculum.Course_Id','acd_course.Course_Name')
    ->get();

    $id_mk = [];
    $i = 0;
    foreach ($course as $item) {
      $id_mk[$i] = $item->Course_Id;
      $i++;
    }
    $Course_Id = db::table('acd_course')->whereIn('Course_Id', $id_mk)->get();
    // dd($course);

    //    $arraymat = [];
    //    $y=0;
    //    foreach ($course as $mat_kul) {
    //      $arraymat[$y] = $mat_kul->Course_Id;
    //      $y++;
    //    }
    //
    // $mat_kul = DB::table('acd_course')->where('Course_Id', $arraymat)->get();
    // dd($mat_kul);
    return view('acd_prerequisite_detail/create')->with('semester',$semester)->with('course_id', $course_id)->with('Course_Id', $Course_Id)->with('grade_department', $grade_department)->with('jenis_prasyarat', $jenis_prasyarat)->with('course', $course)->with('class_program', $class_program)->with('department', $department)->with('curriculum', $curriculum)->with('search',$search)->with('rowpage', $rowpage)->with('current_page', $current_page)->with('current_rowpage', $current_rowpage)->with('current_search', $current_search);
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    // $this->validate($request,[
    //   'Class_Prog_Id'=>'required',
    //   'Department_Id' => 'required',
    //   'Curriculum_Id' => 'required',
    // ]);
    $jp = Input::get('jenis_prasyarat');
    $matakuliah = Input::get('matakuliah');
    $nilai_min = Input::get('nilai_min');
    $nilai_min_null= Input::get('nilai_min_null');
    $value_semester_min = Input::get('value_semester_min');
    $sks_min = input::get('sks_min');
    $sks_d = input::get('sks_d');
    $ipk_min = input::get('ipk_min');
    $department = input::get('department');
    $curriculum = Input::get('curriculum');
    $Course_Id = Input::get('Course_Id');
    $class_program = Input::get('class_program');
    $semester = Input::get('semester');

    $count = DB::table('acd_prerequisite')
    ->where('Course_Id', $Course_Id)
    ->where('Department_Id', $department)
    ->where('Curriculum_Id', $curriculum)
    ->where('Class_Prog_Id', $class_program)
    ->count();


    try{
      if($count==0){
        DB::table('acd_prerequisite')
        ->insert(['Department_Id'=>$department,'Curriculum_Id'=>$curriculum,'Course_Id'=>$Course_Id,'Class_Prog_Id'=>$class_program]);
        $pre_req_last = DB::getPdo()->lastInsertId();

        if($jp== 1 || $jp == 2 || $jp == 3 ){
          foreach ($matakuliah as $data) {
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last,'Prerequisite_Type_Id' => $jp,'Course_Id' => $data,'Grade_Letter_Id' => $nilai_min]);
          }
        }else if($jp == 4){
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $value_semester_min]);
        }else if($jp==5){
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $sks_min]);
        }else if($jp == 6){
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $sks_d]);
        }else if($jp == 7){
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $ipk_min]);
        }
        else{

        }
      }else{
        $pre_req_last = DB::table('acd_prerequisite')->select('Prerequisite_Id')
                      ->where('Course_Id', $Course_Id)
                      ->where('Department_Id', $department)
                      ->where('Curriculum_Id', $curriculum)
                      ->where('Class_Prog_Id', $class_program)
                      ->first();
        // dd($pre_req_last);

        if($jp== 1 || $jp == 2 || $jp == 3 ){
          foreach ($matakuliah as $data) {
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => $data,'Grade_Letter_Id' => $nilai_min]);
          }
        }else if($jp == 4){
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $value_semester_min]);
        }else if($jp==5){
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $sks_min]);
        }else if($jp == 6){
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $sks_d]);
        }else if($jp == 7){
            DB::table('acd_prerequisite_detail')
            ->insert(
            ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $ipk_min]);
        }
        else{

        }
      }


      return Redirect::to('/parameter/prasyarat?class_program='.$class_program.'&department='.$department.'&course_id='.$Course_Id.'&curriculum='.$curriculum.'&semester='.$semester)->withErrors('Berhasil Menambah Prasyarat');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menambah Matakuliah Kurikulum');
    }
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
    $jp = Input::get('jenis_prasyarat');
    $matakuliah = Input::get('matakuliah');
    $nilai_min = Input::get('nilai_min');
    $nilai_min_null= Input::get('nilai_min_null');
    $value_semester_min = Input::get('value_semester_min');
    $sks_min = input::get('sks_min');
    $sks_d = input::get('sks_d');
    $department = input::get('department');
    $curriculum = Input::get('curriculum');
    $Course_Id = Input::get('course_id');
    $search = Input::get('search');
    $rowpage = Input::get('rowpage');
    $semester = Input::get('semester');
    if ($rowpage == null) {
      $rowpage = 10;
    }

    $current_search = Input::get('current_search');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $class_program = Input::get('class_program');

    $select_jp = db::table('mstr_prerequisite_type')->get();
    $grade_department = DB::table('acd_grade_letter')->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')->where('acd_grade_department.Department_Id',$department)->get();
    $cour = DB::table('acd_prerequisite_detail')
    ->join('acd_prerequisite','acd_prerequisite_detail.Prerequisite_Id','=','acd_prerequisite.Prerequisite_Id')
    ->where('acd_prerequisite.Course_Id',$Course_Id)
    ->select('acd_prerequisite_detail.Course_Id')
    ->get();

    // dd($cour);

    $array = [];
    $i=0;
    foreach ($cour as $item_cour) {
      $array[$i] = $item_cour->Course_Id;
      $i++;
    }

    $course = DB::table('acd_course_curriculum')
    ->join('acd_course','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
    ->where([
    ['acd_course_curriculum.Department_Id',$department],
    ['acd_course_curriculum.Class_Prog_Id',$class_program],
    ['acd_course_curriculum.Curriculum_Id',$curriculum],
    ['acd_course_curriculum.Course_Id','!=',$Course_Id]
    ])
    ->whereNotIn('acd_course_curriculum.Course_Id',$array)
    ->select('acd_course_curriculum.Course_Id','acd_course.Course_Name')
    ->get();

    $id_mk = [];
    $i = 0;
    foreach ($course as $item) {
      $id_mk[$i] = $item->Course_Id;
      $i++;
    }
    $mat_kul = db::table('acd_course')->whereIn('Course_Id', $id_mk)->get();

    $mat_kul2 = db::table('acd_course')->where('Course_Id', $Course_Id)->get();

    // dd($mat_kul);

    $query_edit = DB::table('acd_prerequisite_detail')->where('Prerequisite_Detail_Id', $id)->leftjoin('acd_course','acd_course.Course_Id','=','acd_prerequisite_detail.Course_Id')->get();
      $query_edit_count = DB::table('acd_prerequisite_detail')->where('Prerequisite_Detail_Id', $id)->whereNotNUll('Course_Id')->select('Course_Id')->count();
// dd($query_edit_count);
    return view('acd_prerequisite_detail/edit')->with('semester',$semester)->with('query_edit_count', $query_edit_count)->with('mat_kul2', $mat_kul2)->with('grade_department', $grade_department)->with('mat_kul', $mat_kul)->with('select_jp', $select_jp)->with('current_rowpage', $current_rowpage)->with('current_page', $current_page)->with('current_search', $current_search)->with('rowpage', $rowpage)->with('search', $search)->with('class_program', $class_program)->with('Course_Id', $Course_Id)->with('curriculum', $curriculum)->with('department', $department)->with('sks_d', $sks_d)->with('sks_min', $sks_min)->with('value_semester_min', $value_semester_min)->with('nilai_min_null', $nilai_min_null)->with('nilai_min', $nilai_min)->with('matakuliah', $matakuliah)->with('jp', $jp)->with('query_edit', $query_edit);
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
    $jp = Input::get('jenis_prasyarat');
    $matakuliah = Input::get('matakuliah');
    $nilai_min = Input::get('nilai_min');
    $nilai_min_null= Input::get('nilai_min_null');
    $value_semester_min = Input::get('value_semester_min');
    $sks_min = input::get('sks_min');
    $sks_d = input::get('sks_d');
    $ipk_min = input::get('ipk_mins');
    $department = input::get('department');
    $curriculum = Input::get('curriculum');
    $Course_Id = Input::get('Course_Id');
    $class_program = Input::get('class_program');
    $current_search = Input::get('current_search');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $mk=null;
    $val=null;


    try{
      $pre_req_last = DB::table('acd_prerequisite')->select('Prerequisite_Id')->where('Course_Id', $Course_Id)->first();

      if($jp== 1 || $jp == 2 || $jp == 3 ){

          DB::table('acd_prerequisite_detail')
          ->where('Prerequisite_Detail_Id', $id)
          ->update(
          ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => $matakuliah,'Grade_Letter_Id' => $nilai_min,'Value'=>$val]);

      }else if($jp == 4){

          DB::table('acd_prerequisite_detail')
          ->where('Prerequisite_Detail_Id', $id)
          ->update(
          ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $value_semester_min,'Grade_Letter_Id' => ""]);

      }else if($jp==5){

          DB::table('acd_prerequisite_detail')
          ->where('Prerequisite_Detail_Id', $id)
          ->update(
          ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $sks_min,'Grade_Letter_Id' => ""]);

      }else if($jp == 6){

          DB::table('acd_prerequisite_detail')
          ->where('Prerequisite_Detail_Id', $id)
          ->update(
          ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $sks_d,'Grade_Letter_Id' => ""]);

      }else if($jp == 7){

          DB::table('acd_prerequisite_detail')
          ->where('Prerequisite_Detail_Id', $id)
          ->update(
          ['Prerequisite_Id' => $pre_req_last->Prerequisite_Id,'Prerequisite_Type_Id' => $jp,'Course_Id' => "",'Value' => $ipk_min,'Grade_Letter_Id' => ""]);

      }
      else{

      }
      return redirect()->to('parameter/prasyarat?class_program='.$class_program.'&department='.$department.'&course_id='.$Course_Id.'&curriculum='.$curriculum.'&page='.$current_page.'&rowpage='.$current_rowpage.'&search='.$current_search);

     return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
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
    $q=DB::table('acd_prerequisite_detail')->where('Prerequisite_Detail_Id', $id)->delete();
    echo json_encode($q);
  }
}
