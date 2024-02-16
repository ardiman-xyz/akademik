@extends('layouts._layout')
@section('pageTitle', 'Room')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Ruang</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if($building != null && $building != 0 && $term_year != null && $term_year != 0)
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('room-CanAdd', $acc)) <a href="{{ url('master/room/create/?building='.$building.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&term_year='.$term_year ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          <b>Ruang</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('room.index') , 'method' => 'GET', 'name' => 'forms', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Semester :</label>
            <select class="form-control form-control-sm col-md-9" name="term_year" id="pilih" onchange="forms.submit()">
              <option value="0">Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
          </div>
          </div>
          
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Gedung :</label>
            <select class="form-control form-control-sm col-md-9" name="building" id="pilih" onchange="forms.submit()">
              <option value="0">Pilih Gedung</option>
              @foreach ( $select_gedung as $data )
                <option <?php if($building == $data->Building_Id){ echo "selected"; } ?> value="{{ $data->Building_Id }}">{{ $data->Building_Name }}</option>
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
            <select class="form-control form-control-sm col-md-7" name="rowpage" onchange="formsubmit();">
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
                  <th width="5%">No</th>
                  <th width="15%">Kode Ruang</th>
                  <th width="15%">Nama Ruang</th>
                  <th width="15%">Deskripsi</th>
                  <th width="10%">Kapasitas</th>
                  <th width="10%">Kapasitas Ujian</th>
                  <th width="18%">Grup Grub Sesi</th>
                  <th width="10%">Status</th>
                  <!-- <th width="10%">Gedung</th> -->
                  @if(in_array('room-CanEdit', $acc) || in_array('room-CanDelete', $acc))
                  <th width="12%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $a }}</td>
                  <td>{{ $data->Room_Code }}</td>
                  <td>{{ $data->Room_Name }}</td>
                  <td>{{ $data->Description }}</td>
                  <td>{{ $data->Capacity }}</td>
                  <td>{{ $data->Capacity_Exam }}</td>
                  <td>{{ $data->Sched_Session_Group_Name }}</td>
                  <td> <?php if ($data->Is_Active) { echo "Aktif"; }else { echo "Tidak Aktif"; } ?> </td>
                  <!-- <td>{{ $data->Building_Name }}</td> -->
                  @if(in_array('room-CanEdit', $acc) || in_array('room-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('room.destroy', $data->Room_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      <!-- <a href="{{ url('master/room/'.$data->Room_Id.'?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-success btn-sm">Detail</a> -->
                      @if(in_array('room-CanEdit', $acc))<a href="{{ url('master/room/'.$data->Room_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&term_year='.$term_year) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('room-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Room_Id]) !!}@endif
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

<script>
  function formsubmit()
  {
      document.forms.submit();
  }

  </script>

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
                  url: "{{ url('') }}/master/room/" + id,
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
