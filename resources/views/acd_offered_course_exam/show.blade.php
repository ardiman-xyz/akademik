@extends('layouts._layout')
@section('pageTitle', 'Offered Course Exam')
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
      <h3 class="text-white">Jadwal dan Peserta Ujian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if(in_array('offered_course_exam-CanAdd', $acc))<a href="{{ url('setting/offered_course_exam/create?id='.$Offered_Course_id.'&class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&currentpage='.$currentpage.'&currentrowpage='.$currentrowpage.'&currentsearch='.$currentsearch.'&page='.$page.'&rowpage='.$rowpage) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          <a href="{{ url('setting/offered_course_exam?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <b>Edit Jadwal</b>
      </div>
    </div>
    <br>
        <div class="bootstrap-admin-box-title right text-white">
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('faculty.index') , 'method' => 'GET', 'name' => 'form', 'class' => 'row text-green', 'style' => 'margin-left:0%;', 'role' => 'form']) !!}
          <!-- Pencarian: &nbsp<input type="text" name="search"  class="form-control col-md-4" value="{{ $currentsearch }}" placeholder="Search">&nbsp -->
          <!-- Baris Per halaman : &nbsp<input type="number" name="rowpage" class="form-control form-control-sm col-md-4" value="{{ $currentrowpage }}" placeholder="Baris Per halaman">&nbsp
            <input type="submit" name="" class="btn btn-primary btn-sm" value="Cari"> -->
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
      <br>
      <div >
          <label class="col-sm-3">Kode Matakuliah</label>
          <label  class="col-sm-3">{{ $ma->Course_Code }}</label>
          <input hidden value="{{ $ma->Offered_Course_id }}" name="oci">
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

        <!-- @if(in_array('offered_course_exam-CanExportPresensi', $acc))<a href="{{ url('setting/offered_course_exam/'.$ma->Offered_Course_id.'/export/all?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program.'&exam_type=UAS') }}" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi UAS</a>@endif
        @if(in_array('offered_course_exam-CanExportPresensi', $acc))<a href="{{ url('setting/offered_course_exam/'.$ma->Offered_Course_id.'/export/all?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program.'&exam_type=UTS') }}" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi UTS</a>@endif
        @if(in_array('offered_course_exam-CanExportPresensi', $acc))<a href="{{ url('setting/offered_course_exam/'.$ma->Offered_Course_id.'/export/all?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program.'&exam_type=REMIDI') }}" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi Remidi</a>@endif -->
        <div class="table-responsive" >
        <table class="table table-striped table-font-sm" style="width:2000px;">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="5%">Kelompok</th>
                  <th width="8%">Jenis Ujian</th>
                  <th width="10%">Tanggal & Jam Ujian</th>
                  <th width="10%">Ruang</th>
                  <th width="15%">Pengawas 1</th>
                  <th width="15%">Pengawas 2</th>
                  <th width="10%">Jumlah Peserta</th>
                  @if(in_array('offered_course_exam-CanExportPresensi', $acc) || in_array('offered_course_exam-CanViewPeserta', $acc) || in_array('offered_course_exam-CanEdit', $acc) || in_array('offered_course_exam-CanDelete', $acc))
                  <th width="17%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
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
                  <td>{{ $data->Room_Name }}</td>
                  <td>{{ $data->Pengawas_1F }}{{ $data->Pengawas_1 }}{{ $data->Pengawas_1L }}</td>
                  <td>{{ $data->Pengawas_2F }}{{ $data->Pengawas_2 }}{{ $data->Pengawas_2L }}</td>
                  <?php
                    $peserta = DB::table('acd_offered_course_exam_member')
                    ->join('acd_offered_course_exam','acd_offered_course_exam.Offered_Course_Exam_Id','=','acd_offered_course_exam_member.Offered_Course_Exam_Id')
                    ->join('acd_offered_course','acd_offered_course.Offered_Course_Id','=','acd_offered_course_exam.Offered_Course_Id')
                    ->join('acd_student_krs' ,function ($join)
                    {
                      $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                      ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                      ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
                      ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id')
                      ->on('acd_student_krs.Student_Id','=','acd_offered_course_exam_member.Student_Id');
                    })
                    ->where('acd_offered_course_exam.Offered_Course_Exam_Id',$data->Offered_Course_Exam_Id)      
                    ->where('acd_student_krs.Is_Remediasi',1)               
                    ->get();
                    $c_peserta = count($peserta);
                  ?>
                  <td>
                  @if($data->Exam_Type_Id == 3)
                  {{$c_peserta}}
                  @else
                  {{ $data->Jml_Peserta }}
                  @endif
                  </td>
                  @if(in_array('offered_course_exam-CanExportPresensi', $acc) || in_array('offered_course_exam-CanViewPeserta', $acc) || in_array('offered_course_exam-CanEdit', $acc) || in_array('offered_course_exam-CanDelete', $acc))
                  <td>
                      {!! Form::open(['url' => route('offered_course_exam.destroy', $data->Offered_Course_Exam_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      @if($data->Jml_Peserta != 0)
                        @if(in_array('offered_course_exam-CanExportPresensi', $acc))<a href="{{ url('setting/offered_course_exam/'.$data->Offered_Course_Exam_Id.'/export?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program) }}" class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi</a>@endif
                      @else
                        @if(in_array('offered_course_exam-CanViewPeserta', $acc))<button disabled class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak Presensi </button>@endif
                      @endif
                      @if(in_array('offered_course_exam-CanEdit', $acc))<a href="{{ url('setting/offered_course_exam/'.$data->Offered_Course_Exam_Id.'/peserta?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program.'&currentpage='.$currentpage.'current&rowpage='.$currentrowpage.'&currentsearch='.$currentsearch.'&rowpage='.$currentrowpage.'&page='.$page.'&rowpage='.$currentrowpage.'&rowpage='.$rowpage) }}" class="btn btn-success btn-sm"><i class="fa fa-list"></i> Lihat Peserta</a>@endif
                      @if(in_array('offered_course_exam-CanDelete', $acc))<a href="{{ url('setting/offered_course_exam/'.$data->Offered_Course_Exam_Id.'/edit?department='.$department.'&term_year='.$term_year.'&class_program='.$class_program.'&currentpage='.$currentpage.'current&rowpage='.$currentrowpage.'&currentsearch='.$currentsearch.'&rowpage='.$currentrowpage.'&page='.$page.'&rowpage='.$currentrowpage.'&rowpage='.$rowpage) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Offered_Course_Exam_Id]) !!}
                      {!! Form::close() !!}
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

  <script>
  $(document).on('click', '.hapus', function (e) {
      e.preventDefault();
      var id = $(this).data('id');

    //  console.log(id);
      swal({
        title: 'Data Akan Dihapus',
          text: "Semua Peserta Akan Dihapus",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'cancel!',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: true
        }, function(isConfirm) {
      if (isConfirm) {
              $.ajax({
                  url: "{{ url('') }}/setting/offered_course_exam/" + id,
                  type: "DELETE",
                  dataType: "json",
                  data: {
                    "_token": "{{ csrf_token() }}"
                  },
                  success: function (data) {
                    swal2();
                  },
                  error: function(){
                    swal1();
                  }
              });
              // $("#hapus").submit();
            }
          });
  });
    function swal1() {
      swal({
        title: 'Data masih digunakan',
          type: 'error',
          showCancelButton: false,
          cancelButtonColor: '#d33',
          cancelButtonText: 'cancel!',
          cancelButtonClass: 'btn btn-danger',
        });
    }
    function swal2() {
      swal({
        title: 'Data telah dihapus',
        type: 'success', showConfirmButton:false,
        });
        window.location.reload();
    }
          </script>
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
