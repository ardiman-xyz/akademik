<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Presensi Hadir Kuliah</title>
    <style>
      @page { size: Legal; }
    </style>
  </head>
  <body>
    <style type="text/css">
      .fa-sum:bofore{
        content: "\03a3";
        font-family: sans-serif;
      }
    </style>

    <div id="header">
      <img src="{{public_path('/img/header.png')}}" style="width:100%" alt="">
    </div>

    <div style="width:100%; font-size:14px;">
      <center>DAFTAR HADIR KULIAH</center>
      <center>{{ $data->Term_Year_Name }}</center>
    </div>
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
      <tr>
        <td>Pertemuan ke</td><td>:</td>
        <td>
        </td>
      </tr>
    </table>
    </b>
      <br>
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:13px;">
      <tr>
        <th style="width:4%; height:25px;" rowspan=""><center>No</th>
        <th style="width:12%; height:25px;" rowspan=""><center>NPM</center></th>
        <th style="width:25%; height:25px;" rowspan=""><center>NAMA MAHASISWA</center></th>
        <th style="width:5%; height:25px;" rowspan=""><center>L/P</center></th>
        <th style="width:46%; height:25px;" colspan="2"><center>Tanda Tangan</center></th>
      </tr>
      <!-- <tr> -->
        <!-- <th>&nbsp;</th>
        <th>&nbsp;</th> -->
        <!-- <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>10</th>
        <th>11</th>
        <th>12</th>
        <th>13</th>
        <th>14</th>
        <th>15</th>
        <th>16</th> -->
      <!-- </tr> -->

      <?php
      $no = 1;
      foreach ($query  as $dat) {
        $lnm = strtolower($dat->Full_Name); $ucnm = ucwords($lnm);
      ?>

      <tr>
        <td  style="height:25px;">{{ $no }}</td>
        <td  style="height:25px;">{{ $dat->Nim }}</td>
        <td  style="height:25px;">{{ $ucnm }}</td>
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
        <td  style="height:25px;">&nbsp;</td>
        

        <!-- <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td>
        <td  style="height:25px;"></td> -->
        <!-- <td  style="height:25px;"></td>
        <td  style="height:25px;"></td> -->
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
          <label for="" style="font-size:13px;">{{env('NAME_City')}}, {{date('d-m-Y')}}</label><br>
        </td>
      </tr>
      @foreach($dosen as $da)
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
          <label for="" style="font-size:13px;">Dosen Matakuliah, </label><br>
          <div style="height:70px;"></div>
          <?php $lnm = strtolower($da->Name); $ucnm = ucwords($lnm); ?>
            {{ $da->First_Title }} {{ $ucnm }} {{ $da->Last_Title }} <br>
        </td>
      </tr>
      @endforeach
    </table>
    <br>

    </table>
  </body>
</html>
