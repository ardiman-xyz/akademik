<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Export Kartu Hasil Studi</title>
    <style>
      body{
        font-family: Arial, Helvetica, serif ;
      }
    </style>
  </head>
  <body>
    <?php
    function tgl_indo($tanggal){
    	$bulan = array (
    		1 =>   'Januari',
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
    	$pecahkan = explode('-', $tanggal);

    	// variabel pecahkan 0 = tanggal
    	// variabel pecahkan 1 = bulan
    	// variabel pecahkan 2 = tahun

    	return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
    ?>
    <style media="screen">
      .fa-sum:bofore{
        content: "\03a3";
        font-family: sans-serif;
      }
    </style>
    <div id="header">
      <img src="{{public_path('/img/header.png')}}" style="width:100%" alt="">
    </div>

    <hr><center style="font-size:14px;"><b>KARTU HASIL STUDI</b></center>
    <br>
    <table style="width:100%; font-size:12px;">
      <tr>
        <td style="width:20%;">Program</td>
        <td style="width:1%;">:</td>
        <td style="width:55%;">{{ $print['class_prog'] }}</td>
        <td style="width:1%;"></td>
        <td style="width:1%;"></td>
        <td style="width:1%;"></td>
      </tr>
      <tr>
        <td >Semester</td><td style="width:1%;">:</td>
        <td >Semester {{ $print['Smt'] }} Tahun Akademik {{ $print['Year'] }} </td>
        <td ></td><td style="width:1%;"></td>
        <td ></td>
      </tr>
      <tr>
        <td >NIM</td>
        <td style="width:1%;">:</td>
        <td >{{ $print['Nim'] }}</td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr>
      <tr>
        <td >Nama Mahasiswa</td>
        <td style="width:1%;">:</td>
        <td >{{ $print['Full_Name'] }}</td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr>
      <tr>
        <td >Program Studi</td>
        <td style="width:1%;">:</td>
        <td >{{ $print['Prodi'] }}</td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr>
      <!-- <tr>
        <td >Batas Studi</td>
        <td style="width:1%;">:</td>
        <td >{{ $print['Study_Period_Semester'] }} Semester</td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr> -->
    </table>
    @if($print['Entry_Year_Id'] >= 2015)
    <table style="width:100%; font-size:12px;">
      <tr>
        <td colspan="8">Nilai :</td>
      </tr>
      <?php $p = 0; ?>
      @foreach($print['Grade_Department'][0] as $key)
      <tr>
        <td style="width:5%;">{{$key['Grade_Letter']}}</td>
        <td style="width:3%;">{{$key['Weight_Value']}}</td>
        <td style="width:15%;">{{$key['Predicate']}}</td>
        <td style="width:3%;"></td>
        <td style="width:5%;">{{ (isset($print['Grade_Department'][1][$p]) ? $print['Grade_Department'][1][$p]['Grade_Letter']:'' ) }}</td>
        <td style="width:3%;">{{ (isset($print['Grade_Department'][1][$p]) ? $print['Grade_Department'][1][$p]['Weight_Value']:'' ) }}</td>
        <td style="width:15%;">{{ (isset($print['Grade_Department'][1][$p]) ? $print['Grade_Department'][1][$p]['Predicate']:'' ) }}</td>
        <td style="width:45%;"></td>
      </tr>
      <?php $p++; ?>
      @endforeach
    </table>
    @else
    <table style="width:100%; font-size:12px;">
      <tr>
        <td colspan="4">Nilai :</td>
      </tr>
      @foreach($print['Grade_Department'] as $key)
        @foreach($key as $value)
        <tr style="grid-row: auto;">
          <td style="width:5%;">{{$value['Grade_Letter']}}</td>
          <td style="width:3%;">{{$value['Weight_Value']}}</td>
          <td style="width:12%;">{{$value['Predicate']}}</td>
          <td style="width:80%;"></td>
        </tr>
        @endforeach
      @endforeach
    </table>
    @endif

      <br>
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:12px;">
      <tr>
        <th style="width:5%;"><center>No</th>
        <th style="width:15%;"><center>Kode MK</center></th>
        <th style="width:46%;"><center>Mata Kuliah</center></th>
        <th style="width:10%;"><center>SKS</center></th>
        <th style="width:7%;"><center>Nilai Angka</center></th>
        <th style="width:7%;"><center>Nilai Huruf</center></th>
        <th style="width:10%;"><center>Ket.</center></th>
      </tr>
      <?php
      $no = 1;
      foreach ($print['Data_krs']  as $dat) {
      ?>

      {{-- @if($dat->Is_For_Transcript == null)
      <tr>
        <td  style="height:10px;"><center>{{ $no }}</center></td>
        <td  style="height:10px;"><center>&nbsp;{{ $dat->Course_Code}}</center></td>
        <td  style="height:10px;">{{ $dat->Course_Name }}</td>
        <td colspan="4" style="color:#fa7e9d;">Data Matakuliah ini bermasalah. Cek Kurikulum Angkatan dan Kurikulum Matakuliah!</td>
      </tr>
      @else --}}
      <tr>
        <td  style="height:10px;"><center>{{ $no }}</center></td>
        <td  style="height:10px; padding-left: 2px;">{{ $dat->Course_Code}}</center></td>
        <td  style="height:10px; padding-left: 2px;">{{ $dat->Course_Name }}</td>
        <td  style="height:10px;"><center>{{ $dat->Sks }}</center></td>
        <td  style="height:10px;">
          <center>
           <?php echo $dat->Weight_Value ?>
          </center>
        </td>
        <td  style="height:10px;"><center>{{ $dat->Grade_Letter }}</center></td>
        <td  style="height:10px;"><center></center></td>
      </tr>
      {{-- @endif --}}
      <?php
      $no++;
      }
      ?>
      {{--@if($dat->Is_For_Transcript != null) --}}
      <tr>
        <td colspan=3 style="height:10px;"><center>Jumlah</td>
        <td style="height:10px;"><center>{{ $print['Sks'] }}</center></td>
        <!-- <td style="height:10px;"><center>{{$print['Total_sksxnilai']}}</center></td> -->
        <td colspan=3 style="height:10px;"></td>
      </tr>
      {{--@endif--}}
    </table>
    <br>
    <table  style="width:100%; font-size:12px;">
      <tr> 
        <td style="width:18%;">IP Semester</td><td style="width:1%;">:</td>
        <td style="width:81%;">{{$print['Ips']}}</td>
      </tr>
      <tr>
        <td>IP Kumulatif</td><td style="width:1%;">:</td>
        <td>{{$print['Ipk']}}</td>
      </tr>
      <tr>
        <td>Jml. Kredit Kumulatif</td><td style="width:1%;">:</td>
        <td>{{$print['Total_sksxnilai']}}</td>
      </tr>
    </table>
    <br>
    <table style="width:100%; text-align:left">
      <tr>
        <td style="width:59%;"></td>
        <td></td>
        <td style="width:40%; font-size:13px;">{{env('NAME_City')}}, {{ tgl_indo(date('Y-m-d')) }}</td>
      </tr>
      <tr>
        <td style="font-size:13px;">Mengetahui,<br><b>Dekan</td>
        <td></td>
        <td style="font-size:13px;"><b>Ketua Program Studi</td>
      </tr>
      <tr>
        <td height="40pt"></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td style="font-size:13px;"><b>{{$print['functional_name_dekan']}}</td>
        <td></td>
        <td style="font-size:13px;"><b>{{$print['functional_name_kp']}}</td>
      </tr>
      <tr>
        <td style="font-size:13px;">{{$print['functional_nidn_dekan']}}</td>
        <td></td>
        <td style="font-size:13px;">{{$print['functional_nidn_kp']}}</td>
      </tr>
    </table>
  </body>
</html>
