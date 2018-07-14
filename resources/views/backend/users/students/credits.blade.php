@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Student Credits</span></h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::users::users.index') }}">Users</a></li>
				<li><a href="{{ route('admin::users::users.show',$user->id) }}">{{ $user->name }} ({{$user->email}})</a></li>
				<li class="active">Student: {{ $student->code }} ({{ $student->assessments->count() }})</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->

@stop

@section('content')

<!-- Basic datatable -->
<div class="panel panel-flat" style="display: none;">
	<div class="panel-heading">
		<h5 class="panel-title">Add Credits</h5>
	</div>

	<div class="panel-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::users::students.credits',$student->id)) !!}

        {!! 

        	BootForm::select('Credits *', 'credits')
                    ->options([1 => 1, 10 => 10, 15 => 15,20 => 20,25 => 25,30 => 30,40 => 40,50 => 50,60 => 60,70 => 70,75 => 75,80 => 80,90 => 90,100 => 100,110=>110,120=>120,130=>130,140=>140,150=>150,160=>160,170=>170,180=>180,190=>190,200=>200])
                    ->attribute('required','true')
                    ->attribute('id','credits')
        !!}

        {!! BootForm::select('Coupon', 'coupon')
                ->options(['' => 'Click to select a coupon'] + $coupons)
              ->placeholder('Coupon')
              ->attribute('id','coupon')
        !!}

        <div class="form-group">
        <label class="col-sm-4 col-lg-2 control-label" for="free">
        </label>
        <div class="col-sm-8 col-lg-10">
        
            <label class="checkbox-inline">
                <input type="checkbox" class="styled" name="promotional" value="yes">
                Promotional
            </label>

        </div>
        </div>

        {!! BootForm::select('Method *', 'method')
              ->options(['cash' => 'Cash','cheque' => 'Cheque', 'online_transfer' => 'Online Transfer','manual_refund' => 'Manual Refund','bonus' => 'Bonus'])
              ->placeholder('Method')
              ->attribute('required','true') !!}

        {!! BootForm::textarea('Remarks *', 'remarks')
              ->attribute('rows',3)
              ->attribute('required','true') !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
<!-- /basic datatable -->


<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Credits History</h5>
    </div>

    @if($student->lifetimeOffer)
    <div class="panel-body">
        Offer: {{ $student->lifetimeOffer->coupon->additional_value }} {{ $student->lifetimeOffer->coupon->additional_type }}
    </div>
    @endif

    <table class="table table-responsive">
    	<thead>
    		<th>Credits</th>
    		<th>Date</th>
    		<th>Amount</th>
    		<th>Coupon</th>
    		<th>Method</th>
            <th>Belongs To</th>
    		<th>Remarks</th>
    	</thead>
    	<tbody>
    		@foreach($ahamCredits as $ahamCredit)
            
    		<tr>
    		<td>{{ $ahamCredit->credits }}</td>
    		<td>{{ $ahamCredit->created_at->format('jS M Y h:i A') }}</td>
    		<td>{{ $ahamCredit->amount_paid }}</td>
    		<td>
    		@if($ahamCredit->coupon)
    		{{ $ahamCredit->coupon->coupon }}
    		@else
    		NA
    		@endif
    		</td>
    		<td>{{ $ahamCredit->method }}</td>
    		<td>{{ $ahamCredit->parent_id }}</td>
            <td>{{ $ahamCredit->remarks }}</td>
    		</tr>
    		@endforeach
    	</tbody>
    </table>

</div>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Credits Statement</h5>
    </div>

    <table class="table table-responsive">
        <thead>
            <th>Date</th>
            <th>Method</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Remarks</th>
        </thead>
        <tbody>
            <?php 
                $debit = 0;
                $credit = 0;
            ?>

            @foreach($statement as $stm)
            <tr>
                <td>{{ $stm['date']->format('jS M Y H:i A') }}</td>
                <td>{{ $stm['method'] }}</td>
                <td>
                    @if($stm['type'] == 'credit')
                        {{ $stm['credits'] }}
                        <?php $credit += $stm['credits'] ?>
                    @endif
                </td>
                <td>
                    @if($stm['type'] == 'debit')
                        {{ $stm['credits'] }}
                        <?php $debit += $stm['credits'] ?>
                    @endif
                </td>
                <td>{{ $stm['remarks'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <div class="panel-footer text-center" style="padding: 10px; font-weight: bold;">
        <ul class="list-inline">
            <li>Total Credit: {{ $credit }}</li>
            <li>Total Debit: {{ $debit }}</li>
            <li>Balance: {{ $credit - $debit }}</li>
        </ul>
    </div>

</div>
@stop

@section('scripts')
@parent
<script type="text/javascript">

$(function(){

	// $('#topic_id').select2();

});

</script>
@stop