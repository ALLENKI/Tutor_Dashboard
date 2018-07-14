@extends('emails_new.master')

@section('content')

<tr>
<td>
    <tr>
<td>
<table border="0" cellspacing="0" cellpadding="0" width="100%" style="background-color:#ffffff;">
<tbody><tr>
    <td width="20"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
    <td>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tbody><tr>
            <td height="48"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
        </tr>

        <tr>
            <td style="font-size: 26px; line-height: 30px; color:#32363a; font-family: 'Montserrat', sans-serif; text-align:center;">Hello {{ $user->name }},</td>
        </tr>

        <tr>
            <td height="12"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
        </tr>
        <tr>
            <td align="center"><img src="{{ cdn('assets/boost/images/line.png') }}" alt="line" width="25" height="2"></td>
        </tr>
        <tr>
            <td height="32"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
        </tr>
        <tr>
            <td>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                <tr>
                    <td width="30" class="hide"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
                    <td style="font-size: 13px; line-height: 25px; color:#7f8c8d; font-family: 'Open Sans', sans-serif;"> 
                    
                    The below mentioned class that was scheduled earlier for you to attend is now <strong>CANCELLED.</strong>

                    The Reason for cancellation is:
                    <strong> {{ $class->cancellation_reason }} </strong>
                    <br><br>
                    <p>Please contact admin at contactus@ahamlearning.com if you have any questions</p>
                    

                    </td>
                    <td width="30" class="hide"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
                </tr>

                <tr>
                    <td height="48"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
                </tr>

            </tbody></table>
            </td>

        </tr>

    </tbody></table></td>
    <td width="20"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
</tr>
</tbody>
</table>
</td>

</tr>
</td>   
</tr>

@include('emails_new.class_timings')


@stop