@extends('layouts._layout')
@section('pageTitle', 'Announcement')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

  <section class="content">

    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Announcement</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="bootstrap-admin-box-title right text-white">
              <div class="pull-right tombol-gandeng dua">
                <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
                @if(in_array('department-CanAdd', $acc))<a href="{{ url('master/announcement/create/'.$department.'?page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
                </div>
              <b>Announcement</b>
            </div>
          </div>
          <br>
          <!-- <b>Daftar Departemen</b> -->
          {!! Form::open(['url' => route('announcement.index',$department) , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <!-- <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Departemen :</label>
              <select class="form-control form-control-sm col-md-9" name="department" id="department">
                <option value="0">Semua</option>
              </select>
          </div>
        </div> -->
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
              <option <?php if($rowpage == "10"){ echo "selected"; } ?> value="">10 Baris</option>
              <option <?php if($rowpage == "20"){ echo "selected"; } ?> value="20">20 Baris</option>
              <option <?php if($rowpage == "50"){ echo "selected"; } ?> value="50">50 Baris</option>
              <option <?php if($rowpage == "100"){ echo "selected"; } ?> value="100">100 Baris</option>
              <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
            </select>
          </div><br>
          {!! Form::close() !!}
        </div><br>
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
                  {{-- <th width="5%">No</th> --}}
                  <th width="10%">Program Studi</th>
                  <th width="15%">Judul</th>
                  <th width="15%">Pesan</th>
                  <th width="15%">App</th>
                  <th width="12%">Tanggal awal</th>
                  <th width="10%">Tanggal Akhir</th>
                  <th width="10%">File</th>
                  <th width="13%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $a = "1";
                foreach ($datas as $data) {
                  // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
                  // $prod = auth()->user()->Prodi();
                  // if (count($prod)==0) {
                    if($data->Department_Id = 0 || $data->Department_Id == null){
                      $dept_name = "Semua Prodi";
                    }else{ 
                      $dept_name = $data->Department_Name;
                    }
                  ?>
                  <tr>
                    <!-- <th></th> -->
                    {{-- <td>{{ $a }}</td> --}}
                    <td><center>{{ $dept_name }}</center></td>
                    <td>{{ $data->Announcement_Name }}</td>
                    <td>{{ $data->Message }}</td>
                    <td>{{ $data->Penerima }}</td>
                    <td>{{ date('d-m-Y',strtotime($data->Post_Start_Date)) }}</td>
                    <td>{{ date('d-m-Y',strtotime($data->Post_End_Date)) }}</td>
                    <td><a href="{{route('getfile')}}?name={{$data->File_Upload}}" target="_blank">{{ $data->File_Upload }}</a></td>
                    <td align="center">
                      {!! Form::open(['url' => route('announcement.destroy', $data->Announcement_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('department-CanEdit', $acc))<a href="{{ url('master/announcement/'.$data->Announcement_Id.'/edit/'.$department.'?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                        @if(in_array('department-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Announcement_Id]) !!}@endif
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
              buttonsStyling: true,
              closeOnConfirm: false
            }, function(isConfirm) {
              if (isConfirm) {
                $.ajax({
                  url: "{{ url('') }}/master/announcement/" + id,
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
