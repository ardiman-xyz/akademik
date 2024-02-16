<?php
    $no = 1;
    $Fnc_Student_Payment2 = $Fnc_Student_Payment;
    $Student_Payment = $Fnc_Student_Payment->first();
    $Fnc_Reff_Payment = DB::table('fnc_reff_payment')->Where('Reff_Payment_Id','=',$Student_Payment->Reff_Payment_Id)->first();
    function penyebut($nilai) { 
 		$nilai = abs($nilai);
 		$huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
 		$temp = "";
 		if ($nilai < 12) {
 			$temp = " ". $huruf[$nilai];
 		} else if ($nilai <20) {
 			$temp = penyebut($nilai - 10). " belas";
 		} else if ($nilai < 100) {
 			$temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
 		} else if ($nilai < 200) {
 			$temp = " seratus" . penyebut($nilai - 100);
 		} else if ($nilai < 1000) {
 			$temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
 		} else if ($nilai < 2000) {
 			$temp = " seribu" . penyebut($nilai - 1000);
 		} else if ($nilai < 1000000) {
 			$temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
 		} else if ($nilai < 1000000000) {
 			$temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
 		} else if ($nilai < 1000000000000) {
 			$temp = penyebut($nilai/1000000000) . " milyar" . penyebut(fmod($nilai,1000000000));
 		} else if ($nilai < 1000000000000000) {
 			$temp = penyebut($nilai/1000000000000) . " trilyun" . penyebut(fmod($nilai,1000000000000));
 		}
 		return $temp;
 	}

 	function terbilang($nilai) {
 		if($nilai<0) {
 			$hasil = "minus ". trim(penyebut($nilai));
 		} else {
 			$hasil = trim(penyebut($nilai));
 		}
 		return $hasil;
 	}
?>
<head>
  <style media="screen">
      body{
        font-family: sans-serif;
        font-size: 11px;
      }
       .th{
         text-align: center;
       }
       .genap{
         background-color: #ffe;
       }
  </style>
</head>
<body>
  <table style="width: 100%">
    <tr>
      <td>No. Nota : {{ $Student_Payment->Reff_Payment_Id }}</td>
      <td rowspan="4" width="475px"><h1 align="center">BUKTI PEMBAYARAN</h1></td>
      <td>1. Putih : Mahasiswa</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2. Pink : Loket Keuangan</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>3. Kuning : YPTN</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>4. Hijau : Bank</td>
    </tr>
  </table>
  <br>

  {{--  isi kwitamnsi----------------------- --}}
  <?php
    $sum = 0;
    foreach ($Fnc_Student_Payment2 as $data_payment) {
      $sum += $data_payment->Payment_Amount;
    }
  ?>
  <table>
    <tr>
      <td style="width: 110">Telah diterima uang sejumlah</td>
      <td>:</td>
      <td style="width: 350;text-align: center"><b style="font-size: 17px">Rp {{ number_format($sum,'0',',','.') }},- </b><h2></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td style="text-align: center"><i><b style="font-size: 17px;text-transform: uppercase">{{ terbilang($sum) }}</b></i><h2></td>
    </tr>
  </table>

  {{-- dari mahasiswa ---------- --}}
  <table > 
    <tr>
      <td style="width: 110">Dari Mahasiswa</td>
      <td>&nbsp;</td>
      <td>Nama</td>
      <td>:</td>
      <td>{{ $Student_Payment->Full_Name }}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>NIM/SEMESTER</td>
      <td>:</td>
      <td>{{ $Student_Payment->Nim }}/20191</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>Program Studi</td>
      <td>:</td>
      <td>{{ $Student_Payment->Department_Name }}</td>
    </tr>
  </table>

  {{-- dari Rincian ---------- --}}
  <table> 
    <tr>
      <td style="width: 110">Rincian :</td>
    </tr>
  </table>
 
  {{-- Rincian Tagihan --}}
  <table >
    <?php 
    $sum2 = 0;  
    foreach ($Fnc_Student_Payment2 as $data_payment) {
        $class="ganjil";
        if( $no % 2 == 1 ){
         $class="genap";
        }
        ?>
        <tr class="<?php echo $class ?>">
          <td class="td" align="center">{{ $no }}.</td>
          <td class="td" style="width: 200">
            {{ $data_payment->Cost_Item_Name }}
          </td>
          <td align="right" class="td">
            Rp. {{ number_format($data_payment->Payment_Amount,'0',',','.') }} 
          </td>
        </tr>
        <?php
        $sum2 += $data_payment->Payment_Amount;
        $no++;
      }
    ?>
    
  </table>  
  
  <br>
  <br>
  
  <table>
    <tr>
      <td style="width: 480">&nbsp;</td>
      <td align="center">Yogyakarta, {{ Date("d F Y") }}</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td style="width: 480">&nbsp;</td>
      <td align="center">{{ Auth::user()->name }}</td>
    </tr>
  </table>
  <?php
  if ( isset($pdf) ) {
    $text = 'Page: {PAGE_NUM} from {PAGE_COUNT}';
    $font = Font_Metrics::get_font("helvetica", "bold");

    $pdf->page_text(770, 580, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0, 0, 0));
  }
 ?>
</body>
