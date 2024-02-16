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
use Image;
use File;
use Storage;
use App\GetDepartment;

class Cetak_ktmController extends Controller
{
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    // $tgl_ = \Carbon\Carbon::now();
    // $tgl_akhir = $tgl_->format('d-m-Y');
    // dd($tgl_akhir);
    $nimawal = Input::get('nimawal');
    $nimakhir = Input::get('nimakhir');
    $search = Input::get('search');
    $term_year = Input::get('term_year');
    $entry_year = Input::get('entry_year');
    $department = Input::get('department');
    $FacultyId = Auth::user()->Faculty_Id;
    $DepartmentId = Auth::user()->Department_Id;
    $tgl_akhir = Input::get('tgl_akhir');

    $select_entry_year = DB::table('mstr_entry_year')->orderBy('Entry_Year_Id','desc')->get();

    $select_department = GetDepartment::getDepartment();
    
      $select_nim = DB::table('acd_student')
      ->where('acd_student.department_id', $department)
      ->where('acd_student.Entry_Year_Id', $entry_year)
      ->where(function($query){
          $query->where('Status_Id','1');
          $query->orwhere('Status_Id','2');
        })
      ->orderBy('acd_student.Nim', 'asc')
      ->get();
    return view('cetak/index_ktm')->with('entry_year',$entry_year)->with('tgl_akhir',$tgl_akhir)->with('select_entry_year',$select_entry_year)->with('nimawal',$nimawal)->with('nimakhir',$nimakhir)->with('select_nim',$select_nim)->with('department',$department)->with('select_department',$select_department)->with('search',$search);
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
    $uploadfile = $request->file('file');
    if ($uploadfile != null) {
      $image = $request->file('file');
      $img = Image::make($image)->save('img/ktm.png');
    }

    if ($request->file('ttd')) {
      $filename = 'ttd'.date('YmdHis').'.png';
      $path = $request->file('ttd')->storeAs('public/ttd', $filename);
      $update = DB::table('mstr_signature')->where('Ttd_For','TTD KTM')->first();
      if($update){
        Storage::delete('public/ttd/'.$update->Value);
        DB::table('mstr_signature')->where('Ttd_Id',$update->Ttd_Id)->update(['Value'=>$filename]);
      }else{
        DB::table('mstr_signature')->insert(['Value'=>$filename,'Ttd_For'=>'TTD KTM']);
      }
    }

    return Redirect::back()->withErrors('Berhasil Menyimpan');
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
    $nimawal = Input::get('nimawal');
    $nimakhir = Input::get('nimakhir');
    $search = Input::get('search');
    $term_year = Input::get('term_year');
    $entry_year = Input::get('entry_year');
    $department = Input::get('department');
    $bg = Input::get('bg');

    // $tgl_ = \Carbon\Carbon::now();
    $tgl_akhir = Input::get('tgl_akhir');
    // $tgl_akhir = $tgl_->format('d-m-Y');

    $data = DB::table('acd_student')->where('Nim', '>=',$nimawal)->where('Nim', '<=',$nimakhir)
    ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
    ->join('mstr_faculty','mstr_department.Faculty_Id','=','mstr_faculty.Faculty_Id')
    ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
    ->leftjoin('acd_student_address','acd_student.Student_Id','=','acd_student_address.Student_Id')
    ->select('acd_student.*','mstr_department.Department_Name','mstr_education_program_type.Acronym','mstr_education_program_type.Program_Name','Faculty_Name','Address')
    ->get();

    View()->share(['bg'=>$bg,'data'=>$data,'tgl_akhir'=>$tgl_akhir]);

    $pdf = PDF::loadView('cetak/export_ktm');
    return $pdf->stream('ktm.pdf');
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
