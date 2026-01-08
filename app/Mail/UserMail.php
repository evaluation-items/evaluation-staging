<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable {

    use Queueable, SerializesModels;
    
    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details) {
        $this->details= $details;
    }

    /** 
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $subject = $this->details['subject'];
        $email = $this->details['email'];
        $password = $this->details['password'];
        return $this->subject($subject)->view('emails.user_creation_email');
    }
}

