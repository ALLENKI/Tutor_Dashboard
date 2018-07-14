@extends('frontend.layouts.master')


@section('content')

@include('frontend.teacher_dashboard.top_bar')

<!-- SUB BANNER -->
<section class="sub-banner sub-banner-course">
    <div class="awe-static bg-sub-banner-course"></div>
    <div class="container">
        <div class="sub-banner-content">
            <h2 class="text-center">Invitation to teach {{ $invitation->ahamClass->topic->name }}</h2>
        </div>
    </div>
</section>
<!-- END / SUB BANNER -->


<section class="course-top">
<?php $topic = $invitation->ahamClass->topic ?>

<div class="container">
<div class="row">

	<div class="col-md-6">

	<h4 class="sm black bold">Introduction</h4>
	<p>
		{{ $topic->description }}
	</p>

	<h4 class="sm black bold">Units</h4>

	@foreach($topic->units as $unit)
		<h5 class="sm black bold">{{ $unit->name }}</h5>
		<p>{{ $unit->description }}</p>
	@endforeach

	</div>

	<div class="col-md-6">
		<h4 class="sm black bold">Timings</h4>

		<?php $ahamClass = $invitation->ahamClass; ?>

			<table class="table table-framed">

			<thead>
				<tr>
					<th>Unit</th>
					<th>Date and Time</th>
				</tr>
			</thead>

			<tbody>
				@foreach($ahamClass->topic->units as $unit)
				<tr>
					<td>
						{{ $unit->name }}
					</td>
					<td>
						{{ schedule($unit->id,$ahamClass->id) }}
					</td>
				</tr>
				@endforeach
			</tbody>

		</table>

		<hr>

		@if($invitation->status == 'pending')

		<div class="row">
			<div class="col-md-6">
				<a href="{{ route('teacher_dashboard::invitations.accept',$invitation->id) }}" class="btn btn-success">Accept</a>
				<a href="{{ route('teacher_dashboard::invitations.reject',$invitation->id) }}" class="btn btn-danger">Reject</a>
			</div>

			<div class="col-md-6">
				{!! BootForm::open()->action(route('teacher_dashboard::invitations.propose',$invitation->id)) !!}

				<p>Propose alternate timings</p>

	            {!! BootForm::text('Available Dates', 'date_range')
	                        ->hideLabel()
	                        ->placeholder('Available Dates')
	                        ->attribute('required','true') 
	                        ->attribute('class','form-control date-range') 
	            !!}

				<div class="form-submit-1">
                    <input type="submit" value="Submit" class="mc-btn btn-style-1">
                </div>

				{!! BootForm::close() !!}
			</div>

		</div>

		@endif

	</div>

</div>
</div>

</section>


@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){


$('.date-range').daterangepicker();

});
</script>
@stop