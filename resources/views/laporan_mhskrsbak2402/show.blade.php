@extends('layouts._layout')
@section('pageTitle', 'Daftar Mahasiswa KRS')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">DETAIL MAHASISWA AKTIF</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('laporan/laporan_mhskrs?term_year='.$term_year.'&prog_kelas='.$prog_kelas.'&angkatan='.$angkatan) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail Mahasiswa Aktif Prodi {{$department->Department_Name}} th/smt {{ $thsmt->Term_Year_Name }}</b>
        </div>
      </div>
        </div>

      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <br>
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
<input type="text" name="id_prod" hidden value="{{ $id }}">
          @if(in_array('laporan_mhskrs-CanExport', $acc))
          <a href="{{ url('laporan/laporan_mhskrs/exportexcel/exportexcel?term_year='.$term_year.'&prog_kelas='.$prog_kelas.'&angkatan='.$entry_year.'&department='.$id) }}" target="_blank" class="btn btn-primary btn-sm" style="float:left; font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a>
          @endif
        <div class="table-responsive">
          <br>
        <table id="tbl" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="10%">No</th>
                  <th width="15%">Nim</th>
                  <th width="60%">Nama Mahasiswa</th>
                  <th width="15%">Program Kelas</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{$a}}</td>
                  <td>{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
                  <td>{{ $data->Class_Program_Name }}</td>
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>

      </div>
    </div>
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
