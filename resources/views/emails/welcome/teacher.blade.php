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
			<span style="font-weight:700; font-size:16px;"><strong>Almost There! You are <u>one step away</u> from becoming a Tutor at Aham.</strong></span>

			<br><br>
			We will review the information you have provided during registration and will soon reach out to you to further understand your interest and to onboard you as a Tutor. If you were not able to provide information during registration, it's not too late. Please respond to this email and give us more information about your interest and skills. 
			<br><br>
			<strong>Here are the next steps as you begin your journey with Aham:</strong>
            </p>

            <img src="https://res.cloudinary.com/ahamlearning/image/upload/v1473074523/teacher-at-aham_latest_fd1cor.jpg" style="width:100%;">

            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
            	Cheers,
            	<br>
				Aham Team
            </p>
        </td>
     </tr>


@stop