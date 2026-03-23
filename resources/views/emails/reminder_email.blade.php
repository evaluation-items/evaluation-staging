<!DOCTYPE html>
<html>
<head>
    <style>
        .container { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        .header { padding: 10px; color: white; border-radius: 5px; text-align: center; }
        .details { margin: 20px 0; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .footer { font-size: 12px; color: #777; margin-top: 30px; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header" style="background-color: {{ $statusColor }};">
            <h1>Evaluation System Notification</h1>
        </div>

        <p>Dear Officer,</p>

        <p>This is an automated reminder regarding a pending task in the <strong>Directorate of Evaluation</strong> portal.</p>

        <div class="details">
            <p><strong>Stage:</strong> {{ $stageName }}</p>
            <p><strong>Status:</strong> 
                @if($reminderType == 'after_7_days') 
                    <span style="color: red;">7 Days Overdue</span>
                @elseif($reminderType == 'current_day')
                    <span style="color: orange;">Due Today</span>
                @else
                    <span style="color: blue;">Approaching Deadline (7 Days Left)</span>
                @endif
            </p>
            <p><strong>Draft ID:</strong> {{ $stage->draft_id }}</p>
        </div>

        <p>Please log in to the portal to update the status of this evaluation to avoid further delays.</p>

        <div class="footer">
            <p>Government of Gujarat | GAD Planning & Evaluation Office</p>
            <p>This is a system-generated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>