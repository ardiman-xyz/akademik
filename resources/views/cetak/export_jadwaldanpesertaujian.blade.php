<!DOCTYPE html>
<html>
<head>
  <style>
    /* @page { margin: 180px 60px 190px 60px; } */
    @page { size: A4; }
    @page :first { size: A4; margin: 50px 60px 190px 60px;}
    #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 10px; }

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

  <div id="header">
                    <div>
                            <img src="{{ ('img/header.png') }}" style="width:100%" alt="">
                    </div>
                    <hr>
              </div>
</head>
<body>

  <table style="width:100%; font-size:15px;">
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center><b>
          <?php
          $exam_typee = $exam_type->Exam_Type_Id;
          if($exam_typee == 1){
            echo "JADWAL DAN PESERTA UJIAN AKHIR SEMESTER";
          }else{
            echo "JADWAL DAN PESERTA UJIAN TENGAH SEMESTER";
          }
          ?>
        </td>
        <td width=" 15%"></td>
      </tr>
    </table>
  <table  style="width:100%; font-size:12px;">
    <tr>
      <td style="width:15%;">Program Studi</td><td style="width:1%;">:</td>
      <td style="width:34%;">{{ $data->Department_Name }}</td>
      <td style="width:15%;">Kelompok</td><td style="width:1%;">:</td>
      <td style="width:34%;">{{ $data->Room_Number }}</td>
    </tr>
    <tr>
      <td>Kode Matakuliah</td><td>:</td>
      <td>{{ $data->Course_Code }}</td>
      <td>Ruangan</td><td>:</td>
      <td>{{ $data->Room_Name }}</td>
    </tr>
    <tr>
      <td>Nama Matakuliah</td><td>:</td>
      <td>{{ $data->Course_Name }}</td>
      <td>Waktu Mulai</td><td>:</td>
      <td>{{ $data->Exam_Start_Date }}</td>
    </tr>
    <?php 
        $lnm1 = strtolower($data->Dosen); $ucnm1 = ucwords($lnm1); 
        $lnm2 = strtolower($data->Pengawas_1); $ucnm2 = ucwords($lnm2); 
        $lnm3 = strtolower($data->Pengawas_2); $ucnm3 = ucwords($lnm3); 
      ?>
    <!-- <tr>
      <td>Pengawas 1</td><td>:</td>
      <td>{{ $data->Pengawas_1F }} {{ $ucnm2 }} {{ $data->Pengawas_1L }}</td>
      <td>Waktu Selesai</td><td>:</td>
      <td>{{ $data->Exam_End_Date }}</td>
    </tr> -->
    <!-- <tr>
      <td>Pengawas 2</td><td>:</td>
      <td>{{ $data->Pengawas_2F }} {{ $ucnm3 }} {{ $data->Pengawas_2L }}</td>
    </tr> -->
  </table>
    <br>
  <table border="1px" style="border-collapse : collapse; width:100%; font-size:13px;" class="isi">
    <tr>
      <th><center>No</th>
      <th><center>NIM</center></th>
      <th><center>NAMA MAHASISWA</center></th>
      <th colspan=2><center>TANDA TANGAN</center></th>
    </tr>

    <?php
    $no = 1;
    foreach ($member  as $dat) {
    ?>
    <tr>
      <td height="10px"><center>{{ $no }}</td>
      <td><center>{{ $dat->Nim }}</td>
      <?php $lnm = strtolower($dat->Full_Name); $ucnm = ucwords($lnm); ?>
      <td>{{ $ucnm }}</td>
      <?php if( ($no % 2) == 0){ ?> 
        <td height="20"></td>
        <td height="20">{{$no}}</td>
      <?php }else{ ?>        
        <td height="20">{{$no}}</td>
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
      <td style="width:55%;"></td>
      <td style="width:45%;">
        <label for="" style="font-size:13px;">{{env('NAME_City')}}, ______________________
      </td>
    </tr>
  </table>
  <br>
  <table  style="width:100%; font-size:13px;">
    <tr>
      <td style="width:33%;">
        <div style="height:80px;"> Pengawas 1 </div>

        <hr style="width:80%; margin-left:0%;">
      </td>
      <td style="width:33%;">
        <div style="height:80px;"> Pengawas 2 </div>

        <hr style="width:80%; margin-left:0%;">
      </td>
      <td style="width:33%;">
        <div style="height:80px;"> Dosen Matakuliah </div>

        <hr style="width:80%; margin-left:0%;">
      </td>
    </tr>
    <tr>
      <?php 
        $lnm1 = strtolower($data->Dosen); $ucnm1 = ucwords($lnm1); 
        $lnm2 = strtolower($data->Pengawas_1); $ucnm2 = ucwords($lnm2); 
        $lnm3 = strtolower($data->Pengawas_2); $ucnm3 = ucwords($lnm3); 
      ?>
      <td><label for="">{{ $data->Pengawas_1F }} {{ $ucnm2 }} {{ $data->Pengawas_1L }}</label></td>
      <td><label for="">{{ $data->Pengawas_2F }} {{ $ucnm3 }} {{ $data->Pengawas_2L }}</label></td>
      <td><label for="">{{ $data->DosenF }} {{ $ucnm1 }} {{ $data->DosenL }}</label></td>
    </tr>
  </table>
</body>
</html>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Export Matakuliah</title>
  </head>
  <body>
    <div>
      <div  style="float:left; margin-right:10px;">
        <img src="{{ ('img/logo_univ.png') }}" style="width:70px;" alt="">
      </div>
      <div style="font-size:15px;">
        <label for="">{{env('NAME_MAJELIS')}}</label><br>
        <label>{{env('NAME_UNIV')}}</label>
      </div>
    </div>
    <br>

    <center><h4>JADWAL DAN PESERTA UJIAN AKHIR SEMESTER</h4></center>
    <br>


  </body>
</html>
