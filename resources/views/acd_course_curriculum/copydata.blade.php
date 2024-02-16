@extends('layouts._layout')
@section('pageTitle', 'Offered Course')
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
      <h3 class="text-white">Kopi Kurikulum</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
        <div class="pull-right tombol-gandeng dua">
            <a  href="{{ url('parameter/course_curriculum?semester='.$semester.'&department='.$department.'&class_program='.$class_program.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <b>Kopi Kurikulum</b>
        </div>
      </div>
      <br>

      @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Kopi Data")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif

          {!! Form::open(['url' => route('course_curriculum.storecopydata') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{$select_department->Department_Name}}" readonly class="form-control form-control-sm">
              <input type="text" value="{{$department}}" hidden name="dept_asal" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kurikulum', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{$select_curriculum->Curriculum_Name}}" readonly class="form-control form-control-sm">
              <input type="text" value="{{$curriculum}}" hidden name="cur_asal" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Kelas Asal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{$select_class->Class_Program_Name}}" readonly class="form-control form-control-sm">
              <input type="text" value="{{$class_program}}" hidden name="class_asal" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <!-- <input type="text" min="1" value="{{$semesterstr}}" readonly class="form-control form-control-sm">
              <input type="text" value="{{$semester}}" readonly class="form-control form-control-sm"> -->
              <select class="form-control form-control-sm col-md-4" name="semester_asal" onchange="document.form.submit();">
                <option value="">Pilih Semester</option>
                @foreach ( $select_semester_curriculum as $data )
                  <option <?php if($semester == $data->Study_Level_Id){ echo "selected"; } ?>  value="{{ $data->Study_Level_Id }}">{{ $data->Study_Level_Id }}</option>
                @endforeach
                <option <?php if($semester == 999){ echo "selected"; } ?> value="999">Semua Semester</option>
              </select>
            </div>
          </div>          
          <div class="form-group">
            {!! Form::label('', 'Kopi Ke Program Kelas :', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-4" name="class_dest" onchange="document.form.submit();">
                <option value="0">Pilih Program Kelas</option>
                @foreach ( $select_class_program as $data )
                  <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
                @endforeach
              </select>&nbsp
            </div>
          </div> 
          
          <input type="hidden" name="Department_Id" value="{{ $department }}">
            <input type="hidden" name="Term_Year_Id" value="{{ $term_year }}">
            <input type="hidden" name="Class_Prog_Id" value="{{ $class_program }}">
            <input type="hidden" name="curriculum" value="{{ $curriculum }}">
          
          <button type="submit" class="btn btn-primary btn-flat">Kopi Data</button>
          {!! Form::close() !!}
          <br>
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
                  url: "{{ url('') }}/setting/offered_course/" + id,
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
