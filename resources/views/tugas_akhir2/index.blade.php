@extends('layouts._layout')
@section('pageTitle', 'Tugas Akhir')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tugas Akhir</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            <?php
            foreach ($select_department as $data) {
            if ($department == $data->Department_Id) {
              ?>
                @if(in_array('tugas_akhir-CanAdd', $acc))<a href="{{ url('proses/tugas_akhir/create/?department='.$department.'&term_year='.$term_year.'&angkatan='.$entry_year) }}" class="btn btn-success btn-sm">Tambah Data &nbsp;<i class="fa fa-plus"></i></a>@endif

              <?php }}
              ?>
          </div>
          <b>Tugas Akhir</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('tugas_akhir.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->&nbsp &nbsp &nbsp &nbsp
            <label>Prodi :</label>&nbsp
            <select class="form-control form-control-sm col-sm" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp &nbsp &nbsp &nbsp
            <label value="0">Tahun/Semester :</label>&nbsp
            <select class="form-control form-control-sm col-sm" name="term_year"  onchange="document.form.submit();">
                <option value="0" selected>Semua</option>
                @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp &nbsp &nbsp &nbsp
            <!-- <label class="col-md-2">Tahun Semester :</label> -->

            <label value="0">Angkatan:</label>&nbsp
            <select class="form-control form-control-sm col-sm" name="angkatan"  onchange="document.form.submit();">
              <option value="0">Semua</option>
              @foreach ( $select_entry_year as $data )
              <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
            @endforeach
            </select>&nbsp&nbsp &nbsp &nbsp &nbsp&nbsp&nbsp &nbsp &nbsp &nbsp&nbsp

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
          <table width="100%" class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>
                    <th width="5%">No.</th>
                    <th width="10%">NIM</th>
                    <th width="10%">Nama Mahasiswah</th>
                    <th width="10%">Tgl Permohonan</th>
                    <th width="35%">Judul</th>
                    <th width="10%">Pembimbing 1</th>
                    <th width="10%">Pembimbing 2</th>
                    <th width="15%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($query as $data) {
                ?>
                <tr>
                    <!-- <th></th> -->
                    <td>{{$a}}</td>
                    <td>{{ $data->Nim }}</td>
                    <td>{{ $data->nm_mhs }}</td>
                    <td>{{ Carbon\Carbon::parse($data->Application_Date)->format('d-m-Y') }}</td>
                    <td>{{ $data->Thesis_Title }}</td>
                    <td>{{ $data->pem1 }}</td>
                    <td>{{ $data->pem2 }}</td>
                    <td align="center">
                      {!! Form::open(['url' => route('tugas_akhir.destroy', $data->Thesis_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      <a href="{{ url('proses/tugas_akhir/'.$data->Thesis_Id.'?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-success btn-sm">Detail</a>
                      @if(in_array('tugas_akhir-CanEdit', $acc))<a href="{{ url('proses/tugas_akhir/'.$data->Thesis_Id.'/edit?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&Krs_Id=') }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('tugas_akhir-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Thesis_Id]) !!}@endif
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
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
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
                  url: "{{ url('') }}/proses/tugas_akhir/" + id,
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
        type: 'success',
        });
        window.location.reload();
    }
          </script>
</section>
@endsection
