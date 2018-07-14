@extends('dashboard.student.layouts.master')

@section('content')

@include('dashboard.student.classes._tabs')

<div class="row">

	<div class="col-md-12">

		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title"><strong>Completed Classes</strong></h6>
			</div>

			<div class="panel-body">

				@if($completedTimings->count())

					@foreach($completedTimings as $classTiming)
						@include('dashboard.student.snippets._common_class_unit_snippet')
					@endforeach

				@else
					<h4 class="text-center" style="font-style: italic;font-size: 16px;">You have no completed classes.</h4>
				@endif

			</div>

            <div class="panel-footer text-center">
			@if($completedTimings->count())
				{!! $completedTimings->render() !!}
			@endif
			</div>

		</div>

	</div>

</div>


<!-- Modal -->
<div class="modal fade" id="rating" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">GIVE FEEDBACK</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="rating_modal_form">

       <!-- model -->
      <div class="modal-body">
         <div class="row">

            <div class="panel-body">
            <div class="row">
            <div class="col-md-12">

            <div class="alert alert-danger" id="rating_error" style="display: none;">
              <strong>Failed!</strong> Please fill all the fields.
            </div>

            <div class="form-group">

            <label for="input-1" class="control-label">Teacher Rating</label>
            <input id="teacher_rating" name="teacher_rating" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-readonly="false" required value="0">

            </div>

            <div class="form-group">

            <label for="input-2" class="control-label">Overall Experience</label>
            <input id="overall_rating" name="overall_rating" class="rating rating-loading" data-min="0" data-max="5" data-step="1" data-readonly="false" required value="0">

            </div>

            <div class="form-group">
              <textarea rows="2" class="form-control" id="rating_remarks" name="remarks"></textarea>
            </div>

            </div>
            </div>
            </div>

          </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-xs btn-success pull-right legitRipple" id="submit_rating">Submit</button>
        <button type="button" class="btn btn-xs btn-warning pull-right legitRipple" data-dismiss="modal">Close</button>
      </div>

    </form>

    </div>
  </div>
</div>
@stop

@section('styles')
@parent
<style type="text/css">
.glyphicon {
    font-size: 43px;
}

.rating-container .caption {
  margin-top: 0px;
}

.rating-container .caption .label{
    font-size: 15px;
}
</style>
@stop

@section('scripts')
@parent
    <script>

			var classCode =  null;

      $('#submit_rating').on('click',function (event) {
        event.preventDefault();
        console.log("Submit rating",$('#rating_modal_form').serialize());

        var postUrl = '/dashboard/student/class/'+classCode+'/feedback';

        $('#rating_error').hide();

        $.post(postUrl+'?'+$('#rating_modal_form').serialize(),function (data,status) {
            $("button[data-code='" + classCode +"']").hide();
            $('#rating').modal('hide');
        }).done(function() {
          })
          .fail(function() {
           $('#rating_error').show();
          });

      });


			$('.giveFeedback').on('click',function (event) {
						 classCode = $(this).data('code');
             $('#rating_error').hide();
			});

    </script>
@stop
