	<?php
		$image_url = cloudinary_url($enrolledClass->topic->present()->picture, array("height"=>80, "width"=>80, "crop"=>"thumb", 'secure' => true));

		$ahamClass = $enrolledClass;
	?>

<tr>

	<td>
		<div class="media-left media-middle">
			<a href="#"><img src="{{ $image_url }}" class="img-circle img-xs" alt=""></a>
		</div>
		<div class="media-left">
			<div class=""><a href="#" class="text-default text-semibold">
				{{ $ahamClass->topic->name }}
			</a></div>
			@if($ahamClass->start_date)
			<div class="text-muted text-size-small">
				<span class="status-mark border-blue position-left"></span>
				{{ $ahamClass->start_date->format('jS M Y') }}
			</div>
			@endif

			<span class="label bg-danger">{{ ucfirst($ahamClass->status) }}</span>
		</div>
	</td>

	<td>
		@if($ahamClass->status ==  'open_for_enrollment' || $ahamClass->status == 'scheduled')
		<a href="#" class="btn btn-xs btn-danger pull-right" data-url="{{ route('student::enrollment.cancel_modal', [$ahamClass->id]) }}" onclick='openAjaxModal(this);'>Cancel Enrollment<i class="icon-arrow-right14 position-right"></i></a>
		@endif
	</td>
	

</tr>
<br>
<hr>