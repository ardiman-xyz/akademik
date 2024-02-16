@extends('layouts._layout')
@section('pageTitle', 'Pertemuan Kuliah')
@section('content')

  <?php
  $access = auth()->user()->akses();
  $acc = $access;
  
  $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; 

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
    <h3 class="text-white">Pertemuan Kuliah</h3>
  </div>
</div>
<div class="container">
  <div class="panel panel-default bootstrap-admin-no-table-panel">
    <div class="panel-heading-green">
      <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if(in_array('schedreal-CanAdd', $acc)) <a href="{{ url('proses/schedreal/create?id='.$id) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a> @endif
          <a href="{{ url('proses/schedreal?Class_Prog_Id='.$offeredcourse->Class_Prog_Id.'&Department_Id='.$offeredcourse->Department_Id.'&Term_Year_Id='.$offeredcourse->Term_Year_Id) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
      </div>
      <div class="bootstrap-admin-box-title right text-white">
        <b>JADWAL DOSEN</b>
      </div>
    </div>
    <br>
    <div class="row col-md-12 col-xs-12">
      {!! Form::label('', 'Kode Matakuliah', ['class' => 'col-md-4 col-xs-12']) !!}:
      <label for="" class="col-md-4 col-xs-12">{{ $offeredcourse->Course_Code }}</label>
    </div>
    <div class="row col-md-12 col-xs-12">
      {!! Form::label('', 'Nama Matakuliah', ['class' => 'col-md-4 col-xs-12']) !!}:
      <label for="" class="col-md-4 col-xs-12">{{ $offeredcourse->Course_Name }}</label>
    </div>
    <div class="row col-md-12 col-xs-12">
      {!! Form::label('', 'Kelas', ['class' => 'col-md-4 col-xs-12']) !!}:
      <label for="" class="col-md-4 col-xs-12">{{ $offeredcourse->Class_Name }}</label>
    </div>
        <!-- <b>Daftar Fakultas</b> -->
        {!! Form::open(['url' => route('schedreal.show',$id) , 'method' => 'GET', 'name' => 'form', 'class' => 'row text-green', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;', 'role' => 'form']) !!}
        
        <label class="col-md-2">Baris per halamam :</label>
        <select class="form-control form-control-sm col-md-4" name="rowpage" onchange="form.submit()">
          <option <?php if($rowpage == "10"){ echo "selected"; } ?> value="">10 Baris</option>
          <option <?php if($rowpage == "20"){ echo "selected"; } ?> value="20">20 Baris</option>
          <option <?php if($rowpage == "50"){ echo "selected"; } ?> value="50">50 Baris</option>
          <option <?php if($rowpage == "100"){ echo "selected"; } ?> value="100">100 Baris</option>
          <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
        </select>
        {{-- <label>Baris</label> --}}
        {!! Form::close() !!}
    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
      @if (count($errors) > 0)
        @foreach ( $errors->all() as $error )
          <p class="alert alert-danger">{{ $error }}</p>
        @endforeach
      @endif
      <br>
      <div class="table-responsive">
      <table class="table table-striped table-font-sm">
        <thead class="thead-default thead-green">
            <tr>
                <th width="5%">Pertemuan</th>
                <th width="20%">Dosen</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Konten Matakuliah</th>
                <th width="14%">Deskripsi</th>
                <th width="6%">Peserta</th>
                <th width="6%">Kehadiran</th>
                <th width="6%">Tanggal Dibuat</th>
                @if(in_array('offered_course-CanEditCapacity', $acc) || in_array('offered_course-CanEditEmployee', $acc) || in_array('offered_course-CanDelete', $acc))
                <th width="19%"><center><i class="fa fa-gear"></i></center></th>
                @endif
            </tr>
        </thead>
        <tbody>
          @foreach($datas as $data)
          <?php 
          $date = explode(' ',$data->Date);
          $tanggal = tanggal_indo($date[0],false);
          // $tanggal = $data->Date;
          $ja = explode(':',$date[1]);
          unset($ja[2]);
          $jam = implode(':',$ja);

          $createddate = explode(' ',$data->Created_Date);
          $createdtanggal = tanggal_indo($createddate[0],false);
          // $tanggal = $data->Date;
          $createdja = explode(':',$createddate[1]);
          unset($createdja[2]);
          $createdjam = implode(':',$createdja);

          ?>
            <tr>
              <td>Ke- {{ $data->Meeting_Order }}</td>
              <td>
              <?php
                  foreach ($data->empEmployees as $key) {
                      if ($key != null) {
                        // $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key->Employee_Id)
                        // ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                        // ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                        // ->first();
                        $anu = DB::table('emp_employee')
                        ->join(DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"), 'emp_employee.Employee_Id', 'max_placement.Employee_Id'
                        )
                        ->join('emp_placement',function($golru){
                            $golru->on('emp_placement.Employee_Id','emp_employee.Employee_Id')
                            ->on('emp_placement.Tmt_Date','max_placement.Tmt_Date');
                        })
                        // ->where('emp_placement.Department_Id', $key->Department_Id)
                        ->where('emp_employee.Employee_Id', $key->Employee_Id)
                        ->first();
                        // dd($key);
                        if($anu){
                          if($anu->Department_Id != $offeredcourse->Department_Id){
                            $dosennya = DB::table('emp_employee')->where('Employee_Id',$key->Employee_Id)->first();
                            $firstitle = $dosennya->First_Title;
                            $name = $dosennya->Name;
                            $lasttitle = $dosennya->Last_Title;
                            // dd($firstitle);
                            echo "<div class='btn btn-sm' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                          }else{
                              $dosennya = DB::table('emp_employee')->where('Employee_Id',$key->Employee_Id)->first();
                            $firstitle = $dosennya->First_Title;
                            $name = $dosennya->Name;
                            $lasttitle = $dosennya->Last_Title;
                            echo "<div class='btn btn-sm' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                          }                          
                        }else{
                              $dosennya = DB::table('emp_employee')->where('Employee_Id',$key->Employee_Id)->first();
                            $firstitle = $dosennya->First_Title;
                            $name = $dosennya->Name;
                            $lasttitle = $dosennya->Last_Title;
                            echo "<div class='btn btn-sm' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                          }   
                      }
                  }
              ?>
              </td>
              <td>{{ $tanggal }} {{ $jam }}</td>
              <td>{{ $data->Course_Content }}</td>
              <td>{{ $data->Description }}</td>
              <td>{{ $offeredcourse->jml_peserta }}</td>
              <td>{{ $data->acd_students_count }}</td>
              <td>{{ $createdtanggal }} </td>
              <td>
              {!! Form::open(['url' => route('schedreal.destroy', $data->Sched_Real_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                <a href="{{ url('proses/schedreal/peserta/'.$data->Sched_Real_Id.'/'.$offeredcourse->Offered_Course_id.'/detail') }}" class="btn btn-warning btn-sm">Detail</a>
                @if(in_array('schedreal-CanViewPeserta', $acc))<a href="{{ url('proses/schedreal/peserta/'.$data->Sched_Real_Id.'/'.$offeredcourse->Offered_Course_id) }}" class="btn btn-info btn-sm">Peserta</a>@endif
                @if(in_array('schedreal-CanEdit', $acc))<a href="{{ url('proses/schedreal/'.$data->Sched_Real_Id.'/edit?id='.$offeredcourse->Offered_Course_id) }}" class="btn btn-success btn-sm">Edit</a>@endif                
                @if(in_array('schedreal-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Sched_Real_Id]) !!}@endif
              {!! Form::close() !!}
              </td>
            </tr>
          @endforeach
           <tr bgcolor='#dbc3c0'>
              <td colspan="8"><center>TOTAL PERTEMUAN = {{$totalpertemuan}}</center></td>
              <td><center>@if(in_array('schedreal-CanViewPeserta', $acc))<a href="{{ url('proses/schedreal/pesertatotal/'.$offeredcourse->Offered_Course_id) }}" class="btn btn-info btn-sm">Detail Peserta</a>@endif</center></td>
            </tr>
        </tbody>
      </table>
      </div>
      <?php echo $datas->render('vendor.pagination.bootstrap-4'); ?>
    </div>
  </div>
</div>

<script>
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
                url: "{{ url('') }}/proses/schedreal/" + id,
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