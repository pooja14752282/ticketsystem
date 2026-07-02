<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="margin:0;padding:0;background:#f4f4f7;font-family:Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f7;padding:24px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:8px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#111827;padding:18px 24px;">
                            <span style="color:#ffffff;font-size:16px;font-weight:600;">SEEL Support — Ticket Update</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px;">
                            <p style="font-size:14px;color:#111827;margin:0 0 12px 0;">
                                Ticket <strong>{{ $ticket->ticket_id }}</strong> has been reviewed.
                            </p>

                            <table width="100%" cellpadding="6" cellspacing="0" style="font-size:13px;color:#374151;margin-bottom:16px;">
                                <tr>
                                    <td style="width:150px;color:#6b7280;">Title</td>
                                    <td>{{ $ticket->title }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Resolution Status</td>
                                    <td><strong>{{ $review->resolution_status }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Reviewed By</td>
                                    <td>{{ $reviewer->name ?? $reviewer->email }}</td>
                                </tr>
                                <tr>
                                    <td style="color:#6b7280;">Reviewed On</td>
                                    <td>{{ $review->updated_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </table>

                            <p style="font-size:13px;color:#6b7280;margin:0 0 6px 0;">Notes:</p>
                            <div style="font-size:13px;color:#111827;background:#f9fafb;border:1px solid #e5e7eb;border-radius:6px;padding:12px;line-height:1.6;">
                                {{ $review->notes }}
                            </div>

                            <div style="margin-top:24px;">
                                <a href="{{ route('support.ticket.show', $ticket) }}"
                                   style="display:inline-block;background:#111827;color:#ffffff;text-decoration:none;padding:10px 18px;border-radius:6px;font-size:13px;">
                                    View Ticket
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 24px;background:#f9fafb;border-top:1px solid #e5e7eb;">
                            <span style="font-size:11px;color:#9ca3af;">This is an automated notification from SEEL Support.</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>