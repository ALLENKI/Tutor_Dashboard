@extends('dashboard.teacher.layouts.master')

@section('content')
<?php $topic = $class->topic; ?>

@include('dashboard.teacher.class.header')

<div class="row">
	<div class="col-md-8">

		<div class="row">
			
			<div class="col-md-12">
				
				<div class="panel panel-white">
					<div class="panel-heading">
						<h6 class="panel-title">Course Information</h6>
				    </div>	 

					<?php
						$image_url = cloudinary_url($topic->present()->picture, array("height"=>550, "width"=>550, "crop"=>"thumb"));
					?>

					<div class="panel panel-body">

						<h5 class="text-semibold">{{ $topic->name }} <small class="display-block"></small></h5>
						<p class="content-group">
							{{ $topic->description }}			
						<p>

						<ul class="list content-group">
							<li><span class="text-semibold">Sub Category:</span> {{ $topic->lookup->subCategory->name }}</li>
							<li><span class="text-semibold">Subject:</span> {{ $topic->lookup->subject->name }}</li>
							<li><span class="text-semibold">Tags:</span> {{ $topic->tagList }}</li>
						</ul>

					</div>

			    </div>

			</div>

		</div>


		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title">Class Timings</h6>
		    </div>	
		   
	   		<table class="table table-bordered table-striped table-condensed">
	   			<thead>
   					<th>#</th>
   					<th>Timings</th>
   					<th>Classroom</th>
	   			</thead>
				<tbody>
					@foreach($class->topic->units as $index => $unit)
					<?php $timing = classTimingStatus($unit->id,$class->id); ?>

					@if(schedule($unit->id,$class->id) != 'NA')
					<tr>
						<td>{{ $unit->name }}</td>
						<td>{{ schedule($unit->id,$class->id) }}</td>
						<td>

							@if($timing->classroom)
							{{ $timing->classroom->name }}
							@else
							NA <span data-popup="popover" data-trigger="hover" data-content="We will inform you once we assign the classroom. The class in on.">
								<i class="icon-help"></i>
							</span>
							@endif
						</td>
					</tr>
					@endif

					@endforeach
				</tbody>
			</table>

		</div>


		@if($class->status == 'get_feedback' || $class->status == 'got_feedback' || $class->status == 'completed' )
		<div class="panel panel-success">
			<div class="panel-heading">
				<h6 class="panel-title">Feedback</h6>
		    </div>	

		    <div class="panel-body">
		    	@include('dashboard.teacher.class._feedback')
		    </div>
		</div>
		@endif

	</div>

	<div class="col-md-4">

		<div class="panel panel-success">
			<div class="panel-heading">
				<h6 class="panel-title">Location Information</h6>
		    </div>	 

		    <?php $location = $class->location; ?>

			<div class="panel panel-body">

				<h5 class="text-semibold">{{ $location->name }} <small class="display-block"></small></h5>

				<div class="map-container map-click-event" style="height:250px;"></div>

				<hr>

				<ul class="list content-group">
					
					<li><span class="text-semibold">Street:</span> {!! $location->street_address !!}</li>
					<li><span class="text-semibold">Landmark:</span> {!! $location->landmark !!}</li>
					<li><span class="text-semibold">Locality:</span> {{ $location->locality->name }}</li>
					<li><span class="text-semibold">City:</span> {{ $location->city->name }}</li>
					<li><span class="text-semibold">Pincode:</span> {{ $location->pincode }}</li>
				</ul>

			</div>

	    </div>

	</div>
</div>


<div class="row">
	
		<div class="col-md-6">
	
				<div class="panel panel-white">
						<div class="panel-heading">
							<h6 class="panel-title">uploaded files</h6>
						</div>	
					   
						   <table class="table table-bordered table-striped table-condensed">
							   <thead>
								   <th>file name</th>
							   </thead>
							<tbody>
								
								@foreach($topicFiles as $topicFile)
								<tr>
									{{-- <td> --}}
										{{-- {{ $topicFile->id }} --}}
									{{-- </td> --}}
									<td> 
											<a href={{ $topicFile->file_url }}> {{ $topicFile->file_name }}</a>
									</td>
								</tr>
								@endforeach
							</tbody>
						  </table>
			
				</div>
	
		</div>
			
	</div>

@include('dashboard.student.class.firebase')


@stop


@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){


    function initialize() {

        // Options
        var mapOptions = {
            zoom: 14,
            center: new google.maps.LatLng({{ $location->latitude }}, {{ $location->longitude }})
        };

        // Apply options
        var map = new google.maps.Map($('.map-click-event')[0], mapOptions);

        // Add markers
        var marker = new google.maps.Marker({
            position: map.getCenter(),
            map: map,
            title: 'Click to zoom'
        });

        // "Change" event
        google.maps.event.addListener(map, 'center_changed', function() {

            // 3 seconds after the center of the map has changed, pan back to the marker
            window.setTimeout(function() {
                map.panTo(marker.getPosition());
            }, 3000);
        });

        // "Click" event
        google.maps.event.addListener(marker, 'click', function() {
            map.setZoom(14);
            map.setCenter(marker.getPosition());
        });
    }

	google.maps.event.addDomListener(window, 'load', initialize);

});

</script>

@stop
