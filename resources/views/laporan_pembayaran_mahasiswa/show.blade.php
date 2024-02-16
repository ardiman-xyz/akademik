@extends('layouts._layout')
@section('pageTitle', 'Daftar Mahasiswa KRS')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Daftar Biaya Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('laporan/laporan_pembayaran_mahasiswa?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&search='.$search.'&rowpage='.$rowpage.'&entry_year_id='.$request->entry_year_id) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Rincian Biaya Mahasiswa</b>
        </div>
      </div>
        </div>

      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <br>
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="col-sm-5">
            <div >
              <label class="col-sm-4">Nama Mahasiswa</label>
              <label class="col-sm-4">: {{ $data->Full_Name }}</label>
            </div>
            <div>
              <label class="col-sm-4">NIM</label>
              <label class="col-sm-4">: {{ $data->Nim }}</label>
            </div>

          </div>
          <div class="col-sm-6">
            <div>
              <label class="col-sm-4">Prodi Mahasiswa</label>
              <label  class="col-sm-7">: {{ $data->Department_Name }}</label>
            </div>
            <div>
              <label class="col-sm-4">Program Kelas</label>
              <label  class="col-sm-7">: {{ $data->Class_Program_Name }}</label>
            </div>
          </div>
        </div>

        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
        <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="dataTagihanKRS">
                  <thead class="thead-default thead-green">
                    <tr>
                      <th>
                        Tahun
                      </th>
                      <th>
                        Semester
                      </th>
                      <th>
                        Tanggal Tagih
                      </th>
                      <th>
                        Item Biaya
                      </th>
                      <th>
                        Mata Kuliah
                      </th>
                      <th>
                        SKS x Harga Per SKS
                      </th>
                      <th>
                        Jumlah Nominal
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sumAmountKRS =0;
                    foreach ($ListTagihan as $tagihanKRS) {
                      $ganjilgenap = substr($tagihanKRS['Term_Year_Bill_Id'],-1);
                      ?>
                      <tr>

                        <td><?php echo substr($tagihanKRS['Term_Year_Bill_Id'],0,4) ?></td>
                        <td><?php if( $ganjilgenap == 1){echo "Ganjil";}elseif( $ganjilgenap == 2){echo "Genap";} ?></td>
                        <td align="center"><?php echo date('d/M/Y', strtotime($tagihanKRS['Start_Date']))." - ".date('d/M/Y', strtotime($tagihanKRS['End_Date']))?></td>
                        <td><?php echo $tagihanKRS['Cost_Item_Name'] ?></td>
                        <td><?php echo $tagihanKRS['Course_Name'] ?></td>
                        <td style="display:none">{{ $tagihanKRS['Amount'] }}</td> {{-- tagihan--------- --}}
                        <?php if ($tagihanKRS['Cost_Item_Id'] != 105){ 
                          if($tagihanKRS['SKS'] != null){ ?>
                          <td><?php echo $tagihanKRS['SKS'] ?> x <?php echo number_format($tagihanKRS['perSKS'],'0',',','.') ?></td>
                        <?php }else{ ?>
                          <td></td>
                        <?php }
                          ?>
                      <?php }else{
                        ?>
                        <td><?php echo $tagihanKRS['SKS'] ?> SKS, Rp. <?php echo number_format($tagihanKRS['Amount'],'0',',','.') ?> (Paket)</td>
                        <?php
                      } ?>
                        <td align="right"><?php echo number_format($tagihanKRS['Amount'],'0',',','.') ?></td>
                      </tr>
                      <?php
                      $sumAmountKRS += $tagihanKRS['Amount'];
                    }
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="6" align="right">
                        <b>Jumlah</b>
                      </td>
                      <td align="right" id="totalTagihanKRS">

                        <?php echo $jumlahKRS = number_format($sumAmountKRS,'0',',','.') ?>
                      </td>
                      <td style="display:none" id="totalTagihanKRSAsli">

                        <?php echo $jumlahKRS = number_format($sumAmountKRS,'0',',','.') ?>
                      </td>
                    </tr>
                  </tfoot>
                </table><br />

        <b>Riwayat Pembayaran</b>
        <div class="table-responsive" >
          <table class="table table-striped table-bordered table-hover table-sm table-font-sm" id="listTagihan">
            <thead class="thead-default thead-green">
              <tr>
                <th width="w-1">No.</th>
                <th width="w-1">No Kwitansi</th>
                <th width="w-4">Tgl Bayar</th>
                <th width="w-4">Jumlah</th>
                <th width="w-4">Bank</th>
                <th width="w-4"><i class="glyphicon glyphicon-cog"></i></th>
              </tr>
            </thead>
            <?php if ($Student_Payment != null) { ?>
            <tbody>
              <?php
              $no = 1 ;
              $sumpayment = 0;
                foreach ($Student_Payment as $payment) {
                  ?>
                  <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $payment->No_Kwitansi ?></td>
                    <td>
                      <?php
                      if ($payment->Payment_Date != null) {
                        echo date("d F Y",strtotime($payment->Payment_Date));
                      }else{
                        echo "-";
                      }
                      ?>
                    </td>
                    <td style="text-align:right;"><?php echo number_format($payment->Payment_Amount,'0',',','.') ?></td>
                    <td><?php echo $payment->Bank_Name ?></td>
                    <td style="text-align:center;">
                      <a title="Lihat Details" class="btn btn-sm btn-info details" href="javascript:;" onclick="modal_show(<?php  echo $payment->Reff_Payment_Id ?>)"><i class="glyphicon glyphicon-search"></i></a>&nbsp;
                      <a title="Cetak" class="btn btn-sm btn-success" href="{{ asset('laporan/laporan_pembayaran_mahasiswa/getdata/pdf/'.$payment->Reff_Payment_Id)}}" target="_blank"><i class="glyphicon glyphicon-print"></i></a>
                    </td>
                  </tr>
                  <?php
                  $sumpayment += $payment->Payment_Amount;
                }
               ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2" style="text-align:center;">Total</td>
                <td style="text-align:right;"><?php echo number_format($sumpayment,'0',',','.') ?></td>
                <td colspan="2"></td>
              </tr>
            </tfoot>
          <?php }else{
            ?>
            <td colspan="5" style="text-align:center;">Tidak ada data</td>
            <?php
          } ?>
          </table>
        </div>
        </div>

      </div>
    </div>
  </div>

    <div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <center>
      <div class="modal-content" style="width:50%; margin-top:10%;">
        <div class="modal-header">
            <br>
            <b>Detail Riwayat Pembayaran</b>
            <a href="javascript:" class="text-danger" id="close-modal">
                <i class="fa fa-close text-danger"></i>
            </a>
            <script type="text/javascript">
            $("#close-modal").click(function(){
               window.location.reload();
            });
            </script>
        </div>
        <div class="modal-body" id="modal-isi">
        </div>
      </div>
    </center>
  </div>
  <!-- <script type="text/javascript">
     $(document).ready(function () {
     $(".hapus").click(function(e) {
       var url = "{{ url('modal/faculty') }}"
           $("#detail").load(url);
           $("#detail").modal('show',{backdrop: 'true'});
        });
      });
  </script> -->

