@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Classes Mgmt</span> - Class - {{ $groupClass->id }}</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::classes_mgmt::group_classes.index') }}">Group Classes</a></li>
				<li class="active">{{ $groupClass->id }}</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

@foreach($classes as $ahamClass)

@if($ahamClass->status == 'cancelled')
<div class="alert alert-danger alert-styled-left alert-arrow-left alert-component">
	<h6 class="alert-heading text-semibold">This class is cancelled because of following reason:</h6>
	{{ $ahamClass->cancellation_reason }}
</div>
@endif

<div class="row">

	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="panel-title">#{{$ahamClass->id}}: {{$ahamClass->topic->name}} ({{$ahamClass->code}})</h5>

				@if($ahamClass->status != 'cancelled' && $ahamClass->status != 'closed')
				<div class="heading-elements">
					<a href="{{ route('admin::classes_mgmt::classes.edit',$ahamClass->id) }}" class="btn btn-primary heading-btn">Edit</a>
            	</div>
            	@endif

			</div>

			<?php 

				$eligibleTeachers = \Aham\Helpers\TeacherHelper::eligibleTeachers($ahamClass);
		        $certifiedTeachers = \Aham\Models\SQL\TeacherCertification::with('teacher.classes','teacher.user')
                                ->where('topic_id', $ahamClass->topic->id)
                                ->get();
			?>

			<div class="panel-body">
				@include('backend.classes_mgmt.classes._calendar')
				@include('backend.classes_mgmt.classes._invitations')
				@include('backend.classes_mgmt.classes._enrollments')
			</div>


		</div>

	</div>

	<div class="col-lg-4">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">Snapshot</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="collapse"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">

				@include('backend.classes_mgmt.classes._summary')
				
				@if($ahamClass->status == 'initiated' || $ahamClass->status == 'created' || $ahamClass->status == 'invited' || $ahamClass->status == 'accepted' || $ahamClass->status == 'ready_for_enrollment')
				<hr>

				<a href="{{ route('admin::classes_mgmt::classes.reinitiate',$ahamClass->id) }}" data-method='POST' class="rest btn btn-danger btn-block legitRipple">
					REINITIATE
				</a>
				@endif

			</div>
		</div>

	</div>

</div>

<hr>

@endforeach

@stop


@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){

    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});



});
</script>

@stop