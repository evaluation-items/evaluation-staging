<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Mail\FeedbackMail;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Mail;

class SlugController extends Controller
{
        public function accessibility(){
            return view('common_pages.accessibility-statement');
        }
        public function copyright(){
            return view('common_pages.copyright-policy');
        }
        public function feedback(){
            return view('common_pages.feedback');
        }
        public function aboutUs(){
            return view('common_pages.about-us');
        }
        public function feedbackSubmit(Request $request){
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'city' => 'required|string|max:255',
                'message' => 'required|string',
            ]);     
            Feedback::create([
                'name' => $request->name,
                'email' => $request->email,
                'contactno' => $request->contactno,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'message' => $request->message,
            ]);
            $email = ["direvl@gujarat.gov.in"];
            $detail =[
                'name' => $request->name,
                'email' => $request->email,
                'contactno' => $request->contactno,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'message' => $request->message,
            ];
            Mail::to($email)->send(new FeedbackMail($detail));
           
            return redirect()->back()->with('success', 'Thank you for your feedback!');
        }
        public function help(){
            return view('common_pages.help');
        }
        public function hyperlinkPolicy(){
            return view('common_pages.hyperlink-policy');
        }
        public function privacyPolicy(){
            return view('common_pages.privacy-policy');
        }
        public function termsCondition(){
            return view('common_pages.terms-condition');
        }
}
