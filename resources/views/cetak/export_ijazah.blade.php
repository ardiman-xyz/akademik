<!DOCTYPE html>
<html>
<head>
  <style>
  @page :first {   size: A4 landscape;margin: 20px 60px -10px 60px;}
  @page {  size: A4 landscape;margin: 20px 60px -10px 60px;}

  .n_ijazah {
    font-size: 8pt;
    margin-top: 0;
  }

  .eng {
    font-size: 8pt;
    font-style: italic;
  }

  .name {
    font-size: 14pt;
    font-weight: bold;
  }
  .selesai {
    padding-left: 200px;
    padding-right: 200px;
  }

  .ttd{
    text-align: center;
  }

  .akreditasi {
    font-size: 8pt;
  }

  .tgl_ {
    font-size: 8pt;
  }

  .br {
    height: 10px;
  }
  .br2 {
    height:80px;
  }

</style>
</head>
<body>
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
  <?php
  foreach ($data as $data) {
    ?>
    <div class="n_ijazah">Nomor Seri Ijazah : {{ $data->Sk_Num }}</div>
    <div>
      <table style="width:100%; font-size:14pt; margin: 0px 0px 0px 0px;">
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
      </table><br>
      <center>

        <div>Menyatakan Bahwa</div>
        <div class="eng">declared that</div>

        <div class="br"></div>

        <div class="name">{{ $data->Full_Name }}</div>

        <div class="br"></div>

        <?php $date = strtotime($data->Birth_Date);
        $birth = date('Y-m-d', $date);?>
        <?php $date = strtotime($data->Birth_Date);
        $birth_eng = date('M d, Y', $date);?>
        <table style="width:100%;margin: 0px 0px 0px 0px;">
          <tr>
            <td width=" 30%"></td>
            <td style="text-align: right;" width=" 10%">Lahir</td>
            <td style="text-align: center;" width=" 15%"><b>{{ tanggal_indo($birth,false)}}</td>
            <td style="text-align: center;" width=" 5%">di</td>
            <td width=" 15%"><b>{{ $data->Birth_Place }}</td>
            <td width=" 20%"></td>
          </tr>
          <tr class="eng">
            <td width=" 30%"></td>
            <td style="text-align: right;" width=" 10%">born on</td>
            <td style="text-align: center;" width=" 15%"><b>{{ $birth_eng}}</td>
            <td style="text-align: center;" width=" 5%">in</td>
            <td width=" 15%"><b>{{ $data->Birth_Place }}</td>
            <td width=" 25%"></td>
          </tr>
        </table>

        <table style="width:100%;margin: 0px 0px 0px 0px;">
          <tr>
            <td width=" 30%"></td>
            <td style="text-align: right;" width=" 5%">diterima</td>
            <td style="text-align: right;" width=" 5%">tahun</td>
            <td style="text-align: center;" width=" 5%"><b>{{ $data->Entry_Year_Id}}</td>
            <td style="text-align: center;" width=" 25%">dengan Nomor Induk Mahasiswa</td>
            <td width=" 10%"><b>{{ $data->Nim }}</td>
            <td width=" 30%"></td>
          </tr>
          <tr class="eng">
            <td width=" 30%"></td>
            <td style="text-align: right;" width=" 5%">enroled</td>
            <td style="text-align: right;" width=" 5%"></td>
            <td style="text-align: center;" width=" 5%"><b>{{ $data->Entry_Year_Id}}</td>
            <td style="text-align: center;" width=" 25%">student number</td>
            <td width=" 10%"><b>{{ $data->Nim }}</td>
            <td width=" 30%"></td>
          </tr>
        </table>

        <div class="br"></div>

        <?php $date = strtotime($data->Yudisium_Date);
        $yudisium = date('Y-m-d', $date);
        $yudisium_eng = date('M d, Y', $date);
        ?>
        <div class="selesai">telah menyelesaikan studi dengan baik dan memenuhi semua persyaratan pendidikan {{ $prog_type }} Program Studi {{ $data->Department_Name }} pada <b>{{ tanggal_indo($yudisium,false)}}</div>
        <div class="selesai eng">has fulfilled the study requirements at the  {{ $prog_type }}  {{ $data->Department_Name_Eng }} on <b>{{ $yudisium_eng}}</div>

        <div class="br"></div>

        <div class="gelar">Kepadanya diberikan gelar <b>Sarjana Pendidikan (S.Pd.)</b> beserta segala hak dan kewajiban yang melekat pada gelar tersebut</div>
        <div class="gelar eng">with all the rights and responsibilities appertaining thereto in witness thereof, is awarded the Bachelor degree of Education</div>

        <div class="br2"></div>

        <table class="ttd space" style="width:100%;">
          <tr>
            <td width="5%"></td>
            <td>Wakil Ketua 1,</td>
            <td width="30%"></td>
            <td>Ketua,</td>
            <td width="5%"></td>
          </tr>
          <tr>
            <td></td>
            <td class="eng">vice Director,</td>
            <td></td>
            <td class="eng">Director,</td>
            <td></td>
          </tr>
          <tr>
            <td height="80px"></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td><b>{{$namawk1}}</td>
            <td></td>
            <td><b>{{$namak}}</td>
            <td></td>
          </tr>
          <tr>
            <td></td>
            <td>NBM {{ $nidnwk1 }}</td>
            <td></td>
            <td>NBM {{ $nidnk }}</td>
            <td></td>
          </tr>
          <tr class="tgl_">
            <?php $date = strtotime($tgl_akhir);
            $tgl_terbit = date('Y-m-d', $date);
            $tgl_terbiteng = date('M d, Y', $date);?>
            ?>
            <td colspan="5" > Pangkalanbaru, {{ tanggal_indo($tgl_terbit,false)}}</td>
          </tr>
          <tr>
            <td colspan="5" class="eng"> Pangkalanbaru, {{$tgl_terbiteng}}</td>
          </tr>
        </table>

        <div class="br"></div>

        <div class="akreditasi">
          <?php
          if($data->Department_Name == 'Pendidikan Guru Sekolah Dasar'){
            echo "Program Srtudi $data->Department_Name <b>Terakreditasi</b> Oleh Badan Akreditasi Nasional Perguruan Tinggi berdasarkan Keputusan Nomor: 1715/SK/BAN-PT/Akred/S/VII/2018 tanggal 09 Juli 2018";
          }elseif ($data->Department_Name == 'Pendidikan Jasmani Kesehatan & Rekreasi') {
            echo "Program Srtudi $data->Department_Name <b>Terakreditasi</b> Oleh Badan Akreditasi Nasional Perguruan Tinggi berdasarkan Keputusan Nomor: 13/SK/BAN-PT/Akred/S/I/2018 tanggal xxx";
          }elseif ($data->Department_Name=='Pendidikan Bahasa Inggris') {
            echo "Program Srtudi $data->Department_Name <b>Terakreditasi</b> Oleh Badan Akreditasi Nasional Perguruan Tinggi berdasarkan Keputusan Nomor: 1167/SK/BAN-PT/Akred/S/XI/2015 tanggal xxx";
          }else{
            echo  "Program Srtudi $data->Department_Name <b>Terakreditasi</b> Oleh Badan Akreditasi Nasional Perguruan Tinggi berdasarkan Keputusan Nomor: xxx tanggal xxx";
          }
           ?>

        </div><br>

      </center>
    </div>
    <?php
    }
    ?>
</body>
</html>
