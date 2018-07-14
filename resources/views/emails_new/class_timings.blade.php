
<tr>
    <td>
<table border="0" cellspacing="0" cellpadding="0" width="100%" style="background-color:#ffffff;">
<tbody><tr>
<td width="20"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>

<td>
<table border="0" cellspacing="0" cellpadding="5" width="100%">
<tbody><tr>
    <td height="46"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
</tr>
<tr>
    <td style="font-size: 26px; line-height: 28px; color:#32363a; font-family: 'Montserrat', sans-serif; text-align:center;">Schedule</td>
</tr>

<tr>
    <td height="13"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
</tr>
<tr>
    <td align="center"><img src="{{ cdn('assets/boost/images/line.png') }}" alt="line" width="25" height="2"></td>
</tr>
<tr>
    <td height="50"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
</tr>

@foreach($class->topic->units as $index => $unit)

<tr>
    <td>
    <table border="0" cellspacing="0" cellpadding="0" width="100%">
        <tbody><tr>
            <td style="font-size: 0; text-align: center;"><!--[if (gte mso 9)|(IE)]>
            <table width="100%"  cellpadding="0" cellspacing="0" border="0">
            <tr>
            <td width="290" valign="top">
            <![endif]-->
            <table style="width:100%; max-width: 280px; display: inline-block; vertical-align: top;" border="0" cellspacing="0" cellpadding="0">    
                <tbody><tr>
                    <td width="280" valign="top">
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody><tr>
                            <td width="37" class="hide"><img src="{{ cdn('assets/boost/images/blank.gif') }}" height="1" width="1"></td>
                            <td width="212" valign="top">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tbody>
                                <?php 
                                	$schedule = schedule($unit->id,$class->id);
                                ?>
                                @if($schedule != 'NA')
                                <?php 
                                    $schedule = explode('/',$schedule);
                                ?>
                                <tr>
                                    <td class="text-center" style="font-size:18px;line-height: 19px; color:#32363a; font-family: 'Montserrat', sans-serif; text-align:right;">{{ $schedule[0] }}</td>
                                </tr>
                                <tr>
                                    <td height="6"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
                                </tr>
                                <tr>
                                    <td class="text-center" style="font-size:14px; line-height: 18px; color:#32363a; font-family: 'Montserrat', sans-serif; text-align:right;">{{ $schedule[1] }}</td>
                                </tr>
                                @else
                                <tr>
                                    <td class="text-center" style="font-size:14px; line-height: 18px; color:#32363a; font-family: 'Montserrat', sans-serif; text-align:right;">Yet to be scheduled</td>
                                </tr>
                                @endif
                                <tr>
                                    <td class="spacer1" height="75"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
                                </tr>
                            </tbody></table></td>
                            <td width="24" class="hide"><img src="{{ cdn('assets/boost/images/blank.gif') }}" height="1" width="1"></td>
                            <td class="hide" width="20" align="right"><img src="{{ cdn('assets/boost/images/divider1.png') }}" width="11" height="195" alt="divider"></td>
                        </tr>
                    </tbody></table></td>

                </tr>
            </tbody></table><!--[if (gte mso 9)|(IE)]>
            </td>

            <td width="270"  valign="top">
            <![endif]-->
            <table style="width:100%;max-width: 270px; display: inline-block; vertical-align: top; " border="0" cellspacing="0" cellpadding="0">    
                <tbody><tr>
                    <td width="270" valign="top">
                    <table cellpadding="0" cellspacing="0" align="center" width="100%">
                        <tbody><tr>
                            <td width="26" class="hide"><img src="{{ cdn('assets/boost/images/blank.gif') }}" height="1" width="1"></td>
                            <td valign="top" class="224">
                            <table border="0" cellspacing="0" cellpadding="0" width="100%" align="center">
                                <tbody><tr>
                                    <td class="text-center" style="font-size:18px; line-height: 19px; color:#32363a; font-family: 'Montserrat', sans-serif; text-align:left;">{{ $unit->name }}</td>
                                </tr>
                                <tr>
                                    <td height="6"><img src="{{ cdn('assets/boost/images/blank.gif') }}" width="1" height="1"></td>
                                </tr>
                                <tr>
                                    <td class="text-center" align="left"><img src="{{ cdn('assets/boost/images/line.png') }}" alt="line" width="25" height="2"></td>
                                </tr>
                                <tr>
                                    <td height="75"><img src="{{ cdn('assets/boost/images/blank.gif') }}"></td>
                                </tr>
                            </tbody></table></td>
                            <td width="20" class="hide"><img src="{{ cdn('assets/boost/images/blank.gif') }}" height="1" width="1"></td>
                        </tr>
                    </tbody></table></td>

                </tr>
            </tbody></table>

            <!--[if (gte mso 9)|(IE)]>
            </td>

            </tr>
            </table>
            <![endif]--></td>
        </tr>
    </tbody></table></td>
</tr>
@endforeach

<hr>

<tr>
    <td height="47"><img src="{{ cdn('assets/boost/images/blank.gif') }}"></td>
</tr>
</tbody></table></td>


<td width="20">
<img src="{{ cdn('assets/boost/images/blank.gif') }}">
</td>
</tr>
</tbody></table>

</td>

</tr>

