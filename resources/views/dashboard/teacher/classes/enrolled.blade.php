@extends('dashboard.teacher.layouts.master')

@section('content')

<div class="page-header page-header-inverse has-cover" style="    background: url(http://res.cloudinary.com/ahamlearning/image/upload/c_scale,h_860,l_black_opacity_60_sgz5cd,w_1841/v1466663558/Collaboration-Technology_zncjmr.jpg);background-size: cover;background-position-y: -135px;">
	<div class="page-header-content">
		<div class="page-title" style="padding: 52px 36px 52px 0;">
			<h2 style="font-size: 36px;">
				<span class="text-bold">
				Enrolled Classes
				</span>
			</h2>
		</div>
	</div>
</div>


<div class="panel panel-flat">
	<div class="panel-heading">
		<h6 class="panel-title">Classes History</h6>
	</div>

	<div class="table-responsive">
		<table class="table text-nowrap">
			
			<tbody>
				@foreach($enrollments as $enrollment)
				@include('dashboard.teacher.snippets._enrolled_class')
				@endforeach
			</tbody>
		</table>
	</div>
</div>

@stop