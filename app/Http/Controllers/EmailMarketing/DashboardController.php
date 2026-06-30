<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Models\EmailMarketing\EmailCampaign;
use App\Models\EmailMarketing\EmailContact;
use App\Models\EmailMarketing\EmailMessage;
use App\Models\EmailMarketing\EmailProvider;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('email-marketing.dashboard', [
            'providers' => EmailProvider::count(),
            'contacts' => EmailContact::count(),
            'campaigns' => EmailCampaign::latest()->limit(8)->get(),
            'messageStats' => EmailMessage::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
        ]);
    }
}
