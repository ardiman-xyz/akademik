<?php

  namespace App\Http\Controllers;

  use App\Http\Controllers\Controller;
  use Illuminate\Support\Facades\Validator;
  use Illuminate\Foundation\Auth\Registerst5s;
  use Illuminate\Http\Request;
  use Illuminate\Support\Str;
  use App\AcdSchedReal;
  use Input;
  use DB;
  use Redirect;
  use Alert;
  use PDF;
  use Auth;
  use Excel;
  use App\GetDepartment;

  class Dosen_mengajarController extends Controller
  {
    public function __construct()
    {
      $this->middleware('access:CanView', ['only' => ['index','show']]);
    }
      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
    public function index(Request $request)
    {
         $search = Input::get('search');
         $rowpage = Input::get('rowpage');
         $department = Input::get('department');
         $class_program = Input::get('class_program');
         
         $FacultyId = Auth::user()->Faculty_Id;
         $DepartmentId = Auth::user()->Department_Id;

         $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }

         if ($rowpage == null) {
           $rowpage = 10;
         }
        $select_department = GetDepartment::getDepartment();

           $select_class_program = DB::table('mstr_department_class_program')
           ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
           ->where('mstr_department_class_program.Department_Id', $department)
           ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
           ->get();


           if ($search == null) {
             $data=DB::table('acd_student_krs')
             ->select('acd_student_krs.*','acd_student.*','mstr_class_program.Class_Program_Name',
               DB::raw('SUM(acd_student_krs.Sks) as jml_sks'),
               DB::raw('SUM(acd_student_krs.Amount) as biaya'),
               DB::raw('COUNT(acd_student_krs.Student_Id) as jml_mk'))
             ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
             ->leftjoin('mstr_department','acd_student.Department_id','=','mstr_department.department_id')
             ->join('mstr_class_program','acd_student_krs.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
             ->join('mstr_term_year','acd_student_krs.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
             ->groupBy('acd_student_krs.Student_Id')
             ->where('acd_student_krs.Term_Year_Id', $term_year)
             ->where('mstr_department.department_id', $department)
             ->where('acd_student_krs.Class_Prog_Id', $class_program)
             ->where('acd_student_krs.Is_Approved', 1)
             ->paginate($rowpage);


           }else {
             $data=DB::table('acd_student_krs')
             ->select('acd_student_krs.*','acd_student.*','mstr_class_program.Class_Program_Name',
               DB::raw('SUM(acd_student_krs.Sks) as jml_sks'),
               DB::raw('SUM(acd_student_krs.Amount) as biaya'),
               DB::raw('COUNT(acd_student_krs.Student_Id) as jml_mk'))
             ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
             ->leftjoin('mstr_department','acd_student.Department_id','=','mstr_department.department_id')
             ->join('mstr_class_program','acd_student_krs.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
             ->join('mstr_term_year','acd_student_krs.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
             ->groupBy('acd_student_krs.Student_Id')
             ->where('acd_student_krs.Term_Year_Id', $term_year)
             ->where('mstr_department.department_id', $department)
             ->where('acd_student_krs.Class_Prog_Id', $class_program)
             ->where('acd_student_krs.Is_Approved', 1)
             ->where(function($query){
                $search = Input::get('search');
                $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
                $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
              })
             ->paginate($rowpage);
           }

         $select_term_year = DB::table('mstr_term_year')
         ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
         ->get();

         $event_sched = DB::table('mstr_event_sched')
         ->where('Department_Id',$department)
         ->where('Term_Year_Id',$term_year)
         ->where('Event_Id',1)
        //  ->where('Is_Open',1)
         ->first();
         $tutupan = '';
         $date_now = Date('Y-m-d');
         if($event_sched){
           if($event_sched->End_Date_Cost < $date_now){
            $tutupan = 1;
           }
         }

         $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
         return view('laporan_dosenmengajar/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('tutupan', $tutupan);
    }

    public function get_data(Request $request,$term_year,$department,$class_program){
         $term_year = ($term_year == 0 ? null:$term_year);
         $department = ($department == 0 ? null:$department);
         $class_program = ($class_program == 0 ? null:$class_program);
         try {
          $data_all = DB::table('acd_offered_course_lecturer as a')
                ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                ->join('emp_employee as c','c.Employee_Id','=','a.Employee_Id')
                ->where('b.Term_Year_Id', 'like' ,'%'.$term_year.'%')
                ->where('b.Department_Id', 'like' ,'%'.$department.'%')
                ->where('b.Class_Prog_Id', 'like' ,'%'.$class_program.'%')
                ->groupby('c.Employee_Id')
                ->select('c.Employee_Id','c.Full_Name','a.Offered_Course_id')
                ->get();
          $data = [];
          $x = 0;
          foreach ($data_all as $key) {
            $bebansksreals = DB::table('acd_offered_course_lecturer as a')
            ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
            ->leftjoin('acd_course_curriculum as c' ,function ($join)
              {
                $join->on('c.Department_Id','=','b.Department_Id')
                ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
                ->on('c.Course_Id','=','b.Course_Id');
              })
            ->where('b.Term_Year_Id', $term_year)
            ->where('b.Department_Id', 'like' ,'%'.$department.'%')
            ->where('a.Employee_Id',$key->Employee_Id)
            ->get();

            $beban_bagi_dosen = 0;
            foreach ($bebansksreals as $bebansksreal) {
              $all_dosen = DB::table('acd_offered_course_lecturer as a')
              ->where('Offered_Course_id',$bebansksreal->Offered_Course_id)
              ->count();
              $beban_bagi_dosen = $beban_bagi_dosen+($bebansksreal->Applied_Sks/$all_dosen);
            }

            // ->select(DB::raw('(SUM(c.Applied_Sks)) as beban_sks'))->first();

            $bebansks = DB::table('emp_lecturer_work_load')
            ->where([['Term_Year_Id',$term_year],['Employee_Id',$key->Employee_Id]])
            ->first();

            $jml_mk = DB::table('acd_offered_course_lecturer as a')
                ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                ->join('acd_course as c','b.Course_Id','=','c.Course_Id')
                ->join('mstr_department as d','b.Department_Id','=','d.Department_Id')
                ->join('mstr_term_year as e','b.Term_Year_Id','=','e.Term_Year_Id')
                ->join('mstr_class as f','b.Class_Id','=','f.Class_Id')
                ->join('mstr_class_program as g','b.Class_Prog_id','=','g.Class_Prog_Id')
                ->where('a.Employee_Id',$key->Employee_Id)
                ->where('b.Term_Year_Id', 'like' ,'%'.$term_year.'%')
                ->where('b.Department_Id', 'like' ,'%'.$department.'%')
                ->where('b.Class_Prog_Id', 'like' ,'%'.$class_program.'%')
                ->get();

            $data[$x]['Offered_Course_id'] = $key->Offered_Course_id;
            $data[$x]['Employee_Id'] = $key->Employee_Id;
            $data[$x]['jml_mk'] = count($jml_mk);
            $data[$x]['Name'] = $key->Full_Name;
            $data[$x]['beban_sks_real'] = number_format($beban_bagi_dosen,2);
            $data[$x]['beban_sks'] = ($bebansks ? $bebansks->Sks:'');
            $x++;
          }
          $data = json_encode($data);
          $data = json_decode($data);
          $total = count($data);
            return response()->json([
              "success" => true,
              "data" => $data,
              "total" => $total,
          ], 200);
        } catch (\Exception $e) {
          return response()->json([
              "success" => false,
              "data" => $e
          ], 200);
        }
    }
       
    public function get_ajardosen(Request $request,$dosen,$term_year,$department,$class_program){
         $term_year = ($term_year == 0 ? null:$term_year);
         $department = ($department == 0 ? null:$department);
         $class_program = ($class_program == 0 ? null:$class_program);
         try {
          $data_all = DB::table('acd_offered_course_lecturer as a')
                ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                ->join('acd_course as c','b.Course_Id','=','c.Course_Id')
                ->join('mstr_department as d','b.Department_Id','=','d.Department_Id')
                ->join('mstr_term_year as e','b.Term_Year_Id','=','e.Term_Year_Id')
                ->join('mstr_class as f','b.Class_Id','=','f.Class_Id')
                ->join('mstr_class_program as g','b.Class_Prog_id','=','g.Class_Prog_Id')
                ->where('a.Employee_Id',$dosen)
                ->where('b.Term_Year_Id', 'like' ,'%'.$term_year.'%')
                ->where('b.Department_Id', 'like' ,'%'.$department.'%')
                ->where('b.Class_Prog_Id', 'like' ,'%'.$class_program.'%')
                ->get();
          $data = [];
          $beban_bagi_dosen = 0;
          $x = 0;
          foreach ($data_all as $key) {
            $totalpertemuan = DB::table('acd_sched_real')
            ->join('acd_sched_real_employee as ee','acd_sched_real.Sched_Real_Id','=','ee.Sched_Real_Id')
            ->where('Course_Id',$key->Course_Id)
            ->where('Term_Year_Id',$key->Term_Year_Id)
            ->where('Class_Prog_Id',$key->Class_Prog_Id)
            ->where('Class_Id',$key->Class_Id)
            ->where('ee.Employee_Id',$key->Employee_Id)
            ->count();
            $sks = DB::table('acd_course_curriculum')
            ->where([['Course_Id',$key->Course_Id],['Department_Id',$key->Department_Id],['Class_Prog_Id',$key->Class_Prog_Id]])
            ->first();

            $all_dosen = DB::table('acd_offered_course_lecturer as a')
            ->where('Offered_Course_id',$key->Offered_Course_id)
            ->count();
            $beban_bagi_dosen = $sks->Applied_Sks/$all_dosen;

            $data[$x]['Offered_Course_id'] = $key->Offered_Course_id;
            $data[$x]['Employee_Id'] = $key->Employee_Id;
            $data[$x]['Term_Year_Name'] = $key->Term_Year_Name;
            $data[$x]['Term_Year_Id'] = $key->Term_Year_Id;
            $data[$x]['Course_Code'] = $key->Course_Code;
            $data[$x]['Course_Name'] = $key->Course_Name;
            $data[$x]['Class_Name'] = $key->Class_Name;
            $data[$x]['Class_Program_Name'] = $key->Class_Program_Name;
            $data[$x]['totalpertemuan'] = $totalpertemuan;
            $data[$x]['sks'] = ($sks ? $sks->Applied_Sks:'');
            $data[$x]['sks_dosen'] = number_format($beban_bagi_dosen,2);;
            $x++;
          }

          $data = json_encode($data);
          $data = json_decode($data);
          $total = count($data);
            return response()->json([
              "success" => true,
              "data" => $data,
              "total" => $total,
          ], 200);
        } catch (\Exception $e) {
          return response()->json([
              "success" => false,
              "data" => $e
          ], 200);
        }
    }

    public function get_sesikuliah(Request $request,$oci,$term_year,$department,$class_program){
        //  dd([[$term_year],[$department],[$class_program]]);
         $term_year = ($term_year == 0 ? null:$term_year);
         $department = ($department == 0 ? null:$department);
         $class_program = ($class_program == 0 ? null:$class_program);
         try {
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
          ->where('acd_offered_course.Offered_Course_id', $oci)
          ->select( 'acd_offered_course.*','mstr_class_program.Class_Prog_Id','acd_course.*','acd_student.Student_Id','mstr_class.Class_Name', 
                    DB::raw('COUNT(acd_student_krs.Student_Id) as jml_peserta'))
          ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
          ->first();
        // $data_all = AcdSchedReal::where('Course_Id',$offeredcourse->Course_Id)
        //     ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
        //     ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
        //     ->where('Class_Id',$offeredcourse->Class_Id)
        //     ->orderBy('Course_Id')
        //     ->with('empEmployees')
        //     ->whereHas('empEmployees', function($q)use($request){
        //         $q->where('emp_employee.Employee_Id', '=',$request->Employee_Id );
        //     })
        //     ->withCount('acdStudents')->get();
        $emp = DB::table('emp_employee')->where('Employee_Id',$request->Employee_Id)->first();
        $data_all = AcdSchedReal::join('acd_sched_real_employee as emp','emp.Sched_Real_Id','=','acd_sched_real.Sched_Real_Id')
          ->where('Course_Id',$offeredcourse->Course_Id)
          ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
          ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
          ->where('Class_Id',$offeredcourse->Class_Id)
          ->where('Created_By',$emp->Email_Corporate)
          ->orderBy('Course_Id')
          ->with('empEmployees')
          // ->whereHas('empEmployees', function($q)use($request){
          //     $q->where('emp_employee.Employee_Id', '=',$request->Employee_Id );
          // })
          ->withCount('acdStudents')->get();
          
        $totalpertemuan = AcdSchedReal::where('Course_Id',$offeredcourse->Course_Id)
              ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
              ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
              ->where('Class_Id',$offeredcourse->Class_Id)
              ->count();
          $data = [];
          $x = 0;
          
          foreach ($data_all as $key) {
            $date = explode(' ',$key->Date);
            $tanggal = $this->tanggal_indo($date[0],false);
            $ja = explode(':',$date[1]);
            unset($ja[2]);
            $jam = implode(':',$ja);
            $tgl_jam = $tanggal.' '.$jam;

            $datedb = explode(' ',$key->Created_Date);
            $tanggaldb = $this->tanggal_indo($datedb[0],false);
            $jadb = explode(':',$datedb[1]);
            unset($jadb[2]);
            $jamdb = implode(':',$ja);
            // $tgl_jamdb = $tanggaldb.' '.$jamdb;
            $tgl_jamdb = $tanggaldb;

            $data[$x]['Offered_Course_id'] = $key->Offered_Course_id;
            $data[$x]['pertemuan'] = $x+1;
            $data[$x]['tgl_jam'] = $tgl_jam;
            $data[$x]['tgl_jamdb'] = $tgl_jamdb;
            $data[$x]['Course_Content'] = $key->Course_Content;
            $data[$x]['Description'] = $key->Description;
            $data[$x]['jml_peserta'] = $offeredcourse->jml_peserta;
            $data[$x]['acd_students_count'] = $key->acd_students_count;
            $x++;
          }
          $data = json_encode($data);
          $data = json_decode($data);
          $total = count($data);
            return response()->json([
              "success" => true,
              "data" => $data,
              "total" => $total,
          ], 200);
        } catch (\Exception $e) {
          return response()->json([
              "success" => false,
              "data" => $e
          ], 200);
        }
    }

    private function tanggal_indo($tanggal, $cetak_hari = false)
    {
      $hari = array ( 1 =>    'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
          );

      $bulan = array (1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
          );
      $split 	  = explode('-', $tanggal);
      $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

      if ($cetak_hari) {
          $num = date('N', strtotime($tanggal));
          return $hari[$num] . ', ' . $tgl_indo;
        }
        return $tgl_indo;
      }

       public function show($id)
       {

       }


       // public function modal()
       // {
       //   return view('mstr_class_program/modal');
       // }
       /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
       public function create()
       {

       }

       /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
       public function store(Request $request)
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
       public function destroy(Request $request, $id)
       {

       }

       public function exportdata($term_year, $department, $class_program){
          Excel::create('Dosen Mengajar', function ($excel) use($department, $term_year, $class_program){       
          $term_year = ($term_year == 0 ? null:$term_year);
          $department = ($department == 0 ? null:$department);
          $class_program = ($class_program == 0 ? null:$class_program);

          $data_all = DB::table('acd_offered_course_lecturer as a')
                ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                ->join('emp_employee as c','c.Employee_Id','=','a.Employee_Id')
                ->where('b.Term_Year_Id', 'like' ,'%'.$term_year.'%')
                ->where('b.Department_Id', 'like' ,'%'.$department.'%')
                ->where('b.Class_Prog_Id', 'like' ,'%'.$class_program.'%')
                ->groupby('c.Employee_Id')
                ->get();
          
          $datas = [];
          $x = 0;
          foreach ($data_all as $key) {
            $bebansks = DB::table('acd_offered_course_lecturer as a')
            ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
            ->leftjoin('acd_course_curriculum as c' ,function ($join)
              {
                $join->on('c.Department_Id','=','b.Department_Id')
                ->on('c.Class_Prog_Id','=','b.Class_Prog_Id')
                ->on('c.Course_Id','=','b.Course_Id');
              })
            ->where('b.Term_Year_Id', $term_year)
            ->where('a.Employee_Id',$key->Employee_Id)
            ->select(DB::raw('(SUM(c.Applied_Sks)) as beban_sks'))->first();

            $datas[$x]['Dosen']['Offered_Course_id'] = $key->Offered_Course_id;
            $datas[$x]['Dosen']['Employee_Id'] = $key->Employee_Id;
            $datas[$x]['Dosen']['Name'] = $key->Full_Name;
            $datas[$x]['Dosen']['beban_sks'] = $bebansks->beban_sks;

            $ajar_dosen = DB::table('acd_offered_course_lecturer as a')
                  ->join('acd_offered_course as b','a.Offered_Course_id','=','b.Offered_Course_id')
                  ->join('acd_course as c','b.Course_Id','=','c.Course_Id')
                  ->join('mstr_department as d','b.Department_Id','=','d.Department_Id')
                  ->join('mstr_term_year as e','b.Term_Year_Id','=','e.Term_Year_Id')
                  ->join('mstr_class as f','b.Class_Id','=','f.Class_Id')
                  ->join('mstr_class_program as g','b.Class_Prog_id','=','g.Class_Prog_Id')
                  ->where('a.Employee_Id',$key->Employee_Id)
                  ->where('b.Term_Year_Id', 'like' ,'%'.$term_year.'%')
                  ->where('b.Department_Id', 'like' ,'%'.$department.'%')
                  ->where('b.Class_Prog_Id', 'like' ,'%'.$class_program.'%')
                  ->get();
  
            $xx = 0;
            foreach ($ajar_dosen as $key2) {
              $totalpertemuan = DB::table('acd_sched_real')
                        ->where('Course_Id',$key2->Course_Id)
                        ->where('Term_Year_Id',$key2->Term_Year_Id)
                        ->where('Class_Prog_Id',$key2->Class_Prog_Id)
                        ->where('Class_Id',$key2->Class_Id)
                        ->count();
  
              $datas[$x]['Dosen']['AjarDosen'][$xx]['Offered_Course_id'] = $key2->Offered_Course_id;
              $datas[$x]['Dosen']['AjarDosen'][$xx]['Term_Year_Name'] = $key2->Term_Year_Name;
              $datas[$x]['Dosen']['AjarDosen'][$xx]['Term_Year_Id'] = $key2->Term_Year_Id;
              $datas[$x]['Dosen']['AjarDosen'][$xx]['Course_Code'] = $key2->Course_Code;
              $datas[$x]['Dosen']['AjarDosen'][$xx]['Course_Name'] = $key2->Course_Name;
              $datas[$x]['Dosen']['AjarDosen'][$xx]['Class_Name'] = $key2->Class_Name;
              $datas[$x]['Dosen']['AjarDosen'][$xx]['Class_Program_Name'] = $key2->Class_Program_Name;
              $datas[$x]['Dosen']['AjarDosen'][$xx]['totalpertemuan'] = $totalpertemuan;

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
                ->where('acd_offered_course.Offered_Course_id', $key2->Offered_Course_id)
                ->select( 'acd_offered_course.*','mstr_class_program.Class_Prog_Id','acd_course.*','acd_student.Student_Id','mstr_class.Class_Name', 
                          DB::raw('COUNT(acd_student_krs.Student_Id) as jml_peserta'))
                ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
                ->first();
              $pertemuan = AcdSchedReal::join('acd_sched_real_employee as ee','acd_sched_real.Sched_Real_Id','=','ee.Sched_Real_Id')
                  ->where('Course_Id',$offeredcourse->Course_Id)
                  ->where('Term_Year_Id',$offeredcourse->Term_Year_Id)
                  ->where('Class_Prog_Id',$offeredcourse->Class_Prog_Id)
                  ->where('Class_Id',$offeredcourse->Class_Id)
                  ->where('ee.Employee_Id',$key->Employee_Id)
                  ->orderBy('Course_Id')->with('empEmployees')
                  ->withCount('acdStudents')->get();
                    
              $xxx = 0;
              if(count($pertemuan)){
                foreach ($pertemuan as $key3) {
                  $date = explode(' ',$key3->Date);
                  $tanggal = $this->tanggal_indo($date[0],false);
                  $ja = explode(':',$date[1]);
                  unset($ja[2]);
                  $jam = implode(':',$ja);
                  $tgl_jam = $tanggal.' '.$jam;

                  $datedb = explode(' ',$key3->Created_Date);
                  $tanggaldb = $this->tanggal_indo($datedb[0],false);
                  $jadb = explode(':',$datedb[1]);
                  unset($jadb[2]);
                  $jamdb = implode(':',$ja);
                  $tgl_jamdb = $tanggaldb;
  
                  $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'][$xxx]['Offered_Course_id'] = $key2->Offered_Course_id;
                  $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'][$xxx]['pertemuan'] = $xxx+1;
                  $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'][$xxx]['tgl_jam'] = $tgl_jam;
                  $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'][$xxx]['tgl_jamdb'] = $tgl_jamdb;
                  $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'][$xxx]['Course_Content'] = $key3->Course_Content;
                  $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'][$xxx]['Description'] = $key3->Description;
                  $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'][$xxx]['jml_peserta'] = $offeredcourse->jml_peserta;
                  $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'][$xxx]['acd_students_count'] = $key3->acd_students_count;
                  $xxx++;
                }
              }else{
                $datas[$x]['Dosen']['AjarDosen'][$xx]['pertemuan'] = null;
              }
              $xx++;
            }
            $x++;
          }

          $items = $datas;


            if (count($items) == 0) {
              $data = [
                  [
                    'NO' => '',
                    'NIM' => '',
                    'Nama Mahasiswa' => '',
                    'Kelas Program' => '',
                    'Jumlah Matakuliah' => '',
                    'Jumlah SKS' => '',
                    'Jumlah Biaya KRS' => '',
                    'Tagihan KRS' => '',
                  ]
              ];
          }

          $i = 1;
          foreach ($items as $item){
            $ii = 1;
            foreach ($item['Dosen']['AjarDosen'] as $dosen) {
              $iii = 1;
              // dd($dosen['pertemuan']);
              if($dosen['pertemuan'] != null){
                foreach ($dosen['pertemuan'] as $pertemuan) {
                  // dd([$item['Dosen'],[$dosen],[$pertemuan]]);                  
                  $data[] = 
                    [
                      'NO' => $iii,
                      'Nama' => $item['Dosen']['Name'],
                      'Semester' => $dosen['Term_Year_Name'],
                      'Kode Matakuliah' => $dosen['Course_Code'],
                      'Matakuliah' => $dosen['Course_Name'],
                      'Kelas' => $dosen['Class_Name'],
                      'Program Kelas' => $dosen['Class_Program_Name'],
                      'Tanggal Jam' => $pertemuan['tgl_jam'],
                      'Tanggal Dibuat' => $pertemuan['tgl_jamdb'],
                      'Konten' => $pertemuan['Course_Content'],
                      'Deskripsi' => $pertemuan['Description'],
                      'Jml Peserta' => $pertemuan['jml_peserta'],
                      'Mhs Hadir' => $pertemuan['acd_students_count'],
                  ];
                  $iii++;
                }
              }
              else{
                $data[] = 
                  [
                    'NO' => $iii,
                    'Nama' => $item['Dosen']['Name'],
                    'Semester' => $dosen['Term_Year_Name'],
                    'Kode Matakuliah' => $dosen['Course_Code'],
                    'Matakuliah' => $dosen['Course_Name'],
                    'Kelas' => $dosen['Class_Name'],
                    'Program Kelas' => $dosen['Class_Program_Name'],
                    'Tanggal Jam' => '',
                    'Konten' => '',
                    'Deskripsi' => '',
                    'Jml Peserta' => '',
                    'Mhs Hadir' => '',
                ];
              }
              $ii++;
            }
            $i++;
          }
          // dd($data);

          $excel->sheet('Dosen Mengajar', function ($sheet) use ($data,$items) {
            // dd($items);
              $sheet->fromArray($data, null, 'A1');

              $num_rows = sizeof($data) + 1;

              for ($i = 1; $i <= $num_rows; $i++) { 
                  $rows[$i] = 18;
              }

              $rows[1] = 30;

              $sheet->setAutoSize(true);

              $sheet->setStyle([
                  'font' => [
                      'name' => 'Arial',
                      'size' => 10
                  ]
              ]);

              $sheet->setAllBorders('none');

              $sheet->setHeight($rows);

              $sheet->setWidth([
                  'A' => 6,
                  'B' => 20,
                  'C' => 20,
                  'D' => 15,
                  'E' => 40,
                  'F' => 20,
              ]);
              
              $sheet->setHorizontalCentered(true);

              for ($i = 1; $i <= $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $row->setValignment('center');
                  });
              }

              for ($i = 1; $i > $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $cells->setAlignment('center');
                  });
              }
              
              $sheet->setBorder('A1:M' . (sizeof($data) + 1), 'thin');

              $sheet->setHorizontalCentered(true);

              $sheet->cells('A1:M1', function ($cells) {
                  $cells->setBackground('#97D86E');
                  $cells->setFontWeight('bold');
                  $cells->setAlignment('center');
              });
              // $sheet->cells('Q1', function ($cells) {
              //     $cells->setBackground('#F0FF00');
              //     $cells->setFontWeight('bold');
              //     $cells->setAlignment('center');
              // });
              // $sheet->cells('R1:T1', function ($cells) {
              //     $cells->setBackground('#FF3939');
              //     $cells->setFontWeight('bold');
              //     $cells->setAlignment('center');
              // });
              // foreach ($data as $dt) {
              //       $no = ($dt['NO'] + 1);
              //       if ($dt['SKS'] == null || $dt['Semester'] == null || $dt['SKS Transkrip'] == null) {
              //           $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
              //               $cells->setBackground('#ff0000');
              //               $cells->setFontColor('#ffffff');
              //               $cells->setAlignment('center');
              //           });
              //       }else{
              //         $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
              //               $cells->setAlignment('center');
              //       });
              //     }
              //   }

              foreach ($data as $dt) {
                  $sheet->cells('D' . $i . ':E' . sizeof($data), function ($cells) {
                      $cells->setAlignment('center');
                  });
                }

              // $last = $i+1;
              // $sheet->cells('E2:E9999', function ($cells) {
              //             $cells->setAlignment('center');
              //     });
              // $sheet->setCellValue('B'.$last, 'STIKES MUHAMMADIYAH PALEMBANG');
          });
      })->export('xls');
    }
  }
