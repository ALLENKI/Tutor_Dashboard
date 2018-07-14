@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Student Credits</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::users.index') }}">Users</a></li>
				<li><a href="{{ route('admin::users::users.show',$user->id) }}">{{ $user->name }} ({{$user->email}})</a></li>
				<li class="active">Student: {{ $student->code }} ({{ $student->goals->count() }})</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop

@section('content')


<div class="panel panel-primary">
    <div class="panel-heading">
        <h5 class="panel-title">Goals</h5>
    </div>

    <table class="table">
        <thead>
            <th>Name</th>
            <th>Actions</th>
        </thead>

        <tbody>
            @foreach($student->goals as $goal)
            <tr>
                <td>
                    {{ $goal->name }}
                </td>
                <td>
                    <a href='{{ route('admin::users::students.goals.delete',[$student->id, $goal->id]) }}' data-method='DELETE' class="rest">
                        <i class='icon-cross'></i>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
<!-- Basic datatable -->
<div class="panel panel-warning">
	<div class="panel-heading">
		<h5 class="panel-title">Add Goals</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::users::students.goals',$student->id)) !!}

        {!! 

        	BootForm::select('Goal', 'goal_id')
                    ->options($goals)
                    ->attribute('required','true')
        !!}

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

$(function(){

	$('select').select2();

});

</script>
@stop