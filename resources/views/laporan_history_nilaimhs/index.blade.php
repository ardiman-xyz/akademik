@extends('layouts._layout')
@section('pageTitle', 'Histori Nilai Mahasiswa')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Histori Nilai Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Histori Nilai Mahasiswa</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('laporan_history_nilaimhs.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <select class="form-control form-control-sm col-md-3" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-3" name="entry_year" onchange="document.form.submit();">
              <option value="0">Pilih Th Angkatan</option>
              @foreach ( $select_entry_year as $data )
                <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?>  value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code   }}</option>
              @endforeach
            </select>&nbsp
                <select id="select"  class="form-control form-control-sm col-md-3" name="nim" onchange="document.form.submit();" >
                  <option value="0">Pilih Nim/Nama</option>
                    @foreach($select_nim as $ni)
                    <option <?php if($nim == $ni->Nim){ echo "selected"; } ?> value="{{ $ni->Nim }}">{{$ni->Nim}}  {{ $ni->Full_Name }}</option>
                    @endforeach
                </select>
            <script type="text/javascript">
            var select = new SlimSelect({
            select: '#select'
            })
            select.selected()
            </script>

          </div>
          <hr>

          {!! Form::close() !!}
				<br>
      </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                <th>No.</th>
                  <th>Kode Matakuliah</th>
                  <th>Nama Matakuliah</th>
                  <th>SKS KRS</th>
                  <th>Nilai Huruf</th>
                  {{-- <th>Bobot x Nilai</th> --}}
                  <th>Semester</th>
              </tr>
          </thead>
          <tbody>
            <?php
						$i=1;
            foreach ($query as $data) {
            ?>
              <tr>
                  <td width="4%">{{$i}}</td>
                  <td width="12">{{ $data->Course_Code }}</td>
                  <td width="30">{{ $data->Course_Name }}</td>
                  <td width="14">{{ $data->Sks }}</td>
                  <td width="16">{{ $data->Grade_Letter }}</td>
                  {{-- <td width="14">{{ $data->weightvalue }}</td> --}}
                  <td width="10">{{$data->Term_Year_Name}}</td>
              </tr>
              <?php
							$i++;
              }
              ?>
          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>


</section>
@endsection
