<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 130px 60px 190px 60px; }
    @page :first {  margin: 190px 60px 30px 60px;}
    #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 10px; }

    p{
      font-size: 13px;
    }

    #table{
      border-collapse:collapse;
    }
    #table td, #table th {
      border: 1px solid black;
      height: 25px;
      text-align: center;
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
      <td width=" 60%"><center><b>{{env('NAME_MAJELIS')}}<br>{{env('NAME_UNIV')}}<br>_________________________________________________________________________</td>
      <td width=" 2%"></td>
      <td width=" 15%"></td>
    </tr>
  </table>
</head>
<body>
<br><br>
  <table style="width:100%; font-size:15px;">
    <tr>
      <td colspan="3"><div style="float:right;"></div></td>
    </tr>
    <tr>
      <td colspan="3"></td>
    </tr>
    <tr>
      <td width=" 15%"></td>
      <td width=" 70%"><center><b>SURAT PENGANTAR PEMBAYARAN (SP2)</b></center></td>
      <td width=" 15%"></td>
    </tr>
    <tr>
      <td width=" 15%"></td>
      <td width=" 70%"><center><b>PEMBAYARAN PENDADARAN TUGAS AKHIR</b></center></td>
      <td width=" 15%"></td>
    </tr>
  </table>
<br><br>
  <table style="width:100%; font-size:11pt;">
      <tr>
        <td style="width:25%;">Nama Mahasiswa</td>
        <td style="width:1;">:</td>
        <td style="width:74%;">{{$data->Full_Name}}</td>
      </tr>
      <tr>
        <td>Nomor Mahasiswa</td>
        <td>:</td>
        <td> {{ $data->Nim }}</td>
      </tr>
    </table>

  <p style="font-size:12pt;">Uraian Pembayaran &nbsp; &nbsp; &nbsp; :</p>
  <table style="width:100%; font-size:11pt;">
      <tr>
        <td style="width:25%;">Pendadaran Tugas Akhir</td>
        <td style="width:1;">:</td>
        <td style="width:74%;">Rp. &nbsp;{{$jumlah}}</td>
      </tr>
      <tr>
        <td>Nomor Mahasiswa</td>
        <td>:</td>
        <td><b> {{ $terbilang }}</b></td>
      </tr>
    </table>
<br>
<table style="width:100%; font-size:10pt;">
    <tr>
      <td width=" 60%"></td>
      <td width=" 40%"><center>{{env('NAME_City')}}, _______________ <br> Kepala TU</td>
    </tr>
    <tr>
      <td height="60px"></td>
      <td><center> </td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td><center>{{ $petugas }}</center></td>
    </tr>
  </table>
  <br><br><br>

</body>
</html>
