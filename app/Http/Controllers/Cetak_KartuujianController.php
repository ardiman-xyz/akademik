<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use App\Http\Models\StrukturalData;
use Input;
use DB;
use Redirect;
use Alert;
use Storage;
use Auth;
use Image;
use File;
use Excel;
use PDF;

class Cetak_KartuujianController extends Controller
{

  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','show']]);
    // $this->middleware('access:CanAdd', ['only' => ['create','store']]);
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
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $entry_year = Input::get('entry_year');
       $department = Input::get('department');
       $status = Input::get('status');
       $nimawal = Input::get('nimawal');
        $nimakhir = Input::get('nimakhir');

       if($status == null){
       }


       $select_entry_year = DB::table('mstr_entry_year')
       ->orderBy('mstr_entry_year.Entry_Year_Id', 'desc')
       ->get();

       $select_status = DB::table('mstr_status')
       ->orderby('Status_Id')->get();

       $select_term_year = DB::table('mstr_term_year')->orderby('Term_Year_Name','desc')->get();
       $select_exam_type = DB::table('mstr_exam_type')->orderby('Exam_Type_Code','desc')->get();

    if($FacultyId==""){
      if($DepartmentId ==""){
        $select_department = DB::table('mstr_department')
      ->wherenotnull('Faculty_Id')
      ->orderBy('mstr_department.department_code', 'asc')
      ->get();
      }else{
        $select_department = DB::table('mstr_department')
      ->wherenotnull('Faculty_Id')
      ->where('Department_Id',$DepartmentId)
      ->orderBy('mstr_department.department_code', 'asc')
      ->get();
      }
    }else{
       if($DepartmentId == ""){
        $select_department = DB::table('mstr_department')
        ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->orderBy('mstr_department.department_code', 'asc')
        ->get();
       }else{
        $select_department = DB::table('mstr_department')
        ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('mstr_department.Department_Id',$DepartmentId)
        ->orderBy('mstr_department.department_code', 'asc')
        ->get();
       }
    }
      if ($search == null) {
        if($status == null){       
        $data = DB::table('acd_student')
        ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
        // ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student.Class_Prog_Id')
        ->leftjoin('mstr_class','mstr_class.Class_Id','=','acd_student.Class_Id')
        ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')

        ->where('acd_student.Entry_Year_Id', 'like' ,'%'.$entry_year.'%')
        ->where('acd_student.Department_Id','like' , '%'.$department.'%')
        ->orderBy('acd_student.Nim', 'asc')
        ->paginate($rowpage);
        } else{
          $data = DB::table('acd_student')
        ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
        // ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student.Class_Prog_Id')
        ->leftjoin('mstr_class','mstr_class.Class_Id','=','acd_student.Class_Id')
        ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')

        ->where('acd_student.Entry_Year_Id', 'like' ,'%'.$entry_year.'%')
        ->where('acd_student.Department_Id','like' , '%'.$department.'%')
        ->where('acd_student.Status_Id', $status)
        ->orderBy('acd_student.Nim', 'asc')
        ->paginate($rowpage);
        }

      }else {
        if($status == null){
        $data = DB::table('acd_student')
        ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
        // ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student.Class_Prog_Id')
        ->leftjoin('mstr_class','mstr_class.Class_Id','=','acd_student.Class_Id')
        ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')
        ->where('acd_student.Entry_Year_Id', 'like' ,'%'.$entry_year.'%')
        ->where('acd_student.Department_Id','like' , '%'.$department.'%')
        //->where('Full_Name', 'LIKE', '%'.$search.'%')
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
          $query->orwhere('Nim', 'LIKE', '%'.$search.'%');
          $query->orwhere('mstr_class_program.Class_Program_Name', 'LIKE', '%'.$search.'%');
        })
        ->orderBy('acd_student.Nim', 'asc')
        ->paginate($rowpage);
      } else{
        $data = DB::table('acd_student')
        ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
        // ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
        ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student.Class_Prog_Id')
        ->leftjoin('mstr_class','mstr_class.Class_Id','=','acd_student.Class_Id')
        ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')
        ->where('acd_student.Entry_Year_Id', 'like' ,'%'.$entry_year.'%')
        ->where('acd_student.Department_Id','like' , '%'.$department.'%')
        // ->where('acd_student.Status_Id', $status)
        // ->where('Full_Name', 'LIKE', '%'.$search.'%')
        // ->where(function($query){
        //   $search = Input::get('search');
        //   $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
        //   $query->orwhere('Nim', 'LIKE', '%'.$search.'%');
        // })
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
          $query->orwhere('Nim', 'LIKE', '%'.$search.'%');
          $query->whereRaw("lower(mstr_class_program.Class_Program_Name) like '%" . strtolower($search) . "%'");
        })
        ->orderBy('acd_student.Nim', 'asc')
        ->paginate($rowpage);
      }
      }

    $count_dep = "";
    $count_dep = DB::table('acd_student')->count();
    if($department!=0){
      $count_dep = db::table('acd_student')->where('Department_Id', $department)->count();
    }
    if($entry_year!=0){
      $count_dep = db::table('acd_student')->where('Department_Id', $department)->where('Entry_Year_Id', $entry_year)->count();
    }

    $select_nim = DB::table('acd_student')
      ->where('acd_student.department_id', $department)
      ->where('acd_student.Entry_Year_Id', $entry_year)
      // ->where(function($query){
      //     $query->where('Status_Id','1');
      //     $query->orwhere('Status_Id','2');
      //   })
      ->orderBy('acd_student.Nim', 'asc')
      ->get();

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'entry_year'=> $entry_year, 'department'=> $department,'status'=>$status]);
       return view('cetak_kartuujian/index')
       ->with('select_status',$select_status)
       ->with('select_term_year',$select_term_year)
       ->with('select_exam_type',$select_exam_type)
       ->with('select_nim',$select_nim)
       ->with('nimawal',$nimawal)
       ->with('nimakhir',$nimakhir)
       ->with('status',$status)->with('count_dep',$count_dep)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_entry_year', $select_entry_year)->with('entry_year', $entry_year)->with('select_department', $select_department)->with('department', $department);
     }

     public function get_data(Request $request)
     {
      //  echo json_encode($request->all());
        $search = $request->search;
        $datas = DB::table('acd_student as a')
              ->join('mstr_department as b','a.Department_Id','=','b.Department_Id')
              ->where(function($query)use($search){
                $query->where('a.Nim', $search);
                // $query->where('a.Nim', 'LIKE', '%'.$search.'%');
              })
              ->get();
        $response = [
          'success' => 'true',
          'data' => $datas,
          'total' => $datas->count()
        ];

        return response()->json($response);
     }

     public function post_data(Request $request)
     {
      //  echo json_encode($request->all());
        $nim = $request->nim;
        $new_rfid = $request->new_rfid;
        $datas = DB::table('acd_student as a')
              ->join('mstr_department as b','a.Department_Id','=','b.Department_Id')
              ->where(function($query)use($nim){
                $query->where('a.Nim', 'LIKE', '%'.$nim.'%');
              })
              ->update(['Rfid' => $new_rfid]);
        $response = [
          'success' => 'true',
          'data' => $new_rfid,
          'total' => 1
        ];

        return response()->json($response);
     }
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
     public function destroy(Request $request,$id)
     {
       
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
    public function exportdata($department, $termyear ,$nim,$exam_type){
      if($exam_type == 3){
        $examTypeName = DB::table('mstr_exam_type')->where('Exam_Type_Id',$exam_type)->first();
        $termyear = DB::table('mstr_term_year as a')
        ->join('mstr_term as b','a.Term_Id','=','b.Term_Id')
        ->where('a.Term_Year_Id',$termyear)
        ->first();
        $studentdata = DB::table('acd_student')->where('Nim',$nim)
        ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
        ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
        ->select('acd_student.Register_Number','acd_student.Student_Id','acd_student.Nim','acd_student.Full_Name','acd_student.Entry_Year_Id','mstr_department.Department_Name','mstr_education_program_type.Acronym')
        ->get();

        $i = 0;
        $data = [];
        foreach ($studentdata as $key) {
          // $peserta = DB::table('acd_offered_course_exam_member')
          //           ->join('acd_offered_course_exam','acd_offered_course_exam.Offered_Course_Exam_Id','=','acd_offered_course_exam_member.Offered_Course_Exam_Id')
          //           ->join('acd_offered_course','acd_offered_course.Offered_Course_Id','=','acd_offered_course_exam.Offered_Course_Id')
          //           ->join('acd_student_krs' ,function ($join)
          //           {
          //             $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          //             ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          //             ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
          //             ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id')
          //             ->on('acd_student_krs.Student_Id','=','acd_offered_course_exam_member.Student_Id');
          //           })
          //           ->where('acd_offered_course_exam.Offered_Course_Exam_Id',$data->Offered_Course_Exam_Id)      
          //           ->where('acd_student_krs.Is_Remediasi',1)               
          //           ->get();

          $data[$i] = DB::table('acd_student_krs')
                  ->join('acd_offered_course as c' ,function ($join)
                  {
                    $join->on('acd_student_krs.Term_Year_Id','=','c.Term_Year_Id')
                    ->on('acd_student_krs.Class_Prog_Id','=','c.Class_Prog_Id')
                    ->on('acd_student_krs.Course_Id','=','c.Course_Id')
                    ->on('acd_student_krs.Class_Id','=','c.Class_Id');
                  })
                  // // ->join('acd_offered_course as x','b.Offered_Course_Id','=','x.Offered_Course_Id')
                  ->leftjoin('acd_offered_course_exam as b','b.Offered_Course_Id','=','c.Offered_Course_Id')
                  // ->leftjoin('acd_offered_course_exam_member as a','a.Offered_Course_Exam_Id','=','b.Offered_Course_Exam_Id')
                  ->join('acd_course as d','c.Course_Id','=','d.Course_Id')
                  ->leftjoin('mstr_room as e','b.Room_Id','=','e.Room_Id')
                  ->join('mstr_class as f','c.Class_Id','=','f.Class_Id')                
                  ->where([['acd_student_krs.Student_Id',$key->Student_Id],['b.Exam_Type_Id',$exam_type],['acd_student_krs.Is_Remediasi',1]])
                  ->select('b.*','c.*','d.*','e.*','f.*','acd_student_krs.*',
                    DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course_exam.Offered_Course_id = c.Offered_Course_Id) as start_date'),
                    DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = c.Offered_Course_id) as id_dosen')
                  )
                  ->groupby('c.Offered_Course_Id')
                  ->get();
          $i++;
        }
        $date = date('d-m-Y');

        $kaprodi = DB::table('emp_employee')
              ->select('emp_employee.Full_Name', 'emp_employee.Nidn', 'emp_structural.Structural_Name','emp_employee_structural.Sk_Date')
              ->leftJoin('emp_employee_structural', 'emp_employee.Employee_Id', '=', 'emp_employee_structural.Employee_Id')
              ->leftJoin('emp_structural', 'emp_employee_structural.Structural_Id', '=', 'emp_structural.Structural_Id')
  //            ->leftJoin('mstr_department', 'emp_employee_structural.Department_Id', 'mstr_department.Department_Id')
              ->where([
                  'emp_employee_structural.Structural_Id' => 30,
                  'emp_employee_structural.Work_Unit_Id' => $department
              ])
              ->orderBy('emp_employee_structural.Sk_Date', 'desc')
              ->get();

        View()->share(['studentdata'=>$studentdata,
                      'examTypeName'=>$examTypeName,
                      'studentId'=>$studentdata[0]->Student_Id,
                      'studentReg'=>$studentdata[0]->Register_Number,
                      'exam_type'=>$exam_type,
                      'data'=>$data,
                      'date'=>$date,
                      'kaprodi'=>$kaprodi,
                      'kaprodi'=>$kaprodi,
                      'termyear'=>$termyear]);

        $pdf = PDF::loadView('cetak_kartuujian/cetak_kartuujian');
        return $pdf->stream('ktm.pdf');
      }else{
      $examTypeName = DB::table('mstr_exam_type')->where('Exam_Type_Id',$exam_type)->first();
      $termyear = DB::table('mstr_term_year as a')
      ->join('mstr_term as b','a.Term_Id','=','b.Term_Id')
      ->where('a.Term_Year_Id',$termyear)
      ->first();
      $studentdata = DB::table('acd_student')->where('Nim',$nim)
      ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
      ->select('acd_student.Register_Number','acd_student.Student_Id','acd_student.Nim','acd_student.Full_Name','acd_student.Entry_Year_Id','mstr_department.Department_Name','mstr_education_program_type.Acronym')
      ->get();

      $i = 0;
      $data = [];
      foreach ($studentdata as $key) {
        $data[$i] = DB::table('acd_student_krs')
                ->join('acd_offered_course as c' ,function ($join)
                {
                  $join->on('acd_student_krs.Term_Year_Id','=','c.Term_Year_Id')
                  ->on('acd_student_krs.Class_Prog_Id','=','c.Class_Prog_Id')
                  ->on('acd_student_krs.Course_Id','=','c.Course_Id')
                  ->on('acd_student_krs.Class_Id','=','c.Class_Id');
                })
                // // ->join('acd_offered_course as x','b.Offered_Course_Id','=','x.Offered_Course_Id')
                ->leftjoin('acd_offered_course_exam as b','b.Offered_Course_Id','=','c.Offered_Course_Id')
                // ->leftjoin('acd_offered_course_exam_member as a','a.Offered_Course_Exam_Id','=','b.Offered_Course_Exam_Id')
                ->join('acd_course as d','c.Course_Id','=','d.Course_Id')
                ->leftjoin('mstr_room as e','b.Room_Id','=','e.Room_Id')
                ->join('mstr_class as f','c.Class_Id','=','f.Class_Id')                
                ->where([['acd_student_krs.Student_Id',$key->Student_Id],['b.Exam_Type_Id',$exam_type],['acd_student_krs.Term_Year_Id',$termyear->Term_Year_Id]])
                // ->orwhere([['acd_student_krs.Student_Id',$key->Student_Id],['b.Offered_Course_Exam_Id',null]])
                // ->orwhere('b.Exam_Type_Id',$exam_type)
                // ->where('acd_student_krs.Nim',$key->Nim)
                // ->where('a.Student_Id',$key->Student_Id)
                ->select('b.*','c.*','d.*','e.*','f.*','acd_student_krs.*',
                  DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course_exam.Offered_Course_id = c.Offered_Course_Id) as start_date'),
                  DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = c.Offered_Course_id) as id_dosen')
                )
                ->groupby('c.Offered_Course_Id')
                ->get();

        // $cektagihan = DB::table('fnc_student_bill')->where('Register_Number',$key->Register_Number)->where('Payment_Order',2)->get();
        // $cekpembayaran = DB::table('fnc_student_payment')->where('Register_Number',$key->Register_Number)->where('Installment_Order',2)->get();
        // $tgh = 0;
        // $cekdetailtagihan = [];
        // $tagihan_now = 0;
        // if(count($cektagihan) > 0){
        //   foreach ($cektagihan as $cektgh) {          
        //     $cekdetailtagihan = DB::table('fnc_student_bill_detail')->where('Student_Bill_Id',$cektgh->Student_Bill_Id)->get();
        //     if(count($cekpembayaran) > 0){
        //       foreach ($cekpembayaran as $cekby) {
        //           $tagihan_now = $cekby->Payment_Amount - $cekdetailtagihan[0]->Amount;
        //         }
        //       }else{
        //         $tagihan_now = $cekdetailtagihan[0]->Amount;
        //       }
        //     $tgh++;
        //   }
        // }
        // dd($tagihan_now);
        // dd($key);
        $i++;
      }

      $date = date('d-m-Y');

      $kaprodi = DB::table('emp_employee')
            ->select('emp_employee.Full_Name', 'emp_employee.Nidn', 'emp_structural.Structural_Name','emp_employee_structural.Sk_Date')
            ->leftJoin('emp_employee_structural', 'emp_employee.Employee_Id', '=', 'emp_employee_structural.Employee_Id')
            ->leftJoin('emp_structural', 'emp_employee_structural.Structural_Id', '=', 'emp_structural.Structural_Id')
