@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<h3>Hello {{$user->name}},</h3>


            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">

            	The below mentioned class that was scheduled earlier for you to teach is now <strong>CANCELLED</strong>

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

                	The Reason for cancellation is:
					<strong> {{ $class->cancellation_reason }} </strong>
            </p>
        </td>
     </tr>


@stop