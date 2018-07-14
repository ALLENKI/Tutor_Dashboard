@extends('dashboard.student.layouts.master')

@section('content')

<div class="page-header page-header-inverse has-cover">
	<div class="page-header-content">
		<div class="page-title" style="padding: 22px 36px 22px 0;">
<!-- Quick stats boxes -->
		<div class="row">
			<div class="col-lg-4">

				<!-- Members online -->
				<div class="panel bg-teal-400">
					<div class="panel-body">
						<h3 class="no-margin">{{ $student->user->creditBuckets()->sum('total_remaining') }}</h3>
						<span style="font-size:14px;">Available Credits To Take Classes</span>
					</div>

					<div class="container-fluid">
						<div id="members-online"></div>
					</div>
				</div>
				<!-- /members online -->

			</div>

			{{-- <div class="col-lg-4">

				<!-- Current server load -->
				<div class="panel bg-pink-400">
					<div class="panel-body">
						
						<h3 class="no-margin">{{ $freeCredits }}</h3>

						<span style="font-size:14px;">Free Credits Awarded So Far</span>
					</div>

					<div id="server-load"></div>
				</div>
				<!-- /current server load -->

			</div> --}}

			{{-- <div class="col-lg-4">

				<!-- Today's revenue -->
				<div class="panel bg-blue-400">
					<div class="panel-body">
						<h3 class="no-margin">{{ $completedTimings->count() }}</h3>
						<span style="font-size:14px;">Completed Classes So Far</span>
					</div>

					<div id="today-revenue"></div>
				</div>
				<!-- /today's revenue -->

			</div> --}}
		</div>
		<!-- /quick stats boxes -->
		</div>
	</div>
</div>

<div class="row">

    <div class="col-md-12 panel panel-white">
        <div class="panel-body">
            <div id="calendar">
            </div>
        </div>
    </div>


	<div class="col-md-6">
		
		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title"><strong> Ongoing Classes </strong></h6>
			</div>

			<div class="panel-body">

			@if($ongoingTimings->count())

					@foreach($ongoingTimings as $classTiming)
					@include('dashboard.student.snippets._common_class_unit_snippet')
				@endforeach
			
			@else
			
			<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no on-going classes</h4>

			@endif

			</div>

		</div>

	</div>

	<div class="col-md-6">


		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title"><strong> Upcoming Classes </strong></h6>
			</div>

			<div class="panel-body">

			@if($upcomingTimings->count())
			
				@foreach($upcomingTimings as $classTiming)
					@include('dashboard.student.snippets._common_class_unit_snippet')
				@endforeach
			
			@else
			
			<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no upcoming classes</h4>

			@endif

		</div>

	</div>

</div>
@stop

@section('styles')
@parent
	
	<style>
		.fc-icon-left-single-arrow:after{
			font-size: 265% !important;
		    top: -7px !important;
		}

		.fc-icon-right-single-arrow:after{
			font-size: 265% !important;
		    top: -7px !important;
		}

		.fc-icon{
			height: 30px;
		}

		.fc button .fc-icon {
			top: -4px;
		}
	</style>

@stop

@section('scripts')
@parent
<script>
$(document).ready(function(){

    $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today month',
                    center: 'title',
                    right: ''
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week: 'week',
                    day: 'day'
                },
                dayClick: function(date, jsEvent, view) {
                    if (view.name === "month") {
                        $('#calendar').fullCalendar('gotoDate', date);
                        $('#calendar').fullCalendar('changeView', 'agendaDay');
                    }
                },
                eventClick: function(calEvent, jsEvent, view) {

                    console.log('Event:',calEvent.slug);
                    console.log('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                    console.log('View: ' + view.name);
                    window.location.href = calEvent.slug;

                },
                eventMouseOver: function(event,jsEvent, view) {
                      $(this).popover({
                          trigger: 'hover',

                              html: true,
                              content: function() {
                              return event.title;
                          },
                          container: 'body',
                          placement: 'right'
                      });

                },
                 //Random events
                <?php $url = route('student::ahamCalendar') ?>
                events: "<?php echo $url; ?>",

        })
});
</script>
@stop
