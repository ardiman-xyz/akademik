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

class Curriculum_appliedController extends Controller
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

       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');

        if($FacultyId==""){
          if($DepartmentId == ""){
            $select_Department_Id = DB::table('mstr_department')
            ->orderBy('mstr_department.Department_Id', 'desc')
            ->wherenotnull('Faculty_Id')    
            ->get();
            if ($search == null) {
              $data = DB::table('mstr_curriculum_applied')
              ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')

              ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
              ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')
              ->where('mstr_curriculum_applied.Department_Id', $department)
              ->orderBy('mstr_curriculum.Curriculum_Name', 'asc')
              ->paginate($rowpage);
            }else {
              $data = DB::table('mstr_curriculum_applied')
              ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')
              ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
              ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')      
              ->where('mstr_curriculum_applied.Department_Id', $department)
              ->where(function($query){
                $search = Input::get('search');
                $query->whereRaw("lower(mstr_curriculum.Curriculum_Name) like '%" . strtolower($search) . "%'");
                $query->orwhere('mstr_class_program.Class_Program_Name', 'LIKE', '%'.$search.'%');
              })
              ->orderBy('mstr_curriculum.Curriculum_Name', 'asc')
              ->paginate($rowpage);
            }
          }else{
            $select_Department_Id = DB::table('mstr_department')
            ->orderBy('mstr_department.Department_Id', 'desc')
            ->wherenotnull('Faculty_Id')
            ->where('mstr_department.Department_Id',$DepartmentId)
            ->get();

            if ($search == null) {
              $data = DB::table('mstr_curriculum_applied')
              ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')

              ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
              ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')
              ->where('mstr_curriculum_applied.Department_Id', $department)
              ->orderBy('mstr_curriculum.Curriculum_Name', 'asc')
              ->paginate($rowpage);
            }else {
              $data = DB::table('mstr_curriculum_applied')
              ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')
              ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
              ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')

              ->where('mstr_curriculum_applied.Department_Id', $department)
              ->where(function($query){
                $search = Input::get('search');
                $query->whereRaw("lower(mstr_curriculum.Curriculum_Name) like '%" . strtolower($search) . "%'");
                $query->orwhere('mstr_class_program.Class_Program_Name', 'LIKE', '%'.$search.'%');
              })
              ->orderBy('mstr_curriculum.Curriculum_Name', 'asc')
              ->paginate($rowpage);
            }
          }
        }else{
          if($DepartmentId == ""){
            $select_Department_Id = DB::table('mstr_department')
            ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
            ->where('mstr_faculty.Faculty_Id', $FacultyId)
            ->orderBy('mstr_department.Department_Id', 'desc')
            ->get();

            if ($search == null) {
              $data = DB::table('mstr_curriculum_applied')
              ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')
              ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
              ->where('mstr_faculty.Faculty_Id', $FacultyId)
              ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
              ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')
              ->where('mstr_curriculum_applied.Department_Id', $department)
              ->orderBy('mstr_curriculum.Curriculum_Name', 'asc')
              ->paginate($rowpage);
            }else {
              $data = DB::table('mstr_curriculum_applied')
              ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')
              ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
              ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')
              ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
              ->where('mstr_faculty.Faculty_Id', $FacultyId)
              ->where('mstr_curriculum_applied.Department_Id', $department)
              ->where(function($query){
                $search = Input::get('search');
                $query->whereRaw("lower(mstr_curriculum.Curriculum_Name) like '%" . strtolower($search) . "%'");
                $query->orwhere('mstr_class_program.Class_Program_Name', 'LIKE', '%'.$search.'%');
              })
              ->orderBy('mstr_curriculum.Curriculum_Name', 'asc')
              ->paginate($rowpage);
            }
          }else{
            $select_Department_Id = DB::table('mstr_department')
            ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
            ->where('mstr_department.Department_Id',$DepartmentId)
            ->where('mstr_faculty.Faculty_Id', $FacultyId)
            ->orderBy('mstr_department.Department_Id', 'desc')
            ->get();

            if ($search == null) {
              $data = DB::table('mstr_curriculum_applied')
              ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')
              ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
              ->where('mstr_faculty.Faculty_Id', $FacultyId)
              ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
              ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')
              ->where('mstr_curriculum_applied.Department_Id', $department)
              ->orderBy('mstr_curriculum.Curriculum_Name', 'asc')
              ->paginate($rowpage);
            }else {
              $data = DB::table('mstr_curriculum_applied')
              ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')
              ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
              ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')
              ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
              ->where('mstr_faculty.Faculty_Id', $FacultyId)
              ->where('mstr_curriculum_applied.Department_Id', $department)
              ->where(function($query){
                $search = Input::get('search');
                $query->whereRaw("lower(mstr_curriculum.Curriculum_Name) like '%" . strtolower($search) . "%'");
                $query->orwhere('mstr_class_program.Class_Program_Name', 'LIKE', '%'.$search.'%');
              })
              ->orderBy('mstr_curriculum.Curriculum_Name', 'asc')
              ->paginate($rowpage);
            }
          }
        }
        
      $select_Department_Id = GetDepartment::getDepartment();

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
       return view('mstr_curriculum_applied/index')->with('select_Department_Id', $select_Department_Id)->with('department', $department)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
       $FacultyId = Auth::user()->Faculty_Id;
       $department = Input::get('department');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');

      //  if($FacultyId==""){
         $mstr_department = DB::table('mstr_department')
         ->where('Department_Id', $department)->get();
      //  }else{
      //    $mstr_department = DB::table('mstr_department')
      //    ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      //    ->where('mstr_faculty.Faculty_Id', $FacultyId)
      //    ->where('Department_Id', $department)->get();
      //  }

       $select_curriculum = DB::table('mstr_curriculum')->get();
       $select_class_program = DB::table('mstr_class_program')->get();
       return view('mstr_curriculum_applied/create')->with('department', $department)->with('mstr_department', $mstr_department)->with('select_curriculum', $select_curriculum)->with('select_class_program', $select_class_program)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Curriculum_Id'=>'required',
         'Class_Prog_Id'=>'required',


       ]);
             // $Functional_Position_Curriculum_Id  = Input::get('Functional_Position_Curriculum_Id');
             $Department_Id = Input::get('Department_Id');
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Curriculum_Id = Input::get('Curriculum_Id');
             $Total_Sks_Core = Input::get('Total_Sks_Core');
             $Total_Sks_Elective = Input::get('Total_Sks_Elective');
             $Min_Cum_Gpa = Input::get('Min_Cum_Gpa');
             $Sks_Completion = Input::get('Sks_Completion');


