<strong>{{ $invite->teacher->user->name }} Awarded To Teach</strong>
<br>
<p>Teacher {{ $invite->teacher->user->name }} ({{ $invite->teacher->user->email }}) has been awarded to teach class of subject {{ $invite->ahamClass->topic->name }}, Class Code: {{ $invite->ahamClass->code }}</p>