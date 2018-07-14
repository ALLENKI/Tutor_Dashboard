@extends('frontend.layouts.master')

@section('content')

<section class="page-contents">
<section id="main-area" class="pad-60">

<div class="container">
	<div class="row">

    @if($enrolled)
    <div class="col-md-12">

      <div class="panel panel-default">
      <div class="panel-heading">
        <strong>Enrolled</strong>
      </div>

      <div class="panel-body">
        You are enrolled to the following level. Go <a href="{{ route('series::show',$guestSeries->slug) }}" style="color: #337ab7;">back</a> to main series page.
      </div>
      </div>

    </div>
    @else

      @include('frontend.guest_series.student_enrollment')
      @include('frontend.guest_series.user_enrollment')

    @endif

    <div class="clearfix"></div>

    <div class="col-md-12">
      <hr>
    </div>

		<div class="col-md-12">
		
    <div class="panel panel-default">
      <div class="panel-heading"><strong>{{ $guestSeriesLevel->name }}</strong></div>
      <div class="panel-body">
        
        {!! $guestSeriesLevel->description !!}

        <hr>

       {{--  <table class="table">
        	<thead>
            <th>Session</th>
        		<th>Timings</th>
             @if($guestSeries->enrollment_restriction == 'restrict_by_episode')
        		<th></th>
            @endif
        	</thead>

        	<tbody>
    		    @foreach($guestSeriesLevel->episodes as $episode)
    		    <tr>
              <td>
                {{ $episode->topic->name }}
              </td>

    		    	<td>
                {{ $episode->date_summary }} - {{ $episode->time_summary }}
    		    	</td>

              @if($guestSeries->enrollment_restriction == 'restrict_by_episode')
    		    	<td>

              @if(!$episode->enrollment_cutoff->isPast())
              
                @if($episode->enrollments->count() < $episode->enrollment_limit)

                  @if(Sentinel::check())

                    @if(count($enrolled))

                      @if(in_array($episode->id,$enrolled))
                      <span class="label label-success">Enrolled</span>
                      @endif

                    @else
        		    		<a href="{{ route('series::enroll',[$guestSeries->slug, $episode->id]) }}" class="btn btn-small btn-circle btn-skin-green">Enroll</a>
                    @endif

                  @endif

                @else
                  <span class="label label-success">Full</span>
                @endif

              @else

                <span class="label label-danger">Closed</span>

              @endif


    		    	</td>
              @endif

    		    </tr>
    	    	@endforeach
        	</tbody>
        </table> --}}

      </div>
    </div>

		</div>
	</div>
</div>


</section>
</section>


<div id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal fade">

<div role="document" class="modal-dialog">

<div class="modal-content">
<div class="modal-header">
<button type="button" data-dismiss="modal" aria-label="Close" class="close">
<span aria-hidden="true"><i class="oli oli-delete_sign"></i></span></button>
<h4 id="myModalLabel" class="modal-title">Confirm Enrollment</h4>
<div class="modal-body">
<p>Dear {{ $user->name }}, To enroll to this workshop, {{ ($guestSeries->cost_per_episode/1100)*$guestSeriesLevel->episodes->count() }} credits will be deducted from your account. Please confirm or cancel. </p></div>
<div class="modal-footer">
<a href='javascript:;' id="cancel_box" type="button" class="btn btn-small btn-round btn-skin-red">Cancel</a>
<a href='javascript:;' id="confirm_box" type="button" class="btn btn-small btn-round btn-skin-green">Confirm</a>

</div>
</div>
</div>

</div>

</div>

@stop


@section('scripts')
@parent
<script type="text/javascript">
  $(function(){

    @if(Input::has('popup'))
      $('#scrollModal').modal('show');
    @endif

    $('#enroller_scroll').on('click',function(){
      $('#scrollModal').modal('hide');
    });

  });
</script>
@stop