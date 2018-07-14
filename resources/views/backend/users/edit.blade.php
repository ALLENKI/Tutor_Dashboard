@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Users</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::users.index') }}">Users</a></li>
				<li><a href="{{ route('admin::users::users.show',$user->id) }}">{{ $user->email }}</a></li>
				<li class="active">Edit User - {{ $user->email }}</li>
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
		<h5 class="panel-title">Edit User - {{ $user->email }}</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 6]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::users::users.update',$user->id)) !!}


		{!! BootForm::text('Name', 'name')
		                    ->placeholder('Name')
		                    ->attribute('required','true')
		                    ->attribute('value',Input::old('name',$user->name)) !!}


		{!! BootForm::select('Interested In?', 'interested_in')
		            ->options([
		                        'user' => 'Volunteer', 
		                        'student' => 'Student',
		                        'teacher' => 'Teacher',
		                        'student_teacher' => 'Student & Teacher'
		                      ])
		            ->select(Input::old('interested_in',$user->interested_in))
		            ->placeholder('Interested In?')
		            ->attribute('required','true') !!}


		  @if($user->student)

		  <h6 class="m-top-20" style="color: #006696;">Student Profile</h6>
		  <hr>

		  {!! BootForm::text('Grade', 'grade')
		                        ->attribute('value',Input::old('grade',$user->student->grade)) !!}

		  {!! BootForm::text('Curriculum', 'curriculum')
		                        ->attribute('value',Input::old('curriculum',$user->student->curriculum)) !!}
		  @endif

		  @if($user->teacher)

		  <h6 class="m-top-20" style="color: #006696;">Teacher Profile</h6>
		  <hr>

		<div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label" for="free">
		</label>
		<div class="col-sm-8 col-lg-10">

			<label class="checkbox-inline">
				<input type="checkbox" class="styled" name="ignore_calendar" value="yes" {{ $user->teacher->ignore_calendar ? 'checked' : '' }}>
				Ignore Calendar 
			</label>

		</div>
		</div>

		<div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label" for="free">
		</label>
		<div class="col-sm-8 col-lg-10">

			<label class="checkbox-inline">
				<input type="checkbox" class="styled" name="show_on_homepage" value="yes" {{ $user->teacher->show_on_homepage ? 'checked' : '' }}>
				Show on homepage
			</label>

		</div>
		</div>


		  {!! BootForm::text('Commission', 'commission')
		                        ->attribute('value',Input::old('commission',$user->teacher->commission))
		                        ->attribute('type','number') !!}

		  @endif

		  <hr>
              
		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->

@stop


@section('scripts')
@parent

<script type="text/javascript">


$(document).ready(function(){

// $('#parent_id').select2();

});
</script>

@stop