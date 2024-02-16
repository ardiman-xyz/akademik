@extends('layouts._layout')
@section('pageTitle', 'Yudisium')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Yudisium</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/yudisium?department='.$department.'&term_year='.$term_year) }}" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          {!! Form::open(['url' => route('yudisium.dataupdate',$data_edit->Yudisium_Id) , 'method' => 'GET', 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          {{ csrf_field() }}
          {{ method_field('post') }}
          <input type="hidden" name="term_year"  value="{{$term_year}}"  class="form-control term_year2">
          <input type="hidden" name="total_sks"  value=""  class="form-control total_sks">
          <input type="hidden" name="std_id"  value=""  class="form-control total_sks std_id">

               <div class="form-group">
              {!! Form::label('', 'Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <input type="text" name="mahasiswa" value="{{$data_edit->Full_Name}}" readonly class="form-control form-control-sm">
              </div>
            </div>

          <div class="form-group">
            {!! Form::label('', 'No. Ijazah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="no_ijazah" value="{{$data_edit->National_Certificate_Number}}"  class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'No. Transkrip', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="no_transkrip"  value="{{$data_edit->Transcript_Num}}"  class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'No. SKPI', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="no_skpi"  value="{{$data_edit->Skpi_Number}}"  class="form-control form-control-sm">
            </div>
          </div>
          
          <div class="form-group">
            {!! Form::label('', 'Tgl Kelulusan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="datetime-local" name="tgl_kelulusan" value="{{ str_replace(' ', 'T', $data_edit->Graduate_Date) }}" class="form-control form-control-sm">
            </div>
          </div>
          
          <div class="form-group">
            {!! Form::label('', 'Tgl Transcript', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="datetime-local" name="Transcript_Date" value="{{ str_replace(' ', 'T', $data_edit->Transcript_Date) }}" class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Predikat Lulus', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-12 name" name="predikat_lulus" >
                <option value="">Pilih Predikat Lulus</option>
                @foreach($graduate_predicate as $data)
                <option <?php if($data_edit->Graduate_Predicate_Id == $data->Graduate_Predicate_Id){ echo "selected"; } ?> value="{{ $data->Graduate_Predicate_Id }}">{{$data->Predicate_Name}}</option>
                @endforeach
              </select>
            </div>
          </div>


          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<script type="text/javascript">
	$(document).ready(function(){

		$("#select").on('change',function () {
			var student_id=$(this).val();

			var a=$(this).parent();
			var op="";
      var url = {!! json_encode(url('/')) !!};
			$.ajax({
				type:'get',
				url: url + '/proses/yudisium/finddata/finddata',
				data:{'Student_Id':student_id},
				dataType:'json',//return data will be json
				success:function(data){
            console.log(data);
           $(".judul").val(data.Thesis_Title);
           console.log(data.Thesis_Title_Eng);
           $(".juduleng").val(data.Thesis_Title_Eng);
           $(".dp1").val(data.pem1);
           $(".dp2").val(data.pem2);
           $(".tgl_seminar").val(data.Seminar_Date);
           $(".tgl_pendadaran").val(data.Thesis_Exam_Date);
           $(".term_year").val(data.Term_Year_Id);
           $(".total_sks").val(data.jml_sks);
           $(".std_id").val(data.Student_Id);
           $(".ipk").val(data.ipk);

				},
				error:function(){

				}
			});
		});
	});
</script>



@endsection
