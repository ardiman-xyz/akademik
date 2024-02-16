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
        font-size: 12px;
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
  <table>
    <tr>
      <td align="center">
          <img style="margin-left: 0px;width:715px; height: auto;" src="{{ ('img/header.png') }}" align="center" alt="">
      </td>
    </tr>
    <tr>
      {{-- <td style="vertical-align:bottom;text-align:right;">
        <span style="font-size:10px;"></span>
      </td> --}}
    </tr>
  </table>
  <hr>
  <table >
    <tr>
      <td>Tgl Transaksi</td>
      <td>:</td>
      <td><?php
      if ($Fnc_Reff_Payment->Payment_Date != null) {
        echo date("d m Y",strtotime($Fnc_Reff_Payment->Payment_Date));
      }else{
        echo "-";
      }
      ?></td>
    </tr>
    <tr>
      <td>ID Tagihan</td>
      <td>:</td>
      <td><?php echo $Student_Payment->Reff_Payment_Code ?></td>
    </tr>
      <tr>
        <td>Nama</td>
        <td>:</td>
        <td><?php echo $Student_Payment->Full_Name ?></td>
      </tr>
      <tr>
        <td>Nomor Induk</td>
        <td>:</td>
        <td><?php echo $Student_Payment->Nim ?></td>
      </tr>
      <tr>
        <td>Fakultas</td>
        <td>:</td>
        <td><?php echo $Student_Payment->Faculty_Name ?></td>
      </tr>
      <tr>
        <td>Prodi</td>
        <td>:</td>
        <td><?php echo $Student_Payment->Department_Name ?></td>
      <tr>
        <td>Bank</td>
        <td>:</td>
        <td><?php echo $Student_Payment->Bank_Name ?></td>
      </tr>
  </table>
  <h4>Item Pembayaran</h4>
  <table style="width:100%;">
      <tr>
        <th class="th">No</th>
        <th class="th">Deskripsi</th>
        <th class="th">Jumlah</th>
      </tr>
      <?php
      $sum=0;
      foreach ($Fnc_Student_Payment2 as $data_payment) {
        $class="ganjil";
        if( $no % 2 == 1 ){
         $class="genap";
        }
        ?>
        <tr class="<?php echo $class ?>">
          <td class="td" align="center"><?php echo $no ?></td>
          <td class="td">
            <?php echo $data_payment->Cost_Item_Name ?>
          </td>
          <td align="right" class="td">
            Rp.<?php echo number_format($data_payment->Payment_Amount,'0',',','.') ?>
          </td>
        </tr>
        <?php
        $sum += $data_payment->Payment_Amount;
        $no++;
      }
      $class1 ="ganjil";
      $no1 = $no ;
      if( $no1 % 2 == 1 ){
       $class1="genap";
      }
      ?>
      <tr class="<?php echo $class1 ?>">
        <br>
        <td colspan="2" class="td"><b>Total Dibayar</b></td>
        <td align="right" class="td"><b>Rp. <?php echo number_format($sum,'0',',','.') ?></b></td>
      </tr>

  </table>
  <table style="width:100%">
    <tr>
      <td style="width:50%;">Terbilang</td><td style="width:50%;text-align:right;"><?php echo terbilang($sum) ?></td>
    </tr>
  </table>
  <table style="width:100%">
    <tr>
      <td style="width:50%;">Total Data</td><td style="width:50%;text-align:right;"><?php echo $no - 1 . " data"  ?></td>
    </tr>
  </table>
  <br>
  <table style="font-size:10px;">
    <tr>
      <td>Petugas penerima</td>
      <td> : <?php echo $Fnc_Reff_Payment->Created_By ?></td>
    </tr>
    <tr>
      <td>Petugas Cetak</td>
      <td> : <?php echo Auth::user()->name; ?></td>
    </tr>
    <tr>
      <td>Tgl Cetak</td>
      <td> : <?php echo Date("d F Y"); ?></td>
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
