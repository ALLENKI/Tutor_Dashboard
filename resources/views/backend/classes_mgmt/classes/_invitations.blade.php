<div class="panel panel-primary">

	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-calendar position-left"></i> Award and Invite</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">


	@if($ahamClass->status == 'created' || 
	$ahamClass->status == 'invited' ||
	$ahamClass->status == 'accepted')

	<?php 

		$columnSizes = [
		  'sm' => [4, 8],
		  'lg' => [2, 10]
		];

	?>

	{!! 
		BootForm::openHorizontal($columnSizes)
		->action(route('admin::classes_mgmt::classes.invite_award_teacher',$ahamClass->id)) 
	!!}

		<div class="col-md-8">
			{!! BootForm::select('Teacher *', 'teacher_id')
						->options($eligibleTeachers)
						->attribute('required','true')
						->attribute('class','form-control')
			!!}
		</div>

		<div class="col-md-4">
		<button type="submit" class="btn btn-primary btn-block">Invite and Award <i class="icon-arrow-right14 position-right"></i></button>
		</div>

	{!! BootForm::close() !!}

	@endif

	<div class="clearfix"></div>

	@if($ahamClass->invitations->count())
	<table class="table table-framed">

		<thead>
			<tr>
				<th>Code</th>
				<th>Name</th>
				<th>Email</th>
				<th>Status</th>
				<th>Actions</th>
			</tr>
		</thead>

		<tbody>
			@foreach($ahamClass->invitations as $invitation)

			<?php $teacher = $invitation->teacher; ?>

			<tr>
				<td>{{ $teacher->code }}</td>
				<td>{{ $teacher->user->name }}</td>
				<td>{{ $teacher->user->email }}</td>
				<td>

				<div class="btn-group">
	            	<button type="button" class="btn btn-xs btn-primary {{ $invitation->status == 'accepted' ? 'btn-success': '' }} dropdown-toggle" data-toggle="dropdown">
						{{ strtoupper($invitation->status) }}
				 <span class="caret"></span>
				 </button>
	            	<ul class="dropdown-menu dropdown-menu-right">
						<li>
							@if(Activation::completed($teacher->user))
							<a href="{{ route('admin::users::users.impersonate',$teacher->user->id) }}" target="_blank"><i class="icon-menu7"></i> 
								Impersonate
							</a>
							@else
							<a href="{{ route('admin::users::users.activate_and_impersonate',$teacher->user->id) }}" target="_blank"><i class="icon-menu7"></i> 
								Activate and Impersonate
							</a>
							@endif
						</li>
					</ul>
				</div>

				</td>
				<td>
					<?php
						$available = false;

						$available = Aham\Helpers\TeacherHelper::isAvailable($ahamClass, $invitation);
					?>
					@if(!$ahamClass->teacher && $ahamClass != 'cancelled')
						@if($invitation->status == 'accepted' && $available)
						<a href='{{ route('admin::classes_mgmt::classes.award_to_teacher',$invitation->id) }}' class="btn btn-xs btn-primary">Award</a>
						@elseif($invitation->status == 'accepted' && !$available)
						Not Available
						@else
						No Action
						@endif
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>

	</table>
	@else
			<h3 class="text-center">You haven't invited any teacher to the party! Sorry, Class!</h3>		
	@endif


	</div>

</div>

{{-- Invite --}}

<div class="panel panel-primary">

	<div class="panel-heading">
		<h6 class="panel-title"><i class="icon-calendar position-left"></i> Invitations</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	</div>

	<div class="panel-body">


	@if($ahamClass->status == 'created' || 
	$ahamClass->status == 'invited' ||
	$ahamClass->status == 'accepted')

	<?php 

		$columnSizes = [
		  'sm' => [4, 8],
		  'lg' => [2, 10]
		];

	?>

	{!! 
		BootForm::openHorizontal($columnSizes)
		->action(route('admin::classes_mgmt::classes.invite_teacher',$ahamClass->id)) 
	!!}

		<div class="col-md-8">
			{!! BootForm::select('Teacher *', 'teacher_id[]')
						->options($eligibleTeachers)
						->attribute('required','true')
						->attribute('id','teacher_id')
						->attribute('class','form-control multiselect')
						->attribute('multiple','multiple')
			!!}
		</div>

		<div class="col-md-2">
		<button type="submit" class="btn btn-primary btn-block">Invite <i class="icon-arrow-right14 position-right"></i></button>
		</div>


		<div class="col-md-2">
		<a href="{{ route('admin::classes_mgmt::classes.invite_all_teachers',$ahamClass->id) }}" data-method='POST' class="rest btn btn-success btn-block legitRipple">
			Invite All <i class="icon-arrow-right14 position-right"></i>
		</a>
		</div>

	{!! BootForm::close() !!}

	@endif

	<div class="clearfix"></div>

	@if($ahamClass->invitations->count())
	<table class="table table-framed">

		<thead>
			<tr>
				<th>Code</th>
				<th>Name</th>
				<th>Email</th>
				<th>Status</th>
				<th>Actions</th>
			</tr>
		</thead>

		<tbody>
			@foreach($ahamClass->invitations as $invitation)

			<?php $teacher = $invitation->teacher; ?>

			<tr>
				<td>{{ $teacher->code }}</td>
				<td>{{ $teacher->user->name }}</td>
				<td>{{ $teacher->user->email }}</td>
				<td>

				<div class="btn-group">
	            	<button type="button" class="btn btn-xs btn-primary {{ $invitation->status == 'accepted' ? 'btn-success': '' }} dropdown-toggle" data-toggle="dropdown">
						{{ strtoupper($invitation->status) }}
				 <span class="caret"></span>
				 </button>
	            	<ul class="dropdown-menu dropdown-menu-right">
						<li>
							@if(Activation::completed($teacher->user))
							<a href="{{ route('admin::users::users.impersonate',$teacher->user->id) }}" target="_blank"><i class="icon-menu7"></i> 
								Impersonate
							</a>
							@else
							<a href="{{ route('admin::users::users.activate_and_impersonate',$teacher->user->id) }}" target="_blank"><i class="icon-menu7"></i> 
								Activate and Impersonate
							</a>
							@endif
						</li>
					</ul>
				</div>

				</td>
				<td>
					<?php
						$available = false;

						$available = Aham\Helpers\TeacherHelper::isAvailable($ahamClass, $invitation);
					?>
					@if(!$ahamClass->teacher && $ahamClass != 'cancelled')
						@if($invitation->status == 'accepted' && $available)
						<a href='{{ route('admin::classes_mgmt::classes.award_to_teacher',$invitation->id) }}' class="btn btn-xs btn-primary">Award</a>
						@elseif($invitation->status == 'accepted' && !$available)
						Not Available
						@else
						No Action
						@endif
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>

	</table>
	@else
			<h3 class="text-center">You haven't invited any teacher to the party! Sorry, Class!</h3>		
	@endif


	</div>

</div>


@section('styles')
@parent
<style type="text/css">
.btn-group{
	width: 100%;
}

</style>

@stop

@section('scripts')
@parent
<script type="text/javascript">
$(function(){

	// $('#teacher_id').select2();



});
</script>
@stop