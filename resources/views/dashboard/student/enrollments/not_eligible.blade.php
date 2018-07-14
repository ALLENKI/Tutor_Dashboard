<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Enrollment To {{ $class->topic->name }}</h5>
	</div>

	<div class="modal-body">
		<div class="alert alert-danger">
			Hey! You are not eligible to take this class.
		</div>

		<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
		</div>

		<p>
			Why Am I Not Eligible? <br>
			Check prerequisites for this course -> <a href="{{ route('student::courses.prerequisites',$class->topic->slug) }}">HERE</a>

			<hr>

			Can I still enroll?<br>

			Yes, But note that we don't recommend you do this. And we won't be marking you as assessed for this topic.<br>

			{{ $class->topic->units->count() }} credits will be deducted from your credit balance if you enroll.

				<div class="checkbox">
					<label>
						<input type="checkbox" id="still_enroll" class="styled">
						I want to enroll
					</label>
				</div>
		</p>

		{!! 
			BootForm::open()
					->action(route('student::enrollment.enroll_as_ghost',[$class->id]))
					->attribute('id','can_enroll') 
					->attribute('style','display:none;') 

		!!}

		<div class="text-right">
			<button type="submit" class="btn btn-danger">Confirm Enrollment <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>
</div>


<script type="text/javascript">
	$(function(){

	    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});

	    $("#still_enroll").on('change',function(){
	    	$("#can_enroll").toggle();
	    });

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