@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<h3>Dear {{$user->name}},</h3>

               	<p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">

                The below scheduled class has been awarded to you based on your acceptance and it is now open for enrollment. You can see this class in your dashboard under<a href="{{ route('teacher::classes.scheduled') }}"> Scheduled Classes</a>  
                <br><br>

                <strong>This class will go into session if the minimum enrollment is met.</strong>

                <strong>Class Details:</strong>

                <strong>{{ $invite->ahamClass->topic->name}} : {{ $invite->ahamClass->topic->units->count() }} Units</strong>
                <br>

                <strong>At {{ $invite->ahamClass->location->name}}, {{ $invite->ahamClass->location->street_address }}, {{ $invite->ahamClass->location->landmark }}, {{ $invite->ahamClass->location->locality->name }}, {{ $invite->ahamClass->location->city->name }} </strong>
                
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
				
				This class will go into session if the minimum enrollment is met.


				<div style="text-align: center;">
				<a href="{{ route('teacher::home') }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff; padding: 10px 33px; border-radius: 16px;">Go To My Dashboard</a>
				</div>


                </p>       

                <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
                Cheers,
                <br>
                Aham Team
                </p>
        </td>
     </tr>


@stop