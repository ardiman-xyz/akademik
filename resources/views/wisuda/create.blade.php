@extends('layouts._layout')
@section('pageTitle', 'Wisuda')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Peserta Wisuda</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/wisuda?department='.$department.'&periode='.$periode.'&tampilan='.$tampilan) }}" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <b>Tambah</b>
      </div>
    </div>
    <br>

      {!! Form::open(['url' => route('wisuda.store') , 'method' => 'POST', 'role' => 'form']) !!}
      <!-- <input type="hidden" name="Offered_Course_id" value="">
      <input type="hidden" name="Term_Year_Id" value="">
      <input type="hidden" name="Department_Id" value="">
      <input type="hidden" name="Entry_Year_Id" value="">
      <input type="hidden" name="Course_Id" value="">
      <input type="hidden" name="Class_Prog_Id" value="">
      <input type="hidden" name="Class_Id" value=""> -->
      <input type="hidden" name="Graduation_Periode_Id" value="{{ $periode }}">


      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if(Session::get('success') == true)
            @if (count($errors) > 0)
              @foreach ( $errors->all() as $error )
                <p class="alert alert-success">{{ $error }}</p>
              @endforeach
            @endif
        @else
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
        @endif
        <input type="hidden" name="Offered_Course_id" value="">
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%"><input type="checkbox" id="all" /></th>
                  <th width="40%">NIM</th>
                  <th width="50%">Nama Mahasiswa</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $value) {
            ?>
            <tr>
              {{-- <td>{{ $a }}</td> --}}
              <td><center><input type="checkbox" class="student" name="Student_Id[]" value="{{ $value->Student_Id }}" /></center></td>
              <td><center>{{ $value->Nim }}</center></td>
              <td>{{ $value->Full_Name }}</td>
            </tr>
            <?php
            $a++;
            }
            ?>
          </tbody>
        </table>
      </div>
      <div align="center">
        <input type="submit" name="" value="Simpan" class="btn btn-primary">
      </div>
    </div>
    {!! Form::close() !!}
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#all').click(function () {
          $('.student').prop("checked", this.checked);
      });
    });
  </script>

</section>
@endsection
