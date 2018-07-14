@extends('frontend.layouts.master')


@section('content')

<!-- SUB BANNER -->
<section class="sub-banner section">
<div class="awe-parallax bg-profile-feature"></div>
<div class="awe-overlay overlay-color-3"></div>
<div class="container">
<div class="row">
<div class="col-md-6">
    <div class="sub-banner-content">
        <h2 class="big" style="font-weight: bold;">{{ $topic->name }}</h2>
        <p>{{ $topic->description }}</p>

        @if(Sentinel::check() && $user->student)
        <a href="#" class="mc-btn btn-style-3">Enroll Now</a>
        @endif

    </div>
</div>
<div class="col-md-6">
     <iframe src="https://docs.google.com/presentation/d/1pXs2UkfPLlYf1-qG21PTyobbgKMeOwpx2XDohQS-sQY/embed?start=false&loop=false&delayms=3000" frameborder="0" width="420" height="300" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"></iframe>
</div>
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
        <option value="">2</option>
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

<div class="col-md-5">
    <div class="content grid">
        <div class="row" style="margin-bottom: 50px;">

        <!-- ITEM -->
           
        <div class="col-sm-6 col-md-9">

        @foreach($topic->units as $unit)

           <h3 style="line-height: 1em;"><em style="font-size: 16px;"></em> <br>{{ $unit->name }}</h3>

                <p>{{ $unit->description }}.</p>

        @endforeach                      
            </div>
            <!-- END / ITEM -->

        </div>

        

  </div>
</div>

<div class="col-md-7">
    <h3>Upcoming Classes</h3>

    <table class="table">
        <thead>
            <th>#</th>
            <th>Location</th>
            <th>Timings</th>
            <th>Action</th>
        </thead>  

        <tbody>
            @foreach($topic->classes as $ahamClass)
            <tr>
                <td>{{ $ahamClass->code }}</td>
                <td>{{ $ahamClass->location->name }}</td>
                <td>
                    
                    <table class="table table-framed">

                        <thead>
                            <tr>
                                <th>Unit</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($ahamClass->topic->units as $unit)
                            <tr>
                                <td>
                                    {{ $unit->name }}
                                </td>
                                <td>
                                    {{ schedule($unit->id,$ahamClass->id) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>

                </td>
                <td>
                    @if($user->student->enrollments()->where('class_id',$ahamClass->id)->count() == 0)

                    <?php
                        $available = false;

                        $available = Aham\Helpers\StudentHelper::isAvailable($ahamClass, $user->student);
                    ?>
                    @if($available)
                    <a href="{{ route('student_dashboard::class.enroll',$ahamClass->id) }}" class="rest" data-method="POST">Enroll</a>
                    @else
                    Not Available
                    @endif
                    @else
                    Enrolled
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>    
    </table>
</div>

</div>
</div>
</section>
<!-- END / CATEGORIES CONTENT -->

@stop