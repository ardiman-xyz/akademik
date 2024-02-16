@extends('layouts._layout')
@section('pageTitle', 'User')
@section('content')

<section class="content">


  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah User</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/user?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Menambah User")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        {!! Form::open(['url' => route('user.store') , 'method' => 'POST', 'class' => 'form', 'role' => 'form']) !!}
        <!-- <div class="form-group">
            <div class="col-md-12">
             <input type="checkbox" onchange="Change(this);" name="" value=""> Email yang Sudah Ada?
             </div>
          </div> -->
          <div class="form-group" id="email_ada">
            {!! Form::label('', 'Email', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select2"  class="form-control form-control-sm" name="Email_Id" >
                  <option  value="0"></option>
                @foreach($user as $key)
                  <option  value="{{$key->id}}">{{ $key->email }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <script type="text/javascript">
          var select = new SlimSelect({
          select: '#select2'
          })
          select.selected()            
          //  $("#email_ada").hide();
          //  $("#add_name").show();
          //  $("#add_email").show();
          //  $("#add_password").show();
          //  $("#add_confirm").show();
          // function Change(checkbox) {
          //     var id = $(checkbox).val();
          //     if(checkbox.checked == true){
          //       $("#email_ada").show();
          //       $("#add_name").hide();
          //       $("#add_email").hide();
          //       $("#add_password").hide();
          //       $("#add_confirm").hide();
          //     }else {
          //       $("#email_ada").hide();
          //       $("#add_name").show();
          //       $("#add_email").show();
          //       $("#add_password").show();
          //       $("#add_confirm").show();
          //     }
          // }
          </script>

        <!-- <div class="form-group" id="add_name">
          {!! Form::label('', 'Nama', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="text" name="nama" min="1" value="{{ old('nama') }}" class="form-control form-control-sm">
          </div>
        </div> -->
        <!-- <div class="form-group" id="add_email">
          {!! Form::label('', 'Email', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="email" name="email" min="1" value="{{ old('email') }}" class="form-control form-control-sm">
          </div>
        </div> -->
        <!-- <div class="form-group" id="add_password">
          {!! Form::label('', 'Password', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="password" name="password" min="1" value="" class="form-control form-control-sm">
          </div>
        </div>
        <div class="form-group" id="add_confirm">
          {!! Form::label('', 'Confirm Password', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="password" name="confirm" min="1" value="" class="form-control form-control-sm">
          </div>
        </div> -->
        <div class="form-group">
          {!! Form::label('', 'Peran User', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="role">
              @foreach ( $role as $data )
                <option  <?php if(old('role') == $data->id ){ echo "selected"; } ?> value="{{ $data->id }}">{{ $data->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Fakultas', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="fakultas">
                <option value="">All</option>
              @foreach ( $fakultas as $dataa )
                <option <?php if(old('fakultas') == $dataa->Faculty_Id ){ echo "selected"; } ?> value="{{ $dataa->Faculty_Id }}">{{ $dataa->Faculty_Name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Prodi', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="department">
                <option value="">All</option>
              @foreach ( $department as $dataa )
                <option <?php if(old('department') == $dataa->Department_Id ){ echo "selected"; } ?> value="{{ $dataa->Department_Id }}">{{ $dataa->Department_Name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <!-- <div class=" col-md-12 col-xs-12"> -->
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
        <!-- </div> -->
        <!-- <center><a onclick="tambah()" class="btn btn-primary">OK</a></center> -->
        {!! Form::close() !!}
      </div>
    </div>
  </div>



<!-- /.row -->

</section>
@endsection
