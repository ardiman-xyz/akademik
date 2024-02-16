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
        <td>Hal</td>
        <td>:</td>
        <td>Undangan Seminar Tugas Akhir</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td>{{$data->Full_Name}} {{ $data->Nim }}</td>
      </tr>
    </table>

<p><b>Kepada Yth.</b><br>
1. {{ $data->pembimbing_1 }}<br>
2. {{ $data->pembimbing_2 }}<br>
3. Rekan-rekan Mahasiswa Prodi {{ $data->Department_Name }}<br>
Di.<br>
{{env('NAME_City')}}</p><br>

  <p><i>Assalamu'alaikum warahmatullahi wabarakatuh</i>
    <?php
     $tgl = Date('Y-m-d', strtotime($data->Yudisium_Date));
     $tgl_now = date("Y-m-d");
     ?>
  <p>Bersama ini diharapkan kehadiran Bapak/Ibu pada Seminar Tugas Akhir mahasiswa :

    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 5%"></td>
          <td width=" 20%"></td>
          <td width=" 75%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Nama Mahasiswa</td>
          <td>:{{$data->Full_Name}}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Nomor Mahasiswa</td>
          <td>:{{$data->Nim}}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Judul TA</td>
          <td>:{{$data->Thesis_Title}}</b></td>
        </tr>
    </table><br><br>

    <p>Insya Allah akan diselenggarakan pada :

      <table style="width:100%; font-size:10pt;">
          <tr>
            <td width=" 5%"></td>
            <td width=" 20%"></td>
            <td width=" 75%"></td>
          </tr>
          <?php
          $semdate = strtotime($data->Seminar_Date);
          $semda = Date('Y-m-d',$semdate);
          $semti = Date('H:i',$semdate);

          ?>
          <tr>
            <td></td>
            <td>Hari / Tanggal</td>
            <td>: {{ tanggal_indo($semda,true) }}</b></td>
          </tr>
          <tr>
            <td></td>
            <td>Jam</td>
            <td>: {{ $semti }}</b></td>
          </tr>
          <tr>
            <td></td>
            <td>Tempat</td>
            <td>: {{$data->Room_Name}}</b></td>
          </tr>
      </table><br>

<p>Demikian undangan ini dibuat, Atas perhatian dan kehadirannya diucapkan terimakasih  :</p>
<p><i>Wassalamu'alaikum warahmatullahi wabarakatuh</p>

<br>
<table style="width:100%; font-size:10pt;">
    <tr>
      <td width=" 60%"></td>
      <td width=" 40%"><center>{{env('NAME_City')}}, _______________ <br> Pemohon</td>
    </tr>
    <tr>
      <td height="60px"></td>
      <td><center> </td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td><center>{{ $data->Full_Name}}</td>
    </tr>
  </table>
  <br><br><br>

<p style="font-size:8pt;">
  <i>Catatan :
    <br>* Undangan disampaikan ke Dosen Pembimbing oleh mahasiswa paling lambat 2 hari sebelum hari pelaksanaan
    <br>* Informasi ruang seminar dan berita dibagian Pelayanan Akademik
    <br>* Jika ada pengunduran/penundaan waktu mahasiswa mengajukan permohonan baru dibagian Pelayanan Akademik
</p>
</body>
</html>
