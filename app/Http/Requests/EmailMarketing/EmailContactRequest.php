<?php

namespace App\Http\Requests\EmailMarketing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailContactRequest extends FormRequest
{
    public function rules(): array
    {
        $contact = $this->route('contact');

        return [
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('email_contacts')->ignore($contact)],
            'phone' => ['nullable', 'string', 'max:50'],
            'document' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'in:active,unsubscribed,bounced,invalid'],
            'source' => ['nullable', 'string', 'max:255'],
            'lists' => ['nullable', 'array'],
            'lists.*' => ['integer', 'exists:email_lists,id'],
        ];
    }
}
