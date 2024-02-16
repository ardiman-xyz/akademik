<?php

namespace App\Http\Models;

use App\Entities\AcdStudent;
use App\Http\Helpers\SessionHelpers;
use Illuminate\Support\Facades\DB;

class StudentData extends BaseModel
{

    /**
     * return data detail mahasiswa
     * @param $id
     * @return AcdStudent[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    static function getStudentDetail($id)
    {
        return $result = AcdStudent::all()->find($id);

    }

    /**
     * return register number
     * @return \Illuminate\Support\Collection
     */
    static function getRegisterNumberId()
    {
        $id = SessionHelpers::getStudentId();
        return $result = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->select('Register_Number')
            ->get();
    }

    /**
     * return id
     * @return \Illuminate\Support\Collection $query
     */
    static function getStudentId()
    {
        $id = SessionHelpers::getStudentId();

        return $result = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->select('Student_Id')
            ->get();
    }

    /**
     * return data jurusan
     * @return \Illuminate\Support\Collection $query
     */
    static function getDepartmentId()
    {
        $id = SessionHelpers::getStudentId();

        return $result = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->select('Department_Id')
            ->get();
    }

    /**
     * return program kelas
     * @return \Illuminate\Support\Collection $query
     */
    static function getClassProgramId()
    {
        $id = SessionHelpers::getStudentId();

        return $result = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->select('Class_Prog_Id')
            ->get();
    }

    /**
     * return tahun pendaftaran
     * @return \Illuminate\Support\Collection $query
     */
    static function getEntryYearId()
    {
        $id = SessionHelpers::getStudentId();

        return $result = DB::table('acd_student')
            ->where('Student_Id', $id)
            ->select('Entry_Year_Id')
            ->get();
    }

}