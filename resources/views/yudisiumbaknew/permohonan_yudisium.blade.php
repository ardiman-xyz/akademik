<!DOCTYPE html>
<html>
<head>
  <h3><center><u>PERMOHONAN YUDISIUM</u></center></h3>
  <style>
    @page {
      size: legal;

    }

      #table2 {
      border-collapse: collapse;


    }

    #table2 td, #table2 th {
      border: 1px solid black;
    }

    #table2 th {
      text-align: left;
    }

    #pemohon {
      font-size: 12px;
    text-align: center;
  }
    #pemohon p{
    font: bold;
    font-size: 12px;
  }
    #bph {
    border: 1px solid black;
  }

    #bph td{
    padding-left: 20px;
    padding-top: 20px;
    padding-right: 10px;
    padding-bottom: 20px;
    text-align: justify;
    }
</style>
</head>
<body>
  <table style="width:100%; font-size:12px;">
      <tr><td>Kepada Yth,</td>
      <tr><td>Ketua Program Studi {{ $Education_prog_type->Acronym }} {{ $faculty->Faculty_Name }}</td></tr>
      <tr><td>Fakultas {{ $faculty->Faculty_Name }} STKIPMBB</td></tr>
      <tr><td style="height:10px;"></td></tr>
      <tr>
        <td><i>Assalamu'alaikum warahmatullaahi wabarakaatuh </i></td>
      </tr>
      <tr><td>Yang bertanda tangan di bawah ini,</td></tr>
      <tr><td></td></tr>
  </table>
  <table style="width:100%; font-size:12px;">
      <tr>
        <td width=" 5%"></td>
        <td width=" 20%"></td>
        <td width=" 75%"></td>
      </tr>
      <tr>
        <td></td>
        <td>Nama Mahasiswa</td>
        <td>{{$data->Full_Name}}</td>
      </tr>
      <tr>
        <td></td>
        <td>Nomor Mahasiswa</td>
        <td>{{$data->Nim}}</td>
      </tr>
      <tr>
        <td></td>
        <td>Tempat, Tanggal Lahir</td>
        <td>{{$data->Birth_Place}}, {{ $data->Birth_Date }}</td>
      </tr>
      <tr>
        <td></td>
        <td>Alamat Asal</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>Alamat Kos</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>Nama SLTA</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>NNomor Telepon</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>Nama SLTA</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>IPK</td>
        <td>{{ $query->ipk }}</td>
      </tr>
      <tr>
        <td></td>
        <td>Tanggal Seminar</td>
        <td></td>
      </tr>
      <tr>
        <td></td>
        <td>Judul TA Skripsi</td>
        <td>{{ $thesis->Thesis_Title }}</td>
      </tr>
      <tr>
        <td></td>
        <td>Judul TA Bhs. Inggris</td>
        <td>{{$thesis->Thesis_Title_Eng}}</td>
      </tr>
  </table>
  <p style="font-size:12px;">mengajukan permohonan untuk mengikuti Yudisium, dengan persyaratan sebagai berikut:</p>

  <table id="table2" style="width:100%; font-size:12px;">
    <thead>
    <tr >
      <th>No</th>
      <th>Persyaratan</th>
      <th>Keterangan</th>
    </tr>
  </thead>
    <tr>
      <td>1</td>
      <td>Daftar Nilai Lulus Seluruh Matakuliah (Nilai D maksimal 22 SKS) </td>
      <td></td>
    </tr>
    <tr>
      <td>2</td>
      <td>Fotocopy Ijazah SLTA</td>
      <td></td>
    </tr>
    <tr>
      <td>3</td>
      <td>Keterangan Bebas / Lunas Penggunaaan Alat Laboratorium dan Referensi</td>
      <td></td>
    </tr>
    <tr>
      <td>4</td>
      <td>Penyerahan Buku Tugas + CD Tugas Akhir </td>
      <td></td>
    </tr>
    <tr>
      <td>5</td>
      <td>Fotocopy Sertifikat Toefl (Bhs Inggris II), Score Nilai Minimal : 400 </td>
      <td></td>
    </tr>
  </table>

    <p style="font-size:12px;margin-left: 25;">Demikian permohonan ini saya sampaikan, atas perhatiannya say ucapkan terima kasih.</p>
    <p style="font-size:12px;"><i>Wassalamu'alaikum warahmatullaahi wabarakaatuh</i>

      <table id="pemohon" style="width:100%;">
          <tr>
            <td width="50%"></td>
            <td width="50%">Tanggal, <?php echo date('d-m-Y'); ?></td>
          <tr>
            <td></td>
            <td>Pemohon</td></tr>
            <tr>
              <td></td>
              <td height="40px"> </td>
          <tr>
            <td></td>
            <td><p>{{$data->Full_Name}}</p></td></tr>
      </table>

      <table id="bph" style="width:100%; font-size:13px;">
          <tr>
            <td colspan="3">Badan Pelaksana Harian (BPH UMY) dan Bagian Keuangan UMY menerangkan bahwa mahasiswa tersebut di atas sudah melunasi semua kewajiban pembayaran dan mahasiswa tersebut sudah tidak mempunyai tunggakan keuangan sampai dengan semester :</td>
          </tr>
          <tr>
            <td>Tanggal :_________________<br>Badan Pelaksana Harian (BPH) UM</td>
            <td></td>
            <td>Tanggal :_________________<br>Bagian Keuangan UMY</td>
          </tr>
          <tr>
            <td>___________________________<br>(paraf dan cap)</td>
            <td></td>
            <td>___________________________<br>(paraf dan cap)</td>
          </tr>
      </table>
  </table>
</body>
</html>
