<?php

namespace App\Jobs;

use App\Classes\Export;
use App\Notifications\EmailExport;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessExport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;

    /**
     * Create a new job instance.
     * @return void
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Execute the job.
     * @return void
     * @param Export $export
     */
    public function handle(Export $export)
    {

        $filename = $export->export();

        if($this->email) {

            $emails = [
                ['Hemm', 'hemmit.kerrai@unilad.co.uk'],
                ['Kojo', 'kojo@unilad.co.uk'],
                ['Russell', 'russell@unilad.co.uk'],
            ];

            foreach ($emails as $email) {

                $user = new User();
                $user->name = $email[0];
                $user->email = $email[1];

                Notification::send($user, new EmailExport($filename));
            }
        }




    }
}
