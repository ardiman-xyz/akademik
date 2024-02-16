<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 130px 60px 190px 60px; }
    @page :first {  margin: 190px 60px 190px 60px;}
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

  <table style="width:100%; font-size:15px;">
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center><h3>SURAT KETERANGAN BEBAS PINJAMAN</td>
        <td width=" 15%"></td>
      </tr>
    </table>
    <table style="width:100%; font-size:12px;">
        <tr>
          <td width=" 5%"></td>
          <td width=" 20%"></td>
          <td width=" 75%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Nama Mahasiswa</td>
          <td>: {{$data->Full_Name}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Nomor Mahasiswa</td>
          <td>: {{ $data->Nim }} </td>
        </tr>
        <tr>
          <td></td>
          <td>Alamat</td>
          <td>:</td>
        </tr>
        <tr>
          <td></td>
          <td>Nomor HP</td>
          <td>:</td>
        </tr>
    </table>

    <br><br>
    <p>Yang bertanda tangan di bawah ini menerangkan bahwa mahasiswa tersebut di atas telah benar-benar bebas pinjaman/tanggungan/kewajiban lain yang menjadi tanggung jawabnya terhadap

      <table id="table2" style="width:100%; font-size:12px;">
        <thead>
        <tr >
          <th>No</th>
          <th>UNIT KERJA</th>
          <th>PENANGGUNGJAWAB</th>
          <th>TANDA TANGAN</th>
          <th>Ket.</th>
        </tr>
      </thead>
        <tr>
          <td>1</td>
          <td> </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>2</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>3</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>4</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>5</td>
          <td> </td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>6</td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      </table><br>

      <table id="pemohon" style="width:100%;">
          <tr>
            <td width="60%"></td>
            <td width="40%">Tempat, _________________</td>
          <tr>
            <td></td>
            <td>Ketua/Sekretaris Prodi/Koor. Lab</td></tr>
            <tr>
              <td></td>
              <td height="40px"> </td>
      </table>
</body>
</html>
