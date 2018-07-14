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
            <h3 class="panel-title">Aham Earnings</h3>
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
                          <div class="fact-icon fa fa-inr"></div>
                        </div>
                        <div class="fact-number-wrap">
                          <div class="fact-number">1.2k</div>
                        </div>
                        <h2 class="fact-title">Rupees</h2>

                      </div>

                      <div class="clearfix"></div>
                      <small>You have no amount to withdraw from Aham Account</small>
                      <hr>

                        <h2 class="title">How it works for Teacher?</h2>
                      </div>

                      <div class="col-sm-5 col-sm-pull-1 vcenter text-right">
                      <a href="#" class="btn btn-medium btn-circle"> 
                       <i class="oli oli-unlock-filled"></i><span>Take a Class</span>
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