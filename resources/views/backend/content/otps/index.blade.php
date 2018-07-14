@extends('backend.layouts.master')

@section('page_header')

<!-- Page header -->
<div class="page-header">
	<div class="page-header-content">
		<div class="page-title">
			<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">Content</span> - Pages</h4>

			<ul class="breadcrumb breadcrumb-caret position-right">
				<li><a href="{{ route('admin::admin') }}">Home</a></li>
				<li class="active">Pages</li>
			</ul>
		</div>

	</div>
</div>
<!-- /page header -->


@stop

@section('content')

<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title">Mobile OTPs</h5>
	</div>

	<table class="table">
		<thead>
			<th>Mobile</th>
			<th>OTP</th>
			<th>Expires On</th>
		</thead>
		<tbody>
			@foreach($mobileOtps as $otp)
			<tr>
				<td>{{ $otp->mobile }}</td>
				<td>{{ $otp->otp }}</td>
				<td>{{ $otp->expires_on }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>
@stop