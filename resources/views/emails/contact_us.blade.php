@extends('emails.master')


@section('content')

<tr>
    <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">
        <div style="font-size:18px;line-height:1.4;text-align:center;color:#333332">
            Hello
        </div>

        <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
            Someone tried to contact you, Check below
            <br>
            <br>

            <ul>
                <li><strong>Name:</strong> {{ $name }}</li>
                <li><strong>Email:</strong> {{ $email }}</li>
                <li><strong>Option:</strong> {{ $select_option }}</li>
                <li><strong>Message:</strong> {{ $comment }}</li>
            </ul>
        </p>
    </td>
 </tr>


@stop