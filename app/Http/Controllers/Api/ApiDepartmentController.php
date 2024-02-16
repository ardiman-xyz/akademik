<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;
use Auth;

class ApiDepartmentController extends Controller
{
    //all
    public function getDepartmentController(Request $request)
    {
        try {            
            $Faculty_Id=Auth::user()->Faculty_Id;
            $Department_Id = Auth::user()->Department_Id;
            // dd(Auth::user());
            $data = DB::table('mstr_department')
            ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
            ->orderBy('mstr_department.Department_Id', 'asc');
            if($Faculty_Id == null){
                if(!isset($request->Faculty_Id)){
                    $data = $data->where('Faculty_Id','!=',null)->get();
                }else{
                    $data = $data->where([['Faculty_Id',$request->Faculty_Id],['Faculty_Id','!=',null]])->get();
                    // dd($data);
                }
            }else{
                if($Department_Id == null){
                    $data = $data->where([['Faculty_Id',$Faculty_Id],['Faculty_Id','!=',null]])->get();
                }else{
                    $data = $data->where([['Faculty_Id',$Faculty_Id],['Department_Id','like','%'.$Department_Id.'%'],['Faculty_Id','!=',null]])->get();
                }
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

    //post
    public function postDepartmentController(Request $request)
    {
        // dd($request->all());
        try {
            if($request->data['Department_Id'] == null){
                $insert = DB::table('mstr_department')
                ->insert([
                    'Faculty_Id' => $request->data['Faculty_Id'],
                    'Department_Code' => $request->data['Department_Code'],
                    'Education_Prog_Type_Id' => $request->data['Education_Prog_Type_Id'],
                    'Department_Name' => $request->data['Department_Name'],
                    'Department_Name_Eng' => $request->data['Department_Name_Eng'],
                    'Department_Acronym' => $request->data['Department_Acronym'],
                    'Department_Dikti_Sk_Number' => $request->data['Department_Dikti_Sk_Number'],
                    'Department_Dikti_Sk_Date' => $request->data['Department_Dikti_Sk_Date'],
                    'Nim_Code' => $request->data['Nim_Code'],
                    'First_Title' => $request->data['First_Title'],
                    'Last_Title' => $request->data['Last_Title'],
                    'Created_By' => Auth::user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil menambah data',
                    "total" => 1,
                ], 200);
            }else{
                $insert = DB::table('mstr_department')
                ->where('Department_Id',$request->data['Department_Id'])
                ->update([
                    'Department_Code' => $request->data['Department_Code'],
                    'Education_Prog_Type_Id' => $request->data['Education_Prog_Type_Id'],
                    'Department_Name' => $request->data['Department_Name'],
                    'Department_Name_Eng' => $request->data['Department_Name_Eng'],
                    'Department_Acronym' => $request->data['Department_Acronym'],
                    'Department_Dikti_Sk_Number' => $request->data['Department_Dikti_Sk_Number'],
                    'Department_Dikti_Sk_Date' => $request->data['Department_Dikti_Sk_Date'],
                    'Nim_Code' => $request->data['Nim_Code'],
                    'First_Title' => $request->data['First_Title'],
                    'Last_Title' => $request->data['Last_Title'],
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
    public function deleteDepartmentController(Request $request)
    {
        try {
            $insert = DB::table('mstr_department')
            ->where('Department_Id',$request->data)
            ->delete();
            return response()->json([
                "success" => true,
                "data" => 'Berhasil menghapus data',
                "total" => 1,
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menghapus data/ Data masih digunakan',
                "message" => $e
            ], 200);
        }
    }
}
