@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Users</span> - Coupon Templates</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::coupon_templates.index') }}">Coupon Templates</a></li>
				<li class="active">Create New Coupon Template</li>
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
		<h5 class="panel-title">Coupon Template for {{ $template->coupon }}</h5>

        <div class="heading-elements">

        	<a href="{{ route('admin::users::coupon_templates.generate',$template->id) }}" class="btn btn-info heading-btn">Generate Coupons</a>

            @if($template->active)
            <a href="{{ route('admin::users::coupon_templates.status',[$template->id, 'inactive' => true]) }}" data-method='POST' class="rest btn btn-warning heading-btn">Deactivate</a>
            @else
            <a href="{{ route('admin::users::coupon_templates.status',[$template->id, 'active' => true]) }}" data-method='POST' class="rest btn btn-success heading-btn">Activate</a>
            @endif

        </div>

	</div>

	<div class="panel-body">

	</div>

</div>
<!-- /basic datatable -->

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Add Users (Issuance Limit: {{ $template->issuance_limit }})</h5>
	</div>

	<div class="panel-body">

		@if($template->users->count() >= $template->issuance_limit)

		<div class="alert alert-danger alert-styled-left alert-arrow-left alert-component">
			<h6 class="alert-heading text-semibold">You have reached issuance limit.
		</div>
		@else

		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! 
			BootForm::openHorizontal($columnSizes)
			->action(route('admin::users::coupon_templates.add_user',$template->id)) 
		!!}


		<div class="col-md-8">
			{!! BootForm::select('User *', 'user_id[]')
						->options($users)
						->attribute('required','true')
						->attribute('id','user_id')
						->attribute('class','form-control multiselect')
						->attribute('multiple','multiple')
			!!}
		</div>

		<button type="submit" class="btn btn-primary pull-right">Add <i class="icon-arrow-right14 position-right"></i></button>

		{!! BootForm::close() !!}

		@endif

		<div class="clearfix"></div>

		@if($template->users->count())

		<table class="table table-framed">

			<thead>
				<tr>
					<th>ID</th>
					<th>Code</th>
					<th>User</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				@foreach($template->users as $user)
				<tr>
					<td>{{ $user->id }}</td>
					<td>{{ $user->student->code }}</td>
					<td> {{ $user->name }} ({{ $user->email }})</td>
					<td>
						<a href="{{ route('admin::users::coupon_templates.remove_user',[$template->id,$user->id]) }}" data-method='POST' class="rest btn btn-xs btn-danger heading-btn">Remove</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		@endif


	</div>

</div>

@stop

@section('styles')
@parent
<style type="text/css">
.btn-group{
	width: 100%;
}

.select2 {
	background: #ececec;;
}

</style>

@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){

	// $('#teacher_id').select2();

    $('.multiselect').select2({
        // onChange: function() {
        //     $.uniform.update();
		// }
		placeholder: 'None'
    });

    // $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});



});
</script>
@stop