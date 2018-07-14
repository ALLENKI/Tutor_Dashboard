@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<h3>Hello {{$user->name}},</h3>


            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">

                <span>The following credits have been refunded in your dashboard as the class got cancelled: </span>
                <br>

                <ul>
                    <li>{{ $creditsModel->credits }} have been deposited.</li>
                </ul>

                <br><br><br>

				<div style="text-align: center;">
				<a href="{{ route('student::credits.index') }}" class="tp-caption btn btn-medium btn-circle btn-wide" style="background: #006696; color: #fff; padding: 10px 33px; border-radius: 16px;">Go To My Dashboard</a>
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