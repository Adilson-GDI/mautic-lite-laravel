<?php

namespace App\Http\Requests\EmailMarketing;

use Illuminate\Foundation\Http\FormRequest;

class EmailProviderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:google_workspace,aws_ses'],
            'from_name' => ['required', 'string', 'max:255'],
            'from_email' => ['required', 'email', 'max:255'],
            'reply_to' => ['nullable', 'email', 'max:255'],
            'smtp_host' => ['required_if:type,google_workspace', 'nullable', 'string', 'max:255'],
            'smtp_port' => ['required_if:type,google_workspace', 'nullable', 'integer', 'min:1', 'max:65535'],
            'smtp_username' => ['required_if:type,google_workspace', 'nullable', 'string', 'max:255'],
            'smtp_password' => [$this->route('provider') ? 'nullable' : 'required_if:type,google_workspace', 'string'],
            'smtp_encryption' => ['nullable', 'in:tls,ssl'],
            'aws_key' => ['nullable', 'string'],
            'aws_secret' => ['nullable', 'string'],
            'aws_region' => ['nullable', 'string', 'max:100'],
            'daily_limit' => ['nullable', 'integer', 'min:0'],
            'hourly_limit' => ['nullable', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],
        ];
    }
}
