<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Export Matakuliah</title>
  </head>
  <body>
    <center><h4>Daftar Matakuliah</h4></center>
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
        <td>{{ $no }}</td>
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
  </body>
</html>
