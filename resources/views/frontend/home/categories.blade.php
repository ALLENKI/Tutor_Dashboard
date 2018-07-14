@extends('frontend.layouts.master')


@section('content')

 <!-- SUB BANNER -->
    <section class="sub-banner sub-banner-course">
        <div class="awe-static bg-sub-banner-course"></div>
        <div class="container">
            <div class="sub-banner-content">
                <h2 class="text-center">Aham Courses</h2>
            </div>
        </div>
    </section>
    <!-- END / SUB BANNER -->


    <!-- PAGE CONTROL -->
    <section class="page-control">
        <div class="container">
            <div class="page-info">
                <a href="index.html"><i class="icon md-arrow-left"></i>Back to home</a>
            </div>
            <div class="page-view">
                View
                <span class="page-view-info view-grid active" title="View grid"><i class="icon md-ico-2"></i></span>
                <span class="page-view-info view-list" title="View list"><i class="icon md-ico-1"></i></span>
                <div class="mc-select">
                    <select class="select" name="" id="all-categories">
                        <option value="">All level</option>
                        <option value="">Beginner</option>
                        <option value="">Intermediate</option>
                        <option value="">Advanced</option>
                    </select>
                </div>
            </div>
        </div>
    </section>
    <!-- END / PAGE CONTROL -->
    
    <!-- CATEGORIES CONTENT -->
    <section id="categories-content" class="categories-content">
        <div class="container">
            <div class="row">
    
                <div class="col-md-9 col-md-push-3">
                    <div class="content grid">
                        <div class="row">


                            @foreach($topics as $topic)
                            <!-- END / ITEM -->
                            <div class="col-sm-6 col-md-4">
                                <div class="mc-item mc-item-2">
                                    <div class="image-heading">
                                        <img src="{{ cdn('assets/front/images/feature/img-1.jpg') }}" alt="">
                                    </div>
                                    @if($topic->parent)
                                    <div class="meta-categories"><a href="#">{{ $topic->parent->name }}</a></div>
                                    @endif
                                    <div class="content-item">
                                        <h4><a href="{{ route('course',$topic->slug) }}">{{ $topic->name }}</a></h4>
                                    </div>
                                    <div class="ft-item">
                                        <div class="rating">
                                            <a href="#" class="active"></a>
                                            <a href="#" class="active"></a>
                                            <a href="#" class="active"></a>
                                            <a href="#"></a>
                                            <a href="#"></a>
                                        </div>
                                        <div class="view-info">
                                            <i class="icon md-users"></i>
                                            2568
                                        </div>
                                        <div class="comment-info">
                                            <i class="icon md-comment"></i>
                                            25
                                        </div>
                                        <div class="price">
                                            $123
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- END / ITEM -->
                            @endforeach
                            
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR CATEGORIES -->
                <div class="col-md-3 col-md-pull-9">
                    <aside class="sidebar-categories">
                        <div class="inner">
    
                            <!-- WIDGET TOP -->
                            <div class="widget">
                                <ul class="list-style-block">
                                    <li class="current"><a href="#">Featured</a></li>
                                    <li><a href="#">Aham picks</a></li>
                                    <li><a href="#">Free</a></li>
                                    <li><a href="#">Top Courses</a></li>
                                </ul>
                            </div>
                            <!-- END / WIDGET TOP -->
    
                            <!-- WIDGET CATEGORIES -->
                            <div class="widget widget_categories">
                                <ul class="list-style-block">
                                    <li><a href="#">Physics</a></li>
                                    <li><a href="#">Chemistry</a></li>
                                    <li><a href="#">Biology</a></li>
                                    <li><a href="#">Mathematics</a></li>
                                    <li><a href="#">Web Development</a></li>
                                    <li><a href="#">English</a></li>
                                    <li><a href="#">Problem Solving - Physics</a></li>
                                    <li><a href="#">Problem Solving - Mathematics</a></li>
                                    <li><a href="#">Problem Solving - IIT Foundation</a></li>
                                    <li><a href="#">Algorithms</a></li>
                                    <li><a href="#">Programming Basics</a></li>
                                    <li><a href="#">PHP - MySQL</a></li>
                                    <li><a href="#">HTML5</a></li>
                                </ul>
                            </div>
                            <!-- END / WIDGET CATEGORIES -->

                        </div>
                    </aside>
                </div>
                <!-- END / SIDEBAR CATEGORIES -->
    
            </div>
        </div>
    </section>
    <!-- END / CATEGORIES CONTEN

@stop