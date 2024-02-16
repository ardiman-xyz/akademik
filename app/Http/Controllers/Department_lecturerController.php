<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use Auth;
use App\Http\Controllers\ApiStrukturalController;

class Department_lecturerController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

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
       $DepartmentId = Auth::user()->Department_Id;

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');

      $tester = ApiStrukturalController::dosen_prodi('',$department);
      // dd($tester);

      if($FacultyId==""){
        if($DepartmentId == ""){
          $select_Department_Id = DB::table('mstr_department')
          ->wherenotnull('Faculty_Id')
          ->orderBy('mstr_department.Department_Id', 'desc')
          ->get();
        }else{
          $select_Department_Id = DB::table('mstr_department')
          ->wherenotnull('Faculty_Id')
          ->where('mstr_department.Department_Id',$DepartmentId)
          ->orderBy('mstr_department.Department_Id', 'desc')
          ->get();
        }
      }else{
        if($DepartmentId == ""){
          $select_Department_Id = DB::table('mstr_department')
          ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->orderBy('mstr_department.Department_Id', 'desc')
          ->get();
        }else{
          $select_Department_Id = DB::table('mstr_department')
          ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->where('mstr_department.Department_Id',$DepartmentId)
          ->orderBy('mstr_department.Department_Id', 'desc')
          ->get();
        }
      }

      $_role = DB::table('_role')->where([['app','Kepegawaian'],['is_admin','!=','1']])->get();
        $array = [];
        $i=0;
        foreach ($_role as $item) {
          $array[$i] = $item->id;
          $i++;
        }
        $_role_user = DB::table('_role_user')
                      ->join('_user','_user.id','=','_role_user.user_id')
                      ->whereIn('role_id',$array)
                      ->select('_role_user.id','_role_user.role_id','_role_user.user_id','_user.email')
                      ->get();

        $email_user = [];
        $ii=0;
        foreach ($_role_user as $item) {
          $email_user[$ii] = $item->email;
          $ii++;
        }   
        
        if ($search == null) {
          $data = DB::table('acd_department_lecturer')
          ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_department_lecturer.Employee_Id')
          ->where('acd_department_lecturer.Department_Id', $department)
          // ->whereIn('Email_Corporate',$email_user)
          ->orderBy('emp_employee.Employee_Id', 'asc')
          ->paginate($rowpage);
        }else {
          $data = DB::table('acd_department_lecturer')
          ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_department_lecturer.Employee_Id')
          ->where('acd_department_lecturer.Department_Id', $department)
          // ->whereIn('Email_Corporate',$email_user)
          ->whereRaw("lower(emp_employee.Full_Name) like '%" . strtolower($search) . "%'")
          ->orderBy('emp_employee.Employee_Id', 'asc')
          ->paginate($rowpage);
        }

        $query = $this->paginate($tester);

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
       return view('acd_department_lecturer/index')->with('select_Department_Id', $select_Department_Id)->with('department', $department)->with('query',$query)->with('search',$search)->with('rowpage',$rowpage);

     }

    public function paginate($items, $perPage=100, $page = null, $options = [])
    {
      // dd(Paginator::resolveCurrentPath());
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
       $department = Input::get('department');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $FacultyId = Auth::user()->Faculty_Id;


      $mstr_department = DB::table('mstr_department')->where('Department_Id', $department)->get();

      $acd_department_lecturer = DB::table('acd_department_lecturer')
      ->where('acd_department_lecturer.Department_Id', $department)->select('Employee_Id');

      $_role = DB::table('_role')->where([['app','Kepegawaian'],['is_admin','!=','1']])->get();
        $array = [];
        $i=0;
        foreach ($_role as $item) {
          $array[$i] = $item->id;
          $i++;
        }
        $_role_user = DB::table('_role_user')
                      ->join('_user','_user.id','=','_role_user.user_id')
                      ->whereIn('role_id',$array)
                      ->select('_role_user.id','_role_user.role_id','_role_user.user_id','_user.email')
                      ->get();

        $email_user = [];
        $ii=0;
        foreach ($_role_user as $item) {
          $email_user[$ii] = $item->email;
          $ii++;
        }  

       $select_employee_id = DB::table('emp_employee')
      //  ->whereIn('Email_Corporate',$email_user)
       ->WhereNotIn('Employee_Id', $acd_department_lecturer)
       ->get();
       return view('acd_department_lecturer/create')->with('department', $department)->with('mstr_department', $mstr_department)->with('select_employee_id', $select_employee_id)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Department_Id'=>'required|max:6',
         'Employee_Id'=>'required',


       ]);
             // $Functional_Position_Curriculum_Id  = Input::get('Functional_Position_Curriculum_Id');
             $Department_Id = Input::get('Department_Id');
             $Employee_Id = Input::get('Employee_Id');


       foreach ($Employee_Id as $data) {
         $u =  DB::table('acd_department_lecturer')
         ->insert(
         ['Department_Id' => $Department_Id, 'Employee_Id' => $data]);
       }
       return Redirect::back()->withErrors('Berhasil Menambah Desen Prodi');
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
     public function destroy($id)
     {
         $a=DB::table('acd_department_lecturer')->where('Department_Lecturer_Id', $id)->delete();
         echo json_encode($a);
     }
 }
