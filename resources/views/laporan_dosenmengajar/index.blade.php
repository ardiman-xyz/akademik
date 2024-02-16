@extends('layouts._layout')
@section('pageTitle', 'Daftar Mahasiswa KRS')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
?>
<style>
.k-grid .k-state-selected  {
  background-color: #ffffff !important;
  color: #000000;
}
 
.k-grid .k-alt.k-state-selected {
  background-color: #f1f1f1 !important;
  color: #000000;
}
</style>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Dosen Mengajar</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Dosen Mengajar</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('laporan_dosenmengajar.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->
            <select class="form-control form-control-sm col-md-4" name="term_year" id="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-3" name="department" id="department" onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
            <!-- <label class="col-md-2">Tahun Semester :</label> -->
            <select class="form-control form-control-sm col-md-3" name="class_program" id="class_program" onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp

          </div>
          <br>
        {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
        @if($department != 0 && $term_year !=0)
          <a href="#" class="btn btn-primary btn-sm export" id='export' style="font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a> &nbsp
        @endif
          <div  class="row col-md-7">
          <label class="col-md-3">Pencarian :</label>
          <input type="text" name="search" id="search" class="form-control form-control-sm col-md-7 col-sm-7" placeholder="Search">
        </div>
        </div>
        <div class="table-responsive" style="font-size:14px;">
          <div id="grid"></div>
        </div>
      </div>
    </div>
  </div>
	<script>
  $(document).ready(function() {
      var term_year = $('#term_year').val();
      var class_program = $('#class_program').val();
      var department = $('#department').val();
      var Offered_Course_id = $('#Offered_Course_id').val();
      var Class_Id = $('#Class_Id').val();
      var Course_Id = $('#Course_Id').val();

      $("#export").click(function(e) {
        window.open("{{ url('') }}/laporan/laporan_dosenmengajar/exportdata/exportdata/" + term_year + "/" + department + "/" + class_program); 
      });

      $("#search").keyup(function() {
            var searchValue = $('#search').val();
            $("#grid").data("kendoGrid").dataSource.filter(
                {
                  logic: "or",
                  filters: [
                    {
                      field: "Name",
                      operator: "Contains",
                      value: searchValue
                    }
                  ]                  
                }
            );
        });
        
        var grid = $('#grid').kendoGrid({ 
            dataSource: {
                transport: {
                    read: function(options) {
                        $.ajax({
                            dataType: 'json',
                            url: "{{url('')}}/laporan/laporan_dosenmengajar/getdosen/" + term_year + "/" + department + "/" + class_program,
                            type: 'GET',
                            data: options.data,
                            success: function(res) {
                                options.success(res);
                            }, error: function(xhr, ajaxOptions, thrownError) {
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

                        })
                    }, //read
                }, //transport grid
                schema: {
                  data: 'data',
                  total: 'total',
                  model: {
                      id: "Nim",
                      fields: {
                        Offered_Course_id: { editable: false },
                        Employee_Id: { editable: false },
                        Name: { editable: false },
                        beban_sks: { editable: false },
                        jml_mk: { editable: false },
                      }
                  }
                },
                // serverPaging: true,
                pageSize: 10,
            }, //dataSourceGrid
            sortable: true,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            // editable: "inline",
            editable: true,
            // edit: OnEdit,
            // toolbar: ["save", "cancel"],
            detailInit: detailInit,
            columns: [
                    {
                        field: 'Offered_Course_id',
                        title: "Offered_Course_id",
                        width:"75",
                        hidden:true
                    },
                    {
                        field: 'Employee_Id',
                        title: "Employee_Id",
                        width:"75",
                        hidden:true
                    },
                    {
                        field: 'Name',
                        title: "Nama",
                        width:"75",
                    },
                    {
                        field: 'beban_sks',
                        title: "Beban SKS Semester",
                        width:"75",
                        template:"<span><center>#:beban_sks#</span"
                    },
                    {
                        field: 'beban_sks_real',
                        title: "Beban SKS Realisasi",
                        width:"75",
                        template:"<span><center>#:beban_sks_real#</span"
                    },
                    {
                        field: 'jml_mk',
                        title: "Jumlah MK",
                        width:"75",
                        template:"<span><center>#:jml_mk#</span"
                    },
                ],
        })
    });
    function detailInit(e) {
      var term_year = $('#term_year').val();
      var class_program = $('#class_program').val();
      var department = $('#department').val();
      var Employee_Id = e.data.Employee_Id;
        $("<div class='text-green'/>").appendTo(e.detailCell).kendoGrid({
            dataSource: {          
                transport: {
                    read: function(options) {
                      $.ajax({
                          dataType: 'json',
                          url: "{{url('')}}/laporan/laporan_dosenmengajar/getajardosen/" + Employee_Id + "/" + term_year + "/" + department + "/" + class_program,
                          type: 'GET',
                          data: options.data[0],
                          success: function(res) {
                            options.success(res);
                            console.log(res);
                          }, error: function(xhr, ajaxOptions, thrownError) {
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

                      })
                  }, //read
                },
                schema: {
                  data: 'data',
                  total: 'total',
                  model: {
                      id: "Offered_Course_id",
                  }
                },
                pageSize: 10,
            },
            sortable: true,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            detailInit: sesiPerkuliahan,
            columns: [
              {
                  field: 'Offered_Course_id',
                  title: "Offered_Course_id",
                  width:"75",
                  hidden:true
              },
              {
                  field: 'Term_Year_Id',
                  title: "Term_Year_Id",
                  width:"75",
                  hidden:true
              },
              {
                  field: 'Term_Year_Name',
                  title: "Semester",
                  width:"75",
              },
              {
                  field: 'Course_Code',
                  title: "Kode Matakuliah",
                  width:"75",
              },
              {
                  field: 'Course_Name',
                  title: "Matakuliah",
                  width:"75",
              },
              {
                  field: 'sks',
                  title: "Sks Matakuliah",
                  width:"75",
                  template:"<span><center>#:sks#</span"
              },
              {
                  field: 'sks_dosen',
                  title: "Sks Dosen",
                  width:"75",
                  template:"<span><center>#:sks_dosen#</span"
              },
              {
                  field: 'Class_Name',
                  title: "Kelas",
                  width:"75",
                  template:"<span><center>#:Class_Name#</span"
              },
              {
                  field: 'Class_Program_Name',
                  title: "Program Kelas",
                  width:"75",
              },
              {
                  field: 'totalpertemuan',
                  title: "Total Pertemuan",
                  width:"75",
                  template:"<span><center>#:totalpertemuan#</span"
              },
            ]
        });

      }

      function sesiPerkuliahan(e) {
      var term_year = $('#term_year').val();
      var class_program = $('#class_program').val();
      var department = $('#department').val();
      var Offered_Course_id = e.data.Offered_Course_id;
      var Term_Year_Id = e.data.Term_Year_Id;
        $("<div class='text-green'/>").appendTo(e.detailCell).kendoGrid({
            dataSource: {          
                transport: {
                    read: function(options) {
                      $.ajax({
                          dataType: 'json',
                          url: "{{url('')}}/laporan/laporan_dosenmengajar/getsesikuliah/" + Offered_Course_id + "/" + Term_Year_Id + "/" + department + "/" + class_program +"?Employee_Id="+e.data.Employee_Id,
                          type: 'GET',
                          data: options.data[0],
                          success: function(res) {
                            options.success(res);
                            console.log(res);
                          }, error: function(xhr, ajaxOptions, thrownError) {
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

                      })
                  }, //read
                },
                schema: {
                  data: 'data',
                  total: 'total',
                  model: {
                      id: "Offered_Course_id",
                  }
                },
                pageSize: 10,
            },
            sortable: true,
            pageable: {
                refresh: true,
                pageSizes: true,
                buttonCount: 5
            },
            columns: [
              {
                  field: 'Offered_Course_id',
                  title: "Offered_Course_id",
                  width:"75",
                  hidden:true
              },
              {
                  field: 'pertemuan',
                  title: "Pertemuan ke-",
                  width:"25",
              },
              {
                  field: 'tgl_jam',
                  title: "Tanggal / Jam",
                  width:"75",
              },
              {
                  field: 'tgl_jamdb',
                  title: "Tanggal Dibuat",
                  width:"75",
              },
              {
                  field: 'Course_Content',
                  title: "Konten",
                  width:"75",
              },
              {
                  field: 'Description',
                  title: "Deskripsi",
                  width:"75",
              },
              {
                  field: 'jml_peserta',
                  title: "jml Peserta",
                  width:"75",
              },
              {
                  field: 'acd_students_count',
                  title: "Peserta Hadir",
                  width:"75",
              },
                // { field: "Topic_Id", title:"topic code", width: "20%",
                // template: "# if( Topic_Id==null) {#<span><span># } else {#<span>#: Topic_Code#<span>#} #" 
                // },
                // { field: "Topic_Id", title:"topic", width: "30%",
                // template: "# if( Topic_Id==null) {#<span><span># } else {#<span>#: Topic_Name#<span>#} #" 
                // },{
                //       field: "Title",
                //       title: "Title",
                //       width:"50%",
                //       template: "<p style='font-size:12px;'>#:Title#</p>" 
                //   }
            ]
        });

      }

  $(document).ready(function() {  
    var table = $('#datatable').DataTable({
      searching: false, paging: false, info: false
    });
  });

	$(document).on('click', '.hapus', function (e) {
			e.preventDefault();
			var id = $(this).data('id');
			var term_year = $('#term_year').val();
			var department = $('#department').val();
			var class_program = $('#class_program').val();

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
									url: "{{ url('') }}/laporan/laporan_daftar_mahasiswa_krs/" + id,
									type: "DELETE",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
                    term_year : term_year,
                    department : department,
                    class_program : class_program
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
