<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 140px 60px 90px 60px;}
    @page :first {  margin: 360px 60px 50px 60px;}
    #header {left: 0px; top: -70px; right: 0px; height: 0px; text-align: center; }
    #header2 { position: fixed; left: 0px; top: -90px; right: 0px; height: 0px; text-align: center; }

    #headerfull{
      z-index: 9999;
    }
    /* #headerfull th{
     background-color: lemonchiffon;
    }
    #headerfull td{
     background-color: lemonchiffon;
    } */

    #center td
    {
        text-align: center;
        padding: 20px;
    }
    #normal td
    {
        padding: 0px;
    }
    #left td
    {
      text-align: left;
        padding: 0px;
    }

    #tabeldata th,
    #tabeldata td{
      border-bottom: 1px solid #000;
      border-top: 1px solid #000;
      padding-left: 10;
      padding-right: 10;
    }
  </style>

  <?php
  function tanggal_indo($tanggal, $cetak_hari = false)
  {
  	$hari = array ( 1 =>    'Senin',
  				'Selasa',
  				'Rabu',
  				'Kamis',
  				'Jumat',
  				'Sabtu',
  				'Minggu'
  			);

  	$bulan = array (1 =>   'Januari',
  				'Februari',
  				'Maret',
  				'April',
  				'Mei',
  				'Juni',
  				'Juli',
  				'Agustus',
  				'September',
  				'Oktober',
  				'November',
  				'Desember'
  			);
  	$split 	  = explode('-', $tanggal);
  	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

  	if ($cetak_hari) {
  		$num = date('N', strtotime($tanggal));
  		return $hari[$num] . ', ' . $tgl_indo;
  	}
  	return $tgl_indo;
  }
  ?>

</head>


