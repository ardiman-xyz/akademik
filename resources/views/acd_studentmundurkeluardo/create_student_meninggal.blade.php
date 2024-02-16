@extends('layouts._layout')
@section('pageTitle', 'mengundurkan diri')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="panel-heading tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            <div class="pull-right tombol-gandeng dua">
              <a href="{{ url('setting/studentmundurkeluardo?department='.$department_id.'&entry_year='.$entry_year_id.'&status='.$status.'&page='.$page.'&rowpage='.$rowpage.'&current_page='.$current_page.'&current_rowpage='.$current_rowpage.'&current_search='.$current_search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
            </div>
            <b>Meninggal</b>
          </div>
        </div>
      </div>
      <br>
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Menambah Data")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        <br>
        {!! Form::open(['url' => route('studentmundurkeluardo.store_student_meninggal') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}

        <input type="hidden" name="search" value="{{ $search }}">

            <input type="hidden" name="department" value="{{ $department_id }}">
            <input type="hidden" name="entry_year" value="{{ $entry_year_id }}">
            <input type="hidden" name="status" value="{{ $status }}">            

          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Nama Mahasiswa :</label>
              <select id="select"  class="form-control form-control-sm col-md-7" name="nim" >
                <option value="0">Pilih Nim/Nama</option>
                  @foreach($select_nim as $ni)
                  <option <?php if($nim == $ni->Nim){ echo "selected"; } ?> value="{{ $ni->Student_Id }}">{{$ni->Nim}}  {{ $ni->Full_Name }}</option>
                  @endforeach
              </select>
              <script type="text/javascript">
                var select = new SlimSelect({
                select: '#select'
                })
                select.selected()
              </script>
            </div>
          </div><br>

          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Tahun / Semester :</label>
              <select id="select"  class="form-control form-control-sm col-md-7" name="term_year" >
                <option value="0">Pilih Tahun Semester</option>
                  @foreach($select_term_year as $res)
                  <option value="{{ $res->Term_Year_Id }}">{{ $res->Term_Year_Name }}</option>
                  @endforeach
              </select>
            </div>
          </div><br>

          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Alasan :</label>
              <select id="select"  class="form-control form-control-sm col-md-7" name="reason" >
                <option value="0">Pilih Alasan</option>
                  @foreach($reason as $res)
                  <option value="{{ $res->Out_Reason_Id }}">{{ $res->Description }}</option>
                  @endforeach
              </select>
            </div>
          </div><br>
          
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Ketik Alasan :</label>
              <input type='text' name='reason_text' class='form-control form-control-sm col-md-7'/>
            </div>
          </div><br>
          

          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Tanggal Keluar :</label>
               <?php
                $date = strtotime(old('date'));
                $date_out = date('Y-m-d', $date);
              ?>
              <input type="date" name="date" value="{{ old('$date_out') }}" class="form-control form-control-sm col-md-7">
            </div>
          </div><br>
        
        <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
        {!! Form::close() !!}
        <?php
        // echo $query->render('vendor.pagination.bootstrap-4');
        ?>
      </div>
    </div>
  </div>
<script type="text/javascript">
function Change(checkbox) {
    var id = $(checkbox).val();
    if(checkbox.checked == true){
      list = document.getElementsByClassName("checkbox"+id);
      for (index = 0; index < list.length; ++index) {
        // list[index].setAttribute("disabled","disabled");
        list[index].checked = true;
        // list[index].setAttribute("checked","checked");
      }
    }else {
      list = document.getElementsByClassName("checkbox"+id);
      for (index = 0; index < list.length; ++index) {
        // list[index].removeAttribute("disabled");
        list[index].checked = false;
        // list[index].removeAttribute("checked");
      }
    }
}
</script>
</section>
@endsection
