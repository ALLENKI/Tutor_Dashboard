@extends('frontend.layouts.master')


@section('content')

<section class="page-contents">
  <section id="main-area">
    <section class="section p-top-40">
      <div class="container">

      <div class="row">
        <div class="col-md-3">
            @include('frontend.settings.sidebar')
        </div> 

        <div class="col-md-9">

        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Aham Credits</h3>
          </div>
          <div class="panel-body">
            

              <section class="section bg-gray pad-20">
                <div>
                  <div class="row">
                    <!-- #####Begin call to action-->
                    <div class="call-out tb-vcenter-wrapper">

                      <div class="col-sm-5 col-sm-push-1 vcenter">


                      <div class="fact-item left-alined">
                        <div class="fact-icon-wrap left">
                          <div class="fact-icon oli oli-literature"></div>
                        </div>
                        <div class="fact-number-wrap">
                          <div class="fact-number">{{ $user->student->credits }}</div>
                        </div>
                        <h2 class="fact-title">Credits</h2>

                      </div>

                      <div class="clearfix"></div>

                      @if(!$user->student->credits)
                      <small>You don't have sufficient credits to enroll to classes</small>
                      @endif
                      
                      <hr>

                        <h2 class="title">Aham Credits Allow you to enroll to classes</h2>
                      </div>

                      <div class="col-sm-5 col-sm-pull-1 vcenter text-right">
                      <a href="#" class="btn btn-medium btn-circle"> 
                       <i class="oli oli-unlock-filled"></i><span>Buy now</span>
                      </a>
                      </div>

                    </div>
                    <!-- #####End call to action-->
                  </div>
                </div>
              </section>
          </div>
        </div>

        </div>

      </div>

      </div>
    </section>
  </section>
</section>

@stop

@section('scripts')
@parent
<script type="text/javascript">
  $(document).ready(function(){


  });
</script>

@stop