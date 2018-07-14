@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Classes Mgmt</span> - Group Classes</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Group Classes</li>
			</ul>
		</div>

		<div class="heading-elements">
			<div class="heading-btn-group">
                <a href="{{ route('admin::classes_mgmt::classes.create') }}" class="btn btn-link btn-float has-text"><i class="icon-calendar text-primary"></i><span>Create New Class</span></a>

				<a href="{{ route('admin::classes_mgmt::group_classes.create') }}" class="btn btn-link btn-float has-text"><i class="icon-calendar text-primary"></i><span>Create Group Class</span></a>
			</div>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">List of Classes</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">
		This Table Shows all the Aham Classes. 
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
                <th>Goal</th>
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
        dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });


    // Basic datatable
    table =  $('#myTable').DataTable({
    	"order": [[ 0, "asc" ]],
        scrollY: 300,
        scrollX: true,
        "serverSide": true,
        "drawCallback": function( settings, json ) {
		    $(".rest").restfulizer();
		},
		"ajax": {
			"url": "{{ $tableRoute }}", // ajax source
			"type": "GET"
		},
		"columns": [
		    { "data": "classes.id", "name": "id" },
            { "data": "classes.name", "name": "name" },
		    { "data": "classes.goal_id", "name": "goal_id" },
		    { "data": "classes.actions", "name": "actions", "sortable": false, "className": 'text-center'},
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

    $("div.toolbar").append('Type : <select class="multiselect" id="class_status" multiple="multiple"></select>');

    $('#class_status').multiselect({
        onChange: function() {
            $.uniform.update();
            table.ajax.reload();
        }
    });

    var options = [
        {label: 'Initiated', title: 'Initiated', value: 'initiated', selected: 'true'},
        {label: 'Created', title: 'Created', value: 'created', selected: 'true'},
        {label: 'Invited', title: 'Invited', value: 'invited', selected: 'true'},
        {label: 'Accepted', title: 'Accepted', value: 'accepted', selected: 'true'},
        {label: 'Ready For Enrollment', title: 'Ready For Enrollment', value: 'ready_for_enrollment', selected: 'true'},
        {label: 'Open For Enrollment', title: 'Open For Enrollment', value: 'open_for_enrollment', selected: 'true'},
        {label: 'Scheduled', title: 'Scheduled', value: 'scheduled', selected: 'true'},
        {label: 'In Session', title: 'In Session', value: 'in_session', selected: 'true'},
        {label: 'Get Feedback', title: 'Get Feedback', value: 'get_feedback', selected: 'true'},
        {label: 'Got Feedback', title: 'Got Feedback', value: 'got_feedback', selected: 'true'},
        {label: 'Completed', title: 'Completed', value: 'completed'},
        {label: 'Cancelled', title: 'Cancelled', value: 'cancelled'},
        {label: 'Closed', title: 'Closed', value: 'closed'}
    ];

    $('#class_status').multiselect('dataprovider', options);

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

    table.ajax.reload();


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