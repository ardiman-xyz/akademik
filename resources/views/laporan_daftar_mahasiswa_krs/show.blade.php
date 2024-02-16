@extends('layouts._layout')
@section('pageTitle', 'Daftar Mahasiswa KRS')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Daftar Biaya KRS Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('laporan/laporan_daftar_mahasiswa_krs?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&search='.$search.'&rowpage='.$rowpage) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Rincian Biaya KRS Mahasiswa</b>
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
              <label class="col-sm-4">Program Kelaretferts</label>
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
        <table id="tbl" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="15%">No</th>
                  <th width="15%">Kode Matakuliah</th>
                  <th width="9%">Nama Matakuliah</th>
                  <th width="9%">Kelas</th>
                  <th width="9%">SKS</th>
                  <th width="15%">Biaya</th>
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
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->class_Name }}</td>
                  <td>{{ $data->Sks }}</td>
                  <td>{{ $data->Amount }}</td>
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
