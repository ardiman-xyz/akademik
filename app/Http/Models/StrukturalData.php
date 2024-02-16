<?php

namespace App\Http\Models;

use DB;

class StrukturalData extends BaseModel
{
    static function getkaprodiS1sipil()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',21)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )->select('b.Structural_Name','c.Name','a.Sk_Num','a.Start_Date','a.End_Date','c.Nik','c.Nip','c.Nidn','c.Nbm','c.First_Title','c.Last_Title',DB::raw("(SELECT MAX(a.Sk_Date)) as Sk_Date"))
            ->get();
        return $result;
    }

    static function getkaprodiD3elektronika()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',19)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )
            ->select('b.Structural_Name','c.Name','a.Sk_Num','a.Start_Date','a.End_Date','c.Nik','c.Nip','c.Nidn','c.Nbm','c.First_Title','c.Last_Title',DB::raw("(SELECT MAX(a.Sk_Date)) as Sk_Date"))
            ->get();
        return $result;
    }

    static function getkaprodiD3mesin()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',20)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )
            ->select('b.Structural_Name','c.Name','a.Sk_Num','a.Start_Date','a.End_Date','c.Nik','c.Nip','c.Nidn','c.Nbm','c.First_Title','c.Last_Title',DB::raw("(SELECT MAX(a.Sk_Date)) as Sk_Date"))
            ->get();
        return $result;
    }

    static function getkaprodiS1geologi()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',22)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )
            ->select('b.Structural_Name','c.Name','a.Sk_Num','a.Start_Date','a.End_Date','c.Nik','c.Nip','c.Nidn','c.Nbm','c.First_Title','c.Last_Title',DB::raw("(SELECT MAX(a.Sk_Date)) as Sk_Date"))
            ->get();
        return $result;
    }

    static function getkaprodiS1tambang()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',23)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )
            ->select('b.Structural_Name',
                    'c.Name','a.Sk_Num',
                    'a.Start_Date',
                    'a.End_Date',
                    'c.Nik',
                    'c.Nip',
                    'c.Nidn',
                    'c.Nbm',
                    'c.First_Title',
                    'c.Last_Title',
                    DB::raw("(SELECT MAX(a.Sk_Date)) as Sk_Date"))
            ->get();
        return $result;
    }

    static function getkaprodiS1mesin()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',24)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )
            ->select('b.Structural_Name','c.Name','a.Sk_Num','a.Start_Date','a.End_Date','c.Nik','c.Nip','c.Nidn','c.Nbm','c.First_Title','c.Last_Title',DB::raw("(SELECT MAX(a.Sk_Date)) as Sk_Date"))
            ->get();
        return $result;
    }

    static function getkaprodiS1elektro()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',25)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )
            ->get();
        return $result;
    }

    static function getkaprodiS1pwk()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',26)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )
            ->select('b.Structural_Name','c.Name','a.Sk_Num','a.Start_Date','a.End_Date','c.Nik','c.Nip','c.Nidn','c.Nbm','c.First_Title','c.Last_Title',DB::raw("(SELECT MAX(a.Sk_Date)) as Sk_Date"))
            ->get();
        return $result;
    }

    static function getkabagakademik()
    {
        $now = $now = date('Y-m-d H:m:i');
        $result = DB::table('emp_employee_structural as a')
            ->leftjoin('emp_structural as b', 'a.Structural_Id', '=', 'b.Structural_Id')
            ->join('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
            ->where('b.Structural_Id',30)
            ->where('a.Start_Date','<=',$now )
            ->where('a.End_Date','>=',$now )
            ->select('b.Structural_Name','c.Name','a.Sk_Num','a.Start_Date','a.End_Date','c.Nik','c.Nip','c.Nidn','c.Nbm','c.First_Title','c.Last_Title',DB::raw("(SELECT MAX(a.Sk_Date)) as Sk_Date"))
            ->get();
        return $result;
    }

}