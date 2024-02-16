<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use App\Http\Models\KrsOnlineData;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;
use App\GetDepartment;

class Krs_matakuliahController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','show']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','export']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','export']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','export']]);
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
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       $select_class_program = DB::table('mstr_department_class_program')
       ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
       ->join('mstr_department','mstr_department.Department_Id','=','mstr_department_class_program.Department_Id')
       ->where('mstr_department_class_program.Department_Id', $department)
       ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
       ->get();

       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
       ->get();

      $select_department = GetDepartment::getDepartment();
      $data = DB::table('acd_offered_course')
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
        ->where('acd_offered_course.Department_Id', $department)
        ->where('acd_offered_course.Class_Prog_Id', $class_program)
        ->where('acd_offered_course.Term_Year_Id', $term_year)
        ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(Course_Name) like '%" . strtolower($search) . "%'");
          $query->orwhere('acd_course.Course_Code', 'LIKE', '%'.$search.'%');
        })
        ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
        ->orderBy('acd_course.Course_Name', 'asc')
        ->orderBy('mstr_class.class_Name', 'asc')
        ->paginate($rowpage);

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
       return view('krs_matakuliah/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year);
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
        $id = Input::get('id');
        $currentsearch = Input::get('current_search');
        $currentpage = Input::get('current_page');
        $currentrowpage = Input::get('current_rowpage');
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $term_year = Input::get('term_year');
        $FacultyId = Auth::user()->Faculty_Id;

        $entry_year = Input::get('entry_year');

        if($FacultyId==""){
          $Offered_Course = DB::table('acd_offered_course')
          ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')

          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','mstr_term_year.Year_Id')
          ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
          ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name' , 'mstr_term_year.Term_Year_Name','mstr_entry_year.Entry_Year_Id', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
          ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('mstr_class.class_Name', 'asc')->first();
        } else{

          $Offered_Course = DB::table('acd_offered_course')
          ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
          ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','mstr_term_year.Year_Id')
          ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
          ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name' , 'mstr_term_year.Term_Year_Name','mstr_entry_year.Entry_Year_Id', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
          ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
          ->orderBy('acd_course.Course_Name', 'asc')
          ->orderBy('mstr_class.class_Name', 'asc')->first();
        }


        $select_entry_year = DB::table('mstr_entry_year')->orderBy('Entry_Year_Code','desc')->get();

        //20201104
        $getofferedcoursekrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($department,$term_year,$class_program,$entry_year,$Offered_Course->Course_Id));
        $mhs_out = DB::table('acd_student_out')->where('Department_From_Id', $department)->where('Class_Prog_From_Id', $class_program)->where('Entry_Year_Id', $entry_year)->select('Student_Id');
        $member = DB::table('acd_student_krs')        
        ->where('acd_student_krs.Course_Id', $Offered_Course->Course_Id)
        ->where('acd_student_krs.Class_Prog_Id', $Offered_Course->Class_Prog_Id)
        // ->where('acd_student_krs.Class_Id', $Offered_Course->Class_Id)
        ->where('acd_student_krs.Is_Approved', 1)
        ->where('Term_Year_Id', $Offered_Course->Term_Year_Id)
        ->select('acd_student_krs.Student_Id');
        // $equivalen = DB::table('acd_student')->where('Nim', 'LIKE', '%P%')->select('Student_Id')->first();
        //->join('acd_student','acd_student.Student_Id','=','acd_student_krs.Student_Id')->where('Nim', 'LIKE', '%P%')
        $equivalen=DB::table('acd_student')->where('Nim', 'LIKE', '%P%')->select('Student_Id');
        $data = DB::table('acd_student')
        ->leftjoin('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
        ->leftjoin('mstr_religion','acd_student.Religion_Id','=','mstr_religion.Religion_Id')
        ->where('Entry_Year_Id', $entry_year)
        ->where('Department_Id', $department)
        ->where('acd_student.Class_Prog_Id', $class_program)
        ->WhereNotIn('Student_Id',$equivalen)
        ->WhereNotIn('Student_Id', $member)
        ->WhereNotIn('Student_Id', $mhs_out)
        ->orderBy('Nim','ASC')->get();

        // dd([[$department],[$term_year],[$class_program],[$entry_year],[$Offered_Course->Course_Id],[$getofferedcoursekrs]]);

        return view('krs_matakuliah/create_peserta')->with('Offered_Course_id', $id)->with('entry_year',$entry_year)->with('Offered_Course', $Offered_Course)->with('getofferedcoursekrs', $getofferedcoursekrs)->with('Entry_Year_Id', $entry_year)->with('query', $data)->with('select_entry_year', $select_entry_year)->with('department', $department)->with('term_year', $term_year)->with('class_program', $class_program)->with('currentsearch',$currentsearch)->with('currentpage',$currentpage)->with('currentrowpage',$currentrowpage);
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
         'Class_Prog_Id'=>'required',
         'Term_Year_Id' => 'required',
         'Course_Id' => 'required',
         'Class_Id' => 'required'
       ]);
       // $error = "StoreProccedure Belum Selesai";
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Term_Year_Id = Input::get('Term_Year_Id');
             $Course_Id = Input::get('Course_Id');
             $Class_Id = Input::get('Class_Id');
             $Student = input::get('Student_Id');
             $Department_Id = Input::get('Department_Id');
             $Entry_Year_Id = Input::get('Entry_Year_Id');
             $Offered_Course_id = Input::get('Offered_Course_id');
             $biaya = Input::get('biaya');
             $krsnya = Input::get('krsnya');

        if ($Student == null || count($Student) == 0) {
          return Redirect::back()->withErrors('Pilih Data Terlebih Dahulu');
        }


        $offeredcourse = DB::table('acd_offered_course')->where('Offered_Course_id', $Offered_Course_id)->first();
        $count_student = DB::table('acd_student_krs')->where('Term_Year_Id', $Term_Year_Id)->where('Class_Prog_Id', $Class_Prog_Id)->where('Course_Id', $Course_Id)->where('Class_Id', $Class_Id)->where('Is_Approved', 1)
        ->select('Student_Id')->count();
        $sisakuota = $offeredcourse->Class_Capacity - $count_student;
        // dd($sisakuota);
        if ( $sisakuota < count($Student)) {
          return Redirect::back()->withErrors('Kuota Kelas tidak Cukup');
        }

        $i = 0;
        $error = array();
        $success = false;

        foreach ($Student as $Student_Id) {
        $student = Db::table('acd_student')->where('Student_Id', $Student_Id)->first();

        $coursecur=DB::table('acd_course_curriculum')->select('Course_Id');
        if($coursecur==""){
          return Redirect::back()->withErrors('Mata Kuliah Kurikulum Belum DIisi');
        }else{
          $getcoursecostkrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($Department_Id,$Term_Year_Id,$Class_Prog_Id,$Entry_Year_Id,$Course_Id));
          // $getcoursecostkrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($Department_Id,$Term_Year_Id,$Class_Prog_Id,$Entry_Year_Id,$Course_Id));
        }
        // dd([[$Department_Id],[$Term_Year_Id],[$Class_Prog_Id],[$Entry_Year_Id],[$Course_Id]]);

        $sksallowed = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($Term_Year_Id,$Student_Id));
        // dd($Term_Year_Id,$Student_Id);
        $sksambil = DB::table('acd_student_krs')->where('Student_Id', $Student_Id)->where('Term_Year_Id', $Term_Year_Id)
        ->select(DB::raw('(SUM(acd_student_krs.Sks)) as SKS'))->get();
        $allowedsks = 0;
        $ambilsks = 0;
        $Sks = 0;
        $Amount = 0;
        foreach ($sksallowed as $a) { $allowedsks = $a->AllowedSKS; }
        foreach ($sksambil as $b) { $ambilsks = $b->SKS; }
        foreach ($getcoursecostkrs as $c) { $Sks = $c->applied_sks; $Amount = $c->amount; } // MASIH ADA ERROR DISINI
        // dd($getcoursecostkrs);

        // dd([[$Department_Id],[$Term_Year_Id],[$Class_Prog_Id],[$Entry_Year_Id],[$Course_Id],[$getcoursecostkrs]]);

        //Ini dimatikan dulu
        // $saldo = DB::select('CALL usp_saldo(?,?)',array($Student_Id,$Term_Year_Id));
        $saldo = 0;
        $sisasaldo = 0;
        //Ini dimatikan dulu
        // foreach ($saldo as $k) {$sisasaldo = $k->SisaSaldoSaatIni; }

        $curentryyear = DB::table('acd_curriculum_entry_year')->where('Entry_Year_Id', $student->Entry_Year_Id)->where('Department_Id', $Department_Id)->where('Term_Year_Id', $Term_Year_Id)->first();
        $curentryyearcount = DB::table('acd_curriculum_entry_year')->where('Entry_Year_Id', $student->Entry_Year_Id)->where('Department_Id', $Department_Id)->where('Term_Year_Id', $Term_Year_Id)->count();
        // dd([[$student->Entry_Year_Id],[$Department_Id],[$Term_Year_Id],[$curentryyearcount]]);

        $student_bill = DB::select('CALL usp_GetStudentBill(?,?,?)',array($student->Register_Number,'',''));
        $q = 0;
        $ListTagihan = [];
          $total=0;
        if($student_bill!=null){
          foreach ($student_bill as $key) if($key->Term_Year_Bill_id == $Term_Year_Id){
            if($key->Term_Year_Bill_id <= $Term_Year_Id ){
              $ListTagihan[$q]['Amount'] = $key->Amount;
              $ListTagihan[$q]['Cost_Item_Name'] = $key->Cost_Item_Name;
              $q++;
            }

            $sumAmount =0;
                  foreach ($ListTagihan as $tagihan) {
                    $sumAmount += $tagihan['Amount'];
                  }
            $total = number_format($sumAmount,'0',',','.');
          }
        }

        if($curentryyear == null){
          $message = "Kurikulum Angkatan Belum diset";
        }else{
          $message = '';
          $check_krs = DB::table('acd_student_krs')->where([['Student_Id',$Student_Id],['Term_Year_Id',$Term_Year_Id],['Course_Id',$Course_Id],['Class_Prog_Id',$Class_Prog_Id]])->first();

          $matakuliahkurikulum = DB::table('acd_course_curriculum')->where('Curriculum_Id', $curentryyear->Curriculum_Id)->where('Course_Id', $Course_Id)->where('Class_Prog_Id', $Class_Prog_Id)->get();
          $matakuliahkurikulum2 = DB::table('acd_course_curriculum')->WhereNotNull('Applied_Sks')->where('Curriculum_Id', $curentryyear->Curriculum_Id)->where('Course_Id', $Course_Id)->where('Class_Prog_Id', $Class_Prog_Id)->get();
          $tagihankrs = DB::select('CALL usp_GetStudentBill_For_KRS(?,?,?)',[$student->Register_Number,'','']);
          
          $result = KrsOnlineData::getPrerequisite($Course_Id, $Department_Id);
            if (collect($result)->count() != 0) {
                $prerequisite = $result->Prerequisite_Id;
                $prerequisiteid =DB::table('acd_prerequisite_detail')->where('Prerequisite_Id', $prerequisite)->get();

                $message2 = '';
                if ($prerequisiteid->count() > 0) {
                    foreach ($prerequisiteid as $item) {
                      // dd($item);
                      $departmentgrade = DB::table('acd_prerequisite_detail')
                      ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_prerequisite_detail.Grade_Letter_Id')
                      ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                      ->where('Prerequisite_Id', $item->Prerequisite_Id)
                      ->where('acd_grade_department.Department_Id', $Department_Id)
                      ->select('acd_grade_department.Weight_Value')->first();

                      $gradedetail = DB::table('acd_transcript')
                      ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
                      ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                      ->where('acd_transcript.Course_Id', $item->Course_Id)->where('acd_transcript.Student_Id', $Student_Id)
                      ->where('acd_grade_department.Department_Id', $Department_Id)
                      ->select('acd_grade_department.Weight_Value')->first();

                      $studentgrade = DB::table('acd_transcript')
                      ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
                      ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                      ->where('acd_transcript.Course_Id', $item->Course_Id)->where('acd_transcript.Student_Id', $Student_Id)
                      ->where('acd_grade_department.Department_Id', $Department_Id)
                      ->select('acd_grade_department.Weight_Value')->first();
                      
                      $std = DB::table('acd_student')->where('Student_Id',$Student_Id)->first();
                      $querys=DB::table('acd_transcript')
                      ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
                      DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
                      DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                      ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Nim',$std->Nim)->first();

                      $cekdata = DB::table('acd_transcript')->where('Course_Id', $item->Course_Id)->where('Student_Id', $Student_Id)->count();
                      // dd($cekdata);

                      $cour = DB::table('acd_course')->where('Course_Id', $item->Course_Id)->first();
                      $gra = DB::table('acd_grade_letter')->where('Grade_Letter_Id', $item->Grade_Letter_Id)->first();

                        if ($item->Prerequisite_Type_Id == 1) {
                          if ($cekdata <= 0) {
                            $message2 = 'Anda Belum Menggambil Matakuliah '.$cour->Course_Name.' Atau Nilai Masih Kosong';
                          break;
                          }
                        }

                        if ($item->Prerequisite_Type_Id == 2) {
                          $cour = DB::table('acd_course')->where('Course_Id', $item->Course_Id)->first();
                          $count_course = DB::table('acd_student_krs')->where('Student_Id', $Student_Id)->where('Course_Id', $item->Course_Id)->where('Class_Prog_Id', $clasprogid)->where('Term_Year_Id', $term_year)->count();

                          if ($count_course > 0) {

                          }else {
                            $message2 = "Matakuliah ".$cour->Course_Name." harus diambil terlebih dahulu.";
                            break;
                          }

                        }

                        if ($item->Prerequisite_Type_Id == 3) {
                            if ($studentgrade < $departmentgrade) {
                                $message2 = "nilai anda kurang.";
                                break;
                            }
                        }

                        if ($item->Prerequisite_Type_Id == 4) {
                          $entryyear1 = DB::table('acd_student')->where('Nim', $nim)->select('Entry_Year_Id')->first();
                          $entryterm1 = DB::table('acd_student')->where('Nim', $nim)->select('Entry_Term_Id')->first();

                          $entry_year = $entryyear1->Entry_Year_Id."".$entryterm1->Entry_Term_Id;
                          $term_years = DB::table('acd_student_krs')->where('Student_Id', $Student_Id)->select('Term_Year_Id')->orderby('Term_Year_Id', 'DESC')->first();

                          // result = hasil semester
                          $result = 0;
                          $result = $term_years->Term_Year_Id - $entry_year;
                          // dd($result);
                          if ($result % 2 == 1) {
                            $result = $result - 1;
                            $result = $result / 5;
                            $result = $result + 2;

                          }elseif ($result % 2 == 0) {
                            $result = $result / 5;
                            $result = $result + 1;
                          }
                          $value = $item->Value;
                          if ($result < $value) { // CEK apakah sudah masuk pada semester "sesuai prasyarat"
                            $message2 = "Anda Belum masuk Semester ".$value.".";
                            break;
                          }else {

                          }


                        }

                        if ($item->Prerequisite_Type_Id == 5) {
                          $total_sks = DB::table('acd_transcript')->where('Student_Id', $Student_Id)->where('Grade_Letter_Id','!=', null)->sum('Sks');
                          $value = $item->Value;

                            if ($total_sks <= $value) {
                                $message2 = "Total SKS yang ditempuh belum mencukupi ".$value." SKS.";
                                break;
                            }
                        }

                        if ($item->Prerequisite_Type_Id == 6) {
                            $value = $item->Value;
                            $gradeletter = 'D';

                            $totalnilai = KrsOnlineData::sumGrade($Student_Id, $gradeletter);
                            if ($totalnilai > $gradeletter) {
                                $message2 = "nilai anda kurang.";
                                break;
                            }
                        }

                        if ($item->Prerequisite_Type_Id == 7) {
                            $bobot = 0;
                            $grade = '';

                            // foreach ($gradedetail as $total) {
                            //     $finalgrade = $bobot + $total->Sks + $total->Weight_Value;
                            // }

                            $totalsks = collect($studentgrade)->count();
                            $ipktranskrip = $querys->ipk;
                            $ipkprerequisite = $item->Value;

                            if ($ipktranskrip <= $ipkprerequisite) {
                                $message2 = "IPK anda Kurang dari ".$ipkprerequisite;
                                break;
                            }
                        }
                    }
                }
                $message = $message2;
                if($message == ""){
                  $acd_course_get = DB::table('acd_course')->where('Course_Id',$Course_Id)->first();
                  if($acd_course_get->Course_Type_Id == 12){
                    // $date = Date('Y-m-d');
                    // DB::table('acd_student_krs')
                    // ->insert(
                    //   ['Student_Id' => $Student_Id,
                    //   'Class_Prog_Id' => $Class_Prog_Id,
                    //   'Term_Year_Id' => $Term_Year_Id,
                    //   'Course_Id' => $Course_Id, 
                    //   'Class_Id' => $Class_Id, 
                    //   'Cost_Item_Id' => 2, 
                    //   'Sks' => $Sks, 
                    //   'Amount' => $Amount, 
                    //   'Created_Date' => $date, 
                    //   'Is_Approved' => 1, 
                    //   'Approved_By' => 'Admin', 
                    //   'Krs_Date' => $date, 'Modified_Date' => $date ]);
                    $message = "berhasil diinput";
                    $success = true;
                  }elseif ($acd_course_get->Course_Type_Id == 13) {
                    // $date = Date('Y-m-d');
                    // DB::table('acd_student_krs')
                    // ->insert(
                    //   ['Student_Id' => $Student_Id,
                    //   'Class_Prog_Id' => $Class_Prog_Id,
                    //   'Term_Year_Id' => $Term_Year_Id,
                    //   'Course_Id' => $Course_Id, 
                    //   'Class_Id' => $Class_Id, 
                    //   'Cost_Item_Id' => 105, 
                    //   'Sks' => $Sks, 
                    //   'Amount' => $Amount, 
                    //   'Created_Date' => $date, 
                    //   'Is_Approved' => 1, 
                    //   'Approved_By' => 'Admin', 
                    //   'Krs_Date' => $date, 'Modified_Date' => $date ]);
                    $message = "berhasil diinput";
                    $success = true;
                  }
                }
            }
          
          $data_now = DB::table('acd_offered_course')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
            ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
            ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            ->where('acd_offered_course.Department_Id', $Department_Id)
            ->where('acd_offered_course.Class_Prog_Id', $Class_Prog_Id)
            ->where('acd_offered_course.Term_Year_Id', $Term_Year_Id)
            ->where('acd_offered_course.Course_Id', $Course_Id)
            ->where('acd_offered_course.Class_Id', $Class_Id)
            //  ->where('cd.Sched_Session_Group_Id', $schedsession)
            //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
            ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
              DB::raw("(SELECT Group_Concat(acd_sched_session.Description SEPARATOR '|') 
                        FROM acd_offered_course_sched 
                        LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                        LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                        WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as jadwal")
              )
            ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
            ->orderBy('acd_course.Course_Name', 'asc')
            ->orderBy('acd_offered_course.Class_Id', 'asc')
            ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
            ->get();

            $data_krs = DB::table('acd_student_krs')->where([
              ['Student_Id',$Student_Id],
              ['Term_Year_Id',$Term_Year_Id],
              ['Class_Prog_Id',$Class_Prog_Id],
            ])->get();

            $num = 0;
            $all_jadwal = [];
            foreach ($data_krs as $key) {
              $get_jdwl = DB::table('acd_offered_course')
                ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
                ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
                ->where('acd_offered_course.Department_Id', $Department_Id)
                ->where('acd_offered_course.Class_Prog_Id', $key->Class_Prog_Id)
                ->where('acd_offered_course.Term_Year_Id', $key->Term_Year_Id)
                ->where('acd_offered_course.Course_Id', $key->Course_Id)
                ->where('acd_offered_course.Class_Id', $key->Class_Id)
                //  ->where('cd.Sched_Session_Group_Id', $schedsession)
                //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
                ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
                  DB::raw("(SELECT Group_Concat(acd_sched_session.Description SEPARATOR '|') 
                            FROM acd_offered_course_sched 
                            LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                            LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                            WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as jadwal")
                  )
                ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
                ->orderBy('acd_course.Course_Name', 'asc')
                ->orderBy('acd_offered_course.Class_Id', 'asc')
                ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
                ->get();
                foreach ($get_jdwl as $key2) {
                  if($key2->jadwal != "" || $key2->jadwal != NULL){
                    $explodes = explode('|',$key2->jadwal);
                    foreach ($explodes as $key3) {
                      $all_jadwal[$num] = $key3;
                      $num++; 
                    }
                  }else{

                  }
                }
            }

            $bentrok = "";
            $code = 0;
            foreach ($data_now as $key) {
              if($key->jadwal != "" || $key->jadwal != NULL){
                $explodes = explode('|',$key->jadwal);
                foreach ($explodes as $key2) {
                  if(in_array($key2,$all_jadwal)){
                    $bentrok = $bentrok. ' ' .$key2;
                    $code = 99;
                  }
                }
              }
            }

            // dd($code);

          $cekkrsall = DB::table('acd_student_krs')->where([['Student_Id',$Student_Id],['Term_Year_Id',$Term_Year_Id]])->get(); //all
          $cekkrs = DB::table('acd_student_krs')->where([['Student_Id',$Student_Id],['Term_Year_Id',$Term_Year_Id],['Is_Approved',1]])->get(); //acc
          $masuk_semester = DB::table('mstr_term_year')
          ->whereBetween('Term_Year_Id', [$student->Entry_Year_Id.$student->Entry_Term_Id, $Term_Year_Id])
          ->get();
          // dd($cekkrsall, $tagihankrs );
          $is_krs = true;
          //20-07-2022
          // if(count($masuk_semester) == 1 || count($masuk_semester) == 2){
          //   $is_krs = true;
          // }else{
          //   if(count($cekkrsall) == 0 && count($tagihankrs) <= 0){
          //     $is_krs = true;
          //   // }elseif(count($cekkrsall) > 0 && count($tagihankrs) > 0){
          //   }elseif(count($cekkrsall) > 0 ){
          //     $is_krs = true;
          //   }else{
          //     $is_krs = false;
          //   }
          // }

          // dd($matakuliahkurikulum);
          // dd($allowedsks,$ambilsks);

          //di hilangkan dulu
          // if(($allowedsks - $ambilsks) < $Sks) {
          //   $message = "SKS tidak Cukup";
          // }
          if($is_krs == false ){
            $message = "Tagihan sudah terbayar";
          }
          elseif ($code == 99) {
            $message = "Jadwal ".$bentrok." Bentrok";
          }
          elseif (count($matakuliahkurikulum)==0) {
            $message = "Matakuliah & Kurikulum Belum Diisi";
          }elseif (count($matakuliahkurikulum2)==0) {
            $message = "Matakuliah & Kurikulum Belum Lengkap";
          }
          // elseif($Amount == null){
          //   $message = "Biaya SKS matakuliah belum diset oleh Keuangan";
          // }          
          elseif($check_krs){
            if($check_krs->Is_Approved === null){
              $message = "Matakuliah Belum Disetujui";
            }elseif ($check_krs->Is_Approved == 1) {
              $message = "Matakuliah Sudah Diambil";
            }
          }
          else {
            // try {
              $acd_course_get = DB::table('acd_course')->where([['Course_Id',$Course_Id],['Department_Id',$Department_Id]])->first();
              if($acd_course_get->Course_Type_Id == 12 || $acd_course_get->Course_Type_Id == 14 || $acd_course_get->Course_Type_Id == 15 || $acd_course_get->Course_Type_Id == 17 || $acd_course_get->Course_Type_Id == 18 || $acd_course_get->Course_Type_Id == 16){
                  $date = Date('Y-m-d');
                  DB::table('acd_student_krs')
                  ->insert(
                    ['Student_Id' => $Student_Id,
                    'Class_Prog_Id' => $Class_Prog_Id,
                    'Term_Year_Id' => $Term_Year_Id,
                    'Course_Id' => $Course_Id, 
                    'Class_Id' => $Class_Id, 
                    'Cost_Item_Id' => 2, 
                    'Sks' => $Sks, 
                    'Amount' => $Amount, 
                    'Created_Date' => $date, 
                    'Is_Approved' => 1, 
                    'Approved_By' => 'Admin', 
                    'Krs_Date' => $date, 'Modified_Date' => $date ]);
                $message = "berhasil diinput";
                $success = true;
              }elseif ($acd_course_get->Course_Type_Id == 13 || $acd_course_get->Course_Type_Id == 19 || $acd_course_get->Course_Type_Id == 22) {
                $date = Date('Y-m-d');
                  DB::table('acd_student_krs')
                  ->insert(
                    ['Student_Id' => $Student_Id,
                    'Class_Prog_Id' => $Class_Prog_Id,
                    'Term_Year_Id' => $Term_Year_Id,
                    'Course_Id' => $Course_Id, 
                    'Class_Id' => $Class_Id, 
                    'Cost_Item_Id' => 105, 
                    'Sks' => $Sks, 
                    'Amount' => $Amount, 
                    'Created_Date' => $date, 
                    'Is_Approved' => 1, 
                    'Approved_By' => 'Admin', 
                    'Krs_Date' => $date, 'Modified_Date' => $date ]);
                $message = "berhasil diinput";
                $success = true;
              }else{
                $message = 'Err. Code : Course_Type';
              }
            // } catch (\Exception $e) {
            //   $message = "Gagal diinput";
            // }
          }
        }
        $error[$i] = $student->Full_Name." :  ".$message;
        $i++;
      }
       return Redirect::back()->withErrors($error)->with( ['success' => $success] );

     }

     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {
       $page = Input::get('page');
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
       $FacultyId = Auth::user()->Faculty_Id;

if($FacultyId==""){
  $data = DB::table('acd_offered_course')
  ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
  ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')

  ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
  ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
  // ->where('acd_student_krs.Is_Approved', 1)
  ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name' , 'mstr_term_year.Term_Year_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
  ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
  ->orderBy('acd_course.Course_Name', 'asc')
  ->orderBy('mstr_class.class_Name', 'asc')->first();

  $krsacc = DB::table('acd_student_krs')
  ->where([['Term_Year_Id',$data->Term_Year_Id],['Class_Prog_Id',$data->Class_Prog_Id],['Class_Id',$data->Class_Id],['Course_Id',$data->Course_Id],['Is_Approved',1]])
  ->count();

} else{
  $data = DB::table('acd_offered_course')
  ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
  ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
  ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
  ->where('mstr_faculty.Faculty_Id', $FacultyId)
  ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
  ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
  // ->where('acd_student_krs.Is_Approved', 1)
  ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name' , 'mstr_term_year.Term_Year_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
  ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
  ->orderBy('acd_course.Course_Name', 'asc')
  ->orderBy('mstr_class.class_Name', 'asc')->first();

  $krsacc = DB::table('acd_student_krs')
  ->where([['Term_Year_Id',$data->Term_Year_Id],['Class_Prog_Id',$data->Class_Prog_Id],['Class_Id',$data->Class_Id],['Course_Id',$data->Course_Id],['Is_Approved',1]])
  ->count();
}

// dd($data);
       $query = DB::table('acd_student_krs')
        ->join('acd_offered_course' ,function ($join)
        {
          $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
          ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
          ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
          ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
        })
        ->join('acd_student' , function ($join)
        {
          $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
          ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
        })
        ->where('acd_offered_course.Offered_Course_id', $id)
        // ->where('acd_student_krs.Is_Approved', 1)
        ->select('acd_student_krs.Krs_Id','acd_student_krs.Is_Approved','acd_student.*')
        ->orderBy('acd_student.Nim')
        ->paginate($rowpage);

      $query->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department, 'currentpage' => $current_page, 'currentsearch' => $current_search, 'currentrowpage' => $current_rowpage ]);
      return view('krs_matakuliah/show')->with('Offered_Course_id', $id)->with('query',$query)->with('data', $data)->with('page', $page)->with('search',$search)->with('rowpage',$rowpage)->with('class_program', $class_program)->with('department', $department)->with('term_year', $term_year)->with('currentsearch', $current_search)->with('currentpage', $current_page)->with('currentrowpage', $current_rowpage)->with('krsacc', $krsacc);

     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */

      public function export($id)
      {
        $type = Input::get('type');
        $FacultyId = Auth::user()->Faculty_Id;

        $data = DB::table('acd_offered_course')
        ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
        ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
        ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
        ->join('mstr_term','mstr_term.Term_Id','=','mstr_term_year.Term_Id')
        ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','mstr_term_year.Year_Id')
        ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
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
        ->where('acd_student_krs.Is_Approved', 1)
        ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', 'mstr_class_program.Class_Program_Name' , 'mstr_department.Department_Name', 'mstr_faculty.Faculty_Id' , 'mstr_term_year.Term_Year_Name','mstr_term.Term_Name','mstr_entry_year.Entry_Year_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
        ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
        ->orderBy('acd_course.Course_Name', 'asc')
        ->orderBy('mstr_class.class_Name', 'asc')
        ->first();

        $jadwal = "";
        $jadwal_q = DB::table('acd_sched_session')->join('mstr_day' , 'acd_sched_session.Day_Id' , '=', 'mstr_day.Day_Id')
        ->join('acd_offered_course_sched', 'acd_sched_session.Sched_Session_Id', '=', 'acd_offered_course_sched.Sched_Session_Id')
        ->join('mstr_room' , 'acd_offered_course_sched.Room_Id', '=', 'mstr_room.Room_Id')
        ->join('acd_offered_course' , 'acd_offered_course_sched.Offered_Course_id', '=', 'acd_offered_course.Offered_Course_id')
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->first();
        if ($jadwal_q) {
          $jadwal = $jadwal_q;
        }

        $dosen = DB::table('emp_employee')->join('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Employee_Id' , '=', 'emp_employee.Employee_Id')
        ->join('acd_offered_course', 'acd_offered_course.Offered_Course_id', '=', 'acd_offered_course_lecturer.Offered_Course_id')
        ->where('acd_offered_course.Offered_Course_id', $id)
        ->orderBy('acd_offered_course_lecturer.Order_Id' , 'asc')
        ->get();

        $grade = DB::table('acd_grade_department')->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_grade_department.Grade_Letter_Id')->where('Department_Id', $data->Department_Id)->get();

        $acd_student_krs = DB::table('acd_student_krs')
                 ->join('acd_offered_course' ,function ($join)
                 {
                   $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                   ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                   ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
                   ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
                 })
                 ->join('acd_student' , function ($join)
                 {
                   $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                   ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
                 })
                 ->where('acd_offered_course.Offered_Course_id', $id)
                 ->where('acd_student_krs.Is_Approved', 1)
                 ->orderby('acd_student.Nim');

        $query = $acd_student_krs
          ->select('acd_student_krs.Krs_Id','acd_student.*')
          ->get();

        $prog_type = $acd_student_krs
          ->join('mstr_department', 'mstr_department.Department_Id' , '=' , 'acd_offered_course.Department_Id')
          ->join('mstr_education_program_type' , 'mstr_education_program_type.Education_Prog_Type_Id', '=' , 'mstr_department.Education_Prog_Type_Id')
          ->select('mstr_education_program_type.*')
          ->first();

        $ttd = "";
        $pejabat = "";
        if ($prog_type->Education_Prog_Type_Code == 2 || $prog_type->Education_Prog_Type_Code == 3) {
          $ttd = "Ketua Program Studi";
          $pejabat = DB::table('acd_functional_position_term_year')->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')->where('Year_Id', $data->Term_Year_Id)->where('acd_functional_position_term_year.Department_Id', $data->Department_Id)->where('Functional_Position_Code', 'KP')
          ->select('emp_employee.Full_Name')->get();
        }else {
          $ttd = "Wakil Dekan I";
          $pejabat = DB::table('acd_functional_position_term_year')->join('emp_employee','emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')->join('emp_functional_position','emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')->where('Year_Id', $data->Term_Year_Id)->where('Faculty_Id', $data->Faculty_Id)->where('Functional_Position_Code', 'WD1')
          ->select('emp_employee.Full_Name')->get();
        }

        $typ = "";
        if ($type == "BeritaAcaraUTS") {
          $typ = "Ujian Tengah Semester";
        }elseif ($type == "BeritaAcaraUAS") {
          $typ = "Ujian Akhir Semester";
        }


        View()->share(['data'=> $data,'query' => $query, 'jadwal' => $jadwal , 'dosen' => $dosen, 'grade' => $grade, 'ttd' => $ttd, 'pejabat' => $pejabat, 'typ' => $typ ]);
        if ($type == "Presensi") {
          $pdf = PDF::loadView('krs_matakuliah/export_presensi');
          return $pdf->stream('Presensi.pdf');
        }elseif ($type == "FormNilai") {
          $pdf = PDF::loadView('krs_matakuliah/export_form_nilai');
          return $pdf->stream('Form_Nilai.pdf');
        }elseif ($type == "BeritaAcaraUTS") {
          $pdf = PDF::loadView('krs_matakuliah/export_berita_acara');
          return $pdf->stream('Berita_Acara_UTS.pdf');
        }elseif ($type == "BeritaAcaraUAS") {
          $pdf = PDF::loadView('krs_matakuliah/export_berita_acara');
          return $pdf->stream('Berita_Acara_UAS.pdf');
        }
        // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);

      }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy($id)
     {
       $selectkhs=DB::table('acd_student_khs')->where('Krs_Id', $id)->Select('Khs_Id')->first();
        $selectweight=DB::table('acd_student_khs')->where('Krs_Id', $id)->Select('Weight_Value')->first();
        // dd($selectweight);

        if($selectweight == null){
          $q=DB::table('acd_student_krs')->where('Krs_Id', $id)->delete();
        }

      //  $selectkhs=DB::table('acd_student_khs')->where('Krs_Id', $id)->Select('Khs_Id')->first();

      //   if($selectkhs==""){
      //     $q=DB::table('acd_student_krs')->where('Krs_Id', $id)->delete();
      //     $q=DB::table('acd_student_khs')->where('Krs_Id', $id)->delete();
      //   }else{
      //     $q=DB::table('acd_transcript')->where('Khs_Id', $selectkhs->Khs_Id)->delete();
      //     $q=DB::table('acd_student_krs')->where('Krs_Id', $id)->delete();
      //     $q=DB::table('acd_student_khs')->where('Krs_Id', $id)->delete();
      //   }

        echo json_encode($q);

     }
 }
