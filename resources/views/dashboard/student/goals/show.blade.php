@extends('dashboard.student.layouts.master')

@section('page-header')

@stop


@section('content')

<div class="panel panel-white">
	<div class="panel-heading">
        <h5 class="panel-title">
          Goal - {{ $goal->name }}
        </h5>

        <div class="heading-elements">
        	<a href="{{ route('student::goals.remove',$goal->id) }}" data-method="POST" class="rest btn btn-primary legitRipple">Remove</a>
        </div>

        <br>
        <span class="label label-primary label-rounded" style="background-color: #f88506; border-color: #f88506;">Need to Learn ({{ isset($analytics['#f88506']) ? $analytics['#f88506'] : 0 }})</span>
        <span class="label label-primary label-rounded" style="background-color: #34c424; border-color: #34c424;">You Know It ({{ isset($analytics['#34c424'])  ? $analytics['#34c424'] : 0 }})</span>
        <span class="label label-primary label-rounded" style="background-color: #006696; border-color: #006696;">Your Goal</span>

    </div>

	<div class="panel-body">

	<div class="row">
	  <div class="col-md-12" id="mynetwork">
	    

	  </div>

    <hr>

	  <div class="col-md-12" id="message">
	   <table class="table">

      <thead>
        <th>Topic</th>
        <th>No of Units [<span id="totalUnits"></span>]</th>
      </thead>
      <tbody>
        <?php 
          $showLevel = 5;
          $show = true;
          $totalUnits = 0;
        ?>

        @foreach($goal_topics as $index => $goal_topic)

        <?php 
          if($showLevel != $goal_topic->graph_level)
          {
              $showLevel = $goal_topic->graph_level;
              $show = true;
          }
          else
          {
              $show = false;
          }

          if($index == 0)
          {
            $show = true;
          }

          $totalUnits += $goal_topic->units->count();
        ?>

        @if($show)
        <tr style="text-align: center;">
          <td colspan="2"> <strong> Level {{ $goal_topic->graph_level }} </strong></td>
        </tr>
        @endif

        <tr>
          <td><a href="{{ route('student::courses.show',$goal_topic->slug) }}" target="_blank" style="color:{{$goal_topic->color}}">{{ $goal_topic->name }}</a></td>
          <td>{{ $goal_topic->units->count() }}</td>
        </tr>
        @endforeach

      </tbody>
     </table>
	  </div>
	</div>

	</div>

</div>


@stop


@section('styles')
@parent
<style type="text/css">
    #mynetwork {
      height: 500px;
      border: 1px solid lightgray;
      background:#d1d1d1;
    }
    p {
      max-width:600px;
    }

    .label{
      font-size: 13px;
    }
</style>
@stop

@section('scripts')
@parent
<script type="text/javascript">
  var all_topics = {!! json_encode($all_topics) !!};
  var totalUnits = {!!  $totalUnits !!};

  $(function(){
    // console.log(totalUnits);
    $('#totalUnits').html(totalUnits);
  });
</script>
@stop

@include('dashboard.student.goals.graph')

