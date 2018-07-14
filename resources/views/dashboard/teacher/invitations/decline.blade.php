<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Invitation For {{ $invitation->ahamClass->topic->name }}</h5>
	</div>

	<div class="modal-body">
		<div class="alert alert-danger">
			You are about to decline the class, please select a reason. You can also add your remarks 
		</div>

		<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
		</div>

		{!! BootForm::open()->action(route('teacher::invitations.decline',[$invitation->id])) !!}

	    {!! BootForm::select('Decline Reason', 'decline_reason')
	          ->options([
	                '' => 'Please select your reason for declining *',
	                'Not Available' => 'Not Available',
	                'Not Interested' => 'Not Interested',
	                'Other' => 'Other',
	            ])
	    !!}

       {!! BootForm::textarea('Decline Remarks', 'decline_remarks')
                      ->placeholder('If you have anything to add.. ')
                      ->attribute('rows',3)
       !!}

		<div class="text-right">
			<button type="submit" class="btn btn-danger">
				Decline
				<i class="icon-arrow-right14 position-right"></i>
			</button>
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