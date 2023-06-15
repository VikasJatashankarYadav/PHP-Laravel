<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    //
    public function store(Request $request){
        // return 'ture';
        $contact = new Contact;
        $contact->first_name = $request->first_name;
        $contact->last_name = $request->last_name;
        $contact->email = $request->email;
        $contact->info = $request->info;
        $contact->save();
        return response()->json([
            'message' => 'Success',
            'status' => 1,
        ]);
    }
}
