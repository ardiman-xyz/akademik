@extends('layouts._layout')
@section('pageTitle', 'Tugas Akhir')
@section('content')
<?php
  foreach ($data as $data_edit) {
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Tugas Akhir</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/tugas_akhir?department='.$department.'&term_year='.$term_year) }}" class="btn btn-danger btn-sm" >Kembali &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

        @if(Session::get('success') == true)
            @if (count($errors) > 0)
              @foreach ( $errors->all() as $error )
                <p class="alert alert-success">{{ $error }}</p>
              @endforeach
            @endif
        @else
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
        @endif
          {!! Form::open(['url' => route('tugas_akhir.update',$data_edit->Thesis_Id) , 'method' => 'put', 'class' => 'form-horizontal', 'class' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          {{ csrf_field() }}
          {{ method_field('put') }}
          <input type="hidden" name="Entry_Year_Id"  value=""  class="form-control">

            <div class="form-group">
              {!! Form::label('', 'Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select id="select" readonly class="form-control form-control-sm col-md-12" name="mahasiwa">
                  @foreach($select_mahasiswa as $data)
                  <option <?php if($data_edit->Student_Id == $data->Student_Id){ echo "selected"; } ?> value="{{ $data->Student_Id }}">{{$data->Nim}} | {{ $data->Full_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12">
                <select class="form-control form-control-sm col-md-12" id="smt_now" hidden>
                  <option value="0">Pilih Th/Smt Mulai...</option>
                  @foreach($select_term_year as $data)
                  <option <?php if($data_edit->Term_Year_Id == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                  @endforeach
                </select>
              </div>

            <div class="form-group">
              {!! Form::label('', 'Th/Smt Mulai', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select class="form-control form-control-sm col-md-12" name="smt_mulai">
                  <option value="0">Pilih Th/Smt Mulai...</option>
                  @foreach($select_term_year as $data)
                  <option <?php if($data_edit->Term_Year_Id_Start == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('', 'Th/Smt Selesai', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select class="form-control form-control-sm col-md-12" name="smt_selesai">
                  <option value="0">Pilih Th/Smt Selesai...</option>
                  @foreach($select_term_year as $data)
                  <option <?php if($data_edit->Term_Year_Id_Complete == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
            {{-- <input type="text" readonly hidden name="Course_Id" value="{{ $Krs_Id->Course_Id }}"  class="form-control form-control-sm"> --}}

            <div class="form-group">
              <div class="col-md-12">
                <select id="select2" hidden class="form-control form-control-sm col-md-12" name="matakuliah" >
                  <option value="">Pilih Mata Kuliah...</option>
                  @foreach ($matakuliah as $data)
                    <option <?php if($data_edit->Course_Id == $data->Course_Id){ echo "selected"; } ?> value="{{ $data->Course_Id }}">{{ $data->Course_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

          <div class="form-group">
            {!! Form::label('', 'Tanggal Permohonan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Application_Date);
                $tgl_ = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_permohonan" value="{{ $tgl_ }}" class="form-control form-control-sm">
            </div>
          </div>

          <!-- <div class="form-group">
            {!! Form::label('', 'Sks_Trough', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="sks_trough" value="{{ $data_edit->Sks_Trough }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Bnk', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="bnk" value="{{ $data_edit->Bnk }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Proposal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="is_proposal">
                <option <?php if($data_edit->Is_Proposal==1){ echo "selected"; } ?> value="1">Ya</option>
                <option <?php if($data_edit->Is_Proposal==0){ echo "selected"; } ?> value="0">Tidak</option>
              </select>
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Judul Proposal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul_proposal" value="{{ $data_edit->Proposal_Title }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Proposal Masuk', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Proposal_Date_Msk);
                $tgl_propmsk = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_proposalmsk" value="{{ $tgl_propmsk }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Proposal Diterima', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Proposal_Date_Acc);
                $tgl_proptrm = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_proposaltrm" value="{{ $tgl_proptrm }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Sk Proposal Acc', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="skproposalacc" value="{{ $data_edit->Sk_Proposal_Acc }}"  class="form-control form-control-sm">
            </div> -->
          </div>

          <div class="form-group">
            {!! Form::label('', 'Judul', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul" value="{{ $data_edit->Thesis_Title }}"  class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Judul Eng', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul_eng" value="{{ $data_edit->Thesis_Title_Eng }}"  class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 1', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="pembimbing1">
                <option value="0">Pilih Pembimbing 1...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Supervisor_1 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 2', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="pembimbing2">
                <option value="0">Pilih Pembimbing 2...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Supervisor_2 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 3', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-12" name="pembimbing3">
                <option value="0">Pilih Pembimbing 3...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Supervisor_3 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Total Ujian', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="total_ujian" value="{{ $data_edit->Total_Thesis_Exam }}"  class="form-control form-control-sm">
            </div>
          </div>

          <input type="text" hidden name="thesis_id" id='thesis_id' value="{{$id}}">
          <div class="form-group">
            {!! Form::label('', 'Penguji', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="examiner" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="penguji">
                <option value="0">Pilih Penguji ...</option>
                @foreach($dosen as $data)
                <option value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Penguji Ke-', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="examiner_number" id='examiner_number'>
            </div>
          </div>
          <div class="table-responsive col-md-10">
            <table class="table table-striped table-font-sm">
              <thead class="thead-default thead-green">
                  <tr>
                      <!-- <th class="col-sm-1">No</th> -->
                      <th width="7%">Penguji ke-</th>
                      <th width="7%">Nama Dosen</th>
                      <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                  </tr>
              </thead>
              <tbody>
                @foreach($penguji_in_thesis as $key)
                <tr>
                  <td><center>{{$key->Order_Id}}</td>
                  <td><center>{{$key->Full_Name}}</td>
                  <td>    
                      <center>
                      {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$key->Thesis_Examiner_Id]) !!}
                      </center>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <script type="text/javascript">
            var examiner = new SlimSelect({
            select: '#examiner'
            })
            examiner.selected()
          </script>

          <input type="text" name="sks_trough" value="{{ $data_edit->Sks_Trough }}" hidden class="form-control form-control-sm">
          
          <input type="text" hidden name="sks_krs" value=""  class="form-control form-control-sm sks_krs">

          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<?php
  }
  $angkatan = DB::table('acd_student')->where('Student_Id',$data_edit->Student_Id)->first();
?>

<script>
  var student_id=<?php echo $data_edit->Student_Id; ?>;
  var department = <?php echo $data_edit->Department_Id; ?>;
  var term_year = $("#smt_now" ).val();
  var angkatan = <?php echo $angkatan->Entry_Year_Id; ?>;
  var url = {!! json_encode(url('/')) !!};
  $.ajax({
    type:'get',
    url: url + '/proses/tugas_akhir/finddata/finddata',
    data:{'Student_Id':student_id,'department':department,'term_year':term_year,'angkatan':angkatan},
    dataType:'json',//return data will be json
    success:function(data){
        console.log(data);
        if(data.total == 0){
          swal({
              title: data.std.Nim,
              text: 'Belum Menggambil Matakuliah Skripsi/Thesis/Tugas akhir',
              type: "warning",
              confirmButtonColor: "#02991a",
              // confirmButtonText: "Refresh Serkarang",
              cancelButtonText: "Batalkan",
              closeOnConfirm: false,
          },
          function(isConfirm) {
              if (isConfirm) {
              window.location.reload(true) // submitting the form when user press yes
              }
          });
        }
        $(".sks_krs").val(data.data.Applied_Sks);
    },
    error:function(){

    }
  });

  $(document).on('click', '.hapus', function (e) {
      e.preventDefault();
      var id = $(this).data('id');
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
                  url: "{{ url('') }}/proses/tugas_akhir/dosen_penguji/delete/"+id ,
                  type: "DELETE",
                  dataType: "json",
                  data: {
                    "_token": "{{ csrf_token() }}"
                  },
                  success: function (data) {
                    if(data.success == true){
                      swal({
                        title: 'Data telah dihapus',
                        type: 'success', showConfirmButton:true,
                      });
                      window.location.reload();
                    }
                  },
                  error: function(){
                  }
              });
              // $("#hapus").submit();
            }
          });
  });
</script>
@endsection
