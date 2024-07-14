<?php

namespace App\Models;

use App\Observers\IpAddressObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

#[ObservedBy([IpAddressObserver::class])]
class IpAddress extends Model implements AuditableContract
{
    use HasFactory, Auditable;

    protected $fillable = [
        'label',
        'ip_address',
    ];

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updator() : BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
