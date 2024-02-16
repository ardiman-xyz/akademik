<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Daftar peserta dan Nilai Akhir</title>
  </head>
  <body>
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

    <center><h4>DAFTAR PESERTA DAN NILAI AKHIR</h4></center>
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
        <td>Dosen</td><td>:</td>
        <td>
          @foreach($dosen as $da)
          {{ $da->Full_Name }} <br>
          @endforeach
        </td>
      </tr>
      <tr>
        <td>Semester</td><td>:</td>
        <td>{{ $data->Term_Name }} {{ $data->Entry_Year_Name }}</td>
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
        <td>Kelas</td><td>:</td>
        <td>{{ $data->Class_Name }} - {{ $data->Class_Program_Name }}</td>
      </tr>
    </table>
    </b>
      <br>
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:13px;">
      <tr>
        <th style="width:4%;"><center>No</th>
        <th style="width:12%;"><center>NPM</center></th>
        <th style="width:25%;"><center>NAMA MAHASISWA</center></th>
        <!-- <th style="width:5%;"><center>HDR 10%</center></th>
        <th style="width:5%;"><center>TGS 15%</center></th>
        <th style="width:5%;"><center>UTS 50%</center></th> -->
        <th style="width:5%;"><center>SKOR</center></th>
        <th style="width:5%;"><center>NILAI</center></th>
        <!-- <th style="width:5%;"><center>TANDA TANGAN</center></th> -->

      </tr>


      <?php
      $no = 1;
      foreach ($query  as $dat) {
      ?>

      <tr>
        <td  style="height:25px; width:5%;">{{ $no }}</td>
        <td  style="height:25px; width:15%;">{{ $dat->Nim }}</td>
        <td  style="height:25px; width:18%;">{{ $dat->Full_Name }}</td>
        <!-- <td  style="height:25px; width:5%;"></td>
        <td  style="height:25px; width:5%;"></td>
        <td  style="height:25px; width:5%;"></td> -->
        <td  style="height:25px; width:6%;"></td>
        <td  style="height:25px; width:29%;"></td>
        <!-- <td  style="height:25px; width:12%;"></td> -->
      </tr>
      <?php
      $no++;
      }
      ?>
    </table>
    <br>
    <table style="width:100%;">
      <!-- <tr>
        <td style="width:55%;">
          <label  style="font-size:13px;">Keterangan</label>  : <br>
          <label for=""  style="font-size:13px;">
            <?php $i = 1; ?>
            @foreach($grade as $gra)
              @if($i == count($grade))
              {{ $gra->Grade_Letter }} = {{ $gra->Weight_Value }}
              @else
              {{ $gra->Grade_Letter }} = {{ $gra->Weight_Value }} ,
              @endif
              <?php $i++; ?>
            @endforeach
          </label>
        </td>
        <td style="width:45%;">
          <label for="" style="font-size:13px;">{{env('NAME_City')}}, </label><br>
          <hr style="width:80%; margin-right:0%;">
        </td>
      </tr> -->
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
          <label for="" style="font-size:13px;">Dosen Matakuliah, </label><br>
          <div style="height:70px;"></div>
          @foreach($dosen as $da)
            {{ $da->Full_Name }} <br>
          @endforeach
        </td>
      </tr>
    </table>
    <br>

    </table>
  </body>
</html>
