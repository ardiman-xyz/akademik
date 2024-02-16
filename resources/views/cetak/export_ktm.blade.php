<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style type="text/css">
  @page {  size: 8.6cm 5.4cm;}
  /* #header { position: fixed; left: 0px; top: -110px; right: 0px; height: 0px; text-align: center; } */
  /* #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 10px; } */

  /* #example-table {
  background-image:url('{{ asset('img/ktm.png') }}');
  background-size: 300px 100px; */
  /* image courtesy of subtlepatterns.com */
  /* } */
  /* .images{
  background-image: url({{ url('img/ktm.jpg') }});
  } */

  /* <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css"> */

  @font-face {
      font-family: 'Ebrima';
      src: url("{{ asset('fonts/ebrima.ttf') }}");
      /* src: url('../fonts/ebrima.ttf'); */
  }

  @font-face {
      font-family: 'Edwardian';
      src: url("{{ asset('fonts/ITCEDSCR.TTF') }}");
      /* src: url('../fonts/ITCEDSCR.TTF'); */
  }

  @font-face {
     font-family: 'frutiger';
    font-style: normal;
    src: url("{{ url('fonts/FrutigerLTStd-Roman.otf')}}");
    /* src: url("{{ asset('fonts/FrutigerLTStd-Roman.otf') }}"); */
  }

  /* @font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: normal;
  src: url(http://themes.googleusercontent.com/static/fonts/opensans/v8/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf) format('truetype');
} */

  @font-face {
    font-family: 'Firefly';
    font-style: normal;
    font-weight: normal;
    src: url(http://example.com/fonts/firefly.ttf) format('truetype');
  }

  /* @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@600;800&display=swap'); */

  *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Open Sans', sans-serif;
    line-height: 7px;
    font-weight: bold;
  }

  .page_break { page-break-after: always; }
</style>
</head>
<body>
  @foreach ($data as $item)
  <div>
    <table style="width:100%; border-collapse:collapse ; font-size: 6pt">
      <tr>
        <td colspan=3 style="font-size:12pt; font-weight:700; padding-top:20px; padding-bottom:10px" align='center'>IDENTITAS MAHASISWA</td>
      </tr>
      <tr>
        <td style="padding:0" width='85px' rowspan="7" align='center' valign='middle'><img src="{{ public_path() }}/img/7100220020.jpg" width="60px" height="75px" ></td>
        <td width="90px" valign='top'><div><span style="display:inline-block;width:80px;">NIM</span> <span style='width:10px;padding-right:2px;'>:</span></div></td>
        <td valign='top'>{{ $item->Nim }}</td>
      </tr>
      <tr>
        <td valign='top'><div><span style="display:inline-block;width:82px;">NAMA</span><span style='width:10px;padding-right:2px;'>:</span></div></td>
        <td valign='top'>{{ $item->Full_Name }}</td>
      </tr>
      <tr>
        <td valign='top'><div><span style="display:inline-block;width:82px;">TEMPAT, TGL LAHIR</span><span style='width:10px;padding-right:2px;'>:</span></div></td>
        <td valign='top'>{{ $item->Birth_Place }}</td>
      </tr>
      <tr>
        <td valign='top'><div><span style="display:inline-block;width:82px;">FAKULTAS</span><span style='width:10px;padding-right:2px;'>:</span></div></td>
        <td valign='top'>{{ $item->Faculty_Name }}</td>
      </tr>
      <tr>
        <td valign='top'><div><span style="display:inline-block;width:82px;">PROGRAM STUDI</span><span style='width:10px;padding-right:2px;'>:</span></div></td>
        <td valign='top'>{{ $item->Department_Name }}</td>
      </tr>
      <tr>
        <td valign='top'><div><span style="display:inline-block;width:82px;">BERLAKU S.D.</span><span style='width:10px;padding-right:2px;'>:</span></div></td>
        <td valign='top'>{{ $tgl_akhir }}</td>
      </tr>
      <tr>
        <td valign='top'><div><span style="display:inline-block;width:82px;">ALAMAT</span><span style='width:10px;padding-right:2px;'>:</span></div></td>
        <td valign='top'>{{ $item->Address }}</td>
      </tr>
    </table>
    <br>
    @php 
    $data_rfid = "https://simak.umkendari.ac.id/"; 
    $qrcode = base64_encode(QrCode::format('png')
                ->merge('img/logo_univ.png', 0.2, true)
                ->size(250)
                ->generate($data_rfid));
    @endphp
    <table style="width:100%; border-collapse:collapse; padding-left:10px">
      <tr>
        <td width='34%' style="font-size:6pt; font-weight:400;">Pemilik kartu ini tercatat sebagai mahasiswa Universitas Muhammadiyah Kendari Tahun Akademik {{ $item->Entry_Year_Id }}/{{ $item->Entry_Year_Id+1 }}</td>
        <td width='20%' align='center' valign='middle'>
          <img width="70" height="70" src="data:image/png;base64, {{ $qrcode }} ">
        </td>
        <td width='46%' align='center' valign='middle'>
          @php
            $update = DB::table('mstr_signature')->where('Ttd_For','TTD KTM')->first();  
          @endphp
          @if ($update)
            <img src="{{ storage_path() }}/app/public/ttd/{{ $update->Value }}" width="100px" height="70px" >
          @endif
        </td>
      </tr>
    </table>
  </div>
  {{-- <div class="page_break"></div> --}}
  @endforeach
</body>
</html>
