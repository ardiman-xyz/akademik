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

class Offered_courseController extends Controller
{

    public function __construct()
    {
      $this->middleware('access:CanView', ['only' => ['index']]);
      $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','update_capacity','edit_capacity','update_employee','edit_employee','destroy_employee','copydata','storecopydata','update_datacourse']]);
      $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','update_capacity','edit_capacity','update_employee','edit_employee','destroy_employee','copydata','storecopydata','update_datacourse']]);
      $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','update_capacity','edit_capacity','update_employee','edit_employee','destroy_employee','copydata','storecopydata','update_datacourse']]);
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
       $semester = Input::get('semester');
       $page = Input::get('page');
       $FacultyId = Auth::user()->Faculty_Id;
       $curriculum = Input::get('curriculum');
       $DepartmentId = Auth::user()->Department_Id;


       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }
       $select_semester = DB::table('mstr_study_level')
       ->orderBy('mstr_study_level.Study_Level_Code', 'asc')
       ->get();
       $select_curriculum = DB::table('mstr_curriculum')
       ->orderBy('mstr_curriculum.Curriculum_Name', 'desc')
       ->get();

       $select_department = GetDepartment::getDepartment();

      $select_class_program = DB::table('mstr_department_class_program')
      ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
      ->where('mstr_department_class_program.Department_Id', $department)
      ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
      ->get();
      
