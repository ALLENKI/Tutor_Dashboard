<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Assign Classroom</h5>
	</div>

	<div class="modal-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::open()->action(route('admin::classes_mgmt::classes.assign_classroom',[$ahamClass->id,$unit])) !!}

		<div class="row">

			<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
			</div>


			<div class="col-md-12">
				{!! BootForm::select('Classroom *', 'classroom_id')
							->options($classrooms)
							->attribute('required','true')
							->attribute('class','form-control')
				!!}
			</div>

		</div>

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>

<script type="text/javascript">
	$(function(){

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