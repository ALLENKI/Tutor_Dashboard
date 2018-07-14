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
                <li class="current">
                    <a href="{{ url('/student-dashboard/learning') }}">
                        <i class="icon md-people"></i>
                        Learning
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
                <li>
                    <a href="{{ url('/student-dashboard/payment') }}">
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
            
            <div class="row">
             <h2 class="big">Upcoming Classes</h2>

                <div class="col-xs-6 col-sm-4 col-md-3">
                    <!-- MC ITEM -->
                    <div class="mc-teaching-item mc-item mc-item-2">

                        <div class="image-heading">
                            <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                        </div>
                        <div class="meta-categories"><a href="#">Web design</a></div>
                        <div class="content-item">
                            <h4><a href="course-intro.html">The Complete Digital Photography Course Amazon Top Seller</a></h4>
                        </div>
                        <div class="ft-item">
                            <div class="view-info">
                                <i class="icon md-users"></i>
                                12
                            </div>
                            <div class="comment-info">
                                <i class="icon md-comment"></i>
                                25
                            </div>
                            <div class="comment-info">
                                3 units
                            </div>
                        </div>

                        <div class="edit-view">
                            <a href="#" class="edit">Edit</a>
                            <a href="#" class="view">View</a>
                        </div>
                    </div>
                    <!-- END / MC ITEM -->
                </div>

                
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <!-- MC ITEM -->
                    <div class="mc-teaching-item mc-item mc-item-2">

                        <div class="image-heading">
                            <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                        </div>
                        <div class="meta-categories"><a href="#">Web design</a></div>
                        <div class="content-item">
                            <h4><a href="course-intro.html">The Complete Digital Photography Course Amazon Top Seller</a></h4>
                        </div>
                        <div class="ft-item">
                            <div class="view-info">
                                <i class="icon md-users"></i>
                                12
                            </div>
                            <div class="comment-info">
                                <i class="icon md-comment"></i>
                                25
                            </div>
                            <div class="comment-info">
                                3 units
                            </div>
                        </div>

                        <div class="edit-view">
                            <a href="#" class="edit">Edit</a>
                            <a href="#" class="view">View</a>
                        </div>
                    </div>
                    <!-- END / MC ITEM -->
                </div>

               
                <div class="col-xs-6 col-sm-4 col-md-3">
                    <!-- MC ITEM -->
                    <div class="mc-teaching-item mc-item mc-item-2">

                        <div class="image-heading">
                            <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                        </div>
                        <div class="meta-categories"><a href="#">Web design</a></div>
                        <div class="content-item">
                            <h4><a href="course-intro.html">The Complete Digital Photography Course Amazon Top Seller</a></h4>
                        </div>
                        <div class="ft-item">
                            <div class="view-info">
                                <i class="icon md-users"></i>
                                12
                            </div>
                            <div class="comment-info">
                                <i class="icon md-comment"></i>
                                25
                            </div>
                            <div class="comment-info">
                                3 units
                            </div>
                        </div>

                        <div class="edit-view">
                            <a href="#" class="edit">Edit</a>
                            <a href="#" class="view">View</a>
                        </div>
                    </div>
                    <!-- END / MC ITEM -->
                </div>

                 <div class="col-xs-6 col-sm-4 col-md-3">
                    <!-- MC ITEM -->
                    <div class="mc-teaching-item mc-item mc-item-2">

                        <div class="image-heading">
                            <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                        </div>
                        <div class="meta-categories"><a href="#">Web design</a></div>
                        <div class="content-item">
                            <h4><a href="course-intro.html">The Complete Digital Photography Course Amazon Top Seller</a></h4>
                        </div>
                        <div class="ft-item">
                            <div class="view-info">
                                <i class="icon md-users"></i>
                                12
                            </div>
                            <div class="comment-info">
                                <i class="icon md-comment"></i>
                                25
                            </div>
                            <div class="comment-info">
                                3 units
                            </div>
                        </div>

                        <div class="edit-view">
                            <a href="#" class="edit">Edit</a>
                            <a href="#" class="view">View</a>
                        </div>
                    </div>
                    <!-- END / MC ITEM -->
                </div>

            </div>

             <div class="row">
                <h2 class="big">Completed Classes</h2>

                <div class="col-xs-6 col-sm-4 col-md-3">
                    <!-- MC ITEM -->
                    <div class="mc-teaching-item mc-item mc-item-2">
                        <div class="image-heading">
                            <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                        </div>
                        <div class="meta-categories"><a href="#">Web design</a></div>
                        <div class="content-item">
                            <div class="image-author">
                                <img src="{{ cdn('assets/front/images/avatar-1.jpg') }}" alt="">
                            </div>
                            <h4><a href="course-intro.html">The Complete Digital Photography Course Amazon Top Seller</a></h4>
                        </div>
                        <div class="ft-item">
                            <div class="rating">
                                <a href="#" class="active"></a>
                                <a href="#" class="active"></a>
                                <a href="#" class="active"></a>
                                <a href="#"></a>
                                <a href="#"></a>
                            </div>
                             <div class="ft-item">
                            <div class="view-info">
                                <i class="icon md-users"></i>
                                12
                            </div>
                            <div class="comment-info">
                                <i class="icon md-comment"></i>
                                25
                            </div>
                            <div class="comment-info">
                                3 units
                            </div>
                        </div>
                        </div>

                        <div class="edit-view">
                            <a href="#" class="edit">Edit</a>
                            <a href="#" class="view">View</a>
                        </div>
                    </div>
                    <!-- END / MC ITEM -->
                </div>

                  <div class="col-xs-6 col-sm-4 col-md-3">
                    <!-- MC ITEM -->
                    <div class="mc-teaching-item mc-item mc-item-2">
                        <div class="image-heading">
                            <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                        </div>
                        <div class="meta-categories"><a href="#">Web design</a></div>
                        <div class="content-item">
                            <div class="image-author">
                                <img src="{{ cdn('assets/front/images/avatar-1.jpg') }}" alt="">
                            </div>
                            <h4><a href="course-intro.html">The Complete Digital Photography Course Amazon Top Seller</a></h4>
                        </div>
                        <div class="ft-item">
                            <div class="rating">
                                <a href="#" class="active"></a>
                                <a href="#" class="active"></a>
                                <a href="#" class="active"></a>
                                <a href="#"></a>
                                <a href="#"></a>
                            </div>
                             <div class="ft-item">
                            <div class="view-info">
                                <i class="icon md-users"></i>
                                12
                            </div>
                            <div class="comment-info">
                                <i class="icon md-comment"></i>
                                25
                            </div>
                            <div class="comment-info">
                                3 units
                            </div>
                        </div>
                        </div>

                        <div class="edit-view">
                            <a href="#" class="edit">Edit</a>
                            <a href="#" class="view">View</a>
                        </div>
                    </div>
                    <!-- END / MC ITEM -->
                </div>

                  <div class="col-xs-6 col-sm-4 col-md-3">
                    <!-- MC ITEM -->
                    <div class="mc-teaching-item mc-item mc-item-2">
                        <div class="image-heading">
                            <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                        </div>
                        <div class="meta-categories"><a href="#">Web design</a></div>
                        <div class="content-item">
                            <div class="image-author">
                                <img src="{{ cdn('assets/front/images/avatar-1.jpg') }}" alt="">
                            </div>
                            <h4><a href="course-intro.html">The Complete Digital Photography Course Amazon Top Seller</a></h4>
                        </div>
                        <div class="ft-item">
                            <div class="rating">
                                <a href="#" class="active"></a>
                                <a href="#" class="active"></a>
                                <a href="#" class="active"></a>
                                <a href="#"></a>
                                <a href="#"></a>
                            </div>
                             <div class="ft-item">
                            <div class="view-info">
                                <i class="icon md-users"></i>
                                12
                            </div>
                            <div class="comment-info">
                                <i class="icon md-comment"></i>
                                25
                            </div>
                            <div class="comment-info">
                                3 units
                            </div>
                        </div>
                        </div>

                        <div class="edit-view">
                            <a href="#" class="edit">Edit</a>
                            <a href="#" class="view">View</a>
                        </div>
                    </div>
                    <!-- END / MC ITEM -->
                </div>

                  <div class="col-xs-6 col-sm-4 col-md-3">
                    <!-- MC ITEM -->
                    <div class="mc-teaching-item mc-item mc-item-2">
                        <div class="image-heading">
                            <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                        </div>
                        <div class="meta-categories"><a href="#">Web design</a></div>
                        <div class="content-item">
                            <div class="image-author">
                                <img src="{{ cdn('assets/front/images/avatar-1.jpg') }}" alt="">
                            </div>
                            <h4><a href="course-intro.html">The Complete Digital Photography Course Amazon Top Seller</a></h4>
                        </div>
                        <div class="ft-item">
                            <div class="rating">
                                <a href="#" class="active"></a>
                                <a href="#" class="active"></a>
                                <a href="#" class="active"></a>
                                <a href="#"></a>
                                <a href="#"></a>
                            </div>
                             <div class="ft-item">
                            <div class="view-info">
                                <i class="icon md-users"></i>
                                12
                            </div>
                            <div class="comment-info">
                                <i class="icon md-comment"></i>
                                25
                            </div>
                            <div class="comment-info">
                                3 units
                            </div>
                        </div>
                        </div>

                        <div class="edit-view">
                            <a href="#" class="edit">Edit</a>
                            <a href="#" class="view">View</a>
                        </div>
                    </div>
                    <!-- END / MC ITEM -->
                </div>    
                
            </div>
        </div>
    </section>
    <!-- END / COURSE CONCERN -->



@stop