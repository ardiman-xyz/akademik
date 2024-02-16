@extends('layouts._layout')
@section('pageTitle', 'Functional Position Term Year')
@section('content')
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Jabatan struktural</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('master/functional_position_term_year?term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Jabatan Struktural")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('functional_position_term_year.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Tahun Ajaran', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach ( $mstr_term_year as $data )
              <input type="hidden" name="Term_Year" value="{{ $term_year }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              <input type="text" readonly value="{{ $data->Year_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jabatan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="Functional_Position_Id" class="form-control form-control-sm" name="Functional_Position_Id">
                <option value="">Pilih Jabatan</option>
                @foreach ( $functional_position as $data )
                  <option  <?php if(old('Functional_Position_Id') == $data->Functional_Position_Id ){ echo "selected"; } ?> value="{{ $data->Functional_Position_Id }}">{{ $data->Functional_Position_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <div id="Fakultas">
              {!! Form::label('', 'Departemen', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  class="form-control form-control-sm" name="Faculty_Id">
                <option value="">Pilih Departemen</option>
                @foreach ( $select_faculty as $data )
                  <option <?php if(old('Faculty_Id') == $data->Faculty_Id ){ echo "selected"; } ?> value="{{ $data->Faculty_Id }}">{{ $data->Faculty_Name }}</option>
                @endforeach
              </select>
            </div>
            </div>
          </div>
          <div class="form-group">
            <div id="Prodi">
              {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  class="form-control form-control-sm" name="Department_Id">
                <option value="">Pilih Program Studi</option>
                @foreach ( $select_department as $data )
                  <option <?php if(old('Department_Id') == $data->Department_Id ){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
                @endforeach
              </select>
            </div>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Pejabat', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" name="Employee_Id" class="form-control form-control-sm">
                <option value="">Pilih Pejabat</option>
                @foreach ( $select_employee as $data )
                  <option <?php if(old('Employee_Id') == $data->Employee_Id ){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->First_Title }} {{ $data->Name }} {{ $data->Last_Title }}</option>
                @endforeach
              </select>
              <script type="text/javascript">
              var select = new SlimSelect({
              placeholder: 'Pilih Pegawai',
              select: '#select'
              })

              select.selected()

              </script>
            </div>
          </div>
          <!-- <select  name="Employee_Id" id="basic" class="selectpicker show-tick form-control" data-live-search="true"> -->
          <!-- </select> -->

          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>



<!-- /.row -->

</section>

<script>
    $(document).on("ready", function (event) {
        var FP = $("#Functional_Position_Id").val();
        if (FP == 1 || FP == 2 || FP == 3 || FP == 4 || FP == 11) {
            $("#Fakultas").hide();
            $("#Prodi").hide();
        }
        if (FP == 5 || FP == 6 || FP == 7 || FP == 8) {
            $("#Fakultas").show();
            $("#Prodi").hide();
        }
        if (FP == 9 || FP == 10) {
            $("#Fakultas").hide();
            $("#Prodi").show();
        }
    });
    $(document).on("change", "#Functional_Position_Id", function (event) {
        var FP = $("#Functional_Position_Id").val();
        var term_year_id = <?php echo $term_year; ?>;
        var url = {!! json_encode(url('/')) !!};

        if (FP == 1 || FP == 2 || FP == 3 || FP == 4 || FP == 11) {
            $("#Fakultas").hide();
            $("#Prodi").hide();
        }
        if (FP == 5 || FP == 6 || FP == 7 || FP == 8) {
            $("#Fakultas").show();
            $("#Prodi").hide();
            $.ajax({
                url: url + "/master/functional_position_term_year/create/faculty?term_year_id="
                + term_year_id
                + "&functional_position_id=" + FP,
                data: {
                    term_year_id: term_year_id,
                    functional_position_id: FP
                },
                cache: false,
                type: "GET",
                dataType: "html",

                success: function (data, textStatus, XMLHttpRequest) {
                    $("#Fakultas").html(data);// HTML DOM replace
                }
            });
        }
        if (FP == 9 || FP == 10) {
            $("#Fakultas").hide();
            $("#Prodi").show();
            $.ajax({
                url: url + "/master/functional_position_term_year/create/department?term_year_id="
                + term_year_id
                + "&functional_position_id=" + FP,
                data: {
                    term_year_id: term_year_id,
                    functional_position_id: FP
                },
                cache: false,
                type: "GET",
                dataType: "html",

                success: function (data, textStatus, XMLHttpRequest) {
                    $("#Prodi").html(data);
                }
            });
        }
    });
</script>


@endsection
