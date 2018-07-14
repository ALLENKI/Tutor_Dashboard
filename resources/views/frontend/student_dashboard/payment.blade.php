@extends('frontend.layouts.master')


@section('content')

    

    <!-- PROFILE FEATURE -->
    <section class="profile-feature">
        <div class="awe-parallax bg-profile-feature"></div>
        <div class="awe-overlay overlay-color-3"></div>
        <div class="container">
            <div class="info-author">
                <div class="image">
                    <img src="{{ cdn('assets/front/images/team-13.jpg') }}" alt="">
                </div>    
                <div class="name-author">
                    <h2 class="big">Anna Molly</h2>
                </div>     
                <div class="address-author">
                    <i class="fa fa-map-marker"></i>
                    <h3>Hanoi, Vietnam</h3>
                </div>
            </div>
            <div class="info-follow">
                <div class="trophies">
                    <span>12</span>
                    <p>Trophies</p>
                </div>
                <div class="trophies">
                    <span>12</span>
                    <p>Follower</p>
                </div>
                <div class="trophies">
                    <span>20</span>
                    <p>Following</p>
                </div>
            </div>
        </div>
    </section>
    <!-- END / PROFILE FEATURE -->


    <!-- CONTEN BAR -->
    <section class="content-bar">
        <div class="container">
            <ul>
                <li>
                    <a href="{{ url('/student-dashboard/teaching') }}">
                        <i class="icon md-people"></i>
                        Teaching
                    </a>
                </li>
                <li>
                    <a href="{{ url('/student-dashboard/assessment') }}">
                        <i class="icon md-shopping"></i>
                        Assessment
                    </a>
                </li>
                <li>
                    <a href="{{ url('/student-dashboard/profile') }}">
                        <i class="icon md-user-minus"></i>
                        Profile
                    </a>
                </li>
                <li class="current">
                    <a href="{{ url('/student-dashboard/profile') }}" class="current"">
                        <i class="icon md-email"></i>
                        Payments
                    </a>
                </li>
            </ul>
        </div>
    </section>
   <!-- END / CONTENT BAR -->

      <!-- COURSE CONCERN -->
    <section id="course-concern" class="course-concern">
        <div class="container">
            
            <div class="price-course">
                <i class="icon md-database"></i>
                <h3>Available Balance </h3>
                <span>$ 29,278</span>
                <div class="create-coures">
                    <a href="#" class="mc-btn btn-style-1">Create new course</a>
                    <a href="#" class="mc-btn btn-style-5">Request Payment</a>
                </div>

            </div>
        </div>
    </section>

@stop