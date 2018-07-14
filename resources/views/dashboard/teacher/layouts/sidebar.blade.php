<!-- Main sidebar -->
<div class="sidebar sidebar-main">
	<div class="sidebar-content">

		<!-- User menu -->
		<div class="sidebar-user-material">
			<div class="category-content">
				<div class="sidebar-user-material-content">
					<a href="#"><img src="{{ cloudinary_url($teacher->user->present()->picture) }}" class="img-circle img-responsive" alt=""></a>
					<h6>{{ $teacher->user->name }}</h6>
					<span class="text-size-small">Tutor since {{ $teacher->created_at->format('jS M Y') }}</span>
				</div>
										
			</div>
			
		</div>
		<!-- /user menu -->


		<!-- Main navigation -->
		<div class="sidebar-category sidebar-category-visible">
			<div class="category-content no-padding">
				<ul class="navigation navigation-main navigation-accordion">

					<li class="{{ Request::segment(3) == '' ? 'active' : '' }}">
					<a href="{{  route('teacher::home') }}">
					<i class="icon-home4"></i> 
					<span>Dashboard</span>
					</a>
					</li>

					<li class="{{ Request::segment(3) == 'invitations' ? 'active' : '' }}">
					<a href="{{  route('teacher::invitations.new') }}">
					<i class="icon-envelope"></i> 
					<span>Invitations</span>
					</a>
					</li>

					<li class="{{ (Request::segment(3) == 'classes' && Request::segment(4) != 'catalog') ? 'active' : '' }}">
					<a href="{{  route('teacher::classes.on-going') }}">
					<i class="icon-pencil7"></i> 
					<span>My Classes</span>
					</a>
					</li>

					<li class="{{ Request::segment(3) == 'availability' ? 'active' : '' }}">
					<a href="{{  route('teacher::availability') }}">
					<i class="icon-calendar"></i> 
					<span>My Availability</span>
					</a>
					</li>

					<li class="{{ Request::segment(3) == 'certification' ? 'active' : '' }}">
					<a href="{{  route('teacher::certification') }}">
					<i class="icon-stats-dots"></i> 
					<span>My Certification</span>
					</a>
					</li>

					<li class="{{ Request::segment(4) == 'payments' ? 'active' : '' }}">
					<a href="{{  route('teacher::payments') }}">
					<i class="icon-coin-dollar"></i> 
					<span>Payment History</span>
					</a>
					</li>

					<li class="{{ (Request::segment(4) == 'catalog' || Request::segment(3) == 'courses') ? 'active' : '' }} ">
					<a href="{{ route('teacher::courses.catalog') }}">
					<i class="icon-book"></i> 
					<span>Aham Catalog</span>
					</a>
					</li>

					<li class="{{ Request::segment(3) == 'settings' ? 'active' : '' }}"><a href="{{ route('teacher::settings.profile') }}"><i class="icon-cog5"></i> <span>Account settings</span></a></li>

					<li><a href="{{ route('auth::logout') }}"><i class="icon-switch2"></i> <span>Logout</span></a></li>

					<!-- /main -->
				</ul>
			</div>
		</div>
		<!-- /main navigation -->

	</div>
</div>
<!-- /main sidebar -->
