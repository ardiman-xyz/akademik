@extends('layouts._layout')
@section('pageTitle', 'Cetak ktm')
@section('content')

  <section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">CETAK KTM</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="bootstrap-admin-box-title right text-white">
            <b>CETAK KTM</b>
          </div>
        </div>
        <br>
        {!! Form::open(['url' => route('ktm.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}

        <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="row col-md-8">
            <label class="col-md-3" >Program Studi</label>
            <select class="form-control form-control-sm col-md-4" name="department" id="department" onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
          </div>
        </div><br>

        <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="row col-md-8">
            <label class="col-md-3" >Angkatan</label>
            <select class="form-control form-control-sm col-md-4" name="entry_year" id="entry_year" onchange="document.form.submit();">
              <option value="0">Pilih Th Angkatan</option>
              @foreach ( $select_entry_year as $data )
                <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?>  value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code   }}</option>
              @endforeach
            </select>&nbsp
          </div>
        </div><br>

        <div class="row  text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="row col-md-8">
            <label class="col-md-3" >Cetak Dari</label>
            <select id="select"  class="form-control form-control-sm col-md-4" name="nimawal" onchange="document.form.submit();" >
              <option value="0">Pilih Nim/Nama</option>
              @foreach($select_nim as $ni)
                <option <?php if($nimawal == $ni->Nim){ echo "selected"; } ?> value="{{ $ni->Nim }}">{{$ni->Nim}}  {{ $ni->Full_Name }}</option>
              @endforeach
            </select>

            <label class="col-md-1" >s/d</label>
            <select id="select2"  class="form-control form-control-sm col-md-4" name="nimakhir" onchange="document.form.submit();" >
              <option value="0">Pilih Nim/Nama</option>
              @foreach($select_nim as $ni)
                <option <?php if($nimakhir == $ni->Nim){ echo "selected"; } ?> value="{{ $ni->Nim }}">{{$ni->Nim}}  {{ $ni->Full_Name }}</option>
              @endforeach
            </select>
          </div>
        </div><br>

        <script type="text/javascript">
          var select = new SlimSelect({
            select: '#select'
          })
          select.selected()

          var select2 = new SlimSelect({
            select: '#select2'
          })
          select2.selected()
        </script>

        <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="row col-md-8">
            <label class="col-md-3" >Tanggal</label>
            <?php
            if($tgl_akhir != null){
              $date = strtotime($tgl_akhir);
              $birth = date('Y-m-d', $date);
            }else{
              $tgl_ = \Carbon\Carbon::now();
              $birth = $tgl_->format('Y-m-d');
            }
            ?>
            <input type="date" name="tgl_akhir" id="tgl_akhir" value="{{ $birth }}" class="form-control form-control-sm col-md-4" onchange="document.form.submit();" >
          </div>
        </div><br>

        
        <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="row col-md-8">
            <label class="col-md-3" >Background</label>
            <select class="form-control form-control-sm col-md-4" name="bg" id="bg">
              <option value="1">Tidak</option>
              <option value="0">Ya</option>
            </select>&nbsp
          </div>
        </div><br>

        @if($tgl_akhir != 0)
          <div class="col-md-6">
            <center>
            <a href="#" class="btn btn-primary btn-sm export" id='export' style="font-size:medium;margin-right:5px;">Cetak &nbsp;<i class="fa fa-print"></i></a> &nbsp
            <!-- <label class="col-sm-5"><a href="{{ url('cetak/ktm/'.$department.'/export'.'?nimawal='.$nimawal.'&nimakhir='.$nimakhir.'&tgl_akhir='.$tgl_akhir) }}" class="btn btn-primary btn-flat" style="margin:5px;" target="_blank">Cetak <i class="fa fa-print"></i> </a></label></center> -->
          </div>
        @else
          <div class="col-md-6">
            <center><button disabled class="btn btn-primary btn-flat " target="_blank"><i class="fa fa-print"></i> Cetak </button></center>
          </div>
        @endif
        <hr>

        {!! Form::close() !!}

        <div class="col-md-6">
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-success">{{ $error }}</p>
            @endforeach
          @endif
        </div>

        {!! Form::open(['url' => route('ktm.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
        
        <br>
        <div class="col-md-6">
          <div class="col-md-6">
            <label for="">Background</label>
          </div>
          <div class="col-md-12">
            <img width="50%" src="<?php echo env('APP_URL')?>{{ 'img/ktm.png' }}" >
          </div>
          <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-12">
              <div class="col-md-10" ><input type="file" accept="image/*" class="form-control form-control-sm" name="file" /></div>
            </div>
          </div><br><br>
          <div class="col-md-6">
            <label for="">Tanda Tangan</label>
          </div>
          <div class="col-md-12">
            @php
             $update = DB::table('mstr_signature')->where('Ttd_For','TTD KTM')->first();   
            @endphp
            @if ($update)
              <img width="50%" src="<?php echo env('APP_URL')?>{{ 'storage/ttd/'.$update->Value }}" >
            @endif
          </div>
          <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-12">
              <div class="col-md-10" ><input type="file" accept="image/*" class="form-control form-control-sm" name="ttd" /></div>
            </div>
          </div><br>
          <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-12">
              <button type="submit" class="btn btn-primary btn-flat col-md-10">Simpan</button>
            </div>
          </div><br>
        </div>
        {{-- <label class="col-sm-5"><a href="{{ url('cetak/ktm/update') }}" class="btn btn-info btn-sm" style="margin:5px;">Simpan <i class="fa fa-print"></i> </a></label> --}}

        {!! Form::close() !!}

      </div>

    </div>
  </div>
  <script>
    $("#export").click(function(e) {
          var department = $('#department').val();
          var nimawal = $('#select').val();
          var nimakhir = $('#select2').val();
          var tgl_akhir = $('#tgl_akhir').val();
          var bg = $('#bg').val();
          window.open("{{ url('') }}/cetak/ktm/" + department + "/export?nimawal=" + nimawal + "&nimakhir=" + nimakhir+ "&tgl_akhir=" + tgl_akhir+ "&bg=" + bg); 
        });
  </script>
</section>

@endsection()
