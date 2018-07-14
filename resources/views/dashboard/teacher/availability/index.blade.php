@extends('dashboard.teacher.layouts.master')

@section('content')
	
	<div class="panel panel-default">

		<div class="panel-heading">
			<h6 class="panel-title" style="font-size: 18px;"><strong>My Availability</strong></h6>

			<div class="heading-elements">
				<div class="checkbox">
					<label>
						<input type="checkbox" name="ignore_calendar" class="availability styled" {{ $user->teacher->ignore_calendar ? 'checked' : '' }}>
						Ignore this Calendar
					</label>
				</div>
			</div>

		</div>
		<div class="panel-body">

		<div class="row">

			{!! BootForm::open()->action(route('teacher::availability')) !!}

			<div class="col-md-3">
				{!! BootForm::select('Day Type *', 'day_of_week[]')
							->options($daysOfTheWeek)
							->attribute('required','true')
							->attribute('class','form-control multiselect')
							->attribute('multiple','multiple')

				!!}
			</div>

			<div class="col-md-3">
				{!! BootForm::text('From Date *', 'from_date')
				              ->placeholder('From Date')
				              ->attribute('id','from_date')
				              ->attribute('required','true') !!}
			</div>

			<div class="col-md-3">
				{!! BootForm::text('To Date *', 'to_date')
				              ->placeholder('To Date')
				              ->attribute('id','to_date')
				              ->attribute('disabled','true')
				              ->attribute('required','true') !!}
			</div>


			<div class="col-md-3">
				{!! BootForm::select('Session *', 'session[]')
							->options($sessions)
							->attribute('required','true')
							->attribute('class','form-control multiselect')
							->attribute('multiple','multiple')
				!!}
			</div>

			<div class="col-md-12">

			<div class="text-right">
				<button type="submit" class="btn btn-success">Add <i class="icon-arrow-right14 position-right"></i></button>
			</div>

			</div>

			{!! BootForm::close() !!}

			<div class="clearfix"></div>

			<hr>

		</div>


		</div>
	</div>


	<div class="panel panel-default">
		<table class="table">
			<thead>
				<tr style="color: #fff; font-size: 13px; background: #0f74cc; text-transform: uppercase;">
					<th>Day of The Week</th>
					<th>From Date</th>
					<th>To Date</th>
					<th>Early Morning</th>
					<th>Morning</th>
					<th>Afternoon</th>
					<th>Evening</th>
					<th>Late Evening</th>
					<th></th>
				</tr>
			</thead>

			<tbody>
				@foreach($availabilities as $availability)
				<tr>
					<td>{{ ucwords($availability->day_of_the_week) }}</td>
					<td>{{ $availability->from_date->format('jS M Y') }}</td>
					<td>{{ $availability->to_date->format('jS M Y') }}</td>
					@foreach($sessions as $key => $session)
					@if($availability->$key)
					<td>Yes</td>
					@else
					<td>No</td>
					@endif
					@endforeach
					<td>
						<a href="{{ route('teacher::availability.delete',$availability->id) }}" data-method="DELETE" class="rest">X</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>



	<div id="calendar-wrap">
    		<header>
    			<h1>{{ $currentMonth }}</h1>
    		</header>
    		<div id="calendar">
    			<ul class="weekdays">
    				<li>Sunday</li>
    				<li>Monday</li>
    				<li>Tuesday</li>
    				<li>Wednesday</li>
    				<li>Thursday</li>
    				<li>Friday</li>
    				<li>Saturday</li>
    			</ul>

    			<!-- Days from previous month -->

    			@for($i = 1; $i <= $weeksInMonth; $i++)
    			<ul class="days">
    				@for($j = 0; $j < 7; $j++)

    					@if($tempArray[$i][$j] <= 0 || $tempArray[$i][$j] > $daysInMonth)
						<li class="day other-month">    					
    					</li>
    					@else
						<li class="day">
    						<div class="date">{{ $tempArray[$i][$j] }}</div>

    						@foreach($sessions as $key => $session)

    						<?php
    							$active = false;

    							if(isset($availabilityForMonth[$tempArray[$i][$j]]))
    							{
    								if($availabilityForMonth[$tempArray[$i][$j]]->$key)
    								{
    									$active = true;
    								}
    							}
    						?>

		   					<div class="event {{ $active ? 'active' : '' }}">
	    						<div class="event-desc">
	    							{{ $session }}
	    						</div>
	    					</div>
	    					@endforeach

    					</li>
    					@endif

    				@endfor
    			</ul>
    			@endfor

    		</div><!-- /. calendar -->
    	</div><!-- /. wrap -->

