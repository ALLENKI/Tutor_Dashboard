@extends('dashboard.teacher.layouts.master')

@section('content')

<div class="panel">
	<div class="panel-body">
		<h4 class="text-center content-group-lg">
			Welcome to our knowledge base
			<small class="display-block">Explore Aham, Unlock Learning</small>
		</h4>

		<form action="" class="main-search" method="GET">
			<div class="input-group content-group">
				<div class="has-feedback has-feedback-left">
					<input type="text" class="form-control input-xlg" placeholder="Search our knowledgebase" name="q" value="{{ Input::get('q','') }}">
					<div class="form-control-feedback">
						<i class="icon-search4 text-muted text-size-base"></i>
					</div>
				</div>

				<div class="input-group-btn">
					<button type="submit" class="btn btn-primary btn-xlg legitRipple">Search</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="row">
	<div class="col-md-3">
		
		<div class="sidebar sidebar-default">
			<div class="sidebar-content">

        		<!-- Support -->
				<div class="sidebar-category no-margin">
					<div class="category-title">
						<span>Browse</span>
					</div>
				</div>
				<!-- /support -->
    			
    			<!-- Navigation -->
				<div class="sidebar-category">
					<div class="category-content no-padding">
						<ul class="nav navigation">
							<li class="navigation-header"><i class="icon-gear pull-right"></i> Subjects</li>
							@foreach($subjects as $subject)
							<li class="{{ Input::get('subject','') == $subject->slug ? 'active' : '' }}">
							<a href="{{ route('teacher::courses.catalog',['subject' => $subject->slug]) }}" class="legitRipple"> {{ $subject->name }} 
							
							<span class="pull-right">({{ $subject->count }})</span>
							</a>
							</li>
							@endforeach

							@if(Input::has('subject'))
							<li class="navigation-header"><i class="icon-gear pull-right"></i> Sub Categories </li>
							@foreach($subCategories as $subCategory)
							<li class="{{ Input::get('sub-category','') == $subCategory->slug ? 'active' : '' }}">
							<a href="{{ route('teacher::courses.catalog',['subject' => $subCategory->parent->slug, 'sub-category' => $subCategory->slug]) }}" class="legitRipple"> {{ $subCategory->name }} 
							
							<span class="pull-right">({{ $subCategory->count }})</span>
							</a>
							</li>
							@endforeach
							@endif

			            </ul>
		            </div>
	            </div>
	            <!-- /navigation -->

            </div>
        </div>

	</div>

	<div class="col-md-9">
		<div class="row">
			@foreach($topics as $index => $topic)
				<?php $topic = $topic->topic ?>
				<div class="col-md-6">
					@include('dashboard.teacher.snippets._free_class')
				</div>

				@if(($index+1)%2 == 0)
					<div class="clearfix"></div>	
				@endif
			@endforeach

			<div class="col-md-12 text-center">
			{!! $topics->appends(Input::only('subject','sub-category'))->render() !!}
			</div>

		</div>
	</div>

</div>

@stop

@section('styles')
@parent
<style type="text/css">
.thumb {
    margin-bottom: 5px;
}
</style>
@stop