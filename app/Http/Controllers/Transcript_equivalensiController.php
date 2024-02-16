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
use App\GetDepartment;

class Transcript_equivalensiController extends Controller
{
    public function __construct()
    {
        // $this->middleware('access:CanView', ['only' => ['index', 'show']]);
        // $this->middleware('access:CanAdd', ['except' => ['index', 'show', 'edit', 'update', 'destroy']]);
        // $this->middleware('access:CanEdit', ['except' => ['index', 'create', 'store', 'show', 'destroy']]);
        // $this->middleware('access:CanDelete', ['except' => ['index', 'create', 'store', 'show', 'edit', 'update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'rowpage' => 'numeric|nullable',
        ]);
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        if ($rowpage == null || $rowpage <= 0) {
            $rowpage = 10;
        }
        $department = Input::get('department');
        $entry_year = Input::get('entry_year');
        $student = Input::get('student');
        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;
        $select_entry_year = DB::table('mstr_entry_year')
            ->orderBy('mstr_entry_year.Entry_Year_Name', 'desc')
            ->get();

        $select_department = GetDepartment::getDepartment();

        $data = DB::table('acd_student')
            ->where([
                ['acd_student.Status_Id', 11],
                ['Department_Id', $department],
                ['Entry_Year_Id', $entry_year],
                [
                    function ($query) {
                        $search = Input::get('search');
                        $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
                        $query->orwhere('Nim', 'LIKE', '%' . $search . '%');
                    },
                ],
            ])
            ->orwhere([
                ['acd_student.Status_Id', 12],
                ['Department_Id', $department],
                ['Entry_Year_Id', $entry_year],
                [
                    function ($query) {
                        $search = Input::get('search');
                        $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
                        $query->orwhere('Nim', 'LIKE', '%' . $search . '%');
                    },
                ],
            ])
            ->select('acd_student.*', DB::raw('(SELECT SUM(acd_transcript.Sks) as Jml_sks FROM acd_transcript WHERE acd_transcript.Student_Id = acd_student.Student_Id AND acd_transcript.Term_Year_Id is null AND acd_transcript.Is_Transfer = 1 AND acd_transcript.is_Use = 1) as Jml_Sks'), DB::raw('(SELECT COUNT(Student_Id) as Jml_mk FROM acd_transcript WHERE acd_transcript.Student_Id = acd_student.Student_Id AND acd_transcript.Term_Year_Id is null AND acd_transcript.Is_Transfer = 1 AND acd_transcript.is_Use = 1) as Jml_mk'))
            ->leftjoin('acd_transcript', 'acd_student.Student_Id', '=', 'acd_transcript.Student_Id')
            ->groupby('acd_student.Student_Id')
            ->paginate($rowpage);

        $data->appends(['search' => $search, 'rowpage' => $rowpage, 'term_year' => $entry_year, 'department' => $department]);
        return view('transcript_equivalensi/index')
            ->with('query', $data)
            ->with('student', $student)
            ->with('search', $search)
            ->with('rowpage', $rowpage)
            ->with('select_department', $select_department)
            ->with('department', $department)
            ->with('select_entry_year', $select_entry_year)
            ->with('entry_year', $entry_year);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Student_Id = Input::get('student_id');
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        $department = Input::get('department');
        $entry_year = Input::get('entry_year');
        $page = Input::get('page');

        $cek_transcript = DB::table('acd_transcript')
            ->where('Student_Id', $Student_Id)
            ->select('Course_Id');
        $Student = DB::table('acd_student')
            ->where('Student_Id', $Student_Id)
            ->first();
        $cur_student = DB::table('acd_curriculum_entry_year')
            ->where([['Term_Year_Id', $Student->Entry_Year_Id . $Student->Entry_Term_Id], ['Department_Id', $Student->Department_Id], ['Class_Prog_Id', $Student->Class_Prog_Id], ['Entry_Year_Id', $Student->Entry_Year_Id]])
            ->first();
        // dd($cur_student->Curriculum_Id);
        $select_course = DB::table('acd_course')
            ->join('acd_course_curriculum', function ($join) {
                $join->on('acd_course.Course_Id', '=', 'acd_course_curriculum.Course_Id')->on('acd_course.Department_Id', '=', 'acd_course_curriculum.Department_Id');
            })
            ->WhereNotIn('acd_course.Course_Id', $cek_transcript)
            ->where('acd_course_curriculum.Department_Id', $department)
            // ->where('acd_course_curriculum.Curriculum_Id', $cur_student->Curriculum_Id)
            // ->where('acd_course_curriculum.Class_Prog_Id', $cur_student->Class_Prog_Id)
            ->get();
        // dd($select_course);
        $select_grade_letter = DB::table('acd_grade_letter')
            ->join('acd_grade_department', 'acd_grade_department.Grade_Letter_Id', '=', 'acd_grade_letter.Grade_Letter_Id')
            ->where('acd_grade_department.Department_Id', $department)
            ->groupby('acd_grade_letter.Grade_Letter')
            ->orderby('acd_grade_letter.Grade_Letter', 'asc')
            ->get();

        if (Count($select_course) == 0) {
            Alert::error('mata kuliah anda pada departemen ini sudah penuh', 'Mata Kuliah Penuh')
                ->persistent('Close')
                ->autoclose(50000);

            //$previousUrl = app('url')->previous();
            return redirect()->to('proses/transcript_equivalensi/' . $Student_Id . '?department=' . $department . '&entry_year=' . $entry_year . '&page=' . $page . '&rowpage=' . $rowpage . '&search=' . $search);
        } else {
            return view('transcript_equivalensi/create')
                ->with('data', $Student)
                ->with('Student_Id', $Student_Id)
                ->with('select_course', $select_course)
                ->with('select_grade_letter', $select_grade_letter)
                ->with('search', $search)
                ->with('rowpage', $rowpage)
                ->with('page', $page)
                ->with('department', $department)
                ->with('entry_year', $Student->Entry_Year_Id);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'Course_Code_Transfer' => 'required',
            'Course_Name_Transfer' => 'required',
            'Sks_Transfer' => 'required|numeric',
            'Grade_Letter_Transfer' => 'required',
            'Course_Id' => 'required',
            'Sks' => 'required|numeric',
            'Grade_Letter_Id' => 'required',
            //'Entry_Year_Id'=>'required',
        ]);

        $department = Input::get('department');
        $Student_Id = Input::get('Student_Id');
        $entry_year = Input::get('entry_year');
        $Course_Code_Transfer = Input::get('Course_Code_Transfer');
        $Course_Name_Transfer = Input::get('Course_Name_Transfer');
        $Sks_Transfer = Input::get('Sks_Transfer');
        $Grade_Letter_Transfer = Input::get('Grade_Letter_Transfer');
        $Course_Id = Input::get('Course_Id');
        $Sks = Input::get('Sks');
        $Grade_Letter_Id = Input::get('Grade_Letter_Id');
        //$department_id_get=DB::table('acd_transcript')->where('Transcript_Id',$Department_Id)->first();
        //$weight_value=DB::table('acd_grade_department')->where('Department_Id',$Department_Id)->select('Department_Id')->first();
        $acd_grade_department = DB::table('acd_grade_department')
            ->select('Weight_Value')
            ->where('Department_Id', $department)
            ->where('Grade_Letter_Id', $Grade_Letter_Id)
            ->first();
        $weight_value = $acd_grade_department->Weight_Value;

        /*try {
                $u =  DB::table('acd_transcript')
                ->insert(['Student_Id' => $Student_Id, 'Course_Code_Transfer' => $Course_Code_Transfer]);
                  return Redirect::back()->withErrors('Berhasil Menambah Data Mahasiswa');
              } catch (\Exception $e) {
                return Redirect::back()->withErrors('Gagal Menambah Data Mahasiswa');
              }*/
        // try {
            $cek_krs = DB::table('acd_student_krs')
                ->where([['Student_Id', $Student_Id], ['Course_Id', $Course_Id]])
                ->orderBy('Term_Year_Id', 'desc')
                ->get();
            $data_student = DB::table('acd_student')
                ->where('Student_Id', $Student_Id)
                ->first();
            if ($cek_krs->count() > 0) {
                $cek_khs = DB::table('acd_student_khs')
                    ->where([['Student_Id', $Student_Id], ['Krs_Id', $cek_krs[0]->Krs_Id]])
                    ->get();
                if ($cek_khs->count() > 0) {
                    $u = DB::table('acd_transcript')->insert([
                        'Term_Year_Id' => 0,
                        'Weight_Value' => $weight_value,
                        'Khs_Id' => $cek_khs[0]->Khs_Id,
                        'is_Use' => 1,
                        'Is_Transfer' => 1,
                        'Student_Id' => $Student_Id,
                        'Sks' => $Sks,
                        'Course_Id' => $Course_Id,
                        'Sks_Transfer' => $Sks_Transfer,
                        'Grade_Letter_Id' => $Grade_Letter_Id,
                        'Grade_Letter_Transfer' => $Grade_Letter_Transfer,
                        'Course_Code_Transfer' => $Course_Code_Transfer,
                        'Course_Name_Transfer' => $Course_Name_Transfer,
                        'Modified_By' => auth()->user()->email,
                        'Modified_Date' => date('Y-m-d H:i:s'),
                    ]);
                } else {
                    $khs = DB::table('acd_student_khs')->insertgetId([
                        'Student_Id' => $Student_Id,
                        'Krs_Id' => $cek_krs[0]->Krs_Id,
                        'Grade_Letter_Id' => $Grade_Letter_Id,
                        'Sks' => $Sks,
                        'Weight_Value' => $weight_value,
                        'Is_For_Transkrip' => 1,
                        'Bnk_Value' => $Sks * $weight_value,
                        'Is_Published' => 1,
                        'Created_By' => auth()->user()->email,
                        'Created_Date' => date('Y-m-d H:i:s'),
                    ]);
                    $khs_Id = DB::getPdo()->lastInsertId();
                    $u = DB::table('acd_transcript')->insert([
                        'Weight_Value' => $weight_value,
                        'Khs_Id' => $khs_Id,
                        'is_Use' => 1,
                        'Is_Transfer' => 1,
                        'Student_Id' => $Student_Id,
                        'Sks' => $Sks,
                        'Course_Id' => $Course_Id,
                        'Term_Year_Id' => 0,
                        'Sks_Transfer' => $Sks_Transfer,
                        'Grade_Letter_Id' => $Grade_Letter_Id,
                        'Grade_Letter_Transfer' => $Grade_Letter_Transfer,
                        'Course_Code_Transfer' => $Course_Code_Transfer,
                        'Course_Name_Transfer' => $Course_Name_Transfer,
                        'Modified_By' => auth()->user()->email,
                        'Modified_Date' => date('Y-m-d H:i:s'),
                    ]);
                }
            } else {
                $krs = DB::table('acd_student_krs')->insertgetId([
                    'Student_Id' => $Student_Id,
                    'Class_Prog_Id' => $data_student->Class_Prog_Id,
                    'Term_Year_Id' => 0,
                    'Course_Id' => $Course_Id,
                    'Class_Id' => 1,
                    'Amount' => 0,
                    'Sks' => $Sks,
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s'),
                ]);
                $krs_Id = DB::getPdo()->lastInsertId();
                $khs = DB::table('acd_student_khs')->insertgetId([
                    'Student_Id' => $Student_Id,
                    'Krs_Id' => $krs_Id,
                    'Grade_Letter_Id' => $Grade_Letter_Id,
                    'Sks' => $Sks,
                    'Weight_Value' => $weight_value,
                    'Is_For_Transkrip' => 1,
                    'Bnk_Value' => $Sks * $weight_value,
                    'Is_Published' => 1,
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s'),
                ]);
                $khs_Id = DB::getPdo()->lastInsertId();

                $transkrip = DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)', [$khs_Id, '']);
                $u = DB::table('acd_transcript')
                    ->where('Khs_Id', $khs_Id)
                    ->update([
                        'Term_Year_Id' => 0,
                        'Sks_Transfer' => $Sks_Transfer,
                        'Grade_Letter_Transfer' => $Grade_Letter_Transfer,
                        'Course_Code_Transfer' => $Course_Code_Transfer,
                        'Course_Name_Transfer' => $Course_Name_Transfer,
                        'is_Use' => 1,
                        'Is_Transfer' => 1,
                        'Modified_By' => auth()->user()->email,
                        'Modified_Date' => date('Y-m-d H:i:s'),
                    ]);
            }
            // return redirect()->to('proses/transcript_equivalensi/'.$Student_Id.'?department='.$department.'&entry_year='.$entry_year)->with('success', true);
            return Redirect::back()
                ->withErrors('Berhasil Menyimpan Perubahan')
                ->with('success', true);
            // return Redirect::back()->withErrors('Berhasil Menambah Mahasiswa');
        // } catch (\Exception $e) {
        //     return Redirect::back()
        //         ->withErrors('Gagal Menyimpan Perubahan')
        //         ->with('success', false);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        $department = Input::get('department');
        $entry_year = Input::get('entry_year');

        $page = Input::get('page');
        if ($rowpage == null) {
            $rowpage = 10;
        }
        $data = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->first();

        $query = DB::table('acd_transcript')
            ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_transcript.Course_Id')
            ->join('acd_grade_letter', 'acd_transcript.Grade_Letter_Id', '=', 'acd_grade_letter.Grade_Letter_Id')
            ->where('acd_transcript.Student_Id', $id)
            ->where('acd_transcript.Is_Transfer', 1)
            ->where('acd_transcript.is_Use', 1)
            ->select('acd_transcript.*', 'acd_course.*', 'acd_grade_letter.Grade_Letter')
            ->groupBy('acd_transcript.Transcript_Id')
            ->orderBy('acd_transcript.Transcript_Id')
            ->paginate($rowpage);
        // ->get();
        // dd($query);

        $query->appends(['search' => $search, 'rowpage' => $rowpage, 'entry_year' => $entry_year, 'department' => $department]);

        if (Count($query) == 0) {
            Alert::error('Apakah Ingin Menambah Nilai', 'Data Kosong')
                ->persistent('Close')
                ->autoclose(50000);

            $previousUrl = app('url')->previous();
            return redirect()->to('proses/transcript_equivalensi?department=' . $department . '&entry_year=' . $entry_year . '&search=' . $search . '&' . http_build_query(['student' => $id]));
        } else {
            return view('transcript_equivalensi/show')
                ->with('query', $query)
                ->with('data', $data)
                ->with('Student_Id', $id)
                ->with('search', $search)
                ->with('rowpage', $rowpage)
                ->with('department', $department)
                ->with('entry_year', $entry_year)
                ->with('page', $page);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$Student_Id = Input::get('student_id');
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        $department = Input::get('department');
        $entry_year = Input::get('entry_year');
        $page = Input::get('page');
        $transcript = DB::table('acd_transcript')
            ->where('Transcript_Id', $id)
            ->first();

        $dataa = DB::table('acd_transcript')
            ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_transcript.Course_Id')
            ->where('Transcript_Id', $id)
            ->get();
        $cek_transcript = DB::table('acd_transcript')
            ->where('Student_Id', $transcript->Student_Id)
            ->select('Course_Id');

        /*$select_course = DB::table('acd_course')
      ->join('acd_transcript','acd_transcript.Course_Id','=','acd_course.Course_Id')
      ->orWhereNotIn('acd_transcript.Course_Id',$cek_transcript)
      //->WhereNotIn('Course_Id', $cek_transcript)
      ->where('Department_Id', $department)->get();*/

        $select_course_ = DB::table('acd_course')
            ->join('acd_transcript', 'acd_transcript.Course_Id', '=', 'acd_course.Course_Id')
            ->where('acd_transcript.Transcript_Id', $id)
            ->get();
        $select_course = DB::table('acd_course')
            ->WhereNotIn('Course_Id', $cek_transcript)
            ->where('Department_Id', $department)
            ->get();
        //$select_course = DB::table('acd_course')->get();
        //$select_course = DB::table('acd_transcript')->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')->where('acd_transcript.Transcript_Id',$id)->select('acd_course.Course_Id');
        $select_grade_letter = DB::table('acd_grade_letter')
            ->join('acd_grade_department', 'acd_grade_department.Grade_Letter_Id', '=', 'acd_grade_letter.Grade_Letter_Id')
            ->where('acd_grade_department.Department_Id', $department)
            ->get();
        $Student = DB::table('acd_student')
            ->where('Student_Id', $transcript->Student_Id)
            ->first();

        return view('transcript_equivalensi/edit')
            ->with('query_edit', $dataa)
            ->with('data', $Student)
            ->with('Student_Id', $transcript->Student_Id)
            ->with('select_course_', $select_course_)
            ->with('select_course', $select_course)
            ->with('select_grade_letter', $select_grade_letter)
            ->with('search', $search)
            ->with('rowpage', $rowpage)
            ->with('page', $page)
            ->with('department', $department)
            ->with('entry_year', $entry_year);
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
        $Department_Id = Input::get('department');
        $Entry_Year_Id = Input::get('Entry_Year');
        $Student_Id = Input::get('Student_Id');
        $Course_Code_Transfer = Input::get('Course_Code_Transfer');
        $Course_Name_Transfer = Input::get('Course_Name_Transfer');
        $Sks_Transfer = Input::get('Sks_Transfer');
        $Grade_Letter_Transfer = Input::get('Grade_Letter_Transfer');
        $Course_Id = Input::get('Course_Id');
        $Sks = Input::get('Sks');
        $Grade_Letter_Id = Input::get('Grade_Letter_Id');
        $acd_grade_department = DB::table('acd_grade_department')
            ->select('Weight_Value')
            ->where('Department_Id', $Department_Id)
            ->where('Grade_Letter_Id', $Grade_Letter_Id)
            ->first();
        $weight_value = $acd_grade_department->Weight_Value;

        try {
            //cek data
            $cek_krs = DB::table('acd_student_krs')
                ->where([['Student_Id', $Student_Id], ['Course_Id', $Course_Id]])
                ->orderBy('Term_Year_Id', 'desc')
                ->get();
            $data_student = DB::table('acd_student')
                ->where('Student_Id', $Student_Id)
                ->first();
            if ($cek_krs->count() > 0) {
                $cek_khs = DB::table('acd_student_khs')
                    ->where([['Student_Id', $Student_Id], ['Krs_Id', $cek_krs[0]->Krs_Id]])
                    ->get();
                if ($cek_khs->count() > 0) {
                    $u = DB::table('acd_transcript')
                        ->where('Transcript_Id', $id)
                        ->update([
                            'Term_Year_Id' => 0,
                            'Weight_Value' => $weight_value,
                            'Khs_Id' => $cek_khs[0]->Khs_Id,
                            'is_Use' => 1,
                            'Is_Transfer' => 1,
                            'Sks' => $Sks,
                            'Course_Id' => $Course_Id,
                            'Sks_Transfer' => $Sks_Transfer,
                            'Grade_Letter_Id' => $Grade_Letter_Id,
                            'Grade_Letter_Transfer' => $Grade_Letter_Transfer,
                            'Course_Code_Transfer' => $Course_Code_Transfer,
                            'Course_Name_Transfer' => $Course_Name_Transfer,
                            'Modified_By' => auth()->user()->email,
                            'Modified_Date' => date('Y-m-d H:i:s'),
                        ]);
                } else {
                    $khs = DB::table('acd_student_khs')->insertgetId([
                        'Student_Id' => $Student_Id,
                        'Krs_Id' => $cek_krs[0]->Krs_Id,
                        'Grade_Letter_Id' => $Grade_Letter_Id,
                        'Sks' => $Sks,
                        'Weight_Value' => $weight_value,
                        'Is_For_Transkrip' => 1,
                        'Bnk_Value' => $Sks * $weight_value,
                        'Is_Published' => 1,
                        'Created_By' => auth()->user()->email,
                        'Created_Date' => date('Y-m-d H:i:s'),
                    ]);
                    $khs_Id = DB::getPdo()->lastInsertId();
                    $u = DB::table('acd_transcript')
                        ->where('Transcript_Id', $id)
                        ->update([
                            'Term_Year_Id' => 0,
                            'Weight_Value' => $weight_value,
                            'Khs_Id' => $khs_Id,
                            'is_Use' => 1,
                            'Is_Transfer' => 1,
                            'Sks' => $Sks,
                            'Course_Id' => $Course_Id,
                            'Sks_Transfer' => $Sks_Transfer,
                            'Grade_Letter_Id' => $Grade_Letter_Id,
                            'Grade_Letter_Transfer' => $Grade_Letter_Transfer,
                            'Course_Code_Transfer' => $Course_Code_Transfer,
                            'Course_Name_Transfer' => $Course_Name_Transfer,
                            'Modified_By' => auth()->user()->email,
                            'Modified_Date' => date('Y-m-d H:i:s'),
                        ]);
                }
            } else {
                $krs = DB::table('acd_student_krs')->insertgetId([
                    'Student_Id' => $Student_Id,
                    'Class_Prog_Id' => $data_student->Class_Prog_Id,
                    'Term_Year_Id' => 0,
                    'Course_Id' => $Course_Id,
                    'Sks' => $Sks,
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s'),
                ]);
                $krs_Id = DB::getPdo()->lastInsertId();
                $khs = DB::table('acd_student_khs')->insertgetId([
                    'Student_Id' => $Student_Id,
                    'Krs_Id' => $krs_Id,
                    'Grade_Letter_Id' => $Grade_Letter_Id,
                    'Sks' => $Sks,
                    'Weight_Value' => $weight_value,
                    'Is_For_Transkrip' => 1,
                    'Bnk_Value' => $Sks * $weight_value,
                    'Is_Published' => 1,
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s'),
                ]);
                $khs_Id = DB::getPdo()->lastInsertId();

                $transkrip = DB::select('CALL usp_UpdateTrancript_ByStudentId_ByCourseId(?,?)', [$khs_Id, '']);
                $u = DB::table('acd_transcript')
                    ->where('Khs_Id', $khs_Id)
                    ->update([
                        'Term_Year_Id' => 0,
                        'is_Use' => 1,
                        'Is_Transfer' => 1,
                        'Modified_By' => auth()->user()->email,
                        'Modified_Date' => date('Y-m-d H:i:s'),
                    ]);
            }

            return Redirect::back()
                ->withErrors('Berhasil Mengubah Data Nilai')
                ->with('success', true);
        } catch (\Exception $e) {
            return Redirect::back()
                ->withErrors('Gagal Mengubah Data Nilai')
                ->with('success', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $search = DB::table('acd_transcript')
            ->where([['Is_Transfer', 1], ['Transcript_Id', $id]])
            ->first();
        if ($search->Khs_Id != null) {
            $n = DB::table('acd_student_khs')
                ->where('Khs_Id', $search->Khs_Id)
                ->first();
            $q = DB::table('acd_transcript')
                ->where('Transcript_Id', $id)
                ->delete();
            $p = DB::table('acd_student_khs')
                ->where('Khs_Id', $search->Khs_Id)
                ->delete();
            $o = DB::table('acd_student_krs')
                ->where('Krs_Id', $n->Krs_Id)
                ->delete();
            echo json_encode($q);
        } else {
            $q = DB::table('acd_transcript')
                ->where('Transcript_Id', $id)
                ->delete();
            echo json_encode($q);
        }
    }
}
