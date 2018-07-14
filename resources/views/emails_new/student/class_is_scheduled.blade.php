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
                    

                    <span>You are enrolled to the class ({{ $class->code}}) for the topic <strong>{{ $class->topic->name }}</strong>. We are happy to inform that the minimum enrollment has been met for this class. The class session details are as mentioned below.</span>

                    <br><br>

                    <span>You can start using class page to interact with you clasmates and tutor from now until 1 week after the class finishes.

                    </span>

                    </td>
                    <td width="30" class="hide"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
                </tr>

                <tr>
                    <td height="48"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
                </tr>

            </tbody></table>
            </td>

        </tr>

    <tr>
        <td align="center">
        <table width="245" cellpadding="0" cellspacing="0" border="0" style="  display: block; background: #ff5722;text-align:center;  border-radius:2px;" align="center">
            <tbody><tr>
                <td width="245" height="40" style="font-size: 13px; color: #fffefe; font-family: 'Montserrat', sans-serif; text-transform: uppercase; text-align: center;text-decoration: none;font-weight: 700;  border-radius:2px;"><a href="{{ route('student::class.show',$class->code) }}" style="color: #fffefe; line-height: 40px;  display: block;text-decoration: none;font-weight: 700; text-align:center; border-radius:2px; "> Go To Class Page</a></td>
            </tr>
        </tbody></table></td>
    </tr>
    <tr>
        <td height="60"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
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
@include('emails_new.location_snippet')



@stop