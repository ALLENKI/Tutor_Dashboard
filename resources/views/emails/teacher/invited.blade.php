@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<h3>Dear {{$user->name}},</h3>

            	<p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">

                A class as below has been created. Based on your certification and availability, we are inviting you to teach this class:
                <br><br>

                <h3><strong>Class Details:</strong></h3>

                <h3><strong>{{ $invite->ahamClass->topic->name}} : {{ $invite->ahamClass->topic->units->count() }} Units</span></strong></h3>

                <strong>At {{ $invite->ahamClass->location->name}}, {{ $invite->ahamClass->location->street_address }}, {{ $invite->ahamClass->location->landmark }}, {{ $invite->ahamClass->location->locality->name }}, {{ $invite->ahamClass->location->city->name }} </span></strong>


            	</p>

                <span>
        			<i class="icon-watch position-left"></i> 

        			<table>
					<tbody>
        			@foreach($invite->ahamClass->topic->units as $index => $unit)
						<tr>
						<td> {{ $unit->name }} </td>
						<td> {{ schedule($unit->id,$invite->ahamClass->id) }} </td>
						</tr>
					
					@endforeach

					</tbody>
					</table>
        			
    			</span>

        		<p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">

        		Please Accept or Decline the invitation on your <a href="{{ route('teacher::invitations.new') }}">Dashboard. </a>Other certified tutors have also been invited to teach this class. 
				
				<br><br>
				You will get a confirmation if you have been awarded this class.
				<br><br>

				<div style="text-align: center;">
				<a href="{{ route('teacher::home') }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff; padding: 10px 33px; border-radius: 16px;">Go To My Dashboard</a>
				</div>
				

				<p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
            	Cheers,
            	<br>
				Aham Team
            	</p>

            </p>
        </td>
     </tr>


@stop