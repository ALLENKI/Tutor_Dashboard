<div class="modal-dialog">
<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&times;</button>
		<h5 class="modal-title">Enrollment To {{ $class->topic->name }}</h5>
	</div>

	<div class="modal-body">
		<div class="alert alert-danger">
			You don't have enough credits
		</div>

		<div class="text-right">
			<a href="{{ route('student::credits.index') }}" class="btn btn-primary">Add Credits <i class="icon-coins position-right"></i></a>
		</div>

	</div>

</div>
</div>
