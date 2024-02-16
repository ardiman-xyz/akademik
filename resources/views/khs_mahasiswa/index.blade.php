@extends('layouts._layout')
@section('pageTitle', 'KHS')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KHS Per Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($term_year != null && $term_year != 0 && $nim != null && $nim != 0 && $query->count() != 0)
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            <a id="link" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-print"></i> Cetak KHS</a>
          </div>
          @endif
          <b>KHS Per Mahasiswa</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('khs_mahasiswa.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <label class="col-md-2">NIM Mahasiswa :</label>
							<input type="text" class="form-control form-control-sm col-md-3" name="nim" value="{{ $nim }}">
            <label class="col-md-2" >Th Akademik :</label>
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Th Akademik</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
          </div>
          <hr>

          {!! Form::close() !!}
				<div class="row">
					@if($student != null)
					<div class="col-sm-6">
						<label for="" class="col-sm-4">Nama Mahasiswa</label>
						<label for="" class="col-sm-7">:
							@if($student != "")
								{{ $student->Full_Name }}
							@endif
						</label>
						<label for="" class="col-sm-4">Prodi Mahasiswa</label>
						<label for="" class="col-sm-7">:
							@if($dat != "")
								{{ $dat->Department_Name }}
							@endif
						</label>
						<label for="" class="col-sm-4">Program Kelas</label>
						<label for="" class="col-sm-7">:
							@if($dat != "")
							 	{{ $dat->Class_Program_Name }}
							@endif
            </label>
						<!-- <label for="" class="col-sm-4">SKS yang diizinkan</label>
						<label for="" class="col-sm-7">: </label>
						<label for="" class="col-sm-4">Deposit semester ini</label>
						<label for="" class="col-sm-7">:
						</label>
						<label for="" class="col-sm-4">Sisa deposit lalu</label>
						<label for="" class="col-sm-7">: </label> -->
					</div>
					<!-- <div class="col-sm-6">
						<label for="" class="col-sm-4">Deposit bisa dipakai</label>
						<label for="" class="col-sm-7">: </label>
						<label for="" class="col-sm-4">Ddipakai saat ini</label>
						<label for="" class="col-sm-7">: </label>
						<label for="" class="col-sm-4">Sisa saldo saat ini</label>
						<label for="" class="col-sm-7">: </label>
						<label for="" class="col-sm-4">Bayar KKN</label>
						<label for="" class="col-sm-7">: </label>
						<label for="" class="col-sm-4">KRS KKN</label>
						<label for="" class="col-sm-7">: </label>
						<label for="" class="col-sm-4">Saldo KKN</label>
						<label for="" class="col-sm-7">: </label>
					</div> -->
					@endif
				</div>
				<br>
      </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif

        <?php 
        $t_b = 0;
        foreach($new_array as $dat){
          if($t_b < $dat['C_Bobot']){ $t_b = $dat['C_Bobot']; } 
        }
        ?>
        <div class="table-responsive">
        {{-- <table class="table table-striped table-font-sm" width="100%">
          <thead clss="table table-striped  table-font-sm" width="100%">
            <tr>
              <th>Kode Matakuliah</th>
              <th>Nama Matakuliah</th>
              <th colspan="{{$t_b}}"></th>
              <th>Total</th>
              <th>Nilai Huruf</th>
            </tr>
          </thead>

          @foreach($new_array as $dat)
          <tbody>
            <tr>
              <td>{{$dat['Course_Code']}}</td>
              <td>{{$dat['Course_Name']}}</td>
              @foreach($dat['bobot'] as $key => $value)
                @if($key === array_key_last($dat['bobot']))
                <td colspan="{{$t_b-$dat['C_Bobot']+1}}" width="20%">{{$key}} <b>{{$value == ""?'':'='}} {{$value}}</td>
                @else
                <td width="20%" align="left">{{$key}} {{$value == ""?'':'='}} <b>{{$value}}</td>
                @endif
              @endforeach
              <td>{{$dat['Total']}}</td>
              <td>{{$dat['Letter']}}</td>
            </tr>
          </tbody>
          @endforeach
        </table> --}}

        <br>
        <table border=1 class="table table-striped table-font-sm" width="100%">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="20%">Kode Matakuliah</th>
                  <th width="30%">Nama Matakuliah</th>
                  <th width="10%">Nilai Huruf</th>
									<th width="10%">SKS</th>
                  <th width="10%">KxN</th>


              </tr>
          </thead>
          <tbody>
            <?php
						$i=1;
            $bbtXjmlskssmt = 0;
            $jmlsksbernilai = 0;
            $ipsemester = 0;
            // foreach ($query as $data) {
            foreach ($new_array as $data) {
            ?>
            <?php
                // $bbtXjmlskssmt = $bbtXjmlskssmt + ($data->Weight_Value * $data->Sks);
                // if ($data->Weight_Value != "") {
                //   $jmlsksbernilai = $jmlsksbernilai + $data->Sks;
                // }
            ?>
              <tr>
                  <td>{{ $data['Course_Code'] }}</td>
                  <td>{{ $data['Course_Name'] }}</td>
                  <td>{{ $data['Letter'] }}</td>
									<td>{{ $data['Sks'] }}</td>
                  <td>{{ $data['Sks']* $data['Weight_Value']}}</td>
              </tr>
              <?php
							$i++;
              }
              ?>
          </tbody>
        </table>

        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <label for="" class="col-sm-4">Bobot Nilai x Jumlah SKS Semester</label>
          <label for="" class="col-sm-7">: {{ $footer['bobot_x_sks'] }}</label>
          <label for="" class="col-sm-4">Jumlah SKS Bernilai</label>
          <label for="" class="col-sm-7">: {{ $footer['total_sks'] }}</label>
          <label for="" class="col-sm-4">Indeks Prestasi Semester</label>
          <label for="" class="col-sm-7">: <?php echo number_format($footer['ip_semester'],2); ?></label>
        </div>
      </div>
      <br>
    </div>
  </div>

  <script type="text/javascript">
  $("#link").on('click', function() {
    var url = {!! json_encode(url('/')) !!};
    var link = "{{ url('client/khs_mahasiswa/'.$nim.'/'.$term_year) }}";
    var linkk = link.replace(/&amp;/g, '&');
   window.open(linkk,"_blank");
  });
  </script>

</section>
@endsection
