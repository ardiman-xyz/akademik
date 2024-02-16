<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 130px 60px 190px 60px;
    font-size: 13px; }
    @page :first {  margin: 190px 60px 190px 60px;}

    #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 10px; }
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

<br><br>
  <table style="width:100%; font-size:15px;">
      <tr>
        <td width=" 10%"></td>
        <td width=" 80%"><center>BUKTI PENYERAHAN LAPORAN BUKU TA, CD & SUMBANGAN ALUMNI</td>
        <td width=" 10%"></td>
      </tr>
    </table>

<br><br>
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
          <td>: {{$data->Nim}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Judul Skripsi</td>
          <td>: {{ $data->Thesis_Title }}</td>
        </tr>
        <tr>
          <td></td>
          <td>Judul Bahasa Ingrris</td>
          <td>: {{ $data->Thesis_Title_Eng }}</td>
        </tr>
    </table>
<p>Yang bertanda tangan di bawah ini menerangkan bahwa mahasiswa tersebut di atas telah benar-benar mengumpulkan Buku Laporan Tugas Akhir yang sudah disahkan/ditandatangani oleh masing-masing Penguji Pendadaran beserta CD Tugas Akhir.</p>

<br><br><br><?php
 $tgl_now = date("Y-m-d");
 ?>
<table style="width:100%; font-size:15px;">
    <tr>
      <td width=" 55%"></td>
      <td width=" 45%">{{env('NAME_City')}},  {{ tanggal_indo($tgl_now,false)}} <br> Refensi,</td>
    </tr>
  </table>
</body>
</html>
