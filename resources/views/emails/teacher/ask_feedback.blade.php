@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<h3>Hi {{$user->name}},</h3>


            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
                
            	The below mentioned class you attended recently is now marked as complete. We hope you enjoyed the experience of teaching the class. Your feedback is very important to us. Please go to your <a href="{{ route('teacher::home') }}">Dashboard</a> to provide feedback.
                <br><br>

                <strong>Class Details:</strong>

                <strong> {{ $class->topic->name}} : {{ $class->topic->units->count() }} Units</strong>
                <br><br>

                <strong>At {{ $class->location->name}}, {{ $class->location->street_address }}, {{ $class->location->landmark }}, {{ $class->location->locality->name }}, {{ $class->location->city->name }} </strong>
                
        	</p>

                <span>
                    <i class="icon-watch position-left"></i> 

                    <table>
                    <tbody>
                    @foreach($class->topic->units as $index => $unit)
                        <tr>
                        <td> {{ $unit->name }} </td>
                        <td> {{ schedule($unit->id,$class->id) }} </td>
                        </tr>
                    
                    @endforeach

                    </tbody>
                    </table>
                    
                </span>

        		<p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
				
				Th class page is now closed for updates. You can continue to  view the class page for the next 6 months.



				<div style="text-align: center;">
				<a href="{{ route('teacher::class.feedback') }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff; padding: 10px 33px; border-radius: 16px;">Give Feedback</a>
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