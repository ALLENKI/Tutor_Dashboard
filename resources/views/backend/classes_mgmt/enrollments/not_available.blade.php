<div class="">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Enrollment To {{ $class->topic->name }}</h5>
	</div>

	<div class="modal-body">
		<div class="alert alert-danger">
			Oops! Sorry, you are busy in these timings.
		</div>


		<div class="alert alert-danger">
			As an Admin, you can still enroll the student in class. Please be informed!
		</div>

		<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
		</div>

		@if(!$class->free)

		{!! BootForm::open()->action(route('admin::classes_mgmt::enrollment.direct_enroll',[$class->id,$student->id])) !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Confirm Enrollment <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}

		@else

		{!! BootForm::open()->action(route('admin::classes_mgmt::enrollment.free_enroll',[$class->id, $student->id])) !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Confirm Enrollment <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}

		@endif

	</div>

</div>
</div>


<script type="text/javascript">
	$(function(){

		$('form').ajaxForm({

			success: function(responseText, statusText, xhr){
				window.location.reload(true);
			},

			error: function(responseText, statusText, xhr, $form){
				var errors = responseText.responseJSON.errors;

				var errorsHtml= '';

				$.each( errors, function( key, value ) {
				    errorsHtml += '<li>' + value[0] + '</li>';
				});

				console.log(errorsHtml);

				$('#validation_errors').append(errorsHtml);
				$('#validation_errors').show();
			}

		});
	});
</script>