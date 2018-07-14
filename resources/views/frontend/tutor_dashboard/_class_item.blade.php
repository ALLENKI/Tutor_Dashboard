<?php
$image_url = cloudinary_url($ahamClass->topic->present()->picture,['width' => 300, 'height' => 428, "crop"=>"thumb"]);
?>
<div class="pub-item with-thumb large">
<div class="elem-wrapper">
<img src="{{ $image_url }}" alt="item thumbnail" class="pub-thumbnail">
</div>

<div class="content-wrapper">

<h3 class="title"><a href="#">{{ $ahamClass->topic->name }}</a></h3>

<div class="description">
<div class="row">
		<div class="col-md-6">
      	<ul class="list-unstyled">
  		@foreach($ahamClass->topic->units as $index => $unit)
  		<li>
  			Unit {{ $index+1 }} : {{ schedule($unit->id,$ahamClass->id) }}
  		</li>
  		@endforeach

      	</ul>
		</div>

		<div class="col-md-6">
			{!! $ahamClass->location->name !!},<br>
			{!! $ahamClass->location->present()->address !!}
			<hr>
			<a href="{{ url('classes-in-'.$ahamClass->topic->slug) }}" class="link-with-icon p-right-10"> <i class="fa fa-external-link"></i>Course Page</a>
			
			@if($ahamClass->status == 'scheduled' || $ahamClass->status == 'in_session')
			<a href="{{ route('class::index',$ahamClass->code) }}" class="link-with-icon"> <i class="fa fa-external-link"></i>Class Page</a>
			@endif
		</div>
	</div>
</div>

</div>
</div>