<!DOCTYPE html>
<html>
<head>
<style type="text/css">
@page {  size: 13.99in 8.27in; margin: 30px 50px 110px 50px;}
#kiri
{
  -webkit-column-count: 3;
    -moz-column-count: 3;
    column-count: 3;
}
.tulisan{
  font-size: 12px;
}
.footer {
  position: fixed;
  left: 0;
  bottom: 150;
  width: 100%;
  text-align: center;
}
</style>
</head>
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
<body>
  <div id="header">
      <div id="header">
       <table>
        <tr>
          <td>
      <img src="{{public_path('/img/header.png')}}" style="width:100%" alt="">
          </td>
          <td></td>
          <td>
            <!-- <label class="col-md-8" class="vertical-align: text-top;" style="font-family: Arial, Helvetica, serif ;"><b>UNIVERSITAS MUHAMMADIYAH KENDARI <br>KARTU HASIL STUDI</b></label> -->
          </td>
        </tr>
       </table>
    </div>
    </div>
  <div>
  <hr>
  <?php 
    $low=strtolower($data_presensi['Department_Name']); 
    $uc = ucwords($low);

    $genap = strtoupper($data_presensi['Term_Year_gg']);
    $semester = strtoupper($data_presensi['Term_Year_Name']);
  ?>
  <center>LAPORAN PRESENSI PERKULIAHAN</center>
  <br>
    <table  style="width:100%; font-size:14px;">
      <tr>
        <td>Mata Kuliah</td>
        <td>: {{ $data_presensi['Course_Name'] }}</td>
        <td>Kelas</td>
        <td>: {{ $data_presensi['Class_Name'] }}</td>
      </tr>
      <tr>
        <td width="20%">Dosen</td>
        <td width="30%">:
        <?php
          $numItems = count($data_presensi['lecturer']);
          $i = 0;
          foreach($data_presensi['lecturer'] as $lecturer) {
            if(++$i === $numItems) { ?>
            {{ $lecturer }}
        <?php
            }else{ ?>
            {{ $lecturer }} <br> &nbsp;
          <?php  }
          } 
        ?>
        </td>
        <td>T.A/Semester</td>
        <td>: {{ $semester }} </td>
      </tr>
      <tr>
        <td width="20%">Hari/Jam</td>
        <td width="30%">: {{ $data_presensi['day'] }}/{{ $data_presensi['start'] }}-{{$data_presensi['end']}}</td>
        <td>Ruang</td>
        <td>: {{ $data_presensi['room'] }}</td>
      </tr>
    </table>
    <br>
  </div>
  <div id="content">
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
      <thead>
        <tr>
          <th rowspan="2" style="width:5%;"><center>No.</th>
          <th rowspan="2" style="width:10%;"><center>Nim</th>
          <th rowspan="2" style="width:25%;"><center>Nama</th>
          @if(isset($data_presensi['mahasiswa'][0]['Presensi']))
          <th colspan="{{count($data_presensi['mahasiswa'][0]['Presensi'])}}" style="width:65%;"><center>Pertemuan Ke</th>
          @endif
          <th rowspan="2" style="width:5%;"><center>Hadir</th>
          <th rowspan="2" style="width:5%;"><center>Alpha</th>
          <th rowspan="2" style="width:5%;"><center>Sakit</th>
          <th rowspan="2" style="width:5%;"><center>Ijin</th>
          <th rowspan="2" style="width:5%;"><center>Mbkm</th>
          <th rowspan="2" style="width:5%;"><center>Jml</th>
        </tr>
        @if(isset($data_presensi['mahasiswa'][0]['Presensi']))
        <tr>
          <?php $c_pertemuan = 1; foreach ($data_presensi['mahasiswa'][0]['Presensi'] as $key) { ?>
            <th><center>{{$c_pertemuan}}</th>
          <?php $c_pertemuan++; } ?>
        </tr>
        @else
        <tr>
        </tr>
        @endif
      </thead>
      <tbody>
        <?php
        $a = 1;
        foreach ($data_presensi['mahasiswa'] as $presensi_mhs ){
        ?>
        <tr>
            <!-- <th></th> -->
            <td><center>{{ $a }}</td>
            <td><center>{{ $presensi_mhs['Nim'] }}</td>
            <td><center>{{ $presensi_mhs['Full_Name'] }}</td>
            @if(isset($data_presensi['mahasiswa'][0]['Presensi']))
            <?php foreach ($presensi_mhs['Presensi'] as $hadir) { ?>
              @if($hadir == true) 
                <td style=""><center>v</td>
              @else
                <td style="background-color:#A0522D;"></td>
              @endif
            <?php } ?>
            @endif
            <td><center>{{ $presensi_mhs['c_presensi']['hadir'] }}</td>
            <td><center>{{ $presensi_mhs['c_presensi']['alpha'] }}</td>
            <td><center>{{ $presensi_mhs['c_presensi']['sakit'] }}</td>
            <td><center>{{ $presensi_mhs['c_presensi']['ijin'] }}</td>
            <td><center>{{ $presensi_mhs['c_presensi']['mbkm'] }}</td>
            <td><center>{{ $presensi_mhs['hadir'] }}</td>
        </tr>
        <?php
        $a++;
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="footer">
  </div>
</body>
</html>
