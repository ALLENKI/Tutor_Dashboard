@extends('frontend.layouts.master')

@section('content')

     <!-- #####Begin contents-->
      <section id="contents">
        <!-- #####Begin page head-->

        <div class="page-head set-bg h-450 center-it ov-dark-alpha-30 dark" data-img-src="assets/front/img/Aham-Logo-New-1.jpg">
          <div class="container">
            <div class="tb-vcenter-wrapper">
              <div class="title-wrapper vcenter">
              </div>
            </div>
          </div>
        </div>


        <section class="page-contents">
          <!-- #####Begin main area-->
          <section id="main-area">
            <section class="section bg-gray" style="padding:85px 0;">
              <div class="container">
                <div class="row">
                  <div class="col-md-4" sty>
                    <h3 class="with-shaded-label"> <span class="shaded-label">Address</span>Address</h3>
                    <div class="sp-blank-20"></div>
                    <p> <strong>Synergy Building, 3rd Floor, Plot No: 6-11, Survey No. 40, Above Andhra Bank, Khajaguda, Near Delhi Public School, Naga Hills Rd, Madhura Nagar Colony, Gachibowli, Hyderabad, Telangana-500008.</strong>
                    </p>

                    <br>

                    <div class="google-maps-container"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d475.85537866681784!2d78.3746157871881!3d17.419319149301437!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTfCsDI1JzA5LjkiTiA3OMKwMjInMjkuMyJF!5e0!3m2!1sen!2sin!4v1472741830107" width="500" height="450" frameborder="0" style="border:0" allowfullscreen></iframe></div>

                    
                  </div>
                  
                  <div class="col-md-8">
                    <img src="assets/front/img/loc1.png" style="width:100%;">
                  </div>
                </div>
              </div>
            </section>
       
          </section>
          <!-- #####End main area
          -->
          <div class="clearfix"></div>
        </section>
      </section>
      <!-- #####End contents-->

@stop

@section('styles')
@parent

  <style>
    .page-head.dark h1, .page-head.dark h2, .page-head.dark h3, .page-head.dark h4, .page-head.dark h5, .page-head.dark h6, .page-head.dark .info-wrapper {
          color: #f98507;
      }
  </style>

@stop