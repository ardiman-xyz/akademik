<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;
use Auth;

class ApiStrukturalController extends Controller
{
    static function struktural($Structural_Code,$Faculty_Id=0,$Department_Id=0){
      // dd($Structural_Code,$Faculty_Id,$Department_Id);
       return DB::select("SELECT
        `emp_employee`.`Employee_Id` AS `Employee_Id`,
        `emp_employee`.`Nip` AS `Nip`,
        `emp_employee`.`Nidn` AS `Nidn`,
        `emp_employee`.`Name` AS `Name`,
        `emp_employee`.`Full_Name` AS `Full_Name`,
        `emp_employee`.`Last_Title` AS `Last_Title`,
        `emp_employee`.`First_Title` AS `First_Title`,
        `emp_employee_structural`.`Structural_Id` AS `Structural_Id`,
        `emp_structural`.`Structural_Name` AS `Structural_Name`,
        `emp_employee_structural`.`Start_Date` AS `Start_Date`,
        `emp_employee_structural`.`End_Date` AS `End_Date`,
        `emp_employee_structural`.`Structural_Allowance` AS `Salary` 
      FROM
        (
          (
            (
              `emp_employee`
              JOIN (
              SELECT
                `emp_employee_structural`.`Employee_Id` AS `Employee_Id`,
                max( `emp_employee_structural`.`Employee_Structural_Id` ) AS `grouped` 
              FROM
                `emp_employee_structural` 
              GROUP BY
                `emp_employee_structural`.`Employee_Id` 
              ) `group_max` ON ( ( `emp_employee`.`Employee_Id` = `group_max`.`Employee_Id` ) ) 
            )
            JOIN `emp_employee_structural` ON ( ( `emp_employee_structural`.`Employee_Id` = `emp_employee`.`Employee_Id` ) ) 
						JOIN `emp_placement` ON ( ( `emp_placement`.`Employee_Id` = `emp_employee`.`Employee_Id` ) ) 
          )
          JOIN `emp_structural` ON ( ( `emp_structural`.`Structural_Id` = `emp_employee_structural`.`Structural_Id` ) ) 
        ) 
      WHERE
      ( `emp_employee_structural`.`Employee_Structural_Id` = `group_max`.`grouped` AND 
      `emp_employee_structural`.`End_Date` >= '".date('Y-m-d')."' AND 
			`emp_placement`.`Faculty_Id` = '".$Faculty_Id."' AND 
      (`emp_placement`.`Department_Id` = '".$Department_Id."' OR emp_placement.Department_Id IS NULL ) AND
      `emp_structural`.`Structural_Code` = '".$Structural_Code."' )
      ");
    }

    static function new_struktural($Structural_Code,$Faculty_Id=0,$Department_Id=0){
      if($Department_Id == ''){
        return DB::select("
          SELECT e.Employee_Id,e.Nip,e.Nidn,e.Full_Name,es.Structural_Name
          FROM emp_employee e
          JOIN emp_employee_structural ees
            ON ees.Employee_Id=e.Employee_Id
          JOIN emp_structural es
            ON es.Structural_Id=ees.Structural_Id
          WHERE ees.Start_Date <NOW()
            AND ees.End_Date > NOW()
            AND ees.Faculty_Id = '".$Faculty_Id."'
            AND es.Structural_Code = '".$Structural_Code."'
        ");
      }else{
        return DB::select("
          SELECT e.Employee_Id,e.Nip,e.Nidn,e.Full_Name,es.Structural_Name
          FROM emp_employee e
          JOIN emp_employee_structural ees
            ON ees.Employee_Id=e.Employee_Id
          JOIN emp_structural es
            ON es.Structural_Id=ees.Structural_Id
          WHERE ees.Start_Date <NOW()
            AND ees.End_Date > NOW()
            AND ees.Faculty_Id = '".$Faculty_Id."'
            AND ees.Department_Id = '".$Department_Id."'
            AND es.Structural_Code = '".$Structural_Code."'
        ");
        // return DB::select("
        //   SELECT e.Employee_Id,e.Nip,e.Nidn,e.Full_Name,es.Structural_Name
        //   FROM emp_employee e
        //   JOIN emp_employee_structural ees
        //     ON ees.Employee_Id=e.Employee_Id
        //   JOIN emp_structural es
        //     ON es.Structural_Id=ees.Structural_Id
        //     AND ees.Employee_Structural_Id IN (SELECT max(employee_structural_id) FROM emp_employee_structural WHERE employee_id=e.Employee_Id )
        //   WHERE ees.Start_Date <NOW()
        //     AND ees.End_Date > NOW()
        //     AND ees.Faculty_Id = '".$Faculty_Id."'
        //     AND ees.Department_Id = '".$Department_Id."'
        //     AND es.Structural_Code = '".$Structural_Code."'
        // ");
      }
    }

    static function dosen_prodi($Faculty_Id,$Department_Id){
      return DB::select("
        SELECT e.Employee_Id,e.Full_Name,ees.Description
        FROM emp_employee e
        JOIN emp_placement ep
          ON e.Employee_Id = ep.Employee_Id
        JOIN emp_employee_golru eeg
          ON e.Employee_Id = eeg.Employee_Id
        JOIN emp_employee_status ees
          ON eeg.Status_Id = ees.Employee_Status_Id
        WHERE ep.tmt_date<NOW()
          AND eeg.Status_Id IN (13,14,15,19,20)
          -- AND ep.Faculty_Id = '".$Faculty_Id."'
          AND ep.Department_Id = '".$Department_Id."'
      ");
    }
}

// ( `emp_employee_structural`.`Employee_Structural_Id` = `group_max`.`grouped` AND 
//       `emp_employee_structural`.`End_Date` >= '".date('Y-m-d')."' AND 
//       `emp_employee_structural`.`Faculty_Id` = '".$Faculty_Id."' AND 
//       `emp_employee_structural`.`Department_Id` = '".$Department_Id."' AND 
//       `emp_structural`.`Structural_Code` = '".$Structural_Code."' )
      