//            ->leftJoin('mstr_department', 'emp_employee_structural.Department_Id', 'mstr_department.Department_Id')
            ->where([
                'emp_employee_structural.Structural_Id' => 30,
                'emp_employee_structural.Work_Unit_Id' => $department
            ])
            ->orderBy('emp_employee_structural.Sk_Date', 'desc')
            ->get();

      View()->share(['studentdata'=>$studentdata,
                     'examTypeName'=>$examTypeName,
                     'data'=>$data,
                     'date'=>$date,
                     'kaprodi'=>$kaprodi,
                     'kaprodi'=>$kaprodi,
                     'termyear'=>$termyear]);

      $pdf = PDF::loadView('cetak_kartuujian/cetak_kartuujian');
      return $pdf->stream('ktm.pdf');
      }
    }

    public function exportdataall($department, $termyearid ,$nimawal,$exam_type,$nimakhir){      
      set_time_limit(300);
    // try {
      $examTypeName = DB::table('mstr_exam_type')->where('Exam_Type_Id',$exam_type)->first();
      $termyear = DB::table('mstr_term_year as a')
      ->join('mstr_term as b','a.Term_Id','=','b.Term_Id')
      ->where('a.Term_Year_Id',$termyearid)
      ->first();
      $studentdata = DB::table('acd_student')->where('Nim', '>=',$nimawal)->where('Nim', '<=',$nimakhir)
      ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
      ->select('acd_student.Student_Id','acd_student.Nim','acd_student.Full_Name','acd_student.Entry_Year_Id','mstr_department.Department_Name','mstr_education_program_type.Acronym')
      ->get();

      $i=0;
      $data = [];
      foreach ($studentdata as $key) {
         $krs_data = DB::table('acd_student_krs')
                ->join('acd_offered_course as c' ,function ($join)
                {
                  $join->on('acd_student_krs.Term_Year_Id','=','c.Term_Year_Id')
                  ->on('acd_student_krs.Class_Prog_Id','=','c.Class_Prog_Id')
                  ->on('acd_student_krs.Course_Id','=','c.Course_Id')
                  ->on('acd_student_krs.Class_Id','=','c.Class_Id');
                })
                // // ->join('acd_offered_course as x','b.Offered_Course_Id','=','x.Offered_Course_Id')
                ->leftjoin('acd_offered_course_exam as b','b.Offered_Course_Id','=','c.Offered_Course_Id')
                ->join('acd_offered_course_exam_member as g','g.Offered_Course_Exam_Id','=','b.Offered_Course_Exam_Id')
                ->join('acd_course as d','c.Course_Id','=','d.Course_Id')
                ->leftjoin('mstr_room as e','b.Room_Id','=','e.Room_Id')
                ->join('mstr_class as f','c.Class_Id','=','f.Class_Id')                
                ->where([
                  ['g.Student_Id',$key->Student_Id],
                  ['b.Exam_Type_Id',$exam_type],
                  ['acd_student_krs.Term_Year_Id',$termyearid]
                ])
                // ->orwhere([['acd_student_krs.Student_Id',$key->Student_Id],['b.Offered_Course_Exam_Id',null]])
                // ->orwhere('b.Exam_Type_Id',$exam_type)
                // ->where('acd_student_krs.Nim',$key->Nim)
                // ->where('a.Student_Id',$key->Student_Id)
                ->select(
                  'b.Offered_Course_Id','b.Exam_Type_Id','b.Room_Number','b.Room_Id','b.Exam_Start_Date',
                  'c.Department_Id','c.Class_Prog_Id',
                  'd.Course_Type_Id','d.Course_Code','d.Course_Name',
                  'e.Acronym','e.Room_Name','e.Room_Code',
                  'f.Class_Name',
                  'g.Student_Id','acd_student_krs.Term_Year_Id','acd_student_krs.Course_Id','acd_student_krs.Class_Id',
                  DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course_exam.Offered_Course_id = c.Offered_Course_Id) as start_date'),
                  DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = c.Offered_Course_id) as id_dosen')
                )
                ->groupby('c.Offered_Course_Id')
                ->get();
                
        $data[$i] = $krs_data;


                // $datas = DB::table('acd_offered_course_exam_member as a')
                //         ->leftjoin('acd_offered_course_exam as b','b.Offered_Course_Exam_Id','=','a.Offered_Course_Exam_Id')
                //         ->join('acd_offered_course as c','c.Offered_Course_Id','=','b.Offered_Course_Id')
                //         ->join('mstr_room as d','d.Room_Id','=','b.Room_Id')
                //         ->join('mstr_class as e','e.Class_Id','=','c.Class_Id') 
                //         ->join('acd_course as f','f.Course_Id','=','c.Course_Id')
                //         ->select('a.*','b.*','c.*','d.*','e.*','f.*',
                //             DB::raw('(SELECT Group_Concat(acd_offered_course_exam.Exam_Start_Date SEPARATOR "|") FROM acd_offered_course_exam WHERE acd_offered_course_exam.Offered_Course_id = c.Offered_Course_Id) as start_date'),
                //             DB::raw('(SELECT  Group_Concat( emp_employee.Employee_Id SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = c.Offered_Course_id) as id_dosen')
                //           )
                //         ->where('a.Student_Id',$studentdata[$i]->Student_Id)
                //         ->where('b.Exam_Type_Id',$exam_type)
                //         ->orderby('e.Class_Id','asc')
                //         ->get();
                // $data[$i] = $datas;
                $i++;
      }

      if(count($data[0]) <= 0){
        return Redirect::back()->withErrors('Belum ada Jadwal Ujian');
      }
      
      $date = date('d-m-Y');

      $kaprodi = DB::table('emp_employee')
            ->select('emp_employee.Full_Name', 'emp_employee.Nidn', 'emp_structural.Structural_Name','emp_employee_structural.Sk_Date')
            ->leftJoin('emp_employee_structural', 'emp_employee.Employee_Id', '=', 'emp_employee_structural.Employee_Id')
            ->leftJoin('emp_structural', 'emp_employee_structural.Structural_Id', '=', 'emp_structural.Structural_Id')
//            ->leftJoin('mstr_department', 'emp_employee_structural.Department_Id', 'mstr_department.Department_Id')
            ->where([
                'emp_employee_structural.Structural_Id' => 30,
                'emp_employee_structural.Work_Unit_Id' => $department
            ])
            ->orderBy('emp_employee_structural.Sk_Date', 'desc')
            ->get();
      
      $struktural = StrukturalData::getkabagakademik();

      View()->share(['studentdata'=>$studentdata,
                     'examTypeName'=>$examTypeName,
                     'data'=>$data,
                     'date'=>$date,
                     'struktural'=>$struktural,
                     'kaprodi'=>$kaprodi,
                     'termyear'=>$termyear]);

      $pdf = PDF::loadView('cetak_kartuujian/cetak_kartuujian');
      return $pdf->stream('ktm.pdf');

        //code...
        // } catch (\Exception $e) {
        //   return Redirect::back()->withErrors('Maximum execution time of 30 seconds exceeded');
        // }
    }
 }
