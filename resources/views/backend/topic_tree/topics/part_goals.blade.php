<div class="panel panel-default">
	<div class="panel-heading">
	<h5 class="panel-title">Goals</h5>
	</div>

	<div class="panel-body">
		
		<?php 

			$columnSizes = [
			  'sm' => [4, 8],
			  'lg' => [2, 10]
			];

		?>

		{!! BootForm::openHorizontal($columnSizes)->action(route('admin::topic_tree::topics.goals')) !!}

		{!! BootForm::hidden('topic_id')->attribute('value',$topic->id) !!}

        {!! 
          BootForm::select('Goals', 'goals[]')
                  ->options($goals)
                  ->attribute('class','form-control select') 
                  ->attribute('multiple','multiple')
                  ->select($topic->goals->pluck('id')->toArray())
        !!}

		<div class="text-right">
			<button type="submit" class="btn btn-primary">Save <i class="icon-arrow-right14 position-right"></i></button>
		</div>

		{!! BootForm::close() !!}
	</div>
</div>