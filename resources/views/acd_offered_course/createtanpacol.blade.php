@extends('layouts._layout')
@section('pageTitle', 'Offered Course')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Matakuliah Ditawarkan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            <a  href="{{ url('setting/offered_course?term_year='.$term_year.'&department='.$department.'&class_program='.$class_program.'&page='.$current_page.'&rowpage='.$current_rowpage.'&search='.$current_search.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Matakuliah Ditawarkan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif

          <div class="form-group">
            {!! Form::open(['url' => route('offered_course.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
            @foreach ($mstr_term_year as $data)
            <input type="hidden" name="Department_Id" value="{{ $department }}">
            <input type="hidden" name="Term_Year_Id" value="{{ $term_year }}">
            <input type="hidden" name="Class_Prog_Id" value="{{ $class_program }}">
            <input type="hidden" name="curriculum" value="{{ $curriculum }}">
            {!! Form::label('', 'Tahun/Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{ $data->Term_Year_Name }}" readonly class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            @foreach ($mstr_department as $data)
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{ $data->Department_Name }}" readonly class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            @foreach ($mstr_class_program as $data)
            {!! Form::label('', 'Nama Fakultas Eng', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{ $data->Class_Program_Name  }}" readonly class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Course_Id" id="course">
                <option value=""></option>
                @foreach ( $mstr_course as $data )
                  <option  <?php if(old('Course_Id') == $data->Course_Id ){ echo "selected"; }elseif($course_id == $data->Course_Id ){ echo "selected"; } ?> value="{{ $data->Course_Id }}">({{ $data->Course_Code }})  {{ $data->Course_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm" name="Class_Id[]" multiple >
                <option value=""></option>
                @foreach ( $mstr_class as $data )
                  <option  <?php if(old('Class_Id') == $data->Class_Id ){ echo "selected"; } ?> value="{{ $data->Class_Id }}">{{ $data->Class_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kapasitas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Capacity" min="1" value="{{ old('capacity') }}"  class="form-control form-control-sm">
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
          <br>
          <center>
          <div class="table-responsive col-md-10">
          <table class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>
                    <!-- <th class="col-sm-1">No</th> -->
                    <th width="7%">No</th>
                    <th width="7%">Nama Kelas</th>
                    <th width="7%">Kapasitas</th>
                    {{-- <th width="10%"><center><i class="fa fa-gear"></i></center></th> --}}
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($query as $data) {

                ?>
                <tr>
                    <!-- <th></th> -->
                    <td><center>{{ $a }}</center></td>
                    <td><center>{{ $data->Class_Name }}</center></td>
                    <td><center>{{ $data->Class_Capacity }}</center></td>
                    {{-- <td>
                        <center>
                        {!! Form::open(['url' => route('offered_course.destroy', $data->Offered_Course_id) , 'method' => 'delete', 'role' => 'form']) !!}
                        {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                        </center>
                    </td> --}}
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
<script type="text/javascript">
$(document).ready(function () {
  $("#course").change(function () {
    var url = {!! json_encode(url('/')) !!};
    var id = $("#course").val();
    var class_program = "<?php echo $class_program; ?>";
    var current_page = "<?php echo $current_page; ?>";
    var current_rowpage = "<?php echo $current_rowpage; ?>";
    var current_search = "<?php echo $current_search; ?>";
    var department = "<?php echo $department; ?>";
    var term_year = "<?php echo $term_year; ?>";
    var curriculum = "<?php echo $curriculum; ?>";    
    window.location = url+"/setting/offered_course/create?class_program="+ class_program + "&current_page=" + current_page + "&current_rowpage=" + current_rowpage + "&current_search=" + current_search + "&department=" + department + "&term_year=" + term_year + "&course="+id + "&curriculum="+ curriculum;
  });
});

var select = new SlimSelect({
select: '#select'
})

select.selected()

var course = new SlimSelect({
select: '#course'
})

course.selected()

</script>
<!-- /.row -->

</section>

@endsection