try{
       $u =  DB::table('mstr_curriculum_applied')
       ->insert(
       ['Department_Id' => $Department_Id, 'Curriculum_Id' => $Curriculum_Id, 'Class_Prog_Id' => $Class_Prog_Id, 'Total_Sks_Core' => $Total_Sks_Core, 'Total_Sks_Elective' => $Total_Sks_Elective, 'Min_Cum_Gpa' => $Min_Cum_Gpa, 'Sks_Completion' => $Sks_Completion]);
       return Redirect::back()->withErrors('Berhasil Menambah Kurikulum Prodi');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Kurikulum Prodi');
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
       $FacultyId = Auth::user()->Faculty_Id;

       if($FacultyId==""){
         $data = DB::table('mstr_curriculum_applied')
         ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')
         ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
         ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')

         ->where('mstr_curriculum_applied.Curiculum_Applied_Id', $id)
         ->get();
       }else{
         $data = DB::table('mstr_curriculum_applied')
         ->join('mstr_department', 'mstr_department.Department_Id','=','mstr_curriculum_applied.Department_Id')
         ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id','=','mstr_curriculum_applied.Class_Prog_Id')
         ->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','mstr_curriculum_applied.Curriculum_Id')
         ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
         ->where('mstr_faculty.Faculty_Id', $FacultyId)
         ->where('mstr_curriculum_applied.Curiculum_Applied_Id', $id)
         ->get();
       }

       $select_curriculum = DB::table('mstr_curriculum')->get();
       $select_class_program = DB::table('mstr_class_program')->get();
       return view('mstr_curriculum_applied/edit')->with('query_edit',$data)->with('select_curriculum', $select_curriculum)->with('select_class_program', $select_class_program)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Curriculum_Id'=>'required',
         'Class_Prog_Id'=>'required',


       ]);
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Curriculum_Id = Input::get('Curriculum_Id');
             $Total_Sks_Core = Input::get('Total_Sks_Core');
             $Total_Sks_Elective = Input::get('Total_Sks_Elective');
             $Min_Cum_Gpa = Input::get('Min_Cum_Gpa');
             $Sks_Completion = Input::get('Sks_Completion');

             try {
               $u =  DB::table('mstr_curriculum_applied')
               ->where('Curiculum_Applied_Id',$id)
               ->update(
                 ['Curriculum_Id' => $Curriculum_Id, 'Class_Prog_Id' => $Class_Prog_Id, 'Total_Sks_Core' => $Total_Sks_Core, 'Total_Sks_Elective' => $Total_Sks_Elective, 'Min_Cum_Gpa' => $Min_Cum_Gpa, 'Sks_Completion' => $Sks_Completion]);
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
         $q=DB::table('mstr_curriculum_applied')->where('Curiculum_Applied_Id', $id)->delete();
         echo json_encode($q);
     }
 }
