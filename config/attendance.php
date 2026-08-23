<?php

return [
    'qr_ttl_seconds' => (int) env('ATTENDANCE_QR_TTL', 20),
    'entry_code_length' => (int) env('ATTENDANCE_ENTRY_CODE_LENGTH', 6),
    'qr_scan_per_minute' => (int) env('ATTENDANCE_QR_SCAN_PER_MINUTE', 10),
    'attachment_max_kilobytes' => 10240,
    'allowed_attachment_mimes' => [
        'pdf',
        'jpg',
        'jpeg',
        'png',
        'webp',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'txt',
    ],
];
