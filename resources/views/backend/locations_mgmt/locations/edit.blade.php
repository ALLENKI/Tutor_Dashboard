@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Locations Mgmt</span> - Locations</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::locations_mgmt::locations.index') }}">Locations</a></li>
				<li class="active">Edit Location - {{ $location->name }}</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Edit Location - {{ $location->name }}</h5>

        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
	</div>

	<div class="panel-body">
		<?php 

            $columnSizes = [
              'sm' => [4, 8],
              'lg' => [2, 10]
            ];

        ?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::locations_mgmt::locations.update',$location->id)) !!}

        {!! BootForm::bind($location) !!}

        <div class="form-group">
        <label class="col-sm-4 col-lg-2 control-label" for="active">
        </label>
        <div class="col-sm-8 col-lg-10">
        
          <label class="checkbox-inline">
            <input type="checkbox" class="styled" name="active" value="yes" {{ $location->active ? 'checked': '' }}>
            Active
          </label>
          

        </div>
        </div>

        <div class="form-group">
            <label class="col-sm-4 col-lg-2 control-label" for="active">
            </label>
            <div class="col-sm-8 col-lg-10">
            
                <label class="checkbox-inline">

                    <input type="checkbox" class="styled" name="repeat_class" value="yes" 
                    {{ $location->repeat_class ? 'checked': '' }}>
                    Repeat Class
                    
                </label>
            
            </div>
        </div>

        {!! BootForm::text('Name *', 'name')
              ->placeholder('Name')
              ->attribute('required','true') !!}

        {!! BootForm::textarea('Street Address', 'street_address')
        			->attribute('rows',3)
        			->attribute('required','true') !!}

        {!! BootForm::text('Landmark *', 'landmark')
              ->placeholder('Landmark')
              ->attribute('required','true') !!}

    		{!! BootForm::select('City *', 'city_id')
    					->options($cities)
    					->attribute('required','true')
    		!!}

        {!! BootForm::select('Locality *', 'locality_id')
                    ->options($localities)
                    ->attribute('required','true')
        !!}

        {!! BootForm::text('Currency Type *', 'currency_type')
              ->placeholder('Currency Type')
              ->attribute('required','true') !!}

        {!! BootForm::select('Credits Type *', 'credits_type')
                    ->options([
                      'global' => 'Global',
                      'hub_only' => 'Hub Only',
                    ])
                    ->attribute('required','true')
        !!}

        {!! BootForm::text('Pincode *', 'pincode')
              ->placeholder('Pincode')
              ->attribute('required','true')
              ->attribute('type','number') !!}

        <div class="panel panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">Select Location on Map</h5>
            </div>

            <div class="panel-body">

                <div class="row">

                <div class="col-md-6">

                {!! BootForm::text('Latitude *', 'latitude')
                      ->placeholder('Latitude')
                      ->attribute('id','latitude')
                      ->attribute('readonly','true')
                      ->attribute('required','true') !!}


                {!! BootForm::text('Longitude *', 'longitude')
                      ->placeholder('Longitude')
                      ->attribute('id','longitude')
                      ->attribute('readonly','true')
                      ->attribute('required','true') !!}

                    </div>

                    <div class="col-md-4 col-md-offset-1">
                        
                        <div class="form-group">
                        <label>Type Address or Drag Marker on Map:</label>
                        <input type="text" class="form-control" id="us3-address">
                        </div>

                        <div class="form-group">
                        <div id="us3" class="map-wrapper"></div>
                        </div>

                    </div>

                </div>


            </div>
        </div>

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


$(document).ready(function(){

    $('#us3').locationpicker({
        location: {latitude: {{ $location->latitude }}, longitude: {{ $location->longitude }} }, 
        radius: 0,
        scrollwheel: true,
        inputBinding: {
            latitudeInput: $('#latitude'),
            longitudeInput: $('#longitude'),
            radiusInput: 0,
            locationNameInput: $('#us3-address')        
        },
        enableAutocomplete: true,
        enableReverseGeocode: false,
        onchanged: function(currentLocation, radius, isMarkerDropped) {
            console.log(currentLocation);
        }
    });

});
</script>

<script>

</script>

@stop