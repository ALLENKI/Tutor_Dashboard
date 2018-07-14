<?php $topic = $feedbackClass->topic; ?>
<?php $class = $feedbackClass; ?>
<div class="panel panel-flat">
	<div class="panel-heading">
		<h6 class="panel-title">
		{{ $topic->lookup->subject->name }} > 
		{{ $topic->lookup->subCategory->name }} 
		</h6>

		<div class="heading-elements">
			<span class="heading-text"><i class="icon-book position-left"></i>{{ $class->enrolled }} Enrolled</span>
			<span class="heading-text">
				{{ strtoupper(str_replace('_', ' ', $class->status)) }}
			</span>
		</div>

	</div>

	<div class="panel-body" style="display: block;">
		<div class="row">
			<div class="col-lg-12">
				<ul class="media-list content-group">
					<li class="media stack-media-on-mobile">
    					<div class="media-left text-center">
							<div class="thumb">
								<a href="#">
									<img src="http://res.cloudinary.com/ahamlearning/image/upload/c_thumb,h_550,w_550/aham_icon_m6ljr5.png" class="img-responsive img-rounded media-preview" alt="">
									<span class="zoom-image"><i class="icon-link"></i></span>
								</a>
							</div>

							<span class="label label-primary">{{ $topic->units->count() }} Units</span>
							
						</div>

    					<div class="media-body">
							<h6 class="media-heading"><a href="{{ route('teacher::courses.show',$topic->slug) }}">{{ $topic->name }}</a></h6>
                    		<ul class="list-inline list-inline-separate text-muted mb-5">
                    			<li>
                    			<i class="icon-watch position-left"></i> 
                    			Starting on {{ $class->timings->first()->date->format('jS M Y, D') }}
                    			</li>
                    		</ul>
							{{ $topic->description }}

						</div>
					</li>

					
				</ul>
			</div>
		</div>

	</div>

	<div class="panel-footer">
		<div class="heading-elements">
			<a href="{{ route('teacher::class.show',$class->code) }}" class="btn btn-xs btn-success pull-right">Go to Class Page<i class="icon-check position-right"></i></a>
		</div>
	</div>

</div>