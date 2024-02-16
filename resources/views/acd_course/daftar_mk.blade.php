<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Daftar Matakuliah</title>
    <style>
      @page { size: A4; }
    </style>
  </head>
  <body>
    <table>
      <thead>
        <tr>
          <th colspan="6">Daftar Matakuliah</th>
        </tr>
      </thead>
    </table>
  <table>
    <thead>
        <tr>
          <th>No</th>
          <th>Kode Matakuliah</th>
          <th>Nama Matakuliah</th>
          <th>Nama Matakuliah (English)</th>
          <th>Jenis Matakuliah</th>
          <th>Prodi</th>
        </tr>
    </thead>
    <tbody>
      @php $no = 1 @endphp
      @foreach($Daftar_mk as $data)
       <tr>
           <td>{{ $no++ }}</td>
           <td>{{ $data->Course_Code }}</td>
           <td>{{ $data->Course_Name }}</td>
           <td>{{ $data->Course_Name_Eng }}</td>
           <td>{{ $data->Course_Type_Name }}</td>
           <td>{{ $data->Department_Name }}</td>
       </tr>
       @endforeach
    </tbody>
</table>
</body>
