<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public $stage, 
        public $stageType, 
        public $reminderType
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            'before_7_days' => 'Upcoming Deadline: ' . $this->getStageName(),
            'current_day'   => 'URGENT: Deadline Today - ' . $this->getStageName(),
            'after_7_days'  => 'CRITICAL DELAY: Action Required - ' . $this->getStageName(),
        ];

        return new Envelope(
            subject: $subjects[$this->reminderType] ?? 'Evaluation System Reminder',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminder_email',
            with: [
                'stageName' => $this->getStageName(),
                'statusColor' => $this->getStatusColor(),
            ],
        );
    }

    private function getStageName()
    {
        $names = [
            'stage_1' => 'Study Entrusted',
            'stage_2' => 'Report Draft HOD Approval',
            'stage_3' => 'Committee Meeting Minutes',
        ];
        return $names[$this->stageType] ?? 'Evaluation Phase';
    }

    private function getStatusColor()
    {
        return match($this->reminderType) {
            'before_7_days' => '#17a2b8', // Blue (Info)
            'current_day'   => '#ffc107', // Yellow (Warning)
            'after_7_days'  => '#dc3545', // Red (Danger)
            default => '#6c757d'
        };
    }
}