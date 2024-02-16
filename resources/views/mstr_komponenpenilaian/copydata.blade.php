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
      <h3 class="text-white">Kopi Komponen Penilaian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
        <div class="pull-right tombol-gandeng dua">
            <a  href="{{ url('parameter/komponen_penilaian/create?department='.$department.'&term_year='.$term_year.'&course_type='.$course_type) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <b>Kopi Komponen Penilaian</b>
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

          {!! Form::open(['url' => route('komponen_penilaian.storecopydata') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{$select_department->Department_Name}}" readonly class="form-control form-control-sm">
              <input type="text" value="{{$department}}" hidden name="dept_asal" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{$select_term_year->Term_Year_Name}}" readonly class="form-control form-control-sm">
              <input type="text" value="{{$term_year}}" hidden name="term_asal" readonly class="form-control form-control-sm">
            </div>
          </div>        
          <div class="form-group">
            {!! Form::label('', 'Tipe Matakuliah :', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="type_course" class="form-control form-control-sm col-md-4" name="type_course" onchange="document.form.submit();">
                <option value="0">Pilih Tipe</option>
                @foreach ( $select_course_type as $data )
                  <option <?php if($course_type == $data->Course_Type_Id){ echo "selected"; } ?> value="{{ $data->Course_Type_Id }}">{{ $data->Course_Type_Name }}</option>
                @endforeach
              </select>&nbsp
            </div>
          </div> 
               
          <div class="form-group">
            {!! Form::label('', 'Kopi Ke semester :', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="type_course" class="form-control form-control-sm col-md-4" name="term_dest" onchange="document.form.submit();">
                <option value="0">Pilih Semester Tujuan</option>
                @foreach ( $select_term_year_all as $data )
                  <option value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                @endforeach
              </select>&nbsp
            </div>
          </div> 

          <div class="form-group">
            {!! Form::label('', 'Kopi Ke Prodi :', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-4" name="dept_dest[]" multiple>
                <option value="0">Pilih Prodi</option>
                @foreach ( $get_department as $data )
                  <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
                @endforeach
              </select>&nbsp
              <script type="text/javascript">
                var select = new SlimSelect({
                select: '#select'
                })
                select.selected()
              </script>
            </div>
          </div> 
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
