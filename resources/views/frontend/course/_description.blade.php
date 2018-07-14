
<div class="row">
<div class="col-md-12">

<h2>Course Description</h2>
<p style="font-size: 14px;font-family: Tahoma; color: #000;">{!! $course->description !!}</p>

@foreach($course->units as $index => $unit)

<div class="row">
<div class="col-md-1">
<img src="{{ cdn('assets/front_old/images/learn/number_'.($index+1).'_small.png') }}" class="img-responsive">
</div>
<div class="col-md-11 editor">
<h3 style="color: #1F79A3; font-size: 20px; margin-bottom: 20px !important;">{{ $unit->name }}</h3>
<p class="tight">{!! $unit->description !!}</p><br>
</div>
</div>

@endforeach

</div>


</div>