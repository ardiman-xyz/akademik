<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AcdSchedReal;
use App\AcdOfferedCourse;
use App\AcdSchedRealDetail;
use Auth;
use DB;
use PDF;
use App\GetDepartment;

class SchedrealController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','show']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','peserta','detail','storepeserta']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','peserta','detail','storepeserta']]);
    $this->middleware('access:CanViewPeserta', ['except' => ['index','create','store','show','edit','update','destroy','detail']]);
    $this->middleware('access:CanEditPeserta', ['except' => ['index','create','store','show','edit','update','destroy','peserta','detail']]);

  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $search = $request->Input('search');
       $rowpage = $request->Input('rowpage');
       $Department_Id = $request->Input('Department_Id');
       $Term_Year_Id = $request->Input('Term_Year_Id');

       $term_year1 = $request->get('Term_Year_Id');
       if($term_year1 == null){
        $Term_Year_Id =  $request->session()->get('term_year');
       }else{
        $Term_Year_Id = $request->get('Term_Year_Id');
       }
       $Class_Prog_Id = $request->Input('Class_Prog_Id');
       $Curriculum_Id =$request->Input('Curriculum_Id');

       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }

      $select_department = GetDepartment::getDepartment();

      $select_class_program = DB::table('mstr_department_class_program')
      ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')      
      ->where('mstr_department_class_program.Department_Id', $Department_Id)
      ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
      ->get();

      $data = DB::table('acd_offered_course')
          ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->where('acd_offered_course.Department_Id', $Department_Id)
          ->where('acd_offered_course.Class_Prog_Id', $Class_Prog_Id)
          ->where('acd_offered_course.Term_Year_Id', $Term_Year_Id)
          // ->where('acd_course_curriculum.Curriculum_Id',$Curriculum_Id)
          ->where(function($query) use($request){
            $search = $request->Input('search');
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
          ->paginate($rowpage);
          
      $select_curriculum = DB::table('mstr_curriculum')
      ->orderBy('mstr_curriculum.Curriculum_Name', 'desc')
      ->get();

      $Term_Year = DB::table('mstr_term_year')->orderBy('Term_Year_Id','desc')->get();

      $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'Class_Prog_Id'=> $Class_Prog_Id,'Term_Year_Id'=> $Term_Year_Id, 'Department_Id'=> $Department_Id, 'Curriculum_Id'=> $Curriculum_Id]);
      return view('schedreal/index')->with('datas', $data)->with('Term_Year',$Term_Year)->with('Department',$select_department)->with('Curriculum',$select_curriculum)->with('ClassProg',$select_class_program)->with('Department_Id',$Department_Id)->with('Term_Year_Id',$Term_Year_Id)->with('Curriculum_Id',$Curriculum_Id)->with('Class_Prog_Id',$Class_Prog_Id)->with('rowpage',$rowpage)->with('search',$search);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $id = $request->Input('id');
      $offeredcourse = AcdOfferedCourse::join('acd_offered_course_sched as b','acd_offered_course.Offered_Course_Id','=','b.Offered_Course_Id')
      ->join('acd_sched_session as c','b.Sched_Session_Id','=','c.Sched_Session_Id')
      ->where('acd_offered_course.Offered_Course_id',$id)
      ->orderBy('c.Time_Start')
      ->get();
      $data_dosen = DB::table('acd_offered_course_lecturer')
      ->join('emp_employee','acd_offered_course_lecturer.Employee_Id','=','emp_employee.Employee_Id')
      ->where('acd_offered_course_lecturer.Offered_Course_Id',$id)->get();
      $room = DB::table('mstr_room')->get();
      $room_kuliah = DB::table('acd_offered_course_sched')->where('Offered_Course_id',$id)->select('Room_Id')->first();
      $totalpertemuan = 0;
      foreach ($offeredcourse as $key) {
        $totalpertemuan = AcdSchedReal::where('Course_Id',$key->Course_Id)
                ->where('Term_Year_Id',$key->Term_Year_Id)
                ->where('Class_Prog_Id',$key->Class_Prog_Id)
                ->where('Class_Id',$key->Class_Id)
                ->count();
      $totalpertemuan++;
      }
      // dd($totalpertemuan);
      $datas = DB::table('acd_student_krs')
      ->join('acd_offered_course' ,function ($join)
      {
        $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
        ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
      })
      ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
      ->where('acd_offered_course.Offered_Course_id', $id)
      ->select('acd_student_krs.Krs_Id as Krs','acd_student.Student_Id as StudentId','acd_student.*')
      ->orderBy('acd_student.Nim')
      ->get();
      // $peserta = AcdSchedRealDetail::where('Sched_Real_Id',$schedreal)->get();      
      // $datapeserta = array();
      // foreach ($peserta as $value) {
      //   array_push($datapeserta,$value->Student_Id);
      // }
      // $pertemuan = AcdSchedReal::where('Sched_Real_Id',$schedreal)->first();

      $time_start = $offeredcourse[0];
      $time_end = last($offeredcourse);
      // dd($time_end);
      return view('schedreal/create')
      ->with('datass', $datas)
      // ->with('pertemuan',$pertemuan)->with('datapeserta',$datapeserta)->with('id',$id)->with('schedreal',$schedreal)
      ->with('data_dosen',$data_dosen)
      ->with('offeredcourse',$offeredcourse)
      ->with('totalpertemuan',$totalpertemuan)
      ->with('id',$id)
      ->with('room_kuliah', $room_kuliah)
      ->with('room',$room);
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
         'Room_Id'=>'required',
         'datetimepickerkendo'=>'required',
       ],['Room_Id.required'=>'Ruang belum diisi']);

      $Meeting_Order = $request->Input('Meeting_Order');
      $Employee_Id = $request->Input('Employee_Id');
      $Room_Id = $request->Input('Room_Id');
      $Date = $request->Input('Date');
      $Time_Start = $request->Input('Time_Start');
      $Time_End = $request->Input('Time_End');
      $Token = $request->Input('Token');
      $Max_Minutes = $request->Input('Max_Minutes');
      $Course_Content = $request->Input('Course_Content');
      $Description = $request->Input('Description');
      $Course_Id = $request->Input('Course_Id');
      $Class_Id = $request->Input('Class_Id');
      $Term_Year_Id = $request->Input('Term_Year_Id');
      $Class_Prog_Id = $request->Input('Class_Prog_Id');
      $datetimepickerkendo = $request->Input('datetimepickerkendo');

      $split 	  = explode(' ', $Date);

      if($Date == null || $Date == 0){
        $Date = Date('Y-m-d');
      }else{
        $Date = $Date;
      }
      // dd($datetimepickerkendo);

      $AcdSchedReal = new AcdSchedReal;
      $AcdSchedReal->Meeting_Order = $Meeting_Order;
      $AcdSchedReal->Room_Id = $Room_Id;
      $AcdSchedReal->Date = $datetimepickerkendo;
      $AcdSchedReal->Time_Start = $Time_Start;
      // $AcdSchedReal->Time_Start = $split[1];
      $AcdSchedReal->Time_End = $Time_End;
      $AcdSchedReal->Token = $Token;
      $AcdSchedReal->Max_Minutes = $Max_Minutes;
      $AcdSchedReal->Course_Content = $Course_Content;
      $AcdSchedReal->Description = $Description;
      $AcdSchedReal->Course_Id = $Course_Id;
      $AcdSchedReal->Class_Id = $Class_Id;
      $AcdSchedReal->Term_Year_Id = $Term_Year_Id;
      $AcdSchedReal->Class_Prog_Id = $Class_Prog_Id;
      $AcdSchedReal->Created_By = Auth::user()->email;
      $AcdSchedReal->Created_Date = Date('Y-m-d');

      if($AcdSchedReal->save()){
        try{
          foreach($Employee_Id as $employ){
            $AcdSchedReal->empEmployees()->attach($employ);
          }
          $validator = 'Berhasil Menambah Data';
          $success = true;
        }catch(\Exception $e){
          $validator = 'Berhasil Menambah Data namun Dosen belum diisi';
          $success = false;
        }

        $Student_Id = $request->Input('Student_Id');
        $Sched_Real_Id = $AcdSchedReal->Sched_Real_Id;

        $old = AcdSchedRealDetail::where('Sched_Real_Id',$Sched_Real_Id)->delete();
        $error = null;

        if($Student_Id != ''){
          foreach ($Student_Id as $value) {
            $data = new AcdSchedRealDetail;
            $data->Sched_Real_Id = $Sched_Real_Id;
            $data->Student_Id = $value;
            $save = $data->save();
            if(!$save){
              $error = "error";
            }
          }
        }
        if($error == null){
          $validator = 'Berhasil Menyimpan Perubahan';
          $success = true;
        }else{
          $validator = 'Gagal Menyimpan Perubahan';
          $success = false;
        }
      }else{
        $validator = 'Gagal Menambah Data';
        $success = false;
      }
      return redirect()->back()->withErrors($validator)->with('success',$success);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request )
    {
        $search = $request->Input('search');
        $rowpage = $request->Input('rowpage');

        if ($rowpage == null || $rowpage <= 0) {
          $rowpage = 10;
        }

        $offeredcourse = DB::table('acd_offered_course')
        ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
        ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
        ->leftjoin('acd_student_krs' ,function ($join)
        {
          $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
          ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
        })
        ->leftjoin('acd_student' , function ($join)
        {
          $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
        })
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->select( 'acd_offered_course.*','mstr_class_program.Class_Prog_Id','acd_course.*','acd_student.Student_Id','mstr_class.Class_Name', 
                  DB::raw('COUNT(acd_student_krs.Student_Id) as jml_peserta'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
        ->first();
        
        $ofcs =  DB::table('acd_offered_course')
        ->leftjoin('acd_student_krs' ,function ($join)
        {
          $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
          ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
        })
        ->leftjoin('acd_student' , function ($join)
        {
          $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
        })
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->get();
        
        $students = array();
        foreach($ofcs as $o){
          array_push($students,$o->Student_Id);
        }
        // dd($students);

        $Curriculum = DB::table('acd_course_curriculum')->where('Course_Id',$offeredcourse->Course_Id)->first();
        $data = AcdSchedReal::where('Course_Id',$offeredcourse->Course_Id)
              ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
              ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
              ->where('Class_Id',$offeredcourse->Class_Id)
              ->orderBy('Course_Id')
              ->with('empEmployees')
              ->withCount(['acdStudents' => function($qq){
                $qq->where('Attendance_Id',1);
              }])->paginate($rowpage);
        $totalpertemuan = AcdSchedReal::where('Course_Id',$offeredcourse->Course_Id)
              ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
              ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
              ->where('Class_Id',$offeredcourse->Class_Id)
              ->count();

        $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'Class_Prog_Id'=> $offeredcourse->Class_Prog_Id,'Term_Year_Id'=> $offeredcourse->Term_Year_Id, 'Department_Id'=> $offeredcourse->Department_Id]);
        return view('schedreal/show')
        
        ->with('datas', $data)->with('id',$id)->with('Curriculum',$Curriculum)->with('offeredcourse',$offeredcourse)->with('rowpage',$rowpage)->with('search',$search)->with('totalpertemuan',$totalpertemuan);
    }

    public function detail($schedreal,$id)
    {
        $offeredcourse = DB::table('acd_offered_course')
        ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
    
        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
        ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
        ->leftjoin('acd_student_krs' ,function ($join)
        {
          $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
          ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
        })
        ->leftjoin('acd_student' , function ($join)
        {
          $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
        })
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
        ->first();
        
        $data = AcdSchedReal::where('Sched_Real_Id',$schedreal)->with('empEmployees')->withCount('acdStudents')->first();

        return view('schedreal/detail')->with('datas', $data)->with('id',$id)->with('offeredcourse',$offeredcourse);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
      $idofc = $request->Input('id');
      $offeredcourse = AcdOfferedCourse::where('Offered_Course_id',$idofc)->get();
      $employee = DB::table('acd_offered_course_lecturer')->join('emp_employee','acd_offered_course_lecturer.Employee_Id','=','emp_employee.Employee_Id')->where('acd_offered_course_lecturer.Offered_Course_Id',$idofc)->get();
      $room = DB::table('mstr_room')->get();
      $data = AcdSchedReal::where('Sched_Real_Id',$id)->with('empEmployees')->first();

      return view('schedreal/edit')->with('datas', $data)->with('employee',$employee)->with('room',$room)->with('id',$id)->with('offeredcourse',$offeredcourse)->with('idofc',$idofc);
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
      $Meeting_Order = $request->Input('Meeting_Order');
      $Employee_Id = $request->Input('Employee_Id');
      $Room_Id = $request->Input('Room_Id');
      $Date = $request->Input('Date');
      $Time_Start = $request->Input('Time_Start');
      $Time_End = $request->Input('Time_End');
      $Token = $request->Input('Token');
      $Max_Minutes = $request->Input('Max_Minutes');
      $Course_Content = $request->Input('Course_Content');
      $Description = $request->Input('Description');
      $Course_Id = $request->Input('Course_Id');
      $Class_Id = $request->Input('Class_Id');
      $Term_Year_Id = $request->Input('Term_Year_Id');
      $Class_Prog_Id = $request->Input('Class_Prog_Id');

      $AcdSchedReal = AcdSchedReal::find($id);
      $AcdSchedReal->Meeting_Order = $Meeting_Order;
      $AcdSchedReal->Room_Id = $Room_Id;
      $AcdSchedReal->Date = $Date;
      $AcdSchedReal->Time_Start = $Time_Start;
      $AcdSchedReal->Time_End = $Time_End;
      $AcdSchedReal->Token = $Token;
      $AcdSchedReal->Max_Minutes = $Max_Minutes;
      $AcdSchedReal->Course_Content = $Course_Content;
      $AcdSchedReal->Description = $Description;
      $AcdSchedReal->Course_Id = $Course_Id;
      $AcdSchedReal->Class_Id = $Class_Id;
      $AcdSchedReal->Term_Year_Id = $Term_Year_Id;
      $AcdSchedReal->Class_Prog_Id = $Class_Prog_Id;

      if($AcdSchedReal->save()){
        try{
          $AcdSchedReal->empEmployees()->sync([]);
          foreach($Employee_Id as $employ){
            $AcdSchedReal->empEmployees()->attach($employ);
          }
          $validator = 'Berhasil Menyimpan Perubahan';
          $success = true;
        }catch(\Exception $e){
          $validator = 'Gagal Menyimpan Perubahan Dosen, Kemungkinan Dosen belum diisi';
          $success = false;
        }
      }else{
        $validator = 'Gagal Menyimpan Perubahan';
        $success = false;
      }
      return redirect()->back()->withErrors($validator)->with('success', $success);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $AcdSchedReal = AcdSchedReal::find($id);
        $AcdSchedReal->empEmployees()->sync([]);
        $data = $AcdSchedReal->delete();
        echo json_encode($data);
    }


    public function peserta($schedreal,$id)
    {
      $data = DB::table('acd_student_krs')
      ->join('acd_offered_course' ,function ($join)
      {
        $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
        ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
      })
      ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
      ->where('acd_offered_course.Offered_Course_id', $id)
      ->select('acd_student_krs.Krs_Id as Krs','acd_student.Student_Id as StudentId','acd_student.*')
      ->orderBy('acd_student.Nim')
      ->get();
      $peserta = AcdSchedRealDetail::where('Sched_Real_Id',$schedreal)->get();      
      $datapeserta = array();
      $datapesertatidakhadir = array();
      $datapesertasakit = array();
      $datapesertaijin = array();
      $datapesertambkm = array();
      foreach ($peserta as $value) {
        // dd($value);
        if($value->Attendance_Id == 1){
          array_push($datapeserta, $value->Student_Id);        
        }
        if($value->Attendance_Id == 2){
          array_push($datapesertatidakhadir, $value->Student_Id);        
        }
        if($value->Attendance_Id == 3){
          array_push($datapesertasakit, $value->Student_Id);        
        }
        if($value->Attendance_Id == 4){
          array_push($datapesertaijin, $value->Student_Id);        
        }
        if($value->Attendance_Id == 5){
          array_push($datapesertambkm, $value->Student_Id);        
        }
      }
      $pertemuan = AcdSchedReal::where('Sched_Real_Id',$schedreal)->first();
      return view('schedreal/peserta')
      ->with('datas', $data)
      ->with('pertemuan', $pertemuan)
      ->with('datapeserta', $datapeserta)
      ->with('datapesertatidakhadir', $datapesertatidakhadir)
      ->with('datapesertasakit', $datapesertasakit)
      ->with('datapesertaijin', $datapesertaijin)
      ->with('datapesertambkm', $datapesertambkm)
      ->with('id', $id)
      ->with('schedreal', $schedreal);
    }
    public function storepeserta(Request $request)
    {
      $Sched_Real_Id = $request->Input('Sched_Real_Id');
      $Student_Id = $request->Input('Student_Id');

      $old = AcdSchedRealDetail::where('Sched_Real_Id',$Sched_Real_Id)->delete();
      $error = null;

      
      if ($Student_Id != '') {
        foreach ($Student_Id as $hadir) {
          $data = new AcdSchedRealDetail;
          $data->Sched_Real_Id = $Sched_Real_Id;
          $data->Student_Id = $hadir;
          $data->Attendance_Id = '1';
          $save = $data->save();
          if (!$save) {
            $error = "error";
          }
        }
      }
      if ($request->tidakhadirStudent_Id != '') {
        foreach ($request->tidakhadirStudent_Id as $tidakhadir) {
          $data = new AcdSchedRealDetail;
          $data->Sched_Real_Id = $Sched_Real_Id;
          $data->Student_Id = $tidakhadir;
          $data->Attendance_Id = '2';
          $save = $data->save();
          if (!$save) {
            $error = "error";
          }
        }
      }
      if ($request->sakitStudent_Id != '') {
        foreach ($request->sakitStudent_Id as $sakit) {
          $data = new AcdSchedRealDetail;
          $data->Sched_Real_Id = $Sched_Real_Id;
          $data->Student_Id = $sakit;
          $data->Attendance_Id = '3';
          $save = $data->save();
          if (!$save) {
            $error = "error";
          }
        }
      }
      if ($request->ijinStudent_Id != '') {
        foreach ($request->ijinStudent_Id as $ijin) {
          $data = new AcdSchedRealDetail;
          $data->Sched_Real_Id = $Sched_Real_Id;
          $data->Student_Id = $ijin;
          $data->Attendance_Id = '4';
          $save = $data->save();
          if (!$save) {
            $error = "error";
          }
        }
      }
      if ($request->mbkmStudent_Id != '') {
        foreach ($request->mbkmStudent_Id as $mbkm) {
          $data = new AcdSchedRealDetail;
          $data->Sched_Real_Id = $Sched_Real_Id;
          $data->Student_Id = $mbkm;
          $data->Attendance_Id = '5';
          $save = $data->save();
          if (!$save) {
            $error = "error";
          }
        }
      }
      
      if($error == null){
        $validator = 'Berhasil Menyimpan Perubahan';
        $success = true;
      }else{
        $validator = 'Gagal Menyimpan Perubahan';
        $success = false;
      }
      return redirect()->back()->withErrors($validator)->with('success',$success);

    }

    public function pesertatotal($id){
      $offeredcourse = DB::table('acd_offered_course')
        ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
        ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
        ->leftjoin('acd_student_krs' ,function ($join)
        {
          $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
          ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
        })
        ->leftjoin('acd_student' , function ($join)
        {
          $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
        })
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->select( 'acd_offered_course.*','mstr_class_program.Class_Prog_Id','acd_course.*','acd_student.Student_Id','mstr_class.Class_Name', DB::raw('COUNT(acd_student_krs.Student_Id) as jml_peserta'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
        ->first();
        
      $course_Id = $offeredcourse->Course_Id;

      $items = DB::table('acd_student_krs')
      ->join('acd_offered_course' ,function ($join)
      {
        $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
        ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
      })
      ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
      // ->leftjoin('acd_sched_real_detail', 'acd_sched_real_detail.Student_Id', '=', 'acd_student.Student_Id')
      ->where('acd_offered_course.Offered_Course_id', $id)
      ->select('acd_student_krs.Krs_Id as Krs','acd_student.Student_Id as StudentId','acd_student.*'
              // ,DB::raw("(SELECT COUNT(acd_sched_real_detail.Student_Id) FROM acd_sched_real_detail as a join acd_sched_real as b on a.Sched_real_id = b.Sched_Real_Id where b.Course_Id = 3) as totalpeserta")
                )
      ->orderBy('acd_student.Nim')
      ->get();

      // dd($items);

      $data = [];
      $i = 0;
      $totalpertemuan = AcdSchedReal::where('Course_Id',$offeredcourse->Course_Id)
            ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
            ->where('Class_Id',$offeredcourse->Class_Id)
            ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
            ->count();     
       foreach ($items as $item) {
        $data[$i]['Nim'] = $item->Nim;
        $data[$i]['Full_Name'] = $item->Full_Name;
        $data[$i]['Jumlah'] = DB::table('acd_sched_real_detail')
                            ->join('acd_sched_real','acd_sched_real.Sched_Real_Id','=','acd_sched_real_detail.Sched_Real_Id')
                            ->where([['Student_Id',$item->Student_Id]])
                            ->where('Course_Id',$offeredcourse->Course_Id)
                            ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
                            ->where('Class_Id',$offeredcourse->Class_Id)
                            ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
                            ->where('Attendance_Id',1)
                            ->count();
        $data[$i]['Persen'] = round(DB::table('acd_sched_real_detail')
                            ->join('acd_sched_real','acd_sched_real.Sched_Real_Id','=','acd_sched_real_detail.Sched_Real_Id')
                            ->where([['Student_Id',$item->Student_Id]])
                            ->where('Course_Id',$offeredcourse->Course_Id)
                            ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
                            ->where('Class_Id',$offeredcourse->Class_Id)
                            ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
                            ->where('Attendance_Id',1)
                            ->count()/$totalpertemuan*100,2);
        $i++;
      }
      return view('schedreal/pesertatotal')->with('id',$id)->with('datas',$data)->with('totalpertemuan',$totalpertemuan);
    }

    public function exportall($id, Request $request )
    {
        $search = $request->Input('search');
        $rowpage = $request->Input('rowpage');

        if ($rowpage == null || $rowpage <= 0) {
          $rowpage = 10;
        }

        $offered_course =  DB::table('acd_offered_course')
        ->join('acd_course' ,function ($join)
        {
          $join->on('acd_course.Department_Id','=','acd_offered_course.Department_Id')
          ->on('acd_course.Course_Id','=','acd_offered_course.Course_Id');
        })
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->first();

        $jadwal =  DB::table('acd_offered_course')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id') 
          ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
          ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
          ->join('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->where('acd_offered_course.Department_Id', $offered_course->Department_Id)
          ->where('acd_offered_course.Class_Prog_Id', $offered_course->Class_Prog_Id)
          ->where('acd_offered_course.Course_Id', $offered_course->Course_Id)
          ->where('acd_offered_course.Class_Id', $offered_course->Class_Id)
          ->where('acd_offered_course.Term_Year_Id', $offered_course->Term_Year_Id)
          // ->where('acd_offered_course.Curriculum_Id',$curriculum->Curriculum_Id)
          ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id','mstr_term_year.Term_Year_Name','mstr_department.Department_Name','mstr_education_program_type.Acronym',
            DB::raw('(SELECT Group_Concat(acd_sched_session.Description SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as jadwal'),
            DB::raw('(SELECT Group_Concat(acd_sched_session.Day_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as day_id'),
            DB::raw('(SELECT Group_Concat(acd_sched_session.Time_Start SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as time_start'),
            DB::raw('(SELECT Group_Concat(acd_sched_session.Time_End SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as time_end'),
            DB::raw('(SELECT Group_Concat(acd_sched_session.Sched_Session_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as ssi'),
            DB::raw('(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as room'),
            DB::raw('(SELECT Group_Concat(mstr_room.Room_Code SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as room_code'))
          ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('acd_offered_course.Class_Id', 'asc')
          ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
          ->first();

        $lecturers = DB::table('acd_offered_course_lecturer')
        ->join('emp_employee','acd_offered_course_lecturer.Employee_Id','=','emp_employee.Employee_Id')
        ->where('acd_offered_course_lecturer.Offered_Course_id', $id)
        ->select('First_Title','Name','Last_Title','Nip','Nik')
        ->get();

        $krs_mahasiswas = DB::table('acd_student_krs')
          ->join('acd_offered_course' ,function ($join)
          {
            $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
            ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
          })
          ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->where('acd_offered_course.Offered_Course_id', $id)
          ->select('Krs_Id','acd_student_krs.Student_Id','Nim','Full_Name')
          ->orderBy('acd_student.Nim')
          ->get();
        
        if($jadwal->day_id != null){
          $jadwal_get_day = explode('|',$jadwal->day_id);
          $days = DB::table('mstr_day')->where('Day_Id',$jadwal_get_day[0])->select('Day_Name')->first();
          $day = $days->Day_Name;
        }else{
          $day = '';
        }
        if($jadwal->room_code != null){
          $jadwal_get_room = explode('|',$jadwal->room_code);
          $room = $jadwal_get_room[0];
        }else{
          $room = '';
        }
        if($jadwal->time_start != null){
          $jadwal_get_timestart = explode('|',$jadwal->time_start);
          $start = $jadwal_get_timestart[0];
        }else{
          $start = '';
        }
        if($jadwal->time_end != null){
          $jadwal_get_timesend = explode('|',$jadwal->time_end);
          $end = end($jadwal_get_timesend);
        }else{
          $end = '';
        }

        $jadwal_get_termyear = explode('/',$jadwal->Term_Year_Name);

        $data_presensi['Department_Name'] = $jadwal->Department_Name;
        $data_presensi['Acronym'] = $jadwal->Acronym;
        $data_presensi['Term_Year_Name'] = $jadwal->Term_Year_Name;
        $data_presensi['Term_Year_gg'] = end($jadwal_get_termyear);
        $data_presensi['Course_Name'] = $jadwal->Course_Name;
        $data_presensi['Course_Code'] = $jadwal->Course_Code;
        $data_presensi['Class_Name'] = $jadwal->Class_Name;
        $data_presensi['room'] = $room;
        $data_presensi['day'] = $day;
        $data_presensi['start'] = $start;
        $data_presensi['end'] = $end;

        $c_lecturer = 0;
        $data_presensi['lecturer'] = [];
        foreach ($lecturers as $lecturer) {
          $data_presensi['lecturer'][$c_lecturer] = $lecturer->First_Title.($lecturer->First_Title ? ' ':'').$lecturer->Name.($lecturer->Last_Title ? ', ':'').$lecturer->Last_Title;
          $c_lecturer++;
        }

        $c_mhs = 0;
        $data_presensi['mahasiswa'] = [];
        foreach ($krs_mahasiswas as $krs_mahasiswa){
          $datas = AcdSchedReal::where('Course_Id',$offered_course->Course_Id)
            ->where('Term_Year_Id',$offered_course->Term_Year_Id)
            ->where('Class_Prog_Id',$offered_course->Class_Prog_Id)
            ->where('Class_Id',$offered_course->Class_Id)
            ->orderBy('Course_Id')
            ->get();

          $data_presensi['mahasiswa'][$c_mhs]['Nim'] = $krs_mahasiswa->Nim;
          $data_presensi['mahasiswa'][$c_mhs]['Full_Name'] = $krs_mahasiswa->Full_Name;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['hadir'] = 0;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['alpha'] = 0;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['sakit'] = 0;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['ijin'] = 0;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['mbkm'] = 0;
          $c_pertemuan = 1;
          $c_hadir = 0;
          foreach ($datas as $data) {
            $mhs_hadir = AcdSchedRealDetail::where([['Sched_Real_Id',$data->Sched_Real_Id],['Student_Id',$krs_mahasiswa->Student_Id]])->first();
            if($mhs_hadir){
              if($mhs_hadir->Attendance_Id == 1){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = true;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['hadir'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['hadir'] + 1;
              }elseif($mhs_hadir->Attendance_Id == 2){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['alpha'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['alpha'] + 1;
              }elseif($mhs_hadir->Attendance_Id == 3){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['sakit'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['sakit'] + 1;
              }elseif($mhs_hadir->Attendance_Id == 4){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['ijin'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['ijin'] + 1;
              }elseif($mhs_hadir->Attendance_Id == 5){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['mbkm'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['mbkm'] + 1;
              }else{
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
              }
              $c_hadir++;
            }else{
              $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = null;
            }
            $c_pertemuan++;
          }
          $data_presensi['mahasiswa'][$c_mhs]['hadir'] = $c_hadir;
          $c_mhs++;
        }
        // dd($data_presensi);

        View()->share(['data_presensi'=>$data_presensi]);
        $pdf = PDF::loadView('schedreal/exportdata');
        return $pdf->stream('Data Presensi Mahasiswa.pdf');
    }



    public function exportdosen($id, Request $request )
    {
        $search = $request->Input('search');
        $rowpage = $request->Input('rowpage');

        if ($rowpage == null || $rowpage <= 0) {
          $rowpage = 10;
        }

        $offered_course =  DB::table('acd_offered_course')
        ->join('acd_course' ,function ($join)
        {
          $join->on('acd_course.Department_Id','=','acd_offered_course.Department_Id')
          ->on('acd_course.Course_Id','=','acd_offered_course.Course_Id');
        })
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->first();

        $schedreal = DB::table('acd_sched_real')
        ->where([
          ['Term_Year_Id',$offered_course->Term_Year_Id],
          ['Class_Prog_Id',$offered_course->Class_Prog_Id],
          ['Class_Id',$offered_course->Class_Id],
          ['Course_Id',$offered_course->Course_Id],
        ])
        ->orderBy('Meeting_Order','asc')
        ->get();

        $data_dosen = [];
        $q = 0;
        foreach ($schedreal as $key) {
          $dosens = DB::table('acd_sched_real_employee')
          ->join('emp_employee','acd_sched_real_employee.Employee_Id','=','emp_employee.Employee_Id')
          ->where('Sched_Real_Id',$key->Sched_Real_Id)
          ->get();
          $n_dosen = '';
          foreach ($dosens as $dosen) {
            $n_dosen = ($n_dosen == '' ? ''.$dosen->Full_Name:$n_dosen.' | '.$dosen->Full_Name);
          }
          $data_dosen[$q]['dosen'] = $n_dosen;

          $lecturers = DB::table('acd_offered_course_lecturer')
          ->join('emp_employee','acd_offered_course_lecturer.Employee_Id','=','emp_employee.Employee_Id')
          ->where('acd_offered_course_lecturer.Offered_Course_id', $id)
          ->select('First_Title','Name','Last_Title','Nip','Nik','Full_Name')
          ->get();

          $data_dosen[$q]['lecturer'] = '';
          foreach ($lecturers as $lecturer) {
            $data_dosen[$q]['lecturer'] = ($data_dosen[$q]['lecturer'] == '' ? ''.$lecturer->Full_Name:$data_dosen[$q]['lecturer'].' | '.$lecturer->Full_Name);
          }
          $data_dosen[$q]['order'] = $key->Meeting_Order;
          $data_dosen[$q]['konten'] = $key->Course_Content;
          $data_dosen[$q]['deskripsi'] = $key->Description;
          $q++;
        }

        $jadwal =  DB::table('acd_offered_course')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id') 
          ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
          ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
          ->join('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->where('acd_offered_course.Department_Id', $offered_course->Department_Id)
          ->where('acd_offered_course.Class_Prog_Id', $offered_course->Class_Prog_Id)
          ->where('acd_offered_course.Course_Id', $offered_course->Course_Id)
          ->where('acd_offered_course.Class_Id', $offered_course->Class_Id)
          ->where('acd_offered_course.Term_Year_Id', $offered_course->Term_Year_Id)
          // ->where('acd_offered_course.Curriculum_Id',$curriculum->Curriculum_Id)
          ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id','mstr_term_year.Term_Year_Name','mstr_department.Department_Name','mstr_education_program_type.Acronym',
            DB::raw('(SELECT Group_Concat(acd_sched_session.Description SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as jadwal'),
            DB::raw('(SELECT Group_Concat(acd_sched_session.Day_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as day_id'),
            DB::raw('(SELECT Group_Concat(acd_sched_session.Time_Start SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as time_start'),
            DB::raw('(SELECT Group_Concat(acd_sched_session.Time_End SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as time_end'),
            DB::raw('(SELECT Group_Concat(acd_sched_session.Sched_Session_Id SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as ssi'),
            DB::raw('(SELECT Group_Concat(mstr_room.Room_Name SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as room'),
            DB::raw('(SELECT Group_Concat(mstr_room.Room_Code SEPARATOR "|") FROM acd_offered_course_sched LEFT JOIN mstr_room ON acd_offered_course_sched.Room_Id = mstr_room.Room_Id LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id ) as room_code'))
          ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('acd_offered_course.Class_Id', 'asc')
          ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
          ->first();

        $lecturers = DB::table('acd_offered_course_lecturer')
        ->join('emp_employee','acd_offered_course_lecturer.Employee_Id','=','emp_employee.Employee_Id')
        ->where('acd_offered_course_lecturer.Offered_Course_id', $id)
        ->select('First_Title','Name','Last_Title','Nip','Nik')
        ->get();

        $krs_mahasiswas = DB::table('acd_student_krs')
          ->join('acd_offered_course' ,function ($join)
          {
            $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
            ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
          })
          ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->where('acd_offered_course.Offered_Course_id', $id)
          ->select('Krs_Id','acd_student_krs.Student_Id','Nim','Full_Name')
          ->orderBy('acd_student.Nim')
          ->get();
        
        if($jadwal->day_id != null){
          $jadwal_get_day = explode('|',$jadwal->day_id);
          $days = DB::table('mstr_day')->where('Day_Id',$jadwal_get_day[0])->select('Day_Name')->first();
          $day = $days->Day_Name;
        }else{
          $day = '';
        }
        if($jadwal->room_code != null){
          $jadwal_get_room = explode('|',$jadwal->room_code);
          $room = $jadwal_get_room[0];
        }else{
          $room = '';
        }
        if($jadwal->time_start != null){
          $jadwal_get_timestart = explode('|',$jadwal->time_start);
          $start = $jadwal_get_timestart[0];
        }else{
          $start = '';
        }
        if($jadwal->time_end != null){
          $jadwal_get_timesend = explode('|',$jadwal->time_end);
          $end = end($jadwal_get_timesend);
        }else{
          $end = '';
        }

        $jadwal_get_termyear = explode('/',$jadwal->Term_Year_Name);

        $data_presensi['Department_Name'] = $jadwal->Department_Name;
        $data_presensi['Acronym'] = $jadwal->Acronym;
        $data_presensi['Term_Year_Name'] = $jadwal->Term_Year_Name;
        $data_presensi['Term_Year_gg'] = end($jadwal_get_termyear);
        $data_presensi['Course_Name'] = $jadwal->Course_Name;
        $data_presensi['Course_Code'] = $jadwal->Course_Code;
        $data_presensi['Class_Name'] = $jadwal->Class_Name;
        $data_presensi['room'] = $room;
        $data_presensi['day'] = $day;
        $data_presensi['start'] = $start;
        $data_presensi['end'] = $end;

        $c_lecturer = 0;
        $data_presensi['lecturer'] = [];
        foreach ($lecturers as $lecturer) {
          $data_presensi['lecturer'][$c_lecturer] = $lecturer->First_Title.($lecturer->First_Title ? ' ':'').$lecturer->Name.($lecturer->Last_Title ? ', ':'').$lecturer->Last_Title;
          $c_lecturer++;
        }

        $c_mhs = 0;
        $data_presensi['mahasiswa'] = [];
        foreach ($krs_mahasiswas as $krs_mahasiswa){
          $datas = AcdSchedReal::where('Course_Id',$offered_course->Course_Id)
            ->where('Term_Year_Id',$offered_course->Term_Year_Id)
            ->where('Class_Prog_Id',$offered_course->Class_Prog_Id)
            ->where('Class_Id',$offered_course->Class_Id)
            ->orderBy('Course_Id')
            ->get();

          $data_presensi['mahasiswa'][$c_mhs]['Nim'] = $krs_mahasiswa->Nim;
          $data_presensi['mahasiswa'][$c_mhs]['Full_Name'] = $krs_mahasiswa->Full_Name;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['hadir'] = 0;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['alpha'] = 0;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['sakit'] = 0;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['ijin'] = 0;
          $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['mbkm'] = 0;
          $c_pertemuan = 1;
          $c_hadir = 0;
          foreach ($datas as $data) {
            $mhs_hadir = AcdSchedRealDetail::where([['Sched_Real_Id',$data->Sched_Real_Id],['Student_Id',$krs_mahasiswa->Student_Id]])->first();
            if($mhs_hadir){
              if($mhs_hadir->Attendance_Id == 1){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = true;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['hadir'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['hadir'] + 1;
              }elseif($mhs_hadir->Attendance_Id == 2){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['alpha'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['alpha'] + 1;
              }elseif($mhs_hadir->Attendance_Id == 3){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['sakit'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['sakit'] + 1;
              }elseif($mhs_hadir->Attendance_Id == 4){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['ijin'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['ijin'] + 1;
              }elseif($mhs_hadir->Attendance_Id == 5){
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
                $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['mbkm'] = $data_presensi['mahasiswa'][$c_mhs]['c_presensi']['mbkm'] + 1;
              }else{
                $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = false;
              }
              $c_hadir++;
            }else{
              $data_presensi['mahasiswa'][$c_mhs]['Presensi'][$c_pertemuan] = null;
            }
            $c_pertemuan++;
          }
          $data_presensi['mahasiswa'][$c_mhs]['hadir'] = $c_hadir;
          $c_mhs++;
        }
        // dd($data_presensi);

        View()->share(['data_presensi'=>$data_presensi,'data_dosen'=>$data_dosen]);
        $pdf = PDF::loadView('schedreal/exportdatadosen');
        return $pdf->stream('Data Presensi Mahasiswa.pdf');
    }
}
