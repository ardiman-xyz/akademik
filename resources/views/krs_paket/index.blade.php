@extends('layouts._layout')
@section('pageTitle', 'KRS')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
?>
<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KRS Per Paket</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
            @if($class_program != null && $department != 0)
            &nbsp
            <div class="pull-right tombol-gandeng dua">
              <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
              @if(in_array('student-CanAdd', $acc)) <a href="{{ url('proses/krs_paket/create?class_program='.$class_program.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&term_year='.$term_year.'&curriculum='.$curriculum) }}" class="btn btn-success btn-sm">Tambah Paket &nbsp;<i class="fa fa-plus"></i></a>@endif
            </div>
            @endif
          <b>KRS Per Paket</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('krs_paket.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row  col-md-12 text-green">
            <label class="col-md-2">TahunSemester :</label>
            <select class="form-control form-control-sm col-md-3" name="term_year" id="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <label class="col-md-2">Program Studi :</label>
            <select class="form-control form-control-sm col-md-4" name="department" id="department" onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
          </div>
          <div class="row  col-md-12 text-green">
            <label class="col-md-2" >Kurikulum :</label>
            <select class="form-control form-control-sm col-md-3" name="curriculum" id="curriculum" onchange="document.form.submit();">
              <option value="">Pilih Kurikulum</option>
              @foreach ( $select_curriculum as $data )
                <option <?php if($curriculum == $data->Curriculum_Id){ echo "selected"; } ?>  value="{{ $data->Curriculum_Id }}">{{ $data->Curriculum_Name }}</option>
              @endforeach
            </select>
            &nbsp<label class="col-md-2" >Program Kelas :</label>
            <select class="form-control form-control-sm col-md-4" name="class_program" id="class_program"  onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp
          </div>
          <br>
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
                  <th width="15%">Paket</th>
                  <th width="25%">Jumlah Matakuliah</th>
                  <th width="25%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            @if($data_paket['paket1'] > 0)
            <tr>
              <td>PAKET 1</td>
              <td>{{$data_paket['paket1']}}</td>
              <td>
                {!! Form::open(['url' => route('student.destroy', 1) , 'method' => 'delete', 'role' => 'form']) !!}
                  <a href="{{ url('proses/krs_paket/1?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>
                  {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>1]) !!}
                {!! Form::close() !!}
              </td>
            </tr>   
            @endif         
            @if($data_paket['paket2'] > 0)
            <tr>
              <td>PAKET 2</td>
              <td>{{$data_paket['paket2']}}</td>
              <td>
              {!! Form::open(['url' => route('krs_paket.destroy', 2) , 'method' => 'delete', 'role' => 'form']) !!}
                <a href="{{ url('proses/krs_paket/2?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>                 
                {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>2]) !!}
              {!! Form::close() !!}
              </td>
            </tr>   
            @endif         
            @if($data_paket['paket3'] > 0)
            <tr>
              <td>PAKET 3</td>
              <td>{{$data_paket['paket3']}}</td>
              <td>
              {!! Form::open(['url' => route('krs_paket.destroy', 2) , 'method' => 'delete', 'role' => 'form']) !!}
                <a href="{{ url('proses/krs_paket/3?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>                 
                {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>2]) !!}
              {!! Form::close() !!}
              </td>
            </tr>   
            @endif         
            @if($data_paket['paket4'] > 0)
            <tr>
              <td>PAKET 3</td>
              <td>{{$data_paket['paket4']}}</td>
              <td>
              {!! Form::open(['url' => route('krs_paket.destroy', 2) , 'method' => 'delete', 'role' => 'form']) !!}
                  <a href="{{ url('proses/krs_paket/4?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>                 
                {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>2]) !!}
              {!! Form::close() !!}
              </td>
            </tr>   
            @endif         
            @if($data_paket['paket5'] > 0)
            <tr>
              <td>PAKET 3</td>
              <td>{{$data_paket['paket5']}}</td>
              <td>
              {!! Form::open(['url' => route('krs_paket.destroy', 2) , 'method' => 'delete', 'role' => 'form']) !!}
                  <a href="{{ url('proses/krs_paket/5?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>                 
                {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>2]) !!}
              {!! Form::close() !!}
              </td>
            </tr>   
            @endif         
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>
<script>
	$(document).on('click', '.hapus', function (e) {
			e.preventDefault();
			var id = $(this).data('id'),
          term_year = $('#term_year').val(),
          curriculum = $('#curriculum').val(),
          department = $('#department').val(),
          class_program = $('#class_program').val();
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
									url: "{{ url('') }}/proses/krs_paket/" + id,
									type: "DELETE",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
                    term_year : term_year,
                    curriculum : curriculum,
                    department : department,
                    class_program : class_program
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
