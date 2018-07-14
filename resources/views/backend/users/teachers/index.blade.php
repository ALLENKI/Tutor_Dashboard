@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Teachers</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Teachers</li>
			</ul>
		</div>

<!-- 		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="{{ route('admin::users::users.create') }}" class="btn btn-link btn-float has-text"><i class="icon-plus2 text-primary"></i><span>Create New User</span></a>
			</div>
		</div> -->
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">List of Teachers</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">
		This Table Shows all the Teacher registered with Aham. 
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>Code</th>
				<th>Name</th>
				<th>Email</th>
				<th>Active</th>
				<th class="text-center" width="10%">Actions</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<!-- /basic datatable -->

@stop


@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
        }
    });


    // Basic datatable
    table =  $('#myTable').DataTable({
        autoWidth: true,
        scrollY: 300,
        "serverSide": true,
		"ajax": {
			"url": "{{ $tableRoute }}", // ajax source
			"type": "GET"
		},
    	
    	initComplete: function() {
	        $('#myTable_filter input').unbind();
	        $('#myTable_filter input').bind('keyup', function(e) {
	            if(e.keyCode == 13) {
	                table.search( this.value ).draw();
	            }
	        });
	    },

		"columns": [
		    { "data": "teachers.code", "name": "code"  },
		    { "data": "teachers.name", "name": "name"  },
		    { "data": "teachers.email", "name": "email"  },
		    { "data": "teachers.active", "name": "active"  },
		    { "data": "teachers.actions", "name": "actions", "sortable": false, "className": 'text-center'},
		],
    });

	// External table additions
    // ------------------------------

    // Add placeholder to the datatable filter option
    $('.dataTables_filter input[type=search]').attr('placeholder','Type to filter...');

    // Enable Select2 select for the length option
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        width: 'auto'
    });

    $('.panel [data-action=reload]').click(function (e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent().parent();
        $(block).block({ 
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

		table.ajax.reload( function ( json ) {
		    	$(block).unblock();
		});
    });

});
</script>

@stop