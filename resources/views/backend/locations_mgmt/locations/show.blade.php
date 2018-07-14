@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Locations Mgmt</span> - Locations - {{ $location->name }}</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::locations_mgmt::locations.index') }}">Locations</a></li>
				<li class="active">{{ $location->name }}</li>
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
			<div class="panel-heading mt-5">
				<h5 class="panel-title">#{{$location->id}}: {{$location->name}} ({{$location->code}})</h5>
				<div class="heading-elements">
					<a href="{{ route('admin::locations_mgmt::locations.edit',$location->id) }}" class="btn btn-primary heading-btn">Edit</a>
					<a href="{{ route('admin::locations_mgmt::locations.delete',$location->id) }}" data-method="DELETE" class="btn btn-danger heading-btn rest">Delete</a>
            	</div>
			</div>

			<div class="panel-body">
				@include('backend.locations_mgmt.locations._details')
				@include('backend.locations_mgmt.locations._calendar')
				@include('backend.locations_mgmt.locations._classrooms')
			</div>


		</div>

	</div>

	<div class="col-lg-4">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">Location</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="collapse"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">
				<div class="map-container map-click-event"></div>
			</div>
		</div>
	</div>

</div>

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