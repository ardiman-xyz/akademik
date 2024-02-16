@extends('layouts._layout')
@section('pageTitle', 'option remidi')
@section('content')

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

$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Option Remidi</h3>
    </div>
	</div>
	<div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
					@if($event_id != null && $event_id != 0)
					<div class="pull-right tombol-gandeng dua">
						<?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
						<a href="{{ url('setting/event_sched/create?event_id='.$event_id.'&course_id='.$course_id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a
					</div>
					@endif
					<b>index</b>
				</div>
			</div>
			<br>

          <!-- <b>Daftar Departemen</b> -->
          {!! Form::open(['url' => route('short_term.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						<label class="col-md-3">Departemen :</label>
            <select class="form-control form-control-sm col-md-9" name="faculty" id="pilih"  onchange="document.form.submit();">
							<option value="0">Pilih Departemen</option>
              @foreach ( $select_fakultas as $data )
                <option <?php if($fakultas == $data->Faculty_Id){ echo "selected"; } ?> value="{{ $data->Faculty_Id }}">{{ $data->Faculty_Name }}</option>
              @endforeach
            </select>

						</div>
          </div>
          <br>
					<div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						<label class="col-md-3">Pencarian :</label>
						<input type="text" name="search"  class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $search }}" placeholder="Search">
						<input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
						</div>
						<div  class="row col-md-5">
						<label class="col-md-5">Baris per halamam :</label>
						<select class="form-control form-control-sm col-md-7" name="rowpage" onchange="form.submit()">
							<option <?php if($rowpage == 10){ echo "selected"; } ?> value="10">10</option>
							<option <?php if($rowpage == 20){ echo "selected"; } ?> value="20">20</option>
							<option <?php if($rowpage == 50){ echo "selected"; } ?> value="50">50</option>
							<option <?php if($rowpage == 100){ echo "selected"; } ?> value="100">100</option>
							<option <?php if($rowpage == 200){ echo "selected"; } ?> value="200">200</option>
							<option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
						</select>
						</div>
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
                  <th width="25%">Nama Program Studi</th>
                  <th width="15%">Harus Aktif</th>
                  <th width="10%">Matakuliah Max</th>
                  <th width="20%">Semua Tahun</th>
									<th width="20%">Nilai Minimum</th>
                  @if(in_array('short_term-CanEdit', $acc))<th width="10%"><center><i class="fa fa-gear"></i></center></th>@endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Department_Name }}</td>
                  <td><center><input type="checkbox" disabled <?php if ($data->Is_Active_Student == 1) { echo "checked"; } ?> ></center></td>
									<td><center>{{ $data->Course_Limit }}</td>
                  <td><center><input type="checkbox" disabled <?php if ($data->Is_All_Year == 1) { echo "checked"; } ?> ></center></td>
									<td><center>{{ $data->Grade_Letter }}</td>
									@if(in_array('short_term-CanEdit', $acc))
									<td align="center">
										<a href="{{ url('setting/short_term/'.$data->Short_Term_Krs_Id.'/edit?department='.$data->Department_Id.'&rowpage='.$rowpage.'&search='.$search.'&Krs_Id=') }}" class="btn btn-info btn-sm">Edit</a>
                  </td>
									@endif
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
<script type="text/javascript">
$(document).on('click', '.hapus', function (e) {
		e.preventDefault();
		var id = $(this).data('id');

	//  console.log(id);
		swal({
			title: 'Data Akan Dihapus',
				text: "Klik hapus untuk menghapus data",
				type: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Hapus',
				cancelButtonText: 'cancel!',
				confirmButtonClass: 'btn btn-success',
				cancelButtonClass: 'btn btn-danger',
				buttonsStyling: true
			}, function(isConfirm) {
		if (isConfirm) {
						$.ajax({
								url: "{{ url('') }}/setting/event_sched/" + id,
								type: "DELETE",
								dataType: "json",
								data: {
									"_token": "{{ csrf_token() }}"
								},
								success: function (data) {
									swal2();
								},
								error: function(){
									swal1();
								}
						});
						// $("#hapus").submit();
					}
				});
});
	function swal1() {
		swal({
			title: 'Data masih digunakan',
				type: 'error',
				showCancelButton: false,
				cancelButtonColor: '#d33',
				cancelButtonText: 'cancel!',
				cancelButtonClass: 'btn btn-danger',
			});
	}
	function swal2() {
		swal({
			title: 'Data telah dihapus',
			type: 'success', showConfirmButton:false,
			});
			window.location.reload();
	}
</script>

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
