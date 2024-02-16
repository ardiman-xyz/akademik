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
use DateTime;
use App\GetDepartment;

class Cetak_ijazahController extends Controller
{
  public function __construct()
  {
      $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $tgl_ = \Carbon\Carbon::now();
    $tgl_akhir = $tgl_->format('Y-m-d');
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
      $select_nim = DB::table('acd_yudisium')
      ->join('acd_student','acd_yudisium.Student_Id','=','acd_student.Student_Id')
       ->join('mstr_department', 'acd_student.department_id', '=', 'mstr_department.department_id')
       ->where('mstr_department.department_id', $department)
       ->where('acd_student.Entry_Year_Id', $entry_year)
       ->orderBy('acd_student.Nim','asc')
       ->get();


    return view('cetak/index_ijazah')->with('entry_year',$entry_year)->with('tgl_akhir',$tgl_akhir)->with('select_entry_year',$select_entry_year)->with('nimawal',$nimawal)->with('nimakhir',$nimakhir)->with('select_nim',$select_nim)->with('department',$department)->with('select_department',$select_department)->with('search',$search);
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
     $nimawal = Input::get('nimawal');
     $nimakhir = Input::get('nimakhir');
     $search = Input::get('search');
     $term_year = Input::get('term_year');
     $entry_year = Input::get('entry_year');
     $department = Input::get('department');
     $tgl_akhir = Input::get('tgl_akhir');
     // $tgl_akhir = "";

     $data = DB::table('acd_yudisium')
     ->join('acd_student','acd_yudisium.Student_Id','=','acd_student.Student_Id')
     ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
     ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
     ->where('Nim', '>=',$nimawal)
     ->where('Nim', '<=',$nimakhir)
     ->where('mstr_department.Department_Id',$department)
     ->select('acd_student.*','mstr_department.Department_Name','mstr_department.Department_Name_Eng','mstr_education_program_type.Acronym','acd_yudisium.Yudisium_Date','acd_yudisium.Sk_Num')
     ->get();

     $name_id_prog = DB::table('mstr_department')
     ->join('mstr_education_program_type','mstr_education_program_type.Education_Prog_Type_Id','=','mstr_department.Education_Prog_Type_Id')
     ->where('mstr_department.Department_Id',$department)->select('mstr_education_program_type.Program_Name')->first();

     $prog_type="";
     switch ($name_id_prog) {
       case 'Diploma-3':
         $prog_type="Diploma";

         break;

         case 'strata-2':
           $prog_type="Master";

           break;

       default:
       $prog_type="Sarjana";

       break;
     }

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

      $namawk1="";
      $nidnwk1="";
      $namak="";
      $nidnk="";

      $countnamawk1=DB::table('emp_employee')
      ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
      ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
      ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
      ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
      ->where('emp_functional_position.Functional_Position_Code','WK1')
      ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
      ->groupBy('emp_employee.Employee_Id')
      ->count();

      $countnamak=DB::table('emp_employee')
      ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
      ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
      ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
      ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
      ->where('emp_functional_position.Functional_Position_Code','K')
      ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
      ->groupBy('emp_employee.Employee_Id')
      ->count();

      // dd($countnamak);

      $namawk1=DB::table('emp_employee')
      ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
      ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
      ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
      ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
      ->where('emp_functional_position.Functional_Position_Code','WK1')
      ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
      ->groupBy('emp_employee.Employee_Id')
      ->select('emp_employee.Full_Name')
      ->first();

      $namak=DB::table('emp_employee')
      ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
      ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
      ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
      ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
      ->where('emp_functional_position.Functional_Position_Code','K')
      ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
      ->groupBy('emp_employee.Employee_Id')
      ->select('emp_employee.Full_Name')
      ->first();

      $nikwk1=DB::table('emp_employee')
      ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
      ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
      ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
      ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
      ->where('emp_functional_position.Functional_Position_Code','WK1')
      ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
      ->groupBy('emp_employee.Employee_Id')
      ->select('emp_employee.Nip')
      ->first();

      $nikk=DB::table('emp_employee')
      ->join('acd_functional_position_term_year' , 'acd_functional_position_term_year.Employee_Id', '=' , 'emp_employee.Employee_Id')
      ->leftjoin('emp_functional_position' , 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
      ->leftjoin('mstr_faculty' , 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
      ->leftjoin('mstr_department' , 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
      ->where('emp_functional_position.Functional_Position_Code','K')
      ->where('acd_functional_position_term_year.Term_Year_Id',$term_year1)
      ->groupBy('emp_employee.Employee_Id')
      ->select('emp_employee.Nip')
      ->first();

      if($countnamawk1 == 0){
        $namawk1="-";
        $nidnwk1 = "-";
      } else {
        if($nikwk1->Nip == null){
          $namawk1=$namawk1->Full_Name;
          $nidnwk1 = "-";
        } else {
          $namawk1=$namawk1->Full_Name;
          $nidnwk1=$nikwk1->Nip;
        }
      }

      if($countnamak == 0){
        $namak="-";
        $nidnk = "-";
      } else {
        if($nikk->Nip != null){
          $namak=$namak->Full_Name;
          $nidnk=$nikk->Nip;
        } else {
          $namak=$namak->Full_Name;
          $nidnk = "-";
        }
      }

     View()->share(['data'=>$data,'tgl_akhir'=>$tgl_akhir,'prog_type'=>$prog_type,'namawk1'=>$namawk1,'nidnwk1'=>$nidnwk1,'namak'=>$namak,'nidnk'=>$nidnk,'tgl_akhir'=>$tgl_akhir]);

       $pdf = PDF::loadView('cetak/export_ijazah');
       return $pdf->stream('ijazah.pdf');
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
