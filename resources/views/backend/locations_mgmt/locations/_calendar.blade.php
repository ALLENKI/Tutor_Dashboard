	<div class="panel panel-warning">

		<div class="panel-heading">
			<h6 class="panel-title"><i class="icon-calendar position-left"></i> Calendar</h6>
			<div class="heading-elements">
				<ul class="icons-list">
            		<li><a data-action="collapse"></a></li>
            	</ul>
        	</div>
		</div>

		<div class="panel-body">

		<div class="content-group">

		<div class="row">

			<div class="alert alert-warning no-border">
				<span class="text-semibold">Note!</span> Saturdays and Sundays are by default treated as Weekends.
			</div>

			{!! BootForm::open()->action(route('admin::locations_mgmt::locations.add_calendar')) !!}

			{!! BootForm::hidden('location_id')->attribute('value',$location->id) !!}

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

			<div class="col-md-6">
				{!! BootForm::select('Day Type *', 'day_type_id')
							->options($day_types)
							->attribute('required','true')
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

		@if($location->locationCalendars->count())
		<table class="table table-framed">

			<thead>
				<tr>
					<th>From Date</th>
					<th>To date</th>
					<th>Day Type</th>
					<th>Actions</th>
				</tr>
			</thead>

			<tbody>
				@foreach($location->locationCalendars as $locationCalendar)
				<tr>
					<td>
		            	<i class="icon-calendar22 position-left"></i>
		            	{{ $locationCalendar->from_date->format('jS M Y') }}
					</td>
					<td>
		            	<i class="icon-calendar22 position-left"></i>
		            	{{ $locationCalendar->to_date->format('jS M Y') }}
					</td>
					<td>{{ $locationCalendar->dayType->name }}</td>
					<td>
						<a href='{{ route('admin::locations_mgmt::locations.delete_calendar',$locationCalendar->id) }}' data-method='DELETE' class="rest"><i class='icon-cross'></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>

		</table>
		@else
				<h3 class="text-center">This location has no calendar. Please add, else the location won't have any availability </h3>		
		@endif

		</div>

		</div>

	</div>

@section('scripts')
@parent
<script type="text/javascript">
$(function(){

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