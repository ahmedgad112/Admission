<?php

return [
    'qr_ttl_seconds' => (int) env('ATTENDANCE_QR_TTL', 60),
    'qr_ttl_min' => 10,
    'qr_ttl_max' => 600,
    'qr_ttl_presets' => [20, 30, 45, 60, 90, 120, 180, 300],
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
