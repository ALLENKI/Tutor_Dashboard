<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Cancel Enrollment To {{ $class->topic->name }}</h5>
	</div>

	<div class="modal-body">
		<div class="alert alert-success">
			You are about to cancel enrollment this class
		</div>

		<div class="checkbox">
			<label>
				<input type="checkbox" id="confirm_again" class="styled">
				I am sure I want to cancel my enrollment.
			</label>
		</div>

		<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
		</div>

		{!! 
			BootForm::open()
					->action(route('student::enrollment.cancel',[$class->id])) 
					->attribute('id','needs_confirmation') 
					->attribute('style','display:none;') 

			!!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Cancel Enrollment <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>


<script type="text/javascript">
	$(function(){

	    $("#confirm_again").on('change',function(){
	    	$("#needs_confirmation").toggle();
	    });

		$('form').ajaxForm({

			success: function(){
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