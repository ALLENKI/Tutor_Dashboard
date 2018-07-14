@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Users</span> - Coupon Templates</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::coupon_templates.index') }}">Coupon Templates</a></li>
				<li class="active">Create New Coupon Template</li>
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
		<h5 class="panel-title">Create a Coupon Template</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 6]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::users::coupon_templates.store')) !!}

		{!! BootForm::text('Coupon Prefix', 'coupon')
                ->placeholder('Coupon Prefix')
                ->attribute('required','true') !!}

		{!! BootForm::select('Type *', 'type')
					->options(['' => 'Please select..','one-time' => 'One Time', 'multiple' => 'Multiple','lifetime' => 'Lifetime'])
					->attribute('required','true')
					->attribute('id','type')
		!!}

		{!! BootForm::select('Offer Type', 'additional_type')
					->options(['' => 'Please select..','additional_units' => 'Additional Units', 'additional_percent' => 'Additional Percentage'])
					->attribute('required','true')
					->attribute('id','additional_type')
		!!}

		{!! BootForm::text('Offer Value', 'additional_value')
                ->placeholder('Offer Value')
                ->attribute('required','true')
                ->attribute('type','number') 
                ->attribute('value',0) 
                ->attribute('min',0) 
                ->attribute('id','additional_value') 
       	!!}

		{!! BootForm::text('Usage Limit Per User', 'max_usage_limit_per_user')
                ->placeholder('Usage Limit Per User')
                ->attribute('required','true')
                ->attribute('type','number') 
                ->attribute('value',0) 
                ->attribute('min',0) 
                ->attribute('id','max_usage_limit_per_user') 
       	!!}

        {!! 
            BootForm::text('Issuance Limit', 'issuance_limit')
                ->placeholder('Issuance Limit')
                ->attribute('required','true')
                ->attribute('type','number') 
                ->attribute('value',0) 
                ->attribute('min',0) 
                ->attribute('id','issuance_limit') 
        !!}

		{!! BootForm::hidden('Max Users', 'max_users_limit')
                ->attribute('name','max_users_limit')
                ->attribute('value',1)
                !!}

		<div class="form-group">
		<label class="col-sm-4 col-lg-2 control-label" for="min_units_to_purchase">Min. Units To Purchase</label>
		<div class="col-sm-8 col-lg-6">

		<input type="number" name="min_units_to_purchase" id="min_units_to_purchase" class="form-control" placeholder="Min. Units To Purchase" required="true" value="0">

		<span class="help-block">If this is set to 0, then we it means there is no min units constraint<span>

		</div>

		</div>


        <div class="form-group {!! $errors->has('valid_from') ? 'has-error' : '' !!}  {!! $errors->has('valid_till') ? 'has-error' : '' !!}">

          <label class="col-sm-4 col-lg-2 control-label" for="date_range">Date Range *</label>

          <div class="col-sm-8 col-lg-6">
            <div class="input-daterange input-group" id="datepicker">

                <input type="text" class="input-sm form-control" name="valid_from" placeholder="Valid From" value="{{ Input::old('valid_from','') }}">
                {!! $errors->first('valid_from', '<p class="help-block">:message</p>') !!}

                <span class="input-group-addon">to</span>

                <input type="text" class="input-sm form-control" name="valid_till" placeholder="Valid Till" value="{{ Input::old('valid_till','') }}" id="valid_till">
                {!! $errors->first('valid_till', '<p class="help-block">:message</p>') !!}

            </div>
            <span class="help-block text-right">If you coupon type is lifetime, valid till won't be considered.</span>
          </div>

        </div>


        {!! BootForm::textarea('Description *', 'description')
              ->attribute('rows',3)
              ->attribute('required','true') !!}

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


    $('.input-daterange').datepicker({
        format: "dd-mm-yyyy",
        startDate: moment().toDate(),
        clearBtn : true,
    });

    $('#additional_type').on('change',function(){

        console.log('change',$(this).val());

        if($(this).val() == 'additional_percent')
        {
            $('#min_units_to_purchase').val(10);
            $('#min_units_to_purchase').attr('min',10);
        }
        else
        {
            $('#min_units_to_purchase').attr('min',0);
        }


    });


    $('#type').on('change',function(){

    	console.log('change');

    	if($(this).val() == 'one-time')
    	{
    		$('#max_usage_limit_per_user').val(1);
    		$('#max_usage_limit_per_user').attr('readonly',true);
    	}
    	else if($(this).val() == 'multiple')
    	{
    		$('#max_usage_limit_per_user').attr('min',0);
    		$('#max_usage_limit_per_user').attr('readonly',false);
    	}
		else if($(this).val() == 'lifetime')
    	{	
            $('#max_usage_limit_per_user').attr('min',0);
    		$('#valid_till').attr('disabled',true);
    		$('#max_usage_limit_per_user').attr('readonly',false);
    	}

    });


    $('#type').trigger('change');

});
</script>

@stop