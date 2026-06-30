<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMarketing\EmailListRequest;
use App\Models\EmailMarketing\EmailContact;
use App\Models\EmailMarketing\EmailList;

class ListController extends Controller
{
    public function index()
    {
        return view('email-marketing.lists.index', ['lists' => EmailList::withCount('contacts')->latest()->paginate(20)]);
    }

    public function create()
    {
        return view('email-marketing.lists.form', ['list' => new EmailList(), 'contacts' => EmailContact::orderBy('email')->get()]);
    }

    public function store(EmailListRequest $request)
    {
        $list = EmailList::create($request->safe()->except('contacts') + ['active' => request()->boolean('active')]);
        $list->contacts()->sync($request->input('contacts', []));
        return redirect()->route('email-marketing.lists.index')->with('status', 'Lista criada.');
    }

    public function edit(EmailList $list)
    {
        return view('email-marketing.lists.form', ['list' => $list, 'contacts' => EmailContact::orderBy('email')->get()]);
    }

    public function update(EmailListRequest $request, EmailList $list)
    {
        $list->update($request->safe()->except('contacts') + ['active' => request()->boolean('active')]);
        $list->contacts()->sync($request->input('contacts', []));
        return redirect()->route('email-marketing.lists.index')->with('status', 'Lista atualizada.');
    }

    public function destroy(EmailList $list)
    {
        $list->delete();
        return back()->with('status', 'Lista removida.');
    }
}
