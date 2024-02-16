<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Daftar Makasiswa Tidak Aktif KRS</title>
    <style>
      @page { size: A4; }
    </style>
  </head>
  <body>
    <table>
      <thead>
        <tr>
          <th colspan="6">Daftar Mahasiswa Non-Aktif Prodi</th>
        </tr>
      </thead>
    </table>
  <table>
    <thead>
        <tr>
          <th width="10%">No</th>
          <th width="15%">Nim</th>
          <th width="60%">Nama Mahasiswa</th>
          <th width="15%">Program Kelas</th>
        </tr>
    </thead>
    <tbody>
      @php $no = 1 @endphp
      @foreach($Daftar_mhskrsnonaktif as $data)
       <tr>
         <td>{{$no++}}</td>
         <td>{{ $data->Nim }}</td>
         <td>{{ $data->Full_Name }}</td>
         <td>{{ $data->Class_Program_Name }}</td>
       </tr>
       @endforeach
    </tbody>
</table>
</body>
