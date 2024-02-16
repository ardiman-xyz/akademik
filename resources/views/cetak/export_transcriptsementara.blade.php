<!DOCTYPE html>
<html>
<head>
  <style>
    /*@page { margin: 250px 60px 120px 60px; }*/
    /* @page :first {  margin-top: 50cm} */
    /*#header { position: fixed; left: 0px; top: -240px; right: 0px; height: 0px; text-align: center; }*/
    /*#footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 10px; }*/
  </style>
</head>
<body>
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
    <img src="{{public_path('/img/header.png')}}" style="width:100%" alt="">
  </div>
  <hr>
  <div id="content">
    <br>
    <center>TRANSKRIP SEMESTARA</center>
    <br>
    <table  style="width:100%; font-size:14px;">
      <tr>
        <td>Nama</td>
        <td>: {{ $student->Full_Name }}</td>
        <td>Program Studi</td>
        <?php 
          $low=strtolower($student->Department_Name); 
          $uc = ucwords($low);
        ?>
        <td>: {{ $uc }}</td>
      </tr>
      <tr>
        <td width="20%">Nim</td>
        <td width="30%">: {{ $student->Nim }}</td>
        <td>Jenjang</td>
        <td>: {{ $student->Acronym }} ({{$student->Program_Name}})</td>
      </tr>
      <tr>
        <td width="20%">Tgl Lahir</td>
        <?php 
          $date = strtotime($student->Birth_Date);
          $da = Date('Y-m-d',$date);
          $birth = tanggal_indo($da,false);
        ?>
        <td width="30%">: {{ $birth }}</td>
        <td></td>
        <td></td>
      </tr>
    </table>
    <br>
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:11px;  margin: 0px 0px 0px 0px">
      <thead>
        <tr>
          <th rowspan='2' style="width:1%;"><center>NO</th>
          <th rowspan='2' style="width:3%;"><center>KODE MK</th>
          <th rowspan='2' style="width:14%;"><center>MATA KULIAH</th>
          <th rowspan='2' style="width:2%;"><center>SKS</th>
          <th colspan='2' style="width:2%;"><center>NILAI</th>
          <th rowspan='2' style="width:2%;"><center>SKS x AM</th>
        </tr>
        <tr>
          <th style="width:2%;"><center>HM</th>
          <th style="width:2%;"><center>AM</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $a = "1";
        foreach ($data as $data) {
          ?>
            <tr>
                <!-- <th></th> -->
                <td><center>{{ $a }}</td>
                <td><center>{{ $data->Course_Code }}</td>
                <td> &nbsp; {{ $data->Course_Name }}</td>
                <td><center>{{$data->Sks}}</td>
                <td><center>{{ $data->Grade_Letter }}</td>
                <td><center>{{ $data->Weight_Value }}</td>
                <td><center>{{ $data->weightvalue }}</td>
            </tr>
          <?php
          $a++;
        }
        ?>
        <tr>
            <td colspan="3" align="center">JUMLAH</td>
            <td align="center">{{ $query_->jml_sks }}</td>
            <td align="center"></td>
            <td align="center"></td>
            <td align="center">{{ $query_->jml_mutu }}</td>
        </tr>
      </tbody>
    </table>
    <br>
    <table border="1px" style="width:100%; font-size:11px; border-collapse : collapse; padding-top: 10px">
        @if ($query_->ipk != 0)
            <tr>
                <td>Indeks Prestasi Kumulatif (IPK)</td>
                <td>{{ $query_->ipk }}</td>
            </tr>
            <tr>
                <td>Predikat</td>
                <td>
                    <b>{{ $predikat }}</b>
                </td>
            </tr>
        @endif
    </table>
    <br>
    <table style="width:100%;">
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
        <?php $date = strtotime(date('d-m-Y'));
        $birth = date('Y-m-d', $date);?>
          <label for="" style="font-size:13px;">{{env('NAME_City')}},  {{ tanggal_indo($birth,false)}} </label><br>
        </td>
      </tr>
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
          <label for="" style="font-size:13px;">Ketua Program Studi</label><br>
          <div style="height:60px;"></div>
          <!-- <img src="{{ asset('img/signature.jpg') }}" width="80px" height="60px"> -->
          <label for="" style="font-size:13px;">{{$functional_name_kp}}</label><br>
          <label for="" style="font-size:13px;">{{$functional_nidn_kp}}</label><br>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
