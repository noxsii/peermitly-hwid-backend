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
<body style="margin:0; padding:0; background-color:#f8fafc; color:#0f172a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
<!-- preheader -->
<div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#f8fafc;">
    {{ $preheader ?? 'Peermitly' }}
</div>

<table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#f8fafc" style="background-color:#f8fafc;">
    <tr>
        <td align="center" style="padding:40px 16px;">
            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" class="container" style="width:600px; max-width:600px;">

                <!-- header -->
                <tr>
                    <td align="left" style="padding:0 0 24px 0;">
                        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td bgcolor="#0f172a" style="background-color:#0f172a; width:36px; height:36px; border-radius:8px; color:#ffffff; font-family: Arial, Helvetica, sans-serif; font-weight:700; font-size:14px; text-align:center; vertical-align:middle;" align="center" valign="middle">P</td>
                                <td style="padding-left:12px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:16px; font-weight:600; color:#0f172a; vertical-align:middle;" valign="middle">Peermitly</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- card -->
                <tr>
                    <td bgcolor="#ffffff" class="py-md px-md" style="background-color:#ffffff; border-radius:16px; border:1px solid #e2e8f0; padding:40px;">
                        {{ $slot }}
                    </td>
                </tr>

                <!-- footer -->
                <tr>
                    <td align="center" style="padding:24px 8px 0 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:12px; line-height:18px; color:#64748b;">
                        © {{ date('Y') }} Peermitly. This is an automated security notification.
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
