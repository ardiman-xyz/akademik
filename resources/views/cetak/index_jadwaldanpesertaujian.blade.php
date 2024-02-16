@extends('layouts._layout')
@section('pageTitle', 'Cetak Jadwal Peserta Ujian')
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
      <h3 class="text-white">Cetak Jadwal Dan Peserta Ujian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Cetak Jadwal Dan Peserta Ujian</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('jadwaldanpesertaujian.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->
            <select class="form-control form-control-sm col-md-4" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun / Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-3" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
            <!-- <label class="col-md-2">Tahun Semester :</label> -->
            <select class="form-control form-control-sm col-md-3" name="class_program"  onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp

              <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>

          </div>
          <br>
          <div class="row text-green">
            <label class="col-md-2">Pencarian:</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-3" value="{{ $search }}" placeholder="Search">&nbsp
	          <input type="submit" name="" class="btn btn-primary btn-sm" value="Cari">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
	          <label class="col-md-2">Baris Per halaman :</label>
	          <select class="form-control form-control-sm col-md-1" name="rowpage" onchange="document.form.submit();" >
	            <option <?php if($rowpage == "10"){ echo "selected"; } ?> value="">10 Baris</option>
	            <option <?php if($rowpage == "20"){ echo "selected"; } ?> value="20">20 Baris</option>
	            <option <?php if($rowpage == "50"){ echo "selected"; } ?> value="50">50 Baris</option>
	            <option <?php if($rowpage == "100"){ echo "selected"; } ?> value="100">100 Baris</option>
	            <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
	          </select>
          </div><br>
          {!! Form::close() !!}
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
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="15%">Kode Matakuliah</th>
                  <th width="25%">Nama Matakuliah</th>
                  <th width="10%">kelas</th>
                  <th width="10%">Kapasitas Kelas</th>
                  <th width="30%">Jadwal</th>
                  <th width="10%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";

            foreach ($query as $data) {

              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Class_Name }}</td>
                  <td>{{ $data->Class_Capacity }}</td>
                  <td>
                    <?php
                      $startdate = explode('|', $data->start_date);
                      $enddate = explode('|', $data->end_date);
                      $jenis = explode('|', $data->jenis_ujian);
                      $n = 0;
                      if ($data->start_date != "") {
                      foreach ($startdate as $key) {

                            $start = explode(" ",$key);
                            $s_date = $start[0];
                            $s_time = explode(":",$start[1]);
                            unset($s_time[1]);
                            $s_time = implode(".",$s_time);

                            // $end = explode(" ",$enddate[$n]);
                            // $e_date = $end[0];
                            // $e_time = explode(":",$end[1]);
                            // unset($e_time[1]);
                            // $e_time = implode(".",$e_time);

                            $jn = explode(" ",$jenis[$n]);
                            $e_jn = $jn[0];
                            $jenis_ujian = DB::table('mstr_exam_type')->where('Exam_Type_Id',$e_jn)->first();

                            // echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$jenis_ujian->Exam_Type_Code." ".tanggal_indo($s_date,false)." <font style='color:#7cffff;'>".$s_time."</font> - ".tanggal_indo($e_date,false)." <font style='color:#7cffff;'>".$e_time."</font></div>";
                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$jenis_ujian->Exam_Type_Code." ".tanggal_indo($s_date,false)." <font style='color:#7cffff;'>".$s_time."</font></div>";
                            $n++;
                        }
                      }
                    ?>
                  </td>

                  <td align="center">
                      <!-- {!! Form::open(['url' => route('jadwaldanpesertaujian.destroy', $data->Offered_Course_id) , 'method' => 'delete', 'role' => 'form']) !!} -->
                      <a href="{{ url('cetak/jadwaldanpesertaujian/'.$data->Offered_Course_id.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&currentpage='.$page.'&currentrowpage='.$rowpage.'&currentsearch='.$search) }}" class="btn btn-info btn-sm">Lihat Jadwal</a>
                      <!-- {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm']) !!}
                      {!! Form::close() !!} -->
                  </td>
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>
  <!-- <script type="text/javascript">
     $(document).ready(function () {
     $(".hapus").click(function(e) {
       var url = "{{ url('modal/faculty') }}"
           $("#detail").load(url);
           $("#detail").modal('show',{backdrop: 'true'});
        });
      });
  </script> -->

<!-- /.row -->
<!-- <script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script> -->
</section>
@endsection
