<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contact;

class ContactController extends Controller
{
    
    public function index()
    {
        $contacts = Contact::latest()->get();
        return $contacts;
    }

    //single data from contact
    public function show($id)
    {
        return contact::find($id);
    }

    public function store(Request $request)
    {

        $contact = Contact::create($request->all());
        return response()->json(['contact'=>$contact], 200);

    }
    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        $contact->update($request->all());
        return response()->json(['contact'=>$contact], 200);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id)->delete();
        return response()->json(['contact'=>$contact], 200);
    }
}
