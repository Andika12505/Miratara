<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Please enter a valid email address.');
        }

        $email = $request->email;

        // Here you can:
        // 1. Save to database (create newsletters table)
        // 2. Send to email service (Mailchimp, SendGrid, etc.)
        // 3. Add to mailing list
        
        // For now, just simulate success
        try {
            // Example: Save to database
            // Newsletter::firstOrCreate(['email' => $email]);
            
            // Example: Send welcome email
            // Mail::to($email)->send(new WelcomeNewsletter());
            
            return back()->with('success', 'Thank you! You have been subscribed to our newsletter.');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong. Please try again later.');
        }
    }
}