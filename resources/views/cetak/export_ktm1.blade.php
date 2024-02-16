<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <style type="text/css">
  @page :first {   size: 21cm 33cm;margin: 20px 0px 0px 60px;}
  @page {  size: 21cm 33cm;margin: 20px 0px 0px 60px; }
  .page_break { page-break-after: always; }
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

  @font-face {
  font-family: 'Open Sans';
  font-style: normal;
  font-weight: normal;
  src: url(http://themes.googleusercontent.com/static/fonts/opensans/v8/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf) format('truetype');
}

@font-face {
  font-family: 'Firefly';
  font-style: normal;
  font-weight: normal;
  src: url(http://example.com/fonts/firefly.ttf) format('truetype');
}


  .bg{
    margin-top: 40px;
    margin-left: 0px;
  }
  .div1 {
    margin-top: 0px;
    margin-left: 0px;
  }

.div1  .name{
  /* font-family: 'Open Sans'; */
  font-family: firefly, DejaVu Sans, sans-serif;
  /* font-family: 'Nunito', sans-serif; */
  letter-spacing: -0.5px;
  text-transform: uppercase;
  font-size: 11.198pt;
    margin-top: -148px;
    margin-left: 93px;
    color: black;
    width: 235px;
  }
.div1  .nim{
  font-family: 'Ebrima';
  letter-spacing: -0.5px;
  font-size: 10.957pt;
    margin-top: -6px;
    margin-left: 93px;
  }
.div1  .dept{
  font-family: 'Ebrima';
  letter-spacing: -0.5px;
  font-size: 10.957pt;
    margin-top: -9px;
    margin-left:  93px;
  }
.div1  .dept2{
  font-family: 'Ebrima';
  letter-spacing: -0.5px;
  font-size: 10.957pt;
    margin-top: -9px;
    margin-left:  93px;
  }
  .div1  .barcode{
    margin-top: 0px;
    margin-left:  450px;
    height: 10px;
    width: 10px !important;
    font-size: 8pt !important;
  }
  .div1  .ketua{
    margin-top: -7px;
    margin-left:  599px;
    font-size: 6pt;
  }
.div1  .tgl_akhir{
  font-family: 'Ebrima';
    margin-top: 19px;
    margin-left:  20px;
  }
  .div1  .nm_ketua{
      margin-top: -2,5px;
      margin-left: 599px;
      font-size: 6pt;
      text-decoration: underline;
    }
  .div1  .nik{
      margin-top: 0px;
      margin-left: 599px;
      font-size: 5pt;
    }


.div1  .image{
    margin-top: -49px;
    margin-left: 10px;
  }
.div1  .qrcodes{
    margin-top: -44px;
    margin-left: -71px;
  }
.div1  .hitam{
    margin-top: -28px;
    margin-left: 165px;
  }

  .div2 {
    margin-top: 195px;
    margin-left: 0px;
  }

.div2  .name{
  /* font-family: 'Open Sans'; */
  font-family: firefly, DejaVu Sans, sans-serif;
  /* font-family: 'Nunito', sans-serif; */
  letter-spacing: -0.5px;
  text-transform: uppercase;
  font-size: 11.198pt;
    margin-top: -148px;
    margin-left: 93px;
    color: black;
    width: 235px;
  }
.div2  .nim{
  font-family: 'Ebrima';
  letter-spacing: -0.5px;
  font-size: 10.957pt;
    margin-top: -6px;
    margin-left: 93px;
  }
.div2  .dept{
  font-family: 'Ebrima';
  letter-spacing: -0.5px;
  font-size: 10.957pt;
    margin-top: -9px;
    margin-left:  93px;
  }
.div2  .dept2{
  font-family: 'Ebrima';
  letter-spacing: -0.5px;
  font-size: 10.957pt;
    margin-top: -95px;
    margin-left:  93px;
    width:180px;
    /* line-height:0.75; */
  }
  .div2  .barcode{
    margin-top: 0px;
    margin-left:  450px;
    height: 10px;
    width: 10px !important;
    font-size: 8pt !important;
  }
  .div2  .ketua{
    margin-top: -7px;
    margin-left:  599px;
    font-size: 6pt;
  }
.div2  .tgl_akhir{
  font-family: 'Ebrima';
    margin-top: 19px;
    margin-left:  20px;
  }
  .div2  .nm_ketua{
      margin-top: -2,5px;
      margin-left: 599px;
      font-size: 6pt;
      text-decoration: underline;
    }
  .div2  .nik{
      margin-top: 0px;
      margin-left: 599px;
      font-size: 5pt;
    }


.div2  .image{
    margin-top: -38px;
    margin-left: 10px;
  }
.div2  .qrcodes{
    margin-top: -33px;
    margin-left: -67px;
  }
.div2  .hitam{
    margin-top: -19px;
    margin-left: 171px;
  }


  /*

  .div1 .div2{
    width: 340px;
    height: 20px;
    background-color: #081F2B;
  }
  .div1 .div3{
    width: 340px;
    height: 40px;
    background-color: #000;
  }
  .div1 .div4{
    width: 321px;
    height: 150px;
    border: #red;
    background-color: white;
    margin-left: 8px;
  }
  .div1 .p1{
    color: #000;
    font-size: 6pt;
  } */
</style>
</head>
<body>
  <div>
    <?php
    foreach ($data as $data) {
      ?>

      {{-- <div class="div1">
        <div class="div2"></div>
        <div class="div3"></div>
        <div class="div4">
          <ol>
            <li class="p1">Kartu ini adalah Kartu Mahasiswa STTNAS Yogyakarta</li>
            <li class="p1">Kartu ini berlaku selama yang bersangkutan tercatat sebagai mahasiswa aktif
              STTNAS Yogyakarta</li>
            </ol>
        </div>
      </div> --}}
      @if($bg == 1)
      <div class="bg">&nbsp;</div>
      <div class="div2">
        <div class="name"><b>{{ $data->Full_Name }}</div>
        <div class="nim">{{ $data->Nim }}</div>
        <div class="dept">{{ $data->Program_Name }}</div>        
        <div class="tgl_akhir" style="font-size:5px;">
          <?php $date = strtotime($tgl_akhir);
          $birth = date('d-m-Y', $date);
          ?>
          Berlaku s/d {{$birth}}</div>
          <div class="nm_ketua"></div>
          <div class="nik"></div>
        <img class="image" src="<?php echo env('APP_URL')?>{{ 'foto_mhs/'.$data->Entry_Year_Id.'/'.$data->Nim.'.jpg' }}" width="75px" height="100px" >
        <img width="72" height="82" class="hitam" src="{{ ('img/scanme.png') }}" alt="">
        <img width="62" height="62" class="qrcodes" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')  
                         ->size(250)->errorCorrection('H')
                         ->generate('{{env('APP_URL')}}cek?get_data='.$data->Student_Id)) }} ">
        <div class="dept2">{{ $data->Department_Name }}</div>
        </div>
        
      </div>
      <div class="page_break"></div>
      @else
      <img class="bg" src="{{ ('img/ktm.png') }}" style="width:18.169 cm;" alt="">
      <div class="div1">
        <div class="name"><b>{{ $data->Full_Name }}</div>
        <div class="nim">{{ $data->Nim }}</div>
        <div class="dept">{{ $data->Program_Name }}</div>        
        <div class="tgl_akhir" style="font-size:5px;">
          <?php $date = strtotime($tgl_akhir);
          $birth = date('d-m-Y', $date);?>
          Berlaku s/d {{$birth}}</div>
          <div class="nm_ketua"></div>
          <div class="nik"></div>
        <img class="image" src="<?php echo env('APP_URL')?>{{ 'foto_mhs/'.$data->Entry_Year_Id.'/'.$data->Nim.'.jpg' }}" width="75px" height="100px" >
        <img width="72" height="82" class="hitam" src="{{ ('img/scanme.png') }}" alt="">
        <img width="62" height="62" class="qrcodes" src="data:image/png;base64, {{ base64_encode(QrCode::format('png')  
                         ->size(250)->errorCorrection('H')
                         ->generate('{{env('APP_URL')}}cek?get_data='.$data->Student_Id)) }} ">        
        </div>
        <div class="dept2">{{ $data->Department_Name }}</div>
        <!-- <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')
                         ->merge('img/logo_univ.png', 0.1, true)
                         ->size(100)->errorCorrection('H')
                         ->generate('http://10.20.14.69/simak_sttnas_gmail/public/setting/student/'.$data->Student_Id.'/edit?entry_year_id='.$data->Entry_Year_Id.'&department='.$data->Department_Id.'&rowpage=10&search=')) }} "></div> -->
        <!-- <div class="qrcode">
        <img src="data:image/png;base64, {{ base64_encode(QrCode::format('png')
                         ->merge('img/logo_univ.png', 0.5, true)
                         ->size(250)->errorCorrection('H')
                         ->generate('.$data->Nim.')) }} "></div> -->

        </div>
      </div>
      <div class="page_break"></div>
      @endif
        <?php
      }
      ?>
</body>
</html>
