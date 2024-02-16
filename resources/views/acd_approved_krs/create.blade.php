@extends('layouts._layout')
@section('pageTitle', 'Event Sched')
@section('content')
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Jadwal Pengisian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/event_sched?event_id='.$event_id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Jadwal Pengisian")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          
          {!! Form::open(['url' => route('event_sched.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}

          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
              <input type="hidden" name="Event_Id" min="1" value="{{ $event_id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-8">
						</div>
          </div>          
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						  <label class="col-md-4">Jenis Pengisian :</label>
              @foreach($event as $event)
              <input type="text" value="{{ $event->Event_Name }}" disabled class="form-control form-control-sm col-md-8" >
              @endforeach
						</div>
          </div>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						  <label class="col-md-4">Nama Program Studi :</label>
              <select class="form-control form-control-sm col-md-8" name="Department_Id">
                <option value="">Pilih Prodi</option>
                @foreach ( $select_department as $data )
                  <option value="{{ $data->Department_Id }}" <?php if(old('Department_Id') == $data->Department_Id ){ echo "selected"; } ?>>{{ $data->Department_Name }}</option>
                @endforeach
              </select>
						</div>
          </div>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						  <label class="col-md-4">Semester Berlaku :</label>
              <select class="form-control form-control-sm col-md-8" name="Term_Year_Id">
                <option value="">Semester</option>
                @foreach ( $select_term_year as $data )
                  <option value="{{ $data->Term_Year_Id }}" <?php if(old('Term_Year_Id') == $data->Term_Year_Id ){ echo "selected"; } ?>>{{ $data->Term_Year_Name }}</option>
                @endforeach
              </select>
						</div>
          </div>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						  <label class="col-md-4">Buka?</label>
              <select class="form-control form-control-sm col-md-8" name="Is_Open">
                  <option value="1" <?php if(old('Is_Open') == 1){ echo "selected"; } ?>>YA</option>
                  <option value="0" <?php if(old('Is_Open') == 0){ echo "selected"; } ?>>TIDAK</option>
                </select>
						</div>
          </div>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						  <label class="col-md-4">Tanggal Mulai :</label>
              <input type="date" name="Start_Date"   class="form-control form-control-sm col-md-8" value="{{ old('Start_Date') }}">
						</div>
          </div>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						  <label class="col-md-4">Tanggal Akhir :</label>
              <input type="date" name="End_Date"   class="form-control form-control-sm col-md-8"  value="{{ old('End_Date') }}">
						</div>
          </div>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						  <label class="col-md-4">Tanggal Akhir Pembayaran:</label>
              <input type="date" name="End_Date_Cost"   class="form-control form-control-sm col-md-8"  value="{{ old('End_Date_Cost') }}">
						</div>
          </div>

          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>



<!-- /.row -->

</section>
<!-- <script type="text/javascript">
  function handleChange(checkbox) {
      if(checkbox.checked == true){
        list = document.getElementsByClassName("fakultas");
        for (index = 0; index < list.length; ++index) {
          list[index].setAttribute("disabled","disabled");
          list[index].checked = false;
        }
      }else {
        list = document.getElementsByClassName("fakultas");
        for (index = 0; index < list.length; ++index) {
          list[index].removeAttribute("disabled");
        }
      }
  }
  function Change(checkbox) {
      var id = $(checkbox).val();
      if(checkbox.checked == true){
        list = document.getElementsByClassName("prodi"+id);
        for (index = 0; index < list.length; ++index) {
          // list[index].setAttribute("disabled","disabled");
          list[index].checked = true;
          // list[index].setAttribute("checked","checked");
        }
      }else {
        list = document.getElementsByClassName("prodi"+id);
        for (index = 0; index < list.length; ++index) {
          // list[index].removeAttribute("disabled");
          list[index].checked = false;
          // list[index].removeAttribute("checked");
        }
      }
  }
  function ubah(id) {
      if($('.prodi'+id+':checked').length == $('.prodi'+id+'').length){
        list = document.getElementsByClassName("fac"+id);
        for (index = 0; index < list.length; ++index) {
          list[index].checked = true;
        }
      }else {
        list = document.getElementsByClassName("fac"+id);
        for (index = 0; index < list.length; ++index) {
          list[index].checked = false;
        }
      }
  }
  $("form").submit(function() {
    $("input").removeAttr("disabled");
  });
</script> -->
@endsection
