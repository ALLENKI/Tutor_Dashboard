<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Enrollment To {{ $class->topic->name }}</h5>
	</div>

	<div class="modal-body">
		<div class="alert alert-success">
			Hey! Please note that

			{{ $class->topic->units->count() }} credits will be deducted from your credit balance.
		</div>

		<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
		</div>

		{!! BootForm::open()->action(route('student::enrollment.store',[$class->id])) !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Confirm Enrollment <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
</div>


<script type="text/javascript">
	$(function(){

		$('form').ajaxForm({

			success: function(responseText, statusText, xhr){
				window.location.href = responseText.redirect;
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