@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<h3>Hi Aham Admin,</h3>


            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
                {{ $user->name }} is Interested to take {{ $topic->name }}.
            </p>

            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
                Please find the details of the Student:
                <ul>
                	<li>Name: {{ $user->name }}</li>
                    <li>Email: {{ $user->email }}</li>
                    <li>Preferred Time: {{ $preferred_time }}</li>
                    <li>Preferred Day: {{ $preferred_day }}</li>
                    <li>Preferred Period: {{ $preferred_period }}</li>
                	<li>Message: {{ $your_message }}</li>
                </ul>
            </p>
        </td>
     </tr>


@stop