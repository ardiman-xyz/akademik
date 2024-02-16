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
use App\GetDepartment;

class Event_schedController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('access:CanAdd', ['except' => ['index', 'show', 'edit', 'update', 'destroy']]);
    $this->middleware('access:CanEdit', ['except' => ['index', 'create', 'store', 'show', 'destroy']]);
    $this->middleware('access:CanDelete', ['except' => ['index', 'create', 'store', 'show', 'edit', 'update']]);
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $this->validate($request, [
      'rowpage' => 'numeric|nullable'
    ]);
    $search = Input::get('search');
    $rowpage = Input::get('rowpage');
    if ($rowpage == null || $rowpage <= 0) {
      $rowpage = 10;
    }
    $Event_Id = Input::get('event_id');
    $FacultyId = Auth::user()->Faculty_Id;
    $DepartmentId = Auth::user()->Department_Id;

    $select_event = DB::table('mstr_event')
      ->orderBy('mstr_event.Event_Id', 'asc')
      ->get();


    $select_department = GetDepartment::getDepartment();
    $dpt = [];
    foreach ($select_department as $key) {
      $dpt[] = $key->Department_Id;
    }

    $data = DB::table('mstr_event_sched')
      ->join('mstr_event', 'mstr_event.Event_Id', '=', 'mstr_event_sched.Event_Id')
      ->join('mstr_department', 'mstr_department.Department_Id', '=', 'mstr_event_sched.Department_Id')
      ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'mstr_event_sched.Term_Year_Id')
      ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'mstr_event_sched.Class_Prog_Id')
      ->wherein('mstr_department.Department_Id', $dpt)
      ->where('mstr_event_sched.Event_Id', $Event_Id)
      ->whereRaw("lower(Department_Name) like '%" . strtolower($search) . "%'")

      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*', 'mstr_term_year.Term_Year_Name', 'mstr_department.Department_Name', 'mstr_class_program.Class_Program_Name')
      ->paginate($rowpage);

    $data->appends(['event_id' => $Event_Id, 'search' => $search, 'rowpage' => $rowpage]);
    return view('mstr_event_sched/index')->with('query', $data)->with('search', $search)->with('rowpage', $rowpage)->with('select_event', $select_event)->with('event_id', $Event_Id);
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
    $DepartmentId = Auth::user()->Department_Id;


    $select_department = GetDepartment::getDepartment();

    $select_term_year = DB::table('mstr_term_year')->orderBy('Term_Year_Name', 'desc')->get();
    $select_class_prog = DB::table('mstr_class_program')->get();

    return view('mstr_event_sched/create')->with('event', $event)->with('select_class_prog', $select_class_prog)->with('event_id', $Event_Id)->with('select_department', $select_department)->with('select_term_year', $select_term_year)->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // dd($request->all());
    $Event_Id = Input::get('Event_Id');
    $Department_Id = Input::get('Department_Id');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Is_Open = Input::get('Is_Open');
    $Start_Date = Input::get('Start_Date');
    $End_Date = Input::get('End_Date');
    $End_Date_Cost = Input::get('End_Date_Cost');
    $Days = Input::get('Days');
    if ($Event_Id == 1) {
      $this->validate($request, [
        'Event_Id' => 'required',
        // 'Department_Id' => 'required',
        'Term_Year_Id' => 'required',
        'Is_Open' => 'required',
        'Start_Date' => 'required',
        'End_Date' => 'required',
        'End_Date_Cost' => 'required',
      ]);

      if (isset($request->all_prodi)) {
        $data_dept = DB::table('mstr_department')->get();
        foreach ($data_dept as $key) {
          $check_jadwal = DB::table('mstr_event_sched')
            ->where([
              ['Event_Id', $Event_Id],
              ['Department_Id', $key->Department_Id],
              ['Term_Year_Id', $Term_Year_Id],
              ['Class_Prog_Id', $request->Class_Prog_Id]
            ])
            ->first();
          if ($check_jadwal) {
            $u =  DB::table('mstr_event_sched')
              ->where('Event_Sched_Id', $check_jadwal->Event_Sched_Id)
              ->update([
                'Is_Open' => $Is_Open,
                'Start_Date' => $Start_Date,
                'End_Date' => $End_Date,
                'End_Date_Cost' => $End_Date_Cost
              ]);
          } else {
            $u =  DB::table('mstr_event_sched')
              ->insert([
                'Event_Id' => $Event_Id,
                'Department_Id' => $key->Department_Id,
                'Term_Year_Id' => $Term_Year_Id,
                'Class_Prog_Id' => $request->Class_Prog_Id,
                'Is_Open' => $Is_Open,
                'Start_Date' => $Start_Date,
                'End_Date' => $End_Date,
                'End_Date_Cost' => $End_Date_Cost
              ]);
          }
        }
      } else {
        foreach ($Department_Id as $key) {
          $check_jadwal = DB::table('mstr_event_sched')
            ->where([
              ['Event_Id', $Event_Id],
              ['Department_Id', $key],
              ['Term_Year_Id', $Term_Year_Id],
            ['Class_Prog_Id', $request->Class_Prog_Id]
            ])
            ->first();
          if ($check_jadwal) {
            $u =  DB::table('mstr_event_sched')
              ->where('Event_Sched_Id', $check_jadwal->Event_Sched_Id)
              ->update([
                'Is_Open' => $Is_Open,
                'Start_Date' => $Start_Date,
                'End_Date' => $End_Date,
                'End_Date_Cost' => $End_Date_Cost
              ]);
          } else {
            $u =  DB::table('mstr_event_sched')
              ->insert([
                'Event_Id' => $Event_Id,
                'Department_Id' => $key,
                'Term_Year_Id' => $Term_Year_Id,
              'Class_Prog_Id' => $request->Class_Prog_Id,
                'Is_Open' => $Is_Open,
                'Start_Date' => $Start_Date,
                'End_Date' => $End_Date,
                'End_Date_Cost' => $End_Date_Cost
              ]);
          }
        }
      }

      return Redirect::to('/setting/event_sched?event_id=' . $Event_Id)->withErrors('Berhasil Menambah Jadwal Pengisian');
    } else {
      $this->validate($request, [
        'Event_Id' => 'required',
        // 'Department_Id' => 'required',
        'Term_Year_Id' => 'required',
        'Is_Open' => 'required',
        // 'Start_Date' => 'required',
        // 'End_Date' => 'required',
      ]);

      if (isset($request->all_prodi)) {
        $data_dept = DB::table('mstr_department')->get();
        foreach ($data_dept as $key) {
          $check_jadwal = DB::table('mstr_event_sched')
            ->where([
              ['Event_Id', $Event_Id],
              ['Department_Id', $key->Department_Id],
              ['Term_Year_Id', $Term_Year_Id]
            ])
            ->first();
          if ($check_jadwal) {
            $u =  DB::table('mstr_event_sched')
              ->where('Event_Sched_Id', $check_jadwal->Event_Sched_Id)
              ->update([
                'Is_Open' => $Is_Open,
                'Start_Date' => $Start_Date,
                'End_Date' => $End_Date
              ]);
          } else {
            $u =  DB::table('mstr_event_sched')
              ->insert([
                'Event_Id' => $Event_Id,
                'Department_Id' => $key->Department_Id,
                'Term_Year_Id' => $Term_Year_Id,
                'Is_Open' => $Is_Open,
                'Start_Date' => $Start_Date,
                'End_Date' => $End_Date
              ]);
          }
        }
      } else {
        foreach ($Department_Id as $key) {
          $check_jadwal = DB::table('mstr_event_sched')
            ->where([
              ['Event_Id', $Event_Id],
              ['Department_Id', $key],
              ['Term_Year_Id', $Term_Year_Id]
            ])
            ->first();
          if ($check_jadwal) {
            $u =  DB::table('mstr_event_sched')
              ->where('Event_Sched_Id', $check_jadwal->Event_Sched_Id)
              ->update([
                'Is_Open' => $Is_Open,
                'Start_Date' => $Start_Date,
                'End_Date' => $End_Date
              ]);
          } else {
            $u =  DB::table('mstr_event_sched')
              ->insert([
                'Event_Id' => $Event_Id,
                'Department_Id' => $key,
                'Term_Year_Id' => $Term_Year_Id,
                'Is_Open' => $Is_Open,
                'Start_Date' => $Start_Date,
                'End_Date' => $End_Date
              ]);
          }
        }
      }

      return Redirect::to('/setting/event_sched?event_id=' . $Event_Id)->withErrors('Berhasil Menambah Jadwal Pengisian');
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
  public function edit(Request $request, $id)
  {
    $FacultyId = Auth::user()->Faculty_Id;
    $DepartmentId = Auth::user()->Department_Id;
    $Event_Id = Input::get('eventnya');

    if ($FacultyId == "") {
      if ($DepartmentId == "") {
        $data = DB::table('mstr_event_sched')
          ->join('mstr_event', 'mstr_event.Event_Id', '=', 'mstr_event_sched.Event_Id')
          ->join('mstr_department', 'mstr_department.Department_Id', '=', 'mstr_event_sched.Department_Id')
          ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'mstr_event_sched.Term_Year_Id')
          ->where('mstr_event_sched.Event_Sched_Id', $id)
          ->select('mstr_event_sched.*', 'mstr_event.Event_Name', 'mstr_term_year.Term_Year_Name', 'mstr_department.Department_Name')
          ->get();

        $select_department = DB::table('mstr_department')->wherenotnull('Faculty_Id')->get();
      } else {
        $data = DB::table('mstr_event_sched')
          ->join('mstr_event', 'mstr_event.Event_Id', '=', 'mstr_event_sched.Event_Id')
          ->join('mstr_department', 'mstr_department.Department_Id', '=', 'mstr_event_sched.Department_Id')
          ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'mstr_event_sched.Term_Year_Id')
          ->where('mstr_event_sched.Event_Sched_Id', $id)
          ->where('mstr_department.Department_Id', $DepartmentId)
          ->select('mstr_event_sched.*', 'mstr_event.Event_Name', 'mstr_term_year.Term_Year_Name', 'mstr_department.Department_Name')
          ->get();

        $select_department = DB::table('mstr_department')->where('mstr_department.Department_Id', $DepartmentId)->wherenotnull('Faculty_Id')->get();
      }
    } else {
      if ($DepartmentId == "") {
        $data = DB::table('mstr_event_sched')
          ->join('mstr_event', 'mstr_event.Event_Id', '=', 'mstr_event_sched.Event_Id')
          ->join('mstr_department', 'mstr_department.Department_Id', '=', 'mstr_event_sched.Department_Id')
          ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->where('mstr_department.Department_Id', $DepartmentId)
          ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'mstr_event_sched.Term_Year_Id')
          ->where('mstr_event_sched.Event_Sched_Id', $id)
          ->select('mstr_event_sched.*', 'mstr_event.Event_Name', 'mstr_term_year.Term_Year_Name', 'mstr_department.Department_Name')
          ->get();

        $select_department = DB::table('mstr_department')->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
          ->where('mstr_department.Department_Id', $DepartmentId)
          ->where('mstr_faculty.Faculty_Id', $FacultyId)->get();
      } else {
      }
    }


    $search = Input::get('search');
    $page = Input::get('page');
    $rowpage = Input::get('rowpage');

    $select_term_year = DB::table('mstr_term_year')->orderBy('Term_Year_Name', 'desc')->get();
    $class_prog = DB::table('mstr_class_program')->where('Class_Prog_Id', $data[0]->Class_Prog_Id)->first();

    return view('mstr_event_sched/edit')
      ->with('request', $request)
      ->with('class_prog', $class_prog)
      ->with('query_edit', $data)
      ->with('Event_Id', $Event_Id)
      ->with('select_department', $select_department)
      ->with('select_term_year', $select_term_year)
      ->with('search', $search)
      ->with('page', $page)
      ->with('rowpage', $rowpage);;
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
    $Event_Id = Input::get('eventnya');
    $Department_Id = Input::get('Department_Id');
    $Term_Year_Id = Input::get('Term_Year_Id');
    $Is_Open = Input::get('Is_Open');
    $Start_Date = Input::get('Start_Date');
    $End_Date = Input::get('End_Date');
    $End_Date_Cost = Input::get('End_Date_Cost');
    $Day = Input::get('Day');

    if ($Event_Id == 1) {
      $this->validate($request, [
        'Department_Id' => 'required',
        'Term_Year_Id' => 'required',
        'Is_Open' => 'required',
        'Start_Date' => 'required',
        'End_Date' => 'required',
        'End_Date_Cost' => 'required',
      ]);

      try {
        $u =  DB::table('mstr_event_sched')
          ->where('Event_Sched_Id', $id)
          ->update(
            ['Term_Year_Id' => $Term_Year_Id, 'Is_Open' => $Is_Open, 'Start_Date' => $Start_Date, 'End_Date' => $End_Date, 'End_Date_Cost' => $End_Date_Cost]
          );
        return Redirect::to('/setting/event_sched?event_id=' . $Event_Id)->withErrors('Berhasil Menyimpan Perubahan');
      } catch (\Exception $e) {
        return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
      }
    } else {
      $this->validate($request, [
        'Department_Id' => 'required',
        'Term_Year_Id' => 'required',
        'Is_Open' => 'required',
        // 'Start_Date' => 'required',
        // 'End_Date' => 'required',
      ]);

      try {
        $u =  DB::table('mstr_event_sched')
          ->where('Event_Sched_Id', $id)
          ->update(
            [
              'Term_Year_Id' => $Term_Year_Id,
              'Is_Open' => $Is_Open,
              'Start_Date' => $Start_Date,
              'End_Date' => $End_Date,
              'Day' => $Day
            ]
          );
        return Redirect::to('/setting/event_sched?event_id=' . $Event_Id)->withErrors('Berhasil Menyimpan Perubahan');
      } catch (\Exception $e) {
        return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
      }
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
    // try {
    //   DB::table('mstr_event_sched')->where('Event_Sched_Id', $id)->delete();
    //   Alert::success('Berhasil Menghapus Data', 'Success');
    //   // return Redirect::back()->withErrors('Berhasil Menghapus Data');
    //   return Redirect::back();
    // } catch (\Exception $e) {
    //   Alert::error('Gagal Menghapus Data, Kemungkinan data msih digunakan', 'Failed');
    //   // return Redirect::back()->withErrors('Gagal Menghapus Data, Kemungkinan data msih digunakan');
    //   return Redirect::back();
    //
    // }
    $q = DB::table('mstr_event_sched')->where('Event_Sched_Id', $id)->delete();
    echo json_encode($q);
  }
}
