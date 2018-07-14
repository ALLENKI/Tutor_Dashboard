@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<div style="font-size:18px;line-height:1.4;color:#333332">
	            Hi {{ $user->name }},
	        </div>

            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">

            	<span style="font-weight:700; font-size:16px;"><strong>Congratulations! You are now a Learner at Aham.  We are extremely excited to have you onboard.</strong></span>

			    <br><br>

				Based on your initial meeting with Aham staff, we have marked the skills you already know under "My Assessment" section in your 
 				<a href="https://ahamlearning.com/dashboard/student/assessment"> Learner Dashboard.</a> 
				Learner Dashboard is personalized for you, so you can keep track of your goals, classes and progress. 
	 
				<br><br>
				<strong>HAPPY LEARNING!!!</strong>
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
        </td>
     </tr>


@stop