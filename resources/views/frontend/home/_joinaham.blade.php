 <!-- LAYER NR. 4-->


 @if(Sentinel::check())

  <a href="{{ url('classes') }}" target="_self" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['116','105','80','60']" data-transform_idle="o:1;" data-transform_in="z:0;rX:0deg;rY:0;rZ:0;sX:2;sY:2;skX:0;skY:0;opacity:0;s:1210;e:Power2.easeOut;" data-transform_out="y:[175%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" data-mask_in="x:0px;y:0px;" data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;" data-start="1630" data-actions="" data-responsive_offset="on" data-responsive="off" class="tp-caption btn btn-medium btn-circle btn-wide btn-orange">Browse Courses</a>

  @else

  <a href="{{ route('auth::register-as-a-student') }}" target="_self" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['116','105','80','60']" data-transform_idle="o:1;" data-transform_in="z:0;rX:0deg;rY:0;rZ:0;sX:2;sY:2;skX:0;skY:0;opacity:0;s:1210;e:Power2.easeOut;" data-transform_out="y:[175%];s:1000;e:Power2.easeInOut;s:1000;e:Power2.easeInOut;" data-mask_in="x:0px;y:0px;" data-mask_out="x:inherit;y:inherit;s:inherit;e:inherit;" data-start="1630" data-actions="" data-responsive_offset="on" data-responsive="off" class="tp-caption btn btn-medium btn-circle btn-wide btn-orange">Join Aham</a>

  @endif
