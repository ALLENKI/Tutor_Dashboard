@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<div style="font-size:18px;line-height:1.4;color:#333332">
	            Hi {{ $user->name }},
	        </div>

            
            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">

                <span style="font-weight:700; font-size:16px;"><strong>Congratulations! You are now a Tutor at Aham.  We are extremely excited to have you onboard.</strong></span>
			    <br><br>

				We have marked "Topics you can Teach at Aham" under My Certification section in your <a href="https://ahamlearning.com/dashboard/teacher/certification">Tutor Dashboard.</a> 
				Tutor Dashboard is personalized for you, so you can focus more on teaching and less on the logistics.
	 
				<br><br>
				<strong>HAPPY TEACHING!!!</strong>
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