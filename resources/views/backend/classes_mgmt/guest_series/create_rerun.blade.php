<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Create Rerun of {{ $originalEpisode->name }}</h5>
	</div>

	<div class="modal-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::open()->action(route('admin::classes_mgmt::guest_series_episodes.rerun_modal',[$originalEpisode->id])) !!}

		<div class="row">

			<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
			</div>

			{!! BootForm::select('Topic *', 'topic_id')
						->options($topics)
						->attribute('required','true')
						->attribute('id','topic_id')
						->select($originalEpisode->id)
			!!}

			{!! 

				BootForm::text('Name', 'name')
			              ->attribute('required','true') 
			              ->attribute('value',$originalEpisode->name)
			!!}


			{!! 

				BootForm::text('Date Summary *', 'date_summary')
			              ->placeholder('Date Summary')
			              ->attribute('class','form-control')
			              ->attribute('required','true') 
			              ->attribute('value',$originalEpisode->date_summary)
			!!}
			
			{!! 

				BootForm::text('Time Summary *', 'time_summary')
			              ->placeholder('Time Summary')
			              ->attribute('class','form-control')
			              ->attribute('required','true') 
			              ->attribute('value',$originalEpisode->time_summary) 
			!!}

		</div>

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>

</div>

<script type="text/javascript">
	$(function(){

		$('#topic_id').select2();

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