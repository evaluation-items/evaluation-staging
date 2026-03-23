<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Stage;
use App\Mail\ReminderEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendDateReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-date-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Concern Department Sent Email Reminder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Executing: ' . $this->description); // This will print it to the screen
        $stages = Stage::with('schemeSend')
            ->whereHas('schemeSend', function($query) {
                $query->whereNotNull('evaluation_sent_date');
            })->get();

        foreach ($stages as $stage) {
            // --- STAGE 1 LOGIC ---
            // If evaluation is sent, but study_entrusted is still NULL
            if ($stage->schemeSend->evaluation_sent_date && is_null($stage->study_entrusted)) {
                $target = \Carbon\Carbon::parse($stage->schemeSend->evaluation_sent_date)->addDays(30);
                $this->processReminderTiers($stage, 'stage_1', $target);
            }

            // --- STAGE 2 LOGIC ---
            // If report_sent_hod_date is set, but report_draft_hod_date is still NULL
            if ($stage->report_sent_hod_date && is_null($stage->report_draft_hod_date)) {
                $target = \Carbon\Carbon::parse($stage->report_sent_hod_date)->addDays(30);
                $this->processReminderTiers($stage, 'stage_2', $target);
            }
          
          // If Draft is sent (Step 2 finished) but Committee meeting is NOT set (Step 3 pending)
            if ($stage->report_draft_sent_hod_date && is_null($stage->dept_eval_committee_datetime)) {
                $target = \Carbon\Carbon::parse($stage->report_draft_sent_hod_date)->addDays(10);
                $this->processReminderTiers($stage, 'stage_3', $target);
            }
        }
        $this->info('Reminders processed successfully.');
    }
private function processReminderTiers($stage, $stageType, $targetDate)
{
    if (!$targetDate) return;

    $deadline = \Carbon\Carbon::parse($targetDate);
    
    // FOR TESTING ONLY:
    $today = \Carbon\Carbon::parse('2026-04-15')->startOfDay(); 
    
    $diffInDays = (int) $today->diffInDays($deadline, false);

    \Log::info("Testing Stage: $stageType | Today: $today | Deadline: $deadline | Diff: $diffInDays");

    $reminderType = null;
    if ($diffInDays === 7) { $reminderType = 'before_7_days'; }
    elseif ($diffInDays === 0) { $reminderType = 'current_day'; }
    elseif ($diffInDays === -7) { $reminderType = 'after_7_days'; }

    if ($reminderType) {
        \Log::info("Match found! Type: $reminderType");
        
        $alreadySent = \DB::table('imaster.reminder_logs')
            ->where('stage_id', $stage->draft_id)
            ->where('stage_type', $stageType)
            ->where('reminder_type', $reminderType)
            ->exists();

        if (!$alreadySent) {
            $this->sendAndLogEmail($stage, $stageType, $reminderType);
            \Log::info("Email dispatched to Queue.");
        } else {
            \Log::info("Skipped: Already exists in reminder_logs.");
        }
    }
}
   private function processReminderTiers11($stage, $stageType, $targetDate)
    {
        
        if (!$targetDate) return;

        $deadline = \Carbon\Carbon::parse($targetDate);
        // $today = now()->startOfDay();
        $today = \Carbon\Carbon::parse('2026-04-15')->startOfDay();
        
        // Difference calculation
        $diffInDays = $today->diffInDays($deadline, false); // can be negative if deadline passed
       // dd($targetDate,$deadline,$diffInDays);
        $reminderType = null;

        if ($diffInDays === 7) {
            $reminderType = 'before_7_days';
        } elseif ($diffInDays === 0) {
            $reminderType = 'current_day';
        } elseif ($diffInDays === -7) {
            $reminderType = 'after_7_days';
        }

        // If a valid window is hit, check if we already sent it
        if ($reminderType) {
            $alreadySent = \DB::table('imaster.reminder_logs')
                ->where('stage_id', $stage->draft_id)
                ->where('stage_type', $stageType)
                ->where('reminder_type', $reminderType)
                ->where('is_sent', true)
                ->exists();

            if (!$alreadySent) {
                $this->sendAndLogEmail($stage, $stageType, $reminderType);
            }
        }
    }
  private function sendAndLogEmail($stage, $stageType, $reminderType)
{
    $email = 'evaldeveloper123@gmail.com'; 

    try {
        // Use the explicit schema name if needed
        \DB::table('imaster.reminder_logs')->insert([
            'stage_id'      => $stage->draft_id, // Ensure this matches your column name
            'stage_type'    => $stageType,
            'reminder_type' => $reminderType,
            'recipient_email'=> $email,
            'is_sent'       => true,
            'sent_at'       => now(),
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        
        \Log::info("Database Log Success for Stage ID: " . $stage->draft_id);

    } catch (\Exception $e) {
        // This will tell you exactly why the table didn't receive the entry
        \Log::error("Database Insert Failed: " . $e->getMessage());
    }

    // Send the mail after the DB attempt
    \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\ReminderEmail($stage, $stageType, $reminderType));
}
}
