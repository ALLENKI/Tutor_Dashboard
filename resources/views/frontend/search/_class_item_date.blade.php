<div class="col-md-4">
<?php
	$classCourse = $ahamClass->topic;
	$classTeacher = $ahamClass->teacher;
?>
<?php
	$image_url = cloudinary_url($classCourse->present()->picture, array("height"=>250, "width"=>375, "crop"=>"thumb"));
?>
<a href="{{ url('classes-in-'.$classCourse->slug) }}" class="course-el set-bg" style="background-image: url({{ $image_url }});">
<img src="{{ $image_url }}" class="set-me" style="display: none;">

<div class="contents">

	<div class="item-meta">
	<div class="authors-thumb"><img src="{{ cloudinary_url($classTeacher->user->present()->picture,['width' => 100, 'height' => 100]) }}"></div>
	<div class="side-info"><span class="course-instructor">{{ $classTeacher->user->name }}</span></div>
	</div>

	<div class="title-wrapper">
	<h6 class="sub-title">{{ $classCourse->parent->name }}</h6>
	<h2 class="title">{{ $classCourse->name }}</h2>
	<div class="course-shop-data">
	<button class="course-purchase-btn">
	<i class="oli oli-shopping_bag_filled"></i>
	<span>{{ $classCourse->units->count() }} Units</span>
	</button>
	    <div class="ol-review-rates">
	    <small>Starts On</small>
	    </div>
	    <div class="sub-meta"><span>{{ $ahamClass->start_date->format('jS M Y') }}</span></div>
	  </div>

	</div>
</div></a>
<!-- #####End course element-->
</div>