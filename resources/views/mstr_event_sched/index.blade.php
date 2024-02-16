@extends('layouts._layout')
@section('pageTitle', 'Event Sched')
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
      <h3 class="text-white">Setting Jadwal Pengisian</h3>
    </div>
	</div>
	<div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
					@if($event_id != null && $event_id != 0)
					<div class="pull-right tombol-gandeng dua">
						<?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
						@if(in_array('event_sched-CanAdd', $acc)) <a href="{{ url('setting/event_sched/create?event_id='.$event_id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
					</div>
					@endif
					<b>Jadwal Pengisian</b>
				</div>
			</div>
			<br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('event_sched.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div  class="row col-md-7">
						<label class="col-md-3">Tipe :</label>
            <select class="form-control form-control-sm col-md-9" name="event_id" id="pilih"  onchange="document.form.submit();">
              <option value="0">Pilih Tipe</option>
              @foreach ( $select_event as $data )
                <option <?php if($event_id == $data->Event_Id){ echo "selected"; } ?> value="{{ $data->Event_Id }}">{{ $data->Event_Name }}</option>
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
              @if ($error=="Berhasil Menyimpan Perubahan" ||$error == "Berhasil Menambah Jadwal Pengisian")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
        <div class="table-responsive">
        <table id="dataTables" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
					<th width="20%">Nama Program Studi</th>
					<th width="10%">Semester Berlaku</th>
					<th width="10%">Program Kelas</th>
					<th width="5%">Buka?</th>
					@if($event_id == 1 || $event_id == 3 || $event_id == 4 || $event_id == 5|| $event_id == 6)
					<th width="15%">Tanggal Mulai</th>
					<th width="15%">Tanggal Akhir</th>
					@endif
					@if($event_id == 1)
					<th width="15%">Tanggal Akhir Pembayaran</th>
					@endif
					@if($event_id == 0)
					<th width="15%">Days</th>
					@endif
					@if(in_array('event_sched-CanEdit', $acc) || in_array('event_sched-CanDelete', $acc))
					<th width="10%"><center><i class="fa fa-gear"></i></center></th>
					@endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              if ($data->Start_Date == null) {
                $Start_Date = "";
              }else {
                $date = strtotime($data->Start_Date);
                $da = Date('Y-m-d',$date);
                $Start_Date = tanggal_indo($da,true);
              }
              if ($data->End_Date == null) {
                $End_Date = "";
              }else {
                $date = strtotime($data->End_Date);
                $da = Date('Y-m-d',$date);
                $End_Date = tanggal_indo($da,true);
              }
              if ($data->End_Date_Cost == null) {
                $End_Date_Cost = "";
              }else {
                $date = strtotime($data->End_Date_Cost);
                $da = Date('Y-m-d',$date);
                $End_Date_Cost = tanggal_indo($da,true);
              }
              ?>
              <tr>
                  <!-- <th></th> -->
					<td>{{ $data->Department_Name }}</td>
					<td>{{ $data->Term_Year_Name }}</td>
					<td>{{ $data->Class_Program_Name }}</td>
					<td><center><input type="checkbox" disabled <?php if ($data->Is_Open == true) { echo "checked"; } ?> ></center></td>
					@if($event_id == 1 || $event_id == 3 || $event_id == 4 || $event_id == 5 || $event_id == 6)
					<td>{{ $Start_Date }}</td>
					<td>{{ $End_Date }}</td>
					@endif
					@if($event_id == 1)
					<td>{{ $End_Date_Cost }}</td>
					@endif
					@if($event_id == 7)
					<td>{{ $data->Day }}</td>
					@endif
					@if(in_array('event_sched-CanEdit', $acc) || in_array('event_sched-CanDelete', $acc))
					<td align="center">
                      {!! Form::open(['url' => route('event_sched.destroy', $data->Event_Sched_Id) , 'method' => 'delete', 'role' => 'form']) !!}
					<div class="btn-group">
                      @if(in_array('event_sched-CanEdit', $acc)) <a href="{{ url('setting/event_sched/'.$data->Event_Sched_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&eventnya='.$event_id) }}" class="btn btn-info btn-sm">Edit &nbsp;</a>@endif
                      @if(in_array('event_sched-CanDelete', $acc)) &nbsp;{!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Event_Sched_Id]) !!}@endif
					</div>
                      {!! Form::close() !!}
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
<script>
    $(document).ready(function () {
        $('#dataTables').dataTable({searching: false, paging: false, info: false});
    });
</script>
</section>
@endsection
