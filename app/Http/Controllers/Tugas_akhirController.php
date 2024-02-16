<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;
use App\GetDepartment;


class Tugas_akhirController extends Controller
{
  // public function __construct()
  // {
  //   $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','finddata','findgrade']]);
  //   $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','finddata','findgrade']]);
  //   $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','finddata','findgrade']]);
  //   $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','finddata','findgrade']]);
  // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $search = Input::get('search');
      $rowpage = Input::get('rowpage');
      $department = Input::get('department');
      $term_year = input::get('term_year');
      $entry_year = Input::get('angkatan');
      $FacultyId = Auth::user()->Faculty_Id;
      $DepartmentId = Auth::user()->Department_Id;

      if ($rowpage == null || $rowpage <= 0) {
        $rowpage = 10;
      }
      $class_program = Input::get('class_program');

      $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'DESC')
      ->get();

      $select_entry_year = DB::table('mstr_entry_year')
      ->orderBy('Entry_Year_Name','desc')->get();

      $select_department = GetDepartment::getDepartment();


      if($search == ""){
          if ($term_year == 0 && $entry_year==0) {
            // ->where('acd_thesis.Term_Year_Id',$term_year)->where('acd_student.Entry_Year_Id',$entry_year)
          $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
          ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->where('acd_student.Department_Id',$department)
          ->orderby('acd_thesis.Thesis_Id')
          ->select('acd_thesis.*','acd_student.Nim',
          DB::raw('(pembimbing1.Full_Name) as pem1'),
          DB::raw('(pembimbing2.Full_Name) as pem2'),
          DB::raw('(acd_student.Full_Name) as nm_mhs'))->orderBy('acd_thesis.Application_Date','asc')->paginate($rowpage);

          $matakuliah2 = DB::table('acd_course')->select('acd_course.Course_Id')
          ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
          ->where('acd_course.Department_Id', $department)
          ->where('acd_course.Course_Name', 'LIKE', 'Skripsi')
          ->orwhere('acd_course.Course_Name', 'LIKE', 'Thesis')->orwhere('acd_course.Course_Name', 'LIKE', 'Tugas Akhir')->get();

          $data->appends(['search'=> $search,'term_year'=>$term_year, 'search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
          return view('tugas_akhir/index')->with('matakuliah2', $matakuliah2)->with('rowpage', $rowpage)->with('search', $search)->with('query', $data)->with('entry_year', $entry_year)->with('select_entry_year', $select_entry_year)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department',$select_department)->with('department', $department);

        }elseif ($term_year != 0 && $entry_year==0) {
          $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
          ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->where('acd_student.Department_Id',$department)
          ->where('acd_thesis.Term_Year_Id',$term_year)
          ->orderby('acd_thesis.Thesis_Id')
          ->select('acd_thesis.*','acd_student.Nim',
          DB::raw('(pembimbing1.Full_Name) as pem1'),
          DB::raw('(pembimbing2.Full_Name) as pem2'),
          DB::raw('(acd_student.Full_Name) as nm_mhs'))
          ->paginate($rowpage);
          // ->get();
          // dd($data);
          $data->appends(['search'=> $search,'term_year'=>$term_year, 'search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
            return view('tugas_akhir/index')->with('rowpage', $rowpage)->with('search', $search)->with('query', $data)->with('entry_year', $entry_year)->with('select_entry_year', $select_entry_year)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department',$select_department)->with('department', $department);
        }elseif ($entry_year != 0 && $term_year == 0) {
          $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
          ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->where('acd_student.Department_Id',$department)
          ->where('acd_student.Entry_Year_Id', $entry_year)
          ->orderby('acd_thesis.Thesis_Id')
          ->select('acd_thesis.*','acd_student.Nim',
          DB::raw('(pembimbing1.Full_Name) as pem1'),
          DB::raw('(pembimbing2.Full_Name) as pem2'),
          DB::raw('(acd_student.Full_Name) as nm_mhs'))->orderBy('acd_thesis.Application_Date','asc')->paginate($rowpage);
          $data->appends(['search'=> $search,'term_year'=>$term_year, 'search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
            return view('tugas_akhir/index')->with('rowpage', $rowpage)->with('search', $search)->with('query', $data)->with('entry_year', $entry_year)->with('select_entry_year', $select_entry_year)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department',$select_department)->with('department', $department);
        }else{
          $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
          ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->where('acd_student.Department_Id',$department)->where('acd_thesis.Term_Year_Id',$term_year)->where('acd_student.Entry_Year_Id',$entry_year)
          ->orderby('acd_thesis.Thesis_Id')
          ->select('acd_thesis.*','acd_student.Nim',
          DB::raw('(pembimbing1.Full_Name) as pem1'),
          DB::raw('(pembimbing2.Full_Name) as pem2'),
          DB::raw('(acd_student.Full_Name) as nm_mhs'))->orderBy('acd_thesis.Application_Date','asc')->paginate($rowpage);
          $data->appends(['search'=> $search,'term_year'=>$term_year, 'search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
            return view('tugas_akhir/index')->with('rowpage', $rowpage)->with('search', $search)->with('query', $data)->with('entry_year', $entry_year)->with('select_entry_year', $select_entry_year)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department',$select_department)->with('department', $department);
        }
      }else {
          if ($term_year == 0 && $entry_year==0) {
            // ->where('acd_thesis.Term_Year_Id',$term_year)->where('acd_student.Entry_Year_Id',$entry_year)
          $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
          ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->where('acd_student.Department_Id',$department)
          ->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
          })
          ->orderby('acd_thesis.Thesis_Id')
          ->select('acd_thesis.*','acd_student.Nim',
          DB::raw('(pembimbing1.Full_Name) as pem1'),
          DB::raw('(pembimbing2.Full_Name) as pem2'),
          DB::raw('(acd_student.Full_Name) as nm_mhs'))->orderBy('acd_thesis.Application_Date','asc')->paginate($rowpage);
          $data->appends(['search'=> $search,'term_year'=>$term_year, 'search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
          return view('tugas_akhir/index')->with('rowpage', $rowpage)->with('search', $search)->with('query', $data)->with('entry_year', $entry_year)->with('select_entry_year', $select_entry_year)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department',$select_department)->with('department', $department);

        }elseif ($term_year != 0 && $entry_year==0) {
          $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
          ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->where('acd_student.Department_Id',$department)
          ->where('acd_thesis.Term_Year_Id',$term_year)
          ->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
          })
          ->orderby('acd_thesis.Thesis_Id')
          ->select('acd_thesis.*','acd_student.Nim',
          DB::raw('(pembimbing1.Full_Name) as pem1'),
          DB::raw('(pembimbing2.Full_Name) as pem2'),
          DB::raw('(acd_student.Full_Name) as nm_mhs'))->orderBy('acd_thesis.Application_Date','asc')->paginate($rowpage);
          $data->appends(['search'=> $search,'term_year'=>$term_year, 'search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
            return view('tugas_akhir/index')->with('rowpage', $rowpage)->with('search', $search)->with('query', $data)->with('entry_year', $entry_year)->with('select_entry_year', $select_entry_year)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department',$select_department)->with('department', $department);
        }elseif ($entry_year != 0 && $term_year == 0) {
          $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
          ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->where('acd_student.Department_Id',$department)
          ->where('acd_student.Entry_Year_Id', $entry_year)
          ->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
          })
          ->orderby('acd_thesis.Thesis_Id')
          ->select('acd_thesis.*','acd_student.Nim',
          DB::raw('(pembimbing1.Full_Name) as pem1'),
          DB::raw('(pembimbing2.Full_Name) as pem2'),
          DB::raw('(acd_student.Full_Name) as nm_mhs'))->orderBy('acd_thesis.Application_Date','asc')->paginate($rowpage);
          $data->appends(['search'=> $search,'term_year'=>$term_year, 'search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
            return view('tugas_akhir/index')->with('rowpage', $rowpage)->with('search', $search)->with('query', $data)->with('entry_year', $entry_year)->with('select_entry_year', $select_entry_year)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department',$select_department)->with('department', $department);
        }else{
          $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
          ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
          ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
          ->where('acd_student.Department_Id',$department)
          ->where('acd_thesis.Term_Year_Id',$term_year)
          ->where('acd_student.Entry_Year_Id',$entry_year)
          ->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
          })
          ->orderby('acd_thesis.Thesis_Id')
          ->select('acd_thesis.*','acd_student.Nim',
          DB::raw('(pembimbing1.Full_Name) as pem1'),
          DB::raw('(pembimbing2.Full_Name) as pem2'),
          DB::raw('(acd_student.Full_Name) as nm_mhs'))->orderBy('acd_thesis.Application_Date','asc')->paginate($rowpage);
          $data->appends(['search'=> $search,'term_year'=>$term_year, 'search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
            return view('tugas_akhir/index')->with('rowpage', $rowpage)->with('search', $search)->with('query', $data)->with('entry_year', $entry_year)->with('select_entry_year', $select_entry_year)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department',$select_department)->with('department', $department);
        }
      }

  }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
      $angkatan = Input::get('angkatan');
      $department = Input::get('department');
      $mahasiswa = Input::get('mahasiswa');
      $pembimbing1 = Input::get('pembimbing1');
      $pembimbing2 = Input::get('pembimbing2');
      $pembimbing3 = Input::get('pembimbing3');
      $penguji1 = Input::get('penguji1');
      $penguji2 = Input::get('penguji2');
      $penguji3 = Input::get('penguji3');
      $grade_letter=Input::get('nilai');
      $term_year=Input::get('term_year');
      $entry_year=input::get('angkatan');
      $FacultyId = Auth::user()->Faculty_Id;

      $matakuliah2 = DB::table('acd_course')
      ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
      ->where('acd_course.Department_Id', $department)
      ->where('acd_course.Course_Name', 'LIKE', 'Skripsi')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'Thesis')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'Tugas Akhir')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'SKRIPSI')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'Karya Tulis Ilmiah')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'THESIS')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'TUGAS AKHIR')->get();

      if (count($matakuliah2) == 0) {
        return Redirect::back()->withErrors('Matakuliah Skripsi/Tugas Akhir Belum ada');
      }

      $select_mhsthesis = DB::table('acd_thesis')->select('Student_Id');

      if($term_year==0)
      {
        if($angkatan ==0 ){
            //$select_mahasiswa = DB::table('acd_student')->WhereNotIn('Student_Id',$select_mhsthesis)->where('Department_Id',$department)->get();
            $select_mahasiswa = DB::table('acd_student_krs')
            ->leftjoin('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
            ->leftjoin('acd_course','acd_course.Course_Id','=','acd_student_krs.Course_Id')
            ->WhereNotIn('acd_student.Student_Id',$select_mhsthesis)->where('acd_student.Department_Id',$department)
            ->where(function($query){
              $query->where('acd_course.Course_Name', 'LIKE', 'Skripsi');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'Thesis');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'Tugas Akhir');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'SKRIPSI');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'Karya Tulis Ilmiah');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'THESIS');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'TUGAS AKHIR');
            })->get();
        }else{
          $select_mahasiswa = DB::table('acd_student_krs')
          ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
          ->join('acd_course','acd_course.Course_Id','=','acd_student_krs.Course_Id')
          ->WhereNotIn('acd_student.Student_Id',$select_mhsthesis)
          ->where(function($query){
            $query->where('acd_course.Course_Name', 'LIKE', 'Skripsi');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'Thesis');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'Tugas Akhir');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'SKRIPSI');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'Karya Tulis Ilmiah');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'THESIS');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'TUGAS AKHIR');
          })
          ->where('acd_student.Department_Id',$department)
          ->where('acd_student.Entry_Year_Id', $angkatan)->get();
          //$select_mahasiswa = DB::table('acd_student')->WhereNotIn('Student_Id',$select_mhsthesis)->where('Entry_Year_Id', $angkatan)->where('Department_Id',$department)->get();
        }
      }else{
        if($angkatan ==0 ){
            //$select_mahasiswa = DB::table('acd_student')->WhereNotIn('Student_Id',$select_mhsthesis)->where('Department_Id',$department)->get();
            $select_mahasiswa = DB::table('acd_student_krs')
            ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
            ->join('acd_course','acd_course.Course_Id','=','acd_student_krs.Course_Id')
            ->WhereNotIn('acd_student.Student_Id',$select_mhsthesis)
            ->where('acd_student.Department_Id',$department)
            ->where('acd_student_krs.Term_Year_Id', $term_year)
            ->where(function($query){
              $query->where('acd_course.Course_Name', 'LIKE', 'Skripsi');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'Thesis');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'Tugas Akhir');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'SKRIPSI');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'Karya Tulis Ilmiah');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'THESIS');
              $query->orwhere('acd_course.Course_Name', 'LIKE', 'TUGAS AKHIR');
            })->get();
        }else{
          $select_mahasiswa = DB::table('acd_student_krs')
          ->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')
          ->join('acd_course','acd_course.Course_Id','=','acd_student_krs.Course_Id')
          ->WhereNotIn('acd_student.Student_Id',$select_mhsthesis)
          ->where('acd_student.Department_Id',$department)
          ->where(function($query){
            $query->where('acd_course.Course_Name', 'LIKE', 'Skripsi');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'Thesis');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'Tugas Akhir');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'SKRIPSI');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'THESIS');
            $query->orwhere('acd_course.Course_Name', 'LIKE', 'TUGAS AKHIR');
          })
          ->where('acd_student_krs.Term_Year_Id', $term_year)
          ->where('acd_student.Entry_Year_Id', $angkatan)
          ->groupby('acd_student.Student_Id')
          ->get();
          //$select_mahasiswa = DB::table('acd_student')->WhereNotIn('Student_Id',$select_mhsthesis)->where('Entry_Year_Id', $angkatan)->where('Department_Id',$department)->get();
        }
      }

      $dosen=DB::table('acd_department_lecturer')
      ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_department_lecturer.Employee_Id')
      // ->where('acd_department_lecturer.Department_Id', $department)
      ->get();

      $dosen = ApiStrukturalController::dosen_prodi('',$department);

      $select_grade_letter = DB::table('acd_grade_letter')
      ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')

      ->where('acd_grade_department.Department_Id',$department)->get();



      $matakuliah = DB::table('acd_course')->select('acd_course.Course_Id','acd_course.Course_Name')
      ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
      ->where('acd_course.Department_Id', $department)
      ->where('acd_course.Course_Name', 'LIKE', 'Skripsi')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'Thesis')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'Tugas Akhir')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'SKRIPSI')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'Karya Tulis Ilmiah')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'THESIS')
      ->orwhere('acd_course.Course_Name', 'LIKE', 'TUGAS AKHIR')->first();
      // dd($matakuliah);
    
      $select_term_year = DB::table('mstr_term_year')->orderBy('mstr_term_year.Term_Year_Name', 'desc')->get();


      $smt_mulai = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->select('Term_Year_Name')->first();

        return view('tugas_akhir/create')->with('entry_year', $entry_year)->with('term_year', $term_year)->with('smt_mulai', $smt_mulai)->with('grade_letter', $grade_letter)->with('select_grade', $select_grade_letter)->with('penguji3', $penguji3)->with('penguji2', $penguji2)->with('penguji1', $penguji1)->with('pembimbing3', $pembimbing3)->with('pembimbing2', $pembimbing2)->with('pembimbing1', $pembimbing1)->with('dosen', $dosen)->with('matakuliah', $matakuliah)->with('select_term_year', $select_term_year)->with('department', $department)->with('mahasiswa', $mahasiswa)->with('select_mahasiswa', $select_mahasiswa)->with('angkatan', $angkatan)->with('request',$request);
    }


    public function finddata(Request $request){
        // "Student_Id" => "12826"
        // "department" => "1"
        // "term_year" => "20201"
        // "angkatan" => "2019
      //it will get price if its id match with product id
      $std = DB::table('acd_student')->where('Student_Id',$request->Student_Id)->first();
      $angkatan_curriculum = DB::table('acd_curriculum_entry_year')
        ->where([['Term_Year_Id',$request->term_year],['Department_Id',$std->Department_Id],['Class_Prog_Id',$std->Class_Prog_Id],['Entry_Year_Id',$std->Entry_Year_Id]])
        ->select('Curriculum_Id')
        ->first();
      $course_curriculum = DB::table('acd_course_curriculum')
        ->join('acd_course','acd_course_curriculum.Course_Id','=','acd_course.Course_Id')
        ->where([['acd_course_curriculum.Department_Id',$std->Department_Id],['Class_Prog_Id',$std->Class_Prog_Id],['Curriculum_Id',$angkatan_curriculum->Curriculum_Id],['acd_course.Course_Name', 'LIKE', '%Skripsi%']])
        ->orwhere([['acd_course_curriculum.Department_Id',$std->Department_Id],['Class_Prog_Id',$std->Class_Prog_Id],['Curriculum_Id',$angkatan_curriculum->Curriculum_Id],['acd_course.Course_Name', 'LIKE', '%Tugas Akhir%']])
        ->orwhere([['acd_course_curriculum.Department_Id',$std->Department_Id],['Class_Prog_Id',$std->Class_Prog_Id],['Curriculum_Id',$angkatan_curriculum->Curriculum_Id],['acd_course.Course_Name', 'LIKE', '%SKRIPSI%']])
        ->orwhere([['acd_course_curriculum.Department_Id',$std->Department_Id],['Class_Prog_Id',$std->Class_Prog_Id],['Curriculum_Id',$angkatan_curriculum->Curriculum_Id],['acd_course.Course_Name', 'LIKE', '%THESIS%']])
        ->orwhere([['acd_course_curriculum.Department_Id',$std->Department_Id],['Class_Prog_Id',$std->Class_Prog_Id],['Curriculum_Id',$angkatan_curriculum->Curriculum_Id],['acd_course.Course_Name', 'LIKE', '%Thesis%']])
        ->orwhere([['acd_course_curriculum.Department_Id',$std->Department_Id],['Class_Prog_Id',$std->Class_Prog_Id],['Curriculum_Id',$angkatan_curriculum->Curriculum_Id],['acd_course.Course_Name', 'LIKE', '%TUGAS AKHIR%']])
        ->orwhere([['acd_course_curriculum.Department_Id',$std->Department_Id],['Class_Prog_Id',$std->Class_Prog_Id],['Curriculum_Id',$angkatan_curriculum->Curriculum_Id],['acd_course.Course_Name', 'LIKE', '%Karya Tulis Ilmiah%']])
        ->select('acd_course.Course_Name','acd_course_curriculum.Course_Id','acd_course_curriculum.Applied_Sks')
        ->first();
      if($course_curriculum){
        $krs = DB::table('acd_student_krs')->where([['Student_Id',$request->Student_Id],['Course_Id',$course_curriculum->Course_Id]])->first();
      }else{
        $krs = null;
      }
      
        return response()->json([
                    "success" => true,
                    "data" => $course_curriculum,
                    "std" => $std,
                    "krs" => $krs,
                    "total" => ($krs ? 1:0),
                ], 200);
  	}

      public function findgrade(Request $request){
        $Grade_Letter_Id = Input::get('Grade_Letter_Id');
        $Department_Id = Input::get('Department_Id');
        $FacultyId = Auth::user()->Faculty_Id;

        $a=DB::table('acd_grade_department')
        ->join('mstr_department','mstr_department.Department_Id','=','acd_grade_department.Department_Id')
        ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('acd_grade_department.Grade_Letter_Id',$Grade_Letter_Id)
        ->where('acd_grade_department.Department_Id',$Department_Id)->first();
        return response()->json($a);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student_id = Input::get('mahasiwa');
        $smt_mulai = Input::get('smt_mulai');
        $smt_selesai = Input::get('smt_selesai');
        $tgl_permohonan = Input::get('tgl_permohonan');
        $sks_trough = Input::get('sks_trough');
        $bnk = Input::get('bnk');
        $is_proposal = Input::get('is_proposal');
        $judul_proposal = Input::get('judul_proposal');
        $tgl_proposalmsk = Input::get('tgl_proposalmsk');
        $tgl_proposaltrm = Input::get('tgl_proposaltrm');
        $skproposalacc = Input::get('skproposalacc');
        $judul = Input::get('judul');
        $judul_eng = Input::get('judul_eng');
        $pembimbing1 = Input::get('pembimbing1');
        $pembimbing2=Input::get('pembimbing2');
        $pembimbing3 = Input::get('pembimbing3');
        $penguji1 = Input::get('penguji1');
        $penguji2 = Input::get('penguji2');
        $penguji3 = Input::get('penguji3');
        $tgl_mulai = Input::get('tgl_mulai');
        $tgl_pendadaran = Input::get('tgl_pendadaran');
        $ruang_ujian = Input::get('ruang_ujian');
        $tgl_selesai =Input::get('tgl_selesai');
        $nilai_ujian =Input::get('nilai_ujian');
        $nilai= Input::get('nilai');
        $total_ujian = Input::get('total_ujian');
        $sks_troughexam = Input::get('sks_troughexam');
        $bnk_exam = Input::get('bnk_exam');
        $Functionary_Department_Exam=Input::get('Functionary_Department_Exam');
        $Functionary_Name_Department_Exam=Input::get('Functionary_Name_Department_Exam');
        $Department_Functionary=Input::get('Department_Functionary');
        $Department_Functionary_Name=Input::get('Department_Functionary_Name');
        $nomor_izinthesis=Input::get('nomor_izinthesis');
        $halaman_izinthesis=Input::get('halaman_izinthesis');
        $nama_perusahaan = Input::get('nama_perusahaan');
        $alamat_perusahaan=Input::get('alamat_perusahaan');
        $Company_Address = Input::get('Company_Address');
        $Functionary_Company = Input::get('Functionary_Company');
        $Cq_Functionary_Company = Input::get('Cq_Functionary_Company');
        $tgl_dftseminar = Input::get('tgl_dftseminar');
        $tgl_plkseminar = Input::get('tgl_plkseminar');
        $ruang_seminar = Input::get('ruang_seminar');
        $Department_Seminar_Functionary = Input::get('Department_Seminar_Functionary');
        $Department_Seminar_Functionary_Name = Input::get('Department_Seminar_Functionary_Name');
        $Permission_Thesis_Long_Text = Input::get('Permission_Thesis_Long_Text');
        $Permission_Thesis_Start_Date = Input::get('Permission_Thesis_Start_Date');
        $Permission_Thesis_Complete_Date = Input::get('Permission_Thesis_Complete_Date');
        $Invitation_Thesis_Exam=Input::get('Invitation_Thesis_Exam');
        $Krs_Id=Input::get('krs_id');
        $sks_krs=Input::get('sks_krs');
        $department = Input::get('department');
        $matakuliah = Input::get('matakuliah');
        $nl_ujian = Input::get('nl_ujian');

        $date = date('Y-m-d H:i:s');
        $term_year=DB::table('mstr_term_year')
        ->where('Start_Date','<=',$date)
        ->where('End_Date','>=',$date)
        ->first();

        if($total_ujian != null || $total_ujian != ''){
          $grade_department = DB::table('acd_grade_department')
          ->where('Department_Id',$department)
          ->where('Scale_Numeric_Max','>=',$total_ujian)
          ->where('Scale_Numeric_Min','<=',$total_ujian)
          ->first();
          if(
            $grade_department == null
            ){
            return Redirect::back()->withErrors('Range nilai pada prodi belum diset')->with('success', false);
          }
        }
        if(
          $pembimbing1 == 0 ||
          $pembimbing2 == 0 
          // $penguji1 == 0 ||
          // $penguji2 == 0 
          ){
          return Redirect::back()->withErrors('Data Belum Lengkap')->with('success', false);
        }

        try {
          if($total_ujian != null || $total_ujian != ''){
            $insert_khs=DB::table('acd_student_khs')
           ->insertGetId(
           ['Krs_Id' => $Krs_Id, 'Student_Id' => $student_id,'Grade_Letter_Id' => $grade_department->Grade_Letter_Id,'Sks' => $sks_krs, 'Weight_Value' => $grade_department->Weight_Value, 'Is_For_Transkrip' => 1, 'Bnk_Value' => $grade_department->Weight_Value*$sks_krs ]);

           $trans =  DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)',array($insert_khs,''));
          }
          //
          
          // $insert2=DB::table('acd_transcript')
          //  ->insert(
          //  ['Khs_Id'=>$insert, 'Weight_Value' => $nl_ujian,'is_Use' => 1,'Student_Id' => $student_id,'Sks' => $sks_krs,'Course_Id' => $matakuliah,'Grade_Letter_Id' =>$nilai_ujian]);

          if($total_ujian != null || $total_ujian != ''){
            $Thesis_Exam_Score=$grade_department->Grade_Letter_Id;
            $Bnk=$grade_department->Weight_Value*$sks_krs;
          }else{
            $Thesis_Exam_Score = null;
            $Bnk = null;
          }
          $insert_data = 1;
          // $insert_data = DB::table('acd_thesis')
          //   ->insertgetid(
          //     ['Student_Id' => $student_id, 
          //     'Invitation_Thesis_Exam'=>$Invitation_Thesis_Exam, 
          //     'Permission_Thesis_Complete_Date'=>$Permission_Thesis_Complete_Date,
          //     'Permission_Thesis_Start_Date'=>$Permission_Thesis_Start_Date, 
          //     'Permission_Thesis_Long_Text'=>$Permission_Thesis_Long_Text, 
          //     'Department_Seminar_Functionary_Name'=>$Department_Seminar_Functionary_Name, 
          //     'Department_Seminar_Functionary'=>$Department_Seminar_Functionary, 
          //     'Seminar_Room'=>$ruang_seminar, 
          //     'Seminar_Date'=>$tgl_plkseminar, 
          //     'Seminar_App_Date'=>$tgl_dftseminar, 
          //     'Cq_Functionary_Company'=>$Cq_Functionary_Company, 
          //     'Functionary_Company'=>$Functionary_Company, 
          //     'Company_Address'=>$Company_Address, 
          //     'Company_Address'=>$alamat_perusahaan, 
          //     'Company_Name'=>$nama_perusahaan, 
          //     'Permission_Thesis_Page'=>$halaman_izinthesis, 
          //     'Permission_Thesis_Num'=>$nomor_izinthesis, 
          //     'Department_Functionary_Name'=>$Department_Functionary_Name, 
          //     'Department_Functionary'=>$Department_Functionary , 
          //     'Functionary_Department_Exam'=>$Functionary_Department_Exam , 
          //     'Functionary_Name_Department_Exam'=>$Functionary_Name_Department_Exam , 
          //     'Bnk_Exam'=>$bnk_exam ,'Sks_Trough_Exam'=>$sks_troughexam , 
          //     'Total_Thesis_Exam'=>$total_ujian, 
          //     'Course_Id'=>$matakuliah, 
          //     'Thesis_Exam_Room'=>$ruang_ujian, 
          //     'Grade'=>$nilai, 
          //     'Thesis_Complete_Date'=>$tgl_selesai, 
          //     'Thesis_exam_Date'=>$tgl_pendadaran, 
          //     'Thesis_Start_Date'=>$tgl_mulai,'Examiner_1'=>$penguji1,
          //     'Examiner_2'=>$penguji2,
          //     'Examiner_3'=>$penguji3,
          //     'Supervisor_2'=>$pembimbing2, 
          //     'Supervisor_1'=>$pembimbing1, 
          //     'Thesis_Title_Eng'=>$judul_eng, 
          //     'Thesis_Title'=>$judul, 
          //     'SK_Proposal_Acc'=>$skproposalacc, 
          //     'Proposal_Date_Acc'=>$tgl_proposaltrm, 
          //     'Proposal_Date_Msk'=>$tgl_proposalmsk, 
          //     'Proposal_Title'=>$judul_proposal, 
          //     'Is_Proposal'=>$is_proposal, 
          //     'Sks_Trough'=>$sks_krs, 
          //     'Application_Date'=>$tgl_permohonan, 
          //     'Term_Year_Id' => $smt_mulai, 
          //     'Term_Year_Id_Start' => $smt_mulai, 
          //     'Term_Year_Id_Complete'=>$smt_selesai,
          //     'Thesis_Exam_Score'=>$Thesis_Exam_Score, 
          //     'Bnk'=>$Bnk, 
          //     ]);

        // alert()->warning('Registration is Closed.', 'Oppsss...')->persistent('OK');
          alert("<a class='btn btn-success' style='background-color: #357c10 !important;' href='http://akademik-umkendari.utc-umy.id/proses/tugas_akhir/dosen_penguji/create?thesis=$insert_data&department=$department&term_year=$request->term_year&angkatan=$request->angkatan'>Isi Sekarang</a>","Dosen Penguji Belum Diisi.")->html()->persistent("No, thanks");

        return Redirect::back()->withErrors('Berhasil Menambah Tugas Akhir')->with('success', true);
      } catch (\Exception $e) {
        return Redirect::back()->withErrors('Gagal Menambah Tugas Akhir')->with('success', false);
      }
    }


    public function store_srtijinta(Request $request)
    {
      $id = $request->input('id');
      $Permission_Thesis_Page = $request->input('nosrt');
      $Permission_Thesis_Date = $request->input('tgl_surat');
      $Company_Name = $request->input('nm_perusahaan');
      $Company_Address = $request->input('alt_perusahaan');
      $Permission_Thesis_Project_Name = $request->input('proyek');
      $Functionary_Company = $request->input('jbt_pimpinan');
      $Cq_Functionary_Company = $request->input('cq');
      $Permission_Thesis_Long_Text = $request->input('txt_lamaijin');
      $Permission_Thesis_Start_Date = $request->input('mulai');
      $Permission_Thesis_Complete_Date = $request->input('selesai');

      try {
      DB::table('acd_thesis')
      ->where('acd_thesis.Thesis_Id',$id)
      ->update(
        ['Permission_Thesis_Page' => $Permission_Thesis_Page,
        'Permission_Thesis_Date'=>$Permission_Thesis_Date,
        'Company_Name'=>$Company_Name,
        'Company_Address'=>$Company_Address,
        'Permission_Thesis_Project_Name'=>$Permission_Thesis_Project_Name,
        'Functionary_Company'=>$Functionary_Company,
        'Cq_Functionary_Company'=>$Cq_Functionary_Company,
        'Permission_Thesis_Long_Text'=>$Permission_Thesis_Long_Text,
        'Permission_Thesis_Start_Date'=>$Permission_Thesis_Start_Date,
        'Permission_Thesis_Complete_Date'=>$Permission_Thesis_Complete_Date]);
        echo json_encode (['message'=>'Sukses menyimpan data']);
       } catch (\Exception $e) {
         echo json_encode (['message'=>'gagal menyimpan data']);
       }
    }

    public function store_srtmohonseminarta(Request $request)
    {
      $id = $request->input('id');
      $Seminar_App_Date = $request->input('tgl_surat');
      $Thesis_Title = $request->input('judul');
      $Thesis_Title_Eng = $request->input('judul_eng');

      try {
        DB::table('acd_thesis')
        ->where('acd_thesis.Thesis_Id',$id)
        ->update(
        ['Seminar_App_Date' => $Seminar_App_Date,
        'Thesis_Title' => $Thesis_Title,
        'Thesis_Title_Eng' => $Thesis_Title_Eng]);
        echo json_encode (['message'=>'Sukses menyimpan data']);
       } catch (\Exception $e) {
         echo json_encode (['message'=>'gagal menyimpan data']);
       }
    }
    public function store_undanganseminar(Request $request){
      $id = $request->input('id');
      $tgl_jam = $request->input('tgl_jam');
      $ruang = $request->input('Room_Id');

      try {
      DB::table('acd_thesis')
      ->where('acd_thesis.Thesis_Id',$id)
      ->update(
        ['Seminar_Date' => $tgl_jam,
        'Seminar_Room'=>$ruang,]);
        echo json_encode (['message'=>'Berhasil menyimpan data']);
       } catch (\Exception $e) {
         echo json_encode (['message'=>'gagal menyimpan data']);
       }
    }

    public function store_permohonan_pendadaran(Request $request){
      $id = $request->input('id');
      $Thesis_Exam_App_Date = $request->input('tgl_jam');
      $Bnk = $request->input('bnk');
      $Sks_Trough = $request->input('sks');
      $Thesis_Title = $request->input('judul');
      $Thesis_Title_Eng = $request->input('judul_eng');

      try {
        DB::table('acd_thesis')
        ->where('acd_thesis.Thesis_Id',$id)
        ->update(
        ['Thesis_Exam_App_Date'=>$Thesis_Exam_App_Date,
        'Bnk'=>$Bnk,
        'Sks_Trough'=>$Sks_Trough,
        'Thesis_Title' => $Thesis_Title,
        'Thesis_Title_Eng' => $Thesis_Title_Eng]);
        echo json_encode (['message'=>'Berhasil menyimpan data']);
       } catch (\Exception $e) {
         echo json_encode (['message'=>'gagal menyimpan data']);
       }
    }

    public function store_undangan_pendadaran(Request $request){
      $id = $request->input('id');
      $Thesis_Exam_Date = $request->input('tgl_jam');
      $Thesis_Exam_Room = $request->input('Room_Id');
      $Examiner_1 = $request->input('penguji1');
      $Examiner_2 = $request->input('penguji2');
      $Examiner_3 = $request->input('penguji3');
      $Invitation_Thesis_Exam_Date = $request->input('tgl_undangan');

      try {
      DB::table('acd_thesis')
      ->where('acd_thesis.Thesis_Id',$id)
      ->update(
        ['Thesis_Exam_Date'=>$Thesis_Exam_Date,'Thesis_Exam_Room'=>$Thesis_Exam_Room,'Examiner_1'=>$Examiner_1,'Examiner_2'=>$Examiner_2,'Examiner_3'=>$Examiner_3,'Invitation_Thesis_Exam'=>$Invitation_Thesis_Exam_Date]);
        echo json_encode (['message'=>'Berhasil menyimpan data']);
       } catch (\Exception $e) {
         echo json_encode (['message'=>'gagal menyimpan data']);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
      $department = Input::get('department');
      $Thesis_Id = $id;
      $data = DB::table('acd_thesis')
      ->where('Thesis_Id', $id)
      ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
      ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
      ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
      ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
      ->join('emp_employee as exam1','exam1.Employee_Id','=','acd_thesis.Examiner_1')
      ->join('emp_employee as exam2','exam2.Employee_Id','=','acd_thesis.Examiner_2')
      ->join('emp_employee as exam3','exam3.Employee_Id','=','acd_thesis.Examiner_3')
      ->groupBy('acd_thesis.Thesis_Id')
      ->select('exam1.Employee_Id as examiner1','exam2.Employee_Id as examiner2','exam3.Employee_Id as examiner3','acd_thesis.*','mstr_department.Department_Id','mstr_department.Department_Name','mstr_entry_year.Entry_Year_Name','acd_student.Student_Id','acd_student.Nim','acd_student.Full_Name',DB::raw('(pembimbing1.Full_Name) as pem1'),
      DB::raw('(pembimbing2.Full_Name) as pem2'), DB::raw('(exam1.Full_Name) as exam1'), DB::raw('(exam2.Full_Name) as exam2'), DB::raw('(exam3.Full_Name) as exam3'))
      ->first();
      $ttd = "Ketua Program Studi";
      $employee = DB::table('acd_functional_position_term_year')
                ->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
                ->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
                ->where('acd_functional_position_term_year.Term_Year_Id', $data->Term_Year_Id_Start)
                ->where('acd_functional_position_term_year.Department_Id', $data->Department_Id)
                ->where('emp_functional_position.Functional_Position_Code', 'KP')
                ->select('emp_employee.Full_Name')
                ->first();
      $notroom = DB::table('acd_offered_course_sched')->select('Room_Id');
      $select_room = DB::table('mstr_room')->groupBy('mstr_room.Room_Id')
      ->WhereNotIn('mstr_room.Room_Id',$notroom)->get();

      $khs = DB::table('acd_student_khs')->where('Student_Id', $data->Student_Id)->select(DB::raw('SUM(Sks) as Sks_Trough'),DB::raw('SUM(Bnk_Value) as Bnk'))->first();

      $emp_employee = DB::table('emp_employee')->get();

        return view('tugas_akhir/show')->with('data', $data)->with('emp_employee', $emp_employee)->with('khs', $khs)->with('select_room', $select_room)->with('ttd', $ttd)->with('employee', $employee)->with('Thesis_Id', $Thesis_Id)->with('department', $department)->with('term_year', $request->term_year);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id,Request $request)
    {
        $department = Input::get('department');
        $select_mhsthesis = DB::table('acd_thesis')->select('Student_Id');
        $select_mahasiswa = DB::table('acd_student')->join('acd_thesis','acd_thesis.Student_Id','=','acd_student.Student_Id')
        ->where('Department_Id',$department)->get();
        $select_term_year = DB::table('mstr_term_year')->orderBy('mstr_term_year.Term_Year_Name', 'desc')->get();
        $matakuliah = DB::table('acd_course')->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')->where('acd_course.Department_Id', $department)->orderBy('acd_course.Course_Code', 'asc')->get();

        $dosen=DB::table('acd_department_lecturer')
        ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_department_lecturer.Employee_Id')
        ->get();

        $select_grade_letter = DB::table('acd_grade_letter')->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')->where('acd_grade_department.Department_Id',$department)->get();

        $penguji_in = DB::table('acd_thesis_examiner as ate')
        ->where('Thesis_Id',$id)
        ->select('Examiner_Id');

        $penguji_in_thesis = DB::table('acd_thesis_examiner as ate')
        ->where('Thesis_Id',$id)
        ->join('emp_employee as ee','ate.Examiner_Id','=','ee.Employee_Id')
        ->get();
        
        $penguji = DB::table('emp_employee')
        ->WhereNotIn('Employee_Id',$penguji_in)
        ->get();

        $data = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
        ->where('acd_student.Department_Id',$department)
        ->where('acd_thesis.Thesis_Id',$id)
        ->get();



        $student_id=DB::table('acd_thesis')->where('acd_thesis.Thesis_Id',$id)->first();
        $Krs_Id=DB::table('acd_student_krs')
        ->where('Student_Id',$student_id->Student_Id)->where('Course_Id', $student_id->Course_Id)
        ->first();

        $mahasiswa = DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')->where('name', 'pattern')->select('acd_student.Nim');

        return view('tugas_akhir/edit')->with('Krs_Id', $Krs_Id)->with('select_grade', $select_grade_letter)->with('dosen', $dosen)->with('matakuliah', $matakuliah)->with('select_term_year', $select_term_year)->with('select_mahasiswa', $select_mahasiswa)->with('department', $department)->with('data', $data)->with('mahasiswa', $mahasiswa)->with('term_year', $request->term_year)->with('id', $id)->with('request', $request)->with('penguji_in_thesis', $penguji_in_thesis);
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
      $student_id = Input::get('mahasiwa');
      $smt_mulai = Input::get('smt_mulai');
      $smt_selesai = Input::get('smt_selesai');
      $matakuliah = Input::get('matakuliah');
      $sks_krs = Input::get('sks_krs');
      $tgl_permohonan = Input::get('tgl_permohonan');
      $sks_trough = Input::get('sks_trough');
      $bnk = Input::get('bnk');
      $is_proposal = Input::get('is_proposal');
      $judul_proposal = Input::get('judul_proposal');
      $tgl_proposalmsk = Input::get('tgl_proposalmsk');
      $tgl_proposaltrm = Input::get('tgl_proposaltrm');
      $skproposalacc = Input::get('skproposalacc');
      $judul = Input::get('judul');
      $judul_eng = Input::get('judul_eng');
      $pembimbing1 = Input::get('pembimbing1');
      $pembimbing2=Input::get('pembimbing2');
      $pembimbing3 = Input::get('pembimbing3');
      $penguji1 = Input::get('penguji1');
      $penguji2 = Input::get('penguji2');
      $penguji3 = Input::get('penguji3');
      $tgl_mulai = Input::get('tgl_mulai');
      $tgl_pendadaran = Input::get('tgl_pendadaran');
      $ruang_ujian = Input::get('ruang_ujian');
      $tgl_selesai =Input::get('tgl_selesai');
      $nilai_ujian =Input::get('nilai_ujian');
      $nilai= Input::get('nilai');
      $total_ujian = Input::get('total_ujian');
      $sks_troughexam = Input::get('sks_troughexam');
      $bnk_exam = Input::get('bnk_exam');
      $Functionary_Department_Exam=Input::get('Functionary_Department_Exam');
      $Functionary_Name_Department_Exam=Input::get('Functionary_Name_Department_Exam');
      $Department_Functionary=Input::get('Department_Functionary');
      $Department_Functionary_Name=Input::get('Department_Functionary_Name');
      $nomor_izinthesis=Input::get('nomor_izinthesis');
      $halaman_izinthesis=Input::get('halaman_izinthesis');
      $nama_perusahaan = Input::get('nama_perusahaan');
      $alamat_perusahaan=Input::get('alamat_perusahaan');
      $Company_Address = Input::get('Company_Address');
      $Functionary_Company = Input::get('Functionary_Company');
      $Cq_Functionary_Company = Input::get('Cq_Functionary_Company');
      $tgl_dftseminar = Input::get('tgl_dftseminar');
      $tgl_plkseminar = Input::get('tgl_plkseminar');
      $ruang_seminar = Input::get('ruang_seminar');
      $Department_Seminar_Functionary = Input::get('Department_Seminar_Functionary');
      $Department_Seminar_Functionary_Name = Input::get('Department_Seminar_Functionary_Name');
      $Permission_Thesis_Long_Text = Input::get('Permission_Thesis_Long_Text');
      $Permission_Thesis_Start_Date = Input::get('Permission_Thesis_Start_Date');
      $Permission_Thesis_Complete_Date = Input::get('Permission_Thesis_Complete_Date');
      $Invitation_Thesis_Exam=Input::get('Invitation_Thesis_Exam');
      $Course_Id=Input::get('Course_Id');

      $date = date('Y-m-d H:i:s');
      $term_year=DB::table('mstr_term_year')
      ->where('Start_Date','<=',$date)
      ->where('End_Date','>=',$date)
      ->first();

      $student = DB::table('acd_student')->where('Student_Id',$student_id)->first();
      $grade_department = DB::table('acd_grade_department')
        ->where('Department_Id',$student->Department_Id)
        ->where('Scale_Numeric_Max','>=',$total_ujian)
        ->where('Scale_Numeric_Min','<=',$total_ujian)
        ->first();
        if(
          $grade_department == null
          ){
          return Redirect::back()->withErrors('Range nilai pada prodi belum diset')->with('success', false);
        }
      if($pembimbing3=='0')
      {
        // try {
        $u=DB::table('acd_thesis')
        ->where('acd_thesis.Thesis_Id',$id)
       ->update(
          ['Student_Id' => $student_id, 
          'Invitation_Thesis_Exam'=>$Invitation_Thesis_Exam, 
          'Permission_Thesis_Complete_Date'=>$Permission_Thesis_Complete_Date,'Permission_Thesis_Start_Date'=>$Permission_Thesis_Start_Date, 
          'Permission_Thesis_Long_Text'=>$Permission_Thesis_Long_Text, 
          'Department_Seminar_Functionary_Name'=>$Department_Seminar_Functionary_Name, 
          'Department_Seminar_Functionary'=>$Department_Seminar_Functionary, 
          'Seminar_Room'=>$ruang_seminar, 
          'Seminar_Date'=>$tgl_plkseminar, 
          'Seminar_App_Date'=>$tgl_dftseminar, 
          'Cq_Functionary_Company'=>$Cq_Functionary_Company, 
          'Functionary_Company'=>$Functionary_Company, 
          'Company_Address'=>$Company_Address, 
          'Company_Address'=>$alamat_perusahaan, 
          'Company_Name'=>$nama_perusahaan, 
          'Permission_Thesis_Page'=>$halaman_izinthesis, 
          'Permission_Thesis_Num'=>$nomor_izinthesis, 
          'Department_Functionary_Name'=>$Department_Functionary_Name, 
          'Department_Functionary'=>$Department_Functionary , 
          'Functionary_Department_Exam'=>$Functionary_Department_Exam , 
          'Functionary_Name_Department_Exam'=>$Functionary_Name_Department_Exam , 
          'Bnk_Exam'=>$bnk_exam ,'Sks_Trough_Exam'=>$sks_troughexam , 
          'Total_Thesis_Exam'=>$total_ujian, 
          'Thesis_Exam_Score'=>$grade_department->Grade_Letter_Id, 
          'Course_Id'=>$matakuliah,'Thesis_Exam_Room'=>$ruang_ujian, 
          'Grade'=>$nilai, 
          'Thesis_Complete_Date'=>$tgl_selesai, 
          'Thesis_exam_Date'=>$tgl_pendadaran, 
          'Thesis_Start_Date'=>$tgl_mulai,
          'Supervisor_3'=>($pembimbing3 == 0 ? null:$pembimbing3), 
          'Supervisor_2'=>$pembimbing2, 
          'Supervisor_1'=>$pembimbing1, 
          'Thesis_Title_Eng'=>$judul_eng, 
          'Thesis_Title'=>$judul, 
          'SK_Proposal_Acc'=>$skproposalacc, 
          'Proposal_Date_Acc'=>$tgl_proposaltrm, 
          'Proposal_Date_Msk'=>$tgl_proposalmsk, 
          'Proposal_Title'=>$judul_proposal, 
          'Is_Proposal'=>$is_proposal, 
          'Bnk'=>$grade_department->Weight_Value*($sks_trough == null ? $sks_krs:$sks_trough), 
          'Sks_Trough'=>($sks_trough == null ? $sks_krs:$sks_trough), 
          'Application_Date'=>$tgl_permohonan, 
          'Term_Year_Id' => ($term_year ? $term_year->Term_Year_Id:$smt_mulai), 
          'Term_Year_Id_Start' => $smt_mulai, 
          'Term_Year_Id_Complete'=>$smt_selesai]);

        // dd($request->all());
        if($request->penguji != 0){
          $penguji = DB::table('acd_thesis_examiner')
          ->insert([
            'Thesis_Id' => $id,
            'Order_Id' => $request->examiner_number,
            'Examiner_Id' => $request->penguji
          ]);
        }
          return Redirect::back()->withErrors('Berhasil Mengubah Tugas Akhir')->with('success', true);
        // } catch (\Exception $e) {
        //   return Redirect::back()->withErrors('Gagal Mengubah Tugas Akhir')->with('success', false);
        // }

      }else{
        try {
        $u=DB::table('acd_thesis')
        ->where('acd_thesis.Thesis_Id',$id)
       ->update(
          ['Student_Id' => $student_id, 
          'Invitation_Thesis_Exam'=>$Invitation_Thesis_Exam, 
          'Permission_Thesis_Complete_Date'=>$Permission_Thesis_Complete_Date,'Permission_Thesis_Start_Date'=>$Permission_Thesis_Start_Date, 
          'Permission_Thesis_Long_Text'=>$Permission_Thesis_Long_Text, 
          'Department_Seminar_Functionary_Name'=>$Department_Seminar_Functionary_Name, 
          'Department_Seminar_Functionary'=>$Department_Seminar_Functionary, 
          'Seminar_Room'=>$ruang_seminar, 
          'Seminar_Date'=>$tgl_plkseminar, 
          'Seminar_App_Date'=>$tgl_dftseminar, 
          'Cq_Functionary_Company'=>$Cq_Functionary_Company, 
          'Functionary_Company'=>$Functionary_Company, 
          'Company_Address'=>$Company_Address, 
          'Company_Address'=>$alamat_perusahaan, 
          'Company_Name'=>$nama_perusahaan, 
          'Permission_Thesis_Page'=>$halaman_izinthesis, 
          'Permission_Thesis_Num'=>$nomor_izinthesis, 
          'Department_Functionary_Name'=>$Department_Functionary_Name, 
          'Department_Functionary'=>$Department_Functionary , 
          'Functionary_Department_Exam'=>$Functionary_Department_Exam , 
          'Functionary_Name_Department_Exam'=>$Functionary_Name_Department_Exam , 
          'Bnk_Exam'=>$bnk_exam ,'Sks_Trough_Exam'=>$sks_troughexam , 
          'Total_Thesis_Exam'=>$total_ujian, 
          'Thesis_Exam_Score'=>$grade_department->Grade_Letter_Id, 
          'Course_Id'=>$matakuliah,'Thesis_Exam_Room'=>$ruang_ujian, 
          'Grade'=>$nilai, 
          'Thesis_Complete_Date'=>$tgl_selesai, 
          'Thesis_exam_Date'=>$tgl_pendadaran, 
          'Thesis_Start_Date'=>$tgl_mulai,'Examiner_1'=>$penguji1,'Examiner_2'=>$penguji2,'Examiner_3'=>$penguji3,'Supervisor_2'=>$pembimbing2, 
          'Supervisor_1'=>$pembimbing1, 
          'Thesis_Title_Eng'=>$judul_eng, 
          'Thesis_Title'=>$judul, 
          'SK_Proposal_Acc'=>$skproposalacc, 
          'Proposal_Date_Acc'=>$tgl_proposaltrm, 
          'Proposal_Date_Msk'=>$tgl_proposalmsk, 
          'Proposal_Title'=>$judul_proposal, 
          'Is_Proposal'=>$is_proposal, 
          'Bnk'=>$grade_department->Weight_Value*($sks_trough == null ? $sks_krs:$sks_trough), 
          'Sks_Trough'=>($sks_trough == null ? $sks_krs:$sks_trough), 
          'Application_Date'=>$tgl_permohonan, 
          'Term_Year_Id' => ($term_year ? $term_year->Term_Year_Id:$smt_mulai),
          'Term_Year_Id_Start' => $smt_mulai, 
          'Term_Year_Id_Complete'=>$smt_selesai]);

        if($request->penguji != 0){
          $penguji = DB::table('acd_thesis_examiner')
          ->insert([
            'Thesis_Id' => $id,
            'Order_Id' => $request->examiner_number,
            'Examiner_Id' => $request->penguji
          ]);
        }
          return Redirect::back()->withErrors('Berhasil Mengubah Tugas Akhir')->with('success', true);
      } catch (\Exception $e) {
        return Redirect::back()->withErrors('Gagal Mengubah Tugas Akhir')->with('success', false);
      }
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
      $q=DB::table('acd_thesis')->where('Thesis_Id', $id)->delete();
      echo json_encode($q);
    }

    public function export($id)
    {
      $proses = Input::get('proses');

      switch ($proses) {
        case 1:
          try{
            $faculty=DB::table('acd_student')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
            ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
            ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

            $department_student=DB::table('mstr_department')->join('acd_student','acd_student.Department_Id','=','mstr_department.Department_Id')->where('Student_Id',$id)->first();
            $Education_prog_type=DB::table('mstr_education_program_type')->where('Education_Prog_Type_Id', $department_student->Education_Prog_Type_Id)->first();

            $data = DB::table('acd_yudisium')->select('mstr_term_year.Term_Year_Name','acd_yudisium.*', 'acd_thesis.*', 'acd_student.*',
            DB::raw('(acd_yudisium.Application_Date) as apldate'),
            DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'),
            DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
            ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
            ->join('acd_transcript','acd_transcript.Student_Id','=','acd_yudisium.Student_Id')
            ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
            ->where('acd_student.Student_Id', $id)->first();

            $address = DB::table('acd_student_address')->join('acd_student','acd_student_address.Student_Id','=','acd_student.Student_Id')->join('mstr_address_category','mstr_address_category.Address_Category_Id','=','acd_student_address.Address_Category_Id')->where('mstr_address_category.Address_Category_Code', 0)->first();

            $query=DB::table('acd_transcript')
            ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
            DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
            DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
            ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Student_Id', $id)->first();

            $thesis=DB::table('acd_thesis')->where('Student_Id', $id)->first();

            View()->share(['faculty'=>$faculty,'Education_prog_type'=>$Education_prog_type,'data'=>$data,'query'=>$query,'thesis'=>$thesis,'address'=>$address]);
              $pdf = PDF::loadView('tugas_akhir/cetak/surat_permohonan_ta');
              return $pdf->stream('surat_permohonan_ta.pdf');
          } catch(EXCEPTION $e){
          }
          break;

          case 2:
            try{
              $faculty=DB::table('acd_student')
              ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
              ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

              $data = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*' ,'acd_student.Full_Name as Full_Name_Student' , 'mstr_department.Department_Name', 'mstr_term_year.Term_Year_Name','pembimbing1.Full_Name as pembimbing_1','pembimbing2.Full_Name as pembimbing_2','penguji1.Full_Name as penguji_1','penguji2.Full_Name as penguji_2','penguji3.Full_Name as penguji_3')
              ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
              ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
              ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
              ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
              ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
              ->join('emp_employee as penguji1','penguji1.Employee_Id','=','acd_thesis.Examiner_1')
              ->join('emp_employee as penguji2','penguji2.Employee_Id','=','acd_thesis.Examiner_2')
              ->join('emp_employee as penguji3','penguji3.Employee_Id','=','acd_thesis.Examiner_3')
              ->where('acd_student.Student_Id', $id)->first();

              $Employ = DB::table('acd_functional_position_term_year')
                         ->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
                         ->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
                         ->where('acd_functional_position_term_year.Term_Year_Id', $data->Term_Year_Id)
                         ->where('acd_functional_position_term_year.Department_Id', $data->Department_Id)
                         ->where('emp_functional_position.Functional_Position_Code', 'KP')->first();

             $Employcount = DB::table('acd_functional_position_term_year')
                        ->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
                        ->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
                        ->where('acd_functional_position_term_year.Term_Year_Id', $data->Term_Year_Id)
                        ->where('acd_functional_position_term_year.Department_Id', $data->Department_Id)
                        ->where('emp_functional_position.Functional_Position_Code', 'KP')->count();

              if($Employcount == 0){
                $Employ = "";
              } else{
                $Employ->Full_Name;
              }

                View()->share(['faculty'=>$faculty,'data'=>$data,'Employee'=>$Employ]);
                $pdf = PDF::loadView('tugas_akhir/cetak/surat_ijin_ta');
                return $pdf->stream('surat_ijin_ta.pdf');
            } catch(EXCEPTION $e){
            }
            break;
          case 3:
            try{
              $data = DB::table('acd_yudisium')->select('mstr_term_year.Term_Year_Name','acd_yudisium.*', 'acd_thesis.*', 'acd_student.*','pembimbing1.Full_Name as pembimbing_1','pembimbing2.Full_Name as pembimbing_2',DB::raw('(acd_yudisium.Application_Date) as apldate') ,
                DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
                ->join('acd_transcript','acd_transcript.Student_Id','=','acd_yudisium.Student_Id')
                ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
                ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
                ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
                ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
                ->where('acd_student.Student_Id', $id)->first();
              $faculty=DB::table('acd_student')
              ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
              ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

              View()->share(['faculty'=>$faculty,'data'=>$data]);
                $pdf = PDF::loadView('tugas_akhir/cetak/lembar_monitoring');
                return $pdf->stream('lembar_monitoring.pdf');
            } catch(EXCEPTION $e){
            }
            break;
          case 4:
            try{

              $data = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*',DB::raw('(acd_yudisium.Application_Date) as apldate') ,
                DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
                ->join('acd_transcript','acd_transcript.Student_Id','=','acd_yudisium.Student_Id')
                ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
                ->where('acd_student.Student_Id', $id)->first();

              //$notingrad = DB::table('acd_graduation_reg')->select('Student_Id');

              $enddateyudisium=DB::table('acd_graduation_period')->select('Period_Name','Graduation_Date')
              ->where('End_Date_Yudisium', '>=',$data->Graduate_Date)
              ->first();

              $faculty=DB::table('acd_student')
              ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
              ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

              $student=DB::table('acd_student')->where('Student_Id',$id)->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->select('mstr_department.Department_Name','acd_student.*',
              DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))->first();

              View()->share(['faculty'=>$faculty,'data'=>$data, 'student'=>$student,'enddateyudisium'=>$enddateyudisium]);

                $pdf = PDF::loadView('yudisium/pengantar_pembayaran_wisuda');
                return $pdf->stream('pengantar_pembayaran_wisuda.pdf');
            } catch(EXCEPTION $e){
            }
            break;

          case 5:
          try{
            $faculty=DB::table('acd_student')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
            ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
            ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

            $data = DB::table('acd_yudisium')->select('acd_yudisium.*','mstr_room.Room_Name' , 'acd_thesis.*', 'acd_student.*' ,'acd_student.Full_Name as Full_Name_Student' , 'mstr_department.Department_Name', 'mstr_term_year.Term_Year_Name','pembimbing1.Full_Name as pembimbing_1','pembimbing2.Full_Name as pembimbing_2','penguji1.Full_Name as penguji_1','penguji2.Full_Name as penguji_2','penguji3.Full_Name as penguji_3')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
            ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
            ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
            ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
            ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
            ->join('emp_employee as penguji1','penguji1.Employee_Id','=','acd_thesis.Examiner_1')
            ->join('emp_employee as penguji2','penguji2.Employee_Id','=','acd_thesis.Examiner_2')
            ->join('emp_employee as penguji3','penguji3.Employee_Id','=','acd_thesis.Examiner_3')
            ->join('mstr_room','mstr_room.Room_Id','=','acd_thesis.Seminar_Room')
            ->where('acd_student.Student_Id', $id)->first();

            $Employ = DB::table('acd_functional_position_term_year')
                       ->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
                       ->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
                       ->where('acd_functional_position_term_year.Term_Year_Id', $data->Term_Year_Id)
                       ->where('acd_functional_position_term_year.Department_Id', $data->Department_Id)
                       ->where('emp_functional_position.Functional_Position_Code', 'KP')->first();

           $Employcount = DB::table('acd_functional_position_term_year')
                      ->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
                      ->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
                      ->where('acd_functional_position_term_year.Term_Year_Id', $data->Term_Year_Id)
                      ->where('acd_functional_position_term_year.Department_Id', $data->Department_Id)
                      ->where('emp_functional_position.Functional_Position_Code', 'KP')->count();

            if($Employcount == 0){
              $Employ = "";
            } else{
              $Employ->Full_Name;
            }

              View()->share(['faculty'=>$faculty,'data'=>$data,'Employee'=>$Employ]);
              $pdf = PDF::loadView('tugas_akhir/cetak/surat_undangan_seminar_ta');
              return $pdf->stream('surat_undangan_seminar_ta.pdf');
          } catch(EXCEPTION $e){
          }
          break;
         case 6:
            try{

              $faculty=DB::table('acd_student')
              ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
              ->where('Student_Id',$id)->first();

              $student=DB::table('acd_student')->where('Student_Id',$id)->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->select('mstr_department.Department_Name','acd_student.*',
              DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))->first();

              $program_type=DB::table('acd_student')
              ->where('acd_student.Student_Id',$id)
              ->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
              ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
              ->select('mstr_education_program_type.Program_Name')
              ->first();

              $data = DB::table('acd_transcript')
              ->select('acd_student.Full_Name','acd_transcript.*',
               DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
              ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
               ->where('acd_student.Student_Id',$id)
              ->first();

              $dataisi = DB::table('acd_transcript')
              ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
              ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
              ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
              ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
               DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
              ->where('acd_transcript.Student_Id',$id)
              ->get();

              $query=DB::table('acd_transcript')
              ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
              DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
              DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
              ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
              ->where('acd_student.Student_Id',$id)->first();

              $predikat=DB::table('acd_yudisium')
              ->join('mstr_graduate_predicate','mstr_graduate_predicate.Graduate_Predicate_Id','=','acd_yudisium.Graduate_Predicate_Id')
                ->join('acd_student','acd_student.Student_Id','=','acd_yudisium.Student_Id')
                ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
                ->where('acd_student.Student_Id',$id)->first();

              $dosen=DB::table('acd_yudisium')
                ->select(DB::raw('emp_employee.Full_Name as namadosen'),DB::raw('emp_employee.Nik as nik'))
                ->join('emp_employee','emp_employee.Employee_Id','acd_yudisium.Department_Functionary_Name')
                ->join('acd_student','acd_student.Student_Id','=','acd_yudisium.Student_Id')
                ->where('acd_student.Student_Id',$id)->first();

              $dataNo=DB::table('acd_yudisium')
              ->join('acd_student','acd_student.Student_Id','=','acd_yudisium.Student_Id')
              ->where('acd_student.Student_Id',$id)->first();

              $thesis_title=DB::table('acd_thesis')->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')->where('acd_student.Student_Id',$id)->select('acd_thesis.Thesis_Title')->first();

              $jabatan = DB::table('emp_functional_position')->join('acd_yudisium','acd_yudisium.Department_Functionary','=','emp_functional_position.Functional_Position_Id')
              ->join('acd_student','acd_student.Student_Id','=','acd_yudisium.Student_Id')
              ->where('acd_student.Student_Id', $id)->first();

              $graduate_predikat=DB::table('mstr_graduate_predicate')->get();

              $data1 = DB::table('acd_yudisium')->select('mstr_term_year.Term_Year_Name','mstr_department.Department_Name','acd_yudisium.*', 'acd_thesis.*','emp_employee.*' , 'acd_student.*','emp_functional_position.*','pembimbing1.Full_Name as pembimbing_1' ,'pembimbing2.Full_Name as pembimbing_2',DB::raw('(acd_yudisium.Application_Date) as apldate') ,
                DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
                ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
                ->join('acd_transcript','acd_transcript.Student_Id','=','acd_yudisium.Student_Id')
                ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
                ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
                ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
                ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
                ->join('emp_functional_position','acd_yudisium.Faculty_Functionary','=','emp_functional_position.Functional_Position_Id')
                ->join('emp_employee','acd_yudisium.Faculty_Functionary_Name','=','emp_employee.Employee_Id')
                ->where('acd_student.Student_Id', $id)->first();

               View()->share(['dataNo'=>$dataNo, 'data1'=>$data1,'dosen'=>$dosen,'jabatan'=>$jabatan, 'thesis_title'=>$thesis_title, 'faculty'=>$faculty,'student'=>$student,'program_type'=>$program_type,'data'=>$data,'dataisi'=>$dataisi ,'query_'=>$query,'predikat'=>$predikat]);

                $pdf = PDF::loadView('tugas_akhir/cetak/berita_acara_seminar');
                return $pdf->stream('berita_acara_seminar.pdf');
            } catch(EXCEPTION $e){
            }
            break;

          case 7:
             try{
               $faculty=DB::table('acd_student')
               ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
               ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
               ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

               $data = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*' ,'acd_student.Full_Name as Full_Name_Student' , 'mstr_department.Department_Name', 'mstr_term_year.Term_Year_Name','pembimbing1.Full_Name as pembimbing_1','pembimbing2.Full_Name as pembimbing_2')
               ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
               ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
               ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
               ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
               ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
               ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
               ->where('acd_student.Student_Id', $id)->first();

                 View()->share(['data'=>$data,'faculty'=>$faculty]);

                 $pdf = PDF::loadView('tugas_akhir/cetak/daftar_hadir_seminar');
                 return $pdf->stream('daftar_hadir_seminar.pdf');
             } catch(EXCEPTION $e){
             }
             break;

             case 8:
             try{
               $faculty=DB::table('acd_student')
               ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
               ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
               ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

               $department_student=DB::table('mstr_department')->join('acd_student','acd_student.Department_Id','=','mstr_department.Department_Id')->where('Student_Id',$id)->first();
               $Education_prog_type=DB::table('mstr_education_program_type')->where('Education_Prog_Type_Id', $department_student->Education_Prog_Type_Id)->first();

               $data = DB::table('acd_yudisium')->select('mstr_term_year.Term_Year_Name','acd_yudisium.*', 'acd_thesis.*', 'acd_student.*',
               DB::raw('(acd_yudisium.Application_Date) as apldate'),
               DB::raw('(pembimbing1.Full_Name) as pem1'),
               DB::raw('(pembimbing2.Full_Name) as pem2'),
               DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'),
               DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
               ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
               ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
               ->join('acd_transcript','acd_transcript.Student_Id','=','acd_yudisium.Student_Id')
               ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
               ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
               ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
               ->where('acd_student.Student_Id', $id)->first();

               $address = DB::table('acd_student_address')->join('acd_student','acd_student_address.Student_Id','=','acd_student.Student_Id')->join('mstr_address_category','mstr_address_category.Address_Category_Id','=','acd_student_address.Address_Category_Id')->where('mstr_address_category.Address_Category_Code', 0)->first();

               $query=DB::table('acd_transcript')
               ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
               DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
               DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
               ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Student_Id', $id)->first();

               $thesis=DB::table('acd_thesis')->where('Student_Id', $id)->first();

               View()->share(['faculty'=>$faculty,'Education_prog_type'=>$Education_prog_type,'data'=>$data,'query'=>$query,'thesis'=>$thesis,'address'=>$address]);
                 $pdf = PDF::loadView('tugas_akhir/cetak/surat_permohonan_pendadaran');
                 return $pdf->stream('surat_permohonan_pendadaran.pdf');
             } catch(EXCEPTION $e){
             }
                break;
          case 9:
          try{
            $data = DB::table('acd_yudisium')->select('acd_student.*')
            ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
            ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
            ->where('acd_student.Student_Id', $id)->first();

            $jumlah = Input::get('jumlah');
            $terbilang = Input::get('terbilang');
            $petugas = Input::get('petugas');

            View()->share(['data'=>$data,'jumlah'=>$jumlah,'terbilang'=>$terbilang,'petugas'=>$petugas]);
              $pdf = PDF::loadView('tugas_akhir/cetak/pengantar_pembayaran_pendadaran');
              return $pdf->stream('pengantar_pembayaran_pendadaran.pdf');
          } catch(EXCEPTION $e){
          }
             break;

          case 10:
          try{
            $faculty=DB::table('acd_student')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
            ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
            ->select('mstr_faculty.Faculty_Name')->where('Student_Id',$id)->first();

            $department_student=DB::table('mstr_department')->join('acd_student','acd_student.Department_Id','=','mstr_department.Department_Id')->where('Student_Id',$id)->first();
            $Education_prog_type=DB::table('mstr_education_program_type')->where('Education_Prog_Type_Id', $department_student->Education_Prog_Type_Id)->first();

            $data = DB::table('acd_yudisium')->select('mstr_term_year.Term_Year_Name','exam1.Full_Name as ex1','exam2.Full_Name as ex2','exam3.Full_Name as ex3' ,'mstr_room.Room_Name as rom' ,'acd_yudisium.*', 'acd_thesis.*', 'acd_student.*',
            DB::raw('(acd_yudisium.Application_Date) as apldate'),
            DB::raw('(pembimbing1.Full_Name) as pembimbing_1'),
            DB::raw('(pembimbing2.Full_Name) as pembimbing_2'),
            DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'),
            DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
            ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
            ->join('acd_transcript','acd_transcript.Student_Id','=','acd_yudisium.Student_Id')
            ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
            ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
            ->join('emp_employee as exam1','exam1.Employee_Id','=','acd_thesis.Examiner_1')
            ->join('emp_employee as exam2','exam2.Employee_Id','=','acd_thesis.Examiner_2')
            ->join('emp_employee as exam3','exam3.Employee_Id','=','acd_thesis.Examiner_3')
            ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
            ->join('mstr_room','mstr_room.Room_Id','=','acd_thesis.Thesis_Exam_Room')
            ->where('acd_student.Student_Id', $id)->first();

            $address = DB::table('acd_student_address')->join('acd_student','acd_student_address.Student_Id','=','acd_student.Student_Id')->join('mstr_address_category','mstr_address_category.Address_Category_Id','=','acd_student_address.Address_Category_Id')->where('mstr_address_category.Address_Category_Code', 0)->first();

            $query=DB::table('acd_transcript')
            ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
            DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
            DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
            ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Student_Id', $id)->first();

            $thesis=DB::table('acd_thesis')->where('Student_Id', $id)->first();

            View()->share(['faculty'=>$faculty,'Education_prog_type'=>$Education_prog_type,'data'=>$data,'query'=>$query,'thesis'=>$thesis,'address'=>$address]);
              $pdf = PDF::loadView('tugas_akhir/cetak/surat_undangan_pendadaran');
              return $pdf->stream('surat_undangan_pendadaran.pdf');
          } catch(EXCEPTION $e){
          }
             break;


         case 11:
         try {
           $data = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*', 'mstr_department.Department_Name', 'mstr_term_year.Term_Year_Name')
           ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
           ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
           ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
           ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
           ->where('acd_student.Student_Id', $id)->first();

           $Employ = DB::table('acd_functional_position_term_year')
                      ->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
                      ->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
                      ->where('acd_functional_position_term_year.Term_Year_Id', $data->Term_Year_Id)
                      ->where('acd_functional_position_term_year.Department_Id', $data->Department_Id)
                      ->where('emp_functional_position.Functional_Position_Code', 'KP')->first();

           View()->share(['data'=>$data,'Employee'=>$Employ]);

           $pdf = PDF::loadView('tugas_akhir/cetak/form_nilai');
           return $pdf->stream('form_nilai.pdf');
         } catch (\Exception $e) {
         }

         break;

          case 12:
             try {
               $data = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*' ,'acd_student.Full_Name as Full_Name_Student' , 'mstr_department.Department_Name', 'mstr_term_year.Term_Year_Name','penguji1.Full_Name as penguji_1','penguji2.Full_Name as penguji_2','penguji3.Full_Name as penguji_3')
               ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
               ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
               ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
               ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
               ->join('emp_employee as penguji1','penguji1.Employee_Id','=','acd_thesis.Examiner_1')
               ->join('emp_employee as penguji2','penguji2.Employee_Id','=','acd_thesis.Examiner_2')
               ->join('emp_employee as penguji3','penguji3.Employee_Id','=','acd_thesis.Examiner_3')
               ->where('acd_student.Student_Id', $id)->first();

               View()->share(['data'=>$data]);

               $pdf = PDF::loadView('tugas_akhir/cetak/form_berita_acara');
               return $pdf->stream('form_berita_acara.pdf');
             } catch (\Exception $e) {
             }

             break;
         case 13:
            try {
              $data = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*' ,'acd_student.Full_Name as Full_Name_Student' , 'mstr_department.Department_Name', 'mstr_term_year.Term_Year_Name','pembimbing1.Full_Name as pembimbing_1','pembimbing2.Full_Name as pembimbing_2')
              ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
              ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
              ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
              ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
              ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
              ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
              ->where('acd_student.Student_Id', $id)->first();

              View()->share(['data'=>$data]);

              $pdf = PDF::loadView('tugas_akhir/cetak/lembar_revisi_ta');
              return $pdf->stream('lembar_revisi_ta.pdf');
            } catch (\Exception $e) {
            }

            break;
          case 14:
             try {
               $data = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*' ,'acd_student.Full_Name as Full_Name_Student' , 'mstr_department.Department_Name', 'mstr_term_year.Term_Year_Name','pembimbing1.Full_Name as pembimbing_1','pembimbing2.Full_Name as pembimbing_2','penguji1.Full_Name as penguji_1','penguji2.Full_Name as penguji_2','penguji3.Full_Name as penguji_3')
               ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
               ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
               ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
               ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
               ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
               ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
               ->join('emp_employee as penguji1','penguji1.Employee_Id','=','acd_thesis.Examiner_1')
               ->join('emp_employee as penguji2','penguji2.Employee_Id','=','acd_thesis.Examiner_2')
               ->join('emp_employee as penguji3','penguji3.Employee_Id','=','acd_thesis.Examiner_3')
               ->where('acd_student.Student_Id', $id)->first();

               View()->share(['data'=>$data]);

               $pdf = PDF::loadView('tugas_akhir/cetak/lembar_pertanyaan');
               return $pdf->stream('lembar_pertanyaan.pdf');
             } catch (\Exception $e) {
             }

             break;
           case 15:
              try {
                $data = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*' ,'acd_student.Full_Name as Full_Name_Student' , 'mstr_department.Department_Name', 'mstr_term_year.Term_Year_Name','pembimbing1.Full_Name as pembimbing_1','pembimbing2.Full_Name as pembimbing_2','penguji1.Full_Name as penguji_1','penguji2.Full_Name as penguji_2','penguji3.Full_Name as penguji_3')
                ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_yudisium.Term_Year_Id')
                ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
                ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
                ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
                ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
                ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
                ->join('emp_employee as penguji1','penguji1.Employee_Id','=','acd_thesis.Examiner_1')
                ->join('emp_employee as penguji2','penguji2.Employee_Id','=','acd_thesis.Examiner_2')
                ->join('emp_employee as penguji3','penguji3.Employee_Id','=','acd_thesis.Examiner_3')
                ->where('acd_student.Student_Id', $id)->first();

                View()->share(['data'=>$data]);

                $pdf = PDF::loadView('tugas_akhir/cetak/nilai_skripsi');
                return $pdf->stream('nilai_skripsi.pdf');
              } catch (\Exception $e) {
              }

              break;

          default:
          try{
          } catch(EXCEPTION $e){
            return view('yudisium/show');
          }
            break;
      }
    }

  public function dosen_penguji(Request $request)
  {
    $dosen=DB::table('acd_department_lecturer')
    ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_department_lecturer.Employee_Id')
    ->get();

    $penguji_in = DB::table('acd_thesis_examiner as ate')
    ->where('Thesis_Id',$request->thesis)
    ->select('Examiner_Id');

    $penguji_in_thesis = DB::table('acd_thesis_examiner as ate')
    ->where('Thesis_Id',$request->thesis)
    ->join('emp_employee as ee','ate.Examiner_Id','=','ee.Employee_Id')
    ->get();
    
    $penguji = DB::table('emp_employee')
    ->WhereNotIn('Employee_Id',$penguji_in)
    ->get();

    return view('tugas_akhir/dosen_penguji')
    ->with('term_year', $request->term_year)
    ->with('angkatan', $request->angkatan)
    ->with('dosen', $dosen)
    ->with('penguji', $penguji)
    ->with('penguji_in_thesis', $penguji_in_thesis)
    ->with('department', $request->department)
    ->with('request', $request);
  }

  public function storedosen_penguji(Request $request)
  {
    // dd($request->all());
    $penguji = DB::table('acd_thesis_examiner')
    ->insert([
      'Thesis_Id' => $request->thesis_id,
      'Order_Id' => $request->examiner_number,
      'Examiner_Id' => $request->penguji
    ]);
    return Redirect::back()->withErrors('Berhasil Mengubah Penguji')->with('success', true);
  }

  public function deletedosen_penguji(Request $request,$id)
  {
    $q=DB::table('acd_thesis_examiner')->where('Thesis_Examiner_Id', $id)->delete();
    return response()->json([
        "success" => true,
        "message" => 'success delete data'
    ], 200);
  }
}