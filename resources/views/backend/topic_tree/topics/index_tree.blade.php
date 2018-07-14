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
				<a href="{{ route('admin::topic_tree::topics.create') }}" class="btn btn-link btn-float has-text"><i class="icon-plus2 text-primary"></i><span>Add New Topic</span></a>
			</div>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

	<div class="panel panel-flat">
		<div class="panel-heading">
			<h6 class="panel-title">Table tree</h6>
			<div class="heading-elements">
				<ul class="icons-list">
            		<li><a data-action="collapse"></a></li>
            		<li><a data-action="reload"></a></li>
            		<li><a data-action="close"></a></li>
            	</ul>
        	</div>
		</div>

		<div class="panel-body">
			<div class="tree-ajax well border-left-info border-left-lg"></div> 
		</div>

	</div>

@stop


@section('scripts')
@parent

<script type="text/javascript">

var tree;

$(document).ready(function(){

    tree = $(".tree-ajax").fancytree({
    	extensions: ["dnd","persist","childcounter","table"],
    	childcounter: {
	        deep: true,
	        hideZeros: true,
	        hideExpanded: false
	    },
        persist: {
            overrideSource: false, // true: cookie takes precedence over `source` data attributes.
        },
        source: {
            url: "{{ route('admin::topic_tree::topics.table_tree') }}" + '?parent_id=' + 0
        },
        lazyLoad: function(event, data) {
            data.result = {url: "{{ route('admin::topic_tree::topics.table_tree') }}" + '?parent_id=' + data.node.key}
        },
        loadChildren: function(event, data) {
	        // update node and parent counters after lazy loading
	        data.node.updateCounters();
	    },
        click: function(node,data){
        	console.log('Clicked',data.node.key);
        },
        dnd: {
            autoExpandMS: 400,
            focusOnClick: true,
            preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
            preventRecursiveMoves: true, // Prevent dropping nodes on own descendants
            dragStart: function(node, data) {
            	console.log('dragStart');
                return true;
            },
            dragEnter: function(node, data) {
            	console.log('dragEnter');
                return true;
            },
            dragDrop: function(node, data) {
            	console.log('dragDrop');
            	console.log(data.otherNode);
                // This function MUST be defined to enable dropping of items on the tree.
				node.setExpanded(true).always(function(){
				    // Wait until expand finished, then add the additional child
				    data.otherNode.moveTo(node, data.hitMode);
			    });
            }
        },
        init: function(event, data) {
            $('.has-tooltip .fancytree-title').tooltip();
        }
    });

});
</script>

@stop