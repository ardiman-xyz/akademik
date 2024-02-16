<!DOCTYPE html>
<html>
<head>
  <style>
    @page {size:21.5cm 31cm; margin: 130px 50px 10px 50px; }
    @page :first {  margin: 190px 50px 190px 50px;}
    #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 10px; }

    p{
      font-size: 13px;
    }

    #table2 {
    border-collapse: collapse;


  }

  #table2 td, #table2 th {
    border: 1px solid black;
    height: 30px;
    text-align: center;
  }

    #pemohon {
      font-size: 13px;
    text-align: center;
  }

  </style>
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

  <table style="width:100%; font-size:12pt;">
      <tr>
        <td colspan="3"><div style="float:right;">{{ $data1->Term_Year_Name }}</div></td>
      </tr>
      <tr>
        <td colspan="3"></td>
      </tr>
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center><b>BERITA ACARA SEMINAR TUGAS AKHIR</td>
        <td width=" 15%"></td>
      </tr>
    </table>
    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 20%"></td>
          <td width="1%"></td>
          <td width=" 45%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Pada Hari </td>
          <td>: </td>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td>Tanggal</td>
          <td>: </td>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td>Jam/Waktu</td>
          <td>:</td>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td>Tempat</td>
          <td>:</td>
          <td></td>
        </tr>
    </table>
    <p><b>Telah dilaksanakan Seminar Tugas Akhir untuk mahasiswa :</b></p>
    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 20%"></td>
          <td width="1%"></td>
          <td width=" 45%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Nama Mahasiswa</td>
          <td>:</td>
          <td><b>{{$data->Full_Name}}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>NIM</td>
          <td>:</td>
          <td><b>{{$data1->Nim}}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Jurusan</td>
          <td>:</td>
          <td>{{$data1->Department_Name}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Judukl TA</td>
          <td>:</td>
          <td>{{$data1->Thesis_Title}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Dosen pembimbing</td>
          <td>:</td>
          <td>I.&nbsp;&nbsp;&nbsp;{{$data1->pembimbing_1}}</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td>:</td>
          <td>II.&nbsp;&nbsp;{{$data1->pembimbing_2}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Jumlah Peserta</td>
          <td>:</td>
          <td>__________</td>
        </tr>
    </table>

    <p>Seminar tersebut telah dilaksanakan dengan hasil : LULUS / TIDAK LULUS</p>

    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 10%"></td>
          <td width="2%"></td>
          <td width=" 87%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Catatan</td>
          <td>:</td>
          <td>___________________________________________________________________________________________<br></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td height="30pt">___________________________________________________________________________________________<br></td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td>___________________________________________________________________________________________<br></td>
        </tr>
    </table>

      <p><center>Yogyakarta, _____________________</center></p><br><br>

      <table id="pemohon" style="width:100%;">
          <tr>
            <td width="40%"> Doens Pembimbing 1</td>
            <td width="20%"></td>
            <td width="40%">Dosen Pembimbing 2</td>
          <tr>
            <td height="60px"></td>
          </tr>
          <tr>
            <td>{{ $data1->pembimbing_1 }}</td>
            <td></td>
            <td>{{ $data1->pembimbing_2 }}</td>
          </tr>
      </table>
</body>
</html>
