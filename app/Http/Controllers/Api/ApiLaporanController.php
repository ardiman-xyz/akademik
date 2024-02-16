<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;
use Auth;

class ApiLaporanController extends Controller
{
    //all
    public function getStudentSertifikat(Request $request)
    {
        try {
            // dd($request->all());
            // "Department_Id" => "44"
            // "Entry_Year_Id" => "2021"
            // "Achievement_Level_Id" => "1"
            $data = db::table('acd_student_achievement')
            ->join('acd_student','acd_student_achievement.Student_Id','=','acd_student.Student_Id')
            ->join('mstr_achievement_level','mstr_achievement_level.Achievement_Level_Id','=','acd_student_achievement.Achievement_Level_Id')
            ->select(
                'acd_student_achievement.Student_Id',
                'acd_student.Full_Name',
                'acd_student.Nim',
                'acd_student_achievement.Achievement_Level_Id',
            )
            ->where([['acd_student.Department_Id',$request->Department_Id],['acd_student.Entry_Year_Id',$request->Entry_Year_Id]]);

            if($request->Achievement_Level_Id == null){
                $data = $data->groupby('acd_student_achievement.Student_Id')->get();
            }else{
                $data = $data->where('acd_student_achievement.Achievement_Level_Id',$request->Achievement_Level_Id)
                ->groupby('acd_student_achievement.Student_Id')->get();
            }
            
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

    public function getSertifikat(Request $request)
    {
        try {
            // dd($request->all());
            // "Student_Id" => "44"
            $data = db::table('acd_student_achievement')
            ->join('acd_student','acd_student_achievement.Student_Id','=','acd_student.Student_Id')
            ->join('mstr_achievement_level','mstr_achievement_level.Achievement_Level_Id','=','acd_student_achievement.Achievement_Level_Id')
            ->select(
                'acd_student_achievement.Student_Achievement_Id',
                'acd_student_achievement.Student_Id',
                'acd_student_achievement.Achievement_Name',
                'acd_student_achievement.Description',
                'acd_student_achievement.Achievement_Date',
                'acd_student_achievement.Achievement_Level_Id',
                'acd_student.Full_Name',
                'acd_student.Nim',
                'acd_student.Class_Prog_Id',
                'mstr_achievement_level.Description as Level_Name'
            )
            ->where([['acd_student_achievement.Student_Id',$request->Student_Id]])
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

    public function getUploadSertifikat(Request $request)
    {
        try {
            // dd($request->all());
            // "Student_Id" => "44"
            $data = db::table('acd_student_achievement_document as dc')
            ->join('mstr_achievement_document_type as tp','dc.Achievement_Document_Type_Id','=','tp.Achievement_Document_Type_Id')
            ->join('acd_student_achievement as ac','ac.Student_Achievement_Id','=','dc.Student_Achievement_Id')
            ->where('dc.Student_Achievement_Id',$request->Student_Achievement_Id)
            ->select(
                'dc.Student_Achievement_Document_Id',
                'dc.Student_Achievement_Id',
                'dc.Achievement_Document_Type_Id',
                'dc.Document_Upload',
                'tp.Description as Type',
                'ac.Student_Id'
            )
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
}
