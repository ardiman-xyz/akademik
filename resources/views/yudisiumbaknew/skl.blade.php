@extends('layouts._layout')
@section('pageTitle', 'Yudisium')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Surat Keterangan Lulus</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/yudisium/'.$data->Student_Id.'?department='.$department.'&term_year='.$term_year) }}" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-reply"></i></a>
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


          {!! Form::open(['url' => route('skl.store_skl') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          {{ csrf_field() }}
          {{ method_field('post') }}
          <input type="text" name="Student_Ids" hidden value="{{ $data->Student_Id }}">
          <input type="text" name="facult" hidden value="{{ $faculty->Faculty_Id }}">


          <div class="col-sm-6">
              <div >
                <label for="" class="col-sm-5">Nim</label>
                <label for="" name="nim" class="col-sm-5">{{ $data->Nim }}</label>
              </div>

              <div>
                <label for="" class="col-sm-5">Nama</label>
                <label for="" name="nama" class="col-sm-5">{{ $data->Full_Name }}</label>
              </div>

              <div>
                <label for="" class="col-sm-5">Nomor</label>
                <label for="" class="col-sm-5"><input readonly type="text" name="nomor" value="{{ $data->Sk_Num }}"  class="form-control form-control-sm"></label>
              </div>

              <div>
                <label for="" class="col-sm-5">Tgl Lulus Yudisium</label>
                <label class="form-control-sm col-sm-5"><div>
                  <?php
                    $date = strtotime($data->Graduate_Date);
                    $tgl_lulus = date('Y-m-d', $date);
                  ?>
                  <input type="date" name="tgl_lulus" value="{{ $tgl_lulus }}" class="form-control form-control-sm">
                </div></label>
              </div>

              <div>
                <label for="" class="col-sm-5">PEJABAT FAKULTAS</label>
              </div>

              <div>
                <label for="" class="col-sm-5">Jabatan Fakutas</label>
                <select class="form-control-sm col-sm-5" name="jabatanfk">
                  <option value="">Pilih Jabatan</option>
                  @foreach ( $jabatan as $datajabatan )
                    <option  <?php if($datayudisium->Faculty_Functionary == $datajabatan->Functional_Position_Id ){ echo "selected"; } ?> value="{{ $datajabatan->Functional_Position_Id }}">{{ $datajabatan->Functional_Position_Name }}</option>
                  @endforeach
                </select>
              </div>

              <input type="text" name="nik_pjb" hidden class="nik_pjb" value="{{ $datayudisium->Nik }}">
              <div>
                <label for="" class="col-sm-5">Pejabat Fakultas</label>
                <select id="select" class="form-control-sm col-sm-5 pejabat" name="pejabatfk">
                  <option value="0">Pilih...</option>
                  @foreach($dosen as $datads)
                  <option <?php if($datayudisium->Faculty_Functionary_Name == $datads->Employee_Id){ echo "selected"; } ?> value="{{ $datads->Employee_Id }}">{{ $datads->Full_Name }}</option>
                  @endforeach
                </select>
              </div>
          </div>


          <br><button type="submit" class="btn btn-primary btn-flat">Simpan</button>

          {!! Form::close() !!}
          <label class="col-sm-5"><a href="{{ url('proses/yudisium/'.$mhs->Student_Id.'/export?proses=5') }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>

      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<script type="text/javascript">
    var select = new SlimSelect({
    select: '#select',
    })
</script>

<script type="text/javascript">
	$(document).ready(function(){

		$("#select").on('change',function () {
			var Employee_id=$(this).val();
			var a=$(this).parent();
			var op="";
      var url = {!! json_encode(url('/')) !!};
			$.ajax({
				type:'get',
				url: url + '/proses/yudisium/findnik/findnik',
				data:{'Employee_Id':Employee_id},
				dataType:'json',//return data will be json
				success:function(data){
            console.log(data);
           $(".nik_pjb").val(data.Nik);
				},
				error:function(){

				}
			});
		});
    });
</script>





@endsection
