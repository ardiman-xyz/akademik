@extends('layouts._layout')
@section('pageTitle', 'Krs Setuju')
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

<style>
element.style {
    height: 0px;
}
</style>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Setujui KRS Mahasiswa</h3>
    </div>
	</div>
	<div class="container">
	<p id="message"></p>
    <div id="dialog">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
					@if($event_id != null && $event_id != 0)
					<div class="pull-right tombol-gandeng dua">
						<?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
					</div>
					@endif
					<b>Setujui KRS Mahasiswa</b>
				</div>
			</div>
			<br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('krs_approved.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						<label class="col-md-3">Semester :</label>
            <select class="form-control form-control-sm col-md-9" name="term_year" id="pilih"  onchange="document.form.submit();">
              <option value="0">Pilih Semester</option>
              @foreach ( $mstr_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select><br>
          </div>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
         @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menyimpan Perubahan" ||$error == "Berhasil Menambah Jadwal Pengisian")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="20%">Nama Program Studi</th>
                  	<th width="10%"><center><i class="fa fa-gear"></i></center></th>
                  	<th width="10%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data){
	  		$cek_data = DB::table('acd_student_krs as a')
				->join('acd_student as b','a.Student_Id','=','b.Student_Id')
				->where('b.Department_Id',$data->Department_Id)
				->count();
	  		$cek_datas = DB::table('acd_student_krs as a')
				->join('acd_student as b','a.Student_Id','=','b.Student_Id')
                ->where('a.Term_Year_Id', $term_year )
                ->where('a.Is_Approved',null)
				->where('b.Department_Id',$data->Department_Id)->get();
				
	  		$cek_data_acc = DB::table('acd_student_krs as a')
				->join('acd_student as b','a.Student_Id','=','b.Student_Id')
                ->where('a.Term_Year_Id', $term_year )
                ->where('a.Approved_By','Admin')
				->where('b.Department_Id',$data->Department_Id)->count();
              ?>
              <tr>
                  <!-- <th></th> -->
                <td>{{ $data->Department_Name }}</td>
				<td align="center">
                      {!! Form::open(['url' => route('krs_approved.destroy', $data->Department_Id) , 'method' => 'delete', 'role' => 'form']) !!}
					<div class="btn-group">
					@if($cek_data > 0)
                      <a data-params="{{$data->Department_Id}}" href="" class="btn btn-info btn-sm setujui">Setujui</a>
					@else
                      <a data-params="{{$data->Department_Id}}" href="" class="btn btn-danger btn-sm rollback">Roll Back</a>
					@endif
					</div>
                      {!! Form::close() !!}
                </td>
				<td align="center">
                      {!! Form::open(['url' => route('krs_approved.destroy', $data->Department_Id) , 'method' => 'delete', 'role' => 'form']) !!}
					<div class="btn-group">
					@if($cek_data > 0)
                      <a data-params="{{$data->Department_Id}}" href="" class="btn btn-danger btn-sm rollback">Roll Back</a>
					@else
					@endif
					</div>
                      {!! Form::close() !!}
                </td>
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>
        <?php //echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>
<script type="text/javascript">
$(document).ready(function () {
	var dialog = $('#dialog');

	function onClose() {
		location.reload();
	}

	$(document).on('click', '.setujui', function (e) {
			e.preventDefault();
			var id = $(this).data('params'),
				term_year = $("[name='term_year']").val();
			if(term_year == null || term_year == 0){
				swal({
				title: 'Pilih Semester Terlebih Dahulu',
				type: 'warning', 
				showConfirmButton:true,
				confirmButtonText: 'Oke',
				});
				// window.location.reload();
			}else{
			swal({
				title: 'KRS Disetujui',
					text: "KRS Semua Mahasiswa Pada Prodi Ini Akan Disetujui",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Setuju',
					cancelButtonText: 'Cancel!',
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					buttonsStyling: true
				}, function(isConfirm) {
			if (isConfirm) {
							$.ajax({
									url: "{{ url('') }}/proses/krs_approved/approved/approved",
									type: "get",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
										Department_Id : id,
										Term_Year_Id : term_year,
									},
									success: function (data) {
										console.log(data);
										var log = data.log['kurang'];
										// var loging = document.getElementById("message").innerHTML = log;
										if(data.success == false){
											dialog.kendoDialog({
												width: "450px",
												height:"600px",
												title: "Kapasitas  Penuh",
												closable: false,
												modal: false,
												content: "<table style='height=100%'><thead><th>Matakuliah</th><th>Kekurangan</th></thead>"+log+"<table>",
												actions: [
													{ text: 'Ok', primary: true }
												],
												close: onClose
											});
										}else{
											swal({
												title: 'Data telah Diubah',
												type: 'success', showConfirmButton:false,
											});
											window.location.reload();
										}
									},
									error: function(xhr, ajaxOptions, thrownError) {
										swal({
												title: thrownError,
												text: 'Error!! ' + xhr.status,
												type: "error",
												confirmButtonColor: "#02991a",
												confirmButtonText: "Refresh Serkarang",
												cancelButtonText: "Tidak, Batalkan!",
												closeOnConfirm: false,
											},
											function(isConfirm) {
												if (isConfirm) {
												// window.location.reload(true) // submitting the form when user press yes
												}
											});
									}
							});
							// $("#hapus").submit();
						}
					});
			}
	});

	$(document).on('click', '.rollback', function (e) {
			e.preventDefault();
			var id = $(this).data('params'),
				term_year = $("[name='term_year']").val();
			swal({
				title: 'Batalkan Setujui KRS',
					text: "KRS Semua Mahasiswa Pada Prodi Ini Akan Dibatalkan",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Setuju',
					cancelButtonText: 'Cancel!',
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					buttonsStyling: true
				}, function(isConfirm) {
			if (isConfirm) {
							$.ajax({
									url: "{{ url('') }}/proses/krs_approved/approved/rollback",
									type: "get",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
										Department_Id : id,
										Term_Year_Id : term_year,
									},
									success: function (data) {
										swal({
										title: 'Data telah dihapus',
										type: 'success', showConfirmButton:false,
										});
										window.location.reload();
									},
									error: function(){
										swal1();
									}
							});
							// $("#hapus").submit();
						}
					});
	});
});

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
