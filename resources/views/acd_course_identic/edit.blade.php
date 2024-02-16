@extends('layouts._layout')
@section('pageTitle', 'Course Identic')
@section('content')

<?php

foreach($query_edit as $data_edit){


?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Matakuliah Setara</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/course_identic?department='.$data_edit->Department_Id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('course_identic.update', $data_edit->Course_Identic_Id) , 'method' => 'put', 'class' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  readonly value="{{ $data_edit->Department_Name }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm" name="Course_Id[]" multiple>
                @foreach ( $select_course as $data )
                  <option value="{{ $data->Course_Id }}">{{ $data->Course_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
          <br>
          <div class="table-responsive">
          <table class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>

                    <th width="20%">Kode Matakuliah</th>
                    <th width="70%">Matakuliah</th>
                    <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($query_edit_course as $data) {
                ?>
                <tr>
                    <td>{{ $data->Course_Code }}</td>
                    <td>{{ $data->Course_Name }}</td>
                    <td align="center">
                        {{-- {!! Form::open([ 'route' => ['course_identic.destroy_course', $data->Crs_Identic_Dtl_Id] , 'method' => 'delete', 'role' => 'form']) !!} --}}
                        {!! Form::button('Hapus', ['type'=>'button','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Crs_Identic_Dtl_Id]) !!}
                        {{-- {!! Form::close() !!} --}}
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
          </table>
          </div>

      </div>
    </div>
  </div>

<!-- /.row -->

</section>
<script type="text/javascript">
    var select = new SlimSelect({
    select: '#select'
    })
    select.selected()
</script>

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
                url: "{{ url('') }}/parameter/course_identic/destroy_course/"+id,
                type: "delete",
                dataType: "json",
                data: {
                  "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                  swal2(data);
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
  function swal2(url) {
    swal({
      title: 'Data telah dihapus',
      type: 'success', showConfirmButton:false,
      });
      if(url.Redirect != null){
        var url = {!! json_encode(url('/')) !!};
        window.location = url+"/parameter/course_identic";
      }else {
        window.location.reload();
      }
  }
        </script>
<?php
}
?>
@endsection
