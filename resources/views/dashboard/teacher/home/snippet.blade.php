@extends('dashboard.teacher.layouts.master')

@section('content')
	<!-- Content area -->
<div class="content">

	<!-- Square thumbs -->
	<h6 class="content-group text-semibold">
		UPCOMING CLASSES
	</h6>

	<div class="row">
		<div class="col-lg-3 col-md-6">
			<div class="thumbnail no-padding">
				<div class="thumb">
					<a href="#"><img src="http://res.cloudinary.com/ahamlearning/image/upload/c_thumb,h_237,w_237/aham_icon_m6ljr5.png" alt=""></a>			
				</div>
		    	<div class="caption text-center">
		    		<h6 class="text-semibold no-margin"><a href="#">Introduction to Geometry </a>
		    		<small class="display-block">By <a href="#">Ajitha Molakalapalli</a></small></h6><hr>
		    		<div style="margin-bottom:25px;">
		    		<small class="display-block" style="float:left;"><code>Starts on 7th Aug</code></small>
		    		<small class="display-block" style="float:right;"><code>4 Units</code></small>
		    		</div>
		    	</div>
	    	</div>
		</div>

		<div class="col-lg-3 col-md-6">
			<div class="thumbnail no-padding">
				<div class="thumb">
					<a href="#"><img src="http://res.cloudinary.com/ahamlearning/image/upload/c_thumb,h_237,w_237/aham_icon_m6ljr5.png" alt=""></a>			
				</div>
		    	<div class="caption text-center">
		    		<h6 class="text-semibold no-margin"><a href="#">Introduction to Geometry </a>
		    		<small class="display-block">By <a href="#">Ajitha Molakalapalli</a></small></h6><hr>
		    		<div style="margin-bottom:25px;">
		    		<small class="display-block" style="float:left;"><code>Starts on 7th Aug</code></small>
		    		<small class="display-block" style="float:right;"><code>4 Units</code></small>
		    		</div>
		    	</div>
	    	</div>
		</div>

			<div class="col-lg-3 col-md-6">
			<div class="thumbnail no-padding">
				<div class="thumb">
					<a href="#"><img src="http://res.cloudinary.com/ahamlearning/image/upload/c_thumb,h_237,w_237/aham_icon_m6ljr5.png" alt=""></a>			
				</div>
		    	<div class="caption text-center">
		    		<h6 class="text-semibold no-margin"><a href="#">Introduction to Geometry </a>
		    		<small class="display-block">By <a href="#">Ajitha Molakalapalli</a></small></h6>
		    		<hr>
		    		<div style="margin-bottom:25px;">
		    		<small class="display-block" style="float:left;"><code>Starts on 7th Aug</code></small>
		    		<small class="display-block" style="float:right;"><code>4 Units</code></small>
		    		</div>
		    	</div>
	    	</div>
		</div>

	</div>

	<hr>


	<!-- Basic sorting -->
	<h6 class="content-group text-semibold">
		Class Schedule
		{{-- <small class="display-block">Sort panels using <code>move</code> button</small> --}}
	</h6>

	<div class="row row-sortable">
		<div class="col-md-8">
			<div class="panel panel-white">
				<div class="panel-heading">
					{{-- <h6 class="panel-title">Multiple columns panel 1</h6> --}}
					<div class="heading-elements">
						<ul class="icons-list">
	                		<li><a data-action="collapse"></a></li>
	                		<li><a data-action="move"></a></li>
	                	</ul>
                	</div>
				</div>
				
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Units</th>
								<th>Date & Time</th>
								<th>No of Hours</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1</td>
								<td>Physics Fundamentals</td>
								<td>25 Aug,2016. 06:30 - 08:30</td>
								<td>2 Hours</td>
							</tr>
							<tr>
								<td>2</td>
								<td>Time, Speed & Distace</td>
								<td>30th Aug,2016. 13:00-15:00</td>
								<td>2 Hours</td>
							</tr>
							<tr>
								<td>3</td>
								<td>Problem Solving & Practice</td>
								<td>2nd Sept,2016. 10:00 - 12:00</td>
								<td>2 Hours</td>
							</tr>
							<tr>
								<td>4</td>
								<td>Practice Tests</td>
								<td>2nd Sept,2016. 14:00 - 16:00</td>
								<td>2 Hours</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

		</div>

	</div>
	<!-- /basic sorting -->
	<hr>

	<!--Credits-->

		<!-- Dashboard content -->
	<div class="row">
		<div class="col-lg-8">

			<!-- Marketing campaigns -->
			<div class="panel panel-flat">
				<div class="panel-heading">
					<h6 class="panel-title">Credit Units</h6>
					<div class="heading-elements">
						<span class="label bg-success heading-text">28 Remaining</span>
						<span class="label bg-blue heading-text"><a href="#" style="color:#ffffff">Add Credits</a></span>
                	</div>
				</div>

				<div class="table-responsive">
					<table class="table table-lg text-nowrap">
						<tbody>
							<tr>
								<td class="col-md-5">
									<div class="media-left">
										<div id="campaigns-donut"></div>
									</div>

									<div class="media-left">
										<h5 class="text-semibold no-margin">100 <small class="text-success text-size-base"> Loaded</small></h5>
										<ul class="list-inline list-inline-condensed no-margin">
											<li>
												<span class="status-mark border-success"></span>
											</li>
											<li>
												<span class="text-muted">May 12, 12:30 am</span>
											</li>
										</ul>
									</div>
								</td>

								<td class="col-md-5">
									<div class="media-left">
										<div id="campaign-status-pie"></div>
									</div>

									<div class="media-left">
										<h5 class="text-semibold no-margin">72 <small class="text-danger text-size-base">Used</small></h5>
										<ul class="list-inline list-inline-condensed no-margin">
											<li>
												<span class="status-mark border-danger"></span>
											</li>
											<li>
												<span class="text-muted">Jun 4, 4:00 am</span>
											</li>
										</ul>
									</div>
								</td>

								<td class="text-right col-md-2">
									<a href="#" class="btn bg-indigo-300"><i class="icon-statistics position-left"></i> View History</a>
								</td>
							</tr>
						</tbody>
					</table>	
				</div>

			</div>
			<!-- /marketing campaigns -->
		</div>
	</div>
	<!--Credits-->
</div>

@stop