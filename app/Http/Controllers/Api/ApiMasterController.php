<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;
use Auth;

class ApiMasterController extends Controller
{
    //all
    public function getEntryYearController(Request $request)
    {
        try {
            $data = db::table('mstr_entry_year')->orderby('Entry_Year_Id','desc')->get();
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

    public function getAchievementLevel(Request $request)
    {
        try {
            $data = db::table('mstr_achievement_level')->get();
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
