<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Cancel Series {{ $guestSeriesEpisode->name }}</h5>
	</div>

	<div class="modal-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		@if(!$canBeCancelled)

		<div class="alert alert-danger">
			You can't cancel this episode, as some users have already enrolled to this episode or to the level it belongs to.
		</div>

		@else


		{!! BootForm::open()->action(route('admin::classes_mgmt::guest_series_episodes.cancel',[$guestSeriesEpisode->id])) !!}

		<div class="row">

			<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
			</div>


				{!! 

					BootForm::text('Type "DELETE" to confirm*', 'confirm')
				              ->attribute('required','true') 
				!!}

		</div>

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}

		@endif
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