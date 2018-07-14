<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Thanks for your interest.</h5>
	</div>

	<div class="modal-body">
		<div class="alert alert-success">
			Please confirm. 

		</div>

		<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
		</div>

		{!! BootForm::open()->action(route('teacher::courses.interested',[$topic->id])) !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Confirm<i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>


<script type="text/javascript">
	$(function(){

		$('form').ajaxForm({

			success: function(){

	// 		$.toast({
	//             text: 'Request sent, Thank you.',
	//             position: {
	//                 right: 20,
	//                 top: 120
	//             },
	//             stack: false,
	//             hideAfter : false,
	//             allowToastClose : true,
	//             icon: "success"
	//         });
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