<!DOCTYPE html>
<html>
<head>
  <style>
    @page { size: A4;}
    /* @page :first {  margin: 50px 60px 190px 60px;} */

    @page {margin: 140px 30px 230px 30px;}
    .page_break { page-break-after: always; }
    header { position: fixed; left: 0px; top: -140px; right: 0px; height: 30px; text-align: center; }
    footer { position: fixed; bottom: -200px; left: 0px; right: 0px; background-color: lightblue; height: 50px; }
    div.breakNow { page-break-inside:avoid; page-break-after:always; }

    .tablex {
      page-break-inside:auto
    }
    .tablex tr{
      page-break-inside:avoid; page-break-after:auto
    }

    @media print {
     .page-break  { display: block; page-break-before: always; }
    }

    p{
      font-size: 13px;
    }
  </style>

  <?php
  function tanggal_indo($tanggal, $cetak_hari = false)
  {
  	$hari = array ( 1 =>    'Senin',
  				'Selasa',
  				'Rabu',
  				'Kamis',
  				'Jumat',
  				'Sabtu',
  				'Minggu'
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
  	$split 	  = explode('-', $tanggal);
  	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

  	if ($cetak_hari) {
  		$num = date('N', strtotime($tanggal));
  		return $hari[$num] . ', ' . $tgl_indo;
  	}
  	return $tgl_indo;
  }
  ?>

</head>
<?php
$xx = 0;
$n_room = "";
foreach ($member  as $dat) {
  $n_room = $dat['data']->Room_Name;
  ?>

<body>
    <!-- <div id="header"> -->
      <header>
        <div>
          <img src="{{ ('img/header.png') }}" style="width:80%" alt="">
        </div>
        <hr>
      </header>
    <!-- </div> -->

        <table style="width:100%; font-size:13px;">
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center><b>
          <?php
          $exam_typee = $exam_type->Exam_Type_Id;
          if($exam_typee == 1){
            $smt = "";
            $th = $term->Year_Id.'/'.($term->Year_Id+1);
            if($term->Term_Id == 1){
              $smt = 'GANJIL';
            }elseif($term->Term_Id == 2){
              $smt = 'GENAP';
            }
            echo "PRESENSI UJIAN AKHIR SEMESTER PROGRAM STUDI ".$dat['data']->Acronym.' '.$dat['data']->Department_Name.' SEMESTER '.$smt.' TAHUN AKADEMIK '.$th;
          }else if($exam_typee == 2){
            $smt = "";
            $th = $term->Year_Id.'/'.($term->Year_Id+1);
            if($term->Term_Id == 1){
              $smt = 'GANJIL';
            }elseif($term->Term_Id == 2){
              $smt = 'GENAP';
            }
            echo "PRESENSI UJIAN TENGAH SEMESTER PROGRAM STUDI ".$dat['data']->Acronym.' '.$dat['data']->Department_Name.' SEMESTER '.$smt.' TAHUN AKADEMIK '.$th;
          }else if($exam_typee == 3){
            $smt = "";
            $th = $term->Year_Id.'/'.($term->Year_Id+1);
            if($term->Term_Id == 1){
              $smt = 'GANJIL';
            }elseif($term->Term_Id == 2){
              $smt = 'GENAP';
            }
            echo "PRESENSI REMIDI PROGRAM STUDI ".$dat['data']->Acronym.' '.$dat['data']->Department_Name.' SEMESTER '.$smt.' TAHUN AKADEMIK '.$th;
          }
          ?>
        </td>
        <td width=" 15%"></td>
      </tr>
    </table>

      <table  style="width:100%; font-size:12px;">
    <tr>
      <td style="width:15%;">Nama Matakuliah</td><td style="width:1%;">:</td>
      <td style="width:34%;">{{ $dat['data']->Course_Name  }}</td>
      <td style="width:15%;">Kelas</td><td style="width:1%;">:</td>
      <td style="width:34%;">{{$dat['data']->Class_Name}}</td>
    </tr>
    <tr>
      <td>Dosen Pengampu</td><td>:</td>
      <td><?php 
      $id_dosen = explode('|',$dat['data']->id_dosen);
        $dsn_matkul = [];
        $x=0;
        foreach ($id_dosen as $key) {
            if ($key != null) {
              $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
              ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
              ->select('acd_department_lecturer.Employee_Id')
              ->first();
              $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
              if($dosennya->First_Title == "" || $dosennya->First_Title == "-"){
                $firstitle = "";
              }else{
                $firstitle = $dosennya->First_Title;
              }
                $name = $dosennya->Name;
                $lasttitle = $dosennya->Last_Title;
                $name_dosen = $firstitle." ".$name." ".$lasttitle;
                $dsn_matkul[$x] = $name_dosen;
                echo $name_dosen."&nbsp;&nbsp;&nbsp;&nbsp;";
            }
          $x++;
        }
          ?></td>
      <td>T.A/Sem</td><td>:</td>
      <td>{{$th}}</td>
    </tr>
    <tr>
      <td>Waktu Mulai</td><td>:</td>
      <td>
        <?php
          $n = 0;
          if ($dat['data']->Exam_Start_Date != "") {
            $key = $dat['data']->Exam_Start_Date;
                $start = explode(" ",$key);
                $s_date = $start[0];
                $s_time = explode(":",$start[1]);
                unset($s_time[1]);
                $s_time = implode(".",$s_time);
                echo tanggal_indo($s_date,true)." / ".$s_time;
                $n++;
          } ?></td>
      <td>Ruang</td><td>:</td>
      <td>{{ $n_room }}</td>
    </tr>
    <?php
        $lnm2 = strtolower($dat['data']->Pengawas_1); $ucnm2 = ucwords($lnm2);
        $lnm3 = strtolower($dat['data']->Pengawas_2); $ucnm3 = ucwords($lnm3);
      ?>
  </table>
  <br>


  <table class="tablex" border="1px" style="border-collapse : collapse; width:100%; font-size:13px;">
    <tr>
       <th><center>No</th>
      <th><center>NIM</center></th>
      <th><center>NAMA MAHASISWA</center></th>
      <th colspan=2><center>TANDA TANGAN</center></th>
    </tr>

    <?php
    $no = 1;
    foreach ($dat['mhs']  as $data) {
      $totalpertemuan = DB::table('acd_sched_real')
        ->where('Course_Id',$dat['data']->Course_Id)
        ->where('Term_Year_Id',$term_year)
        ->where('Class_Prog_Id',$class_prog)
        ->where('Class_Id',$dat['data']->Class_Id)
        ->count();  
        $totalpertemuans = DB::table('acd_sched_real')
        ->where('Course_Id',$dat['data']->Course_Id)
        ->where('Term_Year_Id',$term_year)
        ->where('Class_Prog_Id',$class_prog)
        ->where('Class_Id',$dat['data']->Class_Id)
        ->get();  

        $Register_Number = DB::table('acd_student')->where('Student_Id',$data->Student_Id)->select('Register_Number')->first();

        $studentbill = DB::select('CALL usp_GetStudentBill(?,?,?)',array($Register_Number->Register_Number,'',''));

        if($exam_typee == 3){
          $cekbiaya = DB::table('acd_student_krs')
          ->where('Is_Remediasi',1)
          ->where('Student_Id',$data->Student_Id)
          ->get();
          $totalbiayaremidi = 0;

          $a = 1;
          foreach ($cekbiaya as $key) {
            $totalbiayaremidi = $totalbiayaremidi + $key->Amount_Rem;
            $a++;
          }

          $sudahdibayar = DB::table('fnc_student_payment')
          ->where('Register_Number',$data->Register_Number)
          ->where('Is_Remediasi',1)
          ->get();

          if($sudahdibayar->count() > 0){
            $s = 1;
            $sdh_bayar = 0;
            foreach ($sudahdibayar as $keys) {
              $sdh_bayar = $sdh_bayar + $keys->Payment_Amount;
            }
          }else{
            $sdh_bayar = 0;
          }

          if($cekbiaya->count()){
            $biaya = $sdh_bayar - $totalbiayaremidi;
          }else{
            $biaya = 'Data Not Fount';
          }
        }else{
          $i = 0;
          $ListTagihan = [];
          $total=0;
          $biaya = 0;
          if($studentbill!=null){
            foreach ($studentbill as $keyx) if($keyx->Term_Year_Bill_id == $term_year){
              $ListTagihan[$i]['Amount'] = $keyx->Amount;
              $ListTagihan[$i]['Cost_Item_Name'] = $keyx->Cost_Item_Name;
              $ListTagihan[$i]['Cost_Item_Id'] = $keyx->Cost_Item_Id;
              $i++;
            }

            $sumAmount =0;
                  foreach ($ListTagihan as $tagihan) {
                    $sumAmount += $tagihan['Amount'];
                  }
          $biaya = number_format($sumAmount,'0',',','.');
          }
        }
    ?>
     <tr>
      <td height="10px"><center>{{ $no }}</td>
      <td><center>{{ $data->Nim }}</td>
      <?php $lnm = strtolower($data->Full_Name); $ucnm = ucwords($lnm); 
        $t_p = 0;
        foreach ($totalpertemuans as $keys) {
            $total = DB::table('acd_sched_real_detail')
            ->where([['Sched_Real_Id',$keys->Sched_Real_Id],['Student_Id',$data->Student_Id]])
            ->first();
            if($total != null){
                $t_p++;
            }
        }

        if($totalpertemuan <= 0){
          $persen = 0;
        }else{
          $persen = round(($t_p/$totalpertemuan) * 100,2);
        }
      ?>
      <td>&nbsp;{{ $ucnm }} ({{$persen}} %)</td>
      <?php if( ($no % 2) == 0){ ?>
        <td height="20"></td>
        <!-- <td height="20">{{$no}} </td> -->
        <td height="20" style="<?php if($exam_typee == 1){ if($persen < 75 || $biaya > 0 ){ ?>background-color:black;color:white;<?php }else{ ?>background-color:none;<?php }} ?>">{{$no}} 
          @if($exam_typee == 1)
          @if($persen < 75)
          *
          @elseif($biaya > 0)
          **
          @elseif($biaya > 0 && $persen < 75)
          ***
          @endif
          @endif</td>
      <?php }else{ ?>
        <td height="20" style="<?php if($exam_typee == 1){ if($persen < 75 || $biaya > 0 ){ ?>background-color:black;color:white;<?php }else{ ?>background-color:none;<?php }} ?>">{{$no}} 
          @if($exam_typee == 1)
          @if($persen < 75)
          *
          @elseif($biaya > 0)
          **
          @elseif($biaya > 0 && $persen < 75)
          ***
          @endif
          @endif</td>
        <!-- <td height="20">{{$no}} </td> -->
        <td height="20"></td>
      <?php }?>
    </tr>
    <?php
    $no++;
    }
    ?>
  </table>
  <br>
  <table style="width:100%;">
    <tr>
      <td style="width:65%;"></td>
      <td style="width:35%;">
        <label for="" style="font-size:13px;">{{env('NAME_City')}}, ______________________
      </td>
    </tr>
  </table>
  <br>
  <table style="width:100%; font-size:13px;">
    <tr>
      <td style="width:33%;"><center>
        <div style="height:100px;"> Pengawas 1</div>
      </td>
      <td style="width:33%;"><center>
        <div style="height:100px;">Pengawas 2</div>
      </td>
      <td style="width:33%;"><center>
        <div style="height:100px;"> Dosen Pengawas</div>
      </td>
    </tr>
    <tr>
    <?php
    // $lnmp = strtolower($kprodi->Name); $ucnmp = ucwords($lnmp);
    ?>
      <td><center>

      </td>
      <td><center>
        
      </td>
      <td><center>
          <?php
          // $id_dosen = explode('|',$dat['data']->id_dosen);
          //   $dsn_matkul = [];
          //   $x=0;
          //   foreach ($id_dosen as $key) {
          //       if ($key != null) {
          //         $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
          //         ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
          //         ->select('acd_department_lecturer.Employee_Id')
          //         ->first();
          //         $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
          //           $firstitle = $dosennya->First_Title;
          //           $name = $dosennya->Name;
          //           $lasttitle = $dosennya->Last_Title;
          //           $name_dosen = $firstitle." ".$name." ".$lasttitle;
          //           $dsn_matkul[$x] = $name_dosen;
          //           echo $name_dosen."&nbsp;&nbsp;&nbsp;&nbsp;";
          //       }
          //     $x++;
          //   }
          ?>
      </td>
    </tr>
  </table>
  @if($exam_typee == 1)
    * &nbsp;&nbsp;&nbsp;&nbsp;Presensi Kurang Dari 75%<br>
    ** &nbsp;&nbsp;Masih ada tagihan<br>
    *** Presensi Kurang Dari 75% dan  Masih ada tagihan
  @endif
  <!-- <div class="page_break"></div> -->
</body>
  <?php
  $xx++;
    }
    ?>
</html>
