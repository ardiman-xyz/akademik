<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;
use Auth;

class ApiStrukturalController extends Controller
{
    public function struktural($Structural_Code,$faculty,$department){
       return DB::select("SELECT
        `emp_employee`.`Employee_Id` AS `Employee_Id`,
        `emp_employee`.`Nip` AS `Nip`,
        `emp_employee`.`Name` AS `Name`,
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
              JOIN (
                  SELECT
                    `emp_placement`.`Employee_Id` AS `Employee_Id`,
                    max( `emp_placement`.`Placement_Id` ) AS `groupedP` 
                  FROM
                    `emp_placement` 
                  GROUP BY
                    `emp_placement`.`Employee_Id` 
                ) `group_maxP` ON ( ( `emp_employee`.`Employee_Id` = `group_maxP`.`Employee_Id` ) ) 
              ) 
            )
            JOIN `emp_employee_structural` ON ( ( `emp_employee_structural`.`Employee_Id` = `emp_employee`.`Employee_Id` ) ) 
          )
          JOIN `emp_structural` ON ( ( `emp_structural`.`Structural_Id` = `emp_employee_structural`.`Structural_Id` ) ) 
        ) 
      WHERE
      ( `emp_employee_structural`.`Employee_Structural_Id` = `group_max`.`grouped` AND 
        `emp_placement`.`Placement_Id` = `group_maxP`.`groupedP` AND 
        `emp_placement`.`Faculty_Id` = $faculty AND 
        `emp_placement`.`Department_Id` = $department AND 
        `emp_structural`.`Structural_Code` = $Structural_Code AND 
      `emp_employee_structural`.`End_Date` >= '".date('Y-m-d')."' )
      ");
    }
}
