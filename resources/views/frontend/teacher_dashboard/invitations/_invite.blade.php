<?php $ahamClass = $invitation->ahamClass; ?>

<?php
	$image_url = cloudinary_url($invitation->ahamClass->topic->present()->picture,['width' => 290, 'height' => 290]);
?>

<div class="course-el-regular">
<div class="row">
  <div class="col-md-3">
	  <a href="{{ url('classes-in-'.$invitation->ahamClass->topic->slug) }}" class="course-thumb set-bg" style="background-image: url({{ $image_url }});">
	  <img src="{{ $image_url }}" style="display: none;">
	  </a>


  </div>
  <div class="col-md-9">
    <div class="contents">

      <h2 class="title">
      	<a 
      	href="{{ url('classes-in-'.$invitation->ahamClass->topic->slug) }}">
      	{{ $invitation->ahamClass->topic->name }} 
      	<small>( {{ $invitation->ahamClass->code }} )</small>
      	</a>

		@if($invitation->status == 'pending')
			<a href="{{ route('tutor::invitations.accept',[$teacher->user->username,$invitation->id]) }}" class="btn btn-small btn-skin-blue btn-circle pull-right">Accept</a>  
		@endif

		@if($invitation->status == 'accepted')
			<a href="#" class="btn btn-small btn-skin-green btn-circle pull-right">Accepted</a>  
		@endif

      </h2>

      <ul class="course-meta">
        <li>
          <div class="course-instructor"><span>Location</span><span>{{ $invitation->ahamClass->location->name }}</span></div>
        </li>
        <li><span>Category</span><span>{{ $invitation->ahamClass->topic->parent->name }}</span></li>
        <li><span>Units</span><span>{{ $invitation->ahamClass->topic->units->count() }}</span></li>
      </ul>
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
      		{!! $invitation->ahamClass->location->present()->address !!}
      		<br><br>

	      	<div class="google-maps-container">
		      	<iframe
				  width="100%"
				  height="250"
				  frameborder="0" style="border:0"
				  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBtXtGg_l44vgS2_GKR4ewlxHzkdYY8SQg&q={{$invitation->ahamClass->location->present()->latlng}}&zoom=18" allowfullscreen>
				</iframe>
			</div>

      	</div>

      </div>


      </div>
    </div>
  </div>
</div>
</div>

<hr>