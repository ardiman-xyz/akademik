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
      <h3 class="text-white">KRS Per Matakuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('krs_matakuliah-CanAdd', $acc))<a href="{{ url('proses/krs_matakuliah/create/?id='.$Offered_Course_id.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_search='.$currentsearch.'&current_page='.$currentpage.'&current_rowpage='.$currentrowpage ) }}" class="btn btn-success btn-sm">Tambah Peserta &nbsp;<i class="fa fa-plus"></i></a>@endif

            <a href="{{ url('proses/krs_matakuliah?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&current_search='.$currentsearch.'&current_page='.$currentpage.'&current_rowpage='.$currentrowpage) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <b>Index</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('krs_matakuliah.show',$Offered_Course_id) , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="">
            <div class="pull-left tombol-gandeng dua">
              <!-- @if(in_array('krs_matakuliah-CanExport', $acc))
              @if($data != null)
                @if($data->jml_peserta != 0)
                  <a href="{{ url('proses/krs_matakuliah/'.$Offered_Course_id.'/export'.'?type=FormNilai&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm" target="_blank">Form Nilai <i class="fa fa-print"></i></a>
                  <a href="{{ url('proses/krs_matakuliah/'.$Offered_Course_id.'/export'.'?type=Presensi&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm" target="_blank">Presensi <i class="fa fa-print"></i></a>
                @else
                  <button disabled class="btn btn-info btn-sm" style="margin:5px;" >Form Nilai <i class="fa fa-print"></i> </button>
                  <button disabled class="btn btn-info btn-sm" style="margin:5px;" >Presensi <i class="fa fa-print"></i></button>
                @endif
                @endif
              @endif -->

            </div>&nbsp
          </div>
          <br>
          <div class="row">
            <label class="col-md-1">Pencarian:</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-4" value="{{ $search }}" placeholder="Search">&nbsp
            <label class="col-md-2" style="text-align:right;">Baris Per halaman :</label>
            <input type="number" name="rowpage"  class="form-control form-control-sm col-md-4" value="{{ $rowpage }}" placeholder="Baris Per halaman">&nbsp
            <input type="submit" name="" style="float:right;" class="btn btn-primary btn-sm" value="Submit">
          </div><hr>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <br>
        <div class="row">
          <div class="col-sm-6">
            <div >
              <label  class="col-sm-5">Th Akademik/Semester</label>:
              <label  class="col-sm-5"> {{ $data->Term_Year_Name }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Program Studi</label>:
              <label  class="col-sm-5"> {{ $data->Department_Name }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Kelas Program</label>:
              <label  class="col-sm-5"> {{ $data->Class_Program_Name }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Matakuliah</label>:
              <label  class="col-sm-5"> {{ $data->Course_Name }}</label>
            </div>

          </div>
          <div class="col-sm-6">
            <div>
              <label  class="col-sm-5">Kelas</label>:
              <label  class="col-sm-5"> {{ $data->Class_Name }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Kapasitas</label>:
              <label  class="col-sm-5"> {{ $data->Class_Capacity }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Terdaftar</label>:
              <label  class="col-sm-5"> {{ $data->jml_peserta }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Ter ACC</label>:
              <label  class="col-sm-5">{{$krsacc}}</label>
            </div>
            <div>
              <label  class="col-sm-5">Sisa</label>:
              <label  class="col-sm-5"> {{ $data->Class_Capacity - $data->jml_peserta }}</label>
            </div>
          </div>
        </div>

        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
        <div class="table-responsive">
        <table id="tbl" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="40%">NIM</th>
                  <th width="45%">Nama Mahasiswa</th>
                  <th width="45%">Approved KRS</th>
                  @if(in_array('krs_matakuliah-CanDelete', $acc))
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
              $tagihan = DB::select('CALL usp_GetStudentBill_For_KRS(?,?,?)',[$data->Register_Number,'','']);
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
									<td><?php if($data->Is_Approved === null ){ echo 'Belum diproses'; }elseif ($data->Is_Approved == 1) {echo 'Acc';}else{ echo 'Ditolak';} ?></td>
                  {{-- @if(in_array('krs_matakuliah-CanDelete', $acc) && count($tagihan) > 0 ) --}}
                  @if(in_array('krs_matakuliah-CanDelete', $acc))
                  <td>
                      {!! Form::open(['url' => route('krs_matakuliah.destroy', $data->Krs_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Krs_Id]) !!}
                      {!! Form::close() !!}
                  </td>
                  @else
                  <td></td>
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
                url: "{{ url('') }}/proses/krs_matakuliah/" + id,
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
