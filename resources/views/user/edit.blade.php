@extends('layouts._layout')
@section('pageTitle', 'User')
@section('content')

<?php
$access = auth()->user()->akses();
		  $acc = $access;
foreach ($query_edit as $data_edit) {

?>

<style>
@media (min-width:993px){
  .w3-modal-content{width:20%}
  .w3-hide-large{display:none!important}
  .w3-sidebar.w3-collapse{display:block!important}}

</style>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit User</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/user?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Mengubah Data")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        {!! Form::open(['url' => route('user.update', $data_edit->id) , 'method' => 'put', 'class' => 'form', 'role' => 'form']) !!}
        <div class="form-group">
          {!! Form::label('', 'Nama', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="text" name="nama" min="1" value="{{ $data_edit->name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Email', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <input type="email" name="email" min="1" value="{{ $data_edit->email }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Fakultas', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="fakultas">
                <option value="">All</option>
              @foreach ( $fakultas as $dataa )
                <option <?php if($dataa->Faculty_Id == $data_edit->Faculty_Id){ echo "selected"; } ?> value="{{ $dataa->Faculty_Id }}">{{ $dataa->Faculty_Name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Prodi', ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="department">
                <option value="">All</option>
              @foreach ( $department as $dataa )
                <option <?php if($dataa->Department_Id == $data_edit->Department_Id){ echo "selected"; } ?> value="{{ $dataa->Department_Id }}">{{ $dataa->Department_Name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <table class="table table-striped table-font-sm table-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="5%">No</th>
                  <th width="25%">Nama</th>
                  <th width="20%">Email</th>
                  <th width="20%">Role</th>
                  <th width="10%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($_role as $data) {
              // dd($data);
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $a }}</td>
                  <td>{{ $data->name }}</td>
                  <td>{{ $data->email }}</td>
                  <td>{{ $data->description }}</td>

                  <td class="table-action">
                    @if(in_array('user-CanEdit', $acc) && $data->app == 'Akademik') <a class="btn btn-info btn-sm" onclick="myFunction({{$data->_role_id_id}})">Edit</a> @endif
                    @if(in_array('user-CanDelete', $acc) && $data->app == 'Akademik') {!! Form::button('Hapus', ['class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->_role_id_id]) !!} @endif
                  </td>
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        

        <!-- <div class=" col-md-12 col-xs-12"> -->
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>
        <!-- </div> -->
        <!-- <center><a onclick="tambah()" class="btn btn-primary">OK</a></center> -->
        {!! Form::close() !!}
      </div>
    </div>
  </div>


<div id="ubahpembayaran" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Edit Role User</h4>
      </header>
      <div class="w3-container">
      </br>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-12">
              <div class="form-group">
                <div class="col-md-12">
                  <input type="text" hidden id="id_userrole" name="id_userrole">
                  <select class="form-control form-control-sm" name="role">
                    @foreach ( $role as $data )
                      <option <?php if($data->id == $data_edit->role_id){ echo "selected"; } ?> value="{{ $data->id }}">{{ $data->name }}</option>
                    @endforeach
                  </select>
                  <br>
                  <a data-id="{{ $data->id }}" 
                    data-name="{{ $data->name }}"
                    class="btn badge-primary" 
                    id="simpan_role"  
                    href="javascript:">Simpan</a>
                </div>
              </div>
            </div>
          </div>
        </br>
      </div>
      </div>
  </div>

<script>

function myFunction(idnya) {
  document.getElementById("ubahpembayaran").style.display = "block";
  document.getElementById("id_userrole").value = idnya;  
}

$(document).on('click', '#simpan_role', function (e) {
  var id_userrole = $("[name='id_userrole']").val();
  var id_role = $("[name='role']").val();
  // console.log(id_role);
  swal({
        title: "Ubah Data",
          text: "Anda akan mengubah Role",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Oke',
          cancelButtonText: 'cancel!',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: true
        }, function(isConfirm) {
      if (isConfirm) {
              $.ajax({
                  url: '{{route('user.update_role')}}',
                  type: "post",
                  dataType: "json",
                  data: {
                    id_userrole: id_userrole,
                    id_role : id_role,                
                    "_token": "{{ csrf_token() }}",
                    
                  },
                  success: function (res) {
                    swal3();
                  },
                  error: function(xhr, ajaxOptions, thrownError){
                    swal({
                                    title: thrownError,
                                    text: 'Error !! ' + xhr.status,
                                    type: "error",
                                    confirmButtonColor: "#02991a",
                                    confirmButtonText: "Refresh Serkarang",
                                    cancelButtonText: "Tidak, Batalkan!",
                                    closeOnConfirm: false,
                                },
                                function (isConfirm) {
                                    if (isConfirm) {
                                        window.location.reload(true) // submitting the form when user press yes
                                    }
                                });
                  }
              });
              // $("#hapus").submit();
            }
          });
});

function swal3() {
      swal({
        title: 'Data telah Diubah',
        type: 'success', showConfirmButton:true,
        });
        window.location.reload();
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
                url: "{{ url('') }}/administrator/user/destroy_role_user/" + id,
                type: "DELETE",
                dataType: "json",
                data: {
                  "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                  window.location.href = "{{ url('') }}/administrator/user";
                },
                error: function(){
                  window.location.href = "{{ url('') }}/administrator/user";
                }
            });
            // $("#hapus").submit();
          }
        });
});

  function swal2() {
    swal({
      title: 'Data telah dihapus',
      type: 'success', showConfirmButton:false,
      });
      window.location.href("http://localhost/simak_sttnas_gmail/public/administrator/user");
  }
</script>

<!-- /.row -->

</section>

<?php } ?>
@endsection
