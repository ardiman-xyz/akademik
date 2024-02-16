<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;
use Auth;

class ApiFacultyController extends Controller
{
    //all
    public function getFacultyController(Request $request)
    {
        try {
            $Faculty_Id=Auth::user()->Faculty_Id;
            $data = DB::table('mstr_faculty')
            ->orderBy('mstr_faculty.Order_Id', 'asc');
            if($Faculty_Id == null){
                $data = $data->get();
            }else{
                $data = $data->where([['Faculty_Id','like','%'.$Faculty_Id.'%']])->get();                
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
    public function postFacultyController(Request $request)
    {
        try {
            if($request->data['Faculty_Id'] == null){
                $insert = DB::table('mstr_faculty')
                ->insert([
                    'Faculty_Code' => $request->data['Faculty_Code'],
                    'Faculty_Name' => $request->data['Faculty_Name'],
                    'Faculty_Name_Eng' => $request->data['Faculty_Name_Eng'],
                    'Faculty_Acronym' => $request->data['Faculty_Acronym'],
                    'Order_Id' => $request->data['Order_Id'],
                    'Created_By' => Auth::user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')
                ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil menambah data',
                    "total" => 1,
                ], 200);
            }else{
                $insert = DB::table('mstr_faculty')
                ->where('Faculty_Id',$request->data['Faculty_Id'])
                ->update([
                    'Faculty_Code' => $request->data['Faculty_Code'],
                    'Faculty_Name' => $request->data['Faculty_Name'],
                    'Faculty_Name_Eng' => $request->data['Faculty_Name_Eng'],
                    'Faculty_Acronym' => $request->data['Faculty_Acronym'],
                    'Order_Id' => $request->data['Order_Id'],
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
    public function deleteFacultyController(Request $request)
    {
        try {
            $insert = DB::table('mstr_faculty')
            ->where('Faculty_Id',$request->data)
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
