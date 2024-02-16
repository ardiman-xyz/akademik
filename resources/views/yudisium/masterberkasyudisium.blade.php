@extends('layouts._layout')
@section('pageTitle', 'Master Berkas Yudisium')
@section('content')

<?php
$access = auth()->user()->akses();
  $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Master Berkas Yudisium</h3>
    </div>
  </div>
  <br>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/yudisium/berkasyudisium/data') }}" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Master Berkas</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <div id="gridMaster" style="width:100%;" class="text-success"></div>
          <div id="hapusMasterDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusMasterDialog">
              Anda yakin ingin menghapus <strong>#= Yudisium_Document_Name #</strong>?
          </script>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
var hapusMasterDialog,
    templateHapusMasterDialog,
    templateMasterEptDialog;
$(document).ready(function() {
  templateHapusMasterDialog = kendo.template($("#templateHapusMasterDialog").html());


//Master Berkas
  var gridMaster = $('#gridMaster').kendoGrid({ 
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_yudisium') }}",
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
                    url: "{{ route('api.post.master_berkas_yudisium') }}",
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
                        $('#gridMaster').data("kendoGrid").dataSource.read();
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
                    url: "{{ route('api.post.master_berkas_yudisium') }}",
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
                        $('#gridMaster').data("kendoGrid").dataSource.read();
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
              id: "Yudisium_Document_Id",
              fields: {
                Yudisium_Document_Id: { editable: false },
                No: { editable: false },
                Yudisium_Document_Name: { editable: true },
              }
          }
        },
        // serverPaging: true,
        pageSize: 10,
    }, //dataSourceGrid
    toolbar: [
      @if(in_array('education_program_type-CanAdd', $acc))
        { name: "create", text: "Tambah Data",className:"k-button-primary" }
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
                field: "No",
                title: "No.",
                width:"75px",
                template: "#= No ? No : ''#" ,
            },
            {
                field: "Yudisium_Document_Name",
                title: "Nama Dokumen",
                width:"75",
                template: "#= Yudisium_Document_Name ? Yudisium_Document_Name : ''#" ,
            },
            { command: [
                {name: "edit"},
                {
                    name: "customDeleteUser",
                    iconClass: "k-icon k-i-close",
                    text: "Hapus",
                    click: hapusMasterBerkas
                },
              ], 
              title: "&nbsp;", 
              width: "250px" }
        ],
  })
//hapus master berkas
  function hapusMasterBerkas(t) {
    t.preventDefault();

    var tr = $(t.target).closest("tr"),
        data = this.dataItem(tr);

    hapusMasterDialog = $("#hapusMasterDialog").kendoDialog({
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
                        url: "{{ route('api.delete.master_berkas_yudisium') }}",
                        type: "delete",
                        data: {
                          data:data.Yudisium_Document_Id,
                        },
                        dataType: "json",
                        success: function (res) {
                            if(res.success == true){
                                $("#gridMaster").data("kendoGrid").dataSource.read();
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
                                        $("#gridMaster").data("kendoGrid").dataSource.read();
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

    hapusMasterDialog.content(templateHapusMasterDialog(data));
    hapusMasterDialog.open();
  }
});

//detail init
  function detailInit(e) {
    var detail_init = $("<div class='text-success'/>").appendTo(e.detailCell).kendoGrid({
      dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_prodi_yudisium') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id
                    },
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
            options.data.models[0].Department_Id = e.data.Department_Id
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.post.master_berkas_prodi_yudisium') }}",
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
                        var grids = detail_init.data("kendoGrid");
                            grids.dataSource.read();
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
                                        var grids = detail_init.data("kendoGrid");
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
                    url: "{{ route('api.post.master_berkas_prodi_yudisium') }}",
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
                        var grids = detail_init.data("kendoGrid");
                            grids.dataSource.read();
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
                                        var grids = detail_init.data("kendoGrid");
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
              id: "Yudisium_Prerequisite_Id",
              fields: {
                Yudisium_Prerequisite_Id: { editable: false },
                No: { editable: false },
                Yudisium_Document_Id: { editable: true },
                Yudisium_Document_Name: { editable: true },
                Copies: { editable: true },
              }
          }
        },
        // serverPaging: true,
        pageSize: 10,
    }, //dataSourceGrid
    toolbar: [
        { name: "create", text: "Tambah Data",className:"k-button-primary" }
    ],
    sortable: true,
    pageable: {
        refresh: true,
        pageSizes: true,
        buttonCount: 5
    },
    editable: "inline",
    edit: function (d) {
      if(d.model.Yudisium_Document_Id == '' || d.model.Yudisium_Document_Id == null){
        d.container.find("input[name=Yudisium_Document_Id]").kendoMultiSelect({
          dataTextField: "Yudisium_Document_Name",
          dataValueField: "Yudisium_Document_Id",
          dataSource: {
              transport: {
                  read: function(options) {
                      $.ajax({
                      dataType: 'json',
                      url: "{{ route('api.get.master_berkas_notin_yudisium') }}",
                      type: 'GET',
                      data: {
                        Department_Id : e.data.Department_Id,
                        Yudisium_Document_Id : d.model.Yudisium_Document_Id
                      },
                      success: function(res) {
                      options.success(res.data);
                      }
                  })
                  },
              },
          }
        });
      }else{
        d.container.find("input[name=Yudisium_Document_Id]").kendoDropDownList({
          dataTextField: "Yudisium_Document_Name",
          dataValueField: "Yudisium_Document_Id",
          dataSource: {
              transport: {
                  read: function(options) {
                      $.ajax({
                      dataType: 'json',
                      url: "{{ route('api.get.master_berkas_notin_yudisium') }}",
                      type: 'GET',
                      data: {
                        Department_Id : e.data.Department_Id,
                        Yudisium_Document_Id : d.model.Yudisium_Document_Id
                      },
                      success: function(res) {
                      options.success(res.data);
                      }
                  })
                  },
              },
          }
        });
      }
    },
    columns: [
            {
                field: "No",
                title: "No",
                width:"75px",
                template: "#= No ? No : ''#" ,
            },
            {
                field: "Yudisium_Document_Id",
                title: "Nama Dokumen",
                width:"75",
                template: "#= Yudisium_Document_Name ? Yudisium_Document_Name : ''#" ,
            },
            {
                field: "Copies",
                title: "Jumlah",
                width:"75",
                template: "#= Copies ? Copies : ''#" ,
            },
            { command: [
                {name: "edit"},
                {
                    name: "customDeleteUser",
                    iconClass: "k-icon k-i-close",
                    text: "Hapus",
                    click: hapusData
                },
              ], 
              title: "&nbsp;", 
              width: "250px" }
        ],
    });

  }
</script>
@endsection
