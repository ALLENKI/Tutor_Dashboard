<!-- Basic modal -->
<div id="avatarModal" class="modal-dialog modal fade">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">Upload Profile Picture</h5>
			</div>

			{!! 
			  Form::open(['route' => 'settings::upload_avatar', 'id' => 'uploadAvatar', 'role' => 'form']) 
			!!}
			<div class="modal-body">
				
				<div class="image-editor">

        		<div class="row">
        			<div class="col-md-12">
        			<div class="cropit-image-preview" style="margin: 0 auto;">
        				
        			</div>
        			</div>


		            <div class="col-md-6 col-md-offset-3" style="padding-top: 10px;">
		            	<input type="range" class="cropit-image-zoom-input">
		        		<input type="hidden" name="image-data" class="hidden-image-data" />
		            </div>

		            <div class="clearfix"></div>


        			<div class="col-md-12">

						<div class="choses-file up-file">
			                <input type="file" class="cropit-image-input">
			                <input type="hidden">
			            </div>

		            </div>

        		</div>

		      	</div>

			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
			</div>
			{!! Form::close() !!}


		</div>
</div>
<!-- /basic modal -->

@section('styles')
@parent
<style type="text/css">
.cropit-image-preview {
background-color: #f8f8f8;
background-size: cover;
border: 1px solid #ccc;
border-radius: 3px;
margin-top: 7px;
width: 250px;
height: 250px;
cursor: move;
}

.cropit-image-background {
opacity: .2;
cursor: auto;
}

.image-size-label {
margin-top: 10px;
}

input {
display: block;
}

button[type="submit"] {
margin-top: 10px;
}

#result {
margin-top: 10px;
width: 900px;
}

#result-data {
display: block;
overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis;
word-wrap: break-word;
}
</style>

@stop

@section('scripts')
@parent
<script type="text/javascript">
  $(document).ready(function(){


  		$imageCropper = $('.image-editor');

		$imageCropper.cropit({
		  imageBackground: true,
		  imageBackgroundBorderWidth: 20,
		  smallImage: 'allow'
		});

        $('#saveChanges').click(function() {

        	console.log('Crop it');

		  	var imageData = $imageCropper.cropit('export',{
			  type: 'image/jpeg',
			  quality: .9,
			  originalSize: true,
			  smallImage: 'allow'
			});

		  	$('.hidden-image-data').val(imageData);

	        $('#uploadAvatar').submit();
        });

        $('#uploadAvatar').submit(function() {

        	console.log('Crop it');

		  	var imageData = $imageCropper.cropit('export',{
			  type: 'image/jpeg',
			  quality: .9,
			  originalSize: true,
			  smallImage: 'allow'
			});

		  	$('.hidden-image-data').val(imageData);

	        return true;
        });


  });

</script>

@stop