@extends('dashboard.student.layouts.master')

@section('content')


<div class="panel panel-success">
    <div class="panel-heading">
        <h5 class="panel-title">Make Payment</h5>
    </div>

    <div class="panel-body">
      <ul class="list-unstyled">
        <li><strong>Credits:</strong> {{ $credits }}</li>
        <li><strong>Amount:</strong> {{ $amount }}</li>
        <li><strong>Cost Per Credit:</strong> Rs.1,100/- (Inclusive of all Taxes)</li>

        @if($appliedCoupon)
        <li><strong>Coupon:</strong> {{ $appliedCoupon->coupon }} ({{ $appliedCoupon->description }})</li>
        @endif

        @if($student->lifetimeOffer)
        <li><strong>Lifetime Offer:</strong>{{ $student->lifetimeOffer->coupon->coupon }}
         ({{ $student->lifetimeOffer->coupon->description }})</li>

          @if($appliedCoupon)
          <?php 
            $resultCoupon = Aham\Managers\CreditsManager::compareCoupons($appliedCoupon, $student->lifetimeOffer->coupon, $credits);
          ?>
          
          <li>
            <strong>Final:</strong>
            {{ $resultCoupon->coupon }} ({{ $resultCoupon->description }})
          </li>

          @endif

        @endif
      </ul>
    </div>

    <div class="panel-footer">

          <form action="{{ route('student::credits.payment_success') }}" method="POST">
            <script
              src="https://checkout.razorpay.com/v1/checkout.js"
              data-key="{{ env('RAZOR_KEY') }}"
              data-amount="{{ $amount*100 }}"
              data-name="Aham Credits"
              data-description="Aham Credits"
              data-netbanking="true"
              data-prefill.name="{{ $user->name }}"
              data-prefill.email="{{ $user->email }}"
              data-prefill.contact="{{ $user->mobile }}"
              data-notes.credits="{{ $credits }}"
              data-notes.coupon="{{ $coupon }}">
            </script>
            <input type="hidden" name="credits" value="{{ $credits }}" style="border-radius: 25px !important;">
            <input type="hidden" name="coupon" value="{{ $coupon }}" style="border-radius: 25px !important;">
          </form>

    </div>

@stop


@section('styles')
@parent

<style type="text/css">
.razorpay-payment-button{
    position: relative;
    display: inline-block;
    line-height: 1.4;
    color: #fff;
    text-align: center;
    background: #00bcd4;
    background-image: none;
    border-width: 1px;
    border-style: solid;
    border-color: transparent;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border-radius: 25px !important;
    font-size: 16px;
    font-weight: 300;
    padding: 12px 66px;
    text-transform: uppercase;
}
</style>

@stop