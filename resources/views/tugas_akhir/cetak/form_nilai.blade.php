<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 130px 60px 190px 60px; }
    @page :first {  margin: 190px 60px 190px 60px;}
    #footer { position: absolute; left: 0px; bottom: -10px; right: 0px; height: 10px; }

    p{
      font-size: 13px;
    }
    #table{
      border-collapse:collapse;
    }
    #table td, #table th {
      border: 1px solid black;
      height: 25px;
      text-align: center;
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
        <td colspan="3"><div style="float:right;">{{ $data->Term_Year_Name }}</div></td>
      </tr>
      <tr>
        <td colspan="3"></td>
      </tr>
      <tr>
        <td width=" 15%"></td>
        <td width=" 70%"><center><b>NILAI PENDADARAN TUGAS AKHIR</b></td>
        <td width=" 15%"></td>
      </tr>
    </table>

    <?php
     $tgl = Date('Y-m-d', strtotime($data->Yudisium_Date));
     $tgl_now = date("Y-m-d");
     ?>

    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 15%"></td>
          <td width="1%"></td>
          <td width=" 45%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Nama Mahasiswa</td>
          <td>:</td>
          <td>{{$data->Full_Name}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Nomor Mahasiswa</td>
          <td>:</td>
          <td>{{$data->Nim}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Jurusan</td>
          <td>:</td>
          <td>{{$data->Department_Name}}</td>
        </tr>
        <tr>
          <td></td>
          <td>Judukl TA</td>
          <td>:</td>
          <td>{{$data->Thesis_Title}}</td>
        </tr>
        <tr>
          <td></td>
          <td><i>Thesis of Title</i></td>
          <td>:</td>
          <td>{{$data->Thesis_Title_Eng}}</td>
        </tr>

    </table>
    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 99%"></td>
        </tr>
        <tr>
          <td></td>
          <td>
          <p>Yang Bertanda Tangan dibawah ini Dosen Penguji Pendadaran Tugas Akhir Mahasiswa tersebut diatas, memberikan penilaian sebagai berikut :</p>
          </td>
        </tr>
    </table>

      <table id="table" style="width:100%; font-size:10pt;">
        <thead>
        <tr >
          <th width="5%">No</th>
          <th width="50%">KOMPONEN NILAI</th>
          <th width="10%">BOBOT NILAI</th>
          <th width="35%">NILAI (SETELAH DIKALIKAN DENGAN BOBOT)</th>
        </tr>
      </thead>
        <tr>
          <td>A</td>
          <td style="text-align:left; padding-left:5px;"> <b>Nilai Penulisan Naskah Tugas Akhir</b></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>1</td>
          <td style="text-align:left; padding-left:5px;">Latar Belakang dan Rumusan Masalah</td>
          <td>20%</td>
          <td></td>
        </tr>
        <tr>
          <td>2</td>
          <td style="text-align:left; padding-left:5px;">Manfaat atau Konstribusi Hasil Penelitian / Perancangan</td>
          <td>30%</td>
          <td></td>
        </tr>
        <tr>
          <td>3</td>
          <td style="text-align:left; padding-left:5px;">Metodologi Penelitian / Pelaksanaan</td>
          <td>15%</td>
          <td></td>
        </tr>
        <tr>
          <td>4</td>
          <td style="text-align:left; padding-left:5px;">Analisa Hasil dan Pembahasan</td>
          <td>25%</td>
          <td></td>
        </tr>
        <tr>
          <td>5</td>
          <td style="text-align:left; padding-left:5px;">Tata Penulisan Naskah</td>
          <td>10%</td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3" style="font-size:13px; padding-left:33px; text-align:left;"><b>JUMLAH A</b></td>
          <td></td>
        </tr>
        <tr>
          <td>B</td>
          <td style="text-align:left; padding-left:5px;"> <b>Nilai Ujian pendadaran Tugas Akhir</b></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td>1</td>
          <td style="text-align:left; padding-left:5px;">Penugasan Materi Tugas Akhir</td>
          <td>40%</td>
          <td></td>
        </tr>
        <tr>
          <td>2</td>
          <td style="text-align:left; padding-left:5px;">Penguasaan Materi Komprehensif Keteknikan</td>
          <td>30%</td>
          <td></td>
        </tr>
        <tr>
          <td>3</td>
          <td style="text-align:left; padding-left:5px;">Kualitas Presentasi</td>
          <td>15%</td>
          <td></td>
        </tr>
        <tr>
          <td>4</td>
          <td style="text-align:left; padding-left:5px;">Sikap</td>
          <td>15%</td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3" style="font-size:13px; padding-left:33px; text-align:left;"><b>JUMLAH B</b></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3" style="font-size:13px; padding-left:33px; text-align:left;"><b>NILAI AKHIR = (A+B)/2</b></td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3" style="font-size:13px; padding-left:33px; text-align:left;"><b>NILAI AKHIR DENGAN HURUF</b></td>
          <td></td>
        </tr>
    </table>
    <br>
    <div  id="footer">
      <table style="width:35%; border:solid 1px; font-size:10px; float:left;">
        <tr>
          <td><i><b>Catatan :</b></i></td>
        </tr>
        <tr>
          <td><i><b>Nilai</b></i></td>
        </tr>
        <tr>
          <td><br></td>
        </tr>
        <tr>
          <td><br></td>
        </tr>
        <tr>
          <td><br></td>
        </tr>
        <tr>
          <td><br></td>
        </tr>
        <tr>
          <td><br></td>
        </tr>
        <tr>
          <td><b><p>TIDAK LULUS N &lt; 50</p></b></td>
        </tr>
      </table>
      <?php
      $employeee="";
      if($Employee != null){
        $employeee=$Employee->Full_Name;
      }
       ?>
      <div class="" style="float:right; width:35%;">
        <p><center style="font-size:12px;">Yogyakarta, _____________________</center></p><br><br>
        <br><br><br><br>
        <p><center style="font-size:12px;">{{ $employeee }}</center></p><br><br>
      </div>
      <div style="float:right; width:30%;">

      </div>
    </div>


</body>
</html>
