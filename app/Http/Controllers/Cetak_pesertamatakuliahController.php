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
use DateTime;
use Excel;
use App\GetDepartment;

class Cetak_pesertamatakuliahController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','export']]);
    // $this->middleware('access:CanViewDetail', ['only' => ['show']]);
    // $this->middleware('access:CanEditDetail', ['only' => ['edit','update']]);
  
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
       $department = Input::get('department');
       $class_program = Input::get('class_program');
       $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }
       $FacultyId=Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;
        $select_department = GetDepartment::getDepartment();
         
       $select_class_program = DB::table('mstr_department_class_program')
       ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
       ->join('mstr_department','mstr_department.Department_Id','=','mstr_department_class_program.Department_Id')
       
       ->where('mstr_department_class_program.Department_Id', $department)
       ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
       ->get();
         
      $data = DB::table('acd_offered_course')
         ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
         ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
       
         ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
         ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
         ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
         ->leftjoin('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Offered_Course_id','=','acd_offered_course.Offered_Course_id')
         ->leftjoin('emp_employee', 'emp_employee.Employee_Id', '=' , 'acd_offered_course_lecturer.Employee_Id')
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
         ->where('acd_offered_course.Department_Id', $department)
         ->where('acd_offered_course.Class_Prog_Id', $class_program)
         ->where('acd_offered_course.Term_Year_Id', $term_year);

         if ($search != null) {
           $data = $data->where(function($query){
             $search = Input::get('search');
             $query->whereRaw("lower(Course_Name) like '%" . strtolower($search) . "%'");
             $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
           });
         }

         $data = $data->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name')
        //  ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
         ->groupBy('acd_course.Course_Id', 'acd_course.Course_Code', 'acd_course.Course_Name')
         ->orderBy('acd_course.Course_Name', 'asc')
         ->paginate($rowpage);


         $select_term_year = DB::table('mstr_term_year')
         ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
         ->get();
       
       
       
         $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
         return view('cetak_pesertamatakuliah/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year);
    }
  
  public function cetak(Request $request){
    $course = DB::table('acd_course')->where([['Course_Id',$request->Course_Id],['Department_Id',$request->department]])->first();
    $prodi = DB::table('mstr_department')->where('Department_Id',$request->department)->first();
    Excel::create($request->term_year.'-'.$prodi->Department_Name.'-'.$course->Course_Name, function ($excel) use($request,$course,$prodi){       
      $items  = DB::table('acd_student_krs as a')
      ->join('acd_student as b','a.Student_Id','=','b.Student_Id')
      ->join('mstr_class as mc','a.Class_Id','=','mc.Class_Id')
      ->where([
        ['b.Department_Id',$request->department],
        ['a.Class_Prog_Id',$request->class_program],
        ['a.Term_Year_Id',$request->term_year],
        ['a.Course_Id',$request->Course_Id],
        ['a.Is_Approved',1],
      ])
      ->select('a.Class_Id','b.Email_Corporate','b.Nim','mc.Class_Name','b.Full_Name')
      ->orderBy('a.Class_Id','b.Nim')
      ->get();

      if ($items->count() == 0) {
        $data = [
          [
            'Nim' => '',
            'Nama' => '',
            'Kelas' => '',
          ]
        ];
      }

      $i = 1;
      foreach ($items as $item) {
        $data[] = [
          'Nim' => $item->Nim,
          'Nama' => $item->Full_Name,
          'Kelas' => $item->Class_Name,
        ];
        $i++;
      }

      $excel->sheet('Data Mahasiswa', function ($sheet) use ($data,$items,$course,$prodi) {
          $sheet->fromArray($data, null, 'A5');

          $sheet->setCellValue('A1', 'Program Studi');
          $sheet->setCellValue('A2', 'Matakuliah');

          $sheet->mergeCells('B1:C1');
          $sheet->setCellValue('B1',$prodi->Department_Name);
          $sheet->mergeCells('B2:C2');
          $sheet->setCellValue('B2',$course->Course_Name);
      });
    })->export('xlsx');
  }
  

}

