<?php $topic = $class->topic; ?>
<div class="panel panel-flat">
	<div class="panel-heading">
		<h6 class="panel-title">{{ $topic->lookup->subject->name }} > {{ $topic->lookup->subCategory->name }} </h6>

		<div class="heading-elements">
			<span class="heading-text"><i class="icon-users4 position-left"></i>{{ $class->maximum_enrollment - $class->enrolled }} Left</span>
			<span class="heading-text"><i class="icon-book position-left"></i>{{ $class->enrolled }} Enrolled</span>
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
									<img src="https://res.cloudinary.com/ahamlearning/image/upload/c_thumb,h_550,w_550/aham_icon_m6ljr5.png" class="img-responsive img-rounded media-preview" alt="">
									<span class="zoom-image"><i class="icon-link"></i></span>
								</a>
							</div>

							<span class="label label-primary">{{ $topic->units->count() }} Units</span>
							
						</div>

    					<div class="media-body">
							<h6 class="media-heading">
							<a href="{{ route('student::courses.show',$topic->slug) }}">{{ $topic->name }}</a>
							<span class="pull-right"><small style="color: #a90909;"> Min. Enrollment: {{ $class->minimum_enrollment }} <span data-popup="popover" data-trigger="hover" data-placement="top" data-content="This class is currently open for enrollment and is scheduled to run only if Minimum enrollment is met.">
								<i class="icon-help"></i>
							</span> </small></span>
							</h6>
                    		<ul class="list-inline list-inline-separate text-muted mb-5">
                    			@if($class->teacher)
                    			<li>
                    			<i class="icon-user position-left"></i> 
                    			by {{ $class->teacher->user->name }}
                    			</li>
                    			@endif

                    			@if($class->start_date)
                    			<li>
                    			<i class="icon-watch position-left"></i> 
                    			Starting on {{ $class->start_date->format('jS M Y, D') }}
                    			</li>
                    			<br>
                    			@endif

                    			<li>
                    			<i class="icon-map position-left"></i> 
                    			at {{ $class->location->name }}
                    			</li>
                    		</ul>
							{{ $topic->description }}

						</div>
					</li>

					
				</ul>
			</div>
		</div>

		<a href="#alpaca-basic-source-{{ $class->id }}" data-toggle="collapse">
		<i class="icon-list position-left"></i> 
		Show Timings &rarr;

		@if($class->free)
		<img src="{{ cdn('assets/dashboard/images/free.png') }}" style="
			float: right;
			width: 60px;
			margin-right: 39px;
			margin-top: -5px;
		">
		@else
		<span class="label label-success pull-right" style="font-size: 13px;border-color: #2196f3;background-color: #2196f3;">Cost : {{ $topic->units->count() }} Credits</span>
		@endif

		</a>

		<div class="collapse mt-10" id="alpaca-basic-source-{{$class->id}}">
		<table class="table table-bordered table-striped table-condensed">
			<tbody>
				@foreach($class->topic->units as $index => $unit)
				<tr>
					<td>{{ $unit->name }}</td>
					<td>{{ schedule($unit->id,$class->id) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		</div>


	</div>

	@if(!in_array($class->id, $enrollments))
	<div class="panel-footer">
		<div class="heading-elements">
			<a class="btn btn-xs btn-warning pull-left not_interested" data-url="{{ route('student::classes.not_interested',$class->id) }}">
				Not Interested
			</a>
			<a href="#" class="btn btn-xs btn-success pull-right" data-url="{{ route('student::enrollment.show', [$class->id]) }}" onclick='openAjaxModal(this);'>Enroll Now <i class="icon-arrow-right14 position-right"></i></a>
		</div>
	</div>
	@else
	<div class="panel-footer">
		<div class="heading-elements">
			<a href="{{ route('student::courses.show',$class->topic->slug).'#'.$class->code }}" class="btn btn-xs btn-primary pull-right">You are Enrolled<i class="icon-check position-right"></i></a>
		</div>
	</div>

	@endif

</div>