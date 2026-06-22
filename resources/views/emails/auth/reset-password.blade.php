@php
    /** @var string $userName */
    /** @var string $resetUrl */
    /** @var int $expiresInMinutes */
    /** @var \Illuminate\Support\Carbon $requestedAt */

    $formattedTime = $requestedAt->copy()->setTimezone(config('app.timezone'))->format('M j, Y \\a\\t H:i T');
@endphp

<x-emails.layout title="Reset your Peermitly password" preheader="Click the button below to choose a new password.">

    <h1 class="h1" style="margin:0 0 16px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:28px; line-height:34px; font-weight:600; color:#0f172a; letter-spacing:-0.01em;">
        Reset your password
    </h1>

    <p style="margin:0 0 24px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:16px; line-height:24px; color:#334155;">
        Hi{{ $userName ? ' ' . e($userName) : '' }}, we received a request to reset the password for your Peermitly account. Click the button below to choose a new one.
    </p>

    {{-- Bulletproof button --}}
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin:0 0 24px 0;">
        <tr>
            <td align="center">
                <!--[if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $resetUrl }}" style="height:44px;v-text-anchor:middle;width:220px;" arcsize="18%" stroke="f" fillcolor="#0f172a">
                    <w:anchorlock/>
                    <center style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:600;">Reset password</center>
                </v:roundrect>
                <![endif]-->
                <!--[if !mso]><!-- -->
                <a href="{{ $resetUrl }}"
                   style="background-color:#0f172a; color:#ffffff; display:inline-block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; line-height:44px; text-align:center; text-decoration:none; border-radius:8px; padding:0 24px; mso-hide:all;">
                    Reset password
                </a>
                <!--<![endif]-->
            </td>
        </tr>
    </table>

    <p style="margin:0 0 16px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#64748b;">
        The link is valid for <strong style="color:#0f172a;">{{ $expiresInMinutes }} minutes</strong> (requested at {{ $formattedTime }}). After that, request a fresh link.
    </p>

    <p style="margin:0 0 16px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#64748b;">
        Trouble with the button? Paste this URL into your browser:
    </p>

    <p style="margin:0 0 24px 0; font-family: 'SF Mono', Menlo, Consolas, monospace; font-size:12px; line-height:18px; color:#0f172a; word-break:break-all; background:#f8fafc; padding:10px 12px; border-radius:6px;">
        {{ $resetUrl }}
    </p>

    <p style="margin:0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#64748b;">
        <strong style="color:#0f172a;">Didn't request this?</strong>
        You can safely ignore this email — your password will not be changed.
    </p>

</x-emails.layout>
