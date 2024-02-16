@extends('layouts._layout')
@section('pageTitle', 'Student')
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
      <h3 class="text-white">Mahasiswa Aktif / Keluar / Pindah</h3>
    </div>
	</div>
	<div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
					@if($entry_year != null && $entry_year != 0 && $department != null && $department != 0)
					&nbsp
					<div class="pull-right tombol-gandeng dua">
						<?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
		        			@if(in_array('studentmundurkeluardo-CanAdd', $acc)) @if($status != 7 && $status != 8)<a onclick="proses()" class="btn btn-success btn-sm" >Tambah data &nbsp;<i class="fa fa-plus"></i></a> @endif @endif
																									
						<!-- @if(in_array('studentmundurkeluardo-CanAdd', $acc)) <a href="{{ url('setting/studentmundurkeluardo/create/student?entry_year='.$entry_year.'&department='.$department.'&status='.$status.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&status='.$status ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif -->
					</div>
					@endif
					<b>Mahasiswa Aktif / Keluar / Pindah</b>
				</div>
			</div>
			<br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('studentmundurkeluardo.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div class="row col-md-7">
							<label class="col-md-3" >Program Studi :</label>
	            <select class="form-control form-control-sm col-md-7" name="department" onchange="document.form.submit();">
	              <option value="0">Pilih Prodi</option>
	              @foreach ( $select_department as $data )
	                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?>  value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
	              @endforeach
	            </select>
						</div>
						<div class="row col-md-5">
							<label class="col-md-5">Angkatan :</label>
	            <select class="form-control form-control-sm col-md-7" name="entry_year"  onchange="document.form.submit();">
	              <option value="0">Pilih Angkatan</option>
	              @foreach ( $select_entry_year as $data )
	                <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
	              @endforeach
	            </select>
						</div>
          </div>
					<br>
          <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div class="row col-md-7">							
						</div>
						<div class="row col-md-5">
							<label class="col-md-5">Status :</label>
	            <select class="form-control form-control-sm col-md-7 status" name="status"  onchange="document.form.submit();">
	              <!-- <option value="">Semua</option> -->
	              @foreach ( $select_status as $data )
	                <option <?php if($status == $data->Status_Id){ echo "selected"; } ?> value="{{ $data->Status_Id }}">{{ $data->Status_Name }}</option>
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
					<label style="font-size:10pt;">Jumlah Mahasiswa : {{$count_dep}}</label>
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
				<th>NIM</th>
				<th>Nama Mahasiswa</th>
				<th>Status</th>
				<?php 
				if($status == 5){ ?>
					<th>Pindah ke</th>
					<th>Pindah dari</th>
				<?php } else{ ?>
					<th>No. Hp</th>
				<?php } 
				?>
                <th>Jenis Kelamin</th>
				@if(in_array('studentmundurkeluardo-CanEdit', $acc) || in_array('studentmundurkeluardo-CanDelete', $acc))
                <th width="15%"><center><i class="fa fa-gear"></i></center></th>
				@endif
              </tr>
          </thead>
          <tbody>
            <?php
            foreach ($query as $data) {
              if ($data->Birth_Date == null) {
                $birth = "";
              }else {
                $date = strtotime($data->Birth_Date);
                $da = Date('Y-m-d',$date);
                $birth = tanggal_indo($da,true);
              }

            ?>
              <tr>
				<td>{{ $data->Nim }}</td>
				<td>{{ $data->Full_Name }}</td>
				<td>{{ $data->Status_Name }}</td>
				<?php 
				if($status == 5){ ?>
					<td>{{ $data->Department_Name_To }}</td>
					<td>{{ $data->Department_Name_From }}</td>
				<?php } else{ ?>
					<td>{{ $data->Phone_Mobile }}</td>
				<?php } 
				?>
				<td>{{ $data->Gender_Type }}</td>
				@if(in_array('studentmundurkeluardo-CanEdit', $acc) || in_array('studentmundurkeluardo-CanDelete', $acc))
				<td align="center">
				{!! Form::open(['url' => route('student.destroy', $data->Student_Id) , 'method' => 'delete', 'role' => 'form']) !!}
				@if(in_array('studentmundurkeluardo-CanEdit', $acc)) 
				<?php 
				if($status == 3){ ?>
					<a href="{{ url('setting/studentmundurkeluardo/student_mengundurkandiri/'.$status.'/'.$data->Student_Id.'?entry_year_id='.$entry_year.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>
				<?php }
				elseif($status == 4){ ?>
					<a href="{{ url('setting/studentmundurkeluardo/student_do/'.$status.'/'.$data->Student_Id.'?entry_year_id='.$entry_year.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>
				<?php }
				elseif($status == 5){ 
					if($department == $data->Department_Destination){ ?>
						<a href="{{ url('setting/studentmundurkeluardo/student_pindah/'.$status.'/'.$data->Student_Id.'?entry_year_id='.$entry_year.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>
					<?php }
				?>
				<?php }
				elseif($status == 6){ ?>
					<a href="{{ url('setting/studentmundurkeluardo/student_meninggal/'.$status.'/'.$data->Student_Id.'?entry_year_id='.$entry_year.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>
				<?php }
				else{
				}
				?>
				
				<!-- <a onclick="edit()" href="#" class="btn btn-info btn-sm" >Edit</a>@endif -->
				<!-- window.locaturl + '/setting/studentmundurkeluardo/student_mengundurkandiri/'+status+'/'+id+'?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search='; -->
				<?php if($status == 5){  
					if($department == $data->Department_Destination){ ?>
							@if(in_array('studentmundurkeluardo-CanDelete', $acc))
							{!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Student_Id]) !!}@endif
							<?php 
						} 
					} else{ ?> 
							@if(in_array('studentmundurkeluardo-CanDelete', $acc)) @if($status != 7) {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Student_Id]) !!}@endif @endif
					<?php } ?>
				{!! Form::close() !!}
				</td>
				@endif
              </tr>
              <?php
              }
              ?>
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>

	<script>
		function proses() {
			var status = $(".status").val();
			var entry_year = <?php echo $entry_year; ?>;
			var department = <?php echo $department; ?>;
			var rowpage = <?php echo $rowpage; ?>;
			var search = <?php $src = $search; 
												if($src == null){
													echo 99;
												}if($src != null){
													echo 999;
												}	else{

												}
										?>;

			var url = {!! json_encode(url('/')) !!};
			if (status==3) {
				if(search == 99){
					window.location.href=url + '/setting/studentmundurkeluardo/create/student_mengundurkandiri?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search=';
				}else{
					window.location.href=url + '/setting/studentmundurkeluardo/create/student_mengundurkandiri?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search='+search;
				}
			}
			else if(status == 4){
				if(search == 99){
					window.location.href=url + '/setting/studentmundurkeluardo/create/student_do?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search=';
				}else{
					window.location.href=url + '/setting/studentmundurkeluardo/create/student_do?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search='+search;
				}
			}
			else if(status == 5){
				if(search == 99){
					window.location.href=url + '/setting/studentmundurkeluardo/create/student_pindah?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search=';
				}else{
					window.location.href=url + '/setting/studentmundurkeluardo/create/student_pindah?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search='+search;
				}
			}
			else if(status == 6){
				if(search == 99){
					window.location.href=url + '/setting/studentmundurkeluardo/create/student_meninggal?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search=';
				}else{
					window.location.href=url + '/setting/studentmundurkeluardo/create/student_meninggal?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search='+search;
				}
			}
			else{
				if(search == 99){
					window.location.href=url + '/setting/studentmundurkeluardo/create/student?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search=';
				}else{
					window.location.href=url + '/setting/studentmundurkeluardo/create/student?entry_year='+entry_year+'&department='+department+'&status='+status+'&rowpage='+rowpage+'&search='+search;
				}
			}
		}

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
									url: "{{ url('') }}/setting/studentmundurkeluardo/" + id,
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

</section>
@endsection
