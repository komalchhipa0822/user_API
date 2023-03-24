<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\UserMail;
use Mail;

class SendUserEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details,$subject;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details,$subject)
    {
        $this->details = $details;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
     public function handle()
    {
        Mail::to($this->details['email'])->send(new UserMail($this->details,$this->subject));
    }
}