@stop


@section('styles')
@parent
<style type="text/css">
.btn-group{
	width: 100%;
}

#calendar {
	width: 100%;	
}

#calendar a {
	color: #8e352e;
	text-decoration: none;
}

#calendar ul {
	list-style: none;
	padding: 0;
	margin: 0;
	width: 100%;
}

#calendar li {
	display: block;
	float: left;
	width:14.342%;
	padding: 5px;
	box-sizing:border-box;
	border: 1px solid #ccc;
	margin-right: -1px;
	margin-bottom: -1px;
}

#calendar ul.weekdays {
	height: 40px;
	background: #ff9800;
}

#calendar ul.weekdays li {
	text-align: center;
	text-transform: uppercase;
	line-height: 20px;
	border: none !important;
	padding: 10px 6px;
	color: #fff;
	font-size: 13px;
}

#calendar .days li {
	height: 180px;
}

#calendar .days li:hover {
	background: #d3d3d3;
}

#calendar .date {
	text-align: center;
	margin-bottom: 5px;
	padding: 4px;
	background: #333;
	color: #fff;
	width: 28px;
	border-radius: 50%;
	float: right;
}

#calendar .event {
	clear: both;
	display: block;
	font-size: 13px;
	border-radius: 4px;
	margin-bottom: 5px;
	line-height: 14px;
	background: #e1eaea;
	border: 1px solid #e1eaea;
	color: #009aaf;
	padding: 2px;
	text-decoration: none;
}

#calendar .active{
	background: #3d9449;
}

#calendar .event-desc {
	color: #d6d2d2;
	text-decoration: none;	
}

#calendar .other-month {
	background: #f5f5f5;
	color: #666;
}

@media(max-width: 768px) {

	#calendar .weekdays, #calendar .other-month {
		display: none;
	}

	#calendar li {
		height: auto !important;
		border: 1px solid #ededed;
		width: 100%;
		padding: 10px;
		margin-bottom: -1px;
	}

	#calendar .date {
		float: none;
	}

}

	.checker span.checked {
		border: 2px solid #607D8B;
	}

</style>

@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){

	$(".availability").restfulizer({
	        method: "POST",
	        @if($teacher->ignore_calendar)
	        target: "{{ route('teacher::availability.ignore_calendar',['value' => 'no']) }}"
	        @else
	        target: "{{ route('teacher::availability.ignore_calendar',['value' => 'yes']) }}"
	        @endif
	});


    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});


    var oneDay = 24*60*60*1000;
    var rangeDemoFormat = "%e-%m-%Y";
    var rangeDemoConv = new AnyTime.Converter({format:rangeDemoFormat});

    // Start date
    $("#from_date").AnyTime_picker({
        format: rangeDemoFormat
    });

    // On value change
    $("#from_date").change(function(e) {
        try {
            var fromDay = rangeDemoConv.parse($("#from_date").val()).getTime();

            var dayLater = new Date(fromDay+oneDay);
                dayLater.setHours(0,0,0,0);

            var ninetyDaysLater = new Date(fromDay+(270*oneDay));
                ninetyDaysLater.setHours(23,59,59,999);

            // End date
            $("#to_date")
            .AnyTime_noPicker()
            .removeAttr("disabled")
            .val(rangeDemoConv.format(dayLater))
            .AnyTime_picker({
                earliest: dayLater,
                format: rangeDemoFormat,
                latest: ninetyDaysLater
            });
        }

        catch(e) {

            // Disable End date field
            $("#to_date").val("").attr("disabled","disabled");
        }
    });

});
</script>
@stop