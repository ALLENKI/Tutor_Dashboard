<!-- Main sidebar -->
<div class="sidebar sidebar-main">
	<div class="sidebar-content">

		<!-- User menu -->
		<div class="sidebar-user-material">
			<div class="category-content">
				<div class="sidebar-user-material-content">
					<a href="#"><img src="{{ cloudinary_url($student->user->present()->picture, ['secure' => true]) }}" class="img-circle img-responsive" alt=""></a>
					<a href="{{route('student::settings.profile')}}"><h6>Hi, {{ $student->user->name }}</h6></a>
				</div>
			</div>			
			
		</div>
		<!-- /user menu -->

		<!-- Main navigation -->
		<div class="sidebar-category sidebar-category-visible">
			<div class="category-content no-padding">
				<ul class="navigation navigation-main navigation-accordion">

					<li class="{{ Request::segment(3) == '' ? 'active' : '' }}">
					<a href="{{  route('student::home') }}">
					<i class="icon-puzzle2"></i> 
					<span>Dashboard</span>
					</a>
					</li>


					<li class="{{ Request::segment(4) == 'recommended' ? 'active' : '' }}">
					<a href="{{ route('student::classes.recommended') }}">
					<i class="icon-calendar22"></i> 
                    <span>Invitation to Enroll
                        <span class="label label-success"> {{$invitedClasses->total()}} </span>
                    </span>
					</a>
					</li>

					<li class="{{ Request::segment(4) == 'browse' ? 'active' : '' }}">
					<a href="{{ route('student::classes.browse') }}">
					<i class="icon-calendar22"></i> 
					<span>Upcoming classes
                    </span>
					</a>
					</li>

					<li class="{{ Request::segment(4) == 'catalog' ? 'active' : '' }} {{ Request::segment(3) == 'courses' ? 'active' : '' }}">
					<a href="{{ route('student::courses.catalog') }}">
					<i class="icon-book"></i> 
					<span>Aham Catalog</span>
					</a>
					</li>

					<li class="{{ Request::segment(3) == 'assessment' ? 'active' : '' }}">
					<a href="{{ route('student::assessment') }}">
					<i class="icon-stats-dots"></i> 
					<span>My Assessment</span>
					</a>
					</li>


					<li class="{{ (Request::segment(3) == 'classes' && Request::segment(4) != 'catalog' && Request::segment(4) != 'browse') ? 'active' : '' }}">
					<a href="{{  route('student::classes.on-going') }}">
					<i class="icon-pencil7"></i> 
					<span>My Classes</span>
					</a>
					</li>


					<li class="{{ Request::segment(3) == 'credits' ? 'active' : '' }}">
					<a href="{{ route('student::credits.index') }}"><i class="icon-coins"></i> <span>My Credits</span>
					</a>
					</li>
					
{{-- 					<li class="">
						<a href="#" class="has-ul legitRipple"><i class="icon-footprint"></i> <span>Classes</span><span class="legitRipple-ripple"></span></a>
						<ul class="hidden-ul" style="display: none;">
							<li><a href="{{ route('student::classes.enrolled') }}" class="legitRipple">All</a></li>
							<li><a href="wizard_form.html" class="legitRipple">Completed</a></li>
						</ul>
					</li> --}}


					<li class="{{ Request::segment(3) == 'settings' ? 'active' : '' }}"><a href="{{ route('student::settings.profile') }}"><i class="icon-cog5"></i> <span>Account settings</span></a></li>

					<li class="{{ Request::segment(3) == 'demo-video' ? 'active' : '' }}"><a href="{{ route('student::demo-video') }}"><i class="icon-play"></i> <span>Demo Video</span></a></li>


					<li><a href="{{ route('auth::logout') }}"><i class="icon-switch2"></i> <span>Logout</span></a></li>

					<!-- /main -->
				</ul>
			</div>
		</div>
		<!-- /main navigation -->

	</div>
</div>
<!-- /main sidebar -->
