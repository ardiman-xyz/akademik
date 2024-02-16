<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Presensi Hadir Kuliah</title>
  </head>
  <body>
    <style media="screen">
      .fa-sum:bofore{
        content: "\03a3";
        font-family: sans-serif;
      }
    </style>

    <div>
      
    <div id="header">
      <div id="header">
       <table>
        <tr>
          <td>
            <img src="http://akademik-umkendari.utc-umy.id/img/logo_univ.png" style="width:7%" alt="">
          </td>
          <td></td>
          <td>
            <label class="col-md-8" class="vertical-align: text-top;" style="font-family: Arial, Helvetica, serif ;"><b>UNIVERSITAS MUHAMMADIYAH KENDARI <br>KARTU HASIL STUDI</b></label>
          </td>
        </tr>
       </table>
    </div>
    </div>

    <center><h4>DAFTAR HADIR KULIAH</h4></center>
    <br>
    <b>
    <table  style="width:100%; font-size:12px;">
      <tr>
        <td style="width:25%;">Program Studi</td><td style="width:1%;">:</td>
        <td style="width:75%;">{{ $data->Department_Name }}</td>
      </tr>
      <tr>
        <td>Nama Matakuliah</td><td>:</td>
        <td>( {{ $data->Course_Code }} ) {{ $data->Course_Name }}</td>
      </tr>
      <tr>
        <td>Kelas</td><td>:</td>
        <td>{{ $data->Class_Name }} - {{ $data->Class_Program_Name }}</td>
      </tr>
      <tr>
        <td>Hari/Jam/Ruang</td><td>:</td>
        <td>
          @if($jadwal)
          {{ $jadwal->Day_Name }} / {{ $jadwal->Time_Start }} - {{ $jadwal->Time_End }} / {{ $jadwal->Room_Name }}
          @endif
        </td>
      </tr>
      <tr>
        <td>Dosen</td><td>:</td>
        <td>
          @foreach($dosen as $da)
          <?php $lnm = strtolower($da->Name); $ucnm = ucwords($lnm); ?>
            {{ $da->First_Title }} {{ $ucnm }} {{ $da->Last_Title }} <br>
          @endforeach
        </td>
      </tr>
    </table>
    </b>
      <br>
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:13px;">
      <tr>
        <th style="width:4%;" rowspan="2"><center>No</th>
        <th style="width:12%;" rowspan="2"><center>NPM</center></th>
        <th style="width:25%;" rowspan="2"><center>NAMA MAHASISWA</center></th>
        <th style="width:5%;" rowspan="2"><center>L/P</center></th>
        <th style="width:46%;" colspan="16"><center>PERTEMUAN KE-</center></th>
        <th style="width:4%;" rowspan="2"><center><i class="fa-sum"></i> </center></th>
        <th style="width:4%;" rowspan="2"><center>%</center></th>
      </tr>
      <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>10</th>
        <th>11</th>
        <th>12</th>
        <th>13</th>
        <th>14</th>
        <th>15</th>
        <th>16</th>
      </tr>

      <?php
      $no = 1;
      foreach ($query  as $dat) {
      ?>

      <tr>
        <td  style="height:25px;">{{ $no }}</td>
        <td  style="height:25px;">{{ $dat->Nim }}</td>
        <td  style="height:25px;">{{ $dat->Full_Name }}</td>
        <td  style="height:25px;">
          <center>
          @if($dat->Gender_Id == 2)
          P
          @elseif($dat->Gender_Id == 1)
          L
          @endif
          </center>
        </td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
      </tr>
      <?php
      $no++;
      }
      ?>
    </table>
    <br>
    <table style="width:100%;">
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
          <label for="" style="font-size:13px;">{{env('NAME_City')}}, </label><br>
          <hr style="width:80%; margin-right:0%;">
        </td>
      </tr>
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
          <label for="" style="font-size:13px;">Dosen Matakuliah, </label><br>
          <div style="height:70px;"></div>
         @foreach($dosen as $da)
          <?php $lnm = strtolower($da->Name); $ucnm = ucwords($lnm); ?>
            {{ $da->First_Title }} {{ $ucnm }} {{ $da->Last_Title }} <br>
          @endforeach
        </td>
      </tr>
    </table>
    <br>

    </table>
  </body>
</html>
