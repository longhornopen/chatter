<?php

namespace App\Console\Commands;

use App\Mail\ActivityDigest;
use App\Models\CourseUser;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendDigestMails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chatter:send_mail_digests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send digests of recent activity via email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ( ! env('APP_FEATURE_MAIL_ACTIVITY_DIGESTS') ) {
            Log::debug("Refusing to send emails due to APP_FEATURE_MAIL_ACTIVITY_DIGESTS being off");
            return 0;
        }
        $a_while_ago = Carbon::now()->subMonths(1);
        $course_users = CourseUser::where('last_launch_at', '>', $a_while_ago)
            ->where('mail_digest_frequency', '>=', 0)
            ->get();
        foreach ($course_users as $course_user) {
            $next_digest_due_at = $course_user->previous_mail_digest_at->addHours($course_user->mail_digest_frequency);
            if ($next_digest_due_at->greaterThan(Carbon::now())) {
                continue;
            }
            $mail = new ActivityDigest($course_user);
            if ($mail->hasContents()) {
                Mail::to($course_user->email)->send($mail);
            }
            $course_user->previous_mail_digest_at = $mail->period_end;
            $course_user->save();
        }
        return 0;
    }
}
