<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMarketing\EmailCampaignRequest;
use App\Jobs\EmailMarketing\GenerateCampaignMessagesJob;
use App\Jobs\EmailMarketing\ProcessPendingEmailMessagesJob;
use App\Models\EmailMarketing\EmailCampaign;
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
