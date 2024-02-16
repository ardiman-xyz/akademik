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
use Excel;
use App\Exports\Daftar_mhskrs;
use App\Exports\Daftar_mhskrsnonaktif;
use App\Exports\Daftar_mhskrscuti;
use App\Mhs_krs;

class Resume_mhs_krsController extends Controller
{
    public function __construct()
    {
        $this->middleware('access:CanView', ['only' => ['index', 'show']]);
        $this->middleware('access:CanViewnonaktif', ['only' => ['showmhsnonaktif']]);
        $this->middleware('access:CanExport', ['only' => ['exportexcelnonaktif', 'exportexcel']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $thsemester = Input::get('thsemester');

        $term_year1 = Input::get('term_year');
        if ($term_year1 == null) {
            $Term_Year_Id = $request->session()->get('term_year');
        } else {
            $Term_Year_Id = Input::get('term_year');
        }

        $Class_Prog_Id = Input::get('prog_kelas');
        $entry_year = Input::get('angkatan');
        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;

        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
            ->get();

        $select_class_program = DB::table('mstr_department_class_program')
            ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'mstr_department_class_program.Class_Prog_Id')
            ->groupBy('mstr_class_program.Class_Program_Name')
            ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
            ->get();

        $select_entry_year = DB::table('mstr_entry_year')
            ->orderBy('Entry_Year_Name', 'desc')
            ->get();

        $data_all = DB::table('mstr_department')
            ->wherenotnull('Faculty_Id')
            ->get();

        $new_data = [];
        $q = 0;
        foreach ($data_all as $key) {
            //mahasiswa aktif
            $mhs_aktif = DB::table('acd_student as std')
            ->join('acd_student_krs as krs','std.Student_Id','=','krs.Student_Id')
            ->where([['std.Department_Id', $key->Department_Id], ['krs.Term_Year_Id', 'like', '%' . $Term_Year_Id], ['std.Entry_Year_Id', 'like', '%' . $request->angkatan], ['std.Class_Prog_Id', 'like', '%' . $request->prog_kelas]])
            ->groupBy('std.Student_Id')
            ->select('std.Student_Id');

            //mahasiswa non aktif
            // $mhs_nonaktif = DB::table('acd_student as std')
            //     ->join('acd_student_krs as ask', 'ask.Student_Id', '=', 'std.Student_Id')
            //     ->where([['std.Department_Id', $key->Department_Id], ['Term_Year_Id', 'like', '%' . $Term_Year_Id]])
            //     ->whereNotIn('ask.Student_Id', $mhs_aktif)
            //     ->groupby('std.Student_Id')
            //     ->select('std.Student_Id');
            $yudisium = DB::table('acd_yudisium')->wherenotnull('Graduate_Date')->select('Student_Id');
            $mhs_nonaktif = DB::table('acd_student')
                ->whereNotIn('acd_student.Student_Id', $mhs_aktif)
                ->whereNotIn('acd_student.Student_Id', $yudisium)
                ->where('Department_Id','like',$key->Department_Id)
                ->where('Class_Prog_Id','like','%'.$request->prog_kelas)
                ->where('Entry_Year_Id','like','%'.$request->angkatan)
                ->select('Student_Id');

            //mahasiswa cuti
            $mhs_cuti = DB::table('acd_student_vacation as asv')
                ->join('acd_student as std', 'asv.Student_Id', '=', 'std.Student_Id')
                ->where([['std.Department_Id', $key->Department_Id], ['Term_Year_Id', 'like', '%' . $Term_Year_Id], ['Is_Approved', 1], ['std.Entry_Year_Id', 'like', '%' . $request->angkatan]])
                ->select('asv.Student_Id');

            $new_data[$q]['Department_Id'] = $key->Department_Id;
            $new_data[$q]['Department_Name'] = $key->Department_Name;
            $new_data[$q]['Department_Name'] = $key->Department_Name;
            $new_data[$q]['JumlahMhsAktif'] = count($mhs_aktif->get());
            $new_data[$q]['JumlahMhsNonAktif'] = count($mhs_nonaktif->get());
            $new_data[$q]['MhsCuti'] = count($mhs_cuti->get());
            $q++;
        }

        // if ($FacultyId == '') {
        //     if ($DepartmentId == '') {
        //         if ($Term_Year_Id == null) {
        //             $query = DB::table('mstr_department')
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw('
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (0) as JumlahMhsAktif,
        //                 (0) as MhsCuti,
        //                 (0) as JumlahMhsNonAktif
        //             '),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id == null && $entry_year == null) {
        //             $query = DB::table('mstr_department')
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student
        //                 LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                 WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             '
        //                 AND acd_student.Department_Id = mstr_department.Department_Id ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw(
        //                         '(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student
        //                 LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                 LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id
        //                 WHERE   acd_student.Student_Id
        //                 NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_out.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_vacation WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id)
        //                 AS JumlahMhsNonAktif',
        //                     ),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id == null && $entry_year != null) {
        //             $query = DB::table('mstr_department')
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw('(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id   LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id   WHERE   acd_student.Student_Id NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' . $entry_year . ') AS JumlahMhsNonAktif'),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id != null && $entry_year == null) {
        //             $query = DB::table('mstr_department')
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ') as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student_vacation.Is_Approved=1 AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ') as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw('(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id   LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id   WHERE   acd_student.Student_Id NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id  AND acd_student.Class_Prog_Id = ' . $Class_Prog_Id . ') AS JumlahMhsNonAktif'),
        //                 )
        //                 ->get();
        //         } else {
        //             $query = DB::table('mstr_department')
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw('
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi
        //             '),
        //                     DB::Raw(
        //                         '
        //             (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student
        //                 LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                 WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             '
        //                 AND acd_student.Department_Id = mstr_department.Department_Id
        //                 AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             '
        //                 AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ') as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     // DB::Raw('(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student.Student_Id = 8 AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = '.$Class_Prog_Id.' AND acd_student.Entry_Year_Id = '.$entry_year.' ) as JumlahMhsLulus
        //                     // '),
        //                     DB::Raw(
        //                         '
        //             (select COUNT(DISTINCT(as2.Student_Id)) from acd_student as2
        //                 WHERE as2.Student_Id NOT IN (
        //                     SELECT distinct(acd_student.Student_Id) as Student_Id  FROM acd_student
        //                     LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                     WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             '
        //                     AND acd_student.Department_Id = mstr_department.Department_Id
        //                     AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             '
        //                     AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ')
        //                 AND as2.Department_Id = mstr_department.Department_Id
        //                 AND as2.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                     '
        //                 AND as2.Student_Id NOT IN (
        //                     SELECT distinct(Student_Id) as Student_Id  FROM acd_yudisium)
        //                 AND as2.Student_Id NOT IN (
        //                     SELECT distinct(Student_Id) as Student_Id  FROM acd_graduation_final)
        //                 AND as2.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ') as JumlahMhsNonAktif
        //             ',
        //                     ),
        //                 )
        //                 ->get();
        //         }
        //     } else {
        //         if ($Term_Year_Id == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw('
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (0) as JumlahMhsAktif,
        //                 (0) as MhsCuti,
        //                 (0) as JumlahMhsNonAktif
        //             '),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id == null && $entry_year == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw(
        //                         '(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student
        //                 LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                 LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id
        //                 WHERE   acd_student.Student_Id
        //                 NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_out.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_vacation WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id)
        //                 AS JumlahMhsNonAktif',
        //                     ),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id == null && $entry_year != null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw('(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id   LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id   WHERE   acd_student.Student_Id NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' . $entry_year . ') AS JumlahMhsNonAktif'),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id != null && $entry_year == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ') as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             'AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw('(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id   LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id   WHERE   acd_student.Student_Id NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id  AND acd_student.Class_Prog_Id = ' . $Class_Prog_Id . ') AS JumlahMhsNonAktif'),
        //                 )
        //                 ->get();
        //         } else {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '
        //             (select COUNT(DISTINCT(as2.Student_Id)) from acd_student as2
        //                 WHERE as2.Student_Id NOT IN (
        //                     SELECT distinct(acd_student.Student_Id) as Student_Id  FROM acd_student
        //                     LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                     WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             '
        //                     AND acd_student.Department_Id = mstr_department.Department_Id
        //                     AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             '
        //                     AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ')
        //                 AND as2.Department_Id = mstr_department.Department_Id
        //                 AND as2.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             '
        //                 AND as2.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ') as JumlahMhsNonAktif
        //             ',
        //                     ),
        //                 )
        //                 ->get();
        //         }
        //     }
        // } else {
        //     if ($DepartmentId == '') {
        //         if ($Term_Year_Id == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw('
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (0) as JumlahMhsAktif,
        //                 (0) as MhsCuti,
        //                 (0) as JumlahMhsNonAktif
        //             '),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id == null && $entry_year == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw(
        //                         '(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student
        //                 LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                 LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id
        //                 WHERE   acd_student.Student_Id
        //                 NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_out.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_vacation WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id)
        //                 AS JumlahMhsNonAktif',
        //                     ),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id == null && $entry_year != null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw('(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id   LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id   WHERE   acd_student.Student_Id NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' . $entry_year . ') AS JumlahMhsNonAktif'),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id != null && $entry_year == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ') as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw('(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id   LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id   WHERE   acd_student.Student_Id NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id  AND acd_student.Class_Prog_Id = ' . $Class_Prog_Id . ') AS JumlahMhsNonAktif'),
        //                 )
        //                 ->get();
        //         } else {
        //             $query = DB::table('mstr_department')
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw(
        //                         '(SELECT COUNT(DISTINCT (acd_student.Student_Id))
        //             FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //             LEFT JOIN acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id
        //                 WHERE acd_student.Student_Id NOT IN (SELECT  Student_id FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //             AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out
        //                     WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                     AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final)
        //                     AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_student_vacation)
        //                     AND acd_student.Department_Id = mstr_department.Department_Id
        //                     AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             '  AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ') AS JumlahMhsNonAktif',
        //                     ),
        //                 )
        //                 ->get();
        //         }
        //     } else {
        //         if ($Term_Year_Id == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw('
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (0) as JumlahMhsAktif,
        //                 (0) as MhsCuti,
        //                 (0) as JumlahMhsNonAktif
        //             '),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id == null && $entry_year == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw(
        //                         '(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student
        //                 LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                 LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id
        //                 WHERE   acd_student.Student_Id
        //                 NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_out.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_vacation WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ')
        //                 AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id)
        //                 AS JumlahMhsNonAktif',
        //                     ),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id == null && $entry_year != null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw('(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id   LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id   WHERE   acd_student.Student_Id NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Entry_Year_Id = ' . $entry_year . ') AS JumlahMhsNonAktif'),
        //                 )
        //                 ->get();
        //         } elseif ($Class_Prog_Id != null && $entry_year == null) {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ') as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::raw('(SELECT COUNT(DISTINCT (acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id   LEFT JOIN   acd_student_out ON acd_student.Student_Id = acd_student_out.Student_Id   WHERE   acd_student.Student_Id NOT IN (SELECT  Student_id   FROM acd_student_krs WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT Student_id FROM acd_student_out WHERE acd_student_krs.Term_Year_Id = ' . $Term_Year_Id . ') AND acd_student.Student_Id NOT IN (SELECT  Student_id FROM  acd_graduation_final) AND acd_student.Department_Id = mstr_department.Department_Id  AND acd_student.Class_Prog_Id = ' . $Class_Prog_Id . ') AS JumlahMhsNonAktif'),
        //                 )
        //                 ->get();
        //         } else {
        //             $query = DB::table('mstr_department')
        //                 ->where('mstr_department.Department_Id', $DepartmentId)
        //                 ->where('Faculty_Id', $FacultyId)
        //                 ->wherenotnull('Faculty_Id')
        //                 ->select(
        //                     DB::Raw(
        //                         '
        //                 mstr_department.Department_Id as dep_Id,
        //                 mstr_department.Department_Name as Prodi,
        //                 (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' ) as JumlahMhsAktif
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '(SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_vacation ON acd_student.Student_Id = acd_student_vacation.Student_Id WHERE acd_student_vacation.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             ' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             ' AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ' AND acd_student_vacation.Is_Approved = 1) as MhsCuti
        //             ',
        //                     ),
        //                     DB::Raw(
        //                         '
        //             (select COUNT(DISTINCT(as2.Student_Id)) from acd_student as2
        //                 WHERE as2.Student_Id NOT IN (
        //                     SELECT distinct(acd_student.Student_Id) as Student_Id  FROM acd_student
        //                     LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id
        //                     WHERE acd_student_krs.Term_Year_Id = ' .
        //                             $Term_Year_Id .
        //                             '
        //                     AND acd_student.Department_Id = mstr_department.Department_Id
        //                     AND acd_student.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                             '
        //                     AND acd_student.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ')
        //                 AND as2.Department_Id = mstr_department.Department_Id
        //                 AND as2.Class_Prog_Id = ' .
        //                             $Class_Prog_Id .
        //                     '
        //                 AND as2.Student_Id NOT IN (
        //                     SELECT distinct(Student_Id) as Student_Id  FROM acd_yudisium)
        //                 AND as2.Entry_Year_Id = ' .
        //                             $entry_year .
        //                             ') as JumlahMhsNonAktif
        //             ',
        //                     ),
        //                 )
        //                 ->get();
        //         }
        //     }
        // }
        $query = [];

        return view('laporan_mhskrs/index')
            ->with('new_data', $new_data)
            ->with('query', $query)
            ->with('prog_kelas', $Class_Prog_Id)
            ->with('thsemester', $thsemester)
            ->with('term_year', $Term_Year_Id)
            ->with('entry_year', $entry_year)
            ->with('select_term_year', $select_term_year)
            ->with('select_class_program', $select_class_program)
            ->with('select_entry_year', $select_entry_year);
    }

    public function showmhslulus($id)
    {
        $term_year = Input::get('term_year');
        $prog_kelas = Input::get('prog_kelas');
        $entry_year = Input::get('angkatan');

        $department = DB::table('mstr_department')
            ->where('Department_Id', $id)
            ->first();
        $thsmt = DB::table('mstr_term_year')
            ->where('Term_Year_Id', $term_year)
            ->first();

        $query = DB::table('acd_student')
            ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
            ->where('acd_student.Status_Id', 8)
            ->where('acd_student.Department_Id', $id)
            ->where('acd_student.Class_Prog_Id', $prog_kelas)
            ->where('acd_student.Entry_Year_Id', $entry_year)
            ->get();

        return view('laporan_mhskrs/showmhslulus')
            ->with('id', $id)
            ->with('query', $query)
            ->with('department', $department)
            ->with('entry_year', $entry_year)
            ->with('thsmt', $thsmt)
            ->with('term_year', $term_year)
            ->with('angkatan', $entry_year)
            ->with('prog_kelas', $prog_kelas);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $term_year = Input::get('term_year');
        $prog_kelas = Input::get('prog_kelas');
        $entry_year = Input::get('angkatan');

        $department = DB::table('mstr_department')
            ->where('Department_Id', $id)
            ->first();
        $thsmt = DB::table('mstr_term_year')
            ->where('Term_Year_Id', $term_year)
            ->first();

        if ($prog_kelas == 0 && $entry_year == 0) {
            $query = DB::table('acd_student')
                ->join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_krs.Term_Year_Id', $term_year)
                ->where('acd_student.Department_Id', $id)
                ->groupBy('acd_student.Student_Id')
                ->get();
        } elseif ($prog_kelas == 0 && $entry_year != 0) {
            $query = DB::table('acd_student')
                ->join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_krs.Term_Year_Id', $term_year)
                ->where('acd_student.Entry_Year_Id', $entry_year)
                ->where('acd_student.Department_Id', $id)
                ->groupBy('acd_student.Student_Id')
                ->get();
        } elseif ($prog_kelas != 0 && $entry_year == 0) {
            $query = DB::table('acd_student')
                ->join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_krs.Term_Year_Id', $term_year)
                ->where('acd_student.Class_Prog_Id', $prog_kelas)
                ->where('acd_student.Department_Id', $id)
                ->groupBy('acd_student.Student_Id')
                ->get();
        } else {
            $query = DB::table('acd_student')
                ->join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_krs.Term_Year_Id', $term_year)
                ->where('acd_student.Class_Prog_Id', $prog_kelas)
                ->where('acd_student.Entry_Year_Id', $entry_year)
                ->where('acd_student.Department_Id', $id)
                ->groupBy('acd_student.Student_Id')
                ->get();
        }

        return view('laporan_mhskrs/show')
            ->with('id', $id)
            ->with('query', $query)
            ->with('department', $department)
            ->with('entry_year', $entry_year)
            ->with('thsmt', $thsmt)
            ->with('term_year', $term_year)
            ->with('angkatan', $entry_year)
            ->with('prog_kelas', $prog_kelas);
        //
    }

    public function showmhsnonaktif($id)
    {
        $term_year = Input::get('term_year');
        $prog_kelas = Input::get('prog_kelas');
        $entry_year = Input::get('angkatan');

        $department = DB::table('mstr_department')
            ->where('Department_Id', $id)
            ->first();
        $thsmt = DB::table('mstr_term_year')
            ->where('Term_Year_Id', $term_year)
            ->first();

        // $std_krs = DB::table('acd_student_krs')->select('Student_Id');
        $std_krs = DB::table('acd_student')
            ->join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
            ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
            ->where('acd_student_krs.Term_Year_Id', $term_year)
            ->where('acd_student.Class_Prog_Id', $prog_kelas)
            ->where('acd_student.Entry_Year_Id', $entry_year)
            ->where('acd_student.Department_Id', $id)
            ->groupBy('acd_student.Student_Id')
            ->select('acd_student_krs.Student_Id');
        $std_out = DB::table('acd_student_out')->where('Term_Year_Id',$term_year)->select('Student_Id');
        $std_gradfinal = DB::table('acd_graduation_final')->select('Student_Id');
        $yudisium = DB::table('acd_yudisium')->wherenotnull('Graduate_Date')->select('Student_Id');

        if ($prog_kelas == 0 && $entry_year == 0) {
            $query = DB::table('acd_student')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->whereNotIn('acd_student.Student_Id', $std_krs)
                ->whereNotIn('acd_student.Student_Id', $std_out)
                ->whereNotIn('acd_student.Student_Id', $std_gradfinal)
                ->whereNotIn('acd_student.Student_Id', $yudisium)
                ->where('acd_student.Department_Id', $id)
                ->get();
        } elseif ($prog_kelas == 0 && $entry_year != 0) {
            $query = DB::table('acd_student')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->whereNotIn('acd_student.Student_Id', $std_krs)
                ->whereNotIn('acd_student.Student_Id', $std_out)
                ->whereNotIn('acd_student.Student_Id', $std_gradfinal)
                ->whereNotIn('acd_student.Student_Id', $yudisium)
                ->where('acd_student.Department_Id', $id)
                ->where('acd_student.Entry_Year_Id', $entry_year)
                ->get();
        } elseif ($prog_kelas != 0 && $entry_year == 0) {
            $query = DB::table('acd_student')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->whereNotIn('acd_student.Student_Id', $std_krs)
                ->whereNotIn('acd_student.Student_Id', $std_out)
                ->whereNotIn('acd_student.Student_Id', $std_gradfinal)
                ->whereNotIn('acd_student.Student_Id', $yudisium)
                ->where('acd_student.Department_Id', $id)
                ->where('acd_student.Class_Prog_Id', $prog_kelas)
                ->get();
        } else {
            $query = DB::table('acd_student')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->whereNotIn('acd_student.Student_Id', $std_krs)
                ->whereNotIn('acd_student.Student_Id', $std_out)
                ->whereNotIn('acd_student.Student_Id', $std_gradfinal)
                ->whereNotIn('acd_student.Student_Id', $yudisium)
                ->where('acd_student.Department_Id', $id)
                ->where('acd_student.Class_Prog_Id', $prog_kelas)
                ->where('acd_student.Entry_Year_Id', $entry_year)
                ->get();
        }

        return view('laporan_mhskrs/showmhsnonaktif')
            ->with('id', $id)
            ->with('query', $query)
            ->with('department', $department)
            ->with('entry_year', $entry_year)
            ->with('thsmt', $thsmt)
            ->with('term_year', $term_year)
            ->with('angkatan', $entry_year)
            ->with('prog_kelas', $prog_kelas);
        //
    }

    public function showmhscuti($id)
    {
        $term_year = Input::get('term_year');
        $prog_kelas = Input::get('prog_kelas');
        $entry_year = Input::get('angkatan');

        $department = DB::table('mstr_department')
            ->where('Department_Id', $id)
            ->first();
        $thsmt = DB::table('mstr_term_year')
            ->where('Term_Year_Id', $term_year)
            ->first();

        $std_krs = DB::table('acd_student_krs')->select('Student_Id');
        $std_out = DB::table('acd_student_out')->select('Student_Id');
        $std_gradfinal = DB::table('acd_graduation_final')->select('Student_Id');

        if ($prog_kelas == 0 && $entry_year == 0) {
            $query = DB::table('acd_student_vacation')
                // ->select('acd_student.Nim','acd_student.Full_Name','acd_student_vacation.*','mstr_term_year.Term_Year_Name','acd_vacation_reason.Vacation_Reason')
                ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
                ->leftjoin('acd_student', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_vacation.Term_Year_Id', $term_year)
                ->where('acd_student.Department_Id', $id)
                ->where('acd_student_vacation.Is_Approved', 1)
                ->groupBy('Student_Vacation_Id')
                ->orderBy('Student_Vacation_Id', 'asc')
                ->get();
        } elseif ($prog_kelas == 0 && $entry_year != 0) {
            $query = DB::table('acd_student_vacation')
                // ->select('acd_student.Nim','acd_student.Full_Name','acd_student_vacation.*','mstr_term_year.Term_Year_Name','acd_vacation_reason.Vacation_Reason')
                ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
                ->leftjoin('acd_student', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_vacation.Term_Year_Id', $term_year)
                ->where('acd_student.Department_Id', $id)
                ->where('acd_student.Entry_Year_Id', $entry_year)
                ->where('acd_student_vacation.Is_Approved', 1)
                ->groupBy('Student_Vacation_Id')
                ->orderBy('Student_Vacation_Id', 'asc')
                ->get();
        } elseif ($prog_kelas != 0 && $entry_year == 0) {
            $query = DB::table('acd_student_vacation')
                // ->select('acd_student.Nim','acd_student.Full_Name','acd_student_vacation.*','mstr_term_year.Term_Year_Name','acd_vacation_reason.Vacation_Reason')
                ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
                ->leftjoin('acd_student', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_vacation.Term_Year_Id', $term_year)
                ->where('acd_student.Department_Id', $id)
                ->where('acd_student.Class_Prog_Id', $prog_kelas)
                ->where('acd_student_vacation.Is_Approved', 1)
                ->groupBy('Student_Vacation_Id')
                ->orderBy('Student_Vacation_Id', 'asc')
                ->get();
        } else {
            $query = DB::table('acd_student_vacation')
                // ->select('acd_student.Nim','acd_student.Full_Name','acd_student_vacation.*','mstr_term_year.Term_Year_Name','acd_vacation_reason.Vacation_Reason')
                ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
                ->leftjoin('acd_student', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_vacation.Term_Year_Id', $term_year)
                ->where('acd_student.Department_Id', $id)
                ->where('acd_student.Class_Prog_Id', $prog_kelas)
                ->where('acd_student.Entry_Year_Id', $entry_year)
                ->where('acd_student_vacation.Is_Approved', 1)
                ->groupBy('Student_Vacation_Id')
                ->orderBy('Student_Vacation_Id', 'asc')
                ->get();
        }

        return view('laporan_mhskrs/showmhscuti')
            ->with('id', $id)
            ->with('query', $query)
            ->with('department', $department)
            ->with('entry_year', $entry_year)
            ->with('thsmt', $thsmt)
            ->with('term_year', $term_year)
            ->with('angkatan', $entry_year)
            ->with('prog_kelas', $prog_kelas);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function exportexcel()
    {
        $term_year = Input::get('term_year');
        $prog_kelas = Input::get('prog_kelas');
        $angkatan = Input::get('angkatan');
        $id_prod = Input::get('department');

        Excel::create('Detail Mahasiswa Aktif', function ($excel) use ($term_year, $prog_kelas, $angkatan, $id_prod) {
            if ($prog_kelas == 0 && $angkatan == 0) {
                $items = Mhs_krs::join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                    ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->where('Term_Year_Id', $term_year)
                    ->where('Department_Id', $id_prod)
                    ->groupBy('acd_student.Student_Id')
                    ->get();
            } elseif ($prog_kelas == 0 && $angkatan != 0) {
                $items = Mhs_krs::join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                    ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->where('Term_Year_Id', $term_year)
                    ->where('Entry_Year_Id', $angkatan)
                    ->where('Department_Id', $id_prod)
                    ->groupBy('acd_student.Student_Id')
                    ->get();
            } elseif ($prog_kelas != 0 && $angkatan == 0) {
                $items = Mhs_krs::join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                    ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->where('Term_Year_Id', $term_year)
                    ->where('acd_student.Class_Prog_Id', $prog_kelas)
                    ->where('Department_Id', $id_prod)
                    ->groupBy('acd_student.Student_Id')
                    ->get();
            } else {
                $items = Mhs_krs::join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                    ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->where('Term_Year_Id', $term_year)
                    ->where('acd_student.Class_Prog_Id', $prog_kelas)
                    ->where('Entry_Year_Id', $angkatan)
                    ->where('Department_Id', $id_prod)
                    ->groupBy('acd_student.Student_Id')
                    ->get();
            }

            if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'NIM' => '',
                        'NAMA' => '',
                        'KELAS PROGRAM' => '',
                    ],
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'NO' => $i,
                    'NIM' => $item->Nim,
                    'NAMA' => $item->Full_Name,
                    'KELAS PROGRAM' => $item->Class_Program_Name,
                ];

                $i++;
            }

            $excel->sheet('Detail Mahasiswa Aktif', function ($sheet) use ($data, $term_year, $id_prod) {
                $dept = db::table('mstr_department')
                    ->where('Department_Id', $id_prod)
                    ->select('Department_Name')
                    ->first();
                $dept_name = $dept->Department_Name;
                $term_yearn = DB::table('mstr_term_year')
                    ->where('Term_Year_Id', $term_year)
                    ->select('Term_Year_Name')
                    ->first();
                $term_Year_Name = $term_yearn->Term_Year_Name;

                $sheet->fromArray($data, null, 'B8');

                $num_rows = sizeof($data) + 8;

                for ($i = 9; $i <= $num_rows; $i++) {
                    $rows[$i] = 18;
                }

                $rows[8] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10,
                    ],
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 6,
                    'C' => 14,
                    'D' => 36,
                    'E' => 18,
                ]);

                $sheet->mergeCells('B2:E2');
                $sheet->mergeCells('B3:E3');
                $sheet->mergeCells('B4:E4');
                $sheet->mergeCells('B5:E5');
                $sheet->mergeCells('B6:E6');

                $kampus1 = strtoupper(env('NAME_UNIV1'));
                $kampus2 = strtoupper(env('NAME_UNIV2'));
                $prodi = strtoupper('Prodi ' . $dept_name);
                $term = strtoupper('tahun ajaran ' . $term_Year_Name);

                $sheet->setCellValue('B2', $kampus1);
                $sheet->setCellValue('B3', $kampus2);
                $sheet->setCellValue('B4', 'DATA MAHASISWA AKTIF');
                $sheet->setCellValue('B5', $prodi);
                $sheet->setCellValue('B6', $term);

                $sheet->cells('B2', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B3', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B4', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B5', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B6', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) {
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }

                $sheet->setBorder('B8:E' . (sizeof($data) + 8), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('B8:E8', function ($cells) {
                    $cells->setBackground('#dddddd');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xlsx');
    }

    public function exportexcelnonaktif()
    {
        $term_year = Input::get('term_year');
        $prog_kelas = Input::get('prog_kelas');
        $angkatan = Input::get('angkatan');
        $id_prod = Input::get('department');

        Excel::create('Detail Mahasiswa Non-Aktif', function ($excel) use ($term_year, $prog_kelas, $angkatan, $id_prod) {
            // $std_krs = DB::table('acd_student_krs')->select('Student_Id');
            $std_krs = DB::table('acd_student')
                ->join('acd_student_krs', 'acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                ->where('acd_student_krs.Term_Year_Id', $term_year)
                ->where('acd_student.Class_Prog_Id', $prog_kelas)
                ->where('acd_student.Entry_Year_Id', $angkatan)
                ->where('acd_student.Department_Id', $id_prod)
                ->groupBy('acd_student.Student_Id')
                ->select('acd_student_krs.Student_Id');
            $std_out = DB::table('acd_student_out')->select('Student_Id');
            $std_gradfinal = DB::table('acd_graduation_final')->select('Student_Id');

            if ($prog_kelas == 0 && $angkatan == 0) {
                $items = Mhs_krs::join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->whereNotIn('acd_student.Student_Id', $std_krs)
                    ->whereNotIn('acd_student.Student_Id', $std_out)
                    ->whereNotIn('acd_student.Student_Id', $std_gradfinal)
                    ->where('acd_student.Department_Id', $id_prod)
                    ->get();
            } elseif ($prog_kelas == 0 && $angkatan != 0) {
                $items = Mhs_krs::join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->whereNotIn('acd_student.Student_Id', $std_krs)
                    ->whereNotIn('acd_student.Student_Id', $std_out)
                    ->whereNotIn('acd_student.Student_Id', $std_gradfinal)
                    ->where('acd_student.Department_Id', $id_prod)
                    ->where('acd_student.Entry_Year_Id', $angkatan)
                    ->get();
            } elseif ($prog_kelas != 0 && $angkatan == 0) {
                $items = Mhs_krs::join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->whereNotIn('acd_student.Student_Id', $std_krs)
                    ->whereNotIn('acd_student.Student_Id', $std_out)
                    ->whereNotIn('acd_student.Student_Id', $std_gradfinal)
                    ->where('acd_student.Department_Id', $id_prod)
                    ->where('acd_student.Class_Prog_Id', $prog_kelas)
                    ->get();
            } else {
                $items = Mhs_krs::join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->whereNotIn('acd_student.Student_Id', $std_krs)
                    ->whereNotIn('acd_student.Student_Id', $std_out)
                    ->whereNotIn('acd_student.Student_Id', $std_gradfinal)
                    ->where('acd_student.Department_Id', $id_prod)
                    ->where('acd_student.Class_Prog_Id', $prog_kelas)
                    ->where('acd_student.Entry_Year_Id', $angkatan)
                    ->get();
            }

            if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'NIM' => '',
                        'NAMA' => '',
                        'KELAS PROGRAM' => '',
                        'TAGIHAN' => '',
                    ],
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $studentbill = DB::select('CALL usp_GetStudentBill(?,?,?)', [$item->Register_Number, '', '']);
                $a = 0;
                $ListTagihan = [];
                $total = 0;
                if ($studentbill != null) {
                    foreach ($studentbill as $key) {
                        $ListTagihan[$i]['Amount'] = $key->Amount;
                        $ListTagihan[$i]['Cost_Item_Name'] = $key->Cost_Item_Name;
                        $a++;
                    }

                    $sumAmount = 0;
                    foreach ($ListTagihan as $tagihan) {
                        $sumAmount += $tagihan['Amount'];
                    }
                    $total = number_format($sumAmount, '0', ',', '.');
                }

                $data[] = [
                    'NO' => $i,
                    'NIM' => $item->Nim,
                    'NAMA' => $item->Full_Name,
                    'KELAS PROGRAM' => $item->Class_Program_Name,
                    'TAGIHAN' => $total,
                ];

                $i++;
            }

            $excel->sheet('Detail Mahasiswa Non-Aktif', function ($sheet) use ($data, $term_year, $id_prod) {
                $dept = db::table('mstr_department')
                    ->where('Department_Id', $id_prod)
                    ->select('Department_Name')
                    ->first();
                $dept_name = $dept->Department_Name;
                $term_yearn = DB::table('mstr_term_year')
                    ->where('Term_Year_Id', $term_year)
                    ->select('Term_Year_Name')
                    ->first();
                $term_Year_Name = $term_yearn->Term_Year_Name;

                $sheet->fromArray($data, null, 'B8');

                $num_rows = sizeof($data) + 8;

                for ($i = 9; $i <= $num_rows; $i++) {
                    $rows[$i] = 18;
                }

                $rows[8] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10,
                    ],
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 6,
                    'C' => 14,
                    'D' => 36,
                    'E' => 18,
                    'F' => 18,
                ]);

                $sheet->mergeCells('B2:F2');
                $sheet->mergeCells('B3:F3');
                $sheet->mergeCells('B4:F4');
                $sheet->mergeCells('B5:F5');
                $sheet->mergeCells('B6:F6');

                $kampus1 = strtoupper(env('NAME_UNIV1'));
                $kampus2 = strtoupper(env('NAME_UNIV2'));
                $prodi = strtoupper('Prodi ' . $dept_name);
                $term = strtoupper('tahun ajaran ' . $term_Year_Name);

                $sheet->setCellValue('B2', $kampus1);
                $sheet->setCellValue('B3', $kampus2);
                $sheet->setCellValue('B4', 'DATA MAHASISWA NON-AKTIF');
                $sheet->setCellValue('B5', $prodi);
                $sheet->setCellValue('B6', $term);

                $sheet->cells('B2', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B3', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B4', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B5', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B6', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) {
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }

                $sheet->setBorder('B8:F' . (sizeof($data) + 8), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('B8:F8', function ($cells) {
                    $cells->setBackground('#dddddd');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xlsx');
    }

    public function exportexcelcuti()
    {
        $term_year = Input::get('term_year');
        $prog_kelas = Input::get('prog_kelas');
        $angkatan = Input::get('angkatan');
        $id_prod = Input::get('department');

        Excel::create('Detail Mahasiswa Cuti', function ($excel) use ($term_year, $prog_kelas, $angkatan, $id_prod) {
            $std_krs = DB::table('acd_student_krs')->select('Student_Id');
            $std_out = DB::table('acd_student_out')->select('Student_Id');
            $std_gradfinal = DB::table('acd_graduation_final')->select('Student_Id');

            if ($prog_kelas == 0 && $angkatan == 0) {
                $items = Mhs_krs::leftjoin('acd_student_vacation', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                    ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
                    ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
                    ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->where('acd_student_vacation.Term_Year_Id', $term_year)
                    ->where('acd_student.Department_Id', $id_prod)
                    ->where('acd_student.Class_Prog_Id', $prog_kelas)
                    ->where('acd_student.Entry_Year_Id', $angkatan)
                    ->where('acd_student_vacation.Is_Approved', 1)
                    ->groupBy('Student_Vacation_Id')
                    ->orderBy('Student_Vacation_Id', 'asc')
                    ->get();
            } elseif ($prog_kelas == 0 && $angkatan != 0) {
                $items = Mhs_krs::leftjoin('acd_student_vacation', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                    ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
                    ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
                    ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->where('acd_student_vacation.Term_Year_Id', $term_year)
                    ->where('acd_student.Department_Id', $id_prod)
                    ->where('acd_student.Entry_Year_Id', $angkatan)
                    ->where('acd_student_vacation.Is_Approved', 1)
                    ->groupBy('Student_Vacation_Id')
                    ->orderBy('Student_Vacation_Id', 'asc')
                    ->get();
            } elseif ($prog_kelas != 0 && $angkatan == 0) {
                $items = Mhs_krs::leftjoin('acd_student_vacation', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                    ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
                    ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
                    ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->where('acd_student_vacation.Term_Year_Id', $term_year)
                    ->where('acd_student.Department_Id', $id_prod)
                    ->where('acd_student.Class_Prog_Id', $prog_kelas)
                    ->where('acd_student_vacation.Is_Approved', 1)
                    ->groupBy('Student_Vacation_Id')
                    ->orderBy('Student_Vacation_Id', 'asc')
                    ->get();
            } else {
                $items = Mhs_krs::leftjoin('acd_student_vacation', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                    ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
                    ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
                    ->join('mstr_class_program', 'acd_student.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
                    ->where('acd_student_vacation.Term_Year_Id', $term_year)
                    ->where('acd_student.Department_Id', $id_prod)
                    ->where('acd_student.Class_Prog_Id', $prog_kelas)
                    ->where('acd_student.Entry_Year_Id', $angkatan)
                    ->where('acd_student_vacation.Is_Approved', 1)
                    ->groupBy('Student_Vacation_Id')
                    ->orderBy('Student_Vacation_Id', 'asc')
                    ->get();
            }

            if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'NIM' => '',
                        'NAMA' => '',
                        'KELAS PROGRAM' => '',
                        'TAGIHAN' => '',
                    ],
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $studentbill = DB::select('CALL usp_GetStudentBill(?,?,?)', [$item->Register_Number, '', '']);
                $a = 0;
                $ListTagihan = [];
                $total = 0;
                if ($studentbill != null) {
                    foreach ($studentbill as $key) {
                        $ListTagihan[$i]['Amount'] = $key->Amount;
                        $ListTagihan[$i]['Cost_Item_Name'] = $key->Cost_Item_Name;
                        $a++;
                    }

                    $sumAmount = 0;
                    foreach ($ListTagihan as $tagihan) {
                        $sumAmount += $tagihan['Amount'];
                    }
                    $totals = number_format($sumAmount, '0', ',', '.');
                    if ($sumAmount == '' || $sumAmount == 0) {
                        $total = '-';
                    } else {
                        $total = $totals;
                    }
                }

                $data[] = [
                    'NO' => $i,
                    'NIM' => $item->Nim,
                    'NAMA' => $item->Full_Name,
                    'KELAS PROGRAM' => $item->Class_Program_Name,
                    'TAGIHAN' => $total,
                ];

                $i++;
            }

            $excel->sheet('Detail Mahasiswa cuti', function ($sheet) use ($data, $term_year, $id_prod) {
                $dept = db::table('mstr_department')
                    ->where('Department_Id', $id_prod)
                    ->select('Department_Name')
                    ->first();
                $dept_name = $dept->Department_Name;
                $term_yearn = DB::table('mstr_term_year')
                    ->where('Term_Year_Id', $term_year)
                    ->select('Term_Year_Name')
                    ->first();
                $term_Year_Name = $term_yearn->Term_Year_Name;

                $sheet->fromArray($data, null, 'B8');

                $num_rows = sizeof($data) + 8;

                for ($i = 9; $i <= $num_rows; $i++) {
                    $rows[$i] = 18;
                }

                $rows[8] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10,
                    ],
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 6,
                    'C' => 14,
                    'D' => 36,
                    'E' => 18,
                    'F' => 18,
                ]);

                $sheet->mergeCells('B2:F2');
                $sheet->mergeCells('B3:F3');
                $sheet->mergeCells('B4:F4');
                $sheet->mergeCells('B5:F5');
                $sheet->mergeCells('B6:F6');

                $kampus1 = strtoupper(env('NAME_UNIV1'));
                $kampus2 = strtoupper(env('NAME_UNIV2'));
                $prodi = strtoupper('Prodi ' . $dept_name);
                $term = strtoupper('tahun ajaran ' . $term_Year_Name);

                $sheet->setCellValue('B2', $kampus1);
                $sheet->setCellValue('B3', $kampus2);
                $sheet->setCellValue('B4', 'DATA MAHASISWA CUTI');
                $sheet->setCellValue('B5', $prodi);
                $sheet->setCellValue('B6', $term);

                $sheet->cells('B2', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B3', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B4', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B5', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B6', function ($cells) {
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true,
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) {
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }

                $sheet->setBorder('B8:F' . (sizeof($data) + 8), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('B8:F8', function ($cells) {
                    $cells->setBackground('#dddddd');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xlsx');
    }
}
