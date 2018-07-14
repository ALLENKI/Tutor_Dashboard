@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Manage Permissions</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::users.index') }}">Users</a></li>
				<li class="active">{{ $user->name }} ({{$user->email}})</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop

@section('content')

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

		{!! BootForm::openHorizontal($columnSizes)->action( route('admin::users::permissions.manage',$user->id)) !!}


		@if($loggedInUser->id == $user->id)
		<div class="alert alert-danger alert-styled-left alert-arrow-left alert-component">
			<h6 class="alert-heading text-semibold">Sorry, you cannot manage your own permissions.</h6>
		</div>
		@endif


		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

            <?php 

              $checked = '';

              if($user->isSuperUser())
              {
                  $checked = 'checked';
              }

            ?>

          <label>
              <input name="superuser" type="checkbox" value="1" {{ $checked }}>
              Is Super Admin
          </label>

          	<hr>

          	<h4>Global Permissions</h4>

            @foreach($permissions['global'] as $permission)

            <?php 

              $checked = '';

              if($user->can($permission['permission']))
              {
                  $checked = 'checked';
              }

            ?>

            <div class="checkbox">
                <label>
                    <input name="global[{{ $permission['permission'] }}]" type="checkbox" value="1" {{ $checked }}>
                    {{ $permission['description'] }}
                    <small>({{ $permission['group'] }}  - {{ $permission['permission'] }})</small>
                </label>
            </div>


            @endforeach

            <hr>

            @foreach($locations as $location)

            	<h5>Permissions for Location: {{ $location->name }}</h5>

            	@foreach($permissions['location'] as $permission)

	            <?php 

	              $checked = '';

	              if($user->can($permission['permission'], $location->id))
	              {
	                  $checked = 'checked';
	              }

	            ?>
	              <div class="checkbox">
	                  <label>
	                      <input name="location[{{$location->id}}][{{ $permission['permission'] }}]" type="checkbox" value="1" {{ $checked }}>
	                      {{ $permission['description'] }}
	                      <small>({{ $permission['group'] }} - {{ $permission['permission'] }})</small>
	                  </label>
	              </div>

	            @endforeach

	            <hr>

            @endforeach

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}

	</div>
</div>

@stop