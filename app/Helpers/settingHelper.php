<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class settingHelper {
    public static function apostrophe($search) {
        $exp = explode("'",$search);
        if(count($exp)>0){
            $search="";
            for ($i=0; $i < count($exp); $i++) { 
                if($i==0){
                $search = $exp[$i];
                }else {
                $search = $search . "\\" . $exp[$i]; 
                }
            }
        }
        return $search;
    }
}