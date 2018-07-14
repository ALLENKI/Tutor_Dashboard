      
      @if(!$isStudent)
      <div class="col-md-12">

      <div class="panel panel-default">
      <div class="panel-heading">
        <strong>Enroll</strong>
      </div>

      <div class="panel-body text-center">

        @if($guestSeries->cost_per_episode != 0)
          <p>
          Dear {{ $user->name }},  Please check date and schedule of the workshop before confirming
          </p>

          <form action="{{ route('series::enroll-as-user',[$guestSeries->slug, $guestSeriesLevel->slug]) }}" method="POST">
            <script
              src="https://checkout.razorpay.com/v1/checkout.js"
              data-key="{{ env('RAZOR_KEY') }}"
              data-amount="{{ ($guestSeriesLevel->episodes->count()*$guestSeries->cost_per_episode)*100 }}"
              data-name="Enrolling to ".{{ $guestSeries->name }}
              data-description="Enrolling to ".{{ $guestSeries->name }}
              data-netbanking="true"
              data-prefill.name="{{ $user->name }}"
              data-prefill.email="{{ $user->email }}"
              data-prefill.contact="{{ $user->mobile }}">
            </script>
          </form>
        @else

      Dear {{ $user->name }}, Please check date and schedule of the workshop before confirming.
      

      <br><br>

      {!! BootForm::open()->action(route('series::enroll-free',[$guestSeries->slug, $guestSeriesLevel->slug])) !!}

        <a href="{{ route('series::show',$guestSeries->slug) }}" class="btn btn-small btn-skin-red">Cancel</a>

        <button type="submit" class="btn btn-small btn-skin-blue">Confirm</button>

      {!! BootForm::close() !!}

        @endif

      </div>
        
      </div>

    </div>
    @endif


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