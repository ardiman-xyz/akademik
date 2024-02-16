@extends('layouts._layout')
@section('pageTitle', 'KHS')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KHS Per Matakuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/khs_matakuliah/?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('khs_matakuliah.show',$Offered_Course_id) , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <label class="col-md-2">Cari NIM/Mahasiswa :</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-4" value="{{ $search }}" placeholder="NIM">&nbsp
            <label class="col-md-2" style="text-align:right;">Baris Per halaman :</label>
            <input type="number" name="rowpage"  class="form-control form-control-sm col-md-3" value="{{ $rowpage }}" placeholder="Baris Per halaman">&nbsp
            <input type="submit" name="" style="float:right;" class="btn btn-primary btn-sm" value="Submit">
          </div><br>
          {!! Form::close() !!}
        </div>

      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <br>
        <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="col-sm-6">
            <div >
              <label class="col-sm-5">Th Akademik/Semester</label>:
              <label class="col-sm-5"> {{ $data->Term_Year_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Program Studi</label>:
              <label class="col-sm-5"> {{ $data->Department_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Kelas Program</label>:
              <label class="col-sm-5"> {{ $data->Class_Program_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Matakuliah</label>:
              <label class="col-sm-5"> {{ $data->Course_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5"><a href="{{ url('proses/khs_matakuliah/bobot/'.$Offered_Course_id.'?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch) }}" class="col-sm-5 btn btn-primary btn-sm">Setting Bobot</i></a></label>
            </div>


          </div>
          <div class="col-sm-6">
            <div>
              <label class="col-sm-5">Kelas</label>:
              <label class="col-sm-5"> {{ $data->Class_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Kapasaitas</label>:
              <label class="col-sm-5"> {{ $data->Class_Capacity }}</label>
            </div>
            <div>
              <label class="col-sm-5">Terdaftar</label>:
              <label class="col-sm-5"> {{ $data->jml_peserta }}</label>
            </div>
            <div>
              <label class="col-sm-5">Sisa</label>:
              <label class="col-sm-5"> {{ $data->Class_Capacity - $data->jml_peserta }}</label>
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
                  <th width="15%">NIM</th>
                  <th width="15%">Nama Mahasiswa</th>
                  <th width="9%">Kode Nilai</th>
                  <th width="9%">Bobot</th>
                  <th width="9%">SKS KRS</th>
                  <th width="15%">Masuk Transkrip</th>
                  <th width="15%">SKS Transkrip</th>
                  {{-- @if(in_array('khs_matakuliah-CanEditDetail', $acc))
                  <th width="13%"><center><i class="fa fa-gear"></i></center></th>
                  @endif --}}
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
                  <td>{{ $data->Grade_Letter }}</td>
                  <td>{{ $data->weightvalue }}</td>
                  <td>{{ $data->Sks }}</td>
                  <td>
                    @if($data->Is_For_Transcript != 0)
                    Ya
                    @else
                    Tidak
                    @endif
                   </td>
                  <td>{{ $data->Transcript_Sks }}</td>

                  {{-- @if(in_array('khs_matakuliah-CanEditDetail', $acc))
                  <td>
                    <a href="{{ url('proses/khs_matakuliah/'.$data->Krs.'/edit?Offered_Course_id='.$Offered_Course_id.'&term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$currentpage.'&current_rowpage='.$currentrowpage.'&current_search='.$currentsearch) }}" class="btn btn-warning btn-sm" style="margin:5px;">Edit <i class="fa fa-edit"></i> </a>
                  </td>
                  @endif --}}
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
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
