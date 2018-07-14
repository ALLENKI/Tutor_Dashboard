
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Edit Level {{ $guestSeriesLevel->name }}</h5>
	</div>

	<div class="modal-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::open()->action(route('admin::classes_mgmt::guest_series_levels.edit_modal',[$guestSeriesLevel->id])) !!}

		{!! BootForm::bind($guestSeriesLevel) !!}

		<div class="row">

			<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
			</div>

			{!! 

				BootForm::text('Name', 'name')
			              ->attribute('required','true') 
			!!}

		<div class="form-group {!! $errors->has('enrollment_cutoff') ? 'has-error' : '' !!}">
		<label for="name">Enrollment Cutoff *</label>
		<input size="16" type="text" value="{{ $guestSeriesLevel->enrollment_cutoff ?  $guestSeriesLevel->enrollment_cutoff->format('d-m-Y h:i') : '' }}" readonly class="form-control form_datetime" name="enrollment_cutoff">
		</div>

			{!! 

				BootForm::textarea('Description *', 'level_description')
			      ->attribute('rows',3) 
			      ->attribute('id','level_description')
			      ->value($guestSeriesLevel->description)
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

		$('#level_description').summernote({
			defaultFontName: 'Open Sans',

			toolbar: [
			    ['style', ['style']],
			    ['font', ['bold', 'italic', 'underline', 'clear']],
			    ['fontname', ['fontname']],
			    ['fontsize', ['fontsize']],
			    ['color', ['color']],
			    ['para', ['ul', 'ol', 'paragraph']],
			    ['height', ['height']],
			    ['table', ['table']],
			    ['insert', ['link', 'picture', 'hr']],
			    ['view', ['fullscreen', 'codeview']],
			    ['help', ['help']]
			  ],

			fontNames: [
				'Open Sans'
			],

			fontNamesIgnoreCheck: [
				'Open Sans'
			]

		});

		$(".form_datetime").datetimepicker(
			{
				format: 'dd-mm-yyyy hh:ii',
				pickerPosition: "top-right",
				minView: 'day',
				minuteStep: 60,
				autoclose: true
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