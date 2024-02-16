<?php

namespace App\Http\Models;

use DB;

class TranskripData extends BaseModel
{
    static function getTranscript($studentid)
    {
        return $result = DB::table('acd_transcript')
            ->join('acd_course', 'acd_transcript.course_id', '=', 'acd_course.course_id')
            ->join('acd_grade_letter', 'acd_transcript.grade_letter_id', '=', 'acd_grade_letter.grade_letter_id')
            ->where('Student_Id', $studentid)
            ->get();

    }

}