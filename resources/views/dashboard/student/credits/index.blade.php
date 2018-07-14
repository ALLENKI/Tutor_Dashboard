@extends('dashboard.student.layouts.master')

@section('page-header')

@stop

@section('content')

<div class="page-header page-header-default page-header-xs">
	<div class="page-header-content">
		<div class="page-title">
			<h5><i class="icon-coins position-left"></i> <span class="text-semibold">Learner Credits</span></h5>
		</div>

		<div class="heading-elements">
			<button type="button" class="btn btn-success btn-labeled btn-xs legitRipple"><b><i class="icon-coins"></i></b> {{ $student->user->creditBuckets()->sum('total_remaining') }} Credits</button>
			<a href="{{ route('student::credits.add') }}" class="btn btn-info btn-labeled btn-xs legitRipple"><b><i class="icon-add"></i></b> Add Credits</a>
		</div>
	</div>
</div>


<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title">Credits History</h5>
    </div>

    @if($student->lifetimeOffer)
    <div class="panel-body">
        Offer: {{ $student->lifetimeOffer->coupon->description }}
    </div>
    @endif

    <table class="table table-responsive">
    	<thead>
            <th>Invoice No</th>
    		<th>Credits</th>
    		<th>Date</th>
    		<th>Amount</th>
    		<th>Coupon</th>
    		<th>Method</th>
    	</thead>
    	<tbody>
    		@foreach($ahamCredits as $ahamCredit)

            @if($ahamCredit->method != 'refund')
    		<tr>

            <td>
            {{ $ahamCredit->invoice_no }}
             @if($ahamCredit->amount_paid > 0)
            <a href={{$ahamCredit->invoice_url}}>Download</a>
            @endif
            </td>

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
            
    		</tr>
            @endif
            
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

   {{--  <div class="panel-footer text-center" style="padding: 10px; font-weight: bold;">
        <ul class="list-inline">
            <li>Total Credit: {{ $credit }}</li>
            <li>Total Debit: {{ $debit }}</li>
            <li>Balance: {{ $credit - $debit }}</li>
        </ul>
    </div> --}}

</div>

@stop
