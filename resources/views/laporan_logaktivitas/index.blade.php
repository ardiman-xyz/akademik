@extends('layouts._layout')
@section('pageTitle', 'Export Feeder')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>
<?php
function tanggal_indo($tanggal, $cetak_hari = false)
{
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);

	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Export  Log Aktivitas</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
         
        </div>
        <b>Export  Log Aktivitas</b>
      </div>
    </div>
    <br>
          {!! Form::open(['url' => route('log_aktivitas.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row  text-green">
						<div class="row col-md-7">
							<label class="col-md-3" >Jenis Export :</label>
              <input type="text" name="search" id="search"  class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $search }}" placeholder="Search">
		          <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
	            
						</div>
          {!! Form::close() !!} 
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

        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th></th>
                  <th>Log_Id</th>
                  <th>Student Id</th>
                  <th>Employee Id</th>
                  <th>IP</th>
                  <th>User App</th>
                  <th>Application</th>
                  <th>Activity</th>
                  <th>Date</th>
              </tr>
          </thead>
          <tbody>
            <?php
              $a = 1;
            foreach ($cek_data as $data) {

              $date = strtotime($data->Created_Date);
              $da = Date('Y-m-d h:i:s',$date);
              $show_date = tanggal_indo($da,true);
            ?>
              <tr>
                  <td>{{$a}}</td>
                  <td>{{$data->Log_User_Id}}</td>
                  <td>{{$data->Nim}}</td>
                  <td>{{$data->Name}}</td>
                  <td>{{$data->Ip_Client}}</td>
                  <td>{{$data->Userapp}}</td>
                  <td>{{$data->Application}}</td>
                  <td>{{$data->Activity}}</td>
                  <td>{{$da}}</td>
                  </td>
              </tr>
              <?php
              $a++;
              }
              ?>
          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>

  <script>
      $("#export").click(function(e) {
      var search = $('#search').val();
      if(search == ''){
         window.open("{{ url('') }}/laporan/log_aktivitas/exportdata/exportdata/0");
      }else{
        window.open("{{ url('') }}/laporan/log_aktivitas/exportdata/exportdata/" + search);
      }
    });
</script> 
</section>
@endsection
