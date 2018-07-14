<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Pick Date and Slots</h5>
	</div>

	<div class="modal-body">
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::open()->action(route('admin::classes_mgmt::guest_series_episodes.schedule_modal',[$episode->id])) !!}

		<div class="row">

			<div class="alert alert-danger no-border" id="validation_errors" style="display:none;">
			</div>


			<div class="col-md-6">

				{!! 

					BootForm::text('Date *', 'unit_date')
				              ->placeholder('Date')
				              ->attribute('class','form-control unit_date')
				              ->attribute('id','unit_date')
				              ->attribute('required','true') 
				!!}

			</div>

			<div class="col-md-6">
				{!! BootForm::select('Slot *', 'unit_slots[]')
							->options([])
							->attribute('required','true')
							->attribute('id','unit_slot')
							->attribute('multiple','true')
							->attribute('class','form-control multiselect unit_slot')
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

	    $('.multiselect').multiselect({
	        onChange: function() {
	            $.uniform.update();
	        }
	    });

	    $(".styled, .multiselect-container input").uniform({
	        radioClass: 'choice'
	    });


	    $('#unit_date').daterangepicker({ 
	    	drops: 'up',
	        singleDatePicker: true,
	        startDate: {!! $startDate !!},
	        minDate: {!! $startDate !!},
			locale: {
		      format: 'DD-MM-YYYY'
		    },
	    }).trigger('change');

	    $('#unit_date').on('change',function(){

	    	//Fetch available slots

	    	$.get('/api/get_available_slots_for_episodes?'+'date='+$(this).val()+'&episode='+{{$episode->id}}, function(data){

	    		var slot_div = $('#unit_slot');

		        slot_div.empty();

		        $.each(data, function(index,value){

		        	console.log(index,value);

		            var option = document.createElement("option");

		            option.text = value;
		            option.value = index;

		            slot_div.append(option);

		        });

		      	$('.multiselect').multiselect('destroy');

			    $('.multiselect').multiselect({
			        onChange: function() {
			            $.uniform.update();
			        }
			    });

	    	});

	    });

	    $('#unit_date').trigger('change');

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