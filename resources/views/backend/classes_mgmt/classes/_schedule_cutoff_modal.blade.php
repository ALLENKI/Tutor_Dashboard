<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Change Schedule Cutoff Date and Time</h5>
	</div>

	<div class="modal-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::open()->action(route('admin::classes_mgmt::classes.schedule_cutoff_modal',[$ahamClass->id])) !!}

		<div class="row">

			<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
			</div>


			<div class="col-md-6">
				
				<input size="16" type="text" value="{{ $ahamClass->schedule_cutoff->format('d-m-Y h:i') }}" readonly class="form-control form_datetime" name="schedule_cutoff">

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

			$(".form_datetime").datetimepicker(
				{
					format: 'dd-mm-yyyy hh:ii',
					pickerPosition: "top-right",
					minView: 'day',
					minuteStep: 60,
					autoclose: true,
					startDate: '{{ $ahamClass->schedule_cutoff->format('Y-m-d H:i') }}',
					endDate: '{{ $ahamClass->start_date->subHours(1)->format('Y-m-d H:i') }}'
				}
			);


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