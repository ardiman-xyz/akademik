@extends('layouts._layout')
@section('pageTitle', 'Cetak Transkrip Sementara')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">CETAK Ijazah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>CETAK Ijazah</b>
        </div>
      </div>
      <br>
        {!! Form::open(['url' => route('ijazah.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}

        <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="row col-md-8">
            <label class="col-md-3" >Program Studi</label>
            <select class="form-control form-control-sm col-md-4" name="department"  onchange="document.form.submit();">
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
            <select class="form-control form-control-sm col-md-4" name="entry_year" onchange="document.form.submit();">
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
            <input type="date" name="tgl_akhir" value="{{ $birth }}" class="form-control form-control-sm col-md-4" onchange="document.form.submit();" >
          </div>
        </div><br>

        @if($nimawal != 0)
          <label class="col-sm-5"><a href="{{ url('cetak/ijazah/'.$department.'/export'.'?nimawal='.$nimawal.'&nimakhir='.$nimakhir.'&department='.$department.'&tgl_akhir='.$tgl_akhir) }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">Cetak <i class="fa fa-print"></i> </a></label>
        @else
          <button disabled class="btn btn-info btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak </button>
        @endif
        <hr>

        {!! Form::close() !!}
        </div>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="col-sm-6">
          </div>
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
      </div>
    </div>
  </div>
</div>
</section>
@endsection()
