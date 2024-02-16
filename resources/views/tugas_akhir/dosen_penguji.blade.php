@extends('layouts._layout')
@section('pageTitle', 'Tugas Akhir')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Dosen Penguji</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/tugas_akhir?department='.$department.'&term_year='.$term_year.'&angkatan='.$angkatan) }}" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
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
          {!! Form::open(['url' => route('tugas_akhir.storedosen_penguji') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          {{ csrf_field() }}
          {{ method_field('post') }}
          <input type="text" hidden name="thesis_id" id='thesis_id' value="{{$request->thesis}}">
          <div class="form-group">
            {!! Form::label('', 'Penguji', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="examiner" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="penguji">
                <option value="0">Pilih Penguji ...</option>
                @foreach($dosen as $data)
                <option value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Penguji Ke-', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="examiner_number" id='examiner_number'>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
          {!! Form::close() !!}
          <br>

          <div class="table-responsive col-md-10">
            <table class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>
                    <!-- <th class="col-sm-1">No</th> -->
                    <th width="7%">Penguji ke-</th>
                    <th width="7%">Nama Dosen</th>
                    <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
            </thead>
            <tbody>
              @foreach($penguji_in_thesis as $key)
              <tr>
                <td><center>{{$key->Order_Id}}</td>
                <td><center>{{$key->Full_Name}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<script type="text/javascript">
  var examiner = new SlimSelect({
  select: '#examiner'
  })
  examiner.selected()
</script>

@endsection
