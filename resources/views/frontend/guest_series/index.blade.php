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
<p>You have successfully logged into Aham Learning Hub and one step away from enrolling to the Workshop!</p></div>
<div class="modal-footer">
<a href='#enroller' id="enroller_scroll" type="button" class="btn btn-small btn-round btn-skin-red">Check Timings and Enroll</a>
</div>
</div>
</div>
</div>
</div>
@endif

<section id="contents">
  <section class="page-contents">
    <section class="section" style="padding: 35px 0;">
      <div class="container">
        <div class="row">
          <!-- #####Begin main area-->
          <div class="col-md-9">
            <div id="main-area">
              <div class="single-post large-typo">
                <div class="post-header">
                  <div class="post-thumb">
                    {!! cl_image_tag($guestSeries->present()->picture,array('class' => 'img-responsive')) !!}
                  </div>
                </div>
                <!-- #####Begin post contents-->
                <div class="post-body">
                  
                </div>
                <!-- #####End post contents-->
              </div>
             
              
            </div>
          </div>
          <!-- #####End main area-->
          <!-- #####Begin sidebar-->
          <div class="col-md-3">
            <div class="sidebar right">

             @foreach($guestSeries->levels as $level)
             <div class="widgets-wrapper" style="margin-bottom:20px;">
                <div class="widget">
                  <h4 style="margin-bottom: 10px;">
                  {{ $level->name }}

                  @if($guestSeries->enrollment_restriction == 'restrict_by_level' && count($enrolled))
                    @if($enrolled[0] == $level->id)
                    <span class="label label-success pull-right">Enrolled</span>
                    @endif
                  @endif

                  </h4>
                  <!-- #####Begin list of categoreis-->
                  <div class="category-list">
                      {!! $level->description !!}
                  </div>
                  <!-- #####End list of categoreis-->

                  @if(!$level->enrollment_cutoff->isPast())

                  @if(Sentinel::check())

                  @if($guestSeries->enrollment_restriction == 'restrict_by_level' && !count($enrolled))
                    
                    <a class="btn btn-extra-small btn-skin-green" href="{{ route('series::enroll-to-level',[$guestSeries->slug, $level->slug]) }}" style="margin-top:10px;">Enroll to This Batch</a>

                  @endif

                  @endif

                  @else

                  <span class="label label-danger" style="font-size: 100%;">Closed</span>

                  @endif

                </div>
              </div>

              
              @endforeach

            </div>

            @if(!$level->enrollment_cutoff->isPast())

            @if(!Sentinel::check())
                <a href="{{ route('auth::login',['redirect' => route('series::show',[$guestSeries->slug,'popup'=>'show'])]) }}" class="btn btn-medium btn-anim-i btn-skin-blue btn-icon-right btn-circle">
                <i class="oli oli-forward"></i>
                <span>Login To Enroll</span>
                </a>
            @endif

            @if(Sentinel::check())
            @if(count($enrolled) && $guestSeries->enrollment_restriction == 'restrict_by_episode')


            @endif

            @if(count($enrolled) && $guestSeries->enrollment_restriction == 'restrict_by_level')

            <p>You are already enrolled. In case you want to cancel, Please <a href="{{ url('contact-us') }}"><strong>contact</strong></a> us to cancel</p>

            @endif
            @endif

            @endif

          </div>
          <!-- #####End sidebar-->
        </div>

        <hr>

        <div class="row">
          <div class="col-md-8 guest">
            
            <h5 style="text-transform: uppercase; color: #1F79A3;">{!! $guestSeries->name !!}</h5>
            <br>
            <h4>Description</h4>
            <p style="font-family: 'Sintony',sans-serif;color: #333;font-size: 14px;">
            {!! $guestSeries->description !!}
            </p>

            <h5>Requirement</h5>  
            <p style="color:#333;">
            {!! $guestSeries->requirement !!}
            </p>

            <h5>Optional Info</h5>
            <p style="color:#333;">
            {!! $guestSeries->optional !!}
            </p>


          </div>

          <div class="col-md-4">

            <h4>Venue</h4>
            <div class="sp-blank-20"></div>
            <p> <strong>Synergy Building, 3rd Floor, Above Andhra Bank, Khajaguda, Gachibowli, Hyderabad, Telangana-500008.</strong>
            </p>

            <div class="google-maps-container"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d475.85537866681784!2d78.3746157871881!3d17.419319149301437!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTfCsDI1JzA5LjkiTiA3OMKwMjInMjkuMyJF!5e0!3m2!1sen!2sin!4v1472741830107" width="500" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></div>

          </div>

        </div>

        <hr>

        @foreach($guestSeries->levels as $level)

        <div class="panel panel-default">
          <div class="panel-heading">

          <strong>{{ $level->name }}</strong>

          @if($guestSeries->enrollment_restriction == 'restrict_by_level' && count($enrolled))
            @if($enrolled[0] == $level->id)
              <span class="label label-success">Enrolled</span>
            @endif
          @endif

          @if($level->enrollment_cutoff->isPast())
            <span class="label label-danger pull-right" style="font-size: 100%;">Closed</span>
          @endif


          </div>
          <div class="panel-body">
            
            {!! $level->description !!}

            @if(!$level->enrollment_cutoff->isPast())

            @if(Sentinel::check())

            @if($guestSeries->enrollment_restriction == 'restrict_by_level' && !count($enrolled))
              
              <a class="btn btn-xs btn-skin-green" href="{{ route('series::enroll-to-level',[$guestSeries->slug, $level->slug]) }}">Enroll to This Batch</a>

              <hr>

            @endif

            @endif

            @else

            

            @endif

            <table class="table">
              <thead>
                <th>Session</th>
                <th>Timings</th>
                 @if($guestSeries->enrollment_restriction == 'restrict_by_episode')
                <th></th>
                @endif
              </thead>

              <tbody>
                @foreach($level->episodes as $episode)
                <tr>

                  <td>
                    {{ $episode->name }}
                  </td>

                  <td style="width:350px;">
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
    </section>
  </section>
</section>

@stop

@section('styles')
@parent

  <style>
    .guest >  *, font,.guest p{
  font-family: 'Sintony',sans-serif !important;
  font-size: 14px !important;
  color: #333;
}
  </style>

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