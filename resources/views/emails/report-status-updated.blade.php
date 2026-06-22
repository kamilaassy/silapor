<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body style="margin:0; padding:0; background-color:#F6F2E9; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F6F2E9; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="background-color:#ffffff; border-radius:12px; overflow:hidden; max-width:480px;">

                    <tr>
                        <td style="background-color:#4A6B4D; padding:24px 32px;">
                            <span style="color:#ffffff; font-size:18px; font-weight:bold;">SiLapor</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <h1 style="font-size:18px; color:#2A2620; margin:0 0 16px;">Update Status Laporan Anda</h1>

                            <p style="font-size:14px; color:#494438; line-height:1.6; margin:0 0 16px;">
                                Halo <strong>{{ $report->user->name }}</strong>,
                            </p>

                            <p style="font-size:14px; color:#494438; line-height:1.6; margin:0 0 20px;">
                                Status laporan Anda dengan nomor <strong>{{ $report->report_number }}</strong> telah diperbarui.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F1EDE3; border-radius:8px; margin-bottom:20px;">
                                <tr>
                                    <td style="padding:16px 20px;">
                                        <p style="font-size:15px; font-weight:bold; color:#2A2620; margin:0 0 8px;">{{ $report->title }}</p>
                                        <p style="font-size:13px; color:#827A68; margin:0 0 4px;">Status terbaru:</p>
                                        <p style="font-size:14px; font-weight:bold; color:{{ $report->status->color_hex }}; margin:0;">{{ $report->status->name }}</p>
                                        @if($report->status->description)
                                            <p style="font-size:13px; color:#827A68; margin:8px 0 0;">{{ $report->status->description }}</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            @if($report->histories->first()?->note)
                                <p style="font-size:13px; font-weight:bold; color:#2A2620; margin:0 0 6px;">Catatan dari petugas:</p>
                                <p style="font-size:13px; color:#494438; line-height:1.5; margin:0 0 20px; background-color:#FAEEDA; padding:12px 16px; border-radius:8px;">
                                    {{ $report->histories->first()->note }}
                                </p>
                            @endif

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 8px 0 24px;">
                                <tr>
                                    <td style="background-color:#C2682F; border-radius:8px;">
                                        <a href="{{ route('reports.show', $report->report_number) }}"
                                           style="display:inline-block; padding:12px 24px; font-size:14px; font-weight:bold; color:#ffffff; text-decoration:none;">
                                            Lihat Detail Laporan
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#827A68; line-height:1.6; margin:0;">
                                Terima kasih telah berpartisipasi menjaga lingkungan sekitar kita.
                            </p>

                            <p style="font-size:13px; color:#494438; margin:20px 0 0;">
                                Salam,<br>
                                <strong>Tim SiLapor</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 32px; background-color:#FAF8F4; border-top:1px solid #E3DDCE;">
                            <p style="font-size:11px; color:#A89E87; margin:0; text-align:center;">
                                Email otomatis dari SiLapor — jangan balas email ini.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>