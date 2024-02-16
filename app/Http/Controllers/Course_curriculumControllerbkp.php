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
use Excel;
use App\GetDepartment;

class Course_curriculumControllerbkp extends Controller
{
    public function __construct()
    {
        $this->middleware('access:CanView', ['except' => ['create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('access:CanAdd', ['except' => ['index', 'show', 'edit', 'update', 'destroy']]);
        $this->middleware('access:CanEdit', ['except' => ['index', 'create', 'store', 'show', 'destroy']]);
        $this->middleware('access:CanDelete', ['except' => ['index', 'create', 'store', 'show', 'edit', 'update']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'rowpage' => 'numeric|nullable'
        ]);
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;

        if ($rowpage == null || $rowpage <= 0) {
            $rowpage = 10;
        }
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $curriculum = Input::get('curriculum');
        $semester = Input::get('semester');


        $select_class_program = DB::table('mstr_class_program')
            ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
            ->get();
        $select_curriculum = DB::table('mstr_curriculum_applied as mca')
            ->join('mstr_curriculum as mc', 'mca.Curriculum_Id', '=', 'mc.Curriculum_Id')
            ->where('Department_Id', $department)
            ->get();

        // $select_curriculum = DB::table('mstr_curriculum')
        // ->orderBy('mstr_curriculum.Curriculum_Name', 'desc')
        // ->get();
        $select_semester = DB::table('mstr_study_level')
            ->orderBy('mstr_study_level.Study_Level_Code', 'asc')
            ->get();

        $select_department = GetDepartment::getDepartment();

        $data_all = DB::table('acd_course_curriculum')
            ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_course_curriculum.Class_Prog_Id')
            ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_course_curriculum.Department_Id')

            ->join('mstr_curriculum', 'mstr_curriculum.Curriculum_Id', '=', 'acd_course_curriculum.Curriculum_Id')
            ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_course_curriculum.Course_Id')
            ->leftjoin('acd_course_group', 'acd_course_group.Course_Group_Id', '=', 'acd_course_curriculum.Course_Group_Id')
            ->leftjoin('mstr_study_level', 'mstr_study_level.Study_Level_Id', '=', 'acd_course_curriculum.Study_Level_Id')
            ->leftjoin('mstr_curriculum_type', 'mstr_curriculum_type.Curriculum_Type_Id', '=', 'acd_course_curriculum.Curriculum_Type_Id')

            ->where('acd_course_curriculum.Department_Id', $department)
            ->where('acd_course_curriculum.Class_Prog_Id', $class_program)
            ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
            ->orderBy('acd_course.Course_Code', 'asc');
        if ($semester != 999) {
            $data_all = $data_all->where('acd_course_curriculum.Study_Level_Id', $semester);
        }

        $data = $data_all->where(function ($query) {
            $search = Input::get('search');
            $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
            $query->orwhereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
        })->paginate($rowpage);

        $data->appends(['search' => $search, 'rowpage' => $rowpage, 'class_program' => $class_program, 'curriculum' => $curriculum, 'semester' => $semester, 'department' => $department]);
        return view('acd_course_curriculum/index')->with('query', $data)->with('search', $search)->with('rowpage', $rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_semester', $select_semester)->with('select_department', $select_department)->with('semester', $semester)->with('department', $department)->with('select_curriculum', $select_curriculum)->with('curriculum', $curriculum);
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
        $FacultyId = Auth::user()->Faculty_Id;

        if ($rowpage == null) {
            $rowpage = 10;
        }

        $current_search = Input::get('current_search');
        $current_page = Input::get('current_page');
        $current_rowpage = Input::get('current_rowpage');
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $curriculum = Input::get('curriculum');
        $semester = Input::get('semester');

        $departmentn = DB::table('mstr_department')
            ->wherenotnull('Faculty_Id')
            ->where('department_id', $department)
            ->first();

        $departmentname = $departmentn->Department_Name;


        if ($FacultyId == "") {
            $course_curriculum = DB::table('acd_course_curriculum')
                ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_course_curriculum.Department_Id')
                ->where('acd_course_curriculum.Department_Id', $department)
                ->where('Class_Prog_Id', $class_program)->where('Curriculum_Id', $curriculum)->select('Course_Id');
        } else {
            $course_curriculum = DB::table('acd_course_curriculum')
                ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_course_curriculum.Department_Id')
                ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
                ->where('mstr_faculty.Faculty_Id', $FacultyId)
                ->where('acd_course_curriculum.Department_Id', $department)
                ->where('Class_Prog_Id', $class_program)->where('Curriculum_Id', $curriculum)->select('Course_Id');
        }

        if ($FacultyId == "") {
            if ($search == null) {
                $course = DB::table('acd_course')
                    ->where('acd_course.Department_Id', $department)
                    ->whereNotIn('acd_course.Course_Id', $course_curriculum)
                    ->paginate($rowpage);
            } else {
                $course = DB::table('acd_course')
                    ->where('acd_course.Department_Id', $department)
                    ->whereNotIn('acd_course.Course_Id', $course_curriculum)
                    ->where(function ($query) {
                        $search = Input::get('search');
                        $query->whereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
                        $query->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
                    })
                    ->paginate($rowpage);
            }
        } else {
            if ($search == null) {
                $course = DB::table('acd_course')
                    ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_course.Department_Id')
                    ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
                    ->where('mstr_faculty.Faculty_Id', $FacultyId)
                    ->where('acd_course.Department_Id', $department)
                    ->whereNotIn('acd_course.Course_Id', $course_curriculum)
                    ->paginate($rowpage);
            } else {
                $course = DB::table('acd_course')
                    ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_course.Department_Id')
                    ->join('mstr_faculty', 'mstr_faculty.Faculty_Id', 'mstr_department.Faculty_Id')
                    ->where('mstr_faculty.Faculty_Id', $FacultyId)
                    ->where('acd_course.Department_Id', $department)
                    ->whereNotIn('acd_course.Course_Id', $course_curriculum)
                    ->where(function ($query) {
                        $search = Input::get('search');
                        $query->whereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
                        $query->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
                    })
                    ->paginate($rowpage);
            }
        }

        $km = DB::table('acd_course_group')->count();

        $notif = null;
        if ($km < 1) {
            $notif = "Kelompok mata kuliah belum ada data ";
        }

        $cccount = DB::table('acd_course_group')->select('Course_Group_Id')->count();

        if ($cccount == 0) {
            $notif = "Kelompok mata kuliah belum ada data ";
            $ccc = "";
        } else {
            $cc = DB::table('acd_course_group')->select('Course_Group_Id')->first();
            $ccc = $cc->Course_Group_Id;
        }
        // dd($cc);


        $course->appends(['class_program' => $class_program, 'curriculum' => $curriculum, 'semester' => $semester, 'department' => $department, 'current_page' => $current_page, 'current_rowpage' => $current_rowpage, 'current_search' => $current_search, 'search' => $search, 'rowpage' => $rowpage]);
        return view('acd_course_curriculum/create')->with('departmentname', $departmentname)->with('ccc', $ccc)->with('notif', $notif)->with('course', $course)->with('class_program', $class_program)->with('department', $department)->with('curriculum', $curriculum)->with('semester', $semester)->with('search', $search)->with('rowpage', $rowpage)->with('current_page', $current_page)->with('current_rowpage', $current_rowpage)->with('current_search', $current_search);
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
            'Class_Prog_Id' => 'required',
            'Department_Id' => 'required',
            'Curriculum_Id' => 'required',
        ]);
        $Department_Id = Input::get('Department_Id');
        $Class_Prog_Id = Input::get('Class_Prog_Id');
        $Curriculum_Id = Input::get('Curriculum_Id');
        $Study_Level_Id = Input::get('Study_Level_Id');
        $Course_Id = Input::get('Course_Id');
        $cc = Input::get('cc');
        $Datetimenow = Date('Y-m-d');
        $FacultyId = Auth::user()->Faculty_Id;

        try {
            foreach ($Course_Id as $data) {
                DB::table('acd_course_curriculum')
                    ->insert(
                        ['Department_Id' => $Department_Id, 'Class_Prog_Id' => $Class_Prog_Id, 'Curriculum_Id' => $Curriculum_Id, 'Study_Level_Id' => $Study_Level_Id, 'Course_Id' => $data, 'Is_For_Transcript' => true, 'Is_Required' => true, 'Course_Group_Id' => $cc, 'Curriculum_Type_Id' => 1, 'Is_Valid' => false, 'Created_Date' => $Datetimenow, 'Created_By' => auth()->user()->email, 'Created_Date' => date('Y-m-d H:i:s')]
                    );
            }

            return Redirect::back()->withErrors('Berhasil Menambah Matakuliah Kurikulum');
        } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Menambah Matakuliah Kurikulum');
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
    public function edit($id)
    {
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $curriculum = Input::get('curriculum');
        $semester = Input::get('semester');
        $FacultyId = Auth::user()->Faculty_Id;

        $select_course_group = DB::table('acd_course_group')->get();
        $select_course_group = DB::table('acd_course_group')->get();
        $select_study_level = DB::table('mstr_study_level')->get();
        $select_curriculum_type = DB::table('mstr_curriculum_type')->get();


        $data = DB::table('acd_course_curriculum')
            ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_course_curriculum.Class_Prog_Id')
            ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_course_curriculum.Department_Id')
            ->join('mstr_curriculum', 'mstr_curriculum.Curriculum_Id', '=', 'acd_course_curriculum.Curriculum_Id')
            ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_course_curriculum.Course_Id')
            ->leftjoin('acd_course_group', 'acd_course_group.Course_Group_Id', '=', 'acd_course_curriculum.Course_Group_Id')
            ->leftjoin('mstr_study_level', 'mstr_study_level.Study_Level_Id', '=', 'acd_course_curriculum.Study_Level_Id')
            ->leftjoin('mstr_curriculum_type', 'mstr_curriculum_type.Curriculum_Type_Id', '=', 'acd_course_curriculum.Curriculum_Type_Id')
            ->where('acd_course_curriculum.Course_Cur_Id', $id)
            ->get();

        return view('acd_course_curriculum/edit')->with('query_edit', $data)->with('department', $department)->with('class_program', $class_program)->with('curriculum', $curriculum)->with('semester', $semester)->with('select_course_group', $select_course_group)->with('select_study_level', $select_study_level)->with('select_curriculum_type', $select_curriculum_type)->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);
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
        $this->validate($request, [
            'Applied_Sks' => 'numeric',
            'Transcript_Sks' => 'numeric',
            'Study_Level_Sub' => 'numeric',

        ]);
        $Applied_Sks = Input::get('Applied_Sks');
        $Transcript_Sks = Input::get('Transcript_Sks');
        $Is_For_Transcript = Input::get('Is_For_Transcript');
        $Is_Required = Input::get('Is_Required');
        $Course_Group_Id = Input::get('Course_Group_Id');
        $Study_Level_Id = Input::get('Study_Level_Id');
        $Study_Level_Sub = Input::get('Study_Level_Sub');
        $Curriculum_Type_Id = Input::get('Curriculum_Type_Id');


        try {
            $u =  DB::table('acd_course_curriculum')
                ->where('Course_Cur_Id', $id)
                ->update(
                    ['Applied_Sks' => $Applied_Sks, 'Transcript_Sks' => $Transcript_Sks, 'Is_For_Transcript' => $Is_For_Transcript, 'Is_Required' => $Is_Required, 'Course_Group_Id' => $Course_Group_Id, 'Study_Level_Id' => $Study_Level_Id, 'Study_Level_Sub' => $Study_Level_Sub, 'Curriculum_Type_Id' => $Curriculum_Type_Id]
                );
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
    public function destroy(Request $request, $id)
    {
        $q = DB::table('acd_course_curriculum')->where('Course_Cur_Id', $id)->delete();
        echo json_encode($q);
    }

    public function silabus(Request $request)
    {
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        $FacultyId = Auth::user()->Faculty_Id;
        $semester = Input::get('semester');


        if ($rowpage == null || $rowpage <= 0) {
            $rowpage = 10;
        }
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $curriculum = Input::get('curriculum');
        $course_id = Input::get('course_id');

        $departmentpra = DB::table('mstr_department')->where('Department_Id', $department)->first();
        $curprasyarat = DB::table('mstr_curriculum')->where('Curriculum_Id', $curriculum)->first();
        $coursepra = DB::table('acd_course')->where('Course_Id', $course_id)->first();
        $coursecur = DB::table('acd_course_curriculum')->where([['Course_Id', $course_id], ['Department_Id', $department], ['Curriculum_Id', $curriculum], ['Class_Prog_Id', $class_program]])->first();

        $prerequisite_detail = DB::table('acd_prerequisite_detail')
            ->leftjoin('acd_course', 'acd_course.Course_Id', '=', 'acd_prerequisite_detail.Course_Id')
            ->leftjoin('acd_prerequisite', 'acd_prerequisite.Prerequisite_Id', '=', 'acd_prerequisite_detail.Prerequisite_Id')
            ->leftjoin('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id', '=', 'acd_prerequisite_detail.Grade_Letter_Id')
            ->join('mstr_prerequisite_type', 'mstr_prerequisite_type.Prerequisite_Type_Id', '=', 'acd_prerequisite_detail.Prerequisite_Type_Id')
            ->where('acd_prerequisite.Course_Id', $course_id)
            ->select('acd_prerequisite_detail.*', 'mstr_prerequisite_type.Prerequisite_Type_Name', 'acd_course.Course_Name', 'acd_grade_letter.Grade_Letter')->paginate($rowpage);


        $prerequisite_detail->appends(['search' => $search, 'rowpage' => $rowpage, 'curriculum' => $curriculum, 'department' => $department]);
        return view('acd_course_curriculum/silabus')
            ->with('semester', $semester)
            ->with('course_id', $course_id)
            ->with('prerequisite_detail', $prerequisite_detail)
            ->with('coursepra', $coursepra)
            ->with('coursecur', $coursecur)
            ->with('curprasyarat', $curprasyarat)
            ->with('departmentpra', $departmentpra)
            ->with('search', $search)
            ->with('rowpage', $rowpage)
            ->with('class_program', $class_program)
            ->with('department', $department)
            ->with('curriculum', $curriculum);
    }

    public function store_silabus(Request $request)
    {

        // $camaru = RegCamaru::find(Auth::user()->Camaru_Id);
        $coursecur = $request->coursecur2;
        $curid = $request->curid;
        $deptid = $request->deptid;
        $smtid = $request->smtid;

        $namecourse = DB::table('acd_course_curriculum as a')
            ->join('acd_course as b', 'a.Course_Id', '=', 'b.Course_Id')
            ->where('a.Course_Cur_Id', $coursecur)->first();
        $curname = DB::table('mstr_curriculum')->where('Curriculum_Id', $curid)->first();
        $dept = DB::table('mstr_department')->where('Department_Id', $deptid)->first();

        $names = $namecourse->Course_Name;
        $curs = $curname->Curriculum_Name;
        $depts = $dept->Department_Name;
        $image = $request->file('imageProfile');
        if ($image == null) {
            return redirect()->back();
        } else {
            $name = $names . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads' . '/' . $curs . '/' . $depts . '/' . $smtid);
            $urlPath = url('/uploads' . '/' . $curs . '/' . $depts . '/' . $smtid . "/" . $name);
            $image->move($destinationPath, $name);
            $u =  DB::table('acd_course_curriculum')
                ->where('Course_Cur_Id', $coursecur)
                ->update(
                    ['Silabus_Upload' => $urlPath]
                );

            return redirect()->back();
        }
    }


    public function copydata()
    {
        $department = Input::get('department');
        $class_program = Input::get('class_program');
        $term_year = Input::get('term_year');
        $curriculum = Input::get('curriculum');
        $semester = Input::get('semester');

        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
            ->get();

        $select_department = DB::table('mstr_department')
            ->where('Department_Id', $department)
            ->first();
        $select_curriculum = DB::table('mstr_curriculum')
            ->where('Curriculum_Id', $curriculum)
            ->first();
        $select_class = DB::table('mstr_class_program')
            ->where('Class_Prog_Id', $class_program)
            ->first();

        if ($semester == 999) {
            $semesterstr = 'Semua Semester';
            $class_not = DB::table('acd_course_curriculum')
                ->where([['Department_Id', $department], ['Class_Prog_Id', $class_program], ['Curriculum_Id', $curriculum]])
                ->select('Class_Prog_Id')
                ->groupby('Class_Prog_Id');
        } else {
            $semesterstr = $semester;
            $class_not = DB::table('acd_course_curriculum')
                ->where([['Department_Id', $department], ['Class_Prog_Id', $class_program], ['Curriculum_Id', $curriculum], ['Study_Level_Id', $semester]])
                ->select('Class_Prog_Id')
                ->groupby('Class_Prog_Id');
        }
        $select_class_program = DB::table('mstr_class_program')
            ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
            ->wherenotin('Class_Prog_Id', $class_not)
            ->get();
        $select_semester_curriculum = DB::table('acd_course_curriculum')
            ->where([['Department_Id', $department], ['Class_Prog_Id', $class_program], ['Curriculum_Id', $curriculum]])
            ->select('Study_Level_Id')
            ->groupby('Study_Level_Id')->get();


        return view('acd_course_curriculum/copydata')
            ->with('select_department', $select_department)
            ->with('select_curriculum', $select_curriculum)
            ->with('select_class', $select_class)
            ->with('department', $department)
            ->with('class_program', $class_program)
            ->with('term_year', $term_year)
            ->with('curriculum', $curriculum)
            ->with('semester', $semester)
            ->with('semesterstr', $semesterstr)
            ->with('select_semester_curriculum', $select_semester_curriculum)
            ->with('select_class_program', $select_class_program)
            ->with('select_term_year', $select_term_year);
    }

    public function storecopydata(Request $request)
    {
        $Department_Id = Input::get('dept_asal');
        $Class_Prog_Id = Input::get('class_asal');
        $Semester = Input::get('semester_asal');
        $curriculum = Input::get('cur_asal');

        $class_dest = Input::get('class_dest');

        if ($Semester == 999) {
            $data = DB::table('acd_course_curriculum')
                ->where('acd_course_curriculum.Department_Id', $Department_Id)
                ->where('acd_course_curriculum.Class_Prog_Id', $Class_Prog_Id)
                ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
                ->get();

            $datacount = DB::table('acd_course_curriculum')
                ->where('acd_course_curriculum.Department_Id', $Department_Id)
                ->where('acd_course_curriculum.Class_Prog_Id', $class_dest)
                ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
                ->count();
            if ($datacount > 0) {
                $datahapus = DB::table('acd_course_curriculum')
                    ->where('acd_course_curriculum.Department_Id', $Department_Id)
                    ->where('acd_course_curriculum.Class_Prog_Id', $class_dest)
                    ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
                    ->delete();
            }
        } else {
            $data = DB::table('acd_course_curriculum')
                ->where('acd_course_curriculum.Department_Id', $Department_Id)
                ->where('acd_course_curriculum.Class_Prog_Id', $Class_Prog_Id)
                ->where('acd_course_curriculum.Study_Level_Id', $Semester)
                ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
                ->get();

            $datacount = DB::table('acd_course_curriculum')
                ->where('acd_course_curriculum.Department_Id', $Department_Id)
                ->where('acd_course_curriculum.Class_Prog_Id', $class_dest)
                ->where('acd_course_curriculum.Study_Level_Id', $Semester)
                ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
                ->count();
            if ($datacount > 0) {
                $datahapus = DB::table('acd_course_curriculum')
                    ->where('acd_course_curriculum.Department_Id', $Department_Id)
                    ->where('acd_course_curriculum.Class_Prog_Id', $class_dest)
                    ->where('acd_course_curriculum.Study_Level_Id', $Semester)
                    ->where('acd_course_curriculum.Curriculum_Id', $curriculum)
                    ->delete();
            }
        }


        try {
            foreach ($data as $data) {
                DB::table('acd_course_curriculum')
                    ->insert(
                        [
                            'Department_Id' => $data->Department_Id,
                            'Class_Prog_Id' => $class_dest,
                            'Curriculum_Id' => $data->Curriculum_Id,
                            'Course_Id' => $data->Course_Id,
                            'Course_Group_Id' => $data->Course_Group_Id,
                            'Study_Level_Id' => $data->Study_Level_Id,
                            'Applied_Sks' => $data->Applied_Sks,
                            'Transcript_Sks' => $data->Transcript_Sks,
                            'Is_For_Transcript' => $data->Is_For_Transcript,
                            'Is_Required' => $data->Is_Required,
                            'Curriculum_Type_Id' => $data->Curriculum_Type_Id,
                            'Silabus_Upload' => $data->Silabus_Upload,
                            'Created_By' => auth()->user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                        ]
                    );
            }

            return Redirect::back()->withErrors('Berhasil Kopi Data');
        } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Kopi Data');
        }
    }

    public function hapus_silabus(Request $request)
    {
        $id = $request->id;
        echo json_encode($id);
        $course_cur = DB::table('acd_course_curriculum')->where('Course_Cur_Id', $id)->update(['Silabus_Upload' => null]);
    }

    public function exportexcel(Request $request)
    {
        // dd($request->all());
        Excel::create('kurikulum', function ($excel) use ($request) {
            $data_all = DB::table('acd_course_curriculum')
                ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_course_curriculum.Class_Prog_Id')
                ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_course_curriculum.Department_Id')

                ->join('mstr_curriculum', 'mstr_curriculum.Curriculum_Id', '=', 'acd_course_curriculum.Curriculum_Id')
                ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_course_curriculum.Course_Id')
                ->leftjoin('acd_course_group', 'acd_course_group.Course_Group_Id', '=', 'acd_course_curriculum.Course_Group_Id')
                ->leftjoin('mstr_study_level', 'mstr_study_level.Study_Level_Id', '=', 'acd_course_curriculum.Study_Level_Id')
                ->leftjoin('mstr_curriculum_type', 'mstr_curriculum_type.Curriculum_Type_Id', '=', 'acd_course_curriculum.Curriculum_Type_Id')

                ->where('acd_course_curriculum.Department_Id', $request->department)
                ->where('acd_course_curriculum.Class_Prog_Id', $request->class_program)
                ->where('acd_course_curriculum.Curriculum_Id', $request->curriculum)
                ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc');
            if ($request->semester != 999) {
                $data_all = $data_all->where('acd_course_curriculum.Study_Level_Id', $request->semester);
            }

            $items = $data_all->where(function ($query) use ($request) {
                $search = $request->search;
                $query->whereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
                $query->orwhereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
            })->get();

            if ($items->count() == 0) {
                $data = [
                    [
                        'Department_Code' => '',
                        'Course_Type_Id' => '',
                        'Course_Code' => '',
                        'Course_Name' => '',
                        'Course_Name_Eng' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'Kode Matakuluah' => $item->Course_Code,
                    'Nama Matakuliah' => $item->Course_Name,
                    'SKS' => $item->Applied_Sks,
                    'Transkrip' => ($item->Is_For_Transcript == 1 ? 'Ya' : 'Tidak'),
                    'Sks Transkrip' => $item->Transcript_Sks,
                    'Semester' => $item->Study_Level_Id
                ];

                $i++;
            }

            $excel->sheet('Kelas', function ($sheet) use ($data,$items) {
                $sheet->fromArray($data, null, 'A6');
                $row = 6;
                foreach ($data as $key => $value) {
                    $sheet->setBorder('A' . $row . ':F' . $row, 'thin');

                    $sheet->cells('A' . $row . ':F' . $row, function ($cells) {
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                    $row++;
                }

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setBorder('A6:F' . (sizeof($data) + 6), 'thin');
                $sheet->cells('A6:F' . (sizeof($data) + 6), function ($cells) {
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->mergeCells('A1:F1');
                $sheet->setCellValue('A1', 'Program Studi '.$items[0]->Department_Name);
                $sheet->cells('A1', function ($cells) {
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true
                    ]);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $sheet->mergeCells('A2:F2');
                $sheet->setCellValue('A2', 'Program Kelas '.$items[0]->Class_Program_Name);
                $sheet->cells('A2', function ($cells) {$cells->setFont([
                            'size' => '15',
                            'bold' => true
                        ]);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });

                $sheet->mergeCells('A3:F3');
                $sheet->setCellValue('A3', 'Kurikulum '.$items[0]->Curriculum_Name);
                $sheet->cells('A3', function ($cells) {$cells->setFont([
                            'size' => '15',
                            'bold' => true
                        ]);
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                });
            });
        })->export('xls');
    }
}
