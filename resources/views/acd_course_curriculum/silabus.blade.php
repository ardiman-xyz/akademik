@extends('layouts._layout')
@section('pageTitle', 'Silabus')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<style>
  th, td {
  padding: 15px;
  text-align: left;
}
</style>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Silabus</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($class_program != null && $class_program != 0 && $department != null && $department != 0 && $curriculum != null && $curriculum != 0)
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            <a href="{{ url('parameter/prasyarat/create?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&course_id='.$course_id.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>
            <a href="{{ url('parameter/course_curriculum?class_program='.$class_program.'&curriculum='.$curriculum.'&semester='.$semester.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>

          </div>
          @endif
          <b>Silabus</b>
        </div>
      </div>
          <br>
        </div>
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div  class="row col-md-7">
          <label class="col-md-3">Jurusan :</label>
          <label class="form-control-sm col-md-7 col-sm-7">{{ $departmentpra->Department_Name }}</label>
          </div>
          <div  class="row col-md-7">
          <label class="col-md-3">Kurikulum :</label>
          <label class="form-control-sm col-md-7 col-sm-7">{{ $curprasyarat->Curriculum_Name }}</label>
          </div>
          <div  class="row col-md-7">
          <label class="col-md-3">Matakuliah :</label>
          <label class="form-control-sm col-md-7 col-sm-7">{{ $coursepra->Course_Name }}</label>
          </div>
          <div  class="row col-md-7">
          <label class="col-md-3">Semester :</label>
          <label class="form-control-sm col-md-7 col-sm-7">{{ $coursecur->Study_Level_Id }}</label>
          </div>
        </div><br>
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
                  <th width="90%">Deskripsi Mata Kuliah</th>
                   @if(in_array('course_curriculum-CanEdit', $acc) || in_array('course_curriculum-CanDelete', $acc))
                  <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                   @endif
              </tr>
          </thead>
          <tbody>
            <?php
            // foreach ($prerequisite_detail as $data) {
              ?>
              <tr>
                  <td>Mahasiswa belajar beberapa tipe data input, transformasi Fourier dan Wavelet, memahami secara
                      komprehensif metode klasifikasi dengan pembelajaran terawasi dan tidak terawasi, dan metode optimasi
                      dengan algoritma evolutionary, serta reduksi dan transformasi data. Mahasiswa menerapkan metodemetode tersebut untuk studi kasus dalam bentuk tugas proyek, mulai dari data input, pemrosesan dan
                      ekstraksi data, reduksi data, menerapkan optimasi dan klasifikasi dengan pembelajaran terawasi dan tidak
                      terawasi, serta menuangkan hasil pemodelan dalam suatu makalah.
                      Pembelajaran terawasi meliputi multilayer perceptron, RBF, ANFIS, SVM, dan soft SVM. Pembelajaran tidak
                      terawasi meliputi variasi metode clustering. Metode optimasi meliputi algoritma evolutionary seperti
                      Genetic Algorithm (GA), Ant Colony (ACO), Particle Swarm Optimization (PSO), Artificial Bee Colony. Reduksi
                      dan transformasi data meliputi Principle Co</td>
                  @if(in_array('course_curriculum-CanEdit', $acc))
                  <td align="center">
                      <a href="#" class="btn btn-info btn-sm">Edit</a>
                  </td>
                  @endif
              </tr>
              <?php
            // }
            ?>
          </tbody>
        </table>
        </div>
        <?php echo $prerequisite_detail->render('vendor.pagination.bootstrap-4'); ?>
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
          text: "Klik hapus untuk menghapus data",
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
                  url: "{{ url('') }}/parameter/prasyarat/" + id,
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
