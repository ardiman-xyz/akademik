@extends('layouts._layout')

@section('title', 'Laporan Data Hamasiswa')

@section('content')
<div class="container-fluid-title">
	<div class="title-laporan">
		<h3 class="text-white">Laporan Data Mahasiswa</h3>
	</div>
</div>
{{-- <div class="container">
	<div class="card" style="margin-bottom: 20px">
		<div class="card-header">
			Master Data
		</div>
		<div class="card-body">
			<div id="userGrid"></div>
		</div>
	</div>
</div> --}}

<div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Index</b>
        </div>
      </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <div id="userGrid"></div>
        </div>
      </div>
    </div>
</div>

<script type="text/x-kendo-template" id="toolbarTemplate">
    <div class="a"></div>
    <div class="float-left row col-md-6">
      <div class="row col-md-12">
        <label class="col-md-2">Department</label>
        <input class="col-md-7" id="department" name="department">
      </div></br>
      <div class="row col-md-12">
        <label class="col-md-2">Angkatan</label>
        <input class="col-md-7" id="angkatan" name="angkatan">
      </div>
      </br></br>
      <a role="button" class="k-button k-button-icontext k-grid-export laporandata " id="laporandata" href="\\#"><span class="k-icon k-i-excel"></span>laporan Data Mahasiswa</a>
      <a role="button" class="k-button k-button-icontext k-grid-export export" id="export" href="\\#"><span class="k-icon k-i-excel"></span>List Data Mahasiswa</a>
    </div>
    <div class="float-right row col-md-5">
        <label class="search-label col-md-1" for="searchBox">Search:</label>
        <input type="search" id="searchBox" class="k-textbox"  placeholder="Search..." style="width: 300px"/>
        <input type="button" id="btnSearch" class="k-button search" value="Search"/>
        <input type="button" id="btnReset" class="k-button search" value="Reset"/>
        <div class="a"></div>
    </div>
</script>

<div id="hapusDialog"></div>

<script type="text/x-kendo-template" id="templateHapusDialog">
	Anda yakin ingin menghapus <strong>#= name #</strong>?
</script>

<script type="text/x-kendo-template" id="PopupTemplate">
    <!-- #if(data.isNew()) {#
        #var createTemp = kendo.template($("\#createPopupTemplate").html());#
        #=createTemp(data)#
    #} else {#
        #var editTemp = kendo.template($("\#editPopupTemplate").html());#
        #=editTemp(data)#
    #}# -->
</script>

