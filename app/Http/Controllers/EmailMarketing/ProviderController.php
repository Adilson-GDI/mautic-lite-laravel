<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMarketing\EmailProviderRequest;
use App\Models\EmailMarketing\EmailProvider;
use App\Models\EmailMarketing\EmailMessage;
use App\Services\EmailMarketing\EmailSenderFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProviderController extends Controller
{
    public function index()
    {
        return view('email-marketing.providers.index', ['providers' => EmailProvider::latest()->paginate(20)]);
    }

    public function create()
    {
        return view('email-marketing.providers.form', ['provider' => new EmailProvider()]);
    }

    public function store(EmailProviderRequest $request)
    {
        EmailProvider::create($this->payload($request));
        return redirect()->route('email-marketing.providers.index')->with('status', 'Provedor criado.');
    }

    public function edit(EmailProvider $provider)
    {
        return view('email-marketing.providers.form', compact('provider'));
    }

    public function update(EmailProviderRequest $request, EmailProvider $provider)
    {
        $provider->update($this->payload($request, $provider));
        return redirect()->route('email-marketing.providers.index')->with('status', 'Provedor atualizado.');
    }

    public function destroy(EmailProvider $provider)
    {
        $provider->delete();
        return back()->with('status', 'Provedor removido.');
    }

    public function test(Request $request, EmailProvider $provider, EmailSenderFactory $factory)
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $message = new EmailMessage([
            'provider_id' => $provider->id,
            'contact_id' => 0,
            'tracking_token' => (string) Str::uuid(),
            'to_email' => $data['email'],
            'subject' => 'Teste de envio - '.$provider->name,
            'html_body' => '<p>Teste de envio realizado pelo painel.</p>',
            'text_body' => 'Teste de envio realizado pelo painel.',
        ]);
        $message->setRelation('provider', $provider);
        $message->setRelation('contact', new \App\Models\EmailMarketing\EmailContact(['email' => $data['email'], 'status' => 'active']));

        $result = $factory->make($provider)->send($message);
        return back()->with($result->success ? 'status' : 'error', $result->success ? 'Teste enviado.' : $result->errorMessage);
    }

    private function payload(EmailProviderRequest $request, ?EmailProvider $provider = null): array
    {
        $data = $request->validated();
        $data['active'] = $request->boolean('active');
        $data['daily_limit'] ??= 0;
        $data['hourly_limit'] ??= 0;

        foreach (['smtp_password', 'aws_key', 'aws_secret'] as $secret) {
            if (($provider && blank($data[$secret] ?? null))) {
                unset($data[$secret]);
            }
        }

        return $data;
    }
}
