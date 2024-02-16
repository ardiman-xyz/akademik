<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>KRS Mahasiswa</title>
    <style>
      body{
        font-family: Arial, Helvetica, serif ;
      }
    </style>
  </head>
  <body>
    <style media="screen">
      .fa-sum:bofore{
        content: "\03a3";
        font-family: sans-serif;
      }
      .tdtengah {
        vertical-align: center;
      }
    </style>

    <div>
     <div id="header">
       <table>
        <tr>
          <td>
            <img src="http://akademik-umkendari.utc-umy.id/img/logo_univ.png" style="width:7%" alt="">
          </td>
          <td></td>
          <td>
            <label class="col-md-8" class="vertical-align: text-top;" style="font-family: Arial, Helvetica, serif ;"><b>UNIVERSITAS MUHAMMADIYAH KENDARI <br>KARTU RENCANA STUDI</label>
          </td>
        </tr>
       </table>
    </div>

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
  <br>
    <table style="width:100%; font-size:12px;">
      <tr>
        <td style="width:24%;">Tahun Akademik</td>
        <td style="width:1%;">:</td>
        <td style="width:25%;"><?php echo $term_years->Term_Year_Id; ?></td>
        <td style="width:24%;"></td>
        <td style="width:1%;"></td>
        <td style="width:25%;"></td>
      </tr>
      <tr>
        <td >Program</td>
        <td style="width:1%;">:</td>
        <td ><?php echo $student_data->Class_Program_Name ?></td>
        <td >SKS Maksimum</td>
        <td style="width:1%;">:</td>
        <td ><?php echo (isset($sksmax) == true ? '':$sksmax[0]->AllowedSKS); ?></td>
      </tr>
      <tr>
        <td >Semester</td><td style="width:1%;">:</td>
        <td ><?php echo $smt ?></td>
        <td >IP Kumulatif</td><td style="width:1%;">:</td>
        <td ><?php if($smt == 1){echo ''; }else{ echo (round($ipk,2)); } ?></td>
      </tr>
      <tr>
        <td >Nama Mahasiswa</td>
        <td style="width:1%;">:</td>
        <td ><?php echo $student_data->Full_Name ?></td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr>
      <tr>
        <td >Nim</td>
        <td style="width:1%;">:</td>
        <td ><?php echo $student_data->Nim ?></td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr>
      <tr>
        <td >Program Studi</td>
        <td style="width:1%;">:</td>
        <td ><?php echo $student_data->Department_Name ?></td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr>
    </table>

    <br>
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:12px;">
      <tr>
        <th style="width:4%;" rowspan="2"><center>No</center></th>
        <th style="width:10%;" rowspan="2"><center>Kode MK</center></th>
        <th style="width:30%;" rowspan="2"><center>Mata Kuliah</center></th>
        <th style="width:5%;" rowspan="2"><center>SMT</center></th>
        <th style="width:5%;" rowspan="2"><center>SKS</center></th>
        <th style="width:8%;" rowspan="2"><center>Hari</center></th>
        <th style="width:15%;" rowspan="2"><center>Ruang</center></th>
        <th style="width:20%;" rowspan="2"><center>Jadwal</center></th>
      </tr>
      <tr>
      </tr>

      <?php
      $no = 1;
      $jml_sks = 0;
      foreach ($data  as $data) {
        $jml_sks = $jml_sks + $data['Applied_Sks'];
      ?>

      <tr>
        <td  style="height:2px;"><center><?php echo $no ?></td>
        <td  style="height:2px;"><?php echo ' '.$data['Course_Code'] ?></td>
        <td  style="height:2px;"><?php echo ' '.$data['Course_Name']  ?></td>    
        <td  style="height:2px;"><center><?php echo $data['smt']  ?></td>                
        <td  style="height:2px;"><center><?php echo number_format($data['Applied_Sks']) ?></td>    
        <td class="tdtengah"><center>
          <?php
            $jadwal = explode('|',$data['jadwal']);
            $day = explode('|',$data['day_id']);
            $n = 0;
            if ($data['jadwal'] != "") {
            $days = '';
            foreach ($jadwal as $key) {
              $name_day = DB::table('mstr_day')->where('Day_Id',$day[$n])->first();
              if($days == $day[$n]){
                // echo "<div class='btn btn-sm' cursor:default; margin:1px;'>&nbsp;</div>";
              }else{
                echo "<div class='btn btn-sm' cursor:default; margin:1px;'>".$name_day->Day_Name."</div>";
                $days = $day[$n];
              }
                $n++;
              }
            }
          ?>
        </td>
        <td><center>
          <?php
            $jadwal = explode('|',$data['jadwal']);
            $room = explode('|',$data['ruang']);
            $n = 0;
            if ($data['jadwal'] != "") {
            $ruangan = "";
            foreach ($jadwal as $key) {
                if($ruangan == $room[$n]){
                  // echo "<div class='btn btn-sm' cursor:default; margin:1px;'>&nbsp;</div>";
                }else{
                  echo "<div class='btn btn-sm' cursor:default; margin:1px;'>".$room[$n]."</div>";
                  $ruangan = $room[$n];
                }
                $n++;
              }
            }
          ?>
        </td>
        <td><center>
          <?php
            $jadwal = explode('|',$data['jadwal']);
            $room = explode('|',$data['ruang']);
            $ssi = explode('|',$data['ssi']);
            $n = 0;
            if ($data['jadwal'] != "") {
            $start = "";
            $end = "";
            $days = '';
            $cekjadwal = DB::table('acd_sched_session')->wherein('Sched_Session_Id',$ssi)->select('Sched_Session_Id')->orderby('Time_Start','asc')->get();
            // dd($cekjadwal);
            foreach ($cekjadwal as $key) {
              $sesi = DB::table('acd_sched_session')->where('Sched_Session_Id',$key->Sched_Session_Id)->first();
              $sesic = DB::table('acd_sched_session')->where('Sched_Session_Id',$sesi->Day_Id)->count();
              if($days ==  $sesi->Day_Id){
                $end = $sesi->Time_End;
              }else{
                $days = $sesi->Day_Id;
                $start = $sesi->Time_Start;
                $end = $sesi->Time_End;
              }
                $n++;
              }
              // dd($end);
              echo "<div class='btn btn-sm' cursor:default; margin:1px;'>".$start."-".$end."</div>";
            }
          ?>
        </td>
      </tr>
      <?php
      $no++;
      }
      ?>      
      <!-- <tr>
        <td colspan="4"></td>
        <td><center><?php echo $jml_sks ?></td>
        <td colspan="3"></td>
      </tr> -->
    </table>
    <br>
    <table style="width:100%; text-align:left" style="font-size:13px;">
      <tr>
        <td style="width:30%; font-size:13px;"></td>
        <td style="width:30%; font-size:13px;"></td>
        <td style="width:30%;">
          <?php 
            $now = date('y-m-d');
            $date = strtotime($now);
            $tgl_terbit = date('Y-m-d', $date);
          ?>
          <label for="" style="font-size:13px;"><?php echo env('NAME_City') ?>, <?php echo tanggal_indo($tgl_terbit,false) ?></label><br>
        </td>
      </tr>
      <tr>
        <td style="font-size:13px;">Mengetahui:</td>
        <td style="font-size:13px;">Menyetujui:</td>
        <td></td>
      </tr>
      <tr>
        <td style="font-size:13px;">Ketua Program Studi </td>
        <td style="font-size:13px;">Dosen</td>
        <td style="font-size:13px;">Mahasiswa</td>
      </tr>
      <tr>
        <td style="font-size:13px;"><b><?php echo $student_data->Department_Name; ?>, </td>
        <td style="font-size:13px;"><b>Pembimbing Akademik,</td>
        <td style="font-size:13px;"></td>
      </tr>
      <tr>
        <td height="50pt"></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td style="font-size:13px;"><b><?php echo $functional_name; ?></b><br>NIDN. <?php echo $functional_nidn ?></td>
        <td style="font-size:13px;"><b>
          <?php
            if($dosenpa == null){
              echo "" ;
            }else{
              echo ($dosenpa->First_Title == null ? '':$dosenpa->First_Title.'. ').$dosenpa->Name.($dosenpa->Last_Title == null ? '':', '.$dosenpa->Last_Title);
            }
          ?></b><br>NIDN. <?php echo ($dosenpa == null ? '':$dosenpa->Nidn) ?>
        </td>
        <td style="font-size:13px;"><b><?php echo $student_data->Full_Name ?><br>&nbsp;</td>
      </tr>
    </table>
    <br>

    </table>
  </body>
</html>