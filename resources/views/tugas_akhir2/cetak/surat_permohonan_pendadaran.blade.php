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
      padding-left: 20%;
    }
    #table2{
      border-collapse:collapse;
    }
    #table3{
      border-collapse:collapse;
    }
    #table4{
      border-collapse:collapse;
    }
    #table td, #table th {
      border: 1px solid black;
      height: 25px;
      text-align: center;
    }
    #table2 td, #table2 th {
      text-align: center;
    }
    #table3 td, #table3 th {
      border: 1px solid black;
      text-align: center;
    }
    #table4 td, #table4 th {
      border: 1px solid black;
      padding-left: 5px;
    }
    div.ex1 {
      padding-left: 400px;
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
        <td width=" 85%"><center>PERMOHONAN PENDADARAN</td>
      </tr>
      <tr>
        <td colspan="2"><p>Kepada Yth.<br>Ketua Program Studi {S2 Magister Studi Islam belum dinamis} <br>FAKULTAS {PASCA SARJANA} Universitas Muhammadiyah {{env('NAME_City')}}</td>
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
          <td>IPK</td>
          <?php
          $ipk = 0;
          if ($data->Bnk != null && $data->Sks_Trough != null) {
            $ipk = $data->Bnk / $data->Sks_Trough;
          }
           ?>
          <td>: BNK / SKS = {{ $data->Bnk }}/{{ $data->Sks_Trough }} = {{ $ipk }}</td>
        </tr>
        <tr>
          <td></td>
          <td>Tempat Tanggal Lahir</td>
          <td>: {{ $data->Birth_Place }}, {{ $data->Birth_Date }}</td>
        </tr>
        <tr>
          <td></td>
          <td>Judul TA</td>
          <td>: {{ $data->Thesis_Title }}</td>
        </tr>
        <tr>
          <td></td>
          <td>Tittle of Thesis</td>
          <td>: {{ $data->Thesis_Title_Eng }}</td>
        </tr>
    </table>

<p>mengajukan permohonan untuk dapat melaksanakan Pendadaran Tugas Akhir yang insyaAllah akan dilaksanakan pada:</p>

<table id="table" style="width:80%; font-size:9pt;">
  <thead>
    <tr >
      <th width="25%">Hari</th>
      <th width="50%">Tanggal</th>
      <th width="25%">Jam</th>
    </tr>
  </thead>
  <tr>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>

<br>
  <table style="width:100%; font-size:12px;">
      <tr>
        <td width=" 5%"></td>
        <td width=" 95%">Demikian permohonan ini kami sampaikan, atas perhatian dan perkenannya kami ucapkan terimakasih.  </td>
      </tr>
      <tr>
        <td colspan="2"><i>Wassalamu'alaikum warahmatullahi wabarakatuh</i></td>
      </tr>
    </table>
<br><br>
<table id="table2" style="width:100%; font-size:10pt;">
  <tr>
    <td width="40%"></td>
    <td width="20%"></td>
    <td width="40%">{{env('NAME_City')}}, _______________ <br> Pemohon</td>
  </tr>
  <tr>
    <td height="50px"></td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>{{ $data->Full_Name}}</td>
  </tr>
</table>
  <br>
  <table id="table2" style="width:100%; font-size:10pt;">
    <tr>
      <td width="40%">Mengetahui</td>
      <td width="20%"></td>
      <td width="40%">Mengetahui</td>
    </tr>
    <tr>
      <td>Dosen Pembimbing 1</td>
      <td></td>
      <td>Dosen Pembimbing 2</td>
    </tr>
    <tr>
      <td height="50px"></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>{{$data->pem1}}</td>
      <td></td>
      <td>{{$data->pem2}}</td>
    </tr>
  </table>

  <br>

<div style="page-break-before:always; margin: -100px 0px 10px 40px;" >
  <table style="width:100%; font-size:15px;">
      <tr>
        <td colspan="2"><div style="float:right;">{{ $data->Term_Year_Name }}</div></td>
      </tr>
    </table><br>

    <div class="ex1">
    <table id="table3" style="width:100%; font-size:15px;">
        <tr>
          <td>No</td>
          <td>Dosen Penguji Pendamping </td>
        </tr>
        <tr>
          <td>1.<br>2.</td>
          <td> </td>
        </tr>
      </table>
    </div><br><br>

    <table id="table4" style="width:100%; font-size:10pt;">
        <tr>
          <td align="center">No</td>
          <td align="center"> Persyaratan </td>
          <td align="center"> Keterangan </td>
        </tr>
        <tr>
          <td align="center">1.</td>
          <td>Slip Pembayaran Pendadaran</td>
          <td></td>
        </tr>
        <tr>
          <td align="center">2.</td>
          <td>Fotocopy Tanda Lulus Sahadah syarat Pendadaran daru LPPI</td>
          <td></td>
        </tr>
        <tr>
          <td align="center">3.</td>
          <td>Berkas Permohonan Pendadaran diajukan paling lambat 4 hari sebelum pendadaran</td>
          <td></td>
        </tr>
        <tr>
          <td align="center">4.</td>
          <td>Mahasiswa Berpakaian rapi pada saat pelaksanaan Pendadaran</td>
          <td></td>
        </tr>
        <tr>
          <td align="center">5.</td>
          <td>Lulus semua mata kuliah (Nilai D maksimal 22 SKS)</td>
          <td></td>
        </tr>
        <tr>
          <td align="center">6.</td>
          <td>Fotocopy kuitansi pembayaran kelebihan waktu TA/Skripsi (jika ada)</td>
          <td></td>
        </tr>
        <tr>
          <td align="center">7.</td>
          <td>Lembar Monitoring TA (min 8x bimbingan)</td>
          <td></td>
        </tr>
        <tr>
          <td align="center">8.</td>
          <td>Masa TA Minimal 2 Bulan</td>
          <td></td>
        </tr>
        <tr>
          <td align="center">9.</td>
          <td>Bukti uji plagiasi dari Perpustakaan maksimal 20%</td>
          <td></td>
        </tr>
      </table>
  </div>
</body>
</html>
