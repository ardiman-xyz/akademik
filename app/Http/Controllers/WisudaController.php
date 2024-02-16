<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Analytics;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;
use App\GetDepartment;

class WisudaController extends Controller
{

  public function __construct()
  {
       $this->middleware('access:CanView', ['only' => ['index','show']]);
       $this->middleware('access:CanAdd', ['only' => ['create','store']]);
       $this->middleware('access:CanEdit', ['only' => ['edit','update']]);
       $this->middleware('access:CanDelete', ['only' => ['destroy']]);
  }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $search = Input::get('search');
      $department = Input::get('department');
      $periode = Input::get('periode');
      $tampilan = input::get('tampilan');
      $rowpage = Input::get('rowpage');
      if ($rowpage == null || $rowpage <= 0) {
        $rowpage = 10;
      }
      $FacultyId = Auth::user()->Faculty_Id;
      $DepartmentId = Auth::user()->Department_Id;
      $select_department = GetDepartment::getDepartment();

      $select_periode = DB::table('acd_graduation_period')
      ->orderBy('acd_graduation_period.Term_Year_Id', 'desc')
      ->get();


      // $ripk = "";
      // $rmasastudi = "";
      // $rusia = "";

      if ($tampilan == "") {
        $query = DB::table('acd_graduation_reg')
        ->join('acd_student','acd_student.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')
        ->leftjoin('acd_graduation_reg_temp','acd_graduation_reg_temp.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('mstr_register_status','acd_graduation_reg.Register_Status_Id','=','mstr_register_status.Register_Status_Id')
        ->leftjoin('acd_yudisium','acd_graduation_reg.Student_Id','=','acd_yudisium.Student_Id')
        ->leftjoin('mstr_graduate_predicate','mstr_graduate_predicate.Graduate_Predicate_Id','=','acd_yudisium.Graduate_Predicate_Id')
        ->leftjoin('acd_graduation_final','acd_graduation_final.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('mstr_term','mstr_term.Term_Id','=','acd_student.Entry_Term_Id')
        ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
        ->where('acd_student.Department_Id', $department)
        // ->select('acd_student.*','acd_graduation_reg.*')
        ->get();
        if ($search != null) {
          $query = DB::table('acd_graduation_reg')
          ->join('acd_student','acd_student.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')
          ->leftjoin('acd_graduation_reg_temp','acd_graduation_reg_temp.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('mstr_register_status','acd_graduation_reg.Register_Status_Id','=','mstr_register_status.Register_Status_Id')
          ->leftjoin('acd_yudisium','acd_graduation_reg.Student_Id','=','acd_yudisium.Student_Id')
          ->leftjoin('mstr_graduate_predicate','mstr_graduate_predicate.Graduate_Predicate_Id','=','acd_yudisium.Graduate_Predicate_Id')
          ->leftjoin('acd_graduation_final','acd_graduation_final.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('mstr_term','mstr_term.Term_Id','=','acd_student.Entry_Term_Id')
          ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
          ->where('acd_student.Department_Id', $department)
          ->where('acd_student.Nim', 'LIKE', '%'.$search.'%')
          ->orwhere('acd_student.Full_Name', 'LIKE', '%'.$search.'%')
          ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
          ->where('acd_student.Department_Id', $department)
          ->get();
        }
      }elseif ($tampilan == "resume") {
        $query = DB::table('acd_graduation_reg')
        ->join('acd_student','acd_student.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('acd_graduation_reg_temp','acd_graduation_reg_temp.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('mstr_register_status','acd_graduation_reg.Register_Status_Id','=','mstr_register_status.Register_Status_Id')
        ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
        ->leftjoin('acd_graduation_period','acd_graduation_reg.Graduation_Periode_Id','=','acd_graduation_period.Graduation_Period_Id')
        ->leftjoin('acd_yudisium','acd_graduation_reg.Student_Id','=','acd_yudisium.Student_Id')
        ->leftjoin('mstr_graduate_predicate','mstr_graduate_predicate.Graduate_Predicate_Id','=','acd_yudisium.Graduate_Predicate_Id')
        ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
        ->where('acd_student.Department_Id', $department)
        ->get();
        if ($search != null) {
          $query = DB::table('acd_graduation_reg')
          ->join('acd_student','acd_student.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('acd_graduation_reg_temp','acd_graduation_reg_temp.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('mstr_register_status','acd_graduation_reg.Register_Status_Id','=','mstr_register_status.Register_Status_Id')
          ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
          ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
          ->leftjoin('acd_graduation_period','acd_graduation_reg.Graduation_Periode_Id','=','acd_graduation_period.Graduation_Period_Id')
          ->leftjoin('acd_yudisium','acd_graduation_reg.Student_Id','=','acd_yudisium.Student_Id')
          ->leftjoin('mstr_graduate_predicate','mstr_graduate_predicate.Graduate_Predicate_Id','=','acd_yudisium.Graduate_Predicate_Id')
          ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
          ->where('acd_student.Department_Id', $department)
          ->where('acd_student.Nim', 'LIKE', '%'.$search.'%')
          ->orwhere('acd_student.Full_Name', 'LIKE', '%'.$search.'%')
          ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
          ->where('acd_student.Department_Id', $department)
          ->get();
        }

        // $ripk = "";
        // $rmasastudi = "";
        // $rusia = "";


      }elseif ($tampilan == "lengkap") {
        $query = DB::table('acd_graduation_reg')
        ->join('acd_student','acd_student.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')
        ->leftjoin('acd_graduation_reg_temp','acd_graduation_reg_temp.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('mstr_register_status','acd_graduation_reg.Register_Status_Id','=','mstr_register_status.Register_Status_Id')
        ->leftjoin('acd_yudisium','acd_graduation_reg.Student_Id','=','acd_yudisium.Student_Id')
        ->leftjoin('mstr_graduate_predicate','mstr_graduate_predicate.Graduate_Predicate_Id','=','acd_yudisium.Graduate_Predicate_Id')
        ->leftjoin('acd_graduation_final','acd_graduation_final.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('mstr_term','mstr_term.Term_Id','=','acd_student.Entry_Term_Id')
        ->leftjoin('acd_thesis','acd_thesis.Student_Id','=','acd_graduation_reg.Student_Id')
        ->leftjoin('emp_employee as employee1','employee1.Employee_Id','=','acd_thesis.Supervisor_1')
        ->leftjoin('emp_employee as employee2','employee2.Employee_Id','=','acd_thesis.Supervisor_2')
        ->leftjoin('emp_employee as employee3','employee3.Employee_Id','=','acd_thesis.Examiner_1')
        ->leftjoin('emp_employee as employee4','employee4.Employee_Id','=','acd_thesis.Examiner_2')
        ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
        ->where('acd_student.Department_Id', $department)
        ->select('acd_graduation_reg.*','acd_student.*','mstr_gender.*','acd_graduation_reg_temp.*','mstr_register_status.*',
                 'acd_yudisium.*','mstr_graduate_predicate.*','acd_graduation_final.*','mstr_term.*','acd_thesis.*','employee1.Full_Name as DosenPemb1','employee2.Full_Name as DosenPemb2','employee3.Full_Name as DosenPenguji1','employee4.Full_Name as DosenPenguji2')
        ->get();
        if ($search != null) {
          $query = DB::table('acd_graduation_reg')
          ->join('acd_student','acd_student.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')
          ->leftjoin('acd_graduation_reg_temp','acd_graduation_reg_temp.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('mstr_register_status','acd_graduation_reg.Register_Status_Id','=','mstr_register_status.Register_Status_Id')
          ->leftjoin('acd_yudisium','acd_graduation_reg.Student_Id','=','acd_yudisium.Student_Id')
          ->leftjoin('mstr_graduate_predicate','mstr_graduate_predicate.Graduate_Predicate_Id','=','acd_yudisium.Graduate_Predicate_Id')
          ->leftjoin('acd_graduation_final','acd_graduation_final.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('mstr_term','mstr_term.Term_Id','=','acd_student.Entry_Term_Id')
          ->leftjoin('acd_thesis','acd_thesis.Student_Id','=','acd_graduation_reg.Student_Id')
          ->leftjoin('emp_employee as employee1','employee1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->leftjoin('emp_employee as employee2','employee2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->leftjoin('emp_employee as employee3','employee3.Employee_Id','=','acd_thesis.Examiner_1')
          ->leftjoin('emp_employee as employee4','employee4.Employee_Id','=','acd_thesis.Examiner_2')
          ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
          ->where('acd_student.Department_Id', $department)
          ->where('acd_student.Nim', 'LIKE', '%'.$search.'%')
          ->orwhere('acd_student.Full_Name', 'LIKE', '%'.$search.'%')
          ->where('acd_graduation_reg.Graduation_Periode_Id', $periode)
          ->where('acd_student.Department_Id', $department)
          ->select('acd_graduation_reg.*','acd_student.*','mstr_gender.*','acd_graduation_reg_temp.*','mstr_register_status.*',
                   'acd_yudisium.*','mstr_graduate_predicate.*','acd_graduation_final.*','mstr_term.*','acd_thesis.*','employee1.Full_Name as DosenPemb1','employee2.Full_Name as DosenPemb2','employee3.Full_Name as DosenPenguji1','employee4.Full_Name as DosenPenguji2')
          ->get();
        }
      }

      $Periodes = DB::table('acd_graduation_period')->where('Graduation_Period_Id', $periode)->first();
      $Facultys = DB::table('mstr_faculty')->join('mstr_department','mstr_department.Faculty_Id','=','mstr_faculty.Faculty_Id')->where('Department_Id', $department)->first();



      return view('wisuda/index')->with('query', $query)->with('Periodes', $Periodes)->with('Facultys', $Facultys)->with('tampilan', $tampilan)->with('search', $search)->with('periode', $periode)->with('select_department', $select_department)->with('rowpage', $rowpage)->with('select_periode', $select_periode)->with('department', $department);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $currentsearch = Input::get('currentsearch');
      $department = Input::get('department');
      $periode = Input::get('periode');
      $tampilan = input::get('tampilan');
      $currentrowpage = Input::get('currentrowpage');

      $search = Input::get('search');
      $rowpage = Input::get('rowpage');
      if ($rowpage == null || $rowpage <= 0) {
        $rowpage = 10;
      }

      $grad_reg = DB::table('acd_graduation_reg')->select('Student_Id');
      $graduate_reg_temp = DB::table('acd_graduation_reg_temp')
                          ->join('acd_student','acd_student.Student_Id','=','acd_graduation_reg_temp.Student_Id')
                          // ->join('acd_graduation_reg', function($q){
                          //   $q->on('acd_graduation_reg.Student_Id' ,'=', 'acd_graduation_reg_temp.Student_Id')
                          //     ->on('acd_graduation_reg.Graduation_Periode_Id', '=', 'acd_graduation_reg_temp.Graduate_Periode_Id');
                          // })
                          ->where('acd_graduation_reg_temp.Graduate_Periode_Id', $periode)
                          ->where('acd_student.Department_Id', $department)
                          ->wherenotin('acd_student.Student_Id',$grad_reg)
                          // ->where('acd_graduation_reg.Student_Id', null)
                          ->select('acd_graduation_reg_temp.Graduation_Reg_Temp_Id','acd_graduation_reg_temp.Student_Id','acd_student.Nim','acd_graduation_reg_temp.Full_Name')
                          ->get();

      return view('wisuda/create')->with('query', $graduate_reg_temp)->with('tampilan', $tampilan)->with('search', $search)->with('periode', $periode)->with('currentsearch', $currentsearch)->with('rowpage', $rowpage)->with('currentrowpage', $currentrowpage)->with('department', $department);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $Graduate_Periode_Id = Input::get('Graduation_Periode_Id');
      $Student_Id = Input::get('Student_Id');
      $date = Date('Y-m-d h:m:s');
      try {
        foreach ($Student_Id as $value) {
          DB::table('acd_graduation_reg')
           ->insert(
              ['Graduation_Periode_Id'=>$Graduate_Periode_Id, 'Student_Id' => $value, 'Created_Date' => $date]);
        }
      return Redirect::back()->withErrors('Berhasil Menambah Data Wisuda')->with('success', true);
      } catch (\Exception $e) {
        return Redirect::back()->withErrors('Gagal Menambah Data Wisuda')->with('success', false);
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
      $currentsearch = Input::get('currentsearch');
      $department = Input::get('department');
      $periode = Input::get('periode');
      $tampilan = input::get('tampilan');
      $currentrowpage = Input::get('currentrowpage');

      $search = Input::get('search');
      $rowpage = Input::get('rowpage');
      if ($rowpage == null || $rowpage <= 0) {
        $rowpage = 10;
      }

      $grad_reg = DB::table('acd_graduation_reg')->select('Student_Id');
      $graduate_reg_temp = DB::table('acd_graduation_reg')
                          ->join('acd_student','acd_student.Student_Id','=','acd_graduation_reg.Student_Id')
                          ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
                          ->leftjoin('mstr_gender','acd_student.Gender_Id','=','mstr_gender.Gender_Id')
                          ->leftjoin('acd_graduation_reg_temp','acd_graduation_reg.Student_Id','=','acd_graduation_reg_temp.Student_Id')
                          ->leftjoin('mstr_register_status','mstr_register_status.Register_Status_Id','=','acd_graduation_reg.Register_Status_Id')
                          ->join('acd_yudisium','acd_yudisium.Student_Id','=','acd_graduation_reg.Student_Id')
                          ->join('mstr_graduate_predicate','mstr_graduate_predicate.Graduate_Predicate_Id','=','acd_yudisium.Graduate_Predicate_Id')
                          ->join('mstr_term','mstr_term.Term_Id','=','acd_student.Entry_Term_Id')
                          ->join('acd_thesis','acd_thesis.Student_Id','=','acd_graduation_reg.Student_Id')
                          ->where('acd_graduation_reg.Graduation_Reg_Id', $id)
                          ->select('acd_graduation_reg.*','acd_student.Student_Id','acd_student.Nim','mstr_entry_year.Entry_Year_Code','mstr_term.Term_Name','acd_student.Full_Name','acd_student.Birth_Place','acd_student.Birth_Date','mstr_gender.Gender_Type','acd_thesis.Thesis_Exam_Date','acd_yudisium.Yudisium_Date','acd_graduation_reg_temp.Gpa','acd_yudisium.Study_Smt_Off_Length','acd_graduation_reg_temp.Thesis_Title','acd_graduation_reg_temp.Address_0','acd_graduation_reg_temp.Phone','acd_graduation_reg_temp.Email','acd_graduation_reg_temp.Parent_Name','mstr_register_status.Register_Status_Name','acd_yudisium.Sk_Num','acd_yudisium.Transcript_Num')
                          ->first();

      return view('wisuda/edit')->with('query', $graduate_reg_temp)->with('tampilan', $tampilan)->with('search', $search)->with('periode', $periode)->with('currentsearch', $currentsearch)->with('rowpage', $rowpage)->with('currentrowpage', $currentrowpage)->with('department', $department);
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
      $Full_Name = Input::get('Full_Name');
      $Birth_Place = Input::get('Birth_Place');
      $Address_0 = Input::get('Address_0');
      $Phone = Input::get('Phone');
      $Email = Input::get('Email');
      $Parent_Name = Input::get('Parent_Name');


        $wisuda =  DB::table('acd_graduation_reg')->where('Graduation_Reg_Id', $id)->first();

        $a = DB::table('acd_student')
        ->where('Student_Id',$wisuda->Student_Id)
        ->update(
          ['Full_Name' => $Full_Name,'Birth_Place' => $Birth_Place]);

        $b = DB::table('acd_graduation_reg_temp')
        ->where('Student_Id',$wisuda->Student_Id)
        ->update(
          ['Address_0' => $Address_0,'Phone' => $Phone,'Email' => $Email,'Parent_Name' => $Parent_Name]);

        if ($a && $b) {
          return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
        }elseif (!$a) {
          return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
        }elseif (!$b) {
          return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan')->with('success', true);
        }else {
          return Redirect::back()->withErrors('Gagal Menyimpan Perubahan')->with('success', false);
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
      $rs=DB::table('acd_graduation_reg')->where('Graduation_Reg_Id', $id)->delete();
      echo json_encode($rs);
    }
}
