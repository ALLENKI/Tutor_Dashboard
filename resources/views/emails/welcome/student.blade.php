@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

            <div style="font-size:18px;line-height:1.4;color:#333332">
	            Hi {{ $user->name }},
	        </div>

            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
		     Welcome to Aham℠, The Learning Hub. 
		     <br><br>
			<span style="font-weight:700; font-size:16px;"><strong>Almost There! You are <u>one step away</u> from becoming a Student at Aham.</strong></span>

			<br><br>
			
            We will soon reach out to you to understand your learning goals and to assess your current understanding of related concepts, so we can get you started ASAP.
 
			<br><br>
			<strong>Here are the next steps as you begin your journey with Aham:</strong>

            </p>

            <img src="https://res.cloudinary.com/ahamlearning/image/upload/v1474921329/student-at-aham_15320835_65489734d07478472134c751800ea11a29cb663f_juvxt1.jpg" style="width:100%;">

            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
            	Cheers,
            	<br>
				Aham Team
            </p>
        </td>
     </tr>


@stop