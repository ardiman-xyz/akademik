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

  <table style="width:100%; font-size:15px;">
      <tr>
        <td colspan="3"><div style="float:right;">{{ $data->Term_Year_Name }}</div></td>
      </tr>
      <tr>
        <td colspan="3"></td>
      </tr>
      <tr>
        <td width=" 14%"></td>
        <td width=" 1%"></td>
        <td width=" 85%"><center>PERMOHONAN IJIN TUGAS AKHIR / SKRIPSI</td>
      </tr>
      <tr style="font-size: 13px;">
        <td>Nomor</td>
        <td>:</td>
        <td></td>
      </tr>
      <tr style="font-size: 13px;">
        <td>Lamp</td>
        <td>:</td>
        <td></td>
      </tr>
      <tr style="font-size: 13px;">
        <td>Hal</td>
        <td>:</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="3"><p>Kepada Yth.</td>
      </tr>
    </table>

  <p><i>Assalamu'alaikum warahmatullahi wabarakatuh</i>
    <?php
     $tgl = Date('Y-m-d', strtotime($data->Yudisium_Date));
     $tgl_now = date("Y-m-d");
     ?>

    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 5%"></td>
          <td width=" 19%"></td>
          <td width=" 1%"></td>
          <td width=" 75%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Nama Mahasiswa</td>
          <td>:</td>
          <td> <b>{{$data->Full_Name}}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Nomor Mahasiswa</td>
          <td>:</td>
          <td> <b>{{$data->Nim}}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Program Studi</td>
          <td>:</td>
          <td> {{$data->Department_Name}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Judul TA / Skripsi</td>
          <td>:</td>
          <td> {{$data->Thesis_Title}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Dosen Pembimbing 1</td>
          <td>:</td>
          <td> {{$data->pembimbing_1}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Dosen Pembimbing 2</td>
          <td>:</td>
          <td> {{$data->pembimbing_2}}</td>
        </tr>

    </table>

<p>Adapun pelaksanaannya kami harapkan atau pada waktu lain yang sesuai dengan kebijakan industri/Perusahaan yang Bapak/Ibu pimpin.</p>
<p>Demikian permohonan ini kami sampaikan, atas perhatian dan perkenannya kami ucapkan terimakasih.</p>

<br>
  <table style="width:100%; font-size:12px;">
      <!-- <tr>
        <td width=" 5%"></td>
        <td width=" 95%">Adapun pelaksanaannya kami harapkan atau pada waktu lain yang sesuai dengan kebijakan industri/Perusahaan yang Bapak/Ibu pimpin.</td>
      </tr>
      <tr>
        <td width=" 5%"></td>
        <td width=" 95%">Demikian permohonan ini saya sampikan, atas perhatian dan perkenannya saya ucapkan terima kasih. </td>
      </tr>
      <tr> -->
        <td colspan="2"><i>Wassalamu'alaikum warahmatullahi wabarakatuh</i></td>
      </tr>
    </table>
<br><br>
<table style="width:100%; font-size:15px;">
    <tr>
      <td width=" 60%"></td>
      <td width=" 40%"><center>Yogyakarta, _______________ <br> Ketua Program Studi</td>
    </tr>
    <tr>
      <td height="10%"></td>
      <td><center> </td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td><center>{{ $Employee}}</td>
    </tr>
  </table>
</body>
</html>
