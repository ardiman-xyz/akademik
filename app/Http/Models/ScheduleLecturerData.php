<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;

class ScheduleLecturerData extends BaseModel
{
    static function getAllCourse($termyearid)
    {
        return $result = DB::table('acd_offered_course')
            ->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')
            ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_offered_course.Class_Prog_Id')
            ->join('mstr_department', 'mstr_department.Department_Id', '=', 'acd_offered_course.Department_Id')
            ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_offered_course.Term_Year_Id')
            ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')
            ->leftjoin('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Offered_Course_id', '=', 'acd_offered_course.Offered_Course_id')
            ->join('emp_employee', 'emp_employee.Employee_Id', '=', 'acd_offered_course_lecturer.Employee_Id')
            ->select(DB::RAW('
                acd_offered_course.Offered_Course_id,
                acd_course.Course_Code,
                acd_course.Course_Name,
                mstr_class.Class_Name,
                mstr_class_program.Class_Program_Name,
                
                (SELECT GROUP_CONCAT(mstr_room.Room_Name," ",mstr_day.Day_Name," ",acd_sched_session.Time_Start,"-",acd_sched_session.Time_End SEPARATOR " | ")
                    FROM acd_offered_course_sched
                        INNER JOIN acd_sched_session on acd_sched_session.Sched_Session_Id = acd_offered_course_sched.Sched_Session_Id
                        INNER JOIN mstr_day on mstr_day.Day_Id = acd_sched_session.Day_Id
                        INNER JOIN mstr_room on mstr_room.Room_Id =acd_offered_course_sched.Room_Id
                    WHERE
                        acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) AS Lecturer_Schedule,

                (SELECT GROUP_CONCAT(emp_employee.Full_Name SEPARATOR " | ")
                    FROM acd_offered_course_lecturer
                        LEFT JOIN  emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id
                        WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) AS Lecturer_Name
                '))
            ->groupBy('acd_offered_course.Offered_Course_id')
            ->where('acd_offered_course.Term_Year_Id', $termyearid)
            ->get();

    }

    static function getKrsSched($studentid, $termyearid)
    {
        return $result = DB::table('acd_student_krs')
            ->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student_krs.Class_Id')
            ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student_krs.Class_Prog_Id')
            ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_krs.Term_Year_Id')
            ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_student_krs.Course_Id')
            ->select(DB::RAW('
                acd_course.Course_Code,
                acd_course.Course_Name,
                acd_student_krs.Sks,
                mstr_class.Class_Name,
                acd_student_krs.Amount,
                
                (SELECT GROUP_CONCAT(mstr_room.Room_Name," ",mstr_day.Day_Name," ",acd_sched_session.Time_Start,"-",acd_sched_session.Time_End SEPARATOR " | ")
                    FROM acd_offered_course_sched
                        INNER JOIN acd_sched_session on acd_sched_session.Sched_Session_Id = acd_offered_course_sched.Sched_Session_Id
                        INNER JOIN mstr_day on mstr_day.Day_Id = acd_sched_session.Day_Id
                        INNER JOIN mstr_room on mstr_room.Room_Id =acd_offered_course_sched.Room_Id
                        INNER JOIN acd_offered_course on acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id
                    WHERE
                        acd_offered_course.Course_Id = acd_student_krs.Course_Id
                    AND
                        acd_offered_course.Term_Year_Id = ' . $termyearid . '
                    AND 
                        acd_offered_course.Class_Id = acd_student_krs.Class_Id
                        ) AS Lecturer_Schedule
              '))
            ->where('acd_student_krs.Student_Id', $studentid)
            ->where('acd_student_krs.Term_Year_Id', $termyearid)
            ->get();
    }

    static function getall($department, $class_program, $term_year)
    {

        return $result = DB::table('acd_offered_course')
            ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_offered_course.Class_Prog_Id')
            ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_offered_course.Term_Year_Id')
            ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_offered_course.Course_Id')
            ->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_offered_course.Class_Id')
            ->where('acd_offered_course.Department_Id', $department)
            ->where('acd_offered_course.Class_Prog_Id', $class_program)
            ->where('acd_offered_course.Term_Year_Id', $term_year)
            ->select('acd_offered_course.*', 'acd_course.*', 'mstr_class.Class_Name',
                DB::raw('(SELECT  Group_Concat( emp_employee.Full_Name SEPARATOR "|" ) FROM acd_offered_course_lecturer LEFT JOIN emp_employee ON acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id WHERE acd_offered_course_lecturer.Offered_Course_id = acd_offered_course.Offered_Course_id) as dosen'))
            ->orderBy('acd_course.Course_Name', 'asc')
            ->orderBy('mstr_class.class_Name', 'asc')
            ->get();
    }

    static function getLecturer(array $values)
    {
        return $result = DB::table('emp_employee as EMP')
            ->join('acd_offered_course_lecturer as OCL', 'EMP.employee_id', '=', 'OCL.employee_id')
            ->join('acd_offered_course as OC', 'OCL.offered_course_id', '=', 'OC.offered_course_id')
            ->where($values)
            ->orderBy('OCL.order_id', 'asc')
            ->get();
    }

    static function getSchedule(array $values)
    {
        return $result = DB::table('acd_sched_session as ASS')
            ->join('mstr_day as MD', 'ASS.day_id', '=', 'MD.day_id')
            ->join('acd_offered_course_sched as OCS', 'ASS.sched_session_id', '=', 'OCS.sched_session_id')
            ->join('mstr_room as MR', 'OCS.room_id', '=', 'MR.room_id')
            ->join('acd_offered_course as OC', 'OCS.offered_course_id', '=', 'OC.offered_course_id')
            ->where($values)
            ->get();
    }

}