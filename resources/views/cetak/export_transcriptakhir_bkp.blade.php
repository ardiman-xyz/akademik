<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 280px 60px 90px 60px;}
    @page :first {  margin: 360px 60px 50px 60px;}
    #header { position: fixed; left: 0px; top: -260px; right: 0px; height: 0px; text-align: center; }
    #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 10px; background-color: lightblue; }
    #footer .page:after { content: counter(page, upper-roman); }
  </style>
  <table style="width:100%; font-size:15px; margin: -310px 0px 0px 0px;">
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

  <div id="header">
  <br>
    <center><h5>TRANSKRIP NILAI AKHIR</h5></center>
    <table  style="width:100%; font-size:14px;">
      <tr>
        <td width="20%">Nama Mahasiswa</td>
        <td width="60%"> : {{ $student->Full_Name }}</td>
      </tr>
      <tr>
        <td width="15%">Tempat / Tanggal Lahir</td>
        <td width="60%"> : {{ $student->Birth_Place }} / {{ $student->Birth_Date }}</td>
      </tr>
      <tr>
        <td width="15%">NPM / Nim</td>
        <td width="60%"> : {{ $student->Nim }}</td>
      </tr>
      <tr>
        <td width="15%">Program Pendidikan</td>
        <td width="60%"> : {{ $program_type->Program_Name }}</td>
      </tr>
      <tr>
        <td width="15%">Program Studi</td>
        <td width="60%"> : {{ $student->Department_Name }}</td>
      </tr>
      <tr>
        <td width="15%">Kosentrasi</td>
        <td width="60%"> : </td>
      </tr>
      <tr>
        <td width="15%">Tanggal Lulus Ujian Tesis</td>
        <td width="60%"> : </td>
      </tr>
      <tr>
        <td width="15%">Akreditasi</td>
        <td width="60%"> : </td>
      </tr>
    </table>
  </div>
  <div id="content">

    <table border="1px" style="border-collapse : collapse; width:100%; font-size:13px;  margin: 220px 0px 0px 0px">
      <thead>
        <tr>
          <th width="4%"><center>No.</th>
            <th width="10%"><center>Kode M.K</th>
            <th width="34%" colspan="2"><center>Nama Matakuliah</th>
            <th width="6%"><center>H.M</th>
            <th width="6%"><center>A.M</th>
            <th width="6%"><center>K</th>
            <th width="6%"><center>M</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $a = "1";
        foreach ($data as $data) {
          ?>
            <tr>
                <!-- <th></th> -->
                <td><center>{{ $a }}</td>
                <td>{{ $data->Course_Code }}</td>
                <td  style="border-right: : : none;">{{ $data->Course_Name }}</td>
                <td align="right" style="border-left: : none;">{{ $data->Course_Name_Eng }}</td>
                <td><center>{{ $data->Grade_Letter }}</td>
                <td><center>{{ $data->Weight_Value }}</td>
                <td><center>{{$data->Sks}}</td>
                <td><center>{{ $data->weightvalue }}</td>
            </tr>
          <?php
          $a++;
        }
        ?>
        <tr style="font-weight:bold;">
            <!-- <th></th> -->
            <td colspan="3" rowspan="3"></td>
            <td colspan="3">Jumlah</td>
            <td><center>{{$query_->jml_sks}}</td>
            <td><center>{{ $query_->jml_mutu }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <!-- <th></th> -->
            <td colspan="3"> IPK</td>
            <td colspan="2"><center>{{ $query_->ipk }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <!-- <th></th> -->
            <td > Predikat </td>
            <td colspan="4"><center><i>{{ $predikat }}</td>
        </tr>
      </tbody>
    </table>
    <br>
    <table  style="width:100%; font-size:13px;">
      <tr>
        <td width=10%>Keterangan</td>
        <td> : </td>
      </tr>
    <tr>
      <td>HM</td>
      <td> : Huruf Mutu (A, B, C, D, E, T)</td>
    </tr>
      <tr>
        <td>AM</td>
        <td> : Angka Mutu (A=4,00, B=3,00, C=2,00, D=1,00, E=0,00, T=0,00)</td>
      </tr>
      <tr>
        <td>K</td>
        <td> : Kredit (SKS)</td>
      </tr>
      <tr>
        <td>M</td>
        <td> : AM x K</td>
      </tr>
    </table>
    <br>
    <table  style="width:100%; font-size:15px;">
    <tr  style="font-weight:bold;">
      <td width=20%>Judul Thesis</td>
      {{-- <td> : {{ $thesis_title->Thesis_Title }}</td> --}}
    </tr>
    </table>
    <br>
    <br>
    <table style="width:100%;">
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
          <label for="" style="font-size:13px;">{{env('NAME_City')}},  <?php echo date('d-m-Y'); ?> </label><br>

        </td>
      </tr>
      <tr>
        <td style="width:55%;"></td>
        <td style="width:45%;">
          <label for="" style="font-size:13px;">{{$ttd}} </label><br>
          <div style="height:70px;"></div>
          <label for="" style="font-size:13px;">{{ $dekan}}</label><br>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
