<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 130px 60px 190px 60px; }
    @page :first {  margin: 190px 60px 30px 60px;}
    #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 10px; }

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
      <td width=" 60%"><center><b>{{env('NAME_MAJELIS')}}<br>{{env('NAME_UNIV')}}<br>_________________________________________________________________________</td>
      <td width=" 2%"></td>
      <td width=" 15%"></td>
    </tr>
  </table>
</head>
<body>

  <table style="width:100%; font-size:15px;">
      <tr>
        <td colspan="2"><div style="float:right;">{{ $data->Term_Year_Name }}</div></td>
      </tr>
      <tr>
        <td colspan="2"></td>
      </tr>
      <tr>
        <td width=" 15%"></td>
        <td width=" 85%"><center>PERMOHONAN TUGAS AKHIR / SKRIPSI</td>
      </tr>
      <tr>
        <td colspan="2"><p>Kepada Yth.<br>Ketua Program Studi {S2 Magister Studi Islam belum dinamis} <br>FAKULTAS PASCA SARJANA Universitas Muhammadiyah {{env('NAME_City')}}</td>
      </tr>
    </table>

  <p><i>Assalamu'alaikum warahmatullahi wabarakatuh</i>
    <?php
     $tgl = Date('Y-m-d', strtotime($data->Yudisium_Date));
     $tgl_now = date("Y-m-d");
     ?>
  <p>Yang bertanda tangan dibawah ini.

    <table style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 5%"></td>
          <td width=" 20%"></td>
          <td width=" 75%"></td>
        </tr>
        <tr>
          <td></td>
          <td>Nama Mahasiswa</td>
          <td>: <b>{{$data->Full_Name}}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Nomor Mahasiswa</td>
          <td>: <b>{{$data->Nim}}</b></td>
        </tr>
        <tr>
          <td></td>
          <td>Alamat Asal</td>
          <td>:
             @if($address != null)
              {{ $address->Address }} &nbsp;&nbsp; Kode Pos &nbsp; {{ $address->Postal_Code }}
             @endif
          </td>
        </tr>
        <tr>
          <td></td>
          <td>Nomor Telephone</td>
          <td>:
            @if($data->Phone_Mobile != null)
              {{ $data->Phone_Mobile != null }}
            @elseif($data->Phone_Home != null)
              {{ $data->Phone_Home }}
            @endif
          </td>
        </tr>
        <tr>
          <td></td>
          <td>Tempat Tanggal Lahir</td>
          <td>: {{ $data->Birth_Place }}, {{ $data->Birth_Date }}</td>
        </tr>
        <?php
        $ipk = 0;
        if ($data->Bnk != null && $data->Sks_Trough != null) {
          $ipk = $data->Bnk / $data->Sks_Trough;
        }

         ?>
        <tr>
          <td></td>
          <td>IPK</td>
          <td>: BNK / SKS = {{ $data->Bnk }}/{{ $data->Sks_Trough }} = {{ $ipk }}</td>
        </tr>
    </table>

<p>Dengan surat ini mengajukan permohonan dapat melaksanakan penyusunan Tugas Akhir / Skripsi dengan Judul  :</p>
<center><i>{{ $data->Thesis_Title }}</i></center>
<p>Bersama ini pula saya lampirkan persyaratan sebagai berikut  :</p>

<table id="table" style="width:100%; font-size:9pt;">
  <thead>
    <tr >
      <th width="4%">No</th>
      <th width="68%">persyaratan</th>
      <th width="28%">Keterangan</th>
    </tr>
  </thead>
  <tr>
    <td>1</td>
    <td style="text-align:left; padding-left:3%;">Daftar Nilai Matakuliah dan Pratikum     *Nilai Lulus >= 120   SKS, IPK >= 2.55</td>
    <td></td>
  </tr>
  <tr>
    <td>2</td>
    <td style="text-align:left; padding-left:3%;">Kartu Mahasiswa yang masih berlaku (Asli) untuk ditunjukan</td>
    <td></td>
  </tr>
  <tr>
    <td>3</td>
    <td style="text-align:left; padding-left:3%;">Fotocopy sertifikat TOEFL minimal 400</td>
    <td></td>
  </tr>
</table>

<br>
  <table style="width:100%; font-size:12px;">
      <tr>
        <td width=" 5%"></td>
        <td width=" 95%">Demikian permohonan ini saya sampikan, atas perhatian dan perkenannya saya ucapkan terima kasih. </td>
      </tr>
      <tr>
        <td colspan="2"><i>Wassalamu'alaikum warahmatullahi wabarakatuh</i></td>
      </tr>
    </table>
<br><br>
<table style="width:100%; font-size:15px;">
    <tr>
      <td width=" 60%"></td>
      <td width=" 40%"><center>{{env('NAME_City')}}, _______________ <br> Pemohon</td>
    </tr>
    <tr>
      <td height="10%"></td>
      <td><center> </td>
      <td></td>
    </tr>
    <tr>
      <td></td>
      <td><center>{{ $data->Full_Name}}</td>
    </tr>
  </table>
</body>
</html>
