@extends('emails.master')


@section('content')

<tr>
    <td style="padding-top:10px;padding-right:5%;padding-bottom:10px;padding-left:5%;color:#333332">
        <div style="font-size:18px;line-height:1.4;text-align:center;color:#333332">
            It Happens!
        </div>

        <p style="margin-top:20px;margin-right:0;margin-bottom:14px;margin-left:0;font-size:14px;line-height:22px">
            So you forgot your password. No Big Deal! 
            <br>
            <br>
            Reset your password: It's easy - But set a secure one!
            <br>
            <br>
            Just hit "Reset Password" below or copy paste the following link in your browser window: {{ $link }}
        </p>
    </td>
 </tr>


<tr valign="middle">
    <td style="vertical-align:middle;padding-bottom:46px" align="center">
    <div><!--[if mso]>
      <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="http://" style="height:53px;v-text-anchor:middle;width:200px;" arcsize="8%" stroke="f" fillcolor="#49a9ce">
        <w:anchorlock/>
        <center>
      <![endif]-->
          <a href="{{ $link }}"
    style="background-color:#49a9ce;border-radius:4px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:13px;font-weight:bold;line-height:53px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">Reset Password</a>
      <!--[if mso]>
        </center>
      </v:roundrect>
    <![endif]-->
    </div>  
    </td>
</tr>

@stop