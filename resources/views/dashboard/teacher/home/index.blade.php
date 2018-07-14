@extends('dashboard.teacher.layouts.master')

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
						<h3 class="no-margin">Rs. {{ inrFormat($teacher->allEarnings->sum('actual_amount')) }}/-</h3>
						<span style="font-size:14px;">Total amount received so far</span>
					</div>

					<div class="container-fluid">
						<div id="members-online"></div>
					</div>
				</div>
				<!-- /members online -->

			</div>

			<div class="col-lg-4">

				<!-- Current server load -->
				<div class="panel bg-pink-400">
					<div class="panel-body">
						
						<h3 class="no-margin">Rs. {{ inrFormat($teacher->earnings-$teacher->allEarnings->sum('actual_amount')) }}/-</h3>

						<span style="font-size:14px;">Pending payout amount</span>
					</div>

					<div id="server-load"></div>
				</div>
				<!-- /current server load -->

			</div>

			<div class="col-lg-4">

				<!-- Today's revenue -->
				<div class="panel bg-blue-400">
					<div class="panel-body">
						<h3 class="no-margin">Rs. {{ inrFormat($projectedAmount) }}/-</h3>
						<span style="font-size:14px;">Projected amount</span>
					</div>

					<div id="today-revenue"></div>
				</div>
				<!-- /today's revenue -->

			</div>
		</div>
		<!-- /quick stats boxes -->
		</div>
	</div>
</div>

    <div class="panel panel-white">
        <div class="panel-body">
            <div id="calendar">
            </div>
        </div>
    </div>

<div class="row">

		<div class="col-md-6">
	
		<div class="panel panel-white">

			<div class="panel-heading">
				<h6 class="panel-title"><strong>New Invitations</strong></h6>
			</div>

			<div class="panel-body">

				@if($newInvitations->count())

					@foreach($newInvitations as $newInvite)
						@include('dashboard.teacher.snippets._new_invite')
					@endforeach	

				@else
					<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no new invitations.</h4>
				@endif

			</div>
			
		</div>

	</div>


	<div class="col-md-6">
	
		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title"><strong>Waiting for Feedback</strong></h6>
			</div>

			<div class="panel-body">

				@if($feedbackClasses->count())

					@foreach($feedbackClasses as $feedbackClass)
						@include('dashboard.teacher.snippets._feedback_class')
					@endforeach	

				@else
					<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no pending feedbacks.</h4>
				@endif

			</div>
			
		</div>

	</div>

    <?php 
         $url = route('teacher::ahamCalendar');
    ?>

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

    let getData = "<?php echo $url; ?>";

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
                    console.log(view);
                    if (view.name === "month") {
                        $('#calendar').fullCalendar('gotoDate', date);
                        $('#calendar').fullCalendar('changeView', 'agendaDay');
                    }
                },
                eventClick: function(calEvent, jsEvent, view) {

                    console.log('Event:',calEvent)
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
                events: getData,
        })
});
</script>
@stop
