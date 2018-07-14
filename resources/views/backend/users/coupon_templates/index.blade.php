@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Coupon Templates</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Coupon Templates</li>
			</ul>
		</div>

		<div class="heading-elements">
			<div class="heading-btn-group">
				<a href="{{ route('admin::users::coupon_templates.create') }}" class="btn btn-link btn-float has-text"><i class="icon-plus2 text-primary"></i><span>Create New Coupon Template</span></a>
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
		<h5 class="panel-title">List of Coupon Templates</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        	</ul>
    	</div>
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>Coupon</th>
				<th class="text-center" width="10%">Actions</th>
				<th>Active</th>
				<th>Valid From</th>
				<th>Valid Till</th>
				<th>Offer Type</th>
				<th>Offer Value</th>
				<th>Per User Limit</th>
				<th>Issuance Limit</th>
				<th>Remaining Coupons</th>
				<th>Max Users</th>
				<th>Min. Units</th>
				
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
		    { "data": "coupons.coupon", "name": "coupon"  },
		    { "data": "coupons.actions", "name": "actions", "sortable": false, "className": 'text-center'},
		    { "data": "coupons.active", "name": "active"  },
		    { "data": "coupons.valid_from", "name": "valid_from"  },
		    { "data": "coupons.valid_till", "name": "valid_till"  },
		    { "data": "coupons.additional_type", "name": "additional_type"  },
		    { "data": "coupons.additional_value", "name": "additional_value"},
		    { "data": "coupons.max_usage_limit_per_user", "name": "max_usage_limit_per_user"},
		    { "data": "coupons.issuance_limit", "name": "issuance_limit"},
		    { "data": "coupons.remaining_coupons", "name": "remaining_coupons", "sortable": false},
		    { "data": "coupons.max_users_limit", "name": "max_users_limit"},
		    { "data": "coupons.min_units_to_purchase", "name": "min_units_to_purchase"},
		    
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