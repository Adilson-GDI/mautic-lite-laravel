<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Models\EmailMarketing\EmailEvent;
use App\Models\EmailMarketing\EmailMessage;
use App\Models\EmailMarketing\EmailUnsubscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class TrackingController extends Controller
{
    public function open(Request $request, string $token)
    {
        $message = EmailMessage::where('tracking_token', $token)->firstOrFail();
        $message->update(['status' => $message->status === 'sent' ? 'opened' : $message->status, 'opened_at' => $message->opened_at ?? now()]);
        $this->event($message, 'opened', $request);

        $pixel = base64_decode('R0lGODlhAQABAPAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw==');
        return Response::make($pixel, 200, ['Content-Type' => 'image/gif']);
    }

    public function click(Request $request, string $token)
    {
        $data = $request->validate(['url' => ['required', 'url']]);
        $message = EmailMessage::where('tracking_token', $token)->firstOrFail();
        $message->update(['status' => 'clicked', 'clicked_at' => $message->clicked_at ?? now()]);
        $this->event($message, 'clicked', $request, $data['url']);

        return redirect()->away($data['url']);
    }

    public function unsubscribe(Request $request, string $token)
    {
        $message = EmailMessage::with('contact')->where('tracking_token', $token)->firstOrFail();
        $message->contact->update(['status' => 'unsubscribed']);

        EmailUnsubscribe::firstOrCreate(
            ['token' => $token],
            [
                'contact_id' => $message->contact_id,
                'email' => $message->to_email,
                'campaign_id' => $message->campaign_id,
                'reason' => $request->input('reason'),
                'unsubscribed_at' => now(),
            ],
        );

        $this->event($message, 'unsubscribed', $request);

        return view('email-marketing.unsubscribed', ['email' => $message->to_email]);
    }

    private function event(EmailMessage $message, string $type, Request $request, ?string $url = null): void
    {
        EmailEvent::create([
            'message_id' => $message->id,
            'event_type' => $type,
            'url' => $url,
            'ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            'created_at' => now(),
        ]);
    }
}
