@extends('layouts._layout')
@section('pageTitle', 'Offered Course')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Matakuliah Ditawarkan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/offered_course?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit Dosen</b>
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
          <div class="form-group">
            {!! Form::open(['url' => route('offered_course.update_employee', $data_edit->Offered_Course_id) , 'method' => 'put', 'class' => 'form']) !!}
            <div class="col-md-12">
              <input type="hidden" name="Offered_Course_id" value="{{ $data_edit->Offered_Course_id }}">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kode Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Code"  value="{{ $data_edit->Course_Code }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Matakuliah Kurikulum', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Name" value="{{ $data_edit->Course_Name }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'kelas yang ditawarkan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Class_Name" min="1" value="{{ $data_edit->Class_Name }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kapasitas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Class_Capacity"  value="{{ $data_edit->Class_Capacity }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Dosen Pengampu', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input id="text" hidden value="{{$term_year}}" class="form-control form-control-sm" name="term_year" >
              <input id="text" hidden value="{{$curriculum}}" class="form-control form-control-sm" name="curriculum" >
              <select id="select"  class="form-control form-control-sm" name="Employee_Id[]" multiple>
                  @foreach($new_employes as $employee)
                  <option  value="{{ $employee['Employee_Id'] }}">{{ $employee['Full_Name'] }} / {{$employee['Sisa_Sks_Dosen']}}</option>
                  @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <div class="col-md-12">
             <input type="checkbox" onchange="Change(this);" name="" value=""> Tambah dosen dari luar prodi?
             </div>
          </div>
          <div class="form-group" id="dosen_luar">
            {!! Form::label('', 'Dosen Pengampu Dari Luar Prodi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select2"  class="form-control form-control-sm" name="Employee_Id[]" multiple>
                  @foreach($new_employes2 as $employee)
                  <option  value="{{ $employee['Employee_Id'] }}">{{ $employee['Full_Name'] }} / {{$employee['Sisa_Sks_Dosen']}}</option>
                  @endforeach
              </select>
            </div>
          </div>
          <script type="text/javascript">
          var select = new SlimSelect({
          select: '#select'
          })
          select.selected()

           $("#dosen_luar").hide();
          function Change(checkbox) {
              var id = $(checkbox).val();
              if(checkbox.checked == true){
                $("#dosen_luar").show();
              }else {
                $("#dosen_luar").hide();
              }
          }

          var select = new SlimSelect({
          select: '#select2'
          })
          select.selected()
          </script>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>


          {!! Form::close() !!}
          <br>
          <center>
          <div class="table-responsive col-md-10">
          <table class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>
                    <!-- <th class="col-sm-1">No</th> -->
                    <th width="7%">Nama Dosen</th>
                    <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($query as $data) {
                ?>
                <tr>
                    <td><center>{{ $data->First_Title }} {{ $data->Name }} {{ $data->Last_Title }}</center></td>
                    <td>
                        <center>
                        {!! Form::open(['url' => route('offered_course.destroy_employee', $data->Acd_Offered_Course_Lecturer) , 'method' => 'delete', 'role' => 'form']) !!}
                        {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Acd_Offered_Course_Lecturer]) !!}
                        {!! Form::close() !!}
                        </center>
                    </td>
                </tr>
                <?php
                $a++;
              }
              ?>
            </tbody>
          </table>
          </div>
        </center>


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
                  url: "{{ url('') }}/setting/offered_course/"+id+"/destroy_employee" ,
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
<!-- /.row -->

</section>

<?php
}
?>
@endsection
