<?php

return [
    'statuses' => [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],
    
    'clearance_types' => [
        'graduation' => 'Graduation',
        'withdrawal' => 'Withdrawal',
        'transfer' => 'Transfer',
        'dismissal' => 'Dismissal',
        'temporary_leave' => 'Temporary Leave',
        'semester_completion' => 'Semester Completion',
    ],
    
    'reminder_days' => 3,
    
    'auto_complete_days' => 30,
    
    'upload_paths' => [
        'students' => 'uploads/students',
        'signatures' => 'uploads/signatures',
        'logos' => 'uploads/logos',
    ],
    
    'pdf_settings' => [
        'paper_size' => 'A4',
        'orientation' => 'portrait',
        'font_family' => 'DejaVu Sans',
    ],
];