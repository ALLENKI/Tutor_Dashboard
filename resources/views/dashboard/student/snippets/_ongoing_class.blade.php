<?php $ahamClass = $ongoingTiming->ahamClass; ?>
<?php $topic = $ahamClass->topic; ?>
<?php
	$image_url = cloudinary_url($topic->present()->picture, array("height"=>250, "width"=>375, "crop"=>"thumb", "secure" => true,"quality"=>"auto:eco", "f_auto"));
?>


<div class="panel panel-white">

	<div class="panel-heading">
		<small class="panel-title">
			{{ $topic->lookup->subject->name }} > 
			{{ $topic->lookup->subCategory->name }} 
		</small>

		<div class="heading-elements">
			<span class="heading-text">
				{{ $ahamClass->code }}
			</span>

			<span class="heading-text">
				<i class="icon-book position-left"></i>
				{{ $ahamClass->enrolled }} Enrolled
			</span>
			<span class="label label-default heading-text">
				{{ strtoupper(str_replace('_', ' ', $ahamClass->status)) }}
			</span>
		</div>
	</div>

	<div class="panel-body">

		<ul class="media-list content-group">
			<li class="media stack-media-on-mobile">
				<div class="media-left text-center">
					<div class="thumb">
						<a href="#">
							<img src="{{ $image_url }}" class="img-responsive img-rounded media-preview" alt="">
							<span class="zoom-image">
								<i class="icon-link"></i>
							</span>
						</a>
					</div>
					
				</div>

				<div class="media-body">

					<h6 class="media-heading">
						<a href="{{ route('teacher::courses.show',$topic->slug) }}">
							<strong>{{ $ongoingTiming->classUnit->name }}</strong>
							of 
							{{ $ahamClass->topic_name }}
						</a>
					</h6>

            		<ul class="list-inline list-inline-separate text-muted mb-5">
            			<li>
            			<i class="icon-watch position-left"></i> 
            			On {{ $ongoingTiming->date->format('jS M Y') }} from {{ $ongoingTiming->start_time  }} to {{ $ongoingTiming->end_time }}
            			</li>
            		</ul>
					{!! $ahamClass->topic_description !!}
				</div>
			</li>
		</ul>

	</div>

	<div class="panel-footer">
		<div class="heading-elements">
			<a href="{{ route('student::class.show',$ahamClass->code) }}" class="btn btn-xs btn-success pull-right">Go to Class Page<i class="icon-check position-right"></i></a>
		</div>
	</div>

</div>