  
  @if($isStudent)
  <div class="col-md-12">

  <?php $student = $user->student; ?>
  <div class="panel panel-default">
  <div class="panel-heading">
    <strong>Enroll</strong>
  </div>

  <div class="panel-body">

  @if($guestSeries->cost_per_episode != 0)

    <?php 
      $number_of_credits = 0;

      $number_of_credits = ($guestSeries->cost_per_episode/1100)*$guestSeriesLevel->episodes->count();
    ?>

    @if($student->credits >= $number_of_credits)

    Dear {{ $user->name }}, Please check date and schedule of the workshop before confirming.
    

    <br><br>

      {!! BootForm::open()->action(route('series::enroll-as-student',[$guestSeries->slug, $guestSeriesLevel->slug]))->attribute('id','confirm_form') !!}

        <a href="{{ route('series::show',$guestSeries->slug) }}" class="btn btn-small btn-skin-red">Cancel</a>

        <a href="javascript:;" id="confirm" class="btn btn-small btn-skin-blue">Confirm</a>

      {!! BootForm::close() !!}

    @else

    <h4 class="text-center">You don't have enough credits to enroll.

    <a href="{{ route('student::credits.add') }}" class="btn btn-small btn-skin-green">Add Credits</a>

    </h4>

    @endif
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


@section('scripts')
@parent

<script type="text/javascript">
  $(function(){

    $('#confirm').on('click',function(){

      $('#confirmModal').modal('show');

    });

    $('#cancel_box').on('click',function(){

      $('#confirmModal').modal('hide');

    });

    $('#confirm_box').on('click',function(){

      $('#confirm_form').submit();

    });

  });
</script>

@stop