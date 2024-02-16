@extends('layouts._layout')
@section('pageTitle', 'Curriculum Entry Year')
@section('content')
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Kurikulum Angkatan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/curriculum_entry_year?term_year='.$Term_Year_Id.'&department='.$Department_Id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Kurikulum Angkatan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          
          <div class="form-group">
            {!! Form::label('', 'Tahun / Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach($Term_Year as $term_y)
              <input type="text" readonly value="{{ $term_y->Term_Year_Name }}" class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach($Department as $depart)
              <input type="text" readonly value="{{ $depart->Department_Name }}"  class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <!-- term_year={{$Term_Year_Id}}&department={{ $Department_Id }} -->
          {!! Form::open(['url' => route('curriculum_entry_year.create') , 'method' => 'GET', 'name' => 'form', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <input type="hidden" name="term_year" min="1" value="{{ $Term_Year_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <input type="hidden" name="department" min="1" value="{{ $Department_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <div class="form-group">
            {!! Form::label('', 'Angkatan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="Entry_Year_Id" class="form-control form-control-sm col-md-12" name="Entry_Year_Id" onchange="document.form.submit();">
                <option value="">Pilih Prodi</option>
                @foreach ( $entry_year as $entry )
                  <option <?php if($request->Entry_Year_Id == $entry->Entry_Year_Id){ echo "selected"; } ?> value="{{ $entry->Entry_Year_Id }}">{{ $entry->Entry_Year_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          {!! Form::close() !!}
          {!! Form::open(['url' => route('curriculum_entry_year.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <input type="hidden" name="Term_Year_Id" min="1" value="{{ $Term_Year_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <input type="hidden" name="Department_Id" min="1" value="{{ $Department_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <input type="hidden" name="Entry_Year_Id" min="1" value="{{ $request->Entry_Year_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <div class="form-group">
            {!! Form::label('', 'Program Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Class_Prog_Id" id="select">
                <option value="">Pilih Program Kelas</option>
                @foreach ( $class_program as $class )
                  <option value="{{ $class->Class_Prog_Id }}">{{ $class->Class_Program_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kurikulum', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Curriculum_Id">
                <option value="">Pilih Kurikulum</option>
                @foreach ( $curriculum as $curri )
                  <option value="{{ $curri->Curriculum_Id }}">{{ $curri->Curriculum_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<script type="text/javascript">
  $(document).on("change", "#pilih", function (event) {
    var term_year_id = <?php echo $Term_Year_Id; ?>;
    var department_id = <?php echo $Department_Id; ?>;
    var entry_year_id = $('#pilih').val();
    var url = {!! json_encode(url('/')) !!};

    $.ajax({
        url: url + "/parameter/curriculum_entry_year/create/class_program",
        data: {
            term_year_id: term_year_id,
            department_id: department_id,
            entry_year_id: entry_year_id
        },
        cache: false,
        type: "GET",
        dataType: "html",

        success: function (data, textStatus, XMLHttpRequest) {
            $("#select").html(data);// HTML DOM replace
        }
    });
  });
</script>

@endsection
