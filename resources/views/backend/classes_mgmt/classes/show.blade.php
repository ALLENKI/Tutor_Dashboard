@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Classes Mgmt</span> - Class - {{ $ahamClass->code }}</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li><a href="{{ route('admin::classes_mgmt::classes.index') }}">Classes</a></li>
				<li class="active">{{ $ahamClass->code }}</li>
			</ul>
		</div>
	</div>
</div>
<!-- /page header -->

@stop

@section('content')

@if($ahamClass->status == 'cancelled')
<div class="alert alert-danger alert-styled-left alert-arrow-left alert-component">
	<h6 class="alert-heading text-semibold">This class is cancelled because of following reason:</h6>
	{{ $ahamClass->cancellation_reason }}
</div>
@endif

<div class="row">

	<div class="col-lg-8">

		<div class="panel panel-default">
			<div class="panel-heading">
				<h5 class="panel-title">#{{$ahamClass->id}}: {{$ahamClass->topic->name}} ({{$ahamClass->code}})</h5>

				<div class="heading-elements">
				
				@if($ahamClass->status != 'cancelled' && $ahamClass->status != 'closed')
				
					<a href="{{ route('admin::classes_mgmt::classes.edit',$ahamClass->id) }}" class="btn btn-primary heading-btn">Edit</a>
            	
            	@endif

            	<a href="{{ route('admin::classes_mgmt::classes.create-repeat',$ahamClass->id) }}" class="btn btn-info heading-btn" target="_blank">Repeat</a>

            	</div>

			</div>

			<div class="panel-body">
				@include('backend.classes_mgmt.classes._calendar')
				@include('backend.classes_mgmt.classes._invitations')
				@include('backend.classes_mgmt.classes._enrollments')
			</div>


		</div>

		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title">Comments</h6>
		    </div>	 

		    <div class="panel panel-body">
				<div class="row">
					<div class="col-md-12" id="comments-container">

					</div>
				</div>
		    </div>
		</div>

	</div>

	<div class="col-lg-4">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">Snapshot</h6>
				<div class="heading-elements">
					<ul class="icons-list">
					<li></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">

				@include('backend.classes_mgmt.classes._summary')
				
				@if($ahamClass->status == 'initiated' || $ahamClass->status == 'created' || $ahamClass->status == 'invited' || $ahamClass->status == 'accepted' || $ahamClass->status == 'ready_for_enrollment')
				<hr>

				<a href="{{ route('admin::classes_mgmt::classes.reinitiate',$ahamClass->id) }}" data-method='POST' class="rest btn btn-danger btn-block legitRipple">
					REINITIATE
				</a>
				@endif

			</div>
		</div>

		@include('backend.classes_mgmt.classes._steps')
		
		@include('backend.classes_mgmt.classes._prerequisites')

		<div class="panel panel-flat">
			<div class="panel-heading">
				<h6 class="panel-title">Crash Course</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="collapse"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">
				<h4>Class Stages</h4>
				<ol>
					<li><strong>Initiated:</strong> When a topic is chosen and class is created.</li>
					<li><strong>Created:</strong> When timings are chosen for a class.</li>
					<li><strong>Invited:</strong> When a teacher is invited to accept invitation for a class.</li>
					<li><strong>Scheduled:</strong> When class is scheduled.</li>
					<li><strong>Confirmed:</strong> When min enrollment is met.</li>
					<li><strong>Completed:</strong> When teacher gives feedback and marks as completed.</li>
					<li><strong>Closed:</strong> Admin closes this class, Teacher gets payment.</li>
				</ol>
			</div>

		</div>



		@if($ahamClass->status != 'cancelled')
		<div class="panel panel-white">
			<div class="panel-heading">
				<h6 class="panel-title">Cancel Class</h6>
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="collapse"></a></li>
                	</ul>
            	</div>
			</div>

			<div class="panel-body">


				<button data-url='{{ route('admin::classes_mgmt::classes.cancel_modal', [$ahamClass->id]) }}' class='btn btn-danger btn-xs btn-block' onclick='openAjaxModal(this);'>CANCEL CLASS</button>

			</div>

		</div>
		@endif


	</div>

</div>

@stop


@section('scripts')
@parent

<script type="text/javascript">

var table;

$(document).ready(function(){

    $('.multiselect').multiselect({
        onChange: function() {
            $.uniform.update();
        }
    });

    $(".styled, .multiselect-container input").uniform({ radioClass: 'choice'});



});


$(function() {

	$('#comments-container').comments({
		enableUpvoting: false,
		@if($ahamClass->status == 'completed')
		readOnly: true,
		@endif
		enableNavigation: false,
		enableReplying: false,
		enableEditing: false,
		enableDeleting: false,
		enableAttachments: false,
		postCommentOnEnter: true,
		profilePictureURL: '{{ cloudinary_url($loggedInUser->present()->picture, ['secure' => true]) }}',
		roundProfilePictures: true,
		textareaRows: 1,
		enableAttachments: false,
		getComments: function(success, error) {

	        $.ajax({
	            type: 'get',
	            url: '{{ route("user::comments.index",$ahamClass->id) }}',
	            success: function(commentsArray) {
	                success(commentsArray)
	            },
	            error: error
	        });

		},
		postComment: function(commentJSON, success, error) {
	        $.ajax({
	            type: 'post',
	            url: '{{ route("user::comments.index",$ahamClass->id) }}',
	            data: commentJSON,
	            success: function(comment) {
	                success(comment)
	            },
	            error: error
	        });
	    },
		putComment: function(data, success, error) {
			setTimeout(function() {
				success(data);
			}, 500);
		},
		deleteComment: function(data, success, error) {
			setTimeout(function() {
				success();
			}, 500);
		},
	    uploadAttachments: function(commentArray, success, error) {
	        var responses = 0;
	        var successfulUploads = [];

	        var serverResponded = function() {
	            responses++;

	            // Check if all requests have finished
	            if(responses == commentArray.length) {
	                
	                // Case: all failed
	                if(successfulUploads.length == 0) {
	                    error();

	                // Case: some succeeded
	                } else {
	                    success(successfulUploads)
	                }
	            }
	        }

	        $(commentArray).each(function(index, commentJSON) {

	            // Create form data
	            var formData = new FormData();
	            $(Object.keys(commentJSON)).each(function(index, key) {
	                var value = commentJSON[key];
	                if(value) formData.append(key, value);
	            });

	            $.ajax({
	                url: '/api/comments/',
	                type: 'POST',
	                data: formData,
	                cache: false,
	                contentType: false,
	                processData: false,
	                success: function(commentJSON) {
	                    successfulUploads.push(commentJSON);
	                    serverResponded();
	                },
	                error: function(data) {
	                    serverResponded();
	                },
	            });
	        });
	    }
	});
});
</script>

@stop