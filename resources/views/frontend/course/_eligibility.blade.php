<div class="row">
<div class="col-md-4">
  <h6 class="with-shaded-label">
 	 <span>Prerequisites to take this course</span>
  </h6>
  <p class="tight">
  	To take this course you should have completed on Aham or be assessed by Aham that you are knowledgeable on the following:
  </p>
</div>
<div class="col-md-8">

  @foreach($course->prerequisites as $prerequisite)
  <?php
  	$preRequisiteEligibility = Aham\Helpers\StudentHelper::isAssessed($prerequisite, $user->student);
  ?>
  <a href="{{ url('classes-in-'.$prerequisite->slug) }}" class="lesson-item {{ $preRequisiteEligibility ? '' : 'access-lock' }}">
    <div class="lesson-meta">
    <i class="lesson-type oli oli-literature_filled"></i>
    @if($preRequisiteEligibility)
    <span class="duration">Completed</span>
    @else
    <span class="duration">Not Assessed</span>
    @endif
    </div>
    <h4 class="title">{{ $prerequisite->name }}</h4>
   </a>
   @endforeach

</div>

</div>
