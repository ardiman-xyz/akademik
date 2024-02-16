<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Analytics;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use App\Thesis;
use Auth;
use Excel;
use App\GetDepartment;

class YudisiumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = Input::get('search');
        $department = Input::get('department');
        $entry_year = Input::get('angkatan');
        $term_year = input::get('term_year');
        $rowpage = Input::get('rowpage');
        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;

        $term_year1 = $request->term_year;
        if ($term_year1 == null) {
            $term_year = $request->session()->get('term_year');
        } else {
            $term_year = $request->term_year;
        }

        if ($rowpage == null || $rowpage <= 0) {
            $rowpage = 10;
        }
        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
            ->get();

        $select_department = GetDepartment::getDepartment();

        if ($search == '') {
            if ($term_year == 0) {
                $query = DB::table('acd_yudisium')
                    ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                    ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')

                    ->where('acd_student.Department_Id', $department)
                    ->select(
                        'acd_yudisium.*',
                        'acd_student.*',
                        // DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'),
                    )
                    ->groupBy('acd_yudisium.Student_Id')
                    ->get();
            } else {
                $query = DB::table('acd_yudisium')
                    ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                    ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')

                    ->where('acd_student.Department_Id', $department)
                    ->where('acd_yudisium.Term_Year_Id', $term_year)
                    ->select('acd_yudisium.*', 'acd_student.*', DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                    ->groupBy('acd_yudisium.Student_Id')
                    ->get();
            }
        } else {
            if ($term_year == 0) {
                $query = DB::table('acd_yudisium')
                    ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                    ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')

                    ->where('acd_student.Department_Id', $department)
                    ->where(function ($query) {
                        $search = Input::get('search');
                        $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
                        $query->orwhere('acd_student.Nim', 'LIKE', '%' . $search . '%');
                    })
                    ->select(
                        'acd_yudisium.*',
                        'acd_student.*',
                        // DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'),
                    )
                    ->groupBy('acd_yudisium.Student_Id')
                    ->get();
            } else {
                $query = DB::table('acd_yudisium')
                    ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                    ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')

                    ->where('acd_student.Department_Id', $department)
                    ->where(function ($query) {
                        $search = Input::get('search');
                        $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
                        $query->orwhere('acd_student.Nim', 'LIKE', '%' . $search . '%');
                    })
                    ->where('acd_yudisium.Term_Year_Id', $term_year)
                    ->select(
                        'acd_yudisium.*',
                        'acd_student.*',
                        // DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'),
                    )
                    ->groupBy('acd_yudisium.Student_Id')
                    ->get();
            }
        }

        return view('yudisium/index')
            ->with('query', $query)
            ->with('search', $search)
            ->with('term_year', $term_year)
            ->with('select_term_year', $select_term_year)
            ->with('rowpage', $rowpage)
            ->with('select_department', $select_department)
            ->with('department', $department);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(Request $request)
    {
        $department = Input::get('department');
        $term_year = Input::get('term_year');
        $mahasiswa = Input::get('mahasiswa');
        $std_id = Input::get('std_id');
        $notif = null;
        $FacultyId = Auth::user()->Faculty_Id;

        $notinyudisium = DB::table('acd_yudisium')->select('Student_Id');

        $select_mahasiswa = DB::table('acd_student')
            ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_student.Student_Id')
            ->where('acd_student.Department_Id', $department)
            ->whereNotIn('acd_student.Student_Id', $notinyudisium)
            ->where('acd_student.Entry_Year_Id', 'like', '%' . $request->entry_year . '%')
            ->groupby('acd_student.Student_Id')
            ->get();

        $data = DB::table('acd_thesis')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
            ->where('Department_Id', $department);

        $graduate_predicate = DB::table('mstr_graduate_predicate')->get();

        $count = DB::table('acd_yudisium')->count();
        $max = DB::table('acd_yudisium')->max('Transcript_Num');
        $ex = explode('/', $max);
        $a = '001';
        $nomornya = 0;
        // if($count==0) {
        //     $nomornya= sprintf("%03s", $a);
        // }
        // else {
        //   //$a='1298';
        //      $a= sprintf("%03s", $ex[0]+1);
        // }

        $b = 'TRANSKRIP';
        $c = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        $d = date('Y');
        $no_transkrip = $a . '/' . $b . '/' . $c[date('n')] . '/' . $d;

        $select_entry_year = DB::table('mstr_entry_year')
            ->orderBy('Entry_Year_Code', 'desc')
            ->get();

        return view('yudisium/create', compact('select_mahasiswa', 'select_entry_year'))
            ->with('request', $request)
            ->with('notif', $notif)
            ->with('no_transkrip', $no_transkrip)
            ->with('term_year', $term_year)
            ->with('department', $department)
            ->with('mahasiswa', $mahasiswa)
            ->with('graduate_predicate', $graduate_predicate);
    }

    public function finddata(Request $request)
    {
        //it will get price if its id match with product id
        $p = Thesis::select('acd_thesis.*', DB::raw('(pembimbing1.Full_Name) as pem1'), DB::raw('(pembimbing2.Full_Name) as pem2'), DB::raw('SUM(Sks_total.Sks) as jml_sks'), DB::raw('round((sum(Sks_total.Sks*Sks_total.Weight_Value))/(SUM(Sks_total.Sks)),2) as ipk'))
            ->join('emp_employee as pembimbing1', 'pembimbing1.Employee_Id', '=', 'acd_thesis.Supervisor_1')
            ->join('emp_employee as pembimbing2', 'pembimbing2.Employee_Id', '=', 'acd_thesis.Supervisor_2')
            ->join('acd_transcript as Sks_total', 'Sks_total.Student_Id', '=', 'acd_thesis.Student_Id')
            ->where('acd_thesis.Student_Id', $request->Student_Id)
            ->first();

        return response()->json($p);
    }

    public function findnik(Request $request)
    {
        //it will get price if its id match with product id
        $Employee_Id = Input::get('Employee_Id');
        $a = DB::table('emp_employee')
            ->where('Employee_Id', $Employee_Id)
            ->first();

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
        $mahasiswa = Input::get('mahasiswa');
        $no_ijazah = Input::get('no_ijazah');
        $no_transkrip = Input::get('no_transkrip');
        $no_skpi = Input::get('no_skpi');
        $tgl_kelulusan = Input::get('tgl_kelulusan');
        $term_year = Input::get('term_year');
        $Transcript_Date = Input::get('Transcript_Date');
        $predikat_lulus = Input::get('predikat_lulus');
        $Student_Id = Input::get('Student_Id');
        $notif = null;
        try {
            foreach ($Student_Id as $key) {
                // $dbnomor=DB::table('acd_yudisium')->select('Transcript_Num')
                //   ->where('National_Certificate_Number', $no_ijazah)
                //   ->orwhere('Transcript_Num', $no_transkrip)
                //   ->orwhere('Skpi_Number', $no_skpi)
                //   ->count();

                $mstr_tear_year = DB::table('mstr_term_year')
                    ->orderby('Term_Year_Id', 'desc')
                    ->get();
                // if($dbnomor > 0){
                // return Redirect::back()->withErrors('Ulangi! No. Transkrip Sudah Ada')->with('success', false);
                // }else {
                DB::table('acd_yudisium')->insert([
                    'Student_Id' => $key,
                    'Term_Year_Id' => $term_year == 0 ? $mstr_tear_year[0]->Term_Year_Id : $term_year,
                    'Graduate_Predicate_Id' => $predikat_lulus,
                    'National_Certificate_Number' => $no_ijazah,
                    'Transcript_Num' => $no_transkrip,
                    'Skpi_Number' => $no_skpi,
                    'Yudisium_Date' => $tgl_kelulusan,
                    'Graduate_Date' => $tgl_kelulusan,
                    'Transcript_Date' => $Transcript_Date,
                    'Created_By' => auth()->user()->email, 
                    'Created_Date' => date('Y-m-d H:i:s')
                ]);

                // }
            }
            return Redirect::back()
                ->withErrors('Berhasil Menambah Data Yudisium')
                ->with('success', true);
        } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Menambah Data Yudisium');
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
        $department = Input::get('department');
        $term_year = Input::get('term_year');
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        $pejabat2 = Input::get('pejabat');
        $jabatan2 = Input::get('jabatan2');
        $status = input::get('status');
        $graduate_predikate = Input::get('graduate_predikate');
        $Student_Id = $id;
        $data = DB::table('acd_yudisium')
            ->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('(pembimbing1.Full_Name) as pem1'), DB::raw('(pembimbing2.Full_Name) as pem2'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
            ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
            ->join('emp_employee as pembimbing1', 'pembimbing1.Employee_Id', '=', 'acd_thesis.Supervisor_1')
            ->join('emp_employee as pembimbing2', 'pembimbing2.Employee_Id', '=', 'acd_thesis.Supervisor_2')
            ->where('acd_student.Student_Id', $id)
            ->first();

        $statuslulus = ['0' => 'Tidak Lulus', '1' => 'Lulus'];
        $graduate_predikat = DB::table('mstr_graduate_predicate')->get();
        $jabatan = DB::table('emp_functional_position')->get();
        $datayudisium = DB::table('acd_yudisium')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->where('acd_student.Student_Id', $id)
            ->first();
        $dosen = DB::table('emp_employee')
            ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->leftjoin('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
            ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
            ->where('mstr_department.Department_Id', $department)
            ->get();
        $mhs = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->first();

        $faculty = DB::table('acd_student')
            ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
            ->select('mstr_faculty.*')
            ->where('Student_Id', $id)
            ->first();

        $count = DB::table('acd_yudisium')->count();
        $no_sk = DB::table('acd_yudisium')
            ->select('Sk_Num')
            ->where('Student_Id', $id)
            ->first();
        $max = DB::table('acd_yudisium')->max('Sk_Num');
        $ex = explode('/', $max);

        $a = '001';
        if ($no_sk != null) {
            //$a= sprintf("%03s", $ex[0]);
            $b = 'YUDISIUM';
            $c = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $d = date('Y');
            $sk_yudisium = $a . '/' . $b . '/' . $c[date('n')] . '/' . $d;
        } else {
            $a = sprintf('%03s', $ex[0] + 1);

            $b = 'YUDISIUM';
            $c = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $d = date('Y');
            $sk_yudisium = $a . '/' . $b . '/' . $c[date('n')] . '/' . $d;
        }
        return view('yudisium/show')
            ->with('sk_yudisium', $sk_yudisium)
            ->with('faculty', $faculty)
            ->with('mhs', $mhs)
            ->with('dosen', $dosen)
            ->with('datayudisium', $datayudisium)
            ->with('pejabat2', $pejabat2)
            ->with('jabatan', $jabatan)
            ->with('jabatan2', $jabatan2)
            ->with('graduate_predikate', $graduate_predikate)
            ->with('graduate_predikat', $graduate_predikat)
            ->with('status', $status)
            ->with('statuslulus', $statuslulus)
            ->with('search', $search)
            ->with('rowpage', $rowpage)
            ->with('data', $data)
            ->with('Student_Id', $Student_Id)
            ->with('department', $department)
            ->with('term_year', $term_year);
    }

    public function beritaacara_yudisium($id)
    {
        //
        $department = input::get('department');
        $term_year = Input::get('term_year');
        $pejabat2 = Input::get('pejabat');
        $jabatan2 = Input::get('jabatan2');
        $status = input::get('status');
        $graduate_predikate = Input::get('graduate_predikate');

        $graduate_predikat = DB::table('mstr_graduate_predicate')->get();

        $count = DB::table('acd_yudisium')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->where('acd_student.Student_Id', $id)
            ->select(DB::raw('count(acd_yudisium.Student_Id) as count_mhs'))
            ->first();

        $mhs = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->first();

        $data = DB::table('acd_yudisium')
            ->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
            ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
            ->where('acd_student.Student_Id', $id)
            ->first();
        $faculty = DB::table('acd_student')
            ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
            ->select('mstr_faculty.Faculty_Name')
            ->where('Student_Id', $id)
            ->first();

        $dosen = DB::table('emp_employee')
            ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->leftjoin('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
            ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
            ->where('mstr_department.Department_Id', $department)
            ->get();

        // $statuslulus="";
        // if($data->Is_Graduated==1){
        //   $statuslulus="1";
        //   $statusnya="Lulus";
        // }else{
        //   $statuslulus="0";
        //   $statusnya="Tidak Lulus";
        // }

        $statuslulus = ['0' => 'Tidak Lulus', '1' => 'Lulus'];

        $jabatan = DB::table('emp_functional_position')->get();

        $datayudisium = DB::table('acd_yudisium')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->where('acd_student.Student_Id', $id)
            ->first();

        return view('yudisium/beritaacara_yudisium')
            ->with('status', $status)
            ->with('statuslulus', $statuslulus)
            ->with('datayudisium', $datayudisium)
            ->with('pejabat2', $pejabat2)
            ->with('jabatan', $jabatan)
            ->with('jabatan2', $jabatan2)
            ->with('mhs', $mhs)
            ->with('count_mhs', $count)
            ->with('graduate_predikate', $graduate_predikate)
            ->with('graduate_predikat', $graduate_predikat)
            ->with('dosen', $dosen)
            ->with('data', $data)
            ->with('faculty', $faculty)
            ->with('term_year', $term_year)
            ->with('department', $department);
    }

    public function storeberitaacara_yudisium(Request $request)
    {
        //
        $department = input::get('department');
        $term_year = Input::get('term_year');
        $Student_Id = Input::get('Student_Ids');
        $nim = Input::get('nim');
        $nama = Input::get('nama');
        $status = Input::get('status');
        $predikat = Input::get('graduate_predikate');
        $nomor = input::get('nomor');
        $tgl_yudisium = Input::get('tgl_yudisium');
        $jabatan = Input::get('jabatan2');
        $pejabat = Input::get('pejabat');
        $nik_pjb = Input::get('nik_pjb');
        $nomorurut = Input::get('nomorurut');
        $nomor = Input::get('nomor');
        $nomorurut = Input::get('nomorurut');

        try {
            DB::table('acd_yudisium')
                ->where('Student_Id', $Student_Id)
                ->update(['No_Urut_Yudisium' => $nomorurut, 'Sk_Num' => $nomor, 'Department_Functionary_Nik' => $pejabat, 'Is_Graduated' => $status, 'Graduate_Predicate_Id' => $predikat, 'Sk_Num' => $nomor, 'Yudisium_Date' => $tgl_yudisium, 'Department_Functionary' => $jabatan, 'Department_Functionary_Name' => $pejabat]);

            echo json_encode(['message' => 'Sukses menambah berita acara yudisium']);
        } catch (\Exception $e) {
            echo json_encode(['message' => 'gagal menambah berita acara yudisium']);
        }
    }

    public function skl($id)
    {
        //
        $department = input::get('department');
        $term_year = Input::get('term_year');
        $pejabat = Input::get('pejabat');
        $graduate_predikate = Input::get('graduate_predikate');

        $graduate_predikat = DB::table('mstr_graduate_predicate')->get();

        $count = DB::table('acd_yudisium')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->where('acd_student.Student_Id', $id)
            ->select(DB::raw('count(acd_yudisium.Student_Id) as count_mhs'))
            ->first();

        $mhs = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->first();

        $data = DB::table('acd_yudisium')
            ->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
            ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
            ->where('acd_student.Student_Id', $id)
            ->first();
        $faculty = DB::table('acd_student')
            ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
            ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
            ->select('mstr_faculty.*')
            ->where('Student_Id', $id)
            ->first();

        $dosen = DB::table('emp_employee')
            ->join('acd_department_lecturer', 'acd_department_lecturer.Employee_Id', '=', 'emp_employee.Employee_Id')
            ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_department_lecturer.Department_Id')
            ->where('mstr_department.Faculty_Id', $faculty->Faculty_Id)
            ->get();

        $jabatan = DB::table('emp_functional_position')->get();

        $datayudisium = DB::table('acd_yudisium')
            ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
            ->where('acd_student.Student_Id', $id)
            ->first();

        return view('yudisium/skl')
            ->with('datayudisium', $datayudisium)
            ->with('jabatan', $jabatan)
            ->with('mhs', $mhs)
            ->with('count_mhs', $count)
            ->with('graduate_predikate', $graduate_predikate)
            ->with('graduate_predikat', $graduate_predikat)
            ->with('pejabat', $pejabat)
            ->with('dosen', $dosen)
            ->with('data', $data)
            ->with('faculty', $faculty)
            ->with('term_year', $term_year)
            ->with('department', $department);
    }

    public function store_skl(Request $request)
    {
        //
        $department = input::get('department');
        $term_year = Input::get('term_year');
        $Student_Id = Input::get('Student_Ids');
        $nim = Input::get('nim');
        $jabatanfk = Input::get('jabatanfk');
        $pejabatfk = Input::get('pejabatfk');
        $tgl_lulus = Input::get('tgl_lulus');
        $nomor = Input::get('nomor');

        try {
            DB::table('acd_yudisium')
                ->where('Student_Id', $Student_Id)
                ->update(['Graduate_Date' => $tgl_lulus, 'Faculty_Functionary' => $jabatanfk, 'Faculty_Functionary_Name' => $pejabatfk, 'Faculty_Functionary_Nik' => $pejabatfk]);

            echo json_encode(['message' => 'Sukses menambah SKL']);
        } catch (\Exception $e) {
            echo json_encode(['message' => 'gagal menambah SKL']);
        }
    }

    public function export($id)
    {
        $proses = Input::get('proses');

        switch ($proses) {
            case 1:
                try {
                    $faculty = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
                        ->select('mstr_faculty.Faculty_Name')
                        ->where('Student_Id', $id)
                        ->first();

                    $department_student = DB::table('mstr_department')
                        ->join('acd_student', 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
                        ->where('Student_Id', $id)
                        ->first();
                    $Education_prog_type = DB::table('mstr_education_program_type')
                        ->where('Education_Prog_Type_Id', $department_student->Education_Prog_Type_Id)
                        ->first();

                    $data = DB::table('acd_yudisium')
                        ->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'), DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
                        ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $query = DB::table('acd_transcript')
                        ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'), DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $thesis = DB::table('acd_thesis')
                        ->where('Student_Id', $id)
                        ->first();

                    View()->share(['faculty' => $faculty, 'Education_prog_type' => $Education_prog_type, 'data' => $data, 'query' => $query, 'thesis' => $thesis]);
                    $pdf = PDF::loadView('yudisium/permohonan_yudisium');
                    return $pdf->stream('permohonan_yudisium.pdf');
                } catch (EXCEPTION $e) {
                }
                break;

            case 2:
                try {
                    // $Student_Id = Input::get('Student_Id');
                    // $datas = DB::table('acd_yudisium')->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*',DB::raw('(acd_yudisium.Application_Date) as apldate') ,DB::raw('(pembimbing1.Full_Name) as pem1'),
                    // DB::raw('(pembimbing2.Full_Name) as pem2'),
                    // DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                    // ->join('acd_thesis','acd_thesis.Student_Id','=','acd_yudisium.Student_Id')
                    // ->join('acd_transcript','acd_transcript.Student_Id','=','acd_yudisium.Student_Id')
                    // ->join('acd_student','acd_student.Student_Id','=','acd_thesis.Student_Id')
                    // ->join('emp_employee as pembimbing1','pembimbing1.Employee_Id','=','acd_thesis.Supervisor_1')
                    // ->join('emp_employee as pembimbing2','pembimbing2.Employee_Id','=','acd_thesis.Supervisor_2')
                    // ->where('acd_student.Student_Id', $id)->first();

                    $faculty = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
                        ->select('mstr_faculty.Faculty_Name')
                        ->where('Student_Id', $id)
                        ->first();

                    $data = DB::table('acd_yudisium')
                        ->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*', 'emp_functional_position.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'), DB::raw('(emp_employee.Full_Name) as namadosen'), DB::raw('(emp_employee.Nik) as nikdosen'))
                        ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
                        ->join('emp_employee', 'emp_employee.Employee_Id', '=', 'acd_yudisium.Department_Functionary_Name')
                        ->join('emp_functional_position', 'acd_yudisium.Department_Functionary', '=', 'emp_functional_position.Functional_Position_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $jabatan = DB::table('emp_functional_position')
                        ->join('acd_yudisium', 'acd_yudisium.Department_Functionary', '=', 'emp_functional_position.Functional_Position_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $dat = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student.Class_Prog_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $department_student = DB::table('mstr_department')
                        ->join('acd_student', 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
                        ->where('Student_Id', $id)
                        ->first();
                    $Education_prog_type = DB::table('mstr_education_program_type')
                        ->where('Education_Prog_Type_Id', $department_student->Education_Prog_Type_Id)
                        ->first();

                    $date = date('Y-m-d H:i:s');
                    $term_year1 = DB::table('mstr_term_year')
                        ->where('Start_Date', '<=', $date)
                        ->where('End_Date', '>=', $date)
                        ->select('Term_Year_Id')
                        ->first();

                    $namadekan = DB::table('emp_employee')
                        ->join('acd_functional_position_term_year', 'acd_functional_position_term_year.Employee_Id', '=', 'emp_employee.Employee_Id')
                        ->leftjoin('emp_functional_position', 'emp_functional_position.Functional_Position_Id', '=', 'acd_functional_position_term_year.Functional_Position_Id')
                        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'acd_functional_position_term_year.Faculty_Id')
                        ->leftjoin('mstr_department', 'mstr_department.Faculty_Id', '=', 'mstr_faculty.Faculty_Id')
                        ->leftjoin('acd_student', 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->where('emp_functional_position.Functional_Position_Code', 'KP')
                        ->where('acd_functional_position_term_year.Term_Year_Id', $term_year1->Term_Year_Id)
                        ->select('emp_employee.Full_Name', 'emp_employee.Nik')
                        ->first();

                    $statuslulus = ['0' => 'Tidak Lulus', '1' => 'Lulus'];

                    //return view('yudisium/beritaacara_yudisium')->with('data', $data)->with('Student_Id', $Student_Id);
                    View()->share(['statuslulus' => $statuslulus, 'jabatan' => $jabatan, 'namadekan' => $namadekan, 'faculty' => $faculty, 'data' => $data, 'dat' => $dat, 'Education_prog_type' => $Education_prog_type]);
                    $pdf = PDF::loadView('yudisium/cetak_beritaacara_yudisium');
                    return $pdf->stream('beritaacara_yudisium.pdf');
                } catch (EXCEPTION $e) {
                }
                break;
            case 3:
                try {
                    $data = DB::table('acd_yudisium')
                        ->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();
                    $faculty = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
                        ->select('mstr_faculty.Faculty_Name')
                        ->where('Student_Id', $id)
                        ->first();

                    View()->share(['faculty' => $faculty, 'data' => $data]);
                    $pdf = PDF::loadView('yudisium/bebas_pinjaman');
                    return $pdf->stream('bebas_pinjaman.pdf');
                } catch (EXCEPTION $e) {
                }
                break;
            case 4:
                try {
                    $data = DB::table('acd_yudisium')
                        ->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    //$notingrad = DB::table('acd_graduation_reg')->select('Student_Id');

                    $enddateyudisium = DB::table('acd_graduation_period')
                        ->select('Period_Name', 'Graduation_Date')
                        ->where('End_Date_Yudisium', '>=', $data->Graduate_Date)
                        ->first();

                    $faculty = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
                        ->select('mstr_faculty.Faculty_Name')
                        ->where('Student_Id', $id)
                        ->first();

                    $student = DB::table('acd_student')
                        ->where('Student_Id', $id)
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->select('mstr_department.Department_Name', 'acd_student.*', DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
                        ->first();

                    View()->share(['faculty' => $faculty, 'data' => $data, 'student' => $student, 'enddateyudisium' => $enddateyudisium]);

                    $pdf = PDF::loadView('yudisium/pengantar_pembayaran_wisuda');
                    return $pdf->stream('pengantar_pembayaran_wisuda.pdf');
                } catch (EXCEPTION $e) {
                }
                break;

            case 5:
                try {
                    $faculty = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
                        ->select('mstr_faculty.Faculty_Name')
                        ->where('Student_Id', $id)
                        ->first();

                    $dat = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student.Class_Prog_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $data = DB::table('acd_yudisium')
                        ->select('acd_yudisium.*', 'acd_thesis.*', 'emp_employee.*', 'acd_student.*', 'emp_functional_position.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
                        ->join('emp_functional_position', 'acd_yudisium.Faculty_Functionary', '=', 'emp_functional_position.Functional_Position_Id')
                        ->join('emp_employee', 'acd_yudisium.Faculty_Functionary_Name', '=', 'emp_employee.Employee_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $namadekan = DB::table('emp_employee')
                        ->join('acd_yudisium', 'acd_yudisium.Faculty_Functionary_Name', '=', 'emp_employee.Employee_Id')
                        ->select('emp_employee.Full_Name', 'emp_employee.Nik')
                        ->first();

                    $jabatan = DB::table('emp_functional_position')
                        ->join('acd_yudisium', 'acd_yudisium.Faculty_Functionary', '=', 'emp_functional_position.Functional_Position_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    View()->share(['data' => $data, 'dat' => $dat, 'faculty' => $faculty, 'namadekan' => $namadekan, 'jabatan' => $jabatan]);
                    $pdf = PDF::loadView('yudisium/skl_cetak');
                    return $pdf->stream('skl.pdf');
                } catch (EXCEPTION $e) {
                }
                break;
            case 6:
                try {
                    $faculty = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
                        ->where('Student_Id', $id)
                        ->first();

                    $student = DB::table('acd_student')
                        ->where('Student_Id', $id)
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->select('mstr_department.Department_Name', 'acd_student.*', DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
                        ->first();

                    $program_type = DB::table('acd_student')
                        ->where('acd_student.Student_Id', $id)
                        ->join('mstr_department', 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
                        ->leftjoin('mstr_education_program_type', 'mstr_department.Education_Prog_Type_Id', '=', 'mstr_education_program_type.Education_Prog_Type_Id')
                        ->select('mstr_education_program_type.Program_Name')
                        ->first();

                    $data = DB::table('acd_transcript')
                        ->select('acd_student.Full_Name', 'acd_transcript.*', DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $dataisi = DB::table('acd_transcript')
                        ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_transcript.Course_Id')
                        ->join('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id', '=', 'acd_transcript.Grade_Letter_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
                        ->select('acd_student.Full_Name', 'acd_transcript.*', 'acd_grade_letter.Grade_Letter', 'acd_course.*', DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
                        ->where('acd_transcript.Student_Id', $id)
                        ->get();

                    $query = DB::table('acd_transcript')
                        ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'), DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $predikat = DB::table('acd_yudisium')
                        ->join('mstr_graduate_predicate', 'mstr_graduate_predicate.Graduate_Predicate_Id', '=', 'acd_yudisium.Graduate_Predicate_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $dosen = DB::table('acd_yudisium')
                        ->select(DB::raw('emp_employee.Full_Name as namadosen'), DB::raw('emp_employee.Nik as nik'))
                        ->join('emp_employee', 'emp_employee.Employee_Id', 'acd_yudisium.Department_Functionary_Name')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $dataNo = DB::table('acd_yudisium')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $thesis_title = DB::table('acd_thesis')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->select('acd_thesis.Thesis_Title')
                        ->first();

                    $jabatan = DB::table('emp_functional_position')
                        ->join('acd_yudisium', 'acd_yudisium.Department_Functionary', '=', 'emp_functional_position.Functional_Position_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    $graduate_predikat = DB::table('mstr_graduate_predicate')->get();

                    $data1 = DB::table('acd_yudisium')
                        ->select('acd_yudisium.*', 'acd_thesis.*', 'emp_employee.*', 'acd_student.*', 'emp_functional_position.*', DB::raw('(acd_yudisium.Application_Date) as apldate'), DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                        ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
                        ->join('emp_functional_position', 'acd_yudisium.Faculty_Functionary', '=', 'emp_functional_position.Functional_Position_Id')
                        ->join('emp_employee', 'acd_yudisium.Faculty_Functionary_Name', '=', 'emp_employee.Employee_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    View()->share(['dataNo' => $dataNo, 'data1' => $data1, 'dosen' => $dosen, 'jabatan' => $jabatan, 'thesis_title' => $thesis_title, 'faculty' => $faculty, 'student' => $student, 'program_type' => $program_type, 'data' => $data, 'dataisi' => $dataisi, 'query_' => $query, 'predikat' => $predikat]);

                    $pdf = PDF::loadView('yudisium/transkrip');
                    return $pdf->stream('transkrip.pdf');
                } catch (EXCEPTION $e) {
                }
                break;

            case 7:
                try {
                    $faculty = DB::table('acd_student')
                        ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_student.Department_Id')
                        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', 'mstr_department.Faculty_Id')
                        ->select('mstr_faculty.Faculty_Name')
                        ->where('Student_Id', $id)
                        ->first();

                    $data = DB::table('acd_yudisium')
                        ->select('acd_yudisium.*', 'acd_thesis.*', 'acd_student.*')
                        ->join('acd_thesis', 'acd_thesis.Student_Id', '=', 'acd_yudisium.Student_Id')
                        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_thesis.Student_Id')
                        ->where('acd_student.Student_Id', $id)
                        ->first();

                    View()->share(['data' => $data, 'faculty' => $faculty]);

                    $pdf = PDF::loadView('yudisium/bukti_penyerahanta');
                    return $pdf->stream('bukti_penyerahanta.pdf');
                } catch (EXCEPTION $e) {
                }
                break;

            default:
                try {
                } catch (EXCEPTION $e) {
                    return view('yudisium/show');
                }
                break;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $term_year = Input::get('term_year');
        $department = Input::get('department');
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');
        $predikat_lulus = Input::get('predikat_lulus');

        $data_edit = DB::table('acd_yudisium as a')
            ->join('acd_student as b', 'a.Student_Id', '=', 'b.Student_Id')
            ->where('Yudisium_Id', $id)
            ->first();

        $graduate_predicate = DB::table('mstr_graduate_predicate')->get();

        return view('yudisium/edit')
            ->with('term_year', $term_year)
            ->with('department', $department)
            ->with('data_edit', $data_edit)
            ->with('predikat_lulus', $predikat_lulus)
            ->with('graduate_predicate', $graduate_predicate)
            ->with('search', $search)
            ->with('page', $page)
            ->with('rowpage', $rowpage);
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
        $mahasiswa = Input::get('mahasiswa');
        $no_ijazah = Input::get('no_ijazah');
        $no_transkrip = Input::get('no_transkrip');
        $no_skpi = Input::get('no_skpi');
        $tgl_kelulusan = Input::get('tgl_kelulusan');
        $term_year = Input::get('term_year');
        $Transcript_Date = Input::get('Transcript_Date');
        $predikat_lulus = Input::get('predikat_lulus');
        $notif = null;
        // try {
        $dbnomor = DB::table('acd_yudisium')
            ->select('Transcript_Num')
            ->where([['National_Certificate_Number', $no_ijazah], ['Yudisium_Id', '!=', $id]])
            ->orwhere([['Transcript_Num', $no_transkrip], ['Yudisium_Id', '!=', $id]])
            ->orwhere([['Skpi_Number', $no_skpi], ['Yudisium_Id', '!=', $id]])
            ->count();
        if ($dbnomor > 0) {
            return Redirect::back()
                ->withErrors('Ulangi! No. Transkrip Sudah Ada')
                ->with('success', false);
        } else {
            DB::table('acd_yudisium')
                ->where('Yudisium_Id', $id)
                ->update([
                    'Graduate_Predicate_Id' => $predikat_lulus,
                    'National_Certificate_Number' => $no_ijazah,
                    'Transcript_Num' => $no_transkrip,
                    'Skpi_Number' => $no_skpi,
                    'Yudisium_Date' => $tgl_kelulusan,
                    'Graduate_Date' => $tgl_kelulusan,
                    'Transcript_Date' => $Transcript_Date,
                ]);

            return Redirect::back()
                ->withErrors('Berhasil Menambah Data Yudisium')
                ->with('success', true);
        }
        // } catch (\Exception $e) {
        //   return Redirect::back()->withErrors('Gagal Menambah Data Yudisium');
        // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $q = DB::table('acd_yudisium')
                ->where('Yudisium_Id', $id)
                ->delete();
            return response()->json(
                [
                    'success' => 'true',
                    'message' => 'Data Dihapus',
                ],
                200,
            );
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'success' => 'false',
                    'message' => $th,
                ],
                200,
            );
        }
    }

    public function berkasyudisium()
    {
        return view('yudisium/berkasyudisium');
    }
    public function masterberkasyudisium()
    {
        return view('yudisium/masterberkasyudisium');
    }

    public function exportYudisium(Request $request)
    {
        if($request->department == ''){
            return Redirect::back()->withErrors('Pilih Prodi Terlebih Dahulu')->with('success', false);
        }
        $term_year1 = $request->term_year;
        if ($term_year1 == null) {
            $term_year = $request->session()->get('term_year');
        } else {
            $term_year = $request->term_year;
        }
        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('Term_Year_Id', 'desc')
            ->get();

        $yudisium = DB::table('acd_yudisium')
        ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_yudisium.Student_Id')
        ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_yudisium.Student_Id')
        ->leftjoin('mstr_graduate_predicate', 'mstr_graduate_predicate.Graduate_Predicate_Id', '=', 'acd_yudisium.Graduate_Predicate_Id')
        ->where('acd_student.Department_Id', $request->department)
        ->where('acd_yudisium.Term_Year_Id', 'like', '%' . $request->term_year . '%')
        ->select('acd_yudisium.*', 'acd_student.Nim','acd_student.Full_Name', 'mstr_graduate_predicate.*')
        ->groupBy('acd_yudisium.Student_Id')
        ->orderby('acd_student.Nim', 'asc')
        ->get();

        Excel::create('Data Mahasiswa Yudisium', function ($excel) use ($yudisium) {
            $data = [
                [
                    'Nim' => '',
                    'Nama' => '',
                    'Tanggal Lulus' => '',
                    'Tanggal Transkrip' => '',
                    'No Ijazah' => '',
                    'No Transkrip' => '',
                    'No Skpi' => '',
                    'Predikat' => '',
                ],
            ];
            $i = 0;
            foreach ($yudisium as $key) {
                $data[] = [
                    'Nim' => $key->Nim,
                    'Nama' => $key->Full_Name,
                    'Tanggal Lulus' => $key->Graduate_Date,
                    'Tanggal Transkrip' => $key->Transcript_Date,
                    'No Ijazah' => $key->National_Certificate_Number,
                    'No Transkrip' => $key->Transcript_Num,
                    'No Skpi' => $key->Skpi_Number,
                    'Predikat' => $key->Predicate_Name,
                ];
                $i++;
            }

            $excel->sheet('Data Mahasiswa Yudisium', function ($sheet) use ($data, $yudisium) {
                $sheet->fromArray($data, null, 'A1');
            });
        })->export('xlsx');
    }

    public function import(Request $request){
        $department =$request->department;
        $mstr_department = DB::table('mstr_department')->where('Department_Id', $department)->get();
        return view('yudisium.import')->with('request',$request)->with('mstr_department', $mstr_department)->with('department', $department);
    }

    public function storeImport(Request $request){
        $data_yudisium = DB::table('acd_yudisium')
        ->join('acd_student','acd_yudisium.Student_Id','=','acd_student.Student_Id')
        ->where('acd_student.Department_Id', $request->department)
        ->select('acd_student.Nim')
        ->get()
        ->toarray();
        $std_nim = [];
        $i = 0;
        foreach ($data_yudisium as $item) {
            $std_nim[$i] = $item->Nim;
            $i++;
        }
        if ($request->hasFile('import_file')) {
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) use ($request, $std_nim) {
                foreach ($reader->toArray() as $row) {
                    $nim = (int)$row['nim'];
                    $predikat = (int)$row['predikat'];
                    $stat = 0;
                    $data = [];
                    if($row['nim']==''){
                        continue;
                    }
                    if (in_array(($nim), $std_nim) == false) {
                        $check_dept = DB::table('mstr_department')->where('Department_Id', $request->department)->first();
                        $check_std = DB::table('acd_student')->where('Nim', $nim)->first();
                        $predicate = DB::table('mstr_graduate_predicate')->where('Graduate_Predicate_Code', $predikat)->first();
                        if ($request->department == $check_dept->Department_Id) {
                            $data['Student_Id'] = $check_std->Student_Id;
                            $data['Term_Year_Id'] = $request->term_year;
                            $data['Graduate_Predicate_Id'] = $predicate->Graduate_Predicate_Id;
                            $data['National_Certificate_Number'] = $row['no_ijazah'];
                            $data['Transcript_Num'] = $row['no_transkrip'];
                            $data['Skpi_Number'] = $row['no_skpi'];
                            $data['Yudisium_Date'] = $row['tanggal_lulus']->format('Y-m-d');
                            $data['Graduate_Date'] = $row['tanggal_lulus']->format('Y-m-d');
                            $data['Transcript_Date'] = $row['tanggal_transkrip']->format('Y-m-d');;
                            $data['Created_By'] = auth()->user()->email;
                            $data['Created_Date'] = date('Y-m-d H:i:s');

                            DB::table('acd_yudisium')->insert($data);
                        }
                    } else {
                        continue;
                    }
                }
            });
        }

        return redirect()->to('/proses/yudisium?department=' . $request->department.'&term_year='.$request->term_year)->withErrors('Berhasil Memasukkan Data Ke Database');
    }
}
