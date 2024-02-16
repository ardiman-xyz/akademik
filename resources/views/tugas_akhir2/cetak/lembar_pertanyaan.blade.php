<!DOCTYPE html>
<html>
<head>
  <style>
    @page { margin: 130px 60px 190px 60px; }
    @page :first {  margin: 190px 60px 0px 60px;}
    #footer { position: relative; left: 0px; bottom: -10px; right: 0px; height: 10px; }

    p{
      font-size: 13px;
    }
    #table td, #table th {
      height: 25px;
      text-align: center;
    }
    #table2{
      border-collapse:collapse;
    }
    #table2 td, #table2 th {
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
        <td width=" 70%"><center><b>LEMBAR PERTANYAAN</b></td>
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
          <td>Dosen pembimbing</td>
          <td>:</td>
          <td>I.&nbsp;&nbsp;&nbsp;{{$data->pembimbing_1}}</td>
        </tr>
        <tr>
          <td></td>
          <td></td>
          <td>:</td>
          <td>II.&nbsp;&nbsp;{{$data->pembimbing_2}}</td>
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
          <td style="font-size:20px;">
          <p><b><u>PERTANYAAN :</u></b></p>
          </td>
        </tr>
    </table>
    <table id="table" style="width:100%; font-size:10pt;">
        <tr>
          <td width=" 1%">&nbsp;</td>
          <td width=" 99%"><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
        <tr>
          <td> &nbsp; </td>
          <td><hr></td>
        </tr>
    </table>
    <br>
    <div  id="footer">
      <table id="table2" style="width:100%; font-size:9pt;">
        <thead>
          <tr >
            <th width="25%">Dosen Pembimbing 1</th>
            <th width="25%">Dosen Pembimbing 2</th>
            <th width="25%">Dosen Penguji 1</th>
            <th width="25%">Dosen Penguji 2</th>
            <th width="25%">Dosen Penguji 3</th>
          </tr>
        </thead>
        <tr>
          <td style="height:120px;" valign="bottom">{{ $data->pembimbing_1 }}</td>
          <td style="height:120px;" valign="bottom">{{ $data->pembimbing_2 }}</td>
          <td style="height:120px;" valign="bottom">{{ $data->penguji_1 }}</td>
          <td style="height:120px;" valign="bottom">{{ $data->penguji_2 }}</td>
          <td style="height:120px;" valign="bottom">{{ $data->penguji_3 }}</td>
        </tr>
      </table>
    </div>


</body>
</html>
