<!-- Basic datatable -->
<div class="panel panel-flat">
	<div class="panel-heading">
		Unlocks
	</div>

	<div class="panel-body">
	<table class="table">
	@foreach($fullTree as $fruit)
	<tr>
		<td>{{ $fruit->id }}</td>
		<td>
		<a href="{{ $fruit->adminShowLink() }}">
		{{ $fruit->name }}</a>
		
		</td>
		<td>
			{{ $fruit->type }}
		</td>
	</tr>
	@endforeach
	</table>
	</div>

</div>
<!-- /basic datatable -->