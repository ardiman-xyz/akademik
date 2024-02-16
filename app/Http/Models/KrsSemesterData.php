<?php
/**
 * Created by PhpStorm.
 * User: UMY Techno
 * Date: 8/24/2018
 * Time: 2:07 PM
 */

namespace App\Http\Models;

use DB;

class KrsSemesterData extends BaseModel
{
    static function getAllCourse($termyearid, $courseid, $classprogid, $offeredcourseid)
    {
        return $result = DB::table('acd_student_krs')
            ->join('mstr_class', 'mstr_class.Class_Id', '=', 'acd_student_krs.Class_Id')
            ->join('mstr_class_program', 'mstr_class_program.Class_Prog_Id', '=', 'acd_student_krs.Class_Prog_Id')
            ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_krs.Term_Year_Id')
            ->join('acd_course', 'acd_course.Course_Id', '=', 'acd_student_krs.Course_Id')
            ->leftJoin('acd_offered_course', 'acd_offered_course.Course_Id', '=', 'acd_student_krs.Course_Id')
            ->leftjoin('acd_offered_course_lecturer', 'acd_offered_course_lecturer.Offered_Course_id', '=', 'acd_offered_course.Offered_Course_id')
            ->select(DB::RAW('
                        acd_student_krs.Krs_Id,
                        acd_student_krs.Sks,
                        acd_student_krs.Course_Id,
                        mstr_class.Class_Name,
                        acd_student_krs.Krs_Id,
                        mstr_class.Class_Name,
                        mstr_class_program.Class_Program_Name,
                        mstr_term_year.Term_Year_Id,
                        acd_course.Course_Code,
                        acd_course.Course_Name,
                        acd_offered_course.Offered_Course_id,
                        (SELECT
                                GROUP_CONCAT(emp_employee.Full_Name SEPARATOR " | ")
                            FROM
                                emp_employee
                                    INNER JOIN acd_offered_course_lecturer on acd_offered_course_lecturer.Employee_Id = emp_employee.Employee_Id
                                    INNER JOIN acd_offered_course on acd_offered_course.Offered_Course_id = acd_offered_course_lecturer.Offered_Course_id
                                    INNER JOIN acd_course on acd_course.Course_Id = acd_offered_course.Course_Id
                            WHERE
                                    acd_offered_course_lecturer.Offered_Course_id = ' . $offeredcourseid . ') AS Lecturer,

                        (SELECT
                                GROUP_CONCAT(mstr_room.Room_Name," ",mstr_day.Day_Name," ",acd_sched_session.Time_Start,"-",acd_sched_session.Time_End
                                        SEPARATOR " | ")
                            FROM
                                acd_offered_course_sched
                                INNER JOIN acd_sched_session on acd_sched_session.Sched_Session_Id = acd_offered_course_sched.Sched_Session_Id
                                INNER JOIN mstr_day on mstr_day.Day_Id = acd_sched_session.Day_Id
                                INNER JOIN mstr_room on mstr_room.Room_Id =acd_offered_course_sched.Room_Id
                            WHERE
                                acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) AS Schedule'))
            ->where('acd_student_krs.Course_Id', $courseid)
            ->where('acd_student_krs.Term_Year_Id', $termyearid)
            ->where('acd_student_krs.Class_Prog_Id', $classprogid)
            ->where('acd_offered_course.Offered_Course_id', $offeredcourseid)
            ->get();

    }

    static function getDetailCourse(array $course_data)
    {
        $query = DB::table('acd_student_krs')
            ->join('mstr_class', 'acd_student_krs.Class_Id', '=', 'mstr_class.Class_Id')
            ->join('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_krs.Term_Year_Id')
            ->join('mstr_class_program', 'acd_student_krs.Class_Prog_Id', '=', 'mstr_class_program.Class_Prog_Id')
            ->join('acd_course', 'acd_student_krs.Course_Id', '=', 'acd_course.Course_Id')
            ->where($course_data)
            ->get();

        return $query->toArray();
    }

}