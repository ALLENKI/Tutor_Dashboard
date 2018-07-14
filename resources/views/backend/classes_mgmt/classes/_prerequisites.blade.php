<div class="panel panel-flat">
	<div class="panel-heading">
		<h6 class="panel-title">Prerequisites</h6>
		<div class="heading-elements">
			<ul class="icons-list">
        		<li><a data-action="collapse"></a></li>
        	</ul>
    	</div>
	</div>

	@if($ahamClass->topic->prerequisites->count())
	<div class="table-responsive">
		<table class="table">

			<tbody>
				@foreach($ahamClass->topic->prerequisites as $prerequisite)
				<tr>
					<td>
						<a href="{{ $prerequisite->adminShowLink() }}">
							{{ $prerequisite->name }}
						</a>
					</td>
					<td>
						{{ $prerequisite->studentAssessments->count() }}
					</td>
				</tr>
				@endforeach

			</tbody>
		</table>
	</div>
	@else
	<div class="panel-body">
		This class has no prerequisites
	</div>
	@endif

</div>