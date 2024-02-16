@extends('layouts._layout')
@section('pageTitle', 'Department Class Program')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;


foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Ubah Prodi Vs Program Kelas</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('master/department_class_program/'.$fakultas.'?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <?php $tgl_dikti = Date("Y-m-d",strtotime($data_edit->Department_Dikti_Sk_Date)); ?>
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Program Kelas")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('department_class_program.store') , 'method' => 'POST', 'class' => 'form']) !!}
          <input type="hidden" name="Department_Id" value="{{ $data_edit->Department_Id }}">
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Nama Prodi', ['class' => 'col-md-2 form-label']) !!}
            <input type="text" name="Department_Name" min="1" value="{{ $data_edit->Department_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-4">
          </div><br>
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Program Kelas', ['class' => 'col-md-2 form-label']) !!}
            <select class="form-control form-control-sm col-md-4" name="Class_Prog_Id">
              @foreach ( $classprogram as $data )
                <option value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>
          </div>
          <br>
          <div class="col-md-3">
            @if(in_array('department_class_program-CanAdd', $acc))<button type="submit" class="btn btn-primary btn-flat">Tambah</button>@endif
          </div>
          {!! Form::close() !!}
      </div>
    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
      <br>
      <div class="row">
      <div class="col-md-1">
      </div>
      <div class="table-responsive col-md-10">
      <table class="table table-striped table-font-sm">
        <thead class="thead-default thead-green">
            <tr>
                <!-- <th class="col-sm-1">No</th> -->
                <th width="5%">No</th>
                <th width="22%">Program Kelas</th>
                <th width="15%"><center><i class="fa fa-gear"></i></center></th>
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
                <td>{{ $data->Class_Program_Name }}</td>
                <td align="center">
                  {!! Form::open(['url' => route('department_class_program.destroy', $data->Department_Class_Prog_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                  @if(in_array('department_class_program-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Department_Class_Prog_Id]) !!}@endif
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
                  url: "{{ url('') }}/master/department_class_program/" + id,
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

<!-- /.row -->

</section>
<!-- <script type="text/javascript">

$('[data-onload]').each(function(){
    eval($(this).data('onload'));
});

function handleChange(checkbox) {
    if(checkbox.checked == true){
      list = document.getElementsByClassName("fakultas");
      for (index = 0; index < list.length; ++index) {
        list[index].setAttribute("disabled","disabled");
        list[index].checked = false;
      }
    }else {
      list = document.getElementsByClassName("fakultas");
      for (index = 0; index < list.length; ++index) {
        list[index].removeAttribute("disabled");
      }
    }
}
  function Change(checkbox) {
      var id = $(checkbox).val();
      if(checkbox.checked == true){
        list = document.getElementsByClassName("prodi"+id);
        for (index = 0; index < list.length; ++index) {
          // list[index].setAttribute("disabled","disabled");
          list[index].checked = true;
          // list[index].setAttribute("checked","checked");
        }
      }else {
        list = document.getElementsByClassName("prodi"+id);
        for (index = 0; index < list.length; ++index) {
          // list[index].removeAttribute("disabled");
          list[index].checked = false;
          // list[index].removeAttribute("checked");
        }
      }
  }
  function ubah(id) {
      if($('.prodi'+id+':checked').length == $('.prodi'+id+'').length){
        list = document.getElementsByClassName("fac"+id);
        for (index = 0; index < list.length; ++index) {
          list[index].checked = true;
        }
      }else {
        list = document.getElementsByClassName("fac"+id);
        for (index = 0; index < list.length; ++index) {
          list[index].checked = false;
        }
      }
  }
</script> -->
<?php
}
?>
@endsection
