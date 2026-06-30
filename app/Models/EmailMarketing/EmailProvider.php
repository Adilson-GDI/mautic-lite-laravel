<?php

namespace App\Models\EmailMarketing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EmailProvider extends Model
{
    protected $fillable = [
        'name', 'type', 'from_name', 'from_email', 'reply_to', 'smtp_host', 'smtp_port',
        'smtp_username', 'smtp_password', 'smtp_encryption', 'aws_key', 'aws_secret',
        'aws_region', 'daily_limit', 'hourly_limit', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'daily_limit' => 'integer',
        'hourly_limit' => 'integer',
    ];

    public function campaigns()
    {
        return $this->hasMany(EmailCampaign::class, 'provider_id');
    }

    public function messages()
    {
        return $this->hasMany(EmailMessage::class, 'provider_id');
    }

    public function setSmtpPasswordAttribute($value): void
    {
        $this->attributes['smtp_password'] = filled($value) ? Crypt::encryptString($value) : null;
    }

    public function getSmtpPasswordPlainAttribute(): ?string
    {
        return $this->decryptNullable('smtp_password');
    }

    public function setAwsKeyAttribute($value): void
    {
        $this->attributes['aws_key'] = filled($value) ? Crypt::encryptString($value) : null;
    }

    public function getAwsKeyPlainAttribute(): ?string
    {
        return $this->decryptNullable('aws_key');
    }

    public function setAwsSecretAttribute($value): void
    {
        $this->attributes['aws_secret'] = filled($value) ? Crypt::encryptString($value) : null;
    }

    public function getAwsSecretPlainAttribute(): ?string
    {
        return $this->decryptNullable('aws_secret');
    }

    private function decryptNullable(string $key): ?string
    {
        if (blank($this->attributes[$key] ?? null)) {
            return null;
        }

        return Crypt::decryptString($this->attributes[$key]);
    }
}
