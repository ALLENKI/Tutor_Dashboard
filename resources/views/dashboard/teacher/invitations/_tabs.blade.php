{{-- <div class="page-header page-header-inverse has-cover" style="    background: url(http://res.cloudinary.com/ahamlearning/image/upload/c_scale,h_860,l_black_opacity_60_sgz5cd,w_1841/v1466663558/Collaboration-Technology_zncjmr.jpg);background-size: cover;background-position-y: -135px;">
	<div class="page-header-content">
		<div class="page-title" style="padding: 32px 36px 32px 0;">
			<h2 style="font-size: 36px;">
				<span class="text-bold">
				Your Invitations
				</span>
			</h2>
		</div>
	</div>
</div> --}}

<div class="tabbable">
	<ul class="nav nav-pills nav-pills-toolbar nav-justified">

		<li class="{{ Request::segment(4) == 'new' ? 'active' : '' }}">
			<a href="{{  route('teacher::invitations.new') }}" class="legitRipple">
			<i class="icon-ticket position-left"></i> New
			</a>
		</li>

		<li class="{{ Request::segment(4) == 'awarded' ? 'active' : '' }}">
			<a href="{{  route('teacher::invitations.awarded') }}" class="legitRipple">
			<i class="icon-airplane2 position-left"></i> Awarded
			</a>
		</li>

		<li class="{{ Request::segment(4) == 'pending' ? 'active' : '' }}">
			<a href="{{  route('teacher::invitations.pending') }}" class="legitRipple">
			<i class="icon-basket position-left"></i> Pending
			</a>
		</li>

		<li class="{{ Request::segment(4) == 'all' ? 'active' : '' }}">
			<a href="{{  route('teacher::invitations.all') }}" class="legitRipple">
			<i class="icon-list position-left"></i> History
			</a>
		</li>

	</ul>

</div>