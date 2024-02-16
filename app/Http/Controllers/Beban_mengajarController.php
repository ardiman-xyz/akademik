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
use PDF;
use Auth;

class Beban_mengajarController extends Controller
{

  public function __construct()
  {
    // $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
    // $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
    // $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    // $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

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
       $page = Input::get('page');
       $department = Input::get('department');
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       $term_year = Input::get('term_year');
       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Id', 'desc')
       ->get();

       if($FacultyId==""){
        if($DepartmentId == ""){
          $select_Department_Id = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->orderBy('mstr_department.Department_Id', 'desc')
         ->get();
        }else{
          $select_Department_Id = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->where('Department_Id',$DepartmentId)
         ->orderBy('mstr_department.Department_Id', 'desc')
         ->get();
        }
       }else{
         if($DepartmentId == ""){
          $select_Department_Id = DB::table('mstr_department as a')
          ->join('mstr_faculty as b','a.Faculty_Id','b.Faculty_Id')
          ->wherenotnull('a.Faculty_Id')
          ->where('a.Faculty_Id',$FacultyId)
          ->orderBy('a.Department_Id', 'desc')
          ->get();
         }else{
          $select_Department_Id = DB::table('mstr_department as a')
          ->join('mstr_faculty as b','a.Faculty_Id','b.Faculty_Id')
          ->wherenotnull('a.Faculty_Id')
          ->where('a.Faculty_Id',$FacultyId)
          ->where('a.Department_Id',$DepartmentId)
          ->orderBy('a.Department_Id', 'desc')
          ->get();
         }
       }
      

      if($FacultyId==""){
        // if($DepartmentId == ""){
          // $dosen_mengajar = $dosen_mengajar;
        // }else{
          // $dosen_mengajar = $dosen_mengajar;
          // $dosen_mengajar = $dosen_mengajar->where('oci.Department_Id',$DepartmentId);
        // }
        $data = DB::table('emp_lecturer_work_load as a')
        ->join('mstr_term_year as b', 'a.Term_Year_Id','=','b.Term_Year_Id')
        ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
        ->orderBy('a.Term_Year_Id', 'desc')
        ->where('a.Term_Year_Id' ,$term_year);
        // ->wherein('a.Employee_Id' ,$dosen_mengajar);

        if ($search == null) {
          $data = $data->paginate($rowpage);
        }else{
          $data = $data->whereRaw("lower(c.Name) like '%" . strtolower($search) . "%'")->paginate($rowpage);
        }

      }else{
        $dosen_mengajar = DB::table('acd_offered_course_lecturer as aocl')
        ->join('acd_offered_course as oci','aocl.Offered_Course_id','=','oci.Offered_Course_id')
        ->join('mstr_department as md','oci.Department_Id','=','md.Department_Id')
        ->where('oci.Term_Year_Id' ,$term_year)
        ->select('Employee_Id');
        if($DepartmentId == ""){
          $dosen_mengajar = $dosen_mengajar->where('md.Faculty_Id',$FacultyId);
        }else{
          $dosen_mengajar = $dosen_mengajar->where('md.Faculty_Id',$FacultyId)->where('oci.Department_Id',$DepartmentId);          
        }

        $data = DB::table('emp_lecturer_work_load as a')
        ->join('mstr_term_year as b', 'a.Term_Year_Id','=','b.Term_Year_Id')
        ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
        ->orderBy('a.Term_Year_Id', 'desc')
        ->where('a.Term_Year_Id' ,$term_year)
        ->wherein('a.Employee_Id' ,$dosen_mengajar);

        if ($search == null) {
          $data = $data->paginate($rowpage);
        }else{
          $data = $data->whereRaw("lower(c.Name) like '%" . strtolower($search) . "%'")->paginate($rowpage);
        }
      }
       

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department,'term_year'=>$term_year]);
       return view('emp_bebanmengajar/index')
       ->with('select_Department_Id', $select_Department_Id)
       ->with('department', $department)
       ->with('term_year', $term_year)
       ->with('term_year', $term_year)
       ->with('select_term_year', $select_term_year)
       ->with('query',$data)
       ->with('search',$search)
       ->with('rowpage',$rowpage);

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
       $term_year = Input::get('term_year');
       $FacultyId = Auth::user()->Faculty_Id;

      $grade_department = DB::table('acd_grade_department')
      ->where('acd_grade_department.department_id', $department)->select('Grade_Letter_Id');

      $mstr_department = DB::table('mstr_department')
      ->wherenotnull('Faculty_Id')
      ->where('Department_Id', $department)->get();

      $mstr_term_year = DB::table('mstr_term_year')
      ->where('Term_Year_Id', $term_year)->first();

      $emp_structural = DB::table('emp_structural')
      ->get();

      $year = DB::table('emp_lecturer_work_load')->select('Term_Year_Id');

      $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Id', 'desc')
       //->wherenotin('Term_Year_Id',$year)
       ->get();

       $select_grade_letter = DB::table('acd_grade_letter')->WhereNotIn('Grade_Letter_Id', $grade_department)->get();
       return view('emp_bebanmengajar/create')
       ->with('department', $department)
       ->with('mstr_department', $mstr_department)
       ->with('select_grade_letter', $select_grade_letter)
       ->with('select_term_year', $select_term_year)
       ->with('search',$search)
       ->with('page', $page)
       ->with('term_year', $term_year)
       ->with('mstr_term_year', $mstr_term_year)
       ->with('emp_structural', $emp_structural)
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
       $this->validate($request,[
         'Term_Year_Id'=>'required',
         'Weight_Value'=>'required',


       ]);
             // $Functional_Position_Course_Code  = Input::get('Functional_Position_Course_Code');
      $term_year = Input::get('Term_Year_Id');
      $Beban = Input::get('Weight_Value');

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

      $role_user = DB::table('_role_user')
      ->join('_role','_role_user.role_id','=','_role.id')
      ->join('_user','_role_user.user_id','=','_user.id')
      ->join('emp_employee','_user.email','=','emp_employee.Email_Corporate')
      ->where([['app','Kepegawaian'],['_role.id','39']])
      ->get();
      // dd($role_user);

      $employee = DB::table('emp_employee')
      ->select('Employee_Id','Name')
      ->get();

      //  dd($request->all());

      if($request->Jabatan == 0){
        $pengajar = DB::select("
          SELECT e.Employee_Id 
          FROM emp_employee e 
          JOIN  emp_employee_golru eg 
          ON e.Employee_Id=eg.Employee_Id 
          AND eg.tmt_date IN (SELECT max(tmt_date) FROM emp_employee_golru WHERE employee_id=e.Employee_Id AND tmt_date IS NOT NULL ORDER BY tmt_date) 
          WHERE tmt_date<NOW()
          AND eg.Status_Id IN (14,25)
          AND e.employee_id NOT IN (SELECT employee_id FROM emp_employee_structural WHERE start_date<NOW()
       AND end_date > NOW())
          ");
      }elseif($request->Jabatan == 1){
        $pengajar = DB::select("
          SELECT e.Employee_Id 
          FROM emp_employee e 
          JOIN  emp_employee_golru eg 
          ON e.Employee_Id=eg.Employee_Id 
          AND eg.tmt_date IN (SELECT max(tmt_date) FROM emp_employee_golru WHERE employee_id=e.Employee_Id AND tmt_date IS NOT NULL ORDER BY tmt_date) 
          WHERE tmt_date<NOW()
          AND eg.Status_Id IN (13,15,19,20)
          AND e.employee_id NOT IN (SELECT employee_id FROM emp_employee_structural WHERE start_date<NOW()
       AND end_date > NOW())
          ");
      }else{
        $pengajar = DB::select("
          SELECT e.Employee_Id
          FROM emp_employee e
          JOIN  emp_employee_golru eg
          ON e.Employee_Id=eg.Employee_Id
          AND eg.tmt_date IN (SELECT max(tmt_date) FROM emp_employee_golru WHERE employee_id=e.Employee_Id AND tmt_date IS NOT NULL ORDER BY tmt_date desc)
          JOIN emp_employee_structural es
          ON es.Employee_Id=eg.Employee_Id
          AND es.Start_Date IN (SELECT max(Start_Date) FROM emp_employee_structural WHERE employee_id=e.Employee_Id )
          WHERE tmt_date<NOW()
          AND es.Start_Date <NOW()
          AND es.End_Date > NOW()
          AND es.Structural_Id = $request->Jabatan
          ");
      }
      // dd($request->all(),$pengajar);

      try {
        foreach ($pengajar as $key) {
          $cekdata = DB::table('emp_lecturer_work_load')->where('Term_Year_Id',$term_year)->where('Employee_Id',$key->Employee_Id)->count();
          if($cekdata > 0){
            $u =  DB::table('emp_lecturer_work_load')
            ->where([['Term_Year_Id',$term_year],['Employee_Id',$key->Employee_Id]])
            ->update([
              'Sks' => $Beban, 
              'Modified_By' => auth()->user()->email,
              'Modified_Date' => date('Y-m-d H:i:s')
              ]);
          }else{
            // dd($pengajar);
            $u =  DB::table('emp_lecturer_work_load')
            ->insert([
              'Employee_Id' => $key->Employee_Id, 
              'Term_Year_Id' => $term_year, 
              'Sks' => $Beban, 
              'Created_By' => auth()->user()->email,
              'Created_Date' => date('Y-m-d H:i:s')
              ]);            
            }
          }
           return Redirect::to('/parameter/beban_mengajar?term_year='.$term_year)->withErrors('Berhasil Menambah Beban Mengajar');
         } catch (\Exception $e) {
           return Redirect::back()->withErrors('Gagal Menambah Beban Mengajar');
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
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $data = DB::table('emp_lecturer_work_load as a')->join('emp_employee as  b','a.Employee_Id','=','b.Employee_Id')
       ->where('a.Lecturer_Work_Load_Id', $id)
       ->get();
       return view('emp_bebanmengajar/edit')
       ->with('query_edit',$data)
       ->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
       $this->validate($request,[
         'Beban_Mengajar'=>'required',


       ]);
             $Sks = Input::get('Beban_Mengajar');

             try {
               $u =  DB::table('emp_lecturer_work_load')
               ->where('Lecturer_Work_Load_Id',$id)
               ->update(
                 ['Sks' => $Sks,'Modified_By' => auth()->user()->email,'Modified_Date' => date('Y-m-d H:i:s')]);
               return Redirect::to('/parameter/beban_mengajar')->withErrors('Berhasil Menyimpan Perubahan');
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
         $q=DB::table('emp_lecturer_work_load')->where('Lecturer_Work_Load_Id', $id)->delete();
        echo json_encode($q);
     }

 }
