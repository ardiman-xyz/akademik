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

class Cetak_transcriptsementaraController extends Controller
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

    if ($FacultyId == '') {
      $student = DB::table('acd_student')
        ->where('Nim', $nim_)
        ->first();
    } else {
      $student = DB::table('acd_student')
        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
        ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('Nim', $nim_)
        ->first();
    }

    $departement = DB::table('acd_student')
      ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
      ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
      ->select('mstr_faculty.Faculty_Name')
      ->where('Nim', $nim_)
      ->first();

    if ($FacultyId == '') {
      $data = DB::table('acd_transcript')
        ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_transcript.Course_Id')
        ->join('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id', '=', 'acd_transcript.Grade_Letter_Id')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
        ->where('acd_student.Nim', $nim_)
        ->select('acd_student.Full_Name', 'acd_transcript.*', 'acd_grade_letter.Grade_Letter', 'acd_course.*', DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
        ->get();
    } else {
      $data = DB::table('acd_transcript')
        ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_transcript.Course_Id')
        ->join('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id', '=', 'acd_transcript.Grade_Letter_Id')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
        ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('acd_student.Nim', $nim_)
        ->select('acd_student.Full_Name', 'acd_transcript.*', 'acd_grade_letter.Grade_Letter', 'acd_course.*', DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
        ->get();
    }

    if ($FacultyId == '') {
      $query = DB::table('acd_transcript')
        ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'), DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
        ->where('acd_student.Nim', $nim_)
        ->first();
    } else {
      $query = DB::table('acd_transcript')
        ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'), DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
        ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('acd_student.Nim', $nim_)
        ->first();
    }
    $jumlahdata = DB::table('acd_transcript')
      ->select(DB::raw('count(acd_transcript.Transcript_Id) as jmldata'))
      ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
      ->where('acd_student.Nim', $nim_)
      ->first();

    return view('cetak/index_transcriptsementara')
      ->with('jmldata', $jumlahdata)
      ->with('student', $student)
      ->with('query_', $query)
      ->with('query', $data)
      ->with('nim', $nim_);
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
    $student = DB::table('acd_student')
      ->where('Nim', $id)
      ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
      ->join('mstr_education_program_type', 'mstr_education_program_type.Education_Prog_Type_Id', '=', 'mstr_department.Education_Prog_Type_Id')
      ->select('mstr_department.Faculty_Id', 'mstr_department.Department_Name', 'acd_student.*', 'mstr_education_program_type.*')
      ->first();
    $faculty = DB::table('acd_student')
      ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
      ->join('mstr_education_program_type', 'mstr_education_program_type.Education_Prog_Type_Id', '=', 'mstr_department.Education_Prog_Type_Id')
      ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
      ->select('mstr_faculty.Faculty_Name', 'mstr_department.Department_Name', 'mstr_education_program_type.Acronym')
      ->where('Nim', $id)
      ->first();
    $query = DB::table('acd_transcript')
      ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'), DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
      ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
      ->where('acd_student.Nim', $id)
      ->first();

    $data = DB::table('acd_transcript')
      ->select('acd_student.Full_Name', 'acd_transcript.*', 'acd_grade_letter.Grade_Letter', 'acd_course.*', DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
      ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_transcript.Course_Id')
      ->join('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id', '=', 'acd_transcript.Grade_Letter_Id')
      ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
      ->where('acd_student.Nim', $id)
      ->orderBy('acd_course.Course_Code')
      ->get();

    $education_prog = DB::table('acd_transcript')
      ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
      ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_transcript.Course_Id')
      ->leftjoin('mstr_department', 'mstr_department.Department_Id', '=', 'acd_course.Department_Id')
      ->where('acd_student.Nim', $id)
      ->groupBy('Education_Prog_Type_Id')
      ->select('mstr_department.Education_Prog_Type_Id')
      ->first();

    $date = date('Y-m-d H:i:s');
    $term_yearcount = DB::table('mstr_term_year')
      ->where('Start_Date', '<=', $date)
      ->where('End_Date', '>=', $date)
      ->select('Start_Date', 'End_Date')
      ->count();

    if ($term_yearcount == 0) {
      $term_year1 = '';
    } else {
      $term_year1 = DB::table('mstr_term_year')
        ->where('Start_Date', '<=', $date)
        ->where('End_Date', '>=', $date)
        ->select('Term_Year_Id')
        ->first();
      $term_year1 = $term_year1->Term_Year_Id;
    }

    $kabaak = DB::table('acd_functional_position_term_year as a')
      ->join('emp_employee as b', 'a.Employee_Id', '=', 'b.Employee_Id')
      ->where([['a.Term_year_Id', $term_year1], ['a.Functional_Position_Id', 7]])
      ->first();

    $namadekan = '';
    $ttd = '';
    switch ($education_prog->Education_Prog_Type_Id) {
      case 2:
        //$namadekan="Dekan Nya";
        // $namadekan=DB::table('acd_functional_position_term_year')
        // ->join('emp_employee','emp_employee.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
        // ->join('emp_functional_position','acd_functional_position_term_year.Functional_Position_Id','=','emp_functional_position.Functional_Position_Id')
        // ->where('acd_functional_position_term_year.Faculty_Id',$education_prog->Faculty_Id)
        // ->where('emp_functional_position.Functional_Position_Code','D')
        // ->select('emp_employee.Full_Name')
        $ttd = 'Dekan';
        try {
          $countnamadekan = DB::table('emp_employee')
            ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->leftjoin('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
            ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
            ->where('emp_functional_position.Functional_Position_Code', 'D')
            ->where('acd_functional_position_term_year.Term_Year_Id', $term_year1)
            ->groupBy('emp_employee.Employee_Id')
            ->count();

          $namadekan = DB::table('emp_employee')
            ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->leftjoin('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
            ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
            ->where('emp_functional_position.Functional_Position_Code', 'D')
            ->where('acd_functional_position_term_year.Term_Year_Id', $term_year1)
            ->groupBy('emp_employee.Employee_Id')
            ->select('emp_employee.Full_Name')
            ->first();

          if ($countnamadekan == 0) {
            $namadekan = '';
          } else {
            $namadekan = $namadekan->Full_Name;
          }
        } catch (EXCEPTION $e) {
          $namadekan = '';
        }
        break;

      case 3:
        $ttd = 'Ketua Program Studi';
        try {
          $countnamadekan = DB::table('emp_employee')
            ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->join('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
            ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
            ->where('emp_functional_position.Functional_Position_Code', 'KP')
            ->where('acd_functional_position_term_year.Term_Year_Id', $term_year1)
            ->groupBy('emp_employee.Employee_Id')
            ->count();

          $namadekan = DB::table('emp_employee')
            ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->join('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
            ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
            ->where('emp_functional_position.Functional_Position_Code', 'KP')
            ->where('acd_functional_position_term_year.Term_Year_Id', $term_year1)
            ->groupBy('emp_employee.Employee_Id')
            ->select('emp_employee.Full_Name')
            ->first();

          if ($countnamadekan == 0) {
            $namadekan = '';
          } else {
            $namadekan = $namadekan->Full_Name;
          }
        } catch (EXCEPTION $e) {
          $namadekan = '';
        }
        break;

      default:
        $ttd = 'Kepala Program Studi';
        try {
          $countnamadekan = DB::table('emp_employee')
            ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->join('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
            ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
            ->where('emp_functional_position.Functional_Position_Code', 'KP')
            ->where('acd_functional_position_term_year.Term_Year_Id', $term_year1)
            ->groupBy('emp_employee.Employee_Id')
            ->count();

          $namadekan = DB::table('emp_employee')
            ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->join('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
            ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
            ->where('emp_functional_position.Functional_Position_Code', 'KP')
            ->where('acd_functional_position_term_year.Term_Year_Id', $term_year1)
            ->groupBy('emp_employee.Employee_Id')
            ->select('emp_employee.Full_Name')
            ->first();

          if ($countnamadekan == 0) {
            $namadekan = '';
          } else {
            $namadekan = $namadekan->Full_Name;
          }
        } catch (EXCEPTION $e) {
          $namadekan = '';
        }
        break;
    }

    $dosen = DB::table('emp_employee')
      ->join('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Employee_Id', '=', 'emp_employee.Employee_Id')
      ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_lecturer.Offered_Course_id')
      ->where('acd_offered_course.Offered_Course_id', $id)
      ->orderBy('acd_offered_course_lecturer.Order_Id', 'asc')
      ->get();

    $struktural_kaprodi = ApiStrukturalController::new_struktural('Kaprodi', $student->Faculty_Id, $student->Department_Id);
    $functional_name_kp = $struktural_kaprodi ? $struktural_kaprodi[0]->Full_Name : '';
    $functional_jenis_kp = $struktural_kaprodi ? $struktural_kaprodi[0]->Structural_Name : '';
    $functional_nidn_kp = $struktural_kaprodi ? $struktural_kaprodi[0]->Nidn : '';

    $predikat = $this->predikatIpk($education_prog->Education_Prog_Type_Id, $query->ipk);

    View()->share([
      'functional_name_kp' => $functional_name_kp,
      'functional_jenis_kp' => $functional_jenis_kp,
      'functional_nidn_kp' => $functional_nidn_kp,
      'predikat' => $predikat,
      'faculty' => $faculty,
      'ttd' => $ttd,
      'dekan' => $namadekan,
      'dosen' => $dosen,
      'query_' => $query,
      'data' => $data,
      'nim' => $nim,
      'student' => $student,
      'kabaak' => $kabaak
    ]);
    if ($type == 'transkripsementara') {
      $pdf = PDF::loadView('cetak/export_transcriptsementara');
      return $pdf->stream('Transkrip_sementara.pdf');
    }
    // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);
  }

  public function predikatIpk($programStudi, $ipk)
  {
    if ($programStudi == 1 || $programStudi == 2) {
      if ($ipk < 3.0) {
        return 'MEMUASKAN';
      } elseif ($ipk >= 3.01 && $ipk <= 3.5) {
        return 'SANGAT MEMUASKAN';
      } elseif ($ipk >= 3.51 && $ipk <= 4.0) {
        return 'PUJIAN';
      }
    } else {
      if ($ipk < 3.0) {
        return 'MEMUASKAN';
      } elseif ($ipk >= 3.01 && $ipk <= 3.75) {
        return 'SANGAT MEMUASKAN';
      } elseif ($ipk >= 3.76 && $ipk <= 4.0) {
        return 'PUJIAN';
      }
    }
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
