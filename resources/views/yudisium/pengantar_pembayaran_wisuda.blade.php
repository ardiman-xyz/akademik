<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 130px 60px 190px 60px; font-size: 13px;}
    @page :first {  margin: 190px 60px 190px 60px;}
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
        <td width=" 15%"><img src="{{ ('img/logo_univ.png') }}" style="width:70px;" alt=""></td>
        <td width=" 2%"></td>
        <td width=" 60%"><center><b>{{env('NAME_MAJELIS')}}<br>{{env('NAME_UNIV')}}<br>Fakultas {{ $faculty->Faculty_Name }}</td>
        <td width=" 2%"></td>
        <td width=" 15%"></td>
      </tr>
    </table>
</head>
<body>
<br>
  <table style="width:100%;">
      <tr>
        <td width=" 7%">No</td>
        <td width=" 33%">:</td>
        <td width=" 60%"></td>
      </tr>
      <tr>
        <td>Lamp.</td>
        <td>: <i>Berita Acara Yudisium </td>
        <td></td>
      </tr>
      <tr>
        <td>Hal</td>
        <td>: <i><b>Permohonan Pembayaran Wisuda</td>
        <td></td>
      </tr>
    </table><br>

Kepada Yth :<br>
Panitia Wisuda Sarjana :<br>
UMY :<br>
(Bagian Keuangan UMY) :<br>
di Yogyakarta :<br><br>

<i>Assalamu'alaikum wr. wb.<br></i>
Dengan ini mohon didaftar sebagai Peserta Wisuda Sarjana Periode I  2017/2018 (Sabtu, 21 Oktober 2017) Universitas Muhammadiyah {{env('NAME_City')}} dengan data sebagai berikut :<br>
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
      <td>: {{ $student->Department_Name }}</td>
    </tr>
</table>

    <?php
     // $tgl = Date('Y-m-d', strtotime());
     $tgl_now = date("Y-m-d");
     $tgl = Date('Y-m-d', strtotime($data->Graduate_Date));
     $tgl_ujian = Date('Y-m-d', strtotime($data->Thesis_Exam_Date));
     $tgl_wisuda = Date('Y-m-d', strtotime($enddateyudisium->Graduation_Date));
     ?>
<p>Mahasiswa tersebut di atas telah mengikuti Ujian Pendadaran pada tanggal {{ tanggal_indo($tgl_ujian,false)}} dan Yudisium pada tanggal {{ tanggal_indo($tgl,false)}} dan dinyatakan LULUS sebagai Sarjana, serta memenuhi persyaratan untuk mengikuti wisuda {{ $enddateyudisium->Period_Name }} pada {{ tanggal_indo($tgl_wisuda,false)}}</p>
<br><i>Wassalamu'alaikum wr. wb.</i>

<br><br><br>
<table style="width:100%;">
    <tr>
      <td width=" 60%"></td>
      <td width=" 40%"><center>{{env('NAME_City')}},  {{ tanggal_indo($tgl_now,false)}} <br> Kepala Tata Usaha Fakultas</td>
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
      <td><center>Namanya</td>
    </tr>
  </table>
</body>
</html>
