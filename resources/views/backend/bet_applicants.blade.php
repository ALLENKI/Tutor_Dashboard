@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Aham Dashboard</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
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
		<h5 class="panel-title">List of Users</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        		<li>
        			<a href="{{ route('admin::users::bet_applicants.export') }}">Download</a>
        		</li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">
		This Table Shows all the Users registered with Aham's BET program. Please download the CSV file to get all the details of the applicants.
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>Full Name</th>
				<th>Age</th>
				<th>School</th>
				<th>Email</th>
				<th>Mobile</th>
				<th>Address</th>
				{{-- <th>Other Programs</th>
				<th>Programming Exp</th>
				<th>Business Vertical</th>
				<th>Summer Exp</th>
				<th>Fav Books/Authors</th>
				<th>Challenges</th> --}}
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<!-- /basic datatable -->

@stop

@section('styles')
@parent

	<style>
		


	</style>

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
        initComplete: function() {
	        $('#myTable_filter input').unbind();
	        $('#myTable_filter input').bind('keyup', function(e) {
	            if(e.keyCode == 13) {
	                table.search( this.value ).draw();
	            }
	        });
	    },
		"ajax": {
			"url": "{{ $tableRoute }}", // ajax source
			"type": "GET"
		},
		"columns": [
		    { "data": "bet_applicants.full_name", "name": "full_name"  },
		    { "data": "bet_applicants.age", "name": "age"  },
		    { "data": "bet_applicants.school", "name": "school"  },
		    { "data": "bet_applicants.email", "name": "email"  },
		    { "data": "bet_applicants.mobile", "name": "mobile"},
		    { "data": "bet_applicants.address", "name": "address"},
		    // { "data": "bet_applicants.other_programs", "name": "other_programs"},
		    // { "data": "bet_applicants.programming_exp", "name": "programming_exp"},
		    // { "data": "bet_applicants.business_vertical", "name": "business_vertical"},
		    // { "data": "bet_applicants.summer_exp", "name": "summer_exp"},
		    // { "data": "bet_applicants.fav_books", "name": "fav_books"},
		    // { "data": "bet_applicants.challenge", "name": "challenge"},
		    
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