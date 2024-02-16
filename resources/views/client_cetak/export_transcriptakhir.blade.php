<!DOCTYPE html>
<html>
<head>
  <style>
  @page {  size: 21cm 33cm; margin: 3.5cm 1.35cm 1cm 1cm;}
  @font-face {
    font-family: arialn;
    src: local('arialn'), url("{{ url('fonts/arialn.ttf')}}") format('truetype');
  }

  .arialn{
    font-family: arialn;
  }

  @font-face {
    font-family: arialnb;
    /* src: url(sansation_light.woff); */
    /* src: local('tahoma'), url("{{ url('fonts/tahoma.ttf')}}") format('ttf'); */
    src: local('arialnb'), url("{{ url('fonts/arialnb.ttf')}}") format('truetype');
  }

  .arialnb{
    font-family: arialnb;
  }

  body{
    font-family: arialn;
    font-size: 9pt;
  }

  .solid {
    border: 1px solid;
  }

  footer { position: fixed; left: 0px; bottom: -80px; right: 0px; height: 100px;font-size: 9pt;text-align: right;font-style: italic; color: lightblue; }
  .thesiss {
      border: 1px solid;
      vertical-align: top;
  }
  .valign {
    width: 3cm;
    height: 2cm; 
    border: 1px solid green;
  }
  br{
    line-height:5px;
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
  <center><div class="arialnb" style="font-size:14pt;">TRANSKRIP AKADEMIK</div></center>
  <center><div class="arialnb" style="font-size:10pt;"><i>TRANSCRIPT OF ACADEMIC RECORD</i></div></center>
  <br>
  <center><div class="arialn"><b>Nomor: {{ $print['Transcript_Number'] }}</div></center>
  <br><br><br>
  <div id="content">
    <table style="border-collapse : collapse; width:100%; margin: 0px 0px 0px 0px; line-height: 10px">
      <tr>
        <td width="24%" style="vertical-align: top; text-align: left;">Nama Mahasiswa</td>
        <td width="1%" style="vertical-align: top; text-align: left;">:</td>
        <td width="29%" rowspan=2 style="vertical-align: top; text-align: left;">{{$print['Full_Name']}}</td>
        <td width="17%">Fakultas</td>
        <td width="1%">:</td>
        <td width="33%">{{$print['Faculty_Name']}}</td>
      </tr>
      <tr>
        <td  height="14" style="vertical-align: top; text-align: left;"><i>(Name of Student)</i></td><td></td><td style="vertical-align: top; text-align: left;"><i>(Faculty)</i></td><td></td><td></td>
      </tr>
      <tr>
        <td>Nomor Ijazah Nasional</td>
        <td>:</td>
        <td>{{$print['National_Certificate_Number']}}</td>
        <td>Program Studi</td>
        <td>:</td>
        <td>{{$print['Department_Name']}}</td>
      </tr>
      <tr>
        <td height="14" style="vertical-align: top; text-align: left;"><i>(National Diploma Number)</i></td><td></td><td></td><td style="vertical-align: top; text-align: left;"><i>(Stydy Program)</i></td><td></td><td></td>
      </tr>
      <tr>
        <td>Nomor Pokok Mahasiswa</td>
        <td>:</td>
        <td>{{$print['Nim']}}</td>
        <td>Gelar Akademik</td>
        <td>:</td>
        <td>{{$print['Title']}}</td>
      </tr>
      <tr>
        <td height="14" style="vertical-align: top; text-align: left;"><i>(Student Identification Number)</i></td><td></td><td></td><td style="vertical-align: top; text-align: left;"><i>(Academic Degree)</i></td><td></td><td></td>
      </tr>
      <tr>
        <td>NIRM</td>
        <td>:</td>
        <td>{{$print['Register_Number']}}</td>
        <td>Tanggal Lulus</td>
        <td>:</td>
        <td>{{$print['Graduate_Date']}}</td>
      </tr>
      <tr>
        <td height="14" style="vertical-align: top; text-align: left;"><i>(Register Number)</i></td><td></td><td></td><td style="vertical-align: top; text-align: left;"><i>(Date of Yudicium)</i></td><td></td><td></td>
      </tr>
      <tr>
        <td style="vertical-align: top; text-align: left;">Tempat & Tanggal Lahir</td>
        <td style="vertical-align: top; text-align: left;">:</td>
        <td rowspan=2 style="vertical-align: top; text-align: left;">{{$print['TTL']}}</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td height="14" style="vertical-align: top; text-align: left;"><i>(Place and Date of Birth)</i></td><td></td><td></td><td></td><td></td>
      </tr>
    </table>
  </div>
</head>
<body>
  <div id="content">
    <center><div class="arialnb" style="font-size:12pt;">MATAKULIAH</div></center>
    <center><div class="arialnb" style="font-size:10pt;"><i>(COURSES)</i></div></center>
    <br>

    <table border="1px" style="border-collapse : collapse; width:100%; font-size:10.75px;">
      <tr>
        <th width="20%"><center>MATA KULIAH</th>
        <th width="5%"><center>SKS</th>
        <th width="5%"><center>NILAI</th>
        <th width="5%"><center>ANGKA</th>
        <th width="5%"><center>SKS x ANGKA</th>
        <th width="20%"><center>MATA KULIAH</th>
        <th width="5%"><center>SKS</th>
        <th width="5%"><center>NILAI</th>
        <th width="5%"><center>ANGKA</th>
        <th width="5%"><center>SKS x ANGKA</th>
      </tr>
      <tr>
        <td><center><i>(NAME OF COURSES)</td>
        <td><center><i>(CREDIT)</td>
        <td><center><i>(GRADE)</td>
        <td><center><i>(SCORE)</td>
        <td><center><i>(POINT)</td>
        <td><center><i>(NAME OF COURSES)</td>
        <td><center><i>(CREDIT)</td>
        <td><center><i>(GRADE)</td>
        <td><center><i>(SCORE)</td>
        <td><center><i>(POINT)</td>
      </tr>
      <?php $p = 0;?>
      @foreach($print['Transcript'][0] as $key)
      <tr>
        <td>{{$key['Course_Name']}}</td>
        <td><center>{{$key['Sks']}}</td>
        <td><center>{{$key['Grade_Letter']}}</td>
        <td><center>{{$key['Weight_Value']}}</td>
        <td><center>{{$key['Bnk_Value']}}</td>
        <td>{{ (isset($print['Transcript'][1][$p]) ? $print['Transcript'][1][$p]['Course_Name']:'' ) }}</td>
        <td><center>{{ (isset($print['Transcript'][1][$p]) ? $print['Transcript'][1][$p]['Sks']:'' ) }}</td>
        <td><center>{{ (isset($print['Transcript'][1][$p]) ? $print['Transcript'][1][$p]['Grade_Letter']:'' ) }}</td>
        <td><center>{{ (isset($print['Transcript'][1][$p]) ? $print['Transcript'][1][$p]['Weight_Value']:'' ) }}</td>
        <td><center>{{ (isset($print['Transcript'][1][$p]) ? $print['Transcript'][1][$p]['Bnk_Value']:'' ) }}</td>
      </tr>
      <?php $p++; ?>
      @endforeach
      <tr>
        <td colspan=6><center>JUMLAH <i>(TOTAL)</td>
        <td><center>{{$print['sum_sks']}}</td>
        <td><center></td>
        <td><center></td>
        <td><center>{{$print['sum_bnk']}}</td>
      </tr>
      <tr>
        <td colspan=10 height="20"><b>JUDUL SKRIPSI : {{$print['Thesis_Title']}}</td>
      </tr>
    </table>
    <br>
    <table style="width:100%; line-height: 10px"">
      <tr>
        <td>Jumlah Seluruh Kredit : {{$print['sum_sks']}}</td>
        <td>IPK : {{$print['ipk']}} ({{$print['ipk_terbilang']}})</td>
        <td>Predikat Kelulusan : {{$print['predikat_lulus']}}</td>
      </tr>
      <tr>
        <td><i>(Total Credit)</td>
        <td><i>(Grade Point Average)</td>
        <td><i>(Degree of Excellence)</td>
      </tr>
    </table>

    @if(2 > 0)
    <div style="margin-top: 10px">
      <table style="border-collapse : collapse; width:100%;  margin: 15px 0px 0px 0px;">
    @else
    <div style="margin-top: 0px">
      <table style=" border-collapse : collapse; width:100%;  margin: 10px 0px 0px 0px;" >
    @endif
      <tr style="height: 10px; line-height: 10px">
        <td width="20%"></td>
        <td width="20%"></td>
        <td width="15%"></td>
        <td width="16%"></td>
        <td width="20%"></td>
        <td width="15%"></td>
        <td width="30%"></td>
      </tr>
      <tr style="height: 10px; line-height: 10px">
        <td colspan=3>Transkrip ini dibuat dengan sebenarnya</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr style="height: 10px; line-height: 10px">
        <td colspan=3><i>(This transcript is .... maaf tidak kelihatan contohnya)</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr style="height: 10px; line-height: 10px">
        <td colspan="7"><b>&nbsp;</td>
      </tr>
      <tr style="height: 5px; line-height: 10px">
        <td style="vertical-align:middle;"></td>
        <td></td> 
        <td></td>
        <td rowspan=4 colspan=2><div class="valign" style="padding-top:30px"><center>Pas Foto Hitam putih 4x6 cm dan Stempel</div></td>
        <td rowspan=4 colspan=2 style="text-align:left; vertical-align:top;"><label for="" style="" >{{env('NAME_City')}},  <?php echo $print['Date_Cetak']; ?> <br>Dekan,<br><i>(Dean)</label><br>
          <div style="height:70px;"></div>
          <div class="arialnb">{{$print['namadekan']}}</div><label for="" style="">NIDN. {{$print['nidn']}}</label></td>
      </tr>
      <tr style="height: 5; line-height: 10px">
        <td ></td>
        <td ></td>
        <td ></td>
      </tr>
      <tr  style="height: 5; line-height: 10px">
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr  style="height: 5; line-height: 10px">
        <td >&nbsp;</td>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      </table>
    </div>
  </div>

</body>
</html>
