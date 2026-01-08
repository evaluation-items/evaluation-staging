<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Attachment;
use App\Couchdb\Couchdb;

class MeetingMail extends Mailable {

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
        $draft_id = $this->details['draft_id'];
        $scheme_id = $this->details['scheme_id'];
        $scheme_id = "scheme_".$scheme_id;
        $extended = new Couchdb();
        $extended->InitConnection();
        $status = $extended->isRunning();
        $out = $extended->getDocument('gad',$scheme_id);
        $arrays = json_decode($out, True);
        // dd($arrays);
        if(isset($arrays)) {
            $attachments = $arrays['_attachments'];
        } else {
            return "no data";
        }
        $scheme = $scheme_id."_meeting".".pdf";
        $file_path = 'http://admin:admin@10.10.2.54:5984/gad/'.$scheme_id.'/'.$scheme;
        // dd($file_path);
        // $details = $this->details();
        // dd($this->details['time']);
        return $this->subject($subject)->attach($file_path)->view('dashboards.eva-dir.meeting_details_mail');
    }
}

