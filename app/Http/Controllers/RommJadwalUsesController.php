<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use Storage;
use Auth;
use Image;
use File;
use Excel;

class RommJadwalUsesController extends Controller
{
  public function get_all(Request $request)
  {
    $jam = date('H:i');
    $jam_awal = "06:00";
    $jam_akhir = "20:00";
    $range = false;
    $date = strtotime(now());
    $date = Date('N',$date);
    $mstr_day = DB::table('mstr_day')
    ->where('Crtb_Name',$date)
    ->select('Day_Id')
    ->first();

    $all_rooms = DB::table('mstr_room as mr')
    // ->where('Room_Id',195)
    // ->where('Building_Id',9)
    ->get();
    $new_array = [];
    $i = 0;
    foreach ($all_rooms as $room) {
      $datas_hariini = DB::table('acd_sched_session as ss')
      ->join('acd_offered_course_sched as sc','ss.Sched_Session_Id','=','sc.Sched_Session_Id')
      ->join('acd_offered_course as ofc','sc.Offered_Course_id','=','ofc.Offered_Course_id')
      ->join('acd_course as ac','ofc.Course_Id','=','ac.Course_Id');

      if($range == false){
        //jam sekarang
        $datas_hariini = $datas_hariini->where([
          ['Room_Id',$room->Room_Id],
          ['Day_Id',$mstr_day->Day_Id],
          ['Time_Start','<=',$jam],
          ['Time_End','>=',$jam],
        ]);
      }else{
        //range jam
        $datas_hariini = $datas_hariini->where([
          ['Room_Id',$room->Room_Id],
          ['Day_Id',$mstr_day->Day_Id],
          ['Time_Start','>=',$jam_awal],
          ['Time_End','<=',$jam_akhir],
        ]);
      }

      $datas_hariini = $datas_hariini
      ->orderBy('Day_Id','asc')
      ->orderBy('Time_Start','asc')
      ->get()
      ->toArray();
      $new_array[$i]['Room_Id'] = $room->Room_Id;
      $new_array[$i]['Room_Code'] = $room->Room_Code;
      $new_array[$i]['Room_Name'] = $room->Room_Name;
      $new_array[$i]['Description'] = $room->Description;
      $new_array[$i]['Building_Id'] = $room->Building_Id;
      $new_array[$i]['jadwal'] = $datas_hariini;
      $i++;
    }
    dd($new_array);
  }

  function tanggal_indo($tanggal, $cetak_hari = false, $only_hari = false)
  {
    $hari = array ( 1 =>    'Minggu',
          'Senin',
          'Selasa',
          'Rabu',
          'Kamis',
          'Jumat',
          'Sabtu'
        );

    $bulan = array (1 =>   'Januari',
          'Februari',
          'Maret',
          'April',
          'Mei',
          'Juni',
          'Juli',
          'Agustus',
          'September',
          'Oktober',
          'November',
          'Desember'
        );
    $split    = explode('-', $tanggal);
    $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

    if ($cetak_hari) {
      $num = date('N', strtotime($tanggal));
      return $hari[$num] . ', ' . $tgl_indo;
    }
    if ($only_hari) {
      $num = date('N', strtotime($tanggal));
      $show = $hari[$num+1];
      return $show;
    }
    return $tgl_indo;
  }
}
