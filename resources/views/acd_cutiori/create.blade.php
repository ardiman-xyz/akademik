@extends('layouts._layout')
@section('pageTitle', 'Cuti')
@section('content')
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Mahasiswa Cuti</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/cuti?page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&term_year='.$term_year) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Cuti")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif

          {!! Form::open(['url' => route('cuti.create') , 'method' => 'GET', 'name' => 'form', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Prodi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="term_year" hidden value="{{ $request->term_year }}" class="form-control form-control-sm">
              <select id="Department_Id" class="form-control form-control-sm col-md-12" name="Department_Id" onchange="document.form.submit();">
                <option value="0">Pilih Prodi</option>
                @foreach($select_department as $data)
                <option <?php if($request->Department_Id == $data->Department_Id){ echo "selected"; } ?>  value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          {!! Form::close() !!}

          <form action="{{ route('cuti.store') }}" method="post" enctype="multipart/form-data">
            @csrf
          <div class="form-group">
            {!! Form::label('', 'Nama Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-12" name="mahasiswa">
                <option value="0">Pilih Mahasiswa...</option>
                @foreach($select_mahasiswa as $data)
                <option <?php if($mahasiswa == $data->Student_Id){ echo "selected"; } ?> value="{{ $data->Student_Id }}">{{$data->Nim}} | {{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
            <select class="form-control form-control-sm col-md-12" name="term_year">
              <option value="0">Pilih Semester...</option>
              @foreach($select_term_year as $data)
              <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
          </div>
          </div>
          <div class="form-group">
            <!-- {!! Form::label('', 'Alasan', ['class' => 'col-md-4 form-label']) !!} -->
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" hidden name="reason">
                @foreach($select_reason as $data)
                <option <?php if($reason == $data->Vacation_Reason_Id){ echo "selected"; } ?> value="{{ $data->Vacation_Reason_Id }}">{{ $data->Vacation_Reason }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Alasan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Deskripsi" min="1" value="{{ old('Deskripsi') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'SK Date', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('SK_Date'));
                $SK_Date = date('Y-m-d', $date);
              ?>
              <input type="date" name="SK_Date" value="{{ old('$SK_Date') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'SK Number', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Sk_Number" min="1" value="{{ old('Sk_Number') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'File', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="file" name="file" id="" class="form-control" accept=".jpg,.jpeg,.pdf,.png"><br>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
  var select = new SlimSelect({
  select: '#select'
  })
  select.selected()
  </script>


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
