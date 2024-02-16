<!DOCTYPE html>
<html>
<head>
  <style>
    /* @page { margin: 180px 60px 190px 60px; } */
    /* @page :first {  margin: 50px 60px 190px 60px;} */
    .page_break { page-break-after: always; }
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

  <?php
    foreach ($member  as $dat) {
  ?>
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

  <br><br>

  <table  style="width:100%; font-size:12px;">
    <tr>
      <td style="width:15%;">Program Studi</td><td style="width:1%;">:</td>
      <td style="width:34%;">{{ $dat['data']->Department_Name }}</td>
      <td style="width:15%;">Kelompok</td><td style="width:1%;">:</td>
      <td style="width:34%;">{{ $dat['data']->Room_Number }}</td>
    </tr>
    <tr>
      <td>Kode Matakuliah</td><td>:</td>
      <td>{{ $dat['data']->Course_Code  }}</td>
      <td>Ruang</td><td>:</td>
      <td>{{ $dat['data']->Room_Name  }}</td>
    </tr>
    <tr>
      <td>Nama Matakuliah</td><td>:</td>
      <td>{{ $dat['data']->Course_Name  }}</td>
      <td>Waktu Mulai</td><td>:</td>
      <td>{{ $dat['data']->Exam_Start_Date  }}</td>
    </tr>
    <?php 
        $lnm2 = strtolower($dat['data']->Pengawas_1); $ucnm2 = ucwords($lnm2); 
        $lnm3 = strtolower($dat['data']->Pengawas_2); $ucnm3 = ucwords($lnm3); 
      ?>
    <tr>
      <td>Pengawas 1</td><td>:</td>
      <td>{{ $dat['data']->Pengawas_1F }} {{ $ucnm2 }} {{ $dat['data']->Pengawas_1L }}</td>
      <td>Waktu Selesai</td><td>:</td>
      <td>{{ $dat['data']->Exam_End_Date }}</td>
    </tr>
    <tr>
      <td>Pengawas 2</td><td>:</td>
      <td>{{ $dat['data']->Pengawas_2F }} {{ $ucnm3 }} {{ $dat['data']->Pengawas_2L }}</td>
    </tr>
  </table>
    <br>
  <table border="1px" style="border-collapse : collapse; width:100%; font-size:13px;">
    <tr>
      <th><center>No</th>
      <th><center>NIM</center></th>
      <th><center>NAMA MAHASISWA</center></th>
      <th><center>TANDA TANGAN</center></th>
      <th><center>KETERANGAN</center></th>
    </tr>

    <?php
    $no = 1;
    foreach ($dat['mhs']  as $data) {
    ?>

    <tr>
      <td>{{ $no }}</td>
      <td>{{ $data->Nim }}</td>
      <td>{{ $data->Full_Name }}</td>
      <td></td>
      <td></td>
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
        <label for="" style="font-size:13px;">{{env('NAME_City')}}, </label><hr style="width:80%; margin-right:0%;">
      </td>
    </tr>
  </table>
  <br>
  <table style="width:100%; font-size:13px;">
    <tr>
      <td style="width:33%;"><center>
        <div style="height:80px;"> Pengawas 1 </div>
      </td>
      <td style="width:33%;"><center>
        <div style="height:80px;"> Pengawas 2 </div>
      </td>
      <td style="width:33%;"><center>
        <div style="height:80px;"> Dosen Matakuliah </div>
      </td>
    </tr>
    <tr>
      <td><center>{{ $dat['data']->Pengawas_1F }} {{ $ucnm2 }} {{ $dat['data']->Pengawas_1L }}</td>
      <td><center>{{ $dat['data']->Pengawas_2F }} {{ $ucnm3 }} {{ $dat['data']->Pengawas_2L }}</td>
      <td><center>
          <?php
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
                    $firstitle = $dosennya->First_Title;
                    $name = $dosennya->Name;
                    $lasttitle = $dosennya->Last_Title;
                    $name_dosen = $firstitle." ".$name." ".$lasttitle;
                    $dsn_matkul[$x] = $name_dosen;
                    echo $name_dosen."&nbsp;&nbsp;&nbsp;&nbsp;";
                }
              $x++;
            }
          ?>
      </td>
    </tr>
  </table>
  <div class="page_break"></div>
</body>
  <?php
    }
    ?>
</html>

