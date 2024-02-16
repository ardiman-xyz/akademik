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
      <h3 class="text-white">Matakuliah Ditawarkan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
        <div class="pull-right tombol-gandeng dua">
            <a  href="{{ url('setting/offered_course?term_year='.$term_year.'&department='.$department.'&class_program='.$class_program.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <b>Matakuliah Ditawarkan</b>
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

          {!! Form::open(['url' => route('offered_course.storecopydata') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{$select_department->Department_Name}}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{$select_class->Class_Program_Name}}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Semester Asal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{$term_year}}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-2">Kopi Kesemester  :</label>
            <select class="form-control form-control-sm col-md-3" name="term_year_dest" onchange="document.form.submit();">
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="col-md-2">Kopi Ke Program Kelas  :</label>
            <select class="form-control form-control-sm col-md-3" name="class_prog_dest" onchange="document.form.submit();">
              <option value="">Pilih Program Kelas</option>
              @foreach ( $select_class_prog as $data )
                <option value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp
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