<body>
  <div id="headerfull">
    <table style="width:100%; font-size:15px; margin: -310px 0px 0px 0px; background-color:#ffffff">
      <tr>
        <td colspan="2"><center><b>TRANSKRIP AKADEMIK SEMENTARA<</td>
      </tr>
      <tr>
        <td colspan="2"><center><i><u>(TEMPORARY ACADEMIC TRANSCRIPT)</td>
      </tr>
      <tr>
        <td colspan="2"><center>No. {{ $dataNo->Transcript_Num }}</td>
      </tr>
      <tr>
        <td height="12"></td>
      </tr>
      <tr>
        <td width="25%"><b>Nama Mahasiswa</b><br><i>Name</td>
        <td valign=top> : <b>{{ $student->Full_Name }}</td>
      </tr>
      <tr>
        <td><b>Tempat, Tanggal Lahir</b><br><i>Place and Date of Birth </td>
        <td valign=top> : <b> {{ $student->Birth_Place }} / {{ $student->Birth_Date }} </td>
      </tr>
      <tr>
        <td><b>Nomor Mahasiswa</b><br><i>Registration Number</td>
        <td valign=top> : <b>{{ $student->Nim }}</td>
      </tr>
      <tr>
        <td height="45" valign=top><b>Fakulas / Program Studi</b><br><i>Faculty /Department</td>
        <td valign=top> : <b>{{ $faculty->Faculty_Name }} / {{ $student->Department_Name }}<br></b>:<b><i> {{$faculty->Faculty_Name_Eng}} / </td>
      </tr>
      </table>
    </div>

  <div id="header2">
    <table  style="width:100%; font-size:14px;">
      <tr>
        <td width="20%"><b><u>Nama Mahasiswa</b></u><br><i>Name</td>
        <td width="40%" valign=top> : <b>{{ $student->Full_Name }}</td>
        <td width="20%" valign=top><b><u>Nomor Mahasiswa</b></u><br><i>Registration Number</td>
        <td width="20%" valign=top> : <b>{{ $student->Nim }}</td>
      </tr>
    </table>
    <center><p style="height:10px"><b>DAFTAR NILAI <i>(LIST OF GRADES)</i> <b></p></center>
  </div>


  <div id="content">
    <table id="tabeldata" style="border-collapse : collapse; width:100%; font-size:13px;  margin: 0px 0px 0px 0px">
            <thead>
              <tr>
                <th height="25" width="5%" style="border-right: 1px solid #000;border-left: 1px solid #000;"><center>No.</th>
                <th colspan="2" style="text-align:left; padding-left:10;">Nama Matakuliah</th>
                <th colspan="2" style="text-align:right; padding-right:10; border-left: 0px solid white;"><i>Subjects</th>
                <th width="7%" style="border-right: 1px solid #000;border-left: 1px solid #000;"><center>H.M</th>
                <th width="7%" style="border-right: 1px solid #000;"><center>M</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($dataisi as $data) {
                for ($i=0; $i <12 ; $i++) {
                ?>
                  <tr>
                      <!-- <th></th> -->
                      <td height="25"style="border-right: 1px solid #000;border-left: 1px solid #000;"><center>{{ $a }}</td>
                      <td  colspan="2" style="border-right: none">{{ $data->Course_Name }}</td>
                      <td align="right"  colspan="2"><i>{{ $data->Course_Name_Eng }}</td>
                      <td align="center" style="border-right: 1px solid #000;border-left: 1px solid #000;">{{ $data->Grade_Letter }}</td>
                      <td align="center" style="border-right: 1px solid #000;">{{ $data->Sks }}</td>
                  </tr>
                <?php
                $a++;
                }
              }
              ?>
            </tbody>
          </table>
   <br>

  <table id="center" border="1px" style="border-collapse : collapse; width:100%; font-size:13px;">
    <tr>
      <td>
        {{ $predikat->Thesis_Title }} <br><br> {{ $predikat->Thesis_Title_Eng }}
      </td>
    </tr>
  </table>

  <?php
   $tgl = Date('Y-m-d', strtotime($data1->Graduate_Date));
   $tgl_now = date("Y-m-d");
   ?>

  <table id="center" style="width:100%;">
    <tr>
      <td width="45%">
        <table id="left" style="width:100%; font-size:13px;">
          <tbody>
            <tr>
              <td>Jumlah Sks</td>
              <td>{{$query_->jml_sks}}</td>
            </tr>
            <tr>
              <td>IPK</td>
              <td>{{ $query_->ipk }}</td>
            </tr>
            <tr>
              <td>Predikat</td>
              <td>{{ $predikat->Predicate_Name }}</td>
            </tr>
            <tr>
              <td></td>
              <td>{{ $predikat->Predicate_Name_Eng }}</td>
            </tr>
            <tr>
              <td>Tanggal Lulus</td>
              <td>{{ tanggal_indo($tgl,false)}}</td>
            </tr>
          </tbody>
      </table>
    </td>
      <td width="10%">
      </td>
      <td width="45%">
        <table id="normal" style="width:100%; font-size:12px;">
                <tbody>
                  <tr>
                      <td width="20%"><center><i><u>Nilai</u></i><br>Grade</td>
                      <td width="20%"><center><i><u>Point</u></i><br>Bobot</td>
                      <td width="60%"><center><i><u>Makna</u></i><br>Meaning</td>
                  </tr>
                  <tr>
                      <td>A</td>
                      <td>4</td>
                      <td>Istimewa / Excellent</td>
                  </tr>
                  <tr>
                      <td>AB</td>
                      <td>3.5</td>
                      <td>Sangat Baik / Very Good</td>
                  </tr>
                  <tr>
                      <td>B</td>
                      <td>3</td>
                      <td>Baik / Good </td>
                  </tr>
                  <tr>
                      <td>BC</td>
                      <td>2.5</td>
                      <td>Lebih dari Cukup / Above Average </td>
                  </tr>
                  <tr>
                      <td>C</td>
                      <td>2</td>
                      <td>Cukup / Fair</td>
                  </tr>
                  <tr>
                      <td>D</td>
                      <td>1</td>
                      <td>Kurang / Poor</td>
                  </tr>
                </tbody>
              </table>
      </td>
    </tr>
    <tr>
      <td width="45%">
      </td>
      <td width="10%">
      </td>
      <td width="45%">
        <label for="" style="font-size:13px;">{{env('NAME_City')}},  {{ tanggal_indo($tgl_now,false)}} <br>{{ $jabatan->Functional_Position_Name}}</label><br>
      </td>
    </tr>
    <tr>
      <td width="45%">
      </td>
      <td width="10%">
      </td>
      <td width="45%">
        <div style="height:70px;"></div>
        <label for="" style="font-size:13px;">{{ $dosen->namadosen }}<br>{{ $dosen->nik }}<</label><br>
      </td>
    </tr>
  </table><br>

   {{-- <table style="width:100%;">
     <tr>
       <td style="width:55%;"></td>
       <td style="width:45%;">
          <label for="" style="font-size:13px;">{{ $dosen->namadosen }}<br>{{ $dosen->nik }}<</label><br>
       </td>
     </tr>
     <tr>
       <td style="width:55%;"></td>
       <td style="width:45%;">
         <div style="height:70px;"></div>
         <label for="" style="font-size:13px;">{{ $dosen->namadosen }}<br>{{ $dosen->nik }}<</label><br>
       </td>
     </tr>
   </table> --}}
  </div>

</body>
</html>
