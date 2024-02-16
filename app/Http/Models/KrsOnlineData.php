<?php

namespace App\Http\Models;

use DB;

class KrsOnlineData extends BaseModel
{

    static function getClassName($courseid, $termyearid, $classprogid)
    {
        return $result = DB::table('acd_offered_course')
            ->select('acd_offered_course.Class_Id', 'mstr_class.Class_Name')
            ->join('mstr_class', 'acd_offered_course.Class_Id', '=', 'mstr_class.Class_Id')
            ->where(['Course_Id' => $courseid, 'Term_Year_Id' => $termyearid, 'Class_Prog_Id' => $classprogid])
            ->get();

//            ->whereIn('Course_Id', function ($query) use ($courseid) {
//                $query->select('Course_Id')->from('acd_student_krs')
//                    ->where(['Course_Id' => $courseid]);
//            })->whereIn('Term_Year_Id', function ($query) use ($termyearid) {
//                $query->select('Term_Year_Id')->from('acd_student_krs')
//                    ->where(['Term_Year_Id' => $termyearid]);
//            })->whereIn('Class_Prog_Id', function ($query) use ($classprogid) {
//                $query->select('Class_Prog_Id')->from('acd_student_krs')
//                    ->where(['Class_Prog_Id' => $classprogid]);
//            })->get();
    }

    static function getCourseName($studentid, $termyearid)
    {
        return $result = DB::table('acd_student_krs')
            ->join('acd_student', 'acd_student_krs.Student_Id', '=', 'acd_student.Student_Id')
            ->join('acd_course', 'acd_student_krs.Course_Id', '=', 'acd_course.Course_Id')
            ->join('mstr_class', 'acd_student_krs.Class_Id', '=', 'mstr_class.Class_Id')
            ->where('acd_student.Student_Id', $studentid)
            ->where('Term_Year_Id', $termyearid)
            ->select('Krs_id', 'Course_Code', 'Course_Name', 'Sks', 'Class_Name', 'Amount', 'acd_course.Course_Id', 'acd_student_krs.Term_Year_Id', 'mstr_class.Class_Id')
            ->orderBy('Course_Code', 'dsc')
            ->get();
    }

        static function getRemidi($departmentid)
    {
        return $result = DB::table('acd_short_term_krs')
            ->where('Department_Id', $departmentid)
            ->first();
    }

    static function getPrerequisite($courseid, $departmentid)
    {
        return $result = DB::table('acd_prerequisite')
            ->select('Prerequisite_Id', 'Curriculum_Id')
            ->where('Course_Id', $courseid)
            ->where('Department_Id', $departmentid)
            ->first();

//            ->whereIn('Department_Id', function ($query) use ($courseid) {
//                $query->select('Department_Id')
//                    ->from('acd_course_curriculum')
//                    ->where('Course_Id', $courseid);
//            })->first();
    }

    static function getGradeDepartment($departmentid)
    {
        return $result = DB::table('acd_grade_department')
            ->join('acd_grade_letter', 'acd_grade_department.Grade_Letter_Id', '=', 'acd_grade_letter.Grade_Letter_Id')
            ->select('Weight_Value')
            ->where('Department_Id', $departmentid)
            ->first();
    }

    static function sumGrade($studentid, $gradeletter)
    {
        return $result = DB::table('acd_transcript')
            ->join('acd_grade_letter', 'acd_transcript.Grade_Id', '=', 'acd_grade_letter.Grade_Id')
            ->where('Student_Id', $studentid)
            ->where('acd_grade_letter.Grade_Letter_Id', $gradeletter)
            ->select('Sks')
            ->HavingRaw('SUM(Sks)')
            ->get();
    }

    static function courseCheck($courseid, $termyearid, $departmentid, $classprogid, $entryyearid, $studentid)
    {
        return $result = DB::table('acd_offered_course as OC')
            ->join('acd_course AS C', 'C.Course_Id', '=', 'OC.Course_Id')
            ->join('acd_course_curriculum AS CC', 'CC.Department_Id', '=', 'OC.Department_Id')
            ->orWhere('CC.Class_Prog_Id', '=', 'OC.Class_Prog_Id')
            ->orWhere('CC.Course_Id', '=', 'OC.Course_Id')
            ->join('fnc_course_cost_type as CCT', 'CCT.Term_Year_Id', '=', 'OC.Term_Year_Id')
            ->orWhere('CCT.Department_Id', '=', 'OC.Department_Id')
            ->orWhere('CCT.Class_Prog_Id', '=', 'OC.Class_Prog_Id')
            ->orWhere('CCT.Course_Id', '=', 'OC.Course_Id')
            ->join('fnc_course_cost_sks CCS', 'CCS.Term_Year_Id', '=', 'OC.Term_Year_Id')
            ->orWhere('CCS.Term_Year_Id', '=', 'OC.Term_Year_Id')
            ->orWhere('CCS.Department_Id', '=', 'OC.Department_Id')
            ->orWhere('CCS.Class_Prog_Id', '=', 'OC.Class_Prog_Id')
            ->orWhere('CCS.Entry_Year_Id', '=', $entryyearid)
            ->orWhere('OC.Term_Year_Id', '=', $termyearid)
            ->orWhere('OC.Department_Id', '=', $departmentid)
            ->orWhere('OC.Class_Prog_Id', '=', $classprogid)
            ->orWhere('C.Course_Id', '=', $courseid)
            ->whereNotIn('oc.Course_Id', function ($query) use ($termyearid, $studentid) {
                $query->select('KRS.Course_Id')
                    ->from('acd_student_krs AS KRS')
                    ->where(['KRS.Term_Year_Id' => $termyearid, 'KRS.Student_Id' => $studentid]);
            })->get();
    }

}