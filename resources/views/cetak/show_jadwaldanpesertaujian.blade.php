@extends('layouts._layout')
@section('pageTitle', 'Cetak Jadwal peserta Ujian')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
?>
<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Cetak Jadwal dan Peserta Ujian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <a href="{{ url('cetak/jadwaldanpesertaujian?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <b>Jadwal</b>
      </div>
    </div>
    <br>
        <div class="bootstrap-admin-box-title right text-white">
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('faculty.index') , 'method' => 'GET', 'name' => 'form', 'class' => 'row text-green', 'style' => 'margin-left:0%;', 'role' => 'form']) !!}
          <!-- Pencarian: &nbsp<input type="text" name="search"  class="form-control col-md-4" value="{{ $currentsearch }}" placeholder="Search">&nbsp -->
          Baris Per halaman : &nbsp<input type="number" name="rowpage" class="form-control form-control-sm col-md-4" value="{{ $currentrowpage }}" placeholder="Baris Per halaman">&nbsp
            <input type="submit" name="" class="btn btn-primary btn-sm" value="Cari">
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
      <br>
      <div >
          <label class="col-sm-3">Kode Matakuliah</label>
          <label  class="col-sm-3">{{ $ma->Course_Code }}</label>
      </div>
      <div>
          <label  class="col-sm-3">Nama Matakuliah</label>
          <label  class="col-sm-3">{{ $ma->Course_Name }}</label>
      </div>
      <div>
          <label  class="col-sm-3">Kelas</label>
          <label  class="col-sm-3">{{ $ma->Class_Name }}</label>
      </div>

        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
        @if(in_array('offered_course_exam-CanExportPresensi', $acc))<a href="{{ url('setting/offered_course_exam/'.$ma->Offered_Course_id.'/export/all?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program.'&exam_type=UAS') }}" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi UAS</a>@endif
        @if(in_array('offered_course_exam-CanExportPresensi', $acc))<a href="{{ url('setting/offered_course_exam/'.$ma->Offered_Course_id.'/export/all?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program.'&exam_type=UTS') }}" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi UTS</a>@endif
        <div class="table-responsive" >
        <table class="table table-striped table-font-sm" style="width:2000px;">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="5%">Kelompok</th>
                  <th width="8%">Jenis Ujian</th>
                  <th width="10%">Tanggal Ujian</th>
                  <th width="10%">Jam Ujian</th>
                  <th width="10%">Ruangan</th>
                  <th width="15%">Pengawas 1</th>
                  <th width="15%">Pengawas 2</th>
                  <th width="10%">Jumlah Peserta</th>
                  <th width="17%"><center><i class="fa fa-gear"></i></center></th>
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
                  <td>{{ $data->Room_Number }}</td>
                  <td>{{ $data->Exam_Type_Code }}</td>
                  <td>{{ $data->Exam_Start_Date }}</td>
                  <td>{{ $data->Exam_Start_Date }}</td>
                  <td>{{ $data->Room_Name }}</td>
                  <td>{{ $data->Pengawas_1 }}</td>
                  <td>{{ $data->Pengawas_2 }}</td>
                  <td>{{ $data->Jml_Peserta }}</td>
                  <td>
                    @if(in_array('jadwaldanpesertaujian-CanExport', $acc))
                      @if($data->Jml_Peserta != 0)
                        <a href="{{ url('cetak/jadwaldanpesertaujian/'.$data->Offered_Course_Exam_Id.'/export?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program) }}" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi</a>
                      @else
                        <button disabled class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi </button>
                      @endif
                    @endif
                  </td>
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
</section>
@endsection
