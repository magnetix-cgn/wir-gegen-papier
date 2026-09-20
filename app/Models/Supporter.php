<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supporter extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_UNSUBSCRIBED = 'unsubscribed';

    public const CONSENT_TEXT_VERSION = 'supporter-v1';

    protected $fillable = [
        'email',
        'status',
        'confirmation_token_hash',
        'token_expires_at',
        'unsubscribe_token_hash',
        'unsubscribe_token_expires_at',
        'confirmed_at',
        'unsubscribed_at',
        'consent_text_version',
        'locale',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
            'unsubscribe_token_expires_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function isConfirmed(): bool
    {
        return $this->status === self::STATUS_CONFIRMED;
    }
}
