@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Topic Tree</span> - Topics</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Topics</li>
			</ul>
		</div>

		<div class="heading-elements">
			<div class="heading-btn-group">

				<a href="{{ route('admin::topic_tree::topics.index_d3') }}" class="btn btn-link btn-float has-text"><i class="icon-tree5 text-primary"></i><span>Graph Visualization</span></a>

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
		<h5 class="panel-title">List of Topics</h5>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="reload"></a></li>

        		@if($loggedInUser->can('topic_tree.create'))
        		<li>
				<div class="btn-group">
                	<button type="button" class="btn bg-teal-400 btn-labeled dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><b><i class="icon-plus2"></i></b> Add New <span class="caret"></span></button>
                	<ul class="dropdown-menu dropdown-menu-right">
						<li><a href="{{ route('admin::topic_tree::topics.create',['type' => 'topic']) }}"><i class="icon-menu7"></i> Topic</a></li>
						<li><a href="{{ route('admin::topic_tree::topics.create',['type' => 'sub-category']) }}"><i class="icon-menu7"></i> Sub Category</a></li>
						<li><a href="{{ route('admin::topic_tree::topics.create',['type' => 'subject']) }}"><i class="icon-menu7"></i> Subject</a></li>
						<li><a href="{{ route('admin::topic_tree::topics.create',['type' => 'category']) }}"><i class="icon-menu7"></i> Category</a></li>
					</ul>
				</div>
        		</li>
        		@endif

        	</ul>
    	</div>
	</div>

	<div class="panel-body">
	This Table Shows all the Topics in the System. 
	</div>

	<table class="table datatable-basic" id="myTable">
		<thead>
			<tr>
				<th>#</th>
				<th>Name</th>
				<th>Slug</th>
				<th>Type</th>
				<th>Level</th>
				<th>Status</th>
				<th>Units</th>
				<th>Assessed</th>
				<th>Certified</th>
				<th>Code</th>
				<th>Created On</th>
				<th class="text-center" width="10%">Actions</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

	<div class="panel-body" style="display: none;">
		
		<hr>


		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! 
			BootForm::openHorizontal($columnSizes)
					->action(route('admin::topic_tree::topics.upload_dump')) 
					->attribute('enctype',"multipart/form-data")
		!!}

	    {!! 
	    	BootForm::text('File *', 'file')
	          ->placeholder('Name')
	          ->attribute('required','false') 
	          ->attribute('type','file') 
	    !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}

	</div>
	

</div>
<!-- /basic datatable -->

@stop

@section('styles')
@parent
<style type="text/css">
.dataTables_filter {
    margin: 0 20px 20px 20px;
}
.toolbar {
    display: inline;
}
</style>
@stop


@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){

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
    	"order": [[ 0, "asc" ]],
        autoWidth: true,
        scrollY: 300,
        scrollX: true,
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
			"type": "GET",
            "data": function (d) {
                var types = $('.toolbar #topic_type option:selected').get().map(function(a, item){return a.value;})

                d['topic_type'] = types;
            },
		},
		"columns": [
		    { "data": "topics.id", "name": "id"  },
		    { "data": "topics.name", "name": "name"  },
		    { "data": "topics.slug", "name": "slug"  },
		    { "data": "topics.type", "name": "type"  },
		    { "data": "topics.graph_level", "name": "graph_level"  },
		    { "data": "topics.status", "name": "status"  },
		    { "data": "topics.units", "name": "units", "sortable": false  },
		    { "data": "topics.assessed", "name": "assessed", "sortable": false  },
		    { "data": "topics.certified", "name": "certified", "sortable": false  },
		    { "data": "topics.code", "name": "code"  },
		    { "data": "topics.created_at", "name": "created_at"},
		    { "data": "topics.actions", "name": "actions", "sortable": false, "className": 'text-center'},
		],
        buttons: [
            {
                text: 'Download Excel',
                className: 'btn bg-teal-400',
                action: function(e, dt, node, config) {

                	var oParams = dt.ajax.params();

        	        var iframe = document.createElement('iframe');
			        iframe.style.height = "0px";
			        iframe.style.width = "0px";
			        iframe.src = "{{ $tableRoute }}"+"?"+$.param(oParams)+'&o=csv';
			        document.body.appendChild( iframe );


                    console.log(config);
                }
            }
        ]
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

    $("div.toolbar").append('Type : <select class="multiselect" id="topic_type" multiple="multiple"></select>');

    $('#topic_type').multiselect({
        onChange: function() {
            $.uniform.update();
            table.ajax.reload();
        }
    });

    var options = [
        {label: 'Category', title: 'Category', value: 'category'},
        {label: 'Subject', title: 'Subject', value: 'subject'},
        {label: 'Sub Category', title: 'Sub Category', value: 'sub-category'},
        {label: 'Topic', title: 'Topic', value: 'topic'},
    ];

    $('#topic_type').multiselect('dataprovider', options);

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