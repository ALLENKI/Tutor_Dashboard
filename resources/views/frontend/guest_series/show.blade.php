@extends('frontend.layouts.master')

@section('content')

@if(!count($enrolled) || 1)
<div id="scrollModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" class="modal fade">

<div role="document" class="modal-dialog">

<div class="modal-content">
<div class="modal-header">
<button type="button" data-dismiss="modal" aria-label="Close" class="close">
<span aria-hidden="true"><i class="oli oli-delete_sign"></i></span></button>
<h4 id="myModalLabel" class="modal-title">One More Step To Go</h4>
<div class="modal-body">
<p>You have successfully logged into Aham Learning Hub and one step away from enrolling to Robotics Workshop!</p></div>
<div class="modal-footer">
<a href='#enroller' id="enroller_scroll" type="button" class="btn btn-small btn-round btn-skin-red">Check Timings and Enroll</a>
</div>
</div>
</div>

</div>

</div>
@endif

<div data-img-src="{{ cdn('assets/front/img/backgrounds/19.jpg') }}" class="page-head parallax-layer ov-light-alpha-50" style="padding: 20px 0;">
  <div class="container">
    <!-- #####Begin course introduction-->
    <div class="course-intro" style="min-height: 250px;background-color: transparent;">
      <div class="row sync-cols-height">

        <div class="col-md-10 col-sm-10 col-xs-10 col-md-offset-1">
          <div class="course-info" style="padding:0px;">

            <img src="https://res.cloudinary.com/ahamlearning/image/upload/v1485851035/RoboticsClubWebBanner_1_amgkrt.jpg" class="img-responsive">

          </div>
        </div>

      </div>
    </div>
    <!-- #####End course introduction-->
  </div>
</div>

@if(!Sentinel::check())
<section class="section bg-gray" style="padding:60px;">
<div class="container">
<div class="row">
<div class="call-out tb-vcenter-wrapper">
<div class="col-sm-12 vcenter text-center">

<a href="{{ route('auth::login',['redirect' => route('series::show',[$guestSeries->slug,'popup'=>'show'])]) }}" class="btn btn-medium btn-anim-i btn-skin-blue btn-icon-right btn-circle">
<i class="oli oli-forward"></i>
<span>Login To Enroll</span>
</a>

</div>
</div>
</div>
</div>
</section>
@endif

@if(count($enrolled) && $guestSeries->enrollment_restriction == 'restrict_by_episode')
<section class="section bg-gray" style="padding:60px;">
<div class="container">
<div class="row">
<div class="call-out tb-vcenter-wrapper">

<div class="col-sm-9 vcenter">
<h2 class="title">You are already enrolled for this workshop <br> on {{ $enrolledEpisode->date_summary }} - {{ $enrolledEpisode->time_summary }}</h2>
@if($canCancel)
<h4 class="sub-title">Please <a href="{{ url('contact-us') }}">contact</a> Aham to cancel your enrollment.</h4>
@endif
</div>

@if(0)
<div class="col-sm-3 vcenter text-center">
<a href="{{ route('series::cancel_enroll',[$guestSeries->slug,array_values($enrolled)[0]]) }}" class="btn btn-medium btn-anim-i btn-skin-blue btn-icon-right btn-circle">
<i class="oli oli-forward"></i>
<span>Cancel Enrollment</span>
</a>
</div>
@endif

</div>
</div>
</div>
</section>
@endif


@if(count($enrolled) && $guestSeries->enrollment_restriction == 'restrict_by_level')
<section class="section bg-gray" style="padding:60px;">
<div class="container">
<div class="row">
<div class="call-out tb-vcenter-wrapper">
<div class="col-sm-9 vcenter">
<h2 class="title">
You are already enrolled for this workshop
@if($canCancel)
<h4 class="sub-title">Please <a href="{{ url('contact-us') }}">contact</a> Aham to cancel your enrollment.</h4>@endif
</div>

@if($canCancel && 0)
<div class="col-sm-3 vcenter text-center">
<a href="#" class="btn btn-medium btn-anim-i btn-skin-blue btn-icon-right btn-circle">
<i class="oli oli-forward"></i>
<span>Cancel Enrollment</span>
</a>
</div>
@endif

</div>
</div>
</div>
</section>
@endif

<section class="page-contents">
<section id="main-area" class="pad-60">

<div class="container">
	<div class="row">
		<div class="col-md-12">
			
        <div class="row">
          <div class="col-md-8">
            
            <h5>Requirement</h5>  
            <p>
            {!! $guestSeries->requirement !!}
            </p>

            <h5>Optional Info</h5>
            <p>
            {!! $guestSeries->optional !!}
            </p>

            <h5>Description</h5>
            <p>
            {!! $guestSeries->description !!}
            </p>
          </div>

          <div class="col-md-4">
    
            <h4>Venue</h4>
            <div class="sp-blank-20"></div>
            <p> <strong>Synergy Building, 3rd Floor, Above Andhra Bank, Khajaguda, Near Delhi Public School, Naga Hills Rd, Madhura Nagar Colony, Gachibowli, Hyderabad, Telangana-500008.</strong>
            </p>

            <div class="google-maps-container"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d475.85537866681784!2d78.3746157871881!3d17.419319149301437!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTfCsDI1JzA5LjkiTiA3OMKwMjInMjkuMyJF!5e0!3m2!1sen!2sin!4v1472741830107" width="500" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></div>

          </div>

        </div>


        <hr id="enroller">


@foreach($guestSeries->levels as $level)

<div class="panel panel-default">
  <div class="panel-heading"><strong>{{ $level->name }}</strong></div>
  <div class="panel-body">
    
    {!! $level->description !!}

    @if(Sentinel::check())
    @if($guestSeries->enrollment_restriction == 'restrict_by_level' && !count($enrolled))
      
      <a class="btn btn-xs btn-skin-green" href="{{ route('series::enroll-to-level',[$guestSeries->slug, $level->slug]) }}">Enroll to This Batch</a>

      <hr>

    @endif
    @endif

    <table class="table">
    	<thead>
        <th>#</th>
    		<th>Timings</th>
         @if($guestSeries->enrollment_restriction == 'restrict_by_episode')
    		<th></th>
        @endif
    	</thead>

    	<tbody>
		    @foreach($level->episodes as $episode)
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
    </table>

  </div>
</div>


@endforeach

		</div>
	</div>
</div>


</section>
</section>



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