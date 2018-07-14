<?php $topic = $pendingInvite->ahamClass->topic; ?>
<?php $class = $pendingInvite->ahamClass; ?>
<div class="panel panel-flat">
	<div class="panel-heading">
		<h6 class="panel-title">
		{{ $topic->lookup->subject->name }} > 
		{{ $topic->lookup->subCategory->name }} 
		</h6>
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

		<a href="#alpaca-basic-source-{{ $class->id }}" data-toggle="collapse">
		<i class="icon-list position-left"></i> 
		Show Timings &rarr;
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

	<div class="panel-footer">
		<div class="heading-elements">
			<a href="#" class="btn btn-xs btn-danger pull-left" data-url="{{ route('teacher::invitations.decline_modal', [$pendingInvite->id]) }}" onclick='openAjaxModal(this);'>Decline</a>
		</div>
	</div>

</div>