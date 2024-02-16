<?php
namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class webSetting {
    public static function announcement() {
        $setting = DB::table('acd_annoucement')
        ->where([
            ['Department_Id',null],
            ['Post_End_Date','>=',date('Y-m-d',strtotime(now()))]
        ])
        ->orderBy('Post_End_Date','asc')
        ->get();

        $data = [];
        $i = 0;
        foreach ($setting as $key) {
            if($key->Penerima != null){
                $explodes = explode(';',$key->Penerima);
            }
            if(in_array('Admin',$explodes)){
                $data[$i]['Announcement_Id'] = $key->Announcement_Id;
                $data[$i]['Department_Id'] = $key->Department_Id;
                $data[$i]['Announcement_Name'] = substr($key->Announcement_Name,0,100);
                $data[$i]['Message'] = substr($key->Message,0,100);
                $data[$i]['Post_Start_Date'] = $key->Post_Start_Date;
                $data[$i]['Post_End_Date'] = $key->Post_End_Date;
                // $data[$i]['File_Upload'] = ($key->File_Upload != null ? 'storage/'.$key->File_Upload:"");
                $data[$i]['File_Upload'] = "getfile?name=".$key->File_Upload;
                $data[$i]['Penerima'] = 'Admin';
                $i++;
            }
        }
        header('Content-type: application/json');

        echo json_encode($data);
    }
}