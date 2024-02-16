<?php

namespace App;

use DB;
use App\User;
use Session;
use Auth;

class GetDepartment
{
    public static function getDepartment()
    {
        $Faculty_Id=Auth::user()->Faculty_Id;
        $Department_Id = Auth::user()->Department_Id;

        $data = DB::table('mstr_department')
        ->join('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
        ->orderBy('mstr_department.Department_Id', 'asc')
        ->selectraw("concat(Department_Name) as Department_Name,Department_Id");
        // dd($data->get(),Auth::user());
        if($Faculty_Id == null){
            $data = $data->where([['Faculty_Id','!=',null]])->get();
        }else{
            if($Department_Id == null){
                $data = $data->where([['Faculty_Id',$Faculty_Id],['Faculty_Id','!=',null]])->get();
            }else{
                $data = $data->where([['Faculty_Id',$Faculty_Id],['Department_Id',$Department_Id],['Faculty_Id','!=',null]])->get();
            }
        }


        // $new_data = [];
        // $p = 0;
        // foreach ($data as $key) {
        //     $new_data[$p]['Department_Id'] = $key->Department_Id;
        //     $new_data[$p]['Department_Name'] = $key->Department_Name.' '.$key->Department_Acronym;
        //     $p++;
        // }
        return $data;        
    }

    public static function forWhereDepartment()
    {
        $dpt = [];
        $p = 0;
        foreach (GetDepartment::getDepartment() as $key) {
            $dpt[$p] = $key->Department_Id;
            $p++;
        }
        return $dpt;
    }
}
