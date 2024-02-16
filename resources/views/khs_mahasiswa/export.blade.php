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
      <div id="header">
       <table>
        <tr>
          <td>
            <img src="http://akademik-umkendari.utc-umy.id/img/logo_univ.png" style="width:7%" alt="">
          </td>
          <td></td>
          <td>
            <label class="col-md-8" class="vertical-align: text-top;" style="font-family: Arial, Helvetica, serif ;"><b>UNIVERSITAS MUHAMMADIYAH KENDARI <br>KARTU RENCANA STUDI </b></label>
          </td>
        </tr>
       </table>
    </div>
    </div>
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
        <td >Cawu/Semester</td><td style="width:1%;">:</td>
        <td >Semester {{ $print['Smt'] }} Tahun Akademik {{ $print['Year'] }} </td>
        <td ></td><td style="width:1%;"></td>
        <td ></td>
      </tr>
      <tr>
        <td >Nim</td>
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
        <td >Jurusan</td>
        <td style="width:1%;">:</td>
        <td >{{ $print['Prodi'] }}</td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr>
      <tr>
        <td >Batas Studi</td>
        <td style="width:1%;">:</td>
        <td ></td>
        <td ></td>
        <td style="width:1%;"></td>
        <td ></td>
      </tr>
    </table>
    <table style="width:100%; font-size:12px;">
      <?php $p = 0; ?>
      @foreach($print['Grade_Department'][0] as $key)
      <tr>
        <td style="width:45%;"></td>
        <td style="width:5%;">{{ ($p==0 ? 'Nilai :':'' ) }}</td>
        <td style="width:5%;">{{$key['Grade_Letter']}}</td>
        <td style="width:3%;">{{$key['Weight_Value']}}</td>
        <td style="width:12%;">{{$key['Predicate']}}</td>
        <td style="width:3%;"></td>
        <td style="width:5%;">{{ (isset($print['Grade_Department'][1][$p]) ? $print['Grade_Department'][1][$p]['Grade_Letter']:'' ) }}</td>
        <td style="width:3%;">{{ (isset($print['Grade_Department'][1][$p]) ? $print['Grade_Department'][1][$p]['Weight_Value']:'' ) }}</td>
        <td style="width:15%;">{{ (isset($print['Grade_Department'][1][$p]) ? $print['Grade_Department'][1][$p]['Predicate']:'' ) }}</td>
      </tr>
      <?php $p++; ?>
      @endforeach
    </table>
      <br>
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:12px;">
      <tr>
        <th style="width:5%;"><center>No</th>
        <th style="width:10%;"><center>Kode MK</center></th>
        <th style="width:45%;"><center>Mata Kuliah</center></th>
        <th style="width:10%;"><center>Grade (K)</center></th>
        <th style="width:10%;"><center>SKS (N)</center></th>
        <th style="width:10%;"><center>KxN</center></th>
        <th style="width:10%;"><center>Ket.</center></th>
      </tr>
      <?php
      $no = 1;
      foreach ($print['Data_krs']  as $dat) {
      ?>

      @if($dat->Is_For_Transcript == null)
      <tr>
        <td  style="height:10px;"><center>{{ $no }}</center></td>
        <td  style="height:10px;"><center>&nbsp;{{ $dat->Course_Code}}</center></td>
        <td  style="height:10px;">{{ $dat->Course_Name }}</td>
        <td colspan="4" style="color:#fa7e9d;">Data Matakuliah ini bermasalah. Cek Kurikulum Angkatan dan Kurikulum Matakuliah!</td>
      </tr>
      @else
      <tr>
        <td  style="height:10px;"><center>{{ $no }}</center></td>
        <td  style="height:10px;"><center>{{ $dat->Course_Code}}</center></td>
        <td  style="height:10px;">{{ $dat->Course_Name }}</td>
        <td  style="height:10px;"><center>{{ $dat->Grade_Letter }}</center></td>
        <td  style="height:10px;"><center>{{ $dat->Sks }}</center></td>
        <td  style="height:10px;">
          <center>
           <?php echo ($dat->Grade_Letter == '' ? '':number_format(($dat->Sks * $dat->Weight_Value),2)) ?>
          </center>
        </td>
        <td  style="height:10px;"><center></center></td>
      </tr>
      @endif

      <?php
      $no++;
      }
      ?>
      @if($dat->Is_For_Transcript != null)
      <tr>
        <td style="height:10px;"></td>
        <td style="height:10px;"></td>
        <td style="height:10px;">Jumlah</td>
        <td style="height:10px;"></td>
        <td style="height:10px;"><center>{{ $print['Sks'] }}</center></td>
        <td style="height:10px;"><center>{{$print['Total_sksxnilai']}}</center></td>
        <td style="height:10px;"></td>
      </tr>
      @endif
    </table>
    <br>
    <table  style="width:100%; font-size:12px;">
      <tr>
        <td style="width:18%;">IP Semester</td><td style="width:1%;">:</td>
        <td style="width:81%;"> <?php echo($dat->Is_For_Transcript == null ? '':$print['Ips']) ?></td>
      </tr>
      <tr>
        <td>IP komulatif</td><td style="width:1%;">:</td>
        <td> <?php echo($dat->Is_For_Transcript == null ? '':$print['Ipk']) ?></td>
      </tr>
      <tr>
        <td>Jml. Kredit Kumulatif</td><td style="width:1%;">:</td>
        <td> <?php echo($dat->Is_For_Transcript == null ? '':$print['SksTotal']) ?></td>
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
        <td style="font-size:13px;">Mengetahui,</td>
        <td></td>
        <td style="font-size:13px;">Ketua Program Studi</td>
      </tr>
      <tr>
        <td height="40pt"></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td style="font-size:13px;">{{$print['functional_name_dekan']}}</td>
        <td></td>
        <td style="font-size:13px;">{{$print['functional_name_kp']}}</td>
      </tr>
      <tr>
        <td style="font-size:13px;">{{$print['functional_jenis_dekan']}}</td>
        <td></td>
        <td style="font-size:13px;">{{$print['functional_jenis_kp']}}</td>
      </tr>
    </table>
  </body>
</html>
