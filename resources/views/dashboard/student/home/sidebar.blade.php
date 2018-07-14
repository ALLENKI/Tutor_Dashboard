<div class="panel panel-default">

  <?php $current = Request::segment(4); ?>


   <ul class="list-group" style="padding:0px;">

        <li class="list-group-item {{ $current == 'profile' ? 'active' : '' }}">
            <a href="{{ route('student::settings.profile') }}">Profile</a>
        </li>

        <li class="list-group-item {{ $current == 'password' ? 'active' : '' }}">
          <a href="{{ route('student::settings.password') }}">Password</a>
        </li>
  </ul>

</div>


@section('styles')
@parent
<style type="text/css">
  .list-group-item.active, .list-group-item.active:hover{
    background-color: #eee;
  }
</style>
@stop
