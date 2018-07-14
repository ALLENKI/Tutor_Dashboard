
<?php
  $classCourse = $ahamClass->topic;
  $classTeacher = $ahamClass->teacher;
  $image_url = cloudinary_url($classTeacher->user->present()->picture, array( "gravity"=>"face:center", "crop"=>"crop"));
?>
	
<div class="item">
<div class="image-wrapper set-bg"><img src="{{ $image_url }}" class="set-me"></div>
    <div class="cols-wrapper clearfix">

      <div class="col-sm-6 col-md-4 vcenter location-col">
      
        <div class="wrap">
        <small>({{ $ahamClass->code }})</small>
        @foreach($ahamClass->topic->units as $index => $unit)
        <br>
        <div class="date">
          Unit {{ $index+1 }} : <i class="oli oli-clock"></i> {{ schedule($unit->id,$ahamClass->id) }}
        </div>
        @endforeach
        </div>

      </div>
  

      <div class="col-sm-6 col-md-3 vcenter location-col">
      <div class="wrap">{!! $ahamClass->location->present()->address !!}</div>
      </div>
      
     <div class="col-sm-6 col-md-2 vcenter location-col">
        <div class="wrap">
        <h4 class="cat"><a href="{{ route('tutor::profile',$ahamClass->teacher->user->username) }}">{{ $ahamClass->teacher->user->name }}</a></h4>
        </div>
      </div>

    <div class="col-sm-6 col-md-3 vcenter location-col">
      <div class="wrap">

        @if(Sentinel::check() && $user->student)

        @if($user->student->enrollments()->where('class_id',$ahamClass->id)->count() == 0)

          <?php
              $available = false;

              $available = Aham\Helpers\StudentHelper::isAvailable($ahamClass, $user->student);
          ?>

          @if(!$available)

          You are busy at these timings

          @elseif($available && $eligibility['result'] == true && $user->id != $ahamClass->teacher->user->id)

          <a href="{{ route('student_dashboard::class.enroll',[$user->student->user->username,$ahamClass->id]) }}" class="rest btn btn-small btn-block btn-skin-blue btn-circle pull-right" data-method="POST">Enroll</a>

          @endif

          @if($user->id == $ahamClass->teacher->user->id)
            Sorry! You are teaching this class!
          @endif

          @if($eligibility['result'] == false && $eligibility['reason'] == 'prerequisite_fail')
          Sorry! You don't meet criteria to take this class. If you want you can still enroll to this class. This feature is <span class="label label-danger">Coming Soon</span>
          @endif

          @if($eligibility['result'] == false && $eligibility['reason'] == 'finish_first')
          Sorry! You cannot take this class, as you are already enrolled into a class which is about to start.
          @endif

        @else
        
          You have already enrolled to this class!

        @endif

        @endif


      </div>
    </div>

    </div>
    <div class="arrow-wrapper"><i class="icon oi-forward"></i></div>
</div>