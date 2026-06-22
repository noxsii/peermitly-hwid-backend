<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta name="color-scheme" content="light only" />
    <meta name="supported-color-schemes" content="light only" />
    <title>{{ $title ?? 'Peermitly' }}</title>
    <!--[if mso]>
    <style type="text/css">
        table, td, div, h1, h2, h3, p { font-family: Arial, Helvetica, sans-serif !important; }
    </style>
    <![endif]-->
    <style type="text/css">
        @media only screen and (max-width: 620px) {
            .container { width: 100% !important; }
            .px-md { padding-left: 24px !important; padding-right: 24px !important; }
            .py-md { padding-top: 32px !important; padding-bottom: 32px !important; }
            .h1 { font-size: 24px !important; line-height: 32px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#faf7f4; color:#1c1917; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
<!-- preheader -->
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#faf7f4;">
    {{ $preheader ?? 'Peermitly' }}
</div>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#faf7f4" style="background-color:#faf7f4;">
    <tr>
        <td align="center" style="padding:40px 16px;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" class="container" style="width:600px; max-width:600px;">

                <!-- header -->
                <tr>
                    <td align="center" style="padding:0 0 28px 0;">
                        <img src="{{ asset('images/logo.svg') }}" width="44" height="44" alt="Peermitly" style="display:inline-block; vertical-align:middle; border:0; outline:none; text-decoration:none;" />
                        <span style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:18px; font-weight:700; color:#1c1917; vertical-align:middle; padding-left:10px; letter-spacing:-0.01em;">Peermitly</span>
                    </td>
                </tr>

                <!-- accent bar -->
                <tr>
                    <td style="padding:0;">
                        <div style="height:3px; background-color:#ea580c; border-radius:999px 999px 0 0; font-size:0; line-height:0;">&nbsp;</div>
                    </td>
                </tr>

                <!-- card -->
                <tr>
                    <td bgcolor="#ffffff" class="py-md px-md" style="background-color:#ffffff; border-radius:0 0 16px 16px; border:1px solid #ece7e1; border-top:0; padding:40px;">
                        {{ $slot }}
                    </td>
                </tr>

                <!-- footer -->
                <tr>
                    <td align="center" style="padding:24px 8px 0 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:12px; line-height:18px; color:#78716c;">
                        © {{ date('Y') }} Peermitly. This is an automated security notification.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
