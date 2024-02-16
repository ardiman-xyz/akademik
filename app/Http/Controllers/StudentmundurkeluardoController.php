<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Input;
use DB;
use Redirect;
use Alert;
use Storage;
use Auth;
use Image;
use File;
use App\GetDepartment;

class StudentmundurkeluardoController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index', 'show']]);
    $this->middleware('access:CanAdd', ['except' => ['index', 'destroy']]);
    $this->middleware('access:CanEdit', ['except' => ['index', 'create_student', 'store', 'show', 'destroy']]);
    $this->middleware('access:CanDelete', ['except' => ['index', 'create_student', 'store', 'show', 'edit', 'update']]);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $this->validate($request, [
      'rowpage' => 'numeric|nullable',
    ]);
    $search = Input::get('search');
    $rowpage = Input::get('rowpage');
    $FacultyId = Auth::user()->Faculty_Id;
    $DepartmentId = Auth::user()->Department_Id;

    if ($rowpage == null || $rowpage <= 0) {
      $rowpage = 10;
    }
    $entry_year = Input::get('entry_year');
    $department = Input::get('department');
    $status = Input::get('status');

    $select_entry_year = DB::table('mstr_entry_year')
      ->orderBy('mstr_entry_year.Entry_Year_Id', 'desc')
      ->get();

    $select_status = DB::table('mstr_status')
      ->orderby('Status_Id')
      ->get();

    $select_department = GetDepartment::getDepartment();
    if ($request->department == 0 && $request->entry_year == 0) {
      if ($search != null) {
        $std = DB::table('acd_student')
          ->where('Nim', $search)
          ->first();
        if ($std) {
          if (!isset($request->find)) {
            return redirect()->to('setting/studentmundurkeluardo?department=' . $std->Department_Id . '&entry_year=' . $std->Entry_Year_Id . '&status=' . $std->Status_Id . '&search=' . $std->Nim . '&rowpage=10' . '&find=true');
          }
        }
      }
    } else {
      if ($search != null) {
        $std = DB::table('acd_student')
          ->where('Nim', $search)
          ->first();
        if ($std) {
          if (!isset($request->find)) {
            return redirect()->to('setting/studentmundurkeluardo?department=' . $request->department . '&entry_year=' . $request->entry_year . '&status=' . $status . '&search=' . $std->Nim . '&rowpage=10' . '&find=true');
          } else {
            // return redirect()->to('setting/studentmundurkeluardo?department='.$request->department.'&entry_year='.$request->entry_year.'&status='.$status.'&search='.$std->Nim.'&rowpage=10');
          }
        }
      }
    }

    if ($status == 5) {
      $data = DB::table('acd_student_out as a')
        ->join('acd_student as b', 'a.Student_Id', '=', 'b.Student_Id')
        ->leftjoin('mstr_gender as c', 'b.Gender_Id', '=', 'c.Gender_Id')
        ->leftjoin('mstr_status as d', 'b.Status_Id', '=', 'd.Status_Id')
        ->leftjoin('mstr_department as e', 'a.Department_Destination', '=', 'e.Department_Id')
        ->leftjoin('mstr_class_program as f', 'a.Class_Prog_Destination', '=', 'f.Class_Prog_Id')
        ->leftjoin('mstr_department as g', 'a.Department_From_Id', '=', 'g.Department_Id')
        ->leftjoin('mstr_class_program as h', 'a.Class_Prog_From_Id', '=', 'h.Class_Prog_Id')
        ->orderBy('b.Nim', 'asc');

      if ($search == null) {
        $data = $data
          ->where('b.Entry_Year_Id', $entry_year)
          ->where([['a.Department_From_Id', $department], ['b.Status_Id', $status]])
          ->orwhere([['a.Department_Destination', $department], ['b.Status_Id', $status]])
          ->select('b.Student_Id', 'b.Nim', 'b.Full_Name', 'b.Department_Id', 'a.Department_From_Id', 'a.Department_Destination', 'b.Class_Prog_Id', 'e.Department_Name as Department_Name_To', 'g.Department_Name as Department_Name_From', 'f.Class_Program_Name as Class_Program_Name_To', 'b.Birth_Date', 'd.Status_Name', 'c.Gender_Type')
          ->paginate($rowpage);
        // ->get();
        // dd($data);
      } else {
        $data = $data
          ->where('acd_student.Entry_Year_Id', $entry_year)
          ->where('acd_student.Department_Id', $department)
          ->where(function ($query) {
            $search = Input::get('search');
            $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('Nim', 'LIKE', '%' . $search . '%');
          })
          ->orderBy('acd_student.Nim', 'asc')
          ->paginate($rowpage);
      }
    } else {
      if ($search == null) {
        if ($status == null) {
          $data = DB::table('acd_student')
            ->join('mstr_entry_year', 'mstr_entry_year.Entry_Year_Id', '=', 'acd_student.Entry_Year_Id')
            // ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
            ->leftjoin('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student.Class_Prog_Id')
            ->leftjoin('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student.Class_Id')
            ->leftjoin('mstr_gender', 'mstr_gender.Gender_Id', '=', 'acd_student.Gender_Id')
            ->leftjoin('mstr_status', 'acd_student.Status_Id', '=', 'mstr_status.Status_Id')

            ->where('acd_student.Entry_Year_Id', $entry_year)
            ->where('acd_student.Department_Id', $department)
            ->orderBy('acd_student.Nim', 'asc')
            ->paginate($rowpage);
        } else {
          $data = DB::table('acd_student')
            ->join('mstr_entry_year', 'mstr_entry_year.Entry_Year_Id', '=', 'acd_student.Entry_Year_Id')
            // ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
            ->leftjoin('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student.Class_Prog_Id')
            ->leftjoin('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student.Class_Id')
            ->leftjoin('mstr_gender', 'mstr_gender.Gender_Id', '=', 'acd_student.Gender_Id')
            ->leftjoin('mstr_status', 'acd_student.Status_Id', '=', 'mstr_status.Status_Id')

            ->where('acd_student.Entry_Year_Id', $entry_year)
            ->where('acd_student.Department_Id', $department)
            ->where('acd_student.Status_Id', $status)
            ->orderBy('acd_student.Nim', 'asc')
            ->paginate($rowpage);
          // ->get();
          //   dd($data);
        }
      } else {
        if ($status == null) {
          $data = DB::table('acd_student')
            ->join('mstr_entry_year', 'mstr_entry_year.Entry_Year_Id', '=', 'acd_student.Entry_Year_Id')
            // ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
            ->leftjoin('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student.Class_Prog_Id')
            ->leftjoin('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student.Class_Id')
            ->leftjoin('mstr_gender', 'mstr_gender.Gender_Id', '=', 'acd_student.Gender_Id')
            ->leftjoin('mstr_status', 'acd_student.Status_Id', '=', 'mstr_status.Status_Id')
            ->where('acd_student.Entry_Year_Id', $entry_year)
            ->where('acd_student.Department_Id', $department)
            //->where('Full_Name', 'LIKE', '%'.$search.'%')
            ->where(function ($query) {
              $search = Input::get('search');
              $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
              $query->orwhere('Nim', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('acd_student.Nim', 'asc')
            ->paginate($rowpage);
        } else {
          $data = DB::table('acd_student')
            ->join('mstr_entry_year', 'mstr_entry_year.Entry_Year_Id', '=', 'acd_student.Entry_Year_Id')
            // ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
            ->leftjoin('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student.Class_Prog_Id')
            ->leftjoin('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student.Class_Id')
            ->leftjoin('mstr_gender', 'mstr_gender.Gender_Id', '=', 'acd_student.Gender_Id')
            ->leftjoin('mstr_status', 'acd_student.Status_Id', '=', 'mstr_status.Status_Id')
            ->where('acd_student.Entry_Year_Id', $entry_year)
            ->where('acd_student.Department_Id', $department)
            ->where('acd_student.Status_Id', $status)
            //->where('Full_Name', 'LIKE', '%'.$search.'%')
            ->where(function ($query) {
              $search = Input::get('search');
              $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
              $query->orwhere('Nim', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('acd_student.Nim', 'asc')
            ->paginate($rowpage);
        }
      }
    }

    $count_dep = '';
    if ($department != 0) {
      if ($status == null) {
        if ($status == 5) {
          $count_dep = DB::table('acd_student_out as a')
            ->join('acd_student as b', 'a.Student_Id', '=', 'b.Student_Id')
            ->leftjoin('mstr_gender as c', 'b.Gender_Id', '=', 'c.Gender_Id')
            ->leftjoin('mstr_status as d', 'b.Status_Id', '=', 'd.Status_Id')
            ->leftjoin('mstr_department as e', 'a.Department_Destination', '=', 'e.Department_Id')
            ->where('b.Entry_Year_Id', $entry_year)
            ->where('a.Department_From_Id', $department)
            ->where('b.Status_Id', $status)
            ->orderBy('b.Nim', 'asc')
            ->count();
        } else {
          $count_dep = db::table('acd_student')
            ->where('Department_Id', $department)
            ->count();
        }
      } else {
        if ($status == 5) {
          $count_dep = DB::table('acd_student_out as a')
            ->join('acd_student as b', 'a.Student_Id', '=', 'b.Student_Id')
            ->leftjoin('mstr_gender as c', 'b.Gender_Id', '=', 'c.Gender_Id')
            ->leftjoin('mstr_status as d', 'b.Status_Id', '=', 'd.Status_Id')
            ->leftjoin('mstr_department as e', 'a.Department_Destination', '=', 'e.Department_Id')
            ->where('b.Entry_Year_Id', $entry_year)
            ->where('a.Department_From_Id', $department)
            ->where('b.Status_Id', $status)
            ->orderBy('b.Nim', 'asc')
            ->count();
        } else {
          $count_dep = db::table('acd_student')
            ->where('Department_Id', $department)
            ->where('Status_Id', $status)
            ->count();
        }
      }
    }
    if ($entry_year != 0) {
      if ($status == null) {
        if ($status == 5) {
          $count_dep = DB::table('acd_student_out as a')
            ->join('acd_student as b', 'a.Student_Id', '=', 'b.Student_Id')
            ->leftjoin('mstr_gender as c', 'b.Gender_Id', '=', 'c.Gender_Id')
            ->leftjoin('mstr_status as d', 'b.Status_Id', '=', 'd.Status_Id')
            ->leftjoin('mstr_department as e', 'a.Department_Destination', '=', 'e.Department_Id')
            ->where('b.Entry_Year_Id', $entry_year)
            ->where('a.Department_From_Id', $department)
            ->where('b.Status_Id', $status)
            ->orderBy('b.Nim', 'asc')
            ->count();
        } else {
          $count_dep = db::table('acd_student')
            ->where('Department_Id', $department)
            ->where('Entry_Year_Id', $entry_year)
            ->count();
        }
      } else {
        if ($status == 5) {
          $count_dep = DB::table('acd_student_out as a')
            ->join('acd_student as b', 'a.Student_Id', '=', 'b.Student_Id')
            ->leftjoin('mstr_gender as c', 'b.Gender_Id', '=', 'c.Gender_Id')
            ->leftjoin('mstr_status as d', 'b.Status_Id', '=', 'd.Status_Id')
            ->leftjoin('mstr_department as e', 'a.Department_Destination', '=', 'e.Department_Id')
            ->where('b.Entry_Year_Id', $entry_year)
            ->where('a.Department_From_Id', $department)
            ->where('b.Status_Id', $status)
            ->orderBy('b.Nim', 'asc')
            ->count();
        } else {
          $count_dep = db::table('acd_student')
            ->where('Department_Id', $department)
            ->where('Entry_Year_Id', $entry_year)
            ->where('Status_Id', $status)
            ->count();
        }
      }
    }

    $data->appends(['search' => $search, 'rowpage' => $rowpage, 'entry_year' => $entry_year, 'department' => $department, 'status' => $status]);
    return view('acd_studentmundurkeluardo/index')
      ->with('select_status', $select_status)
      ->with('status', $status)
      ->with('count_dep', $count_dep)
      ->with('query', $data)
      ->with('search', $search)
      ->with('rowpage', $rowpage)
      ->with('select_entry_year', $select_entry_year)
      ->with('entry_year', $entry_year)
      ->with('select_department', $select_department)
      ->with('department', $department);
  }
  // public function modal()
  // {
  //   return view('mstr_entry_year/modal');
  // }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year');
    $FacultyId = Auth::user()->Faculty_Id;

    $entry_year = DB::table('mstr_entry_year')
      ->where('Entry_Year_Id', $entry_year_id)
      ->get();

    if ($FacultyId == '') {
      $Department = DB::table('mstr_department')
        ->where('Department_Id', $department_id)
        ->get();
    } else {
      $Department = DB::table('mstr_department')
        ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('Department_Id', $department_id)
        ->get();
    }

    $gender = DB::table('mstr_gender')->get();
    $city = DB::table('mstr_city')->get();
    $citizenship = DB::table('mstr_citizenship')->get();
    $religion = DB::table('mstr_religion')->get();
    $marital = DB::table('mstr_marital_status')->get();
    $blood = DB::table('mstr_blood_type')->get();
    $high_school_major = DB::table('mstr_high_school_major')->get();
    $class_program = DB::table('mstr_class_program')->get();
    $class = DB::table('mstr_class')->get();

    return view('acd_studentmundurkeluardo/create')
      ->with('entry_year_id', $entry_year_id)
      ->with('department_id', $department_id)
      ->with('entry_year', $entry_year)
      ->with('department', $Department)
      ->with('gender', $gender)
      ->with('city', $city)
      ->with('citizenship', $citizenship)
      ->with('religion', $religion)
      ->with('marital', $marital)
      ->with('blood', $blood)
      ->with('high_school_major', $high_school_major)
      ->with('class_program', $class_program)
      ->with('class', $class)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //  $this->validate($request,[
    //    'file'=>'max:10000'
    //  ],['file.max' => 'Foto harus kurang dari 8Mb']);
    $Student_Id = Input::get('Student_Id');
    $department_id = Input::get('department_id');
    $status = Input::get('Status');

    try {
      foreach ($Student_Id as $data) {
        $u = DB::table('acd_student')
          ->where('Student_Id', $data)
          ->update(['Status_Id' => $status]);
      }

      return Redirect::back()->withErrors('Berhasil Menambah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menambah Data');
    }
  }

  public function create_student()
  {
    $rowpage = Input::get('rowpage');
    $FacultyId = Auth::user()->Faculty_Id;

    if ($rowpage == null || $rowpage <= 0) {
      $rowpage = 10;
    }

    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year');
    $employee_id = Input::get('employee_id');
    $status = Input::get('status');

    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');

    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');
    $FacultyId = Auth::user()->Faculty_Id;

    $entry_year = DB::table('mstr_entry_year')
      ->orderBy('mstr_entry_year.Entry_Year_Code', 'desc')
      ->get();
    $mhs_out = DB::table('acd_student_out as a')
      ->join('acd_student as b', 'a.Student_Id', '=', 'b.Student_Id')
      ->where('b.Status_Id', '!=', 5)
      ->orderBy('a.Student_Id', 'asc')
      ->select('a.Student_Id');

    if ($FacultyId == '') {
      if ($search == null) {
        $data = DB::table('acd_student')
          ->leftjoin('mstr_status', 'acd_student.Status_Id', '=', 'mstr_status.Status_Id')
          ->where('acd_student.Department_Id', $department_id)
          ->where('acd_student.Entry_Year_Id', $entry_year_id)
          ->wherenotin('acd_student.Student_Id', $mhs_out)
          ->where('acd_student.Status_Id', '!=', $status)
          ->orderBy('Nim', 'asc')
          ->get();
      } else {
        $data = DB::table('acd_student')
          ->leftjoin('mstr_status', 'acd_student.Status_Id', '=', 'mstr_status.Status_Id')
          ->where('acd_student.Department_Id', $department_id)
          ->where('Entry_Year_Id', $entry_year_id)
          ->where('acd_student.Status_Id', '!=', $status)
          ->wherenotin('acd_student.Student_Id', $mhs_out)
          ->where(function ($query) {
            $search = Input::get('search');
            $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('acd_student.Nim', 'LIKE', '%' . $search . '%');
          })
          ->orderBy('Nim', 'asc')
          ->get();
      }
    } else {
      $mhs_bimbingan = DB::table('acd_student_supervision')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_supervision.Student_Id')
        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
        ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('acd_student.Department_Id', $department_id)
        ->select('acd_student_supervision.Student_Id');

      if ($search == null) {
        $data = DB::table('acd_student')
          ->leftjoin('mstr_status', 'acd_student.Status_Id', '=', 'mstr_status.Status_Id')
          ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
          ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->where('acd_student.Department_Id', $department_id)
          ->where('acd_student.Entry_Year_Id', $entry_year_id)
          ->where('acd_student.Status_Id', '!=', $status)
          ->orderBy('Nim', 'asc')
          ->get();
      } else {
        $data = DB::table('acd_student')
          ->leftjoin('mstr_status', 'acd_student.Status_Id', '=', 'mstr_status.Status_Id')
          ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
          ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->where('acd_student.Department_Id', $department_id)
          ->where('Entry_Year_Id', $entry_year_id)
          ->where('acd_student.Status_Id', '!=', $status)
          ->where(function ($query) {
            $search = Input::get('search');
            $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('acd_student.Nim', 'LIKE', '%' . $search . '%');
          })
          ->orderBy('Nim', 'asc')
          ->get();
      }
    }

    //  $data->appends(['search'=> $search, 'department_id'=> $department_id,'entry_year_id'=> $entry_year_id,'status'=>$status]);
    return view('acd_studentmundurkeluardo/create_student')
      ->with('status', $status)
      ->with('query', $data)
      ->with('entry_year', $entry_year)
      ->with('department_id', $department_id)
      ->with('employee_id', $employee_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('search', $search)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage);
  }

  public function create_student_mengundurkandiri()
  {
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year');
    $status = Input::get('status');
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');

    $select_nim = Db::table('acd_student as a')
      ->where('Department_Id', $department_id)
      ->where('Entry_Year_Id', $entry_year_id)
      //  ->where('a.Status_Id',null)
      ->get();
    $nim = Input::get('nim');

    $reason = DB::table('mstr_out_reason')->get();

    $term_year = Input::get('term_year');
    $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

    return view('acd_studentmundurkeluardo/create_student_mengundurkandiri')
      ->with('department_id', $department_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('status', $status)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('select_nim', $select_nim)
      ->with('nim', $nim)
      ->with('reason', $reason)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year);
  }

  public function store_student_mengundurkandiri(Request $request)
  {
    $date_out = Input::get('date');
    $student_id = Input::get('nim');
    $reason = Input::get('reason');
    $term_year = Input::get('term_year');
    $status = Input::get('status');
    $user_login = Auth::user()->email;
    $today = Carbon::today();

    try {
      $u = DB::table('acd_student_out')->insert([
        'Student_Id' => $student_id,
        'Term_Year_Id' => $term_year,
        'Out_Date' => $date_out,
        'Out_Reason_Id' => $reason,
        'Description' => $request->reason_text,
        'Created_Date' => $today,
        'Created_By' => $user_login
      ]);

      $update_student = DB::table('acd_student')
        ->where('Student_Id', $student_id)
        ->update(['Status_Id' => $status]);

      return Redirect::back()->withErrors('Berhasil Menambah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menambah Data');
    }
  }

  public function edit_student_mengundurkandiri($id, $student_id)
  {
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year_id');
    $status = $id;
    $student_id = $student_id;
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');

    $student = Db::table('acd_student as a')
      ->where('Student_Id', $student_id)
      ->first();

    $db_out = DB::table('acd_student_out as a')
      ->where('Student_Id', $student_id)
      ->first();

    $reason = DB::table('mstr_out_reason')->get();

    $term_year = Input::get('term_year');
    $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

    return view('acd_studentmundurkeluardo/edit_student_mengundurkandiri')
      ->with('department_id', $department_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('status', $status)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('reason', $reason)
      ->with('student', $student)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year)
      ->with('db_out', $db_out);
  }

  public function update_student_mengundurkandiri(Request $request, $student_id)
  {
    $date_out = Input::get('date');
    $reason = Input::get('reason');
    $term_year = Input::get('term_year');
    $status = Input::get('status');
    $user_login = Auth::user()->email;
    $today = Carbon::today();

    try {
      $u = DB::table('acd_student_out')
        ->where('Student_Id', $student_id)
        ->update(['Term_Year_Id' => $term_year, 'Out_Date' => $date_out, 'Out_Reason_Id' => $reason, 'Description' => $request->reason_text, 'Modified_Date' => $today, 'Modified_By' => $user_login]);

      return Redirect::back()->withErrors('Berhasil Mengubah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Mengubah Data');
    }
  }

  public function create_student_do()
  {
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year');
    $status = Input::get('status');
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');

    $select_nim = Db::table('acd_student as a')
      ->where('Department_Id', $department_id)
      ->where('Entry_Year_Id', $entry_year_id)
      // ->where('a.Status_Id', null)
      ->get();
    $nim = Input::get('nim');

    $reason = DB::table('mstr_out_reason')->get();

    $term_year = Input::get('term_year');
    $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

    return view('acd_studentmundurkeluardo/create_student_do')
      ->with('department_id', $department_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('status', $status)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('select_nim', $select_nim)
      ->with('nim', $nim)
      ->with('reason', $reason)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year);
  }

  public function store_student_do(Request $request)
  {
    $date_out = Input::get('date');
    $student_id = Input::get('nim');
    $reason = Input::get('reason');
    $term_year = Input::get('term_year');
    $status = Input::get('status');
    $user_login = Auth::user()->email;
    $today = Carbon::today();

    try {
      $u = DB::table('acd_student_out')->insert(['Student_Id' => $student_id, 'Term_Year_Id' => $term_year, 'Out_Date' => $date_out, 'Out_Reason_Id' => $reason, 'Description' => $request->reason_text, 'Created_Date' => $today, 'Created_By' => $user_login]);

      $update_student = DB::table('acd_student')
        ->where('Student_Id', $student_id)
        ->update(['Status_Id' => $status]);

      return Redirect::back()->withErrors('Berhasil Menambah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menambah Data');
    }
  }

  public function edit_student_do($id, $student_id)
  {
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year_id');
    $status = $id;
    $student_id = $student_id;
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');

    $student = Db::table('acd_student as a')
      ->where('Student_Id', $student_id)
      ->first();

    $db_out = DB::table('acd_student_out as a')
      ->where('Student_Id', $student_id)
      ->first();

    $reason = DB::table('mstr_out_reason')->get();

    $term_year = Input::get('term_year');
    $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

    return view('acd_studentmundurkeluardo/edit_student_do')
      ->with('department_id', $department_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('status', $status)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('reason', $reason)
      ->with('student', $student)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year)
      ->with('db_out', $db_out);
  }

  public function update_student_do(Request $request, $student_id)
  {
    $date_out = Input::get('date');
    $reason = Input::get('reason');
    $term_year = Input::get('term_year');
    $status = Input::get('status');
    $user_login = Auth::user()->email;
    $today = Carbon::today();

    try {
      $u = DB::table('acd_student_out')
        ->where('Student_Id', $student_id)
        ->update(['Term_Year_Id' => $term_year, 'Out_Date' => $date_out, 'Out_Reason_Id' => $reason, 'Description' => $request->reason_text, 'Modified_Date' => $today, 'Modified_By' => $user_login]);

      return Redirect::back()->withErrors('Berhasil Mengubah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Mengubah Data');
    }
  }

  public function create_student_meninggal()
  {
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year');
    $status = Input::get('status');
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');

    $select_nim = Db::table('acd_student as a')
      ->where('Department_Id', $department_id)
      ->where('Entry_Year_Id', $entry_year_id)
      // ->where('a.Status_Id', null)
      ->get();
    $nim = Input::get('nim');

    $reason = DB::table('mstr_out_reason')->get();

    $term_year = Input::get('term_year');
    $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

    return view('acd_studentmundurkeluardo/create_student_meninggal')
      ->with('department_id', $department_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('status', $status)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('select_nim', $select_nim)
      ->with('nim', $nim)
      ->with('reason', $reason)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year);
  }

  public function store_student_meninggal(Request $request)
  {
    $date_out = Input::get('date');
    $student_id = Input::get('nim');
    $reason = Input::get('reason');
    $term_year = Input::get('term_year');
    $status = Input::get('status');
    $user_login = Auth::user()->email;
    $today = Carbon::today();

    try {
      $u = DB::table('acd_student_out')->insert(['Student_Id' => $student_id, 'Term_Year_Id' => $term_year, 'Out_Date' => $date_out, 'Out_Reason_Id' => $reason, 'Description' => $request->reason_text, 'Created_Date' => $today, 'Created_By' => $user_login]);

      $update_student = DB::table('acd_student')
        ->where('Student_Id', $student_id)
        ->update(['Status_Id' => $status]);

      return Redirect::back()->withErrors('Berhasil Menambah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menambah Data');
    }
  }

  public function edit_student_meninggal($id, $student_id)
  {
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year_id');
    $status = $id;
    $student_id = $student_id;
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');

    $student = Db::table('acd_student as a')
      ->where('Student_Id', $student_id)
      ->first();

    $db_out = DB::table('acd_student_out as a')
      ->where('Student_Id', $student_id)
      ->first();

    $reason = DB::table('mstr_out_reason')->get();

    $term_year = Input::get('term_year');
    $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

    return view('acd_studentmundurkeluardo/edit_student_meninggal')
      ->with('department_id', $department_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('status', $status)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('reason', $reason)
      ->with('student', $student)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year)
      ->with('db_out', $db_out);
  }

  public function update_student_meninggal(Request $request, $student_id)
  {
    $date_out = Input::get('date');
    $reason = Input::get('reason');
    $term_year = Input::get('term_year');
    $status = Input::get('status');
    $user_login = Auth::user()->email;
    $today = Carbon::today();

    try {
      $u = DB::table('acd_student_out')
        ->where('Student_Id', $student_id)
        ->update(['Term_Year_Id' => $term_year, 'Out_Date' => $date_out, 'Out_Reason_Id' => $reason, 'Description' => $request->reason_text, 'Modified_Date' => $today, 'Modified_By' => $user_login]);

      return Redirect::back()->withErrors('Berhasil Mengubah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Mengubah Data');
    }
  }

  public function create_student_pindah()
  {
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year');
    $status = Input::get('status');
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');

    $nim = Input::get('nim');
    $select_nim = Db::table('acd_student as a')
      ->where('Department_Id', $department_id)
      ->where('Entry_Year_Id', $entry_year_id)
      ->get();

    $select_department = Db::table('mstr_department as a')
      ->where('Faculty_Id', '!=', null)
      ->get();

    $select_class = Db::table('mstr_class_program as a')->get();

    $reason = DB::table('mstr_out_reason')->get();

    $term_year = Input::get('term_year');
    $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

    return view('acd_studentmundurkeluardo/create_student_pindah')
      ->with('department_id', $department_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('status', $status)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('select_nim', $select_nim)
      ->with('nim', $nim)
      ->with('reason', $reason)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year)
      ->with('select_department', $select_department)
      ->with('select_class', $select_class);
  }

  public function findnim(Request $request)
  {
    $student = DB::table('acd_student as a')
      ->join('mstr_department as b', 'a.Department_Id', '=', 'b.Department_Id')
      ->join('mstr_class_program as c', 'a.Class_Prog_Id', '=', 'c.Class_Prog_Id')
      ->where('Student_Id', $request->Student_Id)
      ->select('b.Department_Name', 'b.Department_Id', 'c.Class_Prog_Id', 'c.Class_Program_Name')
      ->first();

    return response()->json($student);
  }

  public function store_student_pindah(Request $request)
  {
    $date_out = Input::get('date');
    $student_id = Input::get('nim');
    $reason = Input::get('reason');
    $term_year = Input::get('term_year');
    $status = Input::get('status');
    $prodi_pindah = Input::get('prodi_pindah');
    $class_pindah = Input::get('class_pindah');
    $user_login = Auth::user()->email;
    $today = Carbon::today();

    $std = DB::table('acd_student as a')
      ->where('Student_Id', $student_id)
      ->first();

    try {
      $u = DB::table('acd_student_out')->insert(['Student_Id' => $student_id, 'Term_Year_Id' => $term_year, 'Out_Date' => $date_out, 'Department_From_Id' => $std->Department_Id, 'Class_Prog_From_Id' => $std->Class_Prog_Id, 'Department_Destination' => $prodi_pindah, 'Class_Prog_Destination' => $class_pindah, 'Out_Reason_Id' => $reason, 'Created_Date' => $today, 'Created_By' => $user_login, 'Description' => 'Pindah Prodi']);

      $update_student = DB::table('acd_student')
        ->where('Student_Id', $student_id)
        ->update(['Status_Id' => $status, 'Department_Id' => $prodi_pindah, 'Class_Prog_Id' => $class_pindah]);

      $update_krs = DB::table('acd_student_krs')
        ->where('Student_Id', $student_id)
        ->update([
          'Class_Prog_Id' => $class_pindah,
        ]);

      return Redirect::back()->withErrors('Berhasil mengubah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal mengubah Data');
    }
  }

  public function edit_student_pindah($id, $student_id)
  {
    $department_id = Input::get('department');
    $entry_year_id = Input::get('entry_year_id');
    $status = $id;
    $student_id = $student_id;
    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');
    $current_page = Input::get('current_page');
    $current_rowpage = Input::get('current_rowpage');
    $current_search = Input::get('current_search');

    $student = Db::table('acd_student as a')
      ->join('mstr_department as c', 'c.Department_Id', '=', 'a.Department_Id')
      ->join('mstr_class_program as d', 'd.Class_Prog_Id', '=', 'a.Class_Prog_Id')
      ->where('Student_Id', $student_id)
      ->first();

    $db_out = DB::table('acd_student_out as a')
      ->join('acd_student as b', 'a.Student_Id', '=', 'b.Student_Id')
      ->join('mstr_department as c', 'c.Department_Id', '=', 'a.Department_From_Id')
      ->join('mstr_class_program as d', 'd.Class_Prog_Id', '=', 'a.Class_Prog_From_Id')
      ->where('a.Student_Id', $student_id)
      ->first();
    // dd($db_out);

    $reason = DB::table('mstr_out_reason')->get();

    $select_department = Db::table('mstr_department as a')->get();

    $term_year = Input::get('term_year');

    $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

    $select_class = Db::table('mstr_class_program as a')->get();

    return view('acd_studentmundurkeluardo/edit_student_pindah')
      ->with('department_id', $department_id)
      ->with('entry_year_id', $entry_year_id)
      ->with('status', $status)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage)
      ->with('current_page', $current_page)
      ->with('current_rowpage', $current_rowpage)
      ->with('current_search', $current_search)
      ->with('reason', $reason)
      ->with('student', $student)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year)
      ->with('db_out', $db_out)
      ->with('select_department', $select_department)
      ->with('select_class', $select_class);
  }

  public function update_student_pindah(Request $request, $student_id)
  {
    $date_out = Input::get('date');
    $reason = Input::get('reason');
    $term_year = Input::get('term_year');
    $status = Input::get('status');
    $prodi_pindah = Input::get('prodi_pindah');
    $class_pindah = Input::get('class_pindah');
    $user_login = Auth::user()->email;
    $today = Carbon::today();

    try {
      $u = DB::table('acd_student_out')
        ->where('Student_Id', $student_id)
        ->update(['Term_Year_Id' => $term_year, 'Out_Date' => $date_out, 'Department_Destination' => $prodi_pindah, 'Class_Prog_Destination' => $class_pindah, 'Out_Reason_Id' => $reason, 'Modified_Date' => $today, 'Modified_By' => $user_login]);

      $update_student = DB::table('acd_student')
        ->where('Student_Id', $student_id)
        ->update(['Status_Id' => $status, 'Department_Id' => $prodi_pindah, 'Class_Prog_Id' => $class_pindah]);

      return Redirect::back()->withErrors('Berhasil Mengubah Data');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Mengubah Data');
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
  public function destroy(Request $request, $id)
  {
    $student = DB::table('acd_student_out')
      ->where('Student_Id', $id)
      ->first();
    if ($student) {
      if ($student->Description == 'Pindah Prodi') {
        $update_student = DB::table('acd_student')
          ->where('Student_Id', $id)
          ->update([
            'Status_Id' => 1,
            'Department_Id' => $student->Department_From_Id,
            'Class_Prog_Id' => $student->Class_Prog_From_Id,
          ]);
        $student = DB::table('acd_student_out')
          ->where('Student_Id', $id)
          ->delete();
      } else {
        $student = DB::table('acd_student_out')
          ->where('Student_Id', $id)
          ->delete();
        $update_student = DB::table('acd_student')
          ->where('Student_Id', $id)
          ->update([
            'Status_Id' => 1,
          ]);
      }
    }else{
      $update_student = DB::table('acd_student')
              ->where('Student_Id', $id)
              ->update([
                  'Status_Id' => 1,
              ]);
  }
    echo json_encode($update_student);
  }
}
