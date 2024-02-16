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
        <td colspan="3"><div style="float:right;"> {{ $data->Term_Year_Name }} </div></td>
      </tr>
      <tr>
        <td colspan="3"></td>
      </tr>
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center><b>DAFTAR HADIR SEMINAR TUGAS AKHIR</td>
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
          <td>Pada Hari ini</td>
          <td>: </td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td>Tanggal</td>
          <td>:</td>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td></td>
          <td>Jam/Waktu</td>
          <td>:</td>
          <td><p style="padding-left:20%;">WIB</p></td>
        </tr>
        <tr>
          <td></td>
          <td>Tempat</td>
          <td>:</td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td>Nama Mahasiswa</td>
          <td>:</td>
          <td><b>{{ $data->Full_Name }}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Nomor Mahasiswa</td>
          <td>:</td>
          <td><b>{{ $data->Nim }}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Jurusan</td>
          <td>:</td>
          <td>{{ $data->Department_Name }}</td>
        </tr>
    </table>
    <br>
      <table id="table2" style="width:100%; font-size:10pt;">
        <thead>
        <tr >
          <th width="5%">No</th>
          <th width="20%">Nomor Mahasiswa</th>
          <th width="60%">Nama Mahasiswa</th>
          <th width="15%">Tanda Tangan</th>
        </tr>
      </thead>
        <tr>
          <td>1</td>
          <td> </td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>2</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>3</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>4</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>5</td>
          <td> </td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>6</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>7</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>8</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>9</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>10</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>11</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>12</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>13</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>14</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>15</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table><br>

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
            <td>{{ $data->pembimbing_1 }}</td>
            <td></td>
            <td>{{ $data->pembimbing_2 }}</td>
          </tr>
      </table>
</body>
</html>
