<!DOCTYPE html>
<html>
<head>
  <style>
  @page {  size: 8.27in 12.99in; margin: 60px 50px 30px 50px;}
    footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 100px;font-size: 8pt;text-align: right;font-style: italic; color: lightblue; }
    #thesiss {
        border: 1px solid;
        vertical-align: top;
    }
  </style>
  <table style="width:100%; font-size:14pt; margin: -30px 50px 30px 50px;">
    <tr>
      <td width=" 2%"></td>
      <td width=" 60%"><center><img src="{{ ('img/logo_univ.png') }}" style="width:90px;" alt=""></td>
      <td width=" 2%"></td>
    </tr>
    <tr>
      <td width=" 2%"></td>
      <td width=" 60%"><center><b>{{env('NAME_UNIV1')}}<br>{{env('NAME_UNIV2')}}</td>
      <td width=" 2%"></td>
    </tr>
    </table>
      <center style="font-size:10pt;">TRANSKRIP AKADEMIK</center>
      <center style="font-size:10pt;"><i>Academic Transcript</i></center>
      <table  style="width:100%; font-size:10pt; margin: 0px 0px 0px 0px;">
        <tr>
          <td width="20%">Nama</td>
          <td colspan="3" width="60%"> : {{ $student->Full_Name }}</td>
        </tr>
        <tr>
          <td><i>Name</td>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td>NIM</td>
          <td colspan="3"> : {{ $student->Nim }}</td>
        </tr><tr>
          <td><i>Student Number</td>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td>Program Studi</td>
          <td colspan="3"> : {{ $student->Department_Name }}  {{ $program_type->Acronym }}</td>
        </tr>
        <tr>
          <td><i>Department</td>
          <td colspan="3"><i>&nbsp; {{ $student->Department_Name_Eng }}  {{ $program_type->Acronym }}</td>
        </tr>
        <tr>
          <td>No. Ijazah</td>
          <td colspan="3"> : </td>
        </tr>
        <tr>
          <td><i>Diploma Number</td>
          <td colspan="3"></td>
        </tr>
        <tr>
          <td>Tahun Masuk</td>
          <td width="30%"> : </td>
          <td>Tahun Keluar</td>
          <td> : </td>
        </tr>
        <tr>
          <td><i>Entry Year</td>
            <td> </td>
            <td><i>Completion Year</td>
            <td></td>
          </tr>
      </table>
</head>
<body>
  <footer>
    Transkrip Akademik {{env('NAME_UNIV')}}
  </footer>
  <br>
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:13px; margin: 10px 0px 30px 0px;">
      <thead>
        <tr>
          <th width="4%"><center>No.</th>
            <th width="10%"><center>Kode</th>
            <th width="34%" colspan="2"><center>Nama Matakuliah</th>
              <th width="6%"><center>SKS<br><i>Credit</th>
            <th width="6%"><center>Nilai<br><i>Grade</th>
              <th width="6%"><center>Bobot<br><i>Weigh</th>
            {{-- <th width="6%"><center>A.M</th> --}}
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
                <td><center>{{ $data->Course_Code }}</td>
                <td  style="border-right: none;padding-left:10">{{ $data->Course_Name }}</td>
                <td align="right" style="border-left: : none;padding-right: 10"><i>{{ $data->Course_Name_Eng }}</td>
                  <td><center>{{$data->Sks}}</td>
                <td><center>{{ $data->Grade_Letter }}</td>
                  <td><center>{{ $data->weightvalue }}</td>
                {{-- <td><center>{{ $data->Weight_Value }}</td> --}}
            </tr>
          <?php
        }
        ?>
        {{-- <tr style="font-weight:bold;">
            <!-- <th></th> -->
            <td colspan="3" rowspan="3"></td>
            <td colspan="1">Jumlah</td>
            <td><center>{{$query_->jml_sks}}</td>
            <td></td>
            <td><center>{{ $query_->jml_mutu }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <!-- <th></th> -->
            <td colspan="3"> IPK</td>
            <td colspan="1"><center>{{ $query_->ipk }}</td>
        </tr>
        <tr style="font-weight:bold;">
            <!-- <th></th> -->
            <td > Predikat </td>
            <td colspan="3"><center><i>{{ $predikat }}</td>
        </tr> --}}
      </tbody>
    </table>
      <div  class="row col-md-7">
        <table style="width:100%; font-size:13px;">
        <tr>
          <td width="20%">IPK</td>
          <td width="10%">{{ $query_->ipk }}</td>
          <td width="20%"></td>
          <td width="50%">Judul Skripsi :</td>
        </tr>
        <tr>
          <td><i>Grade Point Average</td>
          <td></td>
          <td></td>
          <td><i>Title of Thesis</td>
        </tr>
        <tr>
          <td>Jumlah SKS</td>
          <td>{{$query_->jml_sks}}</td>
          <td></td>
          <td rowspan="4" id="thesiss" style="padding-left: 5;padding-right:5">{{ $thesis_title }}<br><i>{{ $thesiseng_title }}</td>
        </tr>
          <tr>
            <td><i>Number of Credit</td>
            <td></td>
          </tr>
          <tr>
            <td>Jumlah Nilai</td>
            <td>{{ $query_->jml_mutu }}</td>
          </tr>
          <tr>
            <td><i>Number of Grade</td>
            <td></td>
          </tr>
          <tr>
            <td>Prdikat</td>
            <td>{{ $predikat }}</td>
          </tr>
          <tr>
            <td><i>Predicate</td>
            <td>{{ $predikateng }}</td>
          </tr>
        </table>
      </div>

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
          <label for="" style="font-size:13px;">{{$ttd}},<br>{{ $ttdeng }} </label><br>
          <div style="height:70px;"></div>
          <label for="" style="font-size:13px;">{{ $dekan}}<br>NIP. {{ $nidn }}</label><br>
        </td>
      </tr>
    </table>

</body>
</html>
