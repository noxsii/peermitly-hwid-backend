@php
    /** @var string $userName */
    /** @var string $verifyUrl */
    /** @var int $expiresInHours */
    /** @var \Illuminate\Support\Carbon $requestedAt */

    $formattedTime = $requestedAt->copy()->setTimezone(config('app.timezone'))->format('M j, Y \\a\\t H:i T');
@endphp

<x-emails.layout title="Confirm your Peermitly email" preheader="Click the button below to confirm your email address.">

    <h1 class="h1" style="margin:0 0 16px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:28px; line-height:34px; font-weight:600; color:#1c1917; letter-spacing:-0.01em;">
        Confirm your email
    </h1>

    <p style="margin:0 0 24px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:16px; line-height:24px; color:#44403c;">
        Hi{{ $userName ? ' ' . e($userName) : '' }}, thanks for signing up for Peermitly. Click the button below to confirm your email address and activate your account.
    </p>

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin:0 0 24px 0;">
        <tr>
            <td align="center">
                <!--[if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $verifyUrl }}" style="height:44px;v-text-anchor:middle;width:220px;" arcsize="18%" stroke="f" fillcolor="#ea580c">
                    <w:anchorlock/>
                    <center style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:600;">Confirm email</center>
                </v:roundrect>
                <![endif]-->
                <!--[if !mso]><!-- -->
                <a href="{{ $verifyUrl }}"
                   style="background-color:#ea580c; color:#ffffff; display:inline-block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; line-height:44px; text-align:center; text-decoration:none; border-radius:8px; padding:0 24px; mso-hide:all;">
                    Confirm email
                </a>
                <!--<![endif]-->
            </td>
        </tr>
    </table>

    <p style="margin:0 0 16px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#78716c;">
        The link is valid for <strong style="color:#1c1917;">{{ $expiresInHours }} hours</strong> (requested at {{ $formattedTime }}). After that, sign in to request a fresh link.
    </p>

    <p style="margin:0 0 16px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#78716c;">
        Trouble with the button? Paste this URL into your browser:
    </p>

    <p style="margin:0 0 24px 0; font-family: 'SF Mono', Menlo, Consolas, monospace; font-size:12px; line-height:18px; color:#1c1917; word-break:break-all; background:#f5f1ec; padding:10px 12px; border-radius:6px;">
        {{ $verifyUrl }}
    </p>

    <p style="margin:0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#78716c;">
        <strong style="color:#1c1917;">Didn't sign up?</strong>
        You can safely ignore this email — no account will be activated.
    </p>

</x-emails.layout>
