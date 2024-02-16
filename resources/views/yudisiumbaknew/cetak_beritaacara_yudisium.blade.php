<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 130px 60px 190px 60px; }
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

  <table style="width:100%; font-size:15px;">
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center>Berita Acara Yudisium</td>
        <td width=" 15%"></td>
      </tr>
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center><p>{{$data->Sk_Num}}</td>
        <td width=" 15%"></td>
      </tr>
    </table>

  <br><br><center><p>Bismillahirrahmanirrohim</center>
    <?php
     $tgl = Date('Y-m-d', strtotime($data->Yudisium_Date));
     $tgl_now = date("Y-m-d");
     ?>
  <p>Pada hari ini tanggal {{ tanggal_indo($tgl,false)}}  telah diselenggarakan yudisium sarjana untuk :

    <table style="width:100%; font-size:12px;">
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
          <td>Program Studi</td>
          <td>: {{ $dat->Department_Name }}</td>
        </tr>
    </table>


  @foreach($statuslulus as $st=>$dt)
  <?php if($data->Is_Graduated == $st){?>
  <p>Berdasarkan evaluasi, kesepakatan dan keputusan dari Pimpinan Program Studi {{ $Education_prog_type->Acronym }} Fakultas {{ $faculty->Faculty_Name }} Universitas Muhammadiyah {{env('NAME_City')}}, ditetapkan bahwa mahasiswa yang bersangkutan dinyatakan {{ $dt }} YUDISIUM.</p>
  <?php } ?>
  @endforeach



<br><br><br>
<table style="width:100%; font-size:15px;">
    <tr>
      <td width=" 15%"></td>
      <td width=" 70%"><center>{{env('NAME_City')}},  {{ tanggal_indo($tgl_now,false)}} <br> {{$data->Functional_Position_Name}}</td>
      <td width=" 15%"></td>
    </tr>
    <tr>
      <td height="10%"></td>
      <td><center> </td>
      <td></td>
    </tr>
    <tr>
      <td width=" 15%"></td>
      <td width=" 70%"><center><u>{{ $data->namadosen}}</td>
      <td width=" 15%"></td>
    </tr>
    <tr>
      <td width=" 15%"></td>
      <td width=" 70%"><center>{{ $data->nikdosen}}</td>
      <td width=" 15%"></td>
    </tr>
  </table>
</body>
</html>
