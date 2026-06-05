<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CertificateAudit extends Model
{
    use HasFactory;

    protected $table = 'certificate_audits';

    protected $fillable = [
        'clearance_id',
        'user_id',
        'ip_address',
        'action',
        'security_code',
        'issued_date',
        'validity_date',
        'issued_by',
        'timestamp',
    ];

    protected $casts = [
        'issued_date' => 'datetime',
        'validity_date' => 'datetime',
        'timestamp' => 'datetime',
    ];

    /**
     * Relationship: Clearance
     */
    public function clearance()
    {
        return $this->belongsTo(ClearanceRequest::class, 'clearance_id');
    }

    /**
     * Relationship: User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
