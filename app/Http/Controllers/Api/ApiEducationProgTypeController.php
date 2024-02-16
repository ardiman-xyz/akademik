<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;
use Auth;

class ApiEducationProgTypeController extends Controller
{
    //all
    public function getEducationProgTypeController(Request $request)
    {
        try {
            $data = DB::table('mstr_education_program_type')
            ->orderBy('mstr_education_program_type.Education_Prog_Type_Code', 'asc')
            ->get();
            return response()->json([
                "success" => true,
                "data" => $data,
                "total" => count($data),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => $e
            ], 200);
        }
    }

    //post
    public function postEducationProgTypeController(Request $request)
    {
        // dd($request->data);
        try {
            if($request->data['Education_Prog_Type_Id'] == null){
                $insert = DB::table('mstr_education_program_type')
                ->insert([
                    'Education_Prog_Type_Code' => $request->data['Education_Prog_Type_Code'],
                    'Program_Name' => $request->data['Program_Name'],
                    'Program_Name_Eng' => $request->data['Program_Name_Eng'],
                    'Acronym' => $request->data['Acronym'],
                    'Study_Period_Semester' => $request->data['Study_Period_Semester'],
                    'Created_By' => Auth::user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil menambah data',
                    "total" => 1,
                ], 200);
            }else{
                $insert = DB::table('mstr_education_program_type')
                ->where('Education_Prog_Type_Id',$request->data['Education_Prog_Type_Id'])
                ->update([
                    'Education_Prog_Type_Code' => $request->data['Education_Prog_Type_Code'],
                    'Program_Name' => $request->data['Program_Name'],
                    'Program_Name_Eng' => $request->data['Program_Name_Eng'],
                    'Acronym' => $request->data['Acronym'],
                    'Study_Period_Semester' => $request->data['Study_Period_Semester'],
                    'Modified_By' => Auth::user()->email,
                    'Modified_Date' => date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil mengubah data',
                    "total" => 1,
                ], 200);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menambah data',
                "message" => $e
            ], 200);
        }
    }

    //delete
    public function deleteEducationProgTypeController(Request $request)
    {
        try {
            $insert = DB::table('mstr_education_program_type')
            ->where('Education_Prog_Type_Id',$request->data)
            ->delete();
            return response()->json([
                "success" => true,
                "data" => 'Berhasil menghapus data',
                "total" => 1,
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menghapus data',
                "message" => $e
            ], 200);
        }
    }
}
