@extends('layouts._layout')
@section('pageTitle', 'KRS')
@section('content')

  <?php
  $access = auth()->user()->akses();
  $acc = $access;
  ?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KRS Per Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($term_year != null && $term_year != 0 && $nim != null && $nim != 0)
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>

               {{-- @if(in_array('krs_mahasiswa-CanAdd', $acc))<a href="{{ url('proses/krs_mahasiswa/create?nim='.$nim.'&term_year='.$term_year ) }}" class="btn btn-success btn-sm">Tambah Matakuliah &nbsp;<i class="fa fa-plus"></i></a>@endif --}}

              <?php
                  // $mhs = DB::table('acd_student')->where('Nim',$_GET['nim'])->first();
                  // $tagihan = DB::select('CALL usp_GetStudentBill_For_KRS(?,?,?)',[$mhs->Register_Number,'','']);
                  // $cekkrsall = DB::table('acd_student_krs')->where([['Student_Id',$mhs->Student_Id],['Term_Year_Id',$_GET['term_year']]])->get(); //all
                  // $cekkrs = DB::table('acd_student_krs')->where([['Student_Id',$mhs->Student_Id],['Term_Year_Id',$_GET['term_year']],['Is_Approved',1]])->get(); //acc
                  // $masuk_semester = DB::table('mstr_term_year')
                  // ->whereBetween('Term_Year_Id', [$mhs->Entry_Year_Id.$mhs->Entry_Term_Id, $_GET['term_year']])
                  // ->get();
                  // $is_krs = true;
                  // if(count($masuk_semester) != 1 || count($masuk_semester) != 2){
                  //   if(count($cekkrsall) == 0 && count($tagihan) <= 0){
                  //     $is_krs = true;
                  //   }elseif(count($cekkrsall) > 0 && count($tagihan) > 0){
                  //     $is_krs = true;
                  //   }else{
                  //     $is_krs = false;
                  //   }
                  // }
              ?>
                {{-- <!-- @if(in_array('krs_mahasiswa-CanAdd', $acc) && $is_krs == true) -->
                <!-- @elseif(in_array('krs_mahasiswa-CanAdd', $acc))<a href="#" class="btn btn-success btn-sm">Tagihan KRS Sudah Terbayar &nbsp;<i class="fa fa-plus"></i></a>
                @endif --> --}}
                <a href="{{ route('krsonline_create', ['nim' => $_GET['nim'], 'term_year'=>$_GET['term_year']]) }}" class="btn btn-success btn-sm">Tambah Matakuliah &nbsp;<i class="fa fa-plus"></i></a>
          </div>
          @endif
          <b>KRS Per Mahasiswa</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('krs_mahasiswa.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <label class="col-md-2">NIM Mahasiswa :</label>
							<input type="text" class="form-control form-control-sm col-md-3" name="nim" value="{{ $nim }}">
            <label class="col-md-2" >Th Akademik :</label>
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Th Akademik</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
          </div>
          <hr>

          {!! Form::close() !!}
				<div class="row">
					@if($student != null)
					<div class="col-sm-6">
						<label for="" class="col-sm-4">Nama Mahasiswa</label>
						<label for="" class="col-sm-7">:
							@if($student != "")
								{{ $student->Full_Name }}
							@endif
						</label>
						<label for="" class="col-sm-4">Prodi Mahasiswa</label>
						<label for="" class="col-sm-7">:
							@if($dat != "")
								{{ $dat->Department_Name }}
							@endif
						</label>
						<label for="" class="col-sm-4">Program Kelas</label>
						<label for="" class="col-sm-7">:
							@if($dat != "")
							 	{{ $dat->Class_Program_Name }}
							@endif
            </label>
						<label for="" class="col-sm-4">SKS yang diizinkan</label>
						<label for="" class="col-sm-7">:
            @foreach($sks as $sk)
              {{ $sk->AllowedSKS }}
            @endforeach
            </label>

					</div>
					<!-- <div class="col-sm-6">
            <label for="" class="col-sm-4">Deposit semester ini</label>
						<label for="" class="col-sm-7">:
              @foreach($saldo as $sal)
                {{ number_format($sal->DepositSmtIni, 0 ,",",".") }}
              @endforeach
            </label>
						<label for="" class="col-sm-4">Sisa deposit lalu</label>
						<label for="" class="col-sm-7">:
              @foreach($saldo as $sal)
                {{ number_format($sal->SisaDepositLalu, 0 ,",",".") }}
              @endforeach
            </label>
						<label for="" class="col-sm-4">Deposit bisa dipakai</label>
						<label for="" class="col-sm-7">:
              @foreach($saldo as $sal)
                {{ number_format($sal->DepositSmtIni + $sal->SisaDepositLalu, 0 ,",",".") }}
              @endforeach
            </label>
						<label for="" class="col-sm-4">Dipakai saat ini</label>
						<label for="" class="col-sm-7">:
              @foreach($saldo as $sal)
                {{ number_format($sal->DipakaiSaatIni, 0 ,",",".") }}
              @endforeach
            </label>
						<label for="" class="col-sm-4">Sisa saldo saat ini</label>
						<label for="" class="col-sm-7">:
              @foreach($saldo as $sal)
                {{ number_format($sal->SisaSaldoSaatIni, 0 ,",",".") }}
              @endforeach
            </label>
					</div> -->
					@endif
				</div>
				<br>
      </div>

      @if($nim != null && $cetak)
      <label class="col-sm-5"><a href="{{ url('/client/krs_mahasiswa/'.$nim.'/'.$term_year) }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">Cetak <i class="fa fa-print"></i> </a></label>
      @endif
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
                  <th>No</th>
                  <th>Kode Matakuliah</th>
                  <th>Nama Matakuliah</th>
                  <th>Kelas</th>
                  <th>SKS</th>
                  <!-- <th>Biaya</th> -->
									<th>SMT</th>
									<th>Approved</th>
                  @if(in_array('krs_mahasiswa-CanEdit', $acc) || in_array('krs_mahasiswa-CanDelete', $acc))
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
                  @endif

              </tr>
          </thead>
          <tbody>
            <?php
            $tagihan = [];
            $masuk_semester = 0;

            if(count($query)>0){
              $tagihan = DB::select('CALL usp_GetStudentBill_For_KRS(?,?,?)', [$query[0]->Register_Number, '', '']);
              $masuk_semester = DB::table('mstr_term_year')
              ->whereBetween('Term_Year_Id', [$query[0]->Entry_Year_Id . $query[0]->Entry_Term_Id, $query[0]->Term_Year_Id])
              ->get();
            }

						$ttl_sks = 0;
						$i=1;
            foreach ($query as $data) {
              $ttl_sks = $ttl_sks + $data->Sks;
            ?>
              <tr>
                  <td>{{ $i }}</td>
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Class_Name }}</td>
                  <td>{{ $data->Sks }}</td>
                  <!-- <td>{{ number_format($data->Amount, 0 ,",",".") }}</td> -->
									<td>{{ $data->Level_Name }}</td>
									<td><?php if($data->Is_Approved === null ){ echo 'Belum diproses'; }elseif ($data->Is_Approved == 1) {echo 'Acc';}else{ echo 'Ditolak';} ?></td>
                  @if(in_array('krs_mahasiswa-CanEdit', $acc) || in_array('krs_mahasiswa-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('krs_mahasiswa.destroy', $data->Krs_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('krs_mahasiswa-CanEdit', $acc))<a href="{{ url('proses/krs_mahasiswa/'.$data->Krs_Id.'/edit?term_year='.$term_year.'&nim='.$nim) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(count($masuk_semester) == 1 || count($masuk_semester) == 2)
                          {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Krs_Id]) !!}
                      @else
                        @if(in_array('krs_mahasiswa-CanDelete', $acc) && count($tagihan) > 0)
                          {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Krs_Id]) !!}
                        @endif
                      @endif
                      {!! Form::close() !!}
                  </td>
                  @endif
              </tr>
              <?php
							$i++;
              }
              ?>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$ttl_sks}}</td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
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
                  url: "{{ url('') }}/proses/krs_mahasiswa/" + id,
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
