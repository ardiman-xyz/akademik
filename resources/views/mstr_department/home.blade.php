@extends('layouts._layout')
@section('pageTitle', 'Department')
@section('content')

<?php
$access = auth()->user()->akses();
  $acc = $access;
?>

<style>
  .k-grid tbody .k-button {
    min-width: 40px;
  }
</style>
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Program Studi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Program Studi</b>
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
        <div class="row">
            <div class="col-md-2">
                <label for="facultySelect">Fakultas</label>
            </div>
            <div class="col-md-3">
                <select id="facultySelect" class="form-control">
                </select>
            </div>
        </div>
        <div class="table-responsive">
        
          <div id="gridFac" style="width:100%;" class="text-success"></div>
          <div id="hapusEptDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusEptDialog">
              Anda yakin ingin menghapus Prodi <strong>#= Department_Name #</strong>?
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

  $("#facultySelect").kendoDropDownList({
    filter: "startswith",
    optionLabel: "Pilih Fakultas",
    dataTextField: "Faculty_Name",
    dataValueField: "Faculty_Id",
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                dataType: 'json',
                url: "{{ route('api.get.faculty') }}",
                type: 'GET',
                data: options.data,
                success: function(res) {
                  options.success(res.data);
                }
            })
            },
        },
        // parameterMap: function (options, operation) {
        //         if (operation == "read") {
        //             options.kodeCoa = $("#facultySelect").data("kendoDropDownList").value();
        //             return JSON.stringify(options);
        //         }
        //         return options;
        //     }
    },
    index: 0,
    change: function() {
        onChangeSearchParameter();
    }
  }).data("kendoDropDownList");

  function onChangeSearchParameter() {
    var grid = $("#gridFac").data("kendoGrid");
    grid.dataSource.read();
  }

  var grid = $('#gridFac').kendoGrid({ 
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.department') }}",
                    // url: "{{ url('') }}/api/master/get/all_building",
                    type: 'GET',
                    data: {
                      Faculty_Id:$("#facultySelect").data("kendoDropDownList").value()
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
            options.data.models[0].Faculty_Id = $("#facultySelect").data("kendoDropDownList").value()
            options.data.models[0].Department_Dikti_Sk_Date= kendo.toString(options.data.models[0].Department_Dikti_Sk_Date, "yyyy-MM-dd")
              $.ajax({
                  dataType: 'json',
                  url: "{{ route('api.post.department') }}",
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
                      $('#gridFac').data("kendoGrid").dataSource.read();
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
            options.data.models[0].Faculty_Id = $("#facultySelect").data("kendoDropDownList").value()
            options.data.models[0].Department_Dikti_Sk_Date= kendo.toString(options.data.models[0].Department_Dikti_Sk_Date, "yyyy-MM-dd")
              $.ajax({
                  dataType: 'json',
                  url: "{{ route('api.post.department') }}",
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
                      $('#gridFac').data("kendoGrid").dataSource.read();
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
              id: "Department_Id",
              fields: {
                Department_Id: { editable: false },
                Department_Code: { editable: true },
                Education_Prog_Type_Id: { editable: true },
                Department_Name: { editable: true },
                Department_Name_Eng: { editable: true },
                Department_Acronym: { editable: true },
                Department_Dikti_Sk_Number: { editable: true },
                Department_Dikti_Sk_Date: { editable: true },
                Nim_Code: { editable: true },
                First_Title: { editable: true },
                Last_Title: { editable: true },
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
    edit: function(e) {
      if($("#facultySelect").data("kendoDropDownList").value() == null || $("#facultySelect").data("kendoDropDownList").value() == ''){
        swal({
            //title: thrownError,
            title: "Mohon Maaf",
            text: 'Pilih Fakultas terlebih dahulu',
            type: "warning",
            confirmButtonColor: "#b20000",
            confirmButtonText: "Batal",
        },
        function(isConfirm) {
            if (isConfirm) {
                $("#gridFac").data("kendoGrid").dataSource.read();
            }
        }); 
        $("#gridFac").data("kendoGrid").dataSource.read();
      }
      e.container.find("input[name=Department_Dikti_Sk_Date]").kendoDatePicker({
        value: new Date(),
        format: "yyyy-MM-dd",
        dateInput: true
      });
      e.container.find("input[name=Education_Prog_Type_Id]").kendoDropDownList({
        dataTextField: "Acronym",
        dataValueField: "Education_Prog_Type_Id",
        dataSource: {
            transport: {
                read: function(options) {
                    $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.strata_pendidikan') }}",
                    type: 'GET',
                    success: function(res) {
                    options.success(res.data);
                    }
                })
                },
            },
        }
      });
    },
    columns: [
            {
                field: "Department_Code",
                title: "Kode",
                width:"8%",
                template: "#= Department_Code ? Department_Code : ''#" ,
            },
            {
                field: "Education_Prog_Type_Id",
                title: "Strata",
                width:"7%",
                template: "#= Education_Prog_Type_Id ? Acronym : ''#" ,
            },
            {
                field: "Department_Name",
                title: "Nama Prodi",
                width:"25%",
                template: "#= Department_Name ? Department_Name : ''#" ,
            },
            {
                field: "Department_Name_Eng",
                title: "Nama Prodi (Eng)",
                width:"10%",
                template: "#= Department_Name_Eng ? Department_Name_Eng : ''#" ,
            },
            {
                field: "Department_Acronym",
                title: "Akronim",
                width:"5%",
                template: "#= Department_Acronym ? Department_Acronym : ''#" ,
            },
            {
                field: "Department_Dikti_Sk_Number",
                title: "No. SK Dikti",
                width:"18%",
                template: "#= Department_Dikti_Sk_Number ? Department_Dikti_Sk_Number : ''#" ,
            },
            {
                field: "Department_Dikti_Sk_Date",
                title: "Tgl. SK Dikti",
                width:"10%",
                template: '#= kendo.toString(kendo.parseDate(Department_Dikti_Sk_Date), "yyyy-MM-dd")#'
            },
            {
                field: "Nim_Code",
                title: "Nim Code",
                width:"7%",
                template: "#= Nim_Code ? Nim_Code : ''#" ,
            },
            {
                field: "Last_Title",
                title: "Gelar",
                width:"7%",
                template: "#= Last_Title ? Last_Title : ''#" ,
            },
            { command: [
              @if(in_array('education_program_type-CanEdit', $acc))
                {name: "edit",text:'',iconClass: "k-icon k-i-edit",},
              @endif
              @if(in_array('education_program_type-CanDelete', $acc))
                {
                    name: "customDeleteUser",
                    iconClass: "k-icon k-i-trash",
                    text: "",
                    click: hapusDataUser
                },
              @endif
              ], 
              title: "&nbsp;", 
              width: "10%" }
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
                        url: "{{ route('api.delete.department') }}",
                        type: "delete",
                        data: {
                          data:data.Department_Id,
                        },
                        dataType: "json",
                        success: function (res) {
                            if(res.success == true){
                                $("#gridFac").data("kendoGrid").dataSource.read();
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
                                        $("#gridFac").data("kendoGrid").dataSource.read();
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
