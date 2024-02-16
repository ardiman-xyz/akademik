@extends('layouts._layout')
@section('pageTitle', 'Cetak Presensi Mahasiswa')
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
      <h3 class="text-white">Presensi Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">

            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>

          <b>Index</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('presensimhs.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->&nbsp &nbsp
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-4" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
            <!-- <label class="col-md-2">Tahun Semester :</label> -->
            <select class="form-control form-control-sm col-md-4" name="class_program"  onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>
          </div>
          <br>
          {!! Form::close() !!}
          <div class="row text-green">
            <label class="col-md-2">Pencarian:</label>
            <input type="text" name="search" id="search"  class="form-control form-control-sm col-md-3" value="{{ $search }}" placeholder="Search">&nbsp
            <input type="button" id="mySearchButton" name="" class="btn btn-primary btn-sm" value="Cari">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
            <label class="col-md-2">Baris Per halaman :</label>
            <select class="form-control form-control-sm col-md-1" name="rowpage" onchange="document.form.submit();" >
              <option <?php if($rowpage == "10"){ echo "selected"; } ?> value="">10 Baris</option>
              <option <?php if($rowpage == "20"){ echo "selected"; } ?> value="20">20 Baris</option>
              <option <?php if($rowpage == "50"){ echo "selected"; } ?> value="50">50 Baris</option>
              <option <?php if($rowpage == "100"){ echo "selected"; } ?> value="100">100 Baris</option>
              <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
            </select>
          </div><br>
          
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="table-responsive">
        <table id="example" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="15%">Kode MK</th>
                  <th width="25%">Nama Matakuliah</th>
                  <th width="5%">Kelas</th>
                  <th width="10%">Hari</th>
                  <th width="10%">Jam</th>
                  <th width="10%">Peserta</th>
                  <th width="25%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data['Kode Matakuliah'] }}</td>
                  <td>{{ $data['Nama Matakuliah'] }}</td>
                  <td>{{ $data['Kelas'] }}</td>
                  <td>{{ $data['Hari'] }}</td>
                  <td>{{ $data['Jam']}}</td>
                  <td <?php if($data['Jumlah Peserta'] == 0){ echo "style='color:#f78383;'"; } ?> >{{ $data['Jumlah Peserta'] }}</td>

                  <td>
                    @if(in_array('presensimhs-CanExport', $acc))
                      @if($data['Jumlah Peserta'] != 0)
                      <a data-params="{{ $data['Offered_Course_id'] }}"
                            data-classprog="{{ $class_program }}" 
                            data-department="{{ $department }}"
                            data-termyear="{{ $term_year }}"
                            class="btn btn-success btn-sm" 
                            id="ctk_presensi"  
                            href="javascript:">Cetak Presensi</a>
                      <a href="{{ url('cetak/presensimhs/'.$data['Offered_Course_id'].'/exportttd'.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">TTD Presensi <i class="fa fa-print"></i> </a>
                      @else
                      <button disabled class="btn btn-info btn-sm" style="margin:5px;" >Presensi <i class="fa fa-print"></i> </button>
                      @endif
                    @endif
                  </td>
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>
        <?php 
        // echo $query->render('vendor.pagination.bootstrap-4'); 
        ?>
      </div>
    </div>
  </div>

    <div id="ubahpembayaran" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Cetak Presensi</h4>
      </header>
      <div class="w3-container">
      </br>
      <!-- <form id="frm-upload" action="{{ route('course_curriculum.store_silabus') }}" method="POST" enctype="multipart/form-data"> -->
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-10">
              <label class="col-md-4">Pertemuan</label>
              <input type="number" min="1" name="kolom" id="kolom"  value="" class="form-control form-control-sm col-md-7">              
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <label class="col-md-7"><center>Sampai</label>             
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <input type="number" min="1" name="kolom2" id="kolom2"  value="" class="form-control form-control-sm col-md-7">              
            </div>
            <div  class="row col-md-10">
              <input type="text" name="ocid"  hidden >
              <input type="text" name="cp"  hidden>
              <input type="text" name="d" hidden>
              <input type="text" name="ty" hidden>
            </div>

            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <button class="btn-success btn-sm form-control form-control-sm col-md-7" id="btncetak" name="btncetak">Presensi</button>
            </div>
            <!-- </form> -->
            </div>
          </br>

      </div>
      </div>
  </div>
<style>
  @media (min-width:993px){.w3-modal-content{width:50%}.w3-hide-large{display:none!important}.w3-sidebar.w3-collapse{display:block!important}}
</style>
  <script type="text/javascript">
  $(document).ready(function () {
    var table = $('#example').DataTable({
    paging : false,
    orderCellsTop: true
    // "dom": '<"clear">'
  });

  $('#mySearchButton').on( 'keyup click', function () {
    table.search($('#search').val()).draw();
  } );
  });
    $(document).on('click', '#ctk_presensi', function (e) {
    document.getElementById("ubahpembayaran").style.display = "block";
    var oci = $(this).data('params'),
        classprog = $(this).data('classprog'),
        department = $(this).data('department'),
        termyear = $(this).data('termyear');
        // console.log($(this).data);

        $("[name='ocid']").val(oci);
        $("[name='cp']").val(classprog);
        $("[name='d']").val(department);
        $("[name='ty']").val(termyear);
    });

    $("#btncetak").click(function(e){
      var oci =  $("[name='ocid']").val();
      var classprog =  $("[name='cp']").val();
      var department =  $("[name='d']").val();
      var termyear =  $("[name='ty']").val();
      var kolom = $('#kolom').val();
      var kolom2 = $('#kolom2').val();
      // console.log(classprog);
      if (kolom == "" || kolom2=="") {
          swal('Perhatian', "Kolom Belum DIisi", 'warning');
      } else {
        window.open("{{ url('cetak/presensimhs/')}}/"+oci+"/export?class_program="+classprog+"&department="+department+"&term_year="+termyear+"&page=&rowpage=10&search=&kolom="+kolom+"&kolom2="+kolom2,"_blank")
      }
  });
  </script> 
</section>
@endsection
