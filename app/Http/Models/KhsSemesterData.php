<?php

namespace App\Http\Models;

use DB;

class KhsSemesterData extends BaseModel
{

    public function getKhsCourse($id, $termyearid)
    {
        return $result = DB::table('acd_student_krs')
            ->join('acd_course', 'acd_student_krs.Course_Id', '=', 'acd_course.Course_Id')
            ->join('acd_student', 'acd_student_krs.Student_Id', '=', 'acd_student.Student_Id')
            ->join('acd_student_khs', 'acd_student_krs.Krs_Id', '=', 'acd_student_khs.Krs_Id')
            ->join('acd_grade_letter', 'acd_student_khs.Grade_Letter_Id', '=', 'acd_grade_letter.Grade_Letter_Id')
            ->join('mstr_class', 'acd_student_krs.Class_Id', '=', 'mstr_class.Class_Id')
            ->where('acd_student_khs.Student_Id', $id)
            ->where('acd_student_krs.Term_Year_Id', $termyearid)
            ->select('acd_student.Student_Id', 'Course_Code', 'Course_Name', 'acd_student_khs.Sks', 'Bnk_Value', 'acd_grade_letter.Grade_Letter', 'Weight_Value', 'acd_grade_letter.Grade_Letter_Id', 'Class_Name', 'acd_course.Course_Id')
            ->orderBy('Course_Code', 'Desc')
            ->get();
    }

    public function getLecturerName($courseid)
    {
        return $result = DB::table('emp_employee')
            ->join('acd_offered_course_lecturer', 'emp_employee.Employee_Id', '=', 'acd_offered_course_lecturer.Employee_Id')
            ->join('acd_offered_course', 'acd_offered_course_lecturer.Offered_Course_Id', 'acd_offered_course.Offered_Course_Id')
            ->where('Course_Id', $courseid)
            ->select('Full_Name')
            ->first();
    }
}