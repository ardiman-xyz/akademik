@extends('layouts._layout')
@section('pageTitle', 'Department')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

  <section class="content">

    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">SKS awal Semester</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="bootstrap-admin-box-title right text-white">
            @if($fakultas != null && $fakultas != 0)
              <div class="pull-right tombol-gandeng dua">
                <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
              </div>
              @endif
              <b>&nbsp;</b>
            </div>
          </div>
          <br>
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
                  <th width="10%">Kode Prodi</th>
                  <th width="15%">Nama Prodi</th>
                  <th width="15%">SKS Awal Semester</th>
                  <th width="13%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
              </thead>
              <tbody>
                <?php
                $a = "1";
                foreach ($department as $data) {
                  // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
                  // $prod = auth()->user()->Prodi();
                  // if (count($prod)==0) {
                  ?>
                  <tr>
                    <td><center>{{ $data->Department_Code }}</center></td>
                    <td>{{ $data->Department_Name }}</td>
                    <td>
                      @php
                      $sks = DB::table('acd_allowed_sks_start')
                      ->join('mstr_class_program','acd_allowed_sks_start.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
                      ->where([['Department_Id',$data->Department_Id]])
                      ->get();
                      if(count($sks) > 0){
                        $var = '';
                        foreach($sks as $key){
                          $var = $var.($var == '' ? '':' | ').$key->Class_Program_Name.' '.$key->Sks_Max;
                        }
                      }else{
                        $var = '';
                      }
                      @endphp
                      {{$var}}
                    </td>
                    <td align="center">
                  @if(in_array('first_sks-CanEdit', $acc))<a href="{{ url('setting/first_sks/'.$data->Department_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                    </td>
                  </tr>
                      <?php
                      $a++;
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php echo $department->render('vendor.pagination.bootstrap-4'); ?>
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
                  url: "{{ url('') }}/setting/department/" + id,
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
