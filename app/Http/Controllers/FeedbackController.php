<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index()
    {
        return view('feedback');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email',
            'feedback' => 'required',
        ]);

        $feedback           = new Feedback();
        $feedback->name     = $request->name;
        $feedback->email    = $request->email;
        $feedback->feedback = $request->feedback;
        $feedback->save();

        return redirect('/feedback')->with('success', 'Thank you for your feedback!');
    }
}
