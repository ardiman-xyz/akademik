@extends('layouts._layout')
@section('pageTitle', 'Curriculum Entry Year')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<style>
.w3-modal-content {
    width: 50%;
}
</style>
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Kurikulum Angkatan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($term_year != null && $term_year != 0 && $department != null && $department != 0)
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            @if(in_array('curriculum_entry_year-CanAdd', $acc)) <a href="{{ url('parameter/curriculum_entry_year/create?term_year='.$term_year.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
            @if(in_array('curriculum_entry_year-CanAdd', $acc)) <a href="#" class="btn btn-warning btn-sm" id="copy_data">Kopi Data &nbsp;<i class="fa fa-copy"></i></a>@endif
          </div>
          @endif
          <b>Kurikulum Angkatan</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('curriculum_entry_year.index',$term_year) , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-7">
            <label class="col-md-3">Tahun Semester :</label>
            <select class="form-control form-control-sm col-md-9" name="term_year"  onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
            <div class="col-md-2">
            </div>
            </div>
            <div class="row col-md-5">
            <label class="col-md-5" >Program Studi :</label>
            <select class="form-control form-control-sm col-md-7" name="department" onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?>  value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
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
                  <th>No</th>
                  <th>Angkatan</th>
                  <?php
                  foreach ($class_prog as $classprog) {
                  ?>
                    <td><center>{{ $classprog->Class_Program_Name }}</td>
                  <?php
                  }
                  ?>

                  <!-- <th width="10%"><center><i class="fa fa-gear"></i></center></th> -->
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach($query as $filter_result){

              ?>
              <tr>
                  <!-- <th></th> -->
                  <td><center>{{ $a }}</td>
                  <td><center>{{ $filter_result->Entry_Year_Name }}</td>
                  <?php
                  foreach ($class_prog as $classprog) {
                  ?>
                    <td><center>
                      <?php
                      $kur = DB::table('acd_curriculum_entry_year')->join('mstr_curriculum','mstr_curriculum.Curriculum_Id','=','acd_curriculum_entry_year.Curriculum_Id')->where('Term_Year_Id', $filter_result->Term_Year_Id)->where('Department_Id', $filter_result->Department_Id)->where('Entry_Year_Id', $filter_result->Entry_Year_Id)->where('Class_Prog_Id', $classprog->Class_Prog_Id)->get();
                      foreach ($kur as $k) {
                        ?>
                        @if(in_array('curriculum_entry_year-CanEdit', $acc))
                        <div><center>
                          <label>{{ $k->Curriculum_Name }}</label> <br>
                          <!-- <a href="{{ url('parameter/curriculum_entry_year/'.$k->Curriculum_Entry_Year_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" style="text-decoration:none;">{{ $k->Curriculum_Name }} <i class="fa fa-edit"></i></a> <br> -->
                        </center></div>
                        @else
                        <div><center>
                          {{ $k->Curriculum_Name }} <br>
                        </center></div>
                        @endif
                        {{-- <div class="row col-md-12"> --}}
                            <div class="col-md-4">
                            </div>
                            @if(in_array('curriculum_entry_year-CanDelete', $acc))
                            {!! Form::open(['url' => route('curriculum_entry_year.destroy', $k->Curriculum_Entry_Year_Id)  , 'method' => 'DELETE', 'role' => 'form', 'class'=>'hapus col-md-1', 'data-id'=>$k->Curriculum_Entry_Year_Id]) !!}
                            <button style="background:none!important; color:inherit; border:none; padding:0!important; font: inherit; cursor: pointer;"><i class="fa fa-close" style="cursor:pointer; color:red;"></i></button>
                            {!! Form::close() !!}
                            @endif
                            <div class="col-md-6">
                            </div>
                          {{-- </div> --}}
                        </div>
                        <?php
                      }
                      ?>
                    </center>
                    </td>
                  <?php
                  }
                  ?>
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
  <?php
  ?>
  <div id="copy_data_form" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Kopi Data</h4>
      </header>
      <div class="w3-container">
      </br>
      <form id="frm-upload" action="{{ route('course_curriculum.store_silabus') }}" method="POST" enctype="multipart/form-data">
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-10">
              <label class="col-md-4">Prodi</label>
              <input type="text" name="prodi_asal" id="prodi_asal"  value="@if($get_department){{$get_department->Department_Name}}@endif" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4">Semester</label>
              <input type="text" name="username" id="username"  value="@if($get_termyear){{$get_termyear->Term_Year_Name}}@endif" readonly class="form-control form-control-sm col-md-7">              
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4">Semester Tujuan</label>   
              <select class="form-control form-control-sm col-md-7" id="term_year_destination"  >
                <option value="0">Pilih Tahun Semester</option>
                @foreach ( $select_term_year as $data )
                  <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                @endforeach
              </select>         
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>  
              <button onclick="copy_data_btn()" type="button" class="btn-success btn-sm form-control form-control-sm col-md-7"name="button"  >Login</button>    
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              </div>
            </form>
            </div>
          </br>
      </div>
      </div>
      <table id="eaea">
      </table>
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
                  url: "{{ url('') }}/parameter/curriculum_entry_year/" + id,
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

  $(document).on('click', '#copy_data', function (e) {
      document.getElementById("copy_data_form").style.display = "block";
  });

  function copy_data_btn() {
    $.ajax({
      dataType: 'json',
      url: "{{ route('curriculum_entry_year.copy_data') }}",
      type: 'get',
      data: {
        prodi:"{{$department}}",
        term_year:"{{$term_year}}",
        term_year_destination:$("#term_year_destination").val(),
      },
      success: function(res) {
        console.log(res);
        if (res.success == false) {
          swal('Sorry',res.message,'warning');
        }else{
          document.getElementById("copy_data_form").style.display = "none";
          swal({
              //title: thrownError,
              title: "Sukses",
              text: res.message,
              type: "success",
              confirmButtonColor: "#02991a",
              confirmButtonText: "Ok",
              cancelButtonText: "Tidak, Batalkan!",
              closeOnConfirm: false,
          },
          function(isConfirm) {
              if (isConfirm) {
                  location.reload();
              }
          });
        }
      }, error: function(xhr, ajaxOptions, thrownError) {
          swal({
                //title: thrownError,
                title: "Mohon Maaf",
                text: 'Error!! ' + xhr.status,
                type: "error",
                confirmButtonColor: "#02991a",
                confirmButtonText: "Refresh Sekarang",
                cancelButtonText: "Tidak, Batalkan!",
                closeOnConfirm: false,
            },
            function(isConfirm) {
                if (isConfirm) {
                    location.reload();
                }
            });
          }

  })
    document.getElementById("mahasiswa").style.display = "none";
    // $("#fom")[0].reset();
  }


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
