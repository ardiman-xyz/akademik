<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Berita Acara Ujian UTS</title>
  </head>
  <body>
    <div id="header">
                    <div>
                            <img src="{{ ('img/header.png') }}" style="width:100%" alt="">
                    </div>
                    <hr>
              </div>

    <center><b>BERITA ACARA UJIAN</b></center>
    <center><b>Semester {{ $data->Term_Name }} Tahun Akademik {{ $data->Entry_Year_Name }}</b></center>
    <center><img src="{{ URL('img/bismillah.png') }}" style="width:200px;" alt=""></center>

    <br>
    <b>
    <center>Pada hari ini ................................ , telah dilangsungkan {{ $typ }}</center>
    <center>{{ $data->Term_Name }} Tahun Akademik {{ $data->Entry_Year_Name }}, Program Studi TEKNIK SIPIL</center>
    <br>
    <table  style="width:100%; font-size:15px;">
      <tr>
        <td style="width:25%;">Mata Ujian</td><td style="width:1%;">:</td>
        <td style="width:75%;">[ {{ $data->Course_Code }} ] {{ $data->Course_Name }}</td>
      </tr>
      <tr>
        <td>Pengasuh</td><td>:</td>
        <td>
          @foreach($dosen as $da)
          {{ $da->Full_Name }} <br>
          @endforeach
        </td>
      </tr>
      <tr>
        <td>Kelas</td><td>:</td>
        <td>{{ $data->Class_Name }} - {{ $data->Class_Program_Name }}</td>
      </tr>
      <tr>
        <td>Pukul</td><td>:</td>
        <td>..........................................................................................</td>
      </tr>
      <tr>
        <td>Ruang</td><td>:</td>
        <td>..........................................................................................</td>
      </tr>
      <tr>
        <td>Jumlah Peserta</td><td>:</td>
        <td>..........................................................................................</td>
      </tr>
      <tr>
        <td>Jumlah Hadir</td><td>:</td>
        <td>..........................................................................................</td>
      </tr>
      <tr>
        <td>Jumlah Tidak Hadir</td><td>:</td>
        <td>..........................................................................................</td>
      </tr>
      <tr>
        <td>Keterangan Lain</td><td>:</td>
        <td>..............................................................................................................................</td>
      </tr>
      <tr>
        <td></td><td></td>
        <td>..............................................................................................................................</td>
      </tr>
      <tr>
        <td></td><td></td>
        <td>..............................................................................................................................</td>
      </tr>
    </table>
    </b>

    <br>
    <table style="width:100%;">
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
        <center>
          <label for="" style="font-size:13px;">{{ $ttd }}, </label><br>
          <div style="height:70px;"></div>
          @foreach($pejabat as $pejab)
            {{ $pejab->Full_Name }} <br>
          @endforeach
          <br><br>
        </center>
        </td>
      </tr>
      <tr>
        <td style="width:50%;">
          Penguji / Pengasuh :<br>

        </td>
        <td style="width:50%;">
        <center>
          <br>
          ..............................................................................
        </center>
        </td>
      </tr>
      <tr>
        <td style="width:50%;">
          Pengawas :<br>
          1................................................................................<br>
          2................................................................................<br>
          3................................................................................<br>
        </td>
        <td style="width:50%;">
          Tanda Tangan :<br>
          1................................................................................<br>
          2................................................................................<br>
          3................................................................................<br>
        </td>
      </tr>
    </table>
    <i style="font-size:12px;">*) Mohon dituliskan jumlah total mahasiswa tampa menggunakan tanda tambah (+)</i>
    <br>

    </table>
  </body>
</html>
