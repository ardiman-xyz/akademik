@extends('layouts._layout')
@section('pageTitle', 'Export')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Exportt</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
         
        </div>
        <b>Exportt</b>
      </div>
    </div>
    <br>
          {!! Form::open(['url' => route('exportfeeder.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row  text-green">
						<div class="row col-md-7">
							<label class="col-md-3" >Jenis Export :</label>
	            <select class="form-control form-control-sm col-md-7" name="jenis" id="jenis" onchange="document.form.submit();">
	              <option <?php if($jenis == 1){ echo "selected"; } ?> value="1">Mahasiswa</option>
                <!-- <option <?php if($jenis == 2){ echo "selected"; } ?>  value="2">Kelas</option> -->
                <!-- <option <?php if($jenis == 3){ echo "selected"; } ?> value="3">KRS</option>
                <option <?php if($jenis == 4){ echo "selected"; } ?> value="4">KHS/Nilai</option>
                <option <?php if($jenis == 5){ echo "selected"; } ?> value="5">Nilai Transfer</option>
                <option <?php if($jenis == 6){ echo "selected"; } ?> value="6">Ajar Dosen</option>
                <option <?php if($jenis == 7){ echo "selected"; } ?> value="7">AKM</option>
                <option <?php if($jenis == 8){ echo "selected"; } ?> value="8">Mahasiswa Lulus</option> -->
	            </select>
						</div>
          {!! Form::close() !!}
          <div class="row col-md-7">
            <label class="col-md-3" >Pilih Prodi :</label>
             <select class="form-control form-control-sm col-md-7" name="prodi" id="prodi"  onchange="document.form.submit();">
             <option value="99999">Semua Prodi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
          </div>          
          <div class="row col-md-7">
            <label class="col-md-3" >Pilih Angkatan :</label>
             <select class="form-control form-control-sm col-md-7" name="entry_year" id="entry_year"  onchange="document.form.submit();">
                <option value="1">Semua Tahun</option>
              @foreach ( $select_entry as $data )
                <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Id }}</option>
              @endforeach
            </select>&nbsp
          </div>          
          </div><br>
          <div class="row  text-green">
						<div class="row col-md-7">
							<label class="col-md-4" ></label>
                <a href="#" class="btn btn-primary btn-sm export" id='export' style="font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a> &nbsp	            
						</div>
          </div>
        
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p id="message" class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
      </div>
    </div>
  </div>

  <script>
      $("#export").click(function(e) {
      var jenis = $('#jenis').val();
      var prodi = $('#prodi').val();
      var entry_year = $('#entry_year').val();
      if(jenis == 7){
        swal('Maaf...!', 'AKM belum siap' , 'warning');          
      }
      else if(jenis == 8){
        swal('Maaf...!', 'Mahasiswa Lulus belum siap' , 'warning'); 
      }
      else{
        window.open("{{ url('') }}/laporan/exportfeeder/exportdata/exportdata/" + jenis + "/" + prodi + "/" + entry_year);          
      }
    });
</script> 
</section>
@endsection