        $data = DB::table('acd_offered_course')
        ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
        ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
        ->where('acd_offered_course.Department_Id', $department)
        ->where('acd_offered_course.Class_Prog_Id', $class_program)
        ->where('acd_offered_course.Term_Year_Id', $term_year)
        // ->where('acd_offered_course.Curriculum_Id',$curriculum)
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
          $query->orwhereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
        })
        ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
        DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'),
        DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as id_dosen'))
        ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
        ->orderBy('acd_course.Course_Name', 'asc')
        ->orderBy('acd_offered_course.Class_Id', 'asc')
        ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
        // dd($data->get());
        ->paginate($rowpage);


       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
       ->get();

       $jatahbeba = DB::table('emp_lecturer_work_load')->where('Term_Year_Id', $term_year)->count();
       if($jatahbeba > 0){
        $jatahbeban = $jatahbeba;
       }else{
        $jatahbeban = 0;
       }


       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program, 'curriculum'=> $curriculum, 'term_year'=> $term_year, 'department'=> $department]);
       return view('acd_offered_course/index')
       ->with('query',$data)
       ->with('select_curriculum', $select_curriculum)
       ->with('jatahbeban', $jatahbeban)
       ->with('curriculum', $curriculum)->with('semester',$semester)->with('select_semester',$select_semester)->with('search',$search)->with('page',$page)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year);
     }
     // public function modal()
     // {
     //   return view('mstr_term_year/modal');
     // }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         $search = Input::get('search');
         $rowpage = Input::get('rowpage');
         if ($rowpage == null) {
           $rowpage = 10;
         }

         $current_search = Input::get('current_search');
         $current_page = Input::get('current_page');
         $current_rowpage = Input::get('current_rowpage');
         $department = Input::get('department');
         $class_program = Input::get('class_program');
         $term_year = Input::get('term_year');
         $Course_Id = Input::get('course');
         $curriculum = Input::get('curriculum');
         $FacultyId = Auth::user()->Faculty_Id;
         $Class_Id = Input::get('Class_Id');

  $mstr_department = DB::table('mstr_department')->where('Department_Id', $department)->get();

    $notoffer = DB::table('acd_offered_course')
      ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
      ->leftjoin('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
      ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
      ->leftjoin('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
      // ->where('acd_offered_course.Curriculum_Id', $curriculum)
      ->where('acd_offered_course.Department_Id', $department)
      ->where('acd_offered_course.Class_Prog_Id', $class_program)
      ->where('acd_offered_course.Term_Year_Id', $term_year)
      ->where('acd_offered_course.Class_Id', $Class_Id)
      ->select('acd_offered_course.Course_Id');

      // dd($notoffer->get());

  $mstr_course = DB::table('acd_course')
  // ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_course.Course_Id')
  ->join('acd_course_curriculum',function($aa)use($curriculum,$class_program){
    $aa->on('acd_course_curriculum.Course_Id','=','acd_course.Course_Id')
    // ->where('acd_course_curriculum.Curriculum_Id','=', $curriculum)
    ->where('acd_course_curriculum.Class_Prog_Id','=', $class_program);
  })
  // ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
  ->where('Applied_Sks','!=',null)
  ->where('acd_course.Department_Id', $department)
  ->wherenotin('acd_course.Course_Id', $notoffer)
  ->groupby('acd_course.Course_Id')
  ->get();

  // dd($mstr_course);

  $data2 =   $data = DB::table('acd_course_curriculum')
  // ->join('acd_course_curriculum' ,function ($join)
  //   {
  //     $join->on('acd_course_curriculum.Curriculum_Id','=','acd_offered_course.Curriculum_Id')
  //     ->on('acd_course_curriculum.Department_Id','=','acd_offered_course.Department_Id')
  //     ->on('acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id');
  //   })
  ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_course_curriculum.Class_Prog_Id')
  ->leftjoin('acd_course','acd_course.Course_Id','=','acd_course_curriculum.Course_Id')
  // ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
  ->where('acd_course_curriculum.Department_Id', $department)
  ->where('acd_course_curriculum.Class_Prog_Id', $class_program);
  
  $data = DB::table('acd_offered_course')
  // ->join('acd_course_curriculum' ,function ($join) use($class_program)
  //   {
  //     $join->on('acd_course_curriculum.Curriculum_Id','=','acd_offered_course.Curriculum_Id')
  //     ->on('acd_course_curriculum.Department_Id','=','acd_offered_course.Department_Id')
  //     ->on('acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id');
  //   })
  ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
  ->leftjoin('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
  ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
  ->leftjoin('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
  // ->where('acd_offered_course.Curriculum_Id', $curriculum)  
  ->where('acd_offered_course.Department_Id', $department)
  ->where('acd_offered_course.Class_Prog_Id', $class_program)  
  ->where('acd_offered_course.Term_Year_Id', $term_year)
  ->where('acd_offered_course.Course_Id', $Course_Id);

  
  // dd($data);
  $mstr_term_year = DB::table('mstr_term_year')->where('Term_Year_Id', $term_year)->get();
  $mstr_class_program = DB::table('mstr_class_program')->where('Class_Prog_Id', $class_program)->get();
  
  
  
  $dat = $data->orderBy('mstr_class.Class_Name')->groupby('mstr_class.Class_Id')->get();
  
  $class = $data->select('acd_offered_course.Class_Id');
  
  $mstr_class = DB::table('mstr_class')->whereNotIn('Class_Id', $class)->get();



         return view('acd_offered_course/create')->with('query', $dat)
         ->with('curriculum',$curriculum)
         ->with('mstr_department', $mstr_department)
         ->with('mstr_term_year', $mstr_term_year)
         ->with('mstr_class_program', $mstr_class_program)
         ->with('mstr_course', $mstr_course)
         ->with('mstr_class', $mstr_class)
         ->with('class_program', $class_program)
         ->with('department', $department)
         ->with('term_year', $term_year)
         ->with('course_id', $Course_Id)
         ->with('Class_Id', $Class_Id)
         ->with('search',$search)
         ->with('rowpage', $rowpage)
         ->with('current_page', $current_page)
         ->with('current_rowpage', $current_rowpage)->with('current_search', $current_search);
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */

     public function update_datacourse(Request $request){
       $department = $request->department;
       $class_program = $request->class_program;
       $term_year = $request->term_year;
      $class_id = $request->class_id;
      $kapasitas = $request->kapasitas;
      // $curriculum = $request->curriculum;
      $course_id = $request->course_id;

      $i = 0;
      foreach ($course_id as $key) {
        $data =[
          'Department_Id'=>$department,
          'Class_Prog_Id'=>$class_program,
          'Term_Year_Id'=>$term_year,
          'Course_Id'=>$key,
          'Class_Id'=>$class_id,
          'Class_Capacity'=>$kapasitas,
        ];

        $scheck = DB::table('acd_offered_course')->where([
          ['Department_Id',$department],
          ['Class_Prog_Id',$class_program],
          ['Term_Year_Id',$term_year],
          ['Course_Id',$key],
          ['Class_Id',$class_id]
          ])->get();
        if(count($scheck) == 0){
          $insert = DB::table('acd_offered_course')->insert($data);
        }

        $i++;
      }

      return response()->json([
                'status' => 304,
                'message' => 'Data Telah Ditambahkan',
                'data' => $i,
            ]);
     }
     public function store(Request $request)
     {
       $this->validate($request,[
         'Class_Prog_Id'=>'required',
         'Department_Id' => 'required',
         'Term_Year_Id' => 'required',
         'Course_Id' => 'required',
         'Class_Id' => 'required'
       ]);
             $Department_Id = Input::get('Department_Id');
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Term_Year_Id = Input::get('Term_Year_Id');
             $Course_Id = Input::get('Course_Id');
             $Class_Id = Input::get('Class_Id');
             $Capacity = input::get('Capacity');
             $curriculum = Input::get('curriculum');

        foreach ($Class_Id as $data) {
         DB::table('acd_offered_course')
         ->insert(
         ['Department_Id' => $Department_Id,'Class_Prog_Id' => $Class_Prog_Id,'Term_Year_Id' => $Term_Year_Id,'Course_Id' => $Course_Id, 'Class_Id' => $data, 
         'Class_Capacity' => $Capacity,
         'Curriculum_Id'=>$curriculum,
         'Created_Date'=>date('Y-m-d'),
         'Created_By' => Auth::user()->email,
          ]);
        }

       return Redirect::back()->withErrors('Berhasil Menambah Matakuliah Ditawarkan');
     }


     public function copydata(){
        $department = Input::get('department');
         $class_program = Input::get('class_program');
         $term_year = Input::get('term_year');
         $curriculum = Input::get('curriculum');

         $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
       ->get();

       $select_department = DB::table('mstr_department')
       ->where('Department_Id',$department)
       ->first();
       $select_curriculum = DB::table('mstr_curriculum')
       ->where('Curriculum_Id',$curriculum)
       ->first();
       $select_class = DB::table('mstr_class_program')
       ->where('Class_Prog_Id',$class_program)
       ->first();

       $select_class_prog = DB::table('mstr_class_program')
      //  ->where('Class_Prog_Id','!=',$class_program)
       ->orderBy('Class_Program_Name', 'asc')
       ->get();


       return view('acd_offered_course/copydata')
       ->with('select_department',$select_department)
       ->with('select_curriculum',$select_curriculum)
       ->with('select_class',$select_class)
       ->with('department',$department)
       ->with('class_program',$class_program)
       ->with('term_year',$term_year)
       ->with('select_class_prog',$select_class_prog)
       ->with('curriculum',$curriculum)
       ->with('select_term_year',$select_term_year);
     }

     public function storecopydata(Request $request)
     {
      //  dd($request->all());
        $Department_Id = Input::get('Department_Id');
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Class_Prog_Id_Dest = Input::get('class_prog_dest');
             $Term_Year_Id = Input::get('Term_Year_Id');
             $curriculum = Input::get('curriculum');
             $term_year = Input::get('Term_Year_Id');
             $term_year_dest = Input::get('term_year_dest');

        $data = DB::table('acd_offered_course')
        ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
        ->where('acd_offered_course.Department_Id', $Department_Id)
        ->where('acd_offered_course.Class_Prog_Id', $Class_Prog_Id)
        ->where('acd_offered_course.Term_Year_Id', $term_year)
        ->groupby('acd_offered_course.Offered_Course_id')
        ->get();

        try{
          if($term_year == $term_year_dest){
            if($Class_Prog_Id_Dest != null){
              foreach ($data as $data) {
                $cekdata = DB::table('acd_offered_course')
                          ->where([
                          ['Department_Id' , $data->Department_Id],
                          ['Term_Year_Id' , $data->Term_Year_Id],
                          ['Course_Id' , $data->Course_Id],
                          ['Class_Id' , $data->Class_Id],
                          ['Class_Capacity' , $data->Class_Capacity],
                          ['Class_Prog_Id' , $Class_Prog_Id_Dest]])->count();
                if($cekdata == 0){
                  $insert = DB::table('acd_offered_course')
                  ->insert(
                  ['Department_Id' => $data->Department_Id,
                  'Class_Prog_Id' => $Class_Prog_Id_Dest,
                  'Term_Year_Id' => $data->Term_Year_Id,
                  'Course_Id' => $data->Course_Id, 
                  'Class_Id' => $data->Class_Id, 
                  'Created_Date'=>date('Y-m-d'),
                  'Created_By' => Auth::user()->email,
                  'Class_Capacity' => $data->Class_Capacity]);
                  }
                }
            }
          }else{
            if($Class_Prog_Id_Dest != null){
              foreach ($data as $data) {
                $cekdata = DB::table('acd_offered_course')
                          ->where([
                          ['Department_Id' , $data->Department_Id],
                          ['Term_Year_Id' , $term_year_dest],
                          ['Course_Id' , $data->Course_Id],
                          ['Class_Id' , $data->Class_Id],
                          ['Class_Capacity' , $data->Class_Capacity],
                          ['Class_Prog_Id' , $Class_Prog_Id_Dest]])->count();
                $lecture = DB::table('acd_offered_course_lecturer')->where('Offered_Course_id',$data->Offered_Course_id)->get();
                if($cekdata == 0){    
                $insert =  DB::table('acd_offered_course')
                      ->insert(
                      ['Department_Id' => $data->Department_Id,
                      'Class_Prog_Id' => $Class_Prog_Id_Dest,
                      'Term_Year_Id' => $term_year_dest,
                      'Course_Id' => $data->Course_Id, 
                      'Class_Id' => $data->Class_Id, 
                      'Created_Date'=>date('Y-m-d'),
                      'Created_By' => Auth::user()->email,
                      'Class_Capacity' => $data->Class_Capacity]);

                  if($insert){
                    if(count($lecture) > 0){
                      foreach ($lecture as $key_lecturer) {
                        $last_insert = DB::getPdo()->lastInsertId();
                        $inser_lecturer = DB::table('acd_offered_course_lecturer')
                          ->insert(
                          ['Offered_Course_id' => $last_insert,
                          'Employee_Id' => $key_lecturer->Employee_Id]);
                      }
                    }
                  }

                  }
                }
            }else{
              return Redirect::back()->withErrors('Pilih Program Kelas');
            }
          }        
         return Redirect::back()->withErrors('Berhasil Kopi Data');
        }catch (\Exception $e) {
          return Redirect::back()->withErrors('Gagal Kopi Data');
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
     public function edit_capacity($id)
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       $term_year = Input::get('term_year');
       $curriculum = Input::get('curriculum');



       $data = DB::table('acd_offered_course')
       ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
       ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
       ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
       ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
       ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
       ->select('acd_offered_course.*','acd_course.*','mstr_class.Class_Name')
       ->where('acd_offered_course.Offered_Course_id', $id)
       ->get();

       return view('acd_offered_course/edit_capacity')->with('query_edit', $data)->with('curriculum',$curriculum)->with('department', $department)->with('class_program', $class_program)->with('term_year', $term_year)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update_capacity(Request $request, $id)
     {
       $term_year = Input::get('term_year');
       $class_program = Input::get('class_program');
       $department = Input::get('department');
       $curriculum = Input::get('curriculum');
       $this->validate($request,[
         'Class_Capacity' => 'required|numeric',
       ],['Class_Capacity.numeric' => 'Kapasitas harus berupa angka']);
             $Class_Capacity = Input::get('Class_Capacity');
             try {

                DB::table('acd_offered_course')
                ->where('Offered_Course_id', $id)
                ->update([
                  'Class_Capacity' => $Class_Capacity,
                  'Modified_Date'=>date('Y-m-d'),
                  'Modified_By' => Auth::user()->email,
                ]);

               return Redirect::to('setting/offered_course?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum)->withErrors('Berhasil Menyimpan Perubahan');
             } catch (\Exception $e) {
               return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
             }
     }




     public function edit_employee($id)
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       $term_year = Input::get('term_year');
       $curriculum = Input::get('curriculum');

       $select_all_emp = DB::select("SELECT e.Employee_Id 
          FROM emp_employee e 
          JOIN  emp_employee_golru eg 
          ON e.Employee_Id=eg.Employee_Id 
          AND eg.tmt_date IN (SELECT max(tmt_date) FROM emp_employee_golru WHERE employee_id=e.Employee_Id AND tmt_date IS NOT NULL ORDER BY tmt_date) 
          WHERE tmt_date<NOW()
          AND eg.Status_Id IN (13,15,19,20,14,25)
          AND e.employee_id NOT IN (SELECT employee_id FROM emp_employee_structural WHERE start_date<NOW()
        AND end_date > NOW())");

        $select_all = [];
        foreach ($select_all_emp as $select_all_emps) {
          // dd($select_all_emps->Employee_Id);
          $select_all[] = $select_all_emps->Employee_Id;
        }

       $data = DB::table('acd_offered_course')
       ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
       ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
       ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
       ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
       ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
       ->leftjoin('acd_course_curriculum as acc' ,function ($join)
        {
          $join->on('acc.Department_Id','=','acd_offered_course.Department_Id')
          ->on('acc.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->on('acc.Course_Id','=','acd_offered_course.Course_Id');
        })
       ->select('acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acc.Applied_Sks')
       ->where('acd_offered_course.Offered_Course_id', $id)
       ->groupby('acd_offered_course.Offered_Course_id')
       ->get();



       $lecture = DB::table('acd_offered_course_lecturer')
        ->leftjoin('emp_employee', 'emp_employee.Employee_Id', '=', 'acd_offered_course_lecturer.Employee_Id')
        ->where('Offered_Course_id', $id);

        $lecturer = $lecture->orderBy('acd_offered_course_lecturer.Order_Id', 'asc')->get();

        $employee = $lecture->select('acd_offered_course_lecturer.Employee_Id');

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

        $select_employee = DB::table('acd_department_lecturer')
          ->join('emp_employee' ,'emp_employee.Employee_Id' ,'=' ,'acd_department_lecturer.Employee_Id')
          ->where('acd_department_lecturer.Department_Id', $department)
          ->whereNotIn('emp_employee.Employee_Id', $employee)
          // ->whereIn('Email_Corporate',$email_user)
          ->get();

        $select_employee = DB::table('emp_employee')
        ->join(DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"), 'emp_employee.Employee_Id', 'max_placement.Employee_Id'
        )
        ->join('emp_placement',function($golru){
            $golru->on('emp_placement.Employee_Id','emp_employee.Employee_Id')
            ->on('emp_placement.Tmt_Date','max_placement.Tmt_Date');
        })
        ->where('emp_placement.Department_Id', $department)
        ->whereNotIn('emp_employee.Employee_Id', $employee)
        ->get();
        // dd($select_employee);

        $new_employes = [];
        $i = 0;
        foreach ($select_employee as $key) {
          $golru = DB::table('emp_employee_golru')->where('Employee_Id',$key->Employee_Id)->orderby('Tmt_Date','Desc')->first();
          if(isset($golru->Status_Id)){
            if(in_array($golru->Status_Id,[21, 22, 23, 24])){
              continue;
            }
          }
          // dd($key);
          //jumlah sks matakuliah 
          $sks_matkul = $data[0]->Applied_Sks;

          //jatah sks dosen
          $Jatahsks = 0;
          $Jatahskss = DB::table('emp_lecturer_work_load')->where('Term_Year_Id', $term_year)->where('Employee_Id',$key->Employee_Id)->first();
          if($Jatahskss){
            $Jatahsks = $Jatahskss->Sks;
          }

          //jumlah dosen matakuliah ini
          $count_dosen_matakuliah = DB::table('acd_offered_course_lecturer as aocl')->where('Offered_Course_id',$id)->count();

          //beban tiap dosen dropdown
          $bebanskss = DB::table('acd_offered_course_lecturer as a')
          ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
          ->leftjoin('acd_course_curriculum as c' ,function ($join)
            {
              $join->on('c.Department_Id','=','b.Department_Id')
              ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
              ->on('c.Course_Id','=','b.Course_Id');
            })
          ->where('a.Employee_Id',$key->Employee_Id)
          ->where('b.Term_Year_Id', $term_year)
          // ->select(DB::raw('(SUM(c.Applied_Sks)) as beban_sks'))
          ->get();
          // dd($bebanskss);
          // ->first();

          $sum_beban_sks_dosen = 0;
          foreach ($bebanskss as $bebansk) {
            // $cd = DB::table('acd_offered_course_lecturer as aocl')->join('acd_offered_course as aoc','aoc.Offered_Course_id','=','aocl.Offered_Course_id')->where('aocl.Offered_Course_id',$bebansk->Offered_Course_id)->get();
            $cd = DB::table('acd_offered_course_lecturer as a')
              ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
              ->leftjoin('acd_course_curriculum as c' ,function ($join)
                {
                  $join->on('c.Department_Id','=','b.Department_Id')
                  ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
                  ->on('c.Course_Id','=','b.Course_Id');
                })
              // ->where('a.Employee_Id',$key->Employee_Id)
              ->where('b.Term_Year_Id', $term_year)
              ->where('a.Offered_Course_id',$bebansk->Offered_Course_id)
              ->get();
              // dd($cd);
            if(count($cd) > 0){
              // $sum_beban_sks_dosen = $sum_beban_sks_dosen+ ($cbebansk->Applied_Sksd[0]->Applied_Sks / count($cd));
              $sum_beban_sks_dosen = $sum_beban_sks_dosen+ ($bebansk->Applied_Sks / count($cd));
            }else{
              $sum_beban_sks_dosen = $sum_beban_sks_dosen;              
            }
            // dd($bebanskss,$bebansk,$cd);
          }
          // dd($bebanskss,$sum_beban_sks_dosen);
          
          // dd($bebansks);

          $new_employes[$i]['Employee_Id'] = $key->Employee_Id;
          $new_employes[$i]['Full_Name'] = $key->Full_Name;
          $new_employes[$i]['Jatah_Sks_Dosen'] = $Jatahsks;
          $new_employes[$i]['Beban_Sks_Matakuliah'] = $sks_matkul;
          $new_employes[$i]['Beban_Sks_Dosen'] = $sum_beban_sks_dosen;
          $new_employes[$i]['Sisa_Sks_Dosen'] = $Jatahsks - $sum_beban_sks_dosen;
          if(!$Jatahskss){
            $new_employes[$i]['Sisa_Sks_Dosen'] = 'Beban Sks Dosen Belum diset';
          }
          $i++;
        }
        // dd($new_employes);

        $select_employee2 = DB::table('acd_department_lecturer')
          ->join('emp_employee' ,'emp_employee.Employee_Id' ,'=' ,'acd_department_lecturer.Employee_Id')
          ->where('acd_department_lecturer.Department_Id', '!=', $department)
          ->whereNotIn('emp_employee.Employee_Id', $employee)
          // ->whereIn('Email_Corporate',$email_user)
          ->groupBy('emp_employee.Employee_Id')
          ->get();

        $select_employee2 = DB::table('emp_employee')
        ->join(DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"), 'emp_employee.Employee_Id', 'max_placement.Employee_Id'
        )
        ->join('emp_placement',function($golru){
            $golru->on('emp_placement.Employee_Id','emp_employee.Employee_Id')
            ->on('emp_placement.Tmt_Date','max_placement.Tmt_Date');
        })
        ->where('emp_placement.Department_Id', '!=' ,$department)
        // ->whereNotIn('emp_employee.Status_Id',[21, 22, 23, 24])
        ->whereNotIn('emp_employee.Employee_Id', $employee)
        ->get();
        // dd(array(1,2,3));

        $new_employes2 = [];
        $i2 = 0;
        foreach ($select_employee2 as $key) {
          $golru = DB::table('emp_employee_golru')->where('Employee_Id',$key->Employee_Id)->orderby('Tmt_Date','Desc')->first();
          if(!isset($golru->Status_Id)){
            continue;
          }
          if(in_array($golru->Status_Id,[21, 22, 23, 24])){
            continue;
          }
          // dd(2);
          //jumlah sks matakuliah 
          $sks_matkul = $data[0]->Applied_Sks;

          //jatah sks dosen
          $Jatahsks = DB::table('emp_lecturer_work_load')->where('Term_Year_Id', $term_year)->where('Employee_Id',$key->Employee_Id)->first();

          //jumlah dosen matakuliah ini
          $count_dosen_matakuliah = DB::table('acd_offered_course_lecturer as aocl')->where('Offered_Course_id',$id)->count();

          //beban tiap dosen dropdown
          $bebanskss = DB::table('acd_offered_course_lecturer as a')
          ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
          ->leftjoin('acd_course_curriculum as c' ,function ($join)
            {
              $join->on('c.Department_Id','=','b.Department_Id')
              ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
              ->on('c.Course_Id','=','b.Course_Id');
            })
          ->where('a.Employee_Id',$key->Employee_Id)
          ->where('b.Term_Year_Id', $term_year)
          // ->select(DB::raw('(SUM(c.Applied_Sks)) as beban_sks'))
          ->get();
          // ->first();

          $sum_beban_sks_dosen = 0;
          foreach ($bebanskss as $bebansk) {
            // $cd = DB::table('acd_offered_course_lecturer as aocl')->join('acd_offered_course as aoc','aoc.Offered_Course_id','=','aocl.Offered_Course_id')->where('aocl.Offered_Course_id',$bebansk->Offered_Course_id)->get();
            $cd = DB::table('acd_offered_course_lecturer as a')
              ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
              ->leftjoin('acd_course_curriculum as c' ,function ($join)
                {
                  $join->on('c.Department_Id','=','b.Department_Id')
                  ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
                  ->on('c.Course_Id','=','b.Course_Id');
                })
              // ->where('a.Employee_Id',$key->Employee_Id)
              ->where('b.Term_Year_Id', $term_year)
              ->where('a.Offered_Course_id',$bebansk->Offered_Course_id)
              ->get();
              // dd($bebanskss,$bebansk);
            if(count($cd) > 0){
              $sum_beban_sks_dosen = $sum_beban_sks_dosen + ($bebansk->Applied_Sks / count($cd));
              // dd($sum_beban_sks_dosen);
            }else{
              $sum_beban_sks_dosen = $sum_beban_sks_dosen;              
            }
            // dd($bebanskss,$bebansk,$cd);
          }
          // dd($bebanskss,$sum_beban_sks_dosen);
          
          // dd($bebansks);

          $new_employes2[$i2]['Employee_Id'] = $key->Employee_Id;
          $new_employes2[$i2]['Full_Name'] = $key->Full_Name;
          $new_employes2[$i2]['Jatah_Sks_Dosen'] = ($Jatahsks ? $Jatahsks->Sks:'belum diset');
          $new_employes2[$i2]['Beban_Sks_Matakuliah'] = $sks_matkul;
          $new_employes2[$i2]['Beban_Sks_Dosen'] = $sum_beban_sks_dosen;
          $new_employes2[$i2]['Sisa_Sks_Dosen'] = ($Jatahsks ? $Jatahsks->Sks:0) - $sum_beban_sks_dosen;
          $i2++;
        }
        // dd($new_employes2);

       return view('acd_offered_course/edit_employee')
       ->with('query_edit', $data)
       ->with('curriculum',$curriculum)
       ->with('query', $lecturer)
       ->with('select_employee', $select_employee)
       ->with('select_employee2', $select_employee2)
       ->with('department', $department)
       ->with('class_program', $class_program)
       ->with('term_year', $term_year)
       ->with('search',$search)
       ->with('page', $page)
       ->with('new_employes', $new_employes)
       ->with('new_employes2', $new_employes2)
       ->with('rowpage', $rowpage);
     }



     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update_employee(Request $request, $id)
     {
      //  $this->validate($request,[
      //    'Employee_Id' => 'required',
      //    'Class_Capacity' => 'required|numeric'
      //  ],['Employee_Id.required' => 'Dosen Pengampu Harus Diisi',
      //     'Class_Capacity.numeric' => 'Kapasitas harus berupa angka'
      //  ]);
      // dd($request->all());
             $Offered_Course_id = $id;
             $Employee_Id = Input::get('Employee_Id');
             $term_year = Input::get('term_year');
             $curriculum = Input::get('curriculum');
             $Course_Code = Input::get('Course_Code');
             $Class_Capacity = Input::get('Class_Capacity');
             try {
                $update = DB::table('acd_offered_course')
                      ->where('Offered_Course_id', $id)
                      ->update([
                        'Class_Capacity' => $Class_Capacity,
                        'Modified_Date'=>date('Y-m-d'),
                        'Modified_By' => Auth::user()->email,
                      ]);

              if($Employee_Id != null){
               foreach ($Employee_Id as $data) {
                $sksmatkul = DB::table('acd_offered_course')
                            ->leftjoin('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')                            
                            ->where('acd_offered_course.Offered_Course_id',$Offered_Course_id)
                            ->first();
                // $bebansks = DB::table('acd_offered_course_lecturer as a')
                //               ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                //               ->leftjoin('acd_course_curriculum as c' ,function ($join)
                //                 {
                //                   $join->on('c.Department_Id','=','b.Department_Id')
                //                   ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
                //                   ->on('c.Course_Id','=','b.Course_Id');
                //                 })
                //               ->where('b.Term_Year_Id', $term_year)
                //               ->where('a.Employee_Id',$data)
                //               ->select(DB::raw('(SUM(c.Applied_Sks)) as beban_sks'))->first();
                  $bebansks = DB::table('acd_offered_course_lecturer as a')
                  ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                  ->leftjoin('acd_course_curriculum as c' ,function ($join)
                    {
                      $join->on('c.Department_Id','=','b.Department_Id')
                      ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
                      ->on('c.Course_Id','=','b.Course_Id');
                    })
                  ->where('a.Employee_Id',$data)
                  ->where('b.Term_Year_Id', $term_year)
                  ->get();

                  $jml_dosen = DB::table('acd_offered_course_lecturer as a')
                  ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                  ->leftjoin('acd_course_curriculum as c' ,function ($join)
                    {
                      $join->on('c.Department_Id','=','b.Department_Id')
                      ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
                      ->on('c.Course_Id','=','b.Course_Id');
                    })
                  // ->where('a.Employee_Id',$key->Employee_Id)
                  ->where('b.Term_Year_Id', $term_year)
                  ->where('a.Offered_Course_id',$Offered_Course_id)
                  ->count();

                  $sum_beban_sks_dosen = 0;
                  foreach ($bebansks as $bebansk) {
                    // $cd = DB::table('acd_offered_course_lecturer as aocl')->join('acd_offered_course as aoc','aoc.Offered_Course_id','=','aocl.Offered_Course_id')->where('aocl.Offered_Course_id',$bebansk->Offered_Course_id)->get();
                    $cd = DB::table('acd_offered_course_lecturer as a')
                      ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                      ->leftjoin('acd_course_curriculum as c' ,function ($join)
                        {
                          $join->on('c.Department_Id','=','b.Department_Id')
                          ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
                          ->on('c.Course_Id','=','b.Course_Id');
                        })
                      // ->where('a.Employee_Id',$key->Employee_Id)
                      ->where('b.Term_Year_Id', $term_year)
                      ->where('a.Offered_Course_id',$bebansk->Offered_Course_id)
                      ->get();
                      // dd($bebansks,$bebansk,$cd);
                    if(count($cd) > 0){
                      $sum_beban_sks_dosen = $sum_beban_sks_dosen + ($bebansk->Applied_Sks / count($cd));
                    }else{
                      $sum_beban_sks_dosen = $sum_beban_sks_dosen;              
                    }
                    
                  }
                  
                  $Jatahsks = DB::table('emp_lecturer_work_load')->where('Term_Year_Id', $term_year)->where('Employee_Id',$data)->first();
                if($Jatahsks != null){
                  $totalsks = $Jatahsks->Sks;
                }else{
                  $totalsks = 0;
                }
                $sisabeban = $totalsks-$sum_beban_sks_dosen;
                // dd($totalsks,$sum_beban_sks_dosen,$sisabeban,$sksmatkul->Applied_Sks,$jml_dosen);
                $count_dosen = ($jml_dosen == 0 ? $sksmatkul->Applied_Sks:($sksmatkul->Applied_Sks/($jml_dosen+1)));
                if($sisabeban < ($count_dosen)){
                  return Redirect::back()->withErrors('Beban Sks Dosen Melebihi Batas');
                }else{
                  DB::table('acd_offered_course_lecturer')
                  ->insert(
                  ['Offered_Course_id' => $Offered_Course_id,'Employee_Id' => $data]);
                }
               }
              }
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
        $q=DB::table('acd_offered_course')->where('Offered_Course_id', $id)->first();
        $check_krs = DB::table('acd_student_krs as a')
        ->join('acd_student as b','a.Student_Id','=','b.Student_Id')
        ->where([
          ['a.Term_Year_Id',$q->Term_Year_Id],
          ['a.Class_Prog_Id',$q->Class_Prog_Id],
          ['a.Course_Id',$q->Course_Id],
          ['a.Class_Id',$q->Class_Id],
          ['a.Is_Approved',1],
          ['b.Department_Id',$q->Department_Id]
        ])->count();
        // dd($check_krs);
        if($check_krs == 0){
          $q=DB::table('acd_offered_course')->where('Offered_Course_id', $id)->delete();
          return response()->json([
              "success" => true,
              "data" =>'',
              "message" =>'Sukses menghapus data',
              "type" => 'success',
              "total" => 0,
          ], 200);
        }else{
          return response()->json([
                      "success" => false,
                      "data" =>'',
                      "message" =>'ada KRS mahasiswa',
                      "type" => 'warning',
                      "total" => 0,
                  ], 200);
        }
     }

     public function destroy_employee(Request $request,$id)
     {
         $q=DB::table('acd_offered_course_lecturer')->where('Acd_Offered_Course_Lecturer', $id)->delete();
         echo json_encode($q);
     }
 }
