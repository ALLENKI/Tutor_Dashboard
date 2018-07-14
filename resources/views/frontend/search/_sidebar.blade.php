<div class="sidebar left">

<div class="widgets-wrapper">

  <div class="widget">
    <h4>Subjects</h4>

    <div class="category-list">
    @foreach($subjects as $subject)
			@if($subject->visibility)
			<a href="{{ url('classes-in-'.$subject->slug) }}" class="cat {{ !is_null($selectedSubject) && $subject->id == $selectedSubject->id ? 'bold underline' : '' }}">
				{{ $subject->name }}
				<span>{{ $subject->count }}</span>
			</a>
			@endif

    @endforeach
	</div>

  </div>

  @if($selectedSubject)
  <div class="widget">
  	<h4>Categories</h4>

  	<ul class="ol-side-navigation skin-blue toggle-free">
		
	@foreach($selectedSubject->children as $subCategory)
      <li class="menu-item-has-children">

      	<a href="{{ url('classes-in-'.$subCategory->slug) }}">
      	{{ $subCategory->name }}
      	</a>

        <ul class="sub-menu">

	        @foreach($subCategory->children as $topic)
            @if(in_array($topic->status,['active','in_progress']))
  	          <li>
  		          <a href="{{ url('classes-in-'.$topic->slug) }}">
  		          	{{ $topic->name }}
  		          </a>
  	          </li>
            @endif
	        @endforeach

        </ul>

      	<span class="ol-toggle"></span>

      </li>
    @endforeach

  	</ul>
  </div>
  @endif

</div>

</div>