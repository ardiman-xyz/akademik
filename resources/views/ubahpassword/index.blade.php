@extends('layouts._layout')
@section('pageTitle', 'Change Password')
@section('content')

<section class="content">


  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Ubah Password</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Ubah Password</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Password Berhasil diubah")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        {!! Form::open(['url' => route('ubahpassword.store') , 'method' => 'POST', 'class' => 'form', 'role' => 'form']) !!}
        <div class="form-group">
          {!! Form::label('', 'Email', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <select id="select"  class="form-control form-control-sm col-md-3" name="id" onchange="document.form.submit();" >
              <option value="0">Pilih Email</option>
                @foreach($user as $ni)
                <option <?php if($id == $ni->id){ echo "selected"; } ?> value="{{ $ni->id }}">{{ $ni->email }}</option>
                @endforeach
            </select>
            <script type="text/javascript">
            var select = new SlimSelect({
            select: '#select'
            })
            select.selected()
            </script>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Password', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <input type="password" name="password" min="1" value="" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-3">
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Confirm Password', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <input type="password" name="confirm" min="1" value="" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-3">
          </div>
        </div>

        <!-- <div class=" col-md-12 col-xs-12"> -->
          <br><center><button type="submit" class="btn btn-primary btn-flat">Ubah</button></center>
        <!-- </div> -->
        <!-- <center><a onclick="tambah()" class="btn btn-primary">OK</a></center> -->
        {!! Form::close() !!}
      </div>
    </div>
  </div>



<!-- /.row -->

</section>
@endsection
