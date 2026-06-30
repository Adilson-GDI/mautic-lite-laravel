<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMarketing\EmailCampaignRequest;
use App\Jobs\EmailMarketing\GenerateCampaignMessagesJob;
use App\Jobs\EmailMarketing\ProcessPendingEmailMessagesJob;
use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailEvent;
use App\Models\EmailMarketing\EmailList;
use App\Models\EmailMarketing\EmailProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        return view('email-marketing.campaigns.index', [
            'campaigns' => EmailCampaign::with('provider')->withCount('messages')->latest()->paginate(20),
        ]);
    }

    public function create()
    {
        return view('email-marketing.campaigns.form', [
            'campaign' => new EmailCampaign(['status' => 'draft']),
            'providers' => EmailProvider::where('active', true)->orderBy('name')->get(),
            'lists' => EmailList::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(EmailCampaignRequest $request)
    {
        $campaign = EmailCampaign::create($request->safe()->except('lists'));
        $campaign->lists()->sync($request->input('lists'));
        return redirect()->route('email-marketing.campaigns.show', $campaign)->with('status', 'Campanha criada.');
    }

    public function show(EmailCampaign $campaign)
    {
        $campaign->load('provider', 'lists');
        $stats = $campaign->messages()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $total = max(1, (int) $stats->sum());

        return view('email-marketing.campaigns.show', [
            'campaign' => $campaign,
            'stats' => $stats,
            'openRate' => round((((int) ($stats['opened'] ?? 0) + (int) ($stats['clicked'] ?? 0)) / $total) * 100, 2),
            'clickRate' => round(((int) ($stats['clicked'] ?? 0) / $total) * 100, 2),
            'errorRate' => round(((int) ($stats['failed'] ?? 0) / $total) * 100, 2),
        ]);
    }

    public function report(EmailCampaign $campaign)
    {
        $campaign->load('provider', 'lists');

        $stats = $campaign->messages()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $eventStats = EmailEvent::whereHas('message', fn ($query) => $query->where('campaign_id', $campaign->id))
            ->selectRaw('event_type, count(*) as total')
            ->groupBy('event_type')
            ->pluck('total', 'event_type');

        $total = max(1, (int) $campaign->messages()->count());
        $sent = (int) $campaign->messages()->whereIn('status', ['sent', 'opened', 'clicked', 'delivered'])->count();
        $opened = (int) (($eventStats['opened'] ?? 0));
        $clicked = (int) (($eventStats['clicked'] ?? 0));

        return view('email-marketing.campaigns.report', [
            'campaign' => $campaign,
            'stats' => $stats,
            'eventStats' => $eventStats,
            'total' => $total,
            'sent' => $sent,
            'openRate' => round(($opened / $total) * 100, 2),
            'clickRate' => round(($clicked / $total) * 100, 2),
            'topLinks' => EmailEvent::whereHas('message', fn ($query) => $query->where('campaign_id', $campaign->id))
                ->where('event_type', 'clicked')
                ->whereNotNull('url')
                ->selectRaw('url, count(*) as total')
                ->groupBy('url')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),
            'events' => EmailEvent::with('message.contact')
                ->whereHas('message', fn ($query) => $query->where('campaign_id', $campaign->id))
                ->latest('created_at')
                ->paginate(20),
            'messages' => $campaign->messages()
                ->with('contact')
                ->latest()
                ->paginate(20, ['*'], 'messages_page'),
        ]);
    }

    public function edit(EmailCampaign $campaign)
    {
        return view('email-marketing.campaigns.form', [
            'campaign' => $campaign,
            'providers' => EmailProvider::orderBy('name')->get(),
            'lists' => EmailList::orderBy('name')->get(),
        ]);
    }

    public function update(EmailCampaignRequest $request, EmailCampaign $campaign)
    {
        $campaign->update($request->safe()->except('lists'));
        $campaign->lists()->sync($request->input('lists'));
        return redirect()->route('email-marketing.campaigns.show', $campaign)->with('status', 'Campanha atualizada.');
    }

    public function destroy(EmailCampaign $campaign)
    {
        $campaign->delete();
        return redirect()->route('email-marketing.campaigns.index')->with('status', 'Campanha removida.');
    }

    public function start(EmailCampaign $campaign)
    {
        $campaign->update(['status' => 'sending', 'started_at' => $campaign->started_at ?? now()]);
        GenerateCampaignMessagesJob::dispatch($campaign->id);
        ProcessPendingEmailMessagesJob::dispatch();
        return back()->with('status', 'Campanha enviada para processamento.');
    }

    public function pause(EmailCampaign $campaign)
    {
        $campaign->update(['status' => 'paused']);
        return back()->with('status', 'Campanha pausada.');
    }

    public function cancel(EmailCampaign $campaign)
    {
        $campaign->update(['status' => 'canceled', 'finished_at' => now()]);
        $campaign->messages()->whereIn('status', ['pending', 'processing'])->update(['status' => 'canceled']);
        return back()->with('status', 'Campanha cancelada.');
    }

    public function process()
    {
        ProcessPendingEmailMessagesJob::dispatch();
        return back()->with('status', 'Fila acionada.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
        ]);

        $directory = public_path('email-images');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $image = $request->file('image');
        $filename = Str::uuid().'.'.$image->getClientOriginalExtension();

        $image->move($directory, $filename);

        return response()->json([
            'url' => asset('email-images/'.$filename),
        ]);
    }
}
