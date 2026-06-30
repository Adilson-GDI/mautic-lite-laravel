<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMarketing\EmailContactRequest;
use App\Models\EmailMarketing\EmailContact;
use App\Models\EmailMarketing\EmailList;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('email-marketing.contacts.index', ['contacts' => EmailContact::with('lists')->latest()->paginate(30)]);
    }

    public function create()
    {
        return view('email-marketing.contacts.form', ['contact' => new EmailContact(), 'lists' => EmailList::orderBy('name')->get()]);
    }

    public function store(EmailContactRequest $request)
    {
        $contact = EmailContact::create($request->safe()->except('lists'));
        $contact->lists()->sync($request->input('lists', []));
        return redirect()->route('email-marketing.contacts.index')->with('status', 'Contato criado.');
    }

    public function edit(EmailContact $contact)
    {
        return view('email-marketing.contacts.form', ['contact' => $contact, 'lists' => EmailList::orderBy('name')->get()]);
    }

    public function update(EmailContactRequest $request, EmailContact $contact)
    {
        $contact->update($request->safe()->except('lists'));
        $contact->lists()->sync($request->input('lists', []));
        return redirect()->route('email-marketing.contacts.index')->with('status', 'Contato atualizado.');
    }

    public function destroy(EmailContact $contact)
    {
        $contact->delete();
        return back()->with('status', 'Contato removido.');
    }

    public function import(Request $request)
    {
        $data = $request->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt'],
            'list_id' => ['nullable', 'exists:email_lists,id'],
        ]);

        $handle = fopen($data['csv']->getRealPath(), 'r');
        $imported = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            [$name, $email] = array_pad($row, 2, null);
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $contact = EmailContact::firstOrCreate(['email' => strtolower($email)], [
                'name' => $name,
                'status' => 'active',
                'source' => 'csv',
            ]);

            if (! empty($data['list_id'])) {
                $contact->lists()->syncWithoutDetaching([$data['list_id']]);
            }

            $imported++;
        }

        fclose($handle);

        return back()->with('status', "{$imported} contatos importados.");
    }
}
