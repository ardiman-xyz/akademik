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
use App\GetDepartment;

class Student_supervisionController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','create_dpa']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','create_dpa']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','create_dpa']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','create_dpa']]);
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
       $FacultyId = Auth::user()->Faculty_Id;
       $DeparmentId = Auth::user()->Department_Id;

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');

      $select_Department_Id = GetDepartment::getDepartment();


      if ($search == null) {
        $data = DB::table('acd_student_supervision')
        ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_student_supervision.Employee_Id')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_supervision.Student_Id')
        ->join('mstr_department', 'mstr_department.Department_Id','=','acd_student.Department_Id')
        ->where('acd_student.Department_Id', $department)
        ->groupBy('emp_employee.Employee_Id')
        ->select('emp_employee.Employee_Id','emp_employee.Email_Corporate', 'emp_employee.Employee_Id', 'emp_employee.Nip', 'emp_employee.Full_Name' , 'emp_employee.First_Title', 'emp_employee.Last_Title' , 'emp_employee.Name',
                DB::raw("(SELECT COUNT(acd_student_supervision.Student_Supervision_Id) FROM acd_student_supervision WHERE acd_student_supervision.Employee_Id = emp_employee.Employee_Id) as jumlah_bimbingan"),
                DB::raw("(SELECT COUNT(acd_student_supervision.Student_Supervision_Id) FROM acd_student_supervision
                          JOIN acd_student as asd ON asd.Student_Id = acd_student_supervision.Student_Id
                          WHERE acd_student_supervision.Employee_Id = emp_employee.Employee_Id 
                          && asd.Status_Id = 8
                          ) as jumlah_lulus"))
        ->orderBy('emp_employee.Nik', 'asc')
        ->paginate($rowpage);
        // ->get();
        // dd($data);
      }else {
        $data = DB::table('acd_student_supervision')
        ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_student_supervision.Employee_Id')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_supervision.Student_Id')
        ->join('mstr_department', 'mstr_department.Department_Id','=','acd_student.Department_Id')
        ->where('acd_student.Department_Id', $department)
        ->groupBy('emp_employee.Employee_Id')
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(emp_employee.Full_Name) like '%" . strtolower($search) . "%'");
          $query->orwhereRaw("lower(emp_employee.Nik) like '%" . strtolower($search) . "%'");
        })
        ->select('emp_employee.Employee_Id','emp_employee.Email_Corporate', 'emp_employee.Employee_Id', 'emp_employee.Nip', 'emp_employee.Full_Name' , 'emp_employee.First_Title', 'emp_employee.Last_Title' , 'emp_employee.Name',
                DB::raw("(SELECT COUNT(acd_student_supervision.Student_Supervision_Id) FROM acd_student_supervision WHERE acd_student_supervision.Employee_Id = emp_employee.Employee_Id) as jumlah_bimbingan"),
                DB::raw("(SELECT COUNT(acd_student_supervision.Student_Supervision_Id) FROM acd_student_supervision
                          JOIN acd_student as asd ON asd.Student_Id = acd_student_supervision.Student_Id
                          WHERE acd_student_supervision.Employee_Id = emp_employee.Employee_Id 
                          && asd.Status_Id = 8
                          ) as jumlah_lulus"))
        ->orderBy('emp_employee.Nik', 'asc')
        ->paginate($rowpage);
      }

      $emp_employee = DB::table('acd_department_lecturer')
       ->join('emp_employee', 'emp_employee.Employee_Id', '=', 'acd_department_lecturer.Employee_Id')
       ->where('acd_department_lecturer.Department_Id', $department)->get();
      //  dd($emp_employee);

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
       return view('acd_student_supervision/index')->with('emp_employee', $emp_employee)->with('select_Department_Id', $select_Department_Id)->with('department', $department)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
       $department_id = Input::get('department_id');
       $employee_id = Input::get('employee_id');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       if ($rowpage == null) {
         $rowpage = 10;
       }
       $current_page = Input::get('current_page');
       $current_rowpage = Input::get('current_rowpage');
       $current_search = Input::get('current_search');
       $FacultyId = Auth::user()->Faculty_Id;

       // $emp_employee = DB::table('acd_department_lecturer')
       // ->join('emp_employee', 'emp_employee.Employee_Id', '=', 'acd_department_lecturer.Employee_Id')
       // ->where('acd_department_lecturer.Department_Id', $department_id)->get();

       $emp_employee = DB::table('emp_employee')
        ->join(DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"), 'emp_employee.Employee_Id', 'max_placement.Employee_Id'
        )
        ->join('emp_placement',function($golru){
            $golru->on('emp_placement.Employee_Id','emp_employee.Employee_Id')
            ->on('emp_placement.Tmt_Date','max_placement.Tmt_Date');
        })
        ->where('emp_placement.Department_Id', $department_id)
        // ->whereNotIn('emp_employee.Employee_Id', $employee)
        ->get();

      if($FacultyId==""){
        if ($search == null) {
        $data = DB::table('acd_student_supervision')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_supervision.Student_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->where('acd_student.Department_Id', $department_id)
        ->where('Employee_Id', $employee_id)
        ->paginate($rowpage);
        }else {
        $data = DB::table('acd_student_supervision')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_supervision.Student_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->where('acd_student.Department_Id', $department_id)
        ->where('Employee_Id', $employee_id)
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
          $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
        })
        ->paginate($rowpage);
        }
      }else{
        if ($search == null) {
        $data = DB::table('acd_student_supervision')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_supervision.Student_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('acd_student.Department_Id', $department_id)->where('Employee_Id', $employee_id)->paginate($rowpage);
        }else {
        $data = DB::table('acd_student_supervision')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_supervision.Student_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('acd_student.Department_Id', $department_id)->where('Employee_Id', $employee_id)
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
          $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
        })
        ->paginate($rowpage);
        }
      }


       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department_id'=> $department_id, 'employee_id'=> $employee_id, 'current_page'=> $current_page, 'current_rowpage'=> $current_rowpage, 'current_search'=> $current_search]);
       return view('acd_student_supervision/create')->with('query', $data)->with('department_id', $department_id)->with('employee_id', $employee_id)->with('emp_employee', $emp_employee)->with('current_page', $current_page)->with('current_rowpage', $current_rowpage)->with('current_search', $current_search)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
     }

     public function create_dpa(Request $request)
     {
       $department_id = Input::get('department_id');
       $entry_year_id = Input::get('entry_year_id');
       $employee_id = Input::get('employee_id');
       $src = Input::get('src');

       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');

       $current_page = Input::get('current_page');
       $current_rowpage = Input::get('current_rowpage');
       $current_search = Input::get('current_search');
       $FacultyId = Auth::user()->Faculty_Id;

       $entry_year = DB::table('mstr_entry_year')->orderBy('mstr_entry_year.Entry_Year_Code', 'desc')->get();
       $mhs_out = DB::table('acd_student_out')->where('Description','!=','Pindah Prodi')->orderBy('Student_Id', 'asc')->select('Student_Id');

       if($FacultyId==""){
         $mhs_bimbingan = DB::table('acd_student_supervision')
         ->join('acd_student','acd_student.Student_Id','=','acd_student_supervision.Student_Id')
         ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
         ->where('acd_student.Department_Id', $department_id)->select('acd_student_supervision.Student_Id');
       }else{
         $mhs_bimbingan = DB::table('acd_student_supervision')
         ->join('acd_student','acd_student.Student_Id','=','acd_student_supervision.Student_Id')
         ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
         ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
         ->where('mstr_faculty.Faculty_Id', $FacultyId)
         ->where('acd_student.Department_Id', $department_id)
         ->select('acd_student_supervision.Student_Id');
       }

       $data = DB::table('acd_student')
       ->where('acd_student.Entry_Year_Id', $entry_year_id)
       ->whereNotIn('Student_Id', $mhs_out)
       ->whereNotIn('Student_Id', $mhs_bimbingan)
       ->orderBy('Nim','asc');
       if($request->semua_prodi == ''){
        $data = $data->where('acd_student.Department_Id', $department_id);
       }

         if ($search == null) {
         $data = $data->get();
         }else {
         $data = $data
         ->where(function($query){
           $search = Input::get('search');
           $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
           $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
         })->get();
         }
       
       // $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department_id'=> $department_id, 'employee_id'=> $employee_id, 'entry_year_id'=> $entry_year_id]);
       return view('acd_student_supervision/create_dpa')->with('request', $request)->with('query', $data)->with('entry_year', $entry_year)->with('department_id', $department_id)->with('employee_id', $employee_id)->with('entry_year_id', $entry_year_id)->with('src',$src)->with('current_page', $current_page)->with('current_rowpage', $current_rowpage)->with('current_search', $current_search)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Employee_Id'=>'required|max:6',
         'Student_Id'=>'required',

       ]);
             // $Functional_Position_Curriculum_Id  = Input::get('Functional_Position_Curriculum_Id');
             $Employee_Id = Input::get('Employee_Id');
             $Student_Id = Input::get('Student_Id');

try {
       foreach ($Student_Id as $data) {
         $u =  DB::table('acd_student_supervision')
         ->insert(
         ['Employee_Id' => $Employee_Id, 'Student_Id' => $data, 'Created_Date' => Date('Y-m-d')]);
       }

       return Redirect::back()->withErrors('Berhasil Menambah Bimbingan DPA');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Grade Nilai');
     }
     }

     public function update_dosen(Request $request)
     {
        $department = $request->department;
        $employeeid_old = $request->employeeid_old;
        $employeeid_new = $request->employeeid_new;
        $insert =  DB::table('acd_student_supervision')
                ->where('Employee_Id',$employeeid_old)
                ->update(
                ['Employee_Id' => $employeeid_new, 'Modified_By'=> Auth::user()->email , 'Created_Date' => Date('Y-m-d')]);

        // echo json_encode($employeeid_new);

        return response()->json([
                'status' => 200,
                'data' => $insert,
                'total' => $insert
            ]);
     }

     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {

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
     public function destroy(Request $request,$id)
     {
         $q=DB::table('acd_student_supervision')->where('Student_Supervision_Id', $id)->delete();
         echo json_encode($q);
     }

     // public function export(Request $request){
     //  dd($request->all());
     // }
 }
