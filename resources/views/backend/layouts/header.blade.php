
    <!-- Main navbar -->
    <div class="navbar navbar-default header-highlight">
        <div class="navbar-header">


            <a class="navbar-brand" href="{{ route('admin::admin') }}" style="padding: 4px 20px;"><img src="https://res.cloudinary.com/ahamlearning/image/upload/c_fit,h_50,q_100/v1466848309/logo_huge_lfpjpa.png" style="height: 36px;"></a>

            <a class="navbar-brand" href="{{ route('admin::admin') }}">
            Admin Dashboard For Aham
            </a>

            <ul class="nav navbar-nav pull-right visible-xs-block">
                <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            </ul>
        </div>

        <div class="navbar-collapse collapse" id="navbar-mobile">

            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown dropdown-user">
                    <a class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ cdn('assets/back/images/image.png') }}" alt="">
                        <span>{{ $loggedInUser->name }}</span>
                        <i class="caret"></i>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="{{ url('/') }}"><i class="icon-home"></i> Home </a></li>
                        <li><a href="{{ route('auth::logout') }}"><i class="icon-switch2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!-- /main navbar -->


    <!-- Second navbar -->
    <div class="navbar navbar-default" id="navbar-second">
        <ul class="nav navbar-nav no-border visible-xs-block">
            <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i class="icon-menu7"></i></a></li>
        </ul>

        <div class="navbar-collapse collapse" id="navbar-second-toggle">
            <ul class="nav navbar-nav navbar-nav-material">
                <li><a href="{{ route('admin::admin') }}"><i class="icon-display4 position-left"></i> Dashboard</a></li>

                <li><a href="{{ route('admin::recon') }}"><i class="icon-display4 position-left"></i> Recon</a></li>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Master Lists <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu width-200">
                        
                        @if($loggedInUser->can('topic_tree.manage'))
                        <li>
                        <a href="{{ route('admin::topic_tree::topics.index') }}"><i class="icon-align-center-horizontal"></i>
                        Topic Tree
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('users.view'))
                        <li class="{{ Request::segment(3) == 'users' ? 'active' : '' }}">
                        <a href="{{ route('admin::users::users.index') }}"><i class="icon-users"></i>
                        Users
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('users.student_profile'))
                        <li class="{{ Request::segment(3) == 'students' ? 'active' : '' }}">
                        <a href="{{ route('admin::users::students.index') }}"><i class="icon-users"></i>
                        Students
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('users.teacher_profile'))
                        <li class="{{ Request::segment(3) == 'teachers' ? 'active' : '' }}">
                        <a href="{{ route('admin::users::teachers.index') }}"><i class="icon-users"></i>
                        Teachers
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('coupons'))
                        <li class="{{ Request::segment(3) == 'coupon_templates' ? 'active' : '' }}">
                        <a href="{{ route('admin::users::coupon_templates.index') }}"><i class="icon-users"></i>
                        Coupon Templates
                        </a>
                        </li>

                        <li class="{{ Request::segment(3) == 'coupons' ? 'active' : '' }}">
                        <a href="{{ route('admin::users::coupons.index') }}"><i class="icon-users"></i>
                        Coupons
                        </a>
                        </li>
                        @endif


                        @if($loggedInUser->can('goals'))
                        <li class="{{ Request::segment(3) == 'goals' ? 'active' : '' }}">
                        <a href="{{ route('admin::topic_tree::goals.index') }}"><i class="icon-users"></i>
                        Goals
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('scheduling_rules'))
                        <li class="{{ Request::segment(3) == 'scheduling_rules' ? 'active' : '' }}">
                        <a href="{{ route('admin::topic_tree::scheduling_rules.index') }}"><i class="icon-users"></i>
                        Scheduling Rules
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('content_pages'))
                        <li class="{{ Request::segment(3) == 'content' ? 'active' : '' }}">
                        <a href="{{ route('admin::content::pages.index') }}"><i class="icon-users"></i>
                        Content Pages
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('settings'))
                        <li class="{{ Request::segment(3) == 'settings' ? 'active' : '' }}">
                        <a href="{{ route('admin::content::settings.index') }}"><i class="icon-users"></i>
                        Settings
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('permissions'))
                        <li class="{{ Request::segment(3) == 'permissions' ? 'active' : '' }}">
                        <a href="{{ route('admin::users::permissions.index') }}"><i class="icon-users"></i>
                        Permissions
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('users.student_credits'))
                        <li class="{{ Request::segment(3) == 'payments' ? 'active' : '' }}">
                        <a href="{{ route('admin::users::payments.index') }}"><i class="icon-users"></i>
                        Payments
                        </a>
                        </li>
                        @endif

                        <li>
                        <a href="{{ route('admin::users::bet_applicants.index') }}"><i class="icon-users"></i>
                        Bet Applicants
                        </a>
                        </li>

                    </ul>
                </li>

                <li class="dropdown {{ Request::segment(2) == 'locations_mgmt' ? 'active' : '' }}">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Location Management <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu width-200">
                    
                        @if($loggedInUser->can('cities'))
                        <li class="{{ Request::segment(3) == 'cities' ? 'active' : '' }}">
                        <a href="{{ route('admin::locations_mgmt::cities.index') }}"><i class="icon-align-center-horizontal"></i>
                        Cities
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('localities'))
                        <li class="{{ Request::segment(3) == 'localities' ? 'active' : '' }}">
                        <a href="{{ route('admin::locations_mgmt::localities.index') }}"><i class="icon-align-center-horizontal"></i>
                        Localities
                        </a>
                        </li>
                        @endif

                        @if(count($loggedInUser->accessibleLocations('locations.manage')))
                        <li class="{{ Request::segment(3) == 'locations' ? 'active' : '' }}">
                        <a href="{{ route('admin::locations_mgmt::locations.index') }}"><i class="icon-pin"></i>
                        Locations
                        </a>
                        </li>
                        @endif

                        @if($loggedInUser->can('day_types'))
                        <li class="{{ Request::segment(3) == 'day_types' ? 'active' : '' }}">
                        <a href="{{ route('admin::locations_mgmt::day_types.index') }}"><i class="icon-align-center-horizontal"></i>
                        Day Types
                        </a>
                        </li>
                        @endif

                    </ul>
                </li>


                <li><a href="https://ala.ahamlearning.com" target="_blank"><i class="icon-display4 position-left"></i> Classes Mgmt</a></li>

                <li class="dropdown {{ Request::segment(2) == 'classes_mgmt' ? 'active' : '' }}" style="display: none;">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        Classes Management <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu width-200">

                        @if(count($loggedInUser->accessibleLocations('classes.manage')))
                        <li class="{{ Request::segment(3) == 'classes' ? 'active' : '' }}">
                        <a href="{{ route('admin::classes_mgmt::classes.index') }}"><i class="icon-calendar"></i>
                        Classes
                        </a>
                        </li>

                        <li class="{{ Request::segment(3) == 'group_classes' ? 'active' : '' }}">
                        <a href="{{ route('admin::classes_mgmt::group_classes.index') }}"><i class="icon-calendar"></i>
                        Group Classes
                        </a>
                        </li>

                        <li class="{{ Request::segment(3) == 'guest_series' ? 'active' : '' }}">
                        <a href="{{ route('admin::classes_mgmt::guest_series.index') }}"><i class="icon-calendar"></i>
                        Guest Series
                        </a>
                        </li>

                        @endif

                    </ul>

                </li>

            </ul>

        </div>
    </div>
    <!-- /second navbar -->