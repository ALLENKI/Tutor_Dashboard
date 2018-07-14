@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Goals Management</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Goals</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop


@section('content')

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Create a New Goal</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::goals.store')) !!}

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','false') !!}

        {!! BootForm::textarea('Description *', 'description')
              ->placeholder('Name')
              ->attribute('rows',3) !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">
			Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->


<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">List of Goals</h5>
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>Name</th>
				<th>Slug</th>
				<th>Active</th>
				<th>Actions</th>
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
    	"order": [[ 0, "asc" ]],
        autoWidth: true,
        scrollY: 300,
        "serverSide": true,
		"ajax": {
			"url": "{{ $tableRoute }}", // ajax source
			"type": "GET"
		},
		"columns": [
		    { "data": "goals.id", "name": "id"  },
		    { "data": "goals.name", "name": "name"  },
		    { "data": "goals.active", "name": "active"  },
		    { "data": "goals.actions", "name": "actions", "sortable": false, "className": 'text-center'},
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