@extends('layouts._layout')
@section('pageTitle', 'Role')
@section('content')
<?php

foreach ($query as $data_edit) {

  $acce = array();
  foreach ($access as $value) {
    $acce[] = $value->name;
  }
  $accekeu = array();
  foreach ($accesskeu as $value) {
    $accekeu[] = $value->name;
  }
?>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Detail Role</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/role?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Nama', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$data_edit->name}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$data_edit->description}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
          {!! Form::label('', 'Otoritas', ['class' => 'col-md-4 col-xs-12']) !!}:
          </div>
          <div class=" col-md-12 col-xs-12" style="padding:2%;">
            <div class="table-responsive">
            <table class="table table-striped table-font-sm">
                <thead class="thead-default thead-green">
                    <tr>
                      <th width="85%">Deskripsi</th>
                      <th width="15%"><i class="fa fa-gear"></i></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($accesses as $acc)
                    <tr>
                      <td>{{ $acc->description }}</td>
                      <td><center><input <?php if(in_array($acc->name, $acce)){ echo "checked";} ?> disabled type="checkbox" name="access[]" value="{{ $acc->id }}"><center></td>
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
                        <th width="15%"><input type="checkbox"/></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($accesseskeu as $acck)
                    <tr>
                      <td>{{ $acck->description }}</td>
                      <td><center><input <?php if(in_array($acck->name,$accekeu)){ echo "checked";} ?> disabled type="checkbox" name="accesskeu[]" value="{{ $acck->id }}"><center></td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>
          </div>
      </div>
    </div>
  </div>



<!-- /.row -->

</section>
<?php } ?>
@endsection
