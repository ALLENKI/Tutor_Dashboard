
<div class="col-md-4">
<?php
	$image_url = cloudinary_url($course->present()->picture, array("height"=>250, "width"=>375, "crop"=>"thumb", 'secure' => true));
?>
<a href="{{ url('classes-in-'.$course->slug) }}" class="course-el set-bg {{ $skins[array_rand($skins)] }}" style="background-image: url({{ $image_url }});">
<img src="{{ $image_url }}" class="set-me" style="display: none;">

<div class="contents">
  <div class="title-wrapper">
    <h6 class="sub-title">{{ $course->parent->name }}</h6>
    <h2 class="title">{{ $course->name }}</h2>
  </div>
</div></a>
<!-- #####End course element-->
</div>