<!-- /.row -->
<!-- <script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script> -->
<script type="text/javascript">
  function modal_hide(){
    $("#detail").modal('hide');
  }
  function modal_show(ReffPaymentId) {
      // var ReffPaymentId = $(this).closest("tr").find("td").eq(0).html();
      // var link = $('#url_riwayat').val();
      $.ajax({
        type: "GET",
        url: "{{ url('laporan/laporan_pembayaran_mahasiswa/getdata/riwayat') }}",
        data: { Reff_Payment_Id: ReffPaymentId },
        // beforeSend: function () {
        //     document.getElementById('gifload').classList.add('show');
        // },
        success: function(data){
            // document.getElementById('gifload').classList.remove('show');
            $('#modal-isi').html(data);
            $("#detail").modal('show');
        },
        // error: function (data) {
        //     document.getElementById('gifload').classList.remove('show');
        //     alert('error'+data);
        // }
      });
  };


function fnExcelReport() {
    var tab_text = "<table border='2px'><tr><td colspan='6' align='center'>Daftar Matakuliah</td></tr><tr bgcolor='#87AFC6'>";
    var textRange; var j = 0;
    tab = document.getElementById('tbl'); // id of table

    for (j = 0 ; j < tab.rows.length ; j++) {
        tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text = tab_text + "</table>";


    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html", "replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus();
        sa = txtArea1.document.execCommand("SaveAs", true, "Global View Task.xls");
    }
    else //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
    return (sa);
}
</script>
</section>
@endsection
