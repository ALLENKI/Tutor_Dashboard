@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Student Payments</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Student Payments</li>
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
		<h5 class="panel-title">List of Student Payments</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>
        		<li><a href="{{ route('admin::users::payments.export') }}" class"btn btn-xs">Export</a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">
		This Table Shows all the Payments with Aham. 
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>#</th>
				<th class="text-center" width="10%">Actions</th>
				<th>Date</th>
				<th>Amount</th>
				<th>Invoice No.</th>
				<th>Email</th>
				<th>Student Name</th>
				<th>Payment Method</th>
				<th>Remarks</th>
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

    // $.extend( $.fn.dataTable.defaults, {
    //     autoWidth: false,
    //     dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
    //     language: {
    //         search: '<span>Filter:</span> _INPUT_',
    //         lengthMenu: '<span>Show:</span> _MENU_',
    //     }
    // });

    $.extend( $.fn.dataTable.defaults, {
        autoWidth: false,
        dom: '<"datatable-header"f<"toolbar">l><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Filter:</span> _INPUT_',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
        }
    });


    // Basic datatable
    table =  $('#myTable').DataTable({
    	"order": [[ 6, "desc" ]],
        scrollY: 300,
        scrollX: true,
        "serverSide": true,
        "drawCallback": function( settings, json ) {
		    $(".rest").restfulizer();
		},
		
		"ajax": {
			"url": "{{ $tableRoute }}", // ajax source
			"type": "GET",
            "data": function (d) {
                var types = $('.toolbar #method option:selected').get().map(function(a, item){return a.value;})

                d['method'] = types;
            },
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
		    { "data": "payments.id", "name": "id"  },
		    { "data": "payments.actions", "name": "actions", "sortable": false, "className": 'text-center'},
		    { "data": "payments.created_at", "name": "created_at"  },
		    { "data": "payments.amount_paid", "name": "amount_paid"  },
		    { "data": "payments.invoice_no", "name": "invoice_no"  },
		    { "data": "payments.email", "name": "email"  },
		    { "data": "payments.student_id", "name": "student_id"  },
		    { "data": "payments.method", "name": "method"  },
		    { "data": "payments.remarks", "name": "remarks"  },
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

    //Search-by filter-payment method

    $("div.toolbar").append('Type : <select class="multiselect" id="method" multiple="multiple"></select>');

    $('#method').multiselect({
        onChange: function() {
            $.uniform.update();
            table.ajax.reload();
        }
    });

    var options = [
        {label: 'Cash', title: 'Cash', value: 'cash', selected: 'true'},
        {label: 'Cheque', title: 'Cheque', value: 'cheque', selected: 'true'},
        {label: 'Online Payment', title: 'Online Payment', value: 'online_payment', selected: 'true'},
        {label: 'Pending', title: 'Pending', value: 'pending', selected: 'true'},
        {label: 'Online Transfer', title: 'Online Transfer', value: 'online_transfer', selected: 'true'}
    ];

    $('#method').multiselect('dataprovider', options);

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

    table.ajax.reload();


    //Search-by filter-payment method

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