<script>
	var hapusDialog,
    record = 0,
		templateHapusDialog;

	$(function () {
		templateHapusDialog = kendo.template($("#templateHapusDialog").html());
		var userDataSource = new kendo.data.DataSource({
			transport: {
				read: function(options){
          var department = $('#department').val();
          var angkatan = $('#angkatan').val();
          options.data.Department_Id = department;
          options.data.Entry_Year_Id = angkatan;
          $.ajax({
            url: "{{ route('laporandatamahasiswa.getAll.getAll') }}",
            type: "GET",
                data: options.data,
                dataType: "json",
                success: function (res) {
                    options.success(res);
                }
          });
        },
			},
			schema: {
				data: "data",
				total: "total",
				model: {
					id: "id",
					fields: {
                        id: {
                            editable: false,
                            nullable: true,
                            visible: false
                        },
                        name: {
                            type: "text",
                            validation: { required: true }
                        },
                        gender: {
                            type: "text",
                            validation: { required: true }
                        },
                        Department_Id:{
                           type: "text",
                            validation: { required: true }
                        }
                    }
				}
			},
			pageSize: 10,
      serverPaging: false,
		});

		$("#userGrid").kendoGrid({
			dataSource: userDataSource,
			columns: [
        { template: "#= ++record #", title: "No", width: 50, filterable:false},
				{ field: "Nim", title: "Nim",filterable:false },
        { field: "Full_Name", title: "Nama",filterable:false },
        { field: "Gender_Type", title: "Jenis Kelamin",
           filterable: {
            multi:true,
            messages: {
                checkAll: "Pilih Semua",
                selectedItemsFormat: "{0} item terpilih"
            },
            dataSource: [{Gender_Type: "Perempuan"},{Gender_Type: "Laki-Laki"}]
        }
        },
        { field: "Class_Program_Name", title: "Kelas Program",filterable:false }
      ],
    filterable:true,
		toolbar: kendo.template($("#toolbarTemplate").html()),
      dataBinding: function() {
          record = (this.dataSource.page() -1) * this.dataSource.pageSize();
        },
			sortable: true,
			pageable: {
				pageSizes: true,
				numeric: false,
				input: true,
				refresh: true
			},
			editable: {
				mode: "popup",
				template: $("#PopupTemplate").html()
			}
		});

    $("#btnSearch").click(function () {
          var searchValue = $('#searchBox').val();
          $("#userGrid").data("kendoGrid").dataSource.filter({
            logic  : "or",
            filters: [
              {
                field   : "Nim",
                operator: "contains",
                value   : searchValue
              },
              {
                field   : "Full_Name",
                operator: "contains",
                value   : searchValue
              }
            ]
          });
        });

        $("#btnReset").click(function () {
          $("#userGrid").data("kendoGrid").dataSource.filter({});
        });

     $("input[name='department']").kendoDropDownList({
      dataTextField: "Department_Name",
      dataValueField: "Department_Id",
      optionLabel: "--- Pilih Departemen ---",
      dataSource: {
        transport: {
          read: {
            url: "{{ route('laporandatamahasiswa.getDepartment.getDepartment') }}",
            dataType: "json"
          }
        }
      },
      change : function () {
       $("#userGrid").data("kendoGrid").dataSource.read();
      }
    });

    $("input[name='angkatan']").kendoDropDownList({
      dataTextField: "Entry_Year_Code",
      dataValueField: "Entry_Year_Id",
      optionLabel: "--- Pilih Tahun Angkatan ---",
      dataSource: {
        transport: {
          read: {
            url: "{{ route('laporandatamahasiswa.getEntryyear.getEntryyear') }}",
            dataType: "json"
          }
        }
      },
      change : function () {
       $("#userGrid").data("kendoGrid").dataSource.read();
      }
    });

    $("#export").click(function(e) {
      var dept = 0;
      var entry = 0;
      var department = $('#department').val();
      var angkatan = $('#angkatan').val();
        if(department == ''){
          if(angkatan==''){
            department = dept;
            angkatan = entry;
          }else{
            department = dept;
            angkatan = angkatan;
          }
        }else{
          if(angkatan==''){
            department = department;
            angkatan = entry;
          }else{
            department = department;
            angkatan = angkatan;
          }
        }
        window.open("{{ url('') }}/laporan/laporandatamahasiswa/exportdata/exportdata/" + department + "/" + angkatan);          
    });

        $("#laporandata").click(function(e) {
      var dept = 0;
      var entry = 0;
      var department = $('#department').val();
      var angkatan = $('#angkatan').val();
        if(department == ''){
          if(angkatan==''){
            department = dept;
            angkatan = entry;
          }else{
            department = dept;
            angkatan = angkatan;
          }
        }else{
          if(angkatan==''){
            department = department;
            angkatan = entry;
          }else{
            department = department;
            angkatan = angkatan;
          }
        }
        window.open("{{ url('') }}/laporan/laporandatamahasiswa/laporandata/laporandata/" + department + "/" + angkatan);          
    });
  });

	function hapusData(e) {
		e.preventDefault();

		var tr = $(e.target).closest("tr"),
			data = this.dataItem(tr);

		hapusDialog = $("#hapusDialog").kendoDialog({
			width: "350px",
			title: "Hapus Data",
			visible: false,
			buttonLayout: "stretched",
			actions: [
				{
					text: "Hapus",
					primary: true,
					action: function (e) {
						var id = {id: data.id};

						$.ajax({
							headers: {
						        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
						    },
							type: "DELETE",
							data: id,
							dataType: "json",
              success: function (res) {
                        options.success(res);
                    },
                    complete: function (e) {
                        $("#userGrid").data("kendoGrid").dataSource.read();
                    }
						});
					}
				},
				{text: "Batal"}
			]
		}).data("kendoDialog");

		hapusDialog.content(templateHapusDialog(data));
		hapusDialog.open();
	}
</script>

@endsection
