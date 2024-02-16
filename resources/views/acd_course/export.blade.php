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
        <td width=" 15%"></td>
        <td width=" 70%"><center><b>Daftar Matakuliah</td>
        <td width=" 15%"></td>
      </tr>
    </table>
    <br>
    Jumlah Data : <?php echo count($data); ?>
    <table border="1px" style="border-collapse : collapse; width:100%;">
      <tr>
        <td>No</td>
        <td>Kode Matakuliah</td>
        <td>Nama Matakuliah</td>
        <td>Nama Matakuliah (Eng)</td>
        <td>Jenis Matakuliah</td>
        <td>Prodi</td>
      </tr>
      <?php
      $no = 1;
      foreach ($data as $dat) {
      ?>

      <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $dat->Course_Code }}</td>
        <td>{{ $dat->Course_Name }}</td>
        <td>{{ $dat->Course_Name_Eng }}</td>
        <td>{{ $dat->Course_Type_Name }}</td>
        <td>{{ $dat->Department_Name }}</td>
      </tr>
      <?php
      }
      ?>
    </table>
    <br>
</body>
</html>
