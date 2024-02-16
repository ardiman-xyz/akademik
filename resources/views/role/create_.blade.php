@extends('layouts._layout')
@section('pageTitle', 'Role')
@section('content')
<section class="content">


  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Role</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/role?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
          {!! Form::open(['url' => route('role.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Nama', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="nama" value="{{ old('nama') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          {!! Form::label('', 'Otoritas', ['class' => 'col-md-4 form-label']) !!}
          <div class=" col-md-12 col-xs-12" style="padding:2%;">
            <div class="table-responsive">
            <table class="table table-striped table-font-sm">
                <thead class="thead-default thead-green">
                    <tr>
                        <th width="85%">SIMAKAD</th>
                        <th width="15%"><input type="checkbox" id="allsimak" /></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($access as $acc)
                    <tr>
                      <td>{{ $acc->description }}</td>
                      <td><center><input class="rolesimak" type="checkbox" name="access[]" value="{{ $acc->id }}"><center></td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>
            <div class="table-responsive">
            <table class="table table-striped table-font-sm">
                <thead class="thead-default thead-green">
                    <tr>
                        <th width="85%">KEUANGAN</th>
                        <th width="15%"><input type="checkbox" id="allkeu" /></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($accesskeu as $acck)
                    <tr>
                      <td>{{ $acck->description }}</td>
                      <td><center><input class="rolekeu" type="checkbox" name="accesskeu[]" value="{{ $acck->id }}"><center></td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>

          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>


  <script type="text/javascript">
    $(document).ready(function () {
      $('#allsimak').click(function () {
          $('.rolesimak').prop("checked", this.checked);
      });
    });
    $(document).ready(function () {
      $('#allkeu').click(function () {
          $('.rolekeu').prop("checked", this.checked);
      });
    });
  </script>
</section>

@endsection
