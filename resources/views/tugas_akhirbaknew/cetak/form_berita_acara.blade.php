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
      height: 35px;
      text-align: center;
    }
    hr{
      padding: 0px;
      margin: 0px;
      height: 1px;
      border: 0;
      background: #000;
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
        <td width=" 70%"><center><b>BERITA ACARA PENDADARAN TUGAS AKHIR</b></td>
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
           <td>Pada Hari ini</td>
           <td>:</td>
           <td></td>
         </tr>
         <tr>
           <td></td>
           <td>Tanggal</td>
           <td>:</td>
           <td></td>
         </tr>
         <tr>
           <td></td>
           <td>Jam/Waktu</td>
           <td>:</td>
           <td></td>
         </tr>
         <tr>
           <td></td>
           <td>Tempat</td>
           <td>:</td>
           <td></td>
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
           <p><b>Telah dilaksanakan Pendadaran Tugas Akhir untuk mahasiswa :</b></p>
           </td>
         </tr>
     </table>
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
          <p>dengan Tim Dosen Penguji :</p>
          </td>
        </tr>
    </table>
    <div class="" style="margin-left:10%;">
      <table id="table" style="width:100%; font-size:9pt;">
        <thead>
          <tr >
            <th width="5%">No</th>
            <th width="65%">Nama</th>
            <th width="30%">Tanda Tangan</th>
          </tr>
        </thead>
        <tr>
          <td>1</td>
          <td style="text-align:left; padding-left:5px;">{{ $data->penguji_1 }}</td>
          <td></td>
        </tr>
        <tr>
          <td>2</td>
          <td style="text-align:left; padding-left:5px;">{{ $data->penguji_2 }}</td>
          <td></td>
        </tr>
        <tr>
          <td>3</td>
          <td style="text-align:left; padding-left:5px;">{{ $data->penguji_3 }}</td>
          <td></td>
        </tr>
        <tr>
          <td>4</td>
          <td style="text-align:left; padding-left:5px;"></td>
          <td></td>
        </tr>
      </table>
    </div>
    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 99%"></td>
        </tr>
        <tr>
          <td></td>
          <td>
          <p>Mahasiswa yang dijuji :</p>
          </td>
        </tr>
    </table>
    <div class="" style="margin-left:10%;">
      <table id="table" style="width:100%; font-size:9pt;">
        <thead>
          <tr >
            <th width="45%">Nama</th>
            <th width="25%">NIM</th>
            <th width="30%">Tanda Tangan</th>
          </tr>
        </thead>
        <tr>
          <td>{{ $data->Full_Name_Student }}</td>
          <td>{{ $data->Nim }}</td>
          <td></td>
        </tr>
      </table>
    </div>
    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 99%"></td>
        </tr>
        <tr>
          <td></td>
          <td>
          <p>Hasil (coret yang tidak perlu) :</p>
          </td>
        </tr>
        <tr>
          <td></td>
          <td><center><b>Lulus dengan Perbaikan / Lulus Tanpa Perbaikan / Pendadaran Ulang / Tidak Lulus</b></center></td>
        </tr>
    </table>
    <table style="width:100%; height: 55px ;font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 42%"></td>
          <td width=" 3%"></td>
          <td width=" 16%"></td>
          <td width=" 47%"></td>
        </tr>
        <tr>
          <td></td>
          <td style="text-align:right; padding-right:5%;">Nilai</td>
          <td>:</td>
          <td style="border:solid 1px;">
          </td>
          <td></td>
        </tr>
    </table>
    <table style="width:100%; height: 55px ;font-size:10pt;">
        <tr>
          <td width=" 1%"></td>
          <td width=" 10%"></td>
          <td width=" 3%"></td>
          <td width=" 87%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Catatan</td>
          <td>:</td>
          <td></td>
        </tr>
        <tr>
          <td colspan="3"> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td colspan="3"> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td colspan="4"></td>
        </tr>
        <tr>
          <td colspan="3"> &nbsp; </td>
          <td><hr></td>
        </tr>
    </table>
    <br>
    <div  id="footer">
      <table style="width:35%; font-size:10px; float:left;">
        <tr>
          <td><i>Dibuat Rnagkap 5    :</i></td>
        </tr>
        <tr>
          <td><i>1. Arsip TU Pelayanan Fakultas Teknik</i></td>
        </tr>
        <tr>
          <td><i>2. Penguji 1, Penguji 2, Penguji 3</i></td>
        </tr>
        <tr>
          <td><i>3. Mahasiswa ybs</i></td>
        </tr>
      </table>
      <div class="" style="float:right; width:50%;">
        <p><center style="font-size:12px;">Yogyakarta, _____________________</center></p><br>
        <p><center style="font-size:12px;">Ketua Tim Penguji</center></p><br>
        <br><br><br><br>
        <p><center style="font-size:12px;">{{ $data->penguji_1 }}</center></p><br><br>
      </div>
    </div>


</body>
</html>
