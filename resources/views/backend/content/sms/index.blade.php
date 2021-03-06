@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Content</span> - SMS</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">SMS</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->


@stop
@section('content')

<style type="text/css">
.dataTables_scroll .dataTables_scrollBody th, .dataTables_scroll .dataTables_scrollBody td {
    white-space: normal;
}
</style>
<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">SMS</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
                <th>ID</th>
                <th>Number</th>
                <th width="50%">Message</th>
                <th>Result</th>
                <th>Time</th>
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
    	"order": [[ 0, "desc" ]],
        scrollX: true,
        scrollY: 300,
        "serverSide": true,
		"ajax": {
			"url": "{{ $tableRoute }}", // ajax source
			"type": "GET"
		},
		"columns": [
		    { "data": "sms.id", "name": "id","width": "10%" },
            { "data": "sms.number", "name": "number","width": "10%"  },
            { "data": "sms.message", "name": "message","width": "35%"  },
            { "data": "sms.result", "name": "result","width": "35%"  },
            { "data": "sms.time", "name": "time","width": "10%" },
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