<strong>{{ $invite->teacher->user->name }} Accepted To Teach</strong>
<br>
<p>Teacher {{ $invite->teacher->user->name }} ({{ $invite->teacher->user->email }}) has accepted to teach class of subject {{ $invite->ahamClass->topic->name }}, Class Code: {{ $invite->ahamClass->code }}</p>
