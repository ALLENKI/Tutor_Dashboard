@extends('emails.master')

@section('content')

    <tr>
        <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">

        	<h3>Dear {{$user->name}},</h3>

            <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
                You are certified to teach {{ $certification->topic->name}}
            </p>
        </td>
     </tr>


@stop