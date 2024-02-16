<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 250px 60px 190px 60px; font-size: 13px;}
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

  <table style="width:100%; font-size:15px; margin: -170px 0px 0px 0px;">
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center><u><b>SURAT KETERANGAN</b></u></td>
        <td width=" 15%"></td>
      </tr>
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center>Nomor: {{$data->Sk_Num}}</td>
        <td width=" 15%"></td>
      </tr>
    </table>
</head>
<body>
<br><br><br>

<center><img src="{{ URL('img/bismillah.png') }}" style="width:200px;" alt=""></center><br><br>
Yang bertanda tangan di bawah ini Pimpinan Fakultas {{ $faculty->Faculty_Name }} Universitas Muhammadiyah {{env('NAME_City')}} menerangkan bahwa :<br>
<br>
<table style="width:100%;">
    <tr>
      <td width=" 5%"></td>
      <td width=" 20%"></td>
      <td width=" 75%"></td>
    </tr>
    <tr>
      <td></td>
      <td>Nama Mahasiswa</td>
      <td>: {{$data->Full_Name}}</td>
    </tr>
    <tr>
      <td></td>
      <td>Nomor Mahasiswa</td>
      <td>: {{ $data->Nim }}</td>
    </tr>
    <tr>
      <td></td>
      <td>Program Studi</td>
      <td>: {{ $dat->Department_Name }}</td>
    </tr>
    <tr>
      <td></td>
      <td>Fakultas</td>
      <td>: {{ $faculty->Faculty_Name }}</td>
    </tr>
</table>


     <?php
      $tgl = Date('Y-m-d', strtotime($data->Graduate_Date));
      ?>
<p>yang bersangkutan telah menyelesaikan semua beban studi yaitu : Bebas Teori, Praktikum, Kerja Praktek serta Tugas Akhir, dan telah dinyatakan LULUS Yudisium yang dilaksanakan pada tanggal {{ tanggal_indo($tgl,false)}}.
</p>

<?php
 // $tgl = Date('Y-m-d', strtotime());
 $tgl_now = date("Y-m-d");
 ?>
<br><br><br>
<table style="width:100%;">
    <tr>
      <td width=" 60%"></td>
      <td width=" 40%"><center>{{env('NAME_City')}},  {{ tanggal_indo($tgl_now,false)}} <br> {{$data->Functional_Position_Name}}</td>
    </tr>
    <tr>
      <td height="10%"></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td><center><u>{{$data->Full_Name}}</u><br>{{$data->Nik}}</br></td>
    </tr>
  </table>
</body>
</html>
