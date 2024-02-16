<?php


namespace App\Http\Models;

use App\Entities\AcdStudentKrs;
use App\Entities\MstrTermYear;
use Illuminate\Database\Eloquent\Model;

class TermYearData extends Model
{
    /**
     * return id tahun ajaran
     * @param $id
     * @return mixed
     */
    static function getTermYearId($id)
    {
        return $result = AcdStudentKrs::where(['Student_Id' => $id])->pluck('Term_Year_Id');
    }

    /**
     * return daftar tahun ajaran
     * @param $termyearid
     * @return array
     */
    static function getTermYearName($termyearid)
    {
        return $result = MstrTermYear::all()->whereIn('Term_Year_Id', $termyearid)->all();
    }

    /**
     * return nama tahun ajaran semester saat ini
     * @param $id
     * @return array
     */
    static function getCurrentTermYearId($id)
    {
        return $result = AcdStudentKrs::all(['Student_Id' => $id])->first();
    }

    /**
     * return nama tahun ajaran semester saat ini
     * @param $termyearid
     * @return mixed
     */
    static function getCurrentTermYearName($termyearid)
    {
        return $result = MstrTermYear::all(['Term_Year_Id' => $termyearid])
            ->pluck('Term_Year_Id', 'Term_Year_Name')->get();
    }


}