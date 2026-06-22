@php
    /** @var \Illuminate\Support\Carbon $loggedInAt */
    /** @var string $userEmail */
    /** @var string|null $userName */
    /** @var string $ipAddress */
    /** @var string $userAgent */

    $formattedTime = $loggedInAt->copy()->setTimezone(config('app.timezone'))->format('M j, Y \\a\\t H:i T');
    $loginUrl = url('/login');
@endphp

<x-emails.layout title="New sign-in to your Peermitly account" preheader="A new sign-in was detected on your Permitlyy account.">

    <h1 class="h1" style="margin:0 0 16px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:28px; line-height:34px; font-weight:600; color:#0f172a; letter-spacing:-0.01em;">
        New sign-in to your Peermitly account
    </h1>

    <p style="margin:0 0 24px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:16px; line-height:24px; color:#334155;">
        Hi{{ $userName ? ' ' . e($userName) : '' }}, we noticed a new sign-in to your Peermitly account. If this was you, you can safely ignore this email.
    </p>

    {{-- Detail table --}}
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin:0 0 32px 0; border-collapse:collapse;">
        <tr>
            <td style="padding:14px 0; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; color:#64748b; width:140px;">Account</td>
            <td style="padding:14px 0; border-top:1px solid #e2e8f0; border-bottom:1px solid #e2e8f0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:14px; color:#0f172a; font-weight:500;">{{ $userEmail }}</td>
        </tr>
        <tr>
            <td style="padding:14px 0; border-bottom:1px solid #e2e8f0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; color:#64748b;">Time</td>
            <td style="padding:14px 0; border-bottom:1px solid #e2e8f0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:14px; color:#0f172a; font-weight:500;">{{ $formattedTime }}</td>
        </tr>
        <tr>
            <td style="padding:14px 0; border-bottom:1px solid #e2e8f0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; color:#64748b;">IP&nbsp;address</td>
            <td style="padding:14px 0; border-bottom:1px solid #e2e8f0; font-family: 'SF Mono', Menlo, Consolas, monospace; font-size:13px; color:#0f172a;">{{ $ipAddress }}</td>
        </tr>
        <tr>
            <td style="padding:14px 0; border-bottom:1px solid #e2e8f0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; color:#64748b; vertical-align:top;">Device</td>
            <td style="padding:14px 0; border-bottom:1px solid #e2e8f0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; color:#0f172a; word-break:break-word;">{{ $userAgent }}</td>
        </tr>
    </table>

    {{-- Bulletproof button --}}
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin:0 0 28px 0;">
        <tr>
            <td align="center">
                <!--[if mso]>
                <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="{{ $loginUrl }}" style="height:44px;v-text-anchor:middle;width:220px;" arcsize="18%" stroke="f" fillcolor="#0f172a">
                    <w:anchorlock/>
                    <center style="color:#ffffff;font-family:Arial,Helvetica,sans-serif;font-size:14px;font-weight:600;">Review my account</center>
                </v:roundrect>
                <![endif]-->
                <!--[if !mso]><!-- -->
                <a href="{{ $loginUrl }}"
                   style="background-color:#0f172a; color:#ffffff; display:inline-block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:14px; font-weight:600; line-height:44px; text-align:center; text-decoration:none; border-radius:8px; padding:0 24px; mso-hide:all;">
                    Review my account
                </a>
                <!--<![endif]-->
            </td>
        </tr>
    </table>

    <p style="margin:0 0 8px 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#64748b;">
        <strong style="color:#0f172a;">Wasn't you?</strong>
        Change your password immediately and review your active sessions.
    </p>
    <p style="margin:0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif; font-size:13px; line-height:20px; color:#64748b;">
        We send this email every time someone signs in to your account, so you always know.
    </p>

</x-emails.layout>
