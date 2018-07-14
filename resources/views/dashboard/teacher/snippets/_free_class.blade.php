<div class="panel panel-white">
	<div class="panel-heading">
		<h6 class="panel-title">{{ $topic->lookup->subject->name }} > {{ $topic->lookup->subCategory->name }} </h6>
	</div>

	<?php
		$image_url = cloudinary_url($topic->present()->picture, array("height"=>250, "width"=>250, "crop"=>"thumb", 'secure' => true));
	?>

	<div class="panel-body" style="display: block;min-height: 195px;">
		<div class="row">
			<div class="col-lg-12">
				<ul class="media-list content-group">
					<li class="media stack-media-on-mobile">
    					<div class="media-left text-center">
							<div class="thumb">
								<a href="#">
									<img src="{{ $image_url }}" class="img-responsive img-rounded media-preview" alt="">
									<span class="zoom-image"><i class="icon-link"></i></span>
								</a>
							</div>

							<span class="label label-primary">{{ $topic->units->count() }} Units</span>

						</div>

    					<div class="media-body">
							<h6 class="media-heading"><a href="{{ route('teacher::courses.show',$topic->slug) }}">{{ $topic->name }}</a></h6>
							{{ str_limit($topic->description,100) }}
						</div>
					</li>

					
				</ul>
			</div>
		</div>

		<?php
			$classes = $topic->classes()->whereIn('status',['open_for_enrollment','scheduled'])
                        ->orderBy('start_date','asc')->get();
		?>

		@if($classes->count())
		<a href="#alpaca-basic-source-{{ $topic->id }}" data-toggle="collapse">
		<i class="icon-list position-left"></i> 
		Show Classes &rarr;
		</a>

		<div class="collapse mt-10" id="alpaca-basic-source-{{$topic->id}}">
		<table class="table table-bordered table-striped table-condensed">
			<tbody>
				@foreach($classes as $class)
				<tr>
					<td>{{ $class->code }}</td>

					@if(!is_null($class->start_date))
						<td>Starting on {{ $class->start_date->format('jS M Y, D') }}</td>
					@endif

				</tr>
				@endforeach
			</tbody>
		</table>
		</div>
		@endif

	</div>

	<div class="panel-footer">
		<div class="heading-elements">
			<a href="{{ route('teacher::courses.show',$topic->slug) }}" class="btn btn-xs btn-success pull-right">Go To Course Page <i class="icon-arrow-right14 position-right"></i></a>
		</div>
	</div>

</div>