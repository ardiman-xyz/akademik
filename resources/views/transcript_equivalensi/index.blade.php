@extends('layouts._layout')
@section('pageTitle', 'Transkrip Equivalensi')
@section('content')


<?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>

<div class="container-fluid-title">
  <div class="title-laporan">
    <h3 class="text-white">Nilai Mahasiswa Equivalensi</h3>
  </div>
</div>
<div class="container">
  <div class="panel panel-default bootstrap-admin-no-table-panel">
    <div class="panel-heading-green">
      <div class="bootstrap-admin-box-title right text-white">
        <b>Nilai Mahasiswa Equivalensi</b>
      </div>
    </div>
    <br>
        {!! Form::open(['url' => route('transcript_equivalensi.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="row col-md-7">
          <label class="col-md-3">Program Studi : &nbsp</label>
          <select class="form-control form-control-sm col-md-9" name="department"  onchange="document.form.submit();">
            <option value="0">Pilih Program Studi</option>
            @foreach ( $select_department as $data )
              <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
            @endforeach
          </select>
        </div>
        <div class="row col-md-5">
          <label class="col-md-5">Tahun Angkatan : &nbsp</label>
          <select class="form-control form-control-sm col-md-7" name="entry_year"  onchange="document.form.submit();">
            <option value="0">Pilih Angkatan</option>
            @foreach ( $select_entry_year as $data )
              <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
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
                  <th width="20%">NIM</th>
                  <th width="29%">Nama Mahasiswa</th>
                  <th width="18%">Jumlah SKS Diakui</th>
                  <th width="18%">Jumlah MK Diakui</th>
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {

              ?>
              <tr>
                  <td>{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
                  <td>{{ $data->Jml_Sks }}</td>
                  <td>{{ $data->Jml_mk }}</td>

                  <td align="center">
                      <a href="{{ url('proses/transcript_equivalensi/'.$data->Student_Id.'?department='.$department.'&entry_year='.$entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-warning btn-sm" >Lihat Nilai <i class="fa fa-list"></i> </a>
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
  @if (Session::has('sweet_alert.alert'))
      <script>
          swal({
              text: "{!! Session::get('sweet_alert.text') !!}",
              title: "{!! Session::get('sweet_alert.title') !!}",
              timer: "{!! Session::get('sweet_alert.timer') !!}",
              type: "{!! Session::get('sweet_alert.type') !!}",
              showConfirmButton: "{!! Session::get('sweet_alert.showConfirmButton') !!}",
              confirmButtonText: "{!! Session::get('sweet_alert.confirmButtonText') !!}",
              confirmButtonColor: "#AEDEF4",
              showCancelButton: true,
              cancelButtonColor: "#DD6B55",
              confirmButtonText: "Ya",
              cancelButtonText: "Tidak",
              closeOnConfirm: false,
              closeOnCancel: true
            },
            function(isConfirm){
             if (isConfirm)
               {
                 // swal("Deleted!", "Your file has been deleted.", "success");
                 var url = {!! json_encode(url('/')) !!};
                 var department = <?php echo $department; ?>;
                 var entry_year = <?php echo $entry_year; ?>;
                 var student_id = <?php echo $student; ?>;

                 window.location = url+"/proses/transcript_equivalensi/create?student_id="+student_id+"&department="+department+"&entry_year="+entry_year;
               }

            });
      </script>
  @endif
</section>
@endsection
