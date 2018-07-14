@extends('frontend.layouts.static_master')

<?php $headerClass = ''; ?>

@section('content')

<section id="main-area">
    <section class="section">
      <div class="container">
        <div class="maintenance-message text-center"><i class="oli oli-attention"></i>
          <h1 class="title">MAINTENANCE MODE</h1>
          <h4 class="m-bottom-40">We are currently working on our website, we'll be back soon!</h4>
        </div>
        <div class="sp-blank-50"></div>
        <div class="shadow-line"></div>
        <div class="sp-blank-50"></div>
        <div class="row">
          <div class="col-md-4">
            <!-- #####Begin iconbox v4-->
            <div class="icon-box ib-v4"><i class="oli oli-alpha"></i>
              <h5 class="title">Why is the site down?</h5>
              <p>We are currently under maintenance or updating the website.</p>
            </div>
            <!-- #####End iconbox v4-->
          </div>
          <div class="col-md-4">
            <!-- #####Begin iconbox v4-->
            <div class="icon-box ib-v4"><i class="oli oli-beta"></i>
              <h5 class="title">What is the downtime?</h5>
              <p>It's usually 10 - 30 seconds. This page will automatically refresh.</p>
            </div>
            <!-- #####End iconbox v4-->
          </div>
          <div class="col-md-4">
            <!-- #####Begin iconbox v4-->
            <div class="icon-box ib-v4"><i class="oli oli-pi"></i>
              <h5 class="title">Do you need support?</h5>
              <p>Please mail us at contactus@ahamlearning.com</p>
            </div>
            <!-- #####End iconbox v4-->
          </div>
        </div>
      </div>
    </section>
  </section>
          
@stop