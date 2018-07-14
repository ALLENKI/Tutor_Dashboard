<div class="panel panel-default">
  <div class="panel-heading">
      <h3 class="panel-title">Personal Settings</h3>
  </div>

  <?php $current = Request::segment(2); ?>

   <ul class="list-group">
        <li class="list-group-item {{ $current == 'profile' ? 'active' : '' }}">
            <a href="{{ route('settings::profile') }}">Profile</a>
        </li>
        
        <li class="list-group-item {{ $current == 'password' ? 'active' : '' }}">
          <a href="{{ route('settings::password') }}">Password</a>
        </li>
        
  </ul>

</div>