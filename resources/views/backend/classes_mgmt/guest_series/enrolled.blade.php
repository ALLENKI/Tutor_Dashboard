@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Classes Mgmt</span> - Guest Series</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::classes_mgmt::guest_series.index') }}">Guest Series</a></li>
				<li class="active">Guest Series - {{ $episode->series->name }}</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')


<div class="row">

	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="panel-title">#{{$episode->series->id}}: {{$episode->series->name}} ({{$episode->series->code}})</h5>

			</div>

			<div class="panel-body">

				<table class="table">
					<thead>
						<th>Name</th>
						<th>Email</th>
						<th>Enrolled On</th>
					</thead>

					<tbody>
						@foreach($episode->enrollments as $enrollment)
						<tr>
							<td>{{ $enrollment->user->name }}</td>
							<td>{{ $enrollment->user->email }}</td>
							<td>{{ $enrollment->created_at->format('jS M Y H:i A') }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				
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

				<ul class="list-unstyled">
					<li>Enrolled: {{ $episode->enrollments->count() }}</li>
					<li>Limit: {{ $episode->enrollment_limit }}</li>
					<li>Name: {{ $episode->name }}</li>
					<li>Date: {{ $episode->date_summary }}</li>
					<li>Time: {{ $episode->time_summary }}</li>
				</ul>



			</div>
		</div>


	</div>

</div>


@stop

@section('styles')
@parent
<style type="text/css">

</style>
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