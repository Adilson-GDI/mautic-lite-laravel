<?php

namespace App\Http\Requests\EmailMarketing;

use Illuminate\Foundation\Http\FormRequest;

class EmailCampaignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'provider_id' => ['required', 'exists:email_providers,id'],
            'subject' => ['required', 'string', 'max:255'],
            'preheader' => ['nullable', 'string', 'max:255'],
            'html_body' => ['required', 'string'],
            'text_body' => ['nullable', 'string'],
            'status' => ['required', 'in:draft,scheduled,paused,canceled'],
            'scheduled_at' => ['nullable', 'date'],
            'lists' => ['required', 'array', 'min:1'],
            'lists.*' => ['integer', 'exists:email_lists,id'],
        ];
    }
}
