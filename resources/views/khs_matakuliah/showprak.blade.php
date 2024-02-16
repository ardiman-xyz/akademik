@extends('layouts._layout')
@section('pageTitle', 'KHS')
@section('content')
<style>
  /* div.dataTables_wrapper {
        width: 1200px;
        margin: 0 auto;
    } */
  /* input {
    width: 100px;
  } */
  .here{
    width:100%%;
  }
  table {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
  border: 1px solid #ddd;
}
</style>
  <?php
    $access = auth()->user()->akses();
    $acc = $access;
  ?>
  <section class="content">

    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Nilai Akhir</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ url('proses/khs_matakuliah/?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <div class="bootstrap-admin-box-title right text-white">
            <b>Detail</b>
          </div>
      </div>
      <br>
      <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="col-sm-6">
            <div >
              <label class="col-sm-5">Th Akademik/Semester</label>:
              <label class="col-sm-5"> {{ $aoc->Term_Year_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Program Studi</label>:
              <label class="col-sm-5"> {{ $aoc->Department_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Kelas Program</label>:
              <label class="col-sm-5"> {{ $aoc->Class_Program_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Matakuliah</label>:
              <label class="col-sm-5"> {{ $aoc->Course_Name }}</label>
            </div>
          </div>
          <div class="col-sm-6">
            <div>
              <label class="col-sm-5">Kelas</label>:
              <label class="col-sm-5"> {{ $aoc->Class_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Kapasitas</label>:
              <label class="col-sm-5"> {{ $aoc->Class_Capacity }}</label>
            </div>
          </div>

          <div class="bootstrap-admin-box-title right text-green">
            <!-- <b>Daftar Fakultas</b> -->
            
            <br>
              <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                 Note:
                <u><i><p> lakukan simpan ulang jika anda melakukan perubahan pada bobot penilaian</p></i></u>
              </div>


        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
          <br>
          <!-- <div class="row here"> -->
          <div style="overflow-x:auto;">
            <form class="" action="{{url('proses/khs_matakuliah/storenilaiprak/simpan')}}" method="post">
              {{ csrf_field() }}              
              <input type="text" hidden name="oci" value="{{$oci}}">
          <!-- <table class="table table-striped table-font-sm nowrap" id="myTable" style="width:100%"> -->
          <table id="myTable" class="table table-striped table-font-sm" style="width:100%">
            <thead class="thead-default thead-green">
              <tr>
                    <th >
                        NIM
                    </th>
                    <th >
                        Nama Mahasiswa
                    </th>
                    @foreach ($bobot as $key)
                    <th  >

                        {{$key->Item_Name.'(Bobot :' .$key->Bobot.')'}}<br>
                        Nilai | Score
                    </th>
                    @endforeach
                    <th >
                        TOTAL
                    </th>
                    <th >
                        NILAI
                    </th>
                </tr>

            </thead>
            <tbody>
              <?php $no=0  ?>
                @foreach ($data as $key)
                  <tr>
                    {{-- <input type="hidden" name="" value="{{$key['krs_id']}}">
                    <input type="hidden" name="" value="{{$key['stu_id']}}"> --}}
                    <td>{{$key['Nim']}}</td>
                    <td>{{$key['Full_Name']}}</td>


                      @foreach ($key['isi'] as $key2)
                        <td>
                          <input type="hidden" name="bobot[]" value="{{$key2['Bobot']}}">
                          <input type="hidden" name="score[]" value="{{$key2['Score']}}">

                          <input type="<?php if(!in_array('khs_matakuliah-CanEditDetail', $acc)){ echo 'hidden'; } ?>" size="15" name="value[]" value="{{$key2['Value']}}">
                          <!-- <input type="text" size="15" name="value[]" value="{{$key2['Value']}}" onkeyup="this.value = minmax(this.value, 0, 100)"> -->
                          <input type="hidden" name="krs[]" value="{{$key['Krs_Id']}}">
                         <input type="hidden" name="status[]" value="{{$key2['status']}}">
                          <input type="hidden" name="Bobot_id[]" value="{{$key2['Bobot_id']}}">|
                          <span  class="badge badge-danger" style="font-size:100%"><strong>{{$key2['Score']}}</strong></span>
                        </td>
                      @endforeach
                      <td>{{$key['Total']}}</td>
                      <td>{{$key['Grade']}}</td>
                      <input type="hidden" name="total[]" value="{{$key['Total']}}">
                       <input type="hidden" name="hitungKrs[]" value="{{$key['Krs_Id']}}">
                       {{-- <td>{{$key['latter']}}</td> --}}
                  </tr>
                  <?php $no++ ?>
                @endforeach
            </tbody>
          </table>
          <label >
             <!-- <input type="checkbox"  name='hitung' value="{{$D_id}}"> Hitung KHS -->
          </label><br>

          <div class="col-sm-6">
            <button class="col-sm-5 btn btn-success btn-sm"type="submit" name="button">Simpan</button>
          </div>
          <br>
            </form>
            <label class="col-sm-6"><a href="#" class="col-sm-5 btn btn-primary btn-sm updatekurikulum">Update Kurikulum</i></a></label>
          </div>
        <!-- </div> -->
        </div>
      </div>
    </div>
  </section>
  <script type="text/javascript">
  $(document).ready( function () {
    $('#myTable').DataTable({
        dom: 'Bfrtip',
        lengthMenu: [
            [ 10, 25, 50, -1 ],
            [ '10 rows', '25 rows', '50 rows', 'Show all' ]
        ],
        buttons: [
            'excelHtml5',
            'pageLength'
        ]
      });
  });
$(document).on('click', '.updatekurikulum', function (e) {
      var term_year = <?php echo $term_year ?>;
      var class_program = <?php echo $class_program ?>;
      var department = <?php echo $department ?>;
      var Offered_Course_id = <?php echo $aoc->Offered_Course_id ?>;
      var Class_Id = <?php echo $aoc->Class_Id ?>;
      var Course_Id = <?php echo $aoc->Course_Id ?>;
			swal({
				title: 'Update Kurikulum',
					text: "Nilai/KRS akan Diupdate sesuai KRS yang baru",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Oke',
					cancelButtonText: 'cancel!',
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					buttonsStyling: true
				}, function(isConfirm) {
			if (isConfirm) {
							$.ajax({
									url: '{{route('khs_matakuliah.updateKurikulum')}}',
									type: "get",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
                    term_year : term_year,
                    Course_Id : Course_Id,
                    Class_Id : Class_Id,
                    Offered_Course_id : Offered_Course_id,
                    department : department,
                    class_program : class_program
									},
									success:function (data) {
                    console.log(data);
                    swal({
                      title: data.message,
                      type: 'success', 
                      showCancelButton: true,
                      cancelButtonText: 'oke',
                    });
                    window.location.reload(true);
									},
									error: function(xhr, ajaxOptions, thrownError) {
                                    swal({
                                            title: thrownError,
                                            text: 'Error!! ' + xhr.status,
                                            type: "error",
                                            confirmButtonColor: "#02991a",
                                            confirmButtonText: "Refresh Serkarang",
                                            cancelButtonText: "Tidak, Batalkan!",
                                            closeOnConfirm: false,
                                        },
                                        function(isConfirm) {
                                            if (isConfirm) {
                                            window.location.reload(true) // submitting the form when user press yes
                                            }
                                        });
                                }
							});
							// $("#hapus").submit();
						}else{
              console.log('cenceled');
            }
					});
	});

function minmax(value, min, max)
{
    if(parseInt(value) < min || isNaN(parseInt(value)))
        return min;
    else if(parseInt(value) > max)
        return max;
    else return value;
}
  </script>
@endsection
