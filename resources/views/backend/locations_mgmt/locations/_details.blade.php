	<div class="panel panel-white">

		<div class="panel-heading">
			<h6 class="panel-title"><i class="icon-files-empty position-left"></i> General details</h6>
			<div class="heading-elements">
				<ul class="icons-list">
            		<li><a data-action="collapse"></a></li>
            	</ul>
        	</div>
		</div>

		<table class="table table-borderless table-xs content-group-sm">
			<tbody>
				<tr>
					<td><i class="icon-briefcase position-left"></i> Name:</td>
					<td class="text-right">{{ $location->name }}</td>
				</tr>
				<tr>
					<td><i class="icon-alarm-check position-left"></i> Created On:</td>
					<td class="text-right">{{ $location->created_at }}</td>
				</tr>
				<tr>
					<td><i class="icon-history position-left"></i> Street Address:</td>
					<td class="text-right">{{ $location->street_address }}</td>
				</tr>
				<tr>
					<td><i class="icon-history position-left"></i> Landmark:</td>
					<td class="text-right">{{ $location->landmark }}</td>
				</tr>
				<tr>
					<td><i class="icon-history position-left"></i> City:</td>
					<td class="text-right">{{ $location->city->name }}</td>
				</tr>
				<tr>
					<td><i class="icon-history position-left"></i> Locality:</td>
					<td class="text-right">{{ $location->locality->name }}</td>
				</tr>
				<tr>
					<td><i class="icon-history position-left"></i> Pincode:</td>
					<td class="text-right">{{ $location->pincode }}</td>
				</tr>
				<tr>
					<td><i class="icon-history position-left"></i> Currency Type:</td>
					<td class="text-right">{{ $location->currency_type }}</td>
				</tr>
				<tr>
					<td><i class="icon-history position-left"></i> Credits Type:</td>
					<td class="text-right">{{ $location->credits_type }}</td>
				</tr>
			</tbody>
		</table>

	</div>