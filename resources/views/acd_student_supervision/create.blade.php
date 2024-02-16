@extends('layouts._layout')
@section('pageTitle', 'Student Supervision')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Bimbingan DPA</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if($department_id != null && $employee_id != 0)
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ url('setting/student_supervision?department='.$department_id.'&page='.$current_page.'&rowpage='.$current_rowpage.'&search='.$current_search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ url('setting/student_supervision/create/dpa/?department_id='.$department_id.'&employee_id='.$employee_id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&current_page='.$current_page.'&current_rowpage='.$current_rowpage.'&current_search='.$current_search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>&nbsp
          </div>
          @else
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('student_supervision-CanAdd', $acc)) <a href="{{ url('setting/student_supervision?department='.$department_id.'&page='.$current_page.'&rowpage='.$current_rowpage.'&search='.$current_search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>@endif
          </div>
          @endif
          <b>Create</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('student_supervision.create') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <input type="hidden" name="current_search" value="{{ $current_search }}">
            <input type="hidden" name="current_page" value="{{ $current_page }}">
            <input type="hidden" name="current_rowpage" value="{{ $current_rowpage }}">
            <input type="hidden" name="department_id" value="{{ $department_id }}">

            <label class="col-md-1">Dosen :</label>
            <select class="form-control form-control-sm col-md-4" name="employee_id" id="pilih">
              <option value="0">Pilih Dosen</option>
              @foreach ( $emp_employee as $data )
                <option <?php if($employee_id == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->First_Title }} {{ $data->Name }} {{ $data->Last_Title }}</option>
              @endforeach
            </select>
            <script type="text/javascript">
                $(document).ready(function () {
                  $("#pilih").change(function () {
                    var url = {!! json_encode(url('/')) !!};
                    var id = $("#pilih").val();
                    var department_id = <?php echo $department_id; ?>;
                    window.location = url+"/setting/student_supervision/create?department_id="+department_id+"&employee_id="+id+"&current_page=<?php echo $current_page; ?>&current_rowpage=<?php echo $current_rowpage; ?>&current_search=<?php echo $current_search; ?>";
                  });
                });
            </script>
          </div>
          <br>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <label class="col-md-1">Pencarian:</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-4" value="{{ $search }}" placeholder="Search">&nbsp
            <label class="col-md-2" style="text-align:right;">Baris Per halaman :</label>
            <input type="number" name="rowpage"  class="form-control form-control-sm col-md-4" value="{{ $rowpage }}" placeholder="Baris Per halaman">&nbsp
            <input type="submit" name="" style="float:right;" class="btn btn-primary btn-sm" value="Search"></input>
          </div><br>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Menambah Bimbingan DPA")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm" >
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="25%">NIM</th>
                  <th width="60%">Nama Mahasiswa</th>
                  @if(in_array('student_supervision-CanDelete', $acc))
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
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
                  <td style="padding-left:1%;">{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
                  @if(in_array('student_supervision-CanDelete', $acc))
                  <td>
                    {!! Form::open(['url' => route('student_supervision.destroy', $data->Student_Supervision_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                    {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Student_Supervision_Id]) !!}
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
                  url: "{{ url('') }}/setting/student_supervision/" + id,
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
