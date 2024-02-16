@extends('layouts._layout')
@section('pageTitle', 'KRS')
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
      <h3 class="text-white">KRS Per Matakuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">

            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>

          <b>KRS Per Matakuliah</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('krs_matakuliah.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->&nbsp &nbsp &nbsp &nbsp
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-4" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
            <!-- <label class="col-md-2">Tahun Semester :</label> -->
            <select class="form-control form-control-sm col-md-4" name="class_program"  onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>
          </div>
          <br>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Pencarian :</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $search }}" placeholder="Search">
            <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
            </div>
            <div  class="row col-md-5">
            <label class="col-md-5">Baris per halamam :</label>
            <select class="form-control form-control-sm col-md-7" name="rowpage" onchange="form.submit()">
              <option <?php if($rowpage == 10){ echo "selected"; } ?> value="10">10</option>
              <option <?php if($rowpage == 20){ echo "selected"; } ?> value="20">20</option>
              <option <?php if($rowpage == 50){ echo "selected"; } ?> value="50">50</option>
              <option <?php if($rowpage == 100){ echo "selected"; } ?> value="100">100</option>
              <option <?php if($rowpage == 200){ echo "selected"; } ?> value="200">200</option>
              <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
            </select>
            </div>
          </div><br>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="15%">Kode MK</th>
                  <th width="25%">Nama Matakuliah</th>
                  <th width="5%">Kelas</th>
                  <th width="10%">Kapasitas</th>
                  <th width="10%">Peserta Terdaftar</th>
                  <th width="10%">Peserta Belum Disetujui</th>
                  <th width="10%">Peserta Disetujui</th>
                  <th width="10%">Sisa Kuota</th>
                  @if(in_array('krs_matakuliah-CanViewDetail', $acc) || in_array('krs_matakuliah-CanExport', $acc))
                  <th width="25%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              $count = DB::table('acd_student_krs as a')->join('acd_student as b','a.Student_Id','=','b.Student_Id')->where([
                    ['a.Term_Year_Id',$data->Term_Year_Id],
                    ['b.Department_Id',$data->Department_Id],
                    ['b.Class_Prog_Id',$data->Class_Prog_Id],
                    ['a.Course_Id',$data->Course_Id],
                    ['a.Class_Id',$data->Class_Id],
                    ['a.Is_Approved',1],
                    ])->count();
              $countnull = DB::table('acd_student_krs as a')->join('acd_student as b','a.Student_Id','=','b.Student_Id')->where([
                    ['a.Term_Year_Id',$data->Term_Year_Id],
                    ['b.Department_Id',$data->Department_Id],
                    ['b.Class_Prog_Id',$data->Class_Prog_Id],
                    ['a.Course_Id',$data->Course_Id],
                    ['a.Class_Id',$data->Class_Id],
                    ['a.Is_Approved',null],
                    ])->count();
              $daftar = DB::table('acd_student_krs as a')->join('acd_student as b','a.Student_Id','=','b.Student_Id')->where([
                    ['a.Term_Year_Id',$data->Term_Year_Id],
                    ['b.Department_Id',$data->Department_Id],
                    ['b.Class_Prog_Id',$data->Class_Prog_Id],
                    ['a.Course_Id',$data->Course_Id],
                    ['a.Class_Id',$data->Class_Id],
                    ])->count();
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Class_Name }}</td>
                  <td>{{ $data->Class_Capacity }}</td>
                  <td <?php if($daftar == 0){ echo "style='color:#f78383;'"; } ?> >{{ $daftar }}</td>
                  <td <?php if($countnull == 0){ echo "style='color:#f78383;'"; } ?> >{{ $countnull }}</td>
                  <td <?php if($count == 0){ echo "style='color:#f78383;'"; } ?> >{{ $count }}</td>
                  <td>{{ $data->Class_Capacity - $count }}</td>
                  @if(in_array('krs_matakuliah-CanViewDetail', $acc) || in_array('krs_matakuliah-CanExport', $acc))
                  <td>
                      @if(in_array('krs_matakuliah-CanViewDetail', $acc))
                      <a href="{{ url('proses/krs_matakuliah/'.$data->Offered_Course_id.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search) }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>
                      @endif

                      @if(in_array('krs_matakuliah-CanExport', $acc))
                        @if($data->jml_peserta != 0)
                        <!-- <a href="{{ url('proses/krs_matakuliah/'.$data->Offered_Course_id.'/export'.'?type=Presensi&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">Presensi <i class="fa fa-print"></i> </a> -->
                        <!-- <a href="{{ url('proses/krs_matakuliah/'.$data->Offered_Course_id.'/export'.'?type=FormNilai&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">Form Nilai <i class="fa fa-print"></i></a>
                        <a href="{{ url('proses/krs_matakuliah/'.$data->Offered_Course_id.'/export'.'?type=BeritaAcaraUTS&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">Berita Acara UTS <i class="fa fa-print"></i></a>
                        <a href="{{ url('proses/krs_matakuliah/'.$data->Offered_Course_id.'/export'.'?type=BeritaAcaraUAS&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">Berita Acara UAS <i class="fa fa-print"></i></a> -->
                        @else
                        <!-- <button disabled class="btn btn-info btn-sm" style="margin:5px;" >Presensi <i class="fa fa-print"></i> </button> -->
                        <!-- <button disabled class="btn btn-info btn-sm" style="margin:5px;" >Form Nilai <i class="fa fa-print"></i></button>
                        <button disabled class="btn btn-info btn-sm" style="margin:5px;" >Berita Acara UTS <i class="fa fa-print"></i></button>
                        <button disabled class="btn btn-info btn-sm" style="margin:5px;" >Berita Acara UAS <i class="fa fa-print"></i></button> -->
                        @endif
                      @endif
                  </td>
                  @endif
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
