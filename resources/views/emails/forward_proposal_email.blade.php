<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proposal Received</title>
</head>
<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color:#f4f4f4;">
    <table align="center" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f4f4f4; padding:20px 0;">
        <tr>
            <td>
                <table align="center" cellpadding="0" cellspacing="0" width="600" style="background:#ffffff; border-radius:8px; box-shadow:0 0 8px rgba(0,0,0,0.1); overflow:hidden;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" bgcolor="#2c7be5" style="padding:20px; color:#ffffff; font-size:20px; font-weight:bold;">
                            Directorate Of Evaluation of Gujarat
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333333; font-size:15px; line-height:1.6;">
                            <p>Sir,</p>
                            <p>The following proposal has been received on the portal for evaluation:</p>
                            
                            <table cellpadding="6" cellspacing="0" width="100%" style="border:1px solid #e0e0e0; border-radius:5px; background:#fafafa;">
                                <tr>
                                    <td width="40%" style="font-weight:bold;">Name Of Scheme:</td>
                                    <td>{{$details['scheme_name']}}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:bold;">Department:</td>
                                    <td>{{$details['department']}}</td>
                                </tr>
                                @if(!is_null($details['hod_name']))
                                <tr>
                                    <td style="font-weight:bold;">Implementing Office (Hod) Name:</td>
                                    <td>{{$details['hod_name']}}</td>
                                </tr>
                                @endif
                            </table>

                            <p style="margin-top:20px;">Thanks &amp; Regards,<br>Evaluation System</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" bgcolor="#f1f1f1" style="padding:15px; font-size:12px; color:#777;">
                            This is an automated message. Please do not reply.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
