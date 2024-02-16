@extends('layouts._layout')
@section('pageTitle', 'KRS')
@section('content')

<?php
?>
<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">List Mahasiswa Kuesioner</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
            <div class="pull-right tombol-gandeng dua">
                @if(in_array('krslist-CanView', $acc) && $request->department!='' && $request->department!=0 && $request->term_year!='')
                <a href="{{ url('proses/krslist/import?term_year='.$request->term_year.'&department='.$request->department.'&current_search='.$request->currentsearch.'&current_page='.$request->currentpage.'&current_rowpage='.$request->currentrowpage) }}" class="btn btn-warning btn-sm">Import &nbsp;<i class="fa fa-plus"></i></a>
                @endif
            </div>
            <b>List Mahasiswa Kuesioner</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('krslist.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->&nbsp &nbsp &nbsp &nbsp
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($request->term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-4" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($request->department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
          </div>
          <br>
          <!-- <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Pencarian :</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $request->search }}" placeholder="Search">
            <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
            </div>
            <div  class="row col-md-5">
            <label class="col-md-5">Baris per halamam :</label>
            <select class="form-control form-control-sm col-md-7" name="rowpage" onchange="form.submit()">
              <option <?php if($request->rowpage == 10){ echo "selected"; } ?> value="10">10</option>
              <option <?php if($request->rowpage == 20){ echo "selected"; } ?> value="20">20</option>
              <option <?php if($request->rowpage == 50){ echo "selected"; } ?> value="50">50</option>
              <option <?php if($request->rowpage == 100){ echo "selected"; } ?> value="100">100</option>
              <option <?php if($request->rowpage == 200){ echo "selected"; } ?> value="200">200</option>
              <option <?php if($request->rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
            </select>
            </div>
          </div><br> -->
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="table-responsive">
        <table id='myTable' class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="15%">no</th>
                  <th width="25%">Nim</th>
                  <th width="25%">Nama</th>
                  <th width="15%"><center>
                  @if(in_array('krslist-CanView', $acc) && $request->department!='' && $request->department!=0 && $request->term_year!='')
                  <a href="{{ url('proses/krslist/delete?department='.$request->department.'&rowpage='.$request->rowpage.'&search='.$request->search.'&term_year='.$request->term_year) }}" class="btn btn-info btn-sm">Hapus Semua</a></center>
                  @endif
                  </th>
              </tr>
          </thead>
          <tbody>
            @php($no = 1)
            @foreach($query as $key)
                <tr>
                  <th width="15%">{{$no++}}</th>
                  <th width="25%">{{$key->Nim}}</th>
                  <th width="25%">{{$key->Full_Name}}</th>
                  <th>
                    @if(in_array('krslist-CanView', $acc) && $request->department!='' && $request->department!=0 && $request->term_year!='')
                    <a href="{{ url('proses/krslist/delete?Student_Id='.$key->Student_Id.'&department='.$request->department.'&rowpage='.$request->rowpage.'&search='.$request->search.'&term_year='.$request->term_year) }}" class="btn btn-danger btn-sm">Hapus</a>
                    @endif
                  </th>
                </tr>
            @endforeach
          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function () {
        $('#myTable').dataTable({searching: true, paging: true, info: true});
    });
    </script>
</section>
@endsection
