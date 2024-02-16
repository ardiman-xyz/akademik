@extends('layouts._layout')
@section('pageTitle', 'Education Program Type')
@section('content')

<?php
$access = auth()->user()->akses();
  $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Strata Pendidikan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Strata Pendidikan</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
        <div class="table-responsive">
        
          <div id="gridEpt" style="width:100%;" class="text-success"></div>
          <div id="hapusEptDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusEptDialog">
              Anda yakin ingin menghapus <strong>#= Program_Name #</strong>?
          </script>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
var hapusEptDialog,
    templateHapusEptDialog;
$(document).ready(function() {
  templateHapusEptDialog = kendo.template($("#templateHapusEptDialog").html());

  var grid = $('#gridEpt').kendoGrid({ 
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.strata_pendidikan') }}",
                    // url: "{{ url('') }}/api/master/get/all_building",
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
                                    // window.location.reload(true) // submitting the form when user press yes
                                        var grids = grid.data("kendoGrid");
                                        grids.dataSource.read();
                                        swal.close()
                                    }
                                });
                        }

                })
            }, //read
          create: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.post.strata_pendidikan') }}",
                    type: 'post',
                    data: {
                      data:options.data.models[0]
                    },
                    success: function(res) {
                    if (res.success == false) {
                        swal('Sorry',res.data,'warning');
                    }else{
                        swal('Ok',res.data,'success');
                        options.success(res);
                        $('#gridEpt').data("kendoGrid").dataSource.read();
                    }
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
                                    // window.location.reload(true) // submitting the form when user press yes
                                        var grids = grid.data("kendoGrid");
                                        grids.dataSource.read();
                                        swal.close()
                                    }
                                });
                        }

                })
            }, //create
          update: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.post.strata_pendidikan') }}",
                    type: 'post',
                    data: {
                      data:options.data.models[0],
                    },
                    success: function(res) {
                      console.log(res);
                    if (res.success == false) {
                        swal('Sorry',res.data,'warning');
                    }else{
                        swal('Ok',res.data,'success');
                        options.success(res);
                        $('#gridEpt').data("kendoGrid").dataSource.read();
                    }
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
                                    // window.location.reload(true) // submitting the form when user press yes
                                        var grids = grid.data("kendoGrid");
                                        grids.dataSource.read();
                                        swal.close()
                                    }
                                });
                        }

                })
            }, //update
        }, //transport grid
        batch: true,
        schema: {
          data: 'data',
          total: 'total',
          model: {
              id: "Education_Prog_Type_Id",
              fields: {
                Education_Prog_Type_Id: { editable: false },
                Education_Prog_Type_Code: { editable: true },
                Program_Name: { editable: true },
                Program_Name_Eng: { editable: true },
                Acronym: { editable: true },
                Study_Period_Semester: { editable: true },
              }
          }
        },
        // serverPaging: true,
        pageSize: 10,
    }, //dataSourceGrid
    toolbar: [
      @if(in_array('education_program_type-CanAdd', $acc))
        { name: "create", text: "Tambah Data",className:"btn btn-success" }
      @endif
    ],
    sortable: true,
    pageable: {
        refresh: true,
        pageSizes: true,
        buttonCount: 5
    },
    editable: "inline",
    columns: [
            {
                field: "Education_Prog_Type_Code",
                title: "Kode",
                width:"75",
                template: "#= Education_Prog_Type_Code ? Education_Prog_Type_Code : ''#" ,
            },
            {
                field: "Program_Name",
                title: "Nama Strata Pendidikan",
                width:"75",
                template: "#= Program_Name ? Program_Name : ''#" ,
            },
            {
                field: "Program_Name_Eng",
                title: "Nama Strata Pendidikan Eng",
                width:"75",
                template: "#= Program_Name_Eng ? Program_Name_Eng : ''#" ,
            },
            {
                field: "Acronym",
                title: "Acronym",
                width:"75",
                template: "#= Acronym ? Acronym : ''#" ,
            },
            {
                field: "Study_Period_Semester",
                title: "Maksimal Semester",
                width:"75",
                template: "#= Study_Period_Semester ? Study_Period_Semester : ''#" ,
            },
            { command: [
              @if(in_array('education_program_type-CanEdit', $acc))
                {name: "edit"},
              @endif
              @if(in_array('education_program_type-CanDelete', $acc))
                {
                    name: "customDeleteUser",
                    iconClass: "k-icon k-i-close",
                    text: "Hapus",
                    click: hapusDataUser
                },
              @endif
              ], 
              title: "&nbsp;", 
              width: "250px" }
        ],
  })
});

function hapusDataUser(t) {
    t.preventDefault();

    var tr = $(t.target).closest("tr"),
        data = this.dataItem(tr);

    hapusEptDialog = $("#hapusEptDialog").kendoDialog({
        width: "350px",
        title: "Hapus Data",
        visible: false,
        buttonLayout: "stretched",
        actions: [
            {
                text: "Hapus",
                primary: true,
                action: function (e) {
                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('api.delete.strata_pendidikan') }}",
                        type: "delete",
                        data: {
                          data:data.Education_Prog_Type_Id,
                        },
                        dataType: "json",
                        success: function (res) {
                            if(res.success == true){
                                $("#gridEpt").data("kendoGrid").dataSource.read();
                            }else{
                                swal({
                                    //title: thrownError,
                                    title: "Mohon Maaf",
                                    text: res.data,
                                    type: "error",
                                    confirmButtonColor: "#b20000",
                                    confirmButtonText: "Batal",
                                    // cancelButtonText: "Tidak, Batalkan!",
                                    // closeOnConfirm: false,
                                    // showCancelButton: true,
                                    // cancelButtonText: 'cancel!',
                                },
                                function(isConfirm) {
                                    if (isConfirm) {
                                        $("#gridEpt").data("kendoGrid").dataSource.read();
                                    }
                                }); 
                            }
                        }
                    });
                }
            },
            {text: "Batal"}
        ]
    }).data("kendoDialog");

    hapusEptDialog.content(templateHapusEptDialog(data));
    hapusEptDialog.open();
}
</script>
@endsection
