<?php

namespace App\Http\Models;

use App\Http\Helpers\SessionHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Input;

/**
 * Class StoreProcedure
 * @package App\Http\Models
 */
class StoreProcedure extends Model
{
    /**
     * @param $id
     * @param $ipsemester integer
     * @return array
     */
    static function getAllowedSKSForKHS($ipsemester)
    {
        $id = SessionHelpers::getStudentId();
        return $query = DB::select('CALL usp_GetAllowedSKSForKHS(?,?)', [$id, $ipsemester]);
    }

    /**
     * @param $termyearid
     * @param $id
     * @return array
     */
    static function getAllowedSKSForKRS($termyearid,$nim)
    {
        $id1 = DB::table('acd_student')->where('nim', $nim)->where('Nim', $nim)->select('Student_Id')->first();
        $id=$id1->Student_Id;
        return $result = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)', [$termyearid, $id]);
    }

    /**
     * @param $termyearid
     * @param $departmentid
     * @param $classprogid
     * @param $courseid
     * @param $classid
     * @return array
     */
    static function getClassInfoForKRS($termyearid, $courseid, $classid,$nim)
    {
        $classprogid1 = DB::table('acd_student')->where('nim', $nim)->select('Class_Prog_Id')->first();
        $departmentid1 = DB::table('acd_student')->where('nim', $nim)->select('Department_Id')->first();
        // dd($classprogid1);
        $classprogid =$classprogid1->Class_Prog_Id;
        $departmentid=$departmentid1->Department_Id;

        return $query = DB::select('CALL usp_GetClassInfoForKRS(?,?,?,?,?)', [$termyearid, $departmentid, $classprogid, $courseid, $classid]);
    }

    /**
     * @param $departmentid
     * @param $termyearid
     * @param $classprogid
     * @param $entryyearid
     * @param $courseid
     * @return array
     */
    static function getCourseCostForKRS($termyearid, $courseid,$nim)
    {
      $entryyearid1 = DB::table('acd_student')->where('nim', $nim)->where('Nim', $nim)->select('Entry_Year_Id')->first();
      $entryyearid=$entryyearid1->Entry_Year_Id;
      $classprogid1 = DB::table('acd_student')->where('nim', $nim)->where('Nim', $nim)->select('Class_Prog_Id')->first();
      $classprogid=$classprogid1->Class_Prog_Id;
      $departmentid1 = DB::table('acd_student')->where('nim', $nim)->where('Nim', $nim)->select('Department_Id')->first();
      $departmentid=$departmentid1->Department_Id;
        // $entryyearid = SessionHelpers::getEntryTermId();
        // $classprogid = SessionHelpers::getClassProgramId();
        // $departmentid = SessionHelpers::getDepartmentId();

        return $query = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)', [$departmentid, $termyearid, $classprogid, $entryyearid, $courseid]);
    }

    /**
     * @param $termyearid
     * @param $departmentid
     * @param $classprogid
     * @param $entryyearid
     * @param $courseid
     * @return array
     */
    static function getOfferedCourseClassForKRS($termyearid, $courseid)
    {
        $entryyearid = SessionHelpers::getEntryTermId();
        $classprogid = SessionHelpers::getClassProgramId();
        $departmentid = SessionHelpers::getDepartmentId();

        return $query = DB::select('CALL usp_GetOfferredCourseClassForKRS(?,?,?,?,?)', [$termyearid, $departmentid, $classprogid, $entryyearid, $courseid]);
    }

    /**
     * @param $termyearid
     * @return array
     */
    static function getOfferedCourseForKRS($termyearid,$nim)
    {

        $entryyearid1 = DB::table('acd_student')->where('nim', $nim)->select('Entry_Year_Id')->first();
        $classprogid1 = DB::table('acd_student')->where('nim', $nim)->select('Class_Prog_Id')->first();
        $departmentid1 = DB::table('acd_student')->where('nim', $nim)->select('Department_Id')->first();
        // dd($classprogid1);
        $entryyearid =$entryyearid1->Entry_Year_Id;
        $classprogid =$classprogid1->Class_Prog_Id;
        $departmentid=$departmentid1->Department_Id;
        // $entryyearid = SessionHelpers::getEntryYearId();
        // $classprogid = SessionHelpers::getClassProgramId();
        // $departmentid = SessionHelpers::getDepartmentId();
$query = DB::select('CALL usp_GetOfferredCourseForKRS(?,?,?,?)', [$termyearid, $departmentid, $classprogid, $entryyearid]);
// dd($termyearid,$departmentid,$classprogid,$entryyearid);
        return $query;
    }

    /**
     * @param $termyearid
     * @return array
     */
    static function getOfferedCourseForKRSByStudent($termyearid,$nim)
    {
        $std_id1 = DB::table('acd_student')->where('nim', $nim)->select('Student_Id')->first();
        $entryyearid1 = DB::table('acd_student')->where('nim', $nim)->select('Entry_Year_Id')->first();
        $classprogid1 = DB::table('acd_student')->where('nim', $nim)->select('Class_Prog_Id')->first();
        $departmentid1 = DB::table('acd_student')->where('nim', $nim)->select('Department_Id')->first();
        $id =$std_id1->Student_Id;
        $entryyearid =$entryyearid1->Entry_Year_Id;
        $classprogid =$classprogid1->Class_Prog_Id;
        $departmentid=$departmentid1->Department_Id;
        dd($termyearid,$departmentid,$classprogid,$entryyearid,$id);

        return $query = DB::select('CALL usp_GetOfferredCourseForKRSByStudent(?,?,?,?,?)', [$termyearid, $departmentid, $classprogid, $entryyearid, $id]);
    }

    /**
     * @return array
     */
    static function getStudentBill()
    {
        $registernumber = SessionHelpers::getRegisterNumber();
        return $query = DB::select('CALL usp_GetStudentBill(?,?,?)', [$registernumber, '', '']);
    }

    /**
     * @return array
     */
    static function getStudentBillForKRS()
    {
        $registernumber = SessionHelpers::getRegisterNumber();
        return $query = DB::select('CALL usp_GetStudentBill_For_KRS(?)', [$registernumber]);
    }

    /**
     * @return array
     */
    static function getStudentBillAllPaymentOrder()
    {
        $registernumber = SessionHelpers::getRegisterNumber();
        $entryyearid = SessionHelpers::getEntryYearId();

        return $query = DB::select('CALL usp_GetStudentBillAllPaymentOrder(?,?)', [$registernumber, $entryyearid]);
    }

    /**
     * @return array
     */
    static function getStudentBillChoice()
    {
        $registernumber = SessionHelpers::getRegisterNumber();
        return $query = DB::select('CALL usp_GetStudentBillChoice2(?)', [$registernumber]);
    }

    /**
     * @param $termyearid
     * @return array
     */
    static function getSaldo($termyearid)
    {
        // $id = $registernumber = SessionHelpers::getStudentNim();
        // return $query = DB::select('CALL usp_saldo(?,?)', [$id, $termyearid]);
    }

    /**
     * @param $termyearid
     * @param $entryperiod
     * @param $entryperiodtype
     * @param $modifiedby
     * @return array
     */
    static function getCopyCostSched($termyearid, $entryperiod, $entryperiodtype, $modifiedby)
    {
        $entryyearid = SessionHelpers::getEntryYearId();
        return $query = DB::select('CALL usp_CopyCostSched_ByTY_ByEY_ByEP(?,?,?,?,?)', [$termyearid, $entryyearid, $entryperiod, $entryperiodtype, $modifiedby]);
    }


}
