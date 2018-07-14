@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<h3>Hi {{$user->name}},</h3>


            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
                
            	<span>You are enrolled to the class below. We are happy to inform that the minimum enrollment has been met for this class. The class session details are as mentioned below.</span>

                <br><br>

                <strong>Class Details:</strong>

                <h3> <strong> {{ $class->topic->name}} : {{ $class->topic->units->count() }} Units</span></strong> </h3>

                At {{ $class->location->name}}, {{ $class->location->street_address }}, {{ $class->location->landmark }}, {{ $class->location->locality->name }}, {{ $class->location->city->name }}
                <br><br>
                
				<span>
                    <i class="icon-watch position-left"></i> 

                    <table>
                    <tbody>
                    @foreach($class->topic->units as $index => $unit)
                        <tr>
                        <td> {{ $unit->name }}: </td>
                        <td> {{ schedule($unit->id,$class->id) }} </td>
                        </tr>
                    
                    @endforeach

                    </tbody>
                    </table>
                    
                </span>

                <br>

                <span>You can start using class page to interact with you clasmates and tutor from now until 1 week after the class finishes.

				</span>

				<br><br>

				<div style="text-align: center;">
				<a href="{{ route('student::home') }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff; padding: 10px 33px; border-radius: 16px;">Go To My Dashboard</a>
				</div>
				

				<p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
            	Cheers,
            	<br>
				Aham Team
            	</p>

            </p>
            </p>
        </td>
     </tr>


@stop