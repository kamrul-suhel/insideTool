<?php

namespace App\Jobs;

use App\Classes\Export;
use App\Notifications\EmailExport;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
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
     * @return mixed
     * @param Export $export
     */
    public function handle(Export $export)
    {

        $filename = $export->export();

        if(!$filename) {
            return false;
        }

        if($this->email) {

            $emails = [
                ['name'=> 'Hemm', 'email' => 'hemmit.kerrai@unilad.co.uk'],
                ['name'=> 'Kojo', 'email' => 'kojo@unilad.co.uk'],
                ['name'=> 'Russell', 'email' => 'russell@unilad.co.uk'],
            ];

            foreach ($emails as $email) {

                $user = new User();
                $user->name = $email['name'];
                $user->email = $email['email'];

                Notification::send($user, new EmailExport($filename));
            }

            Log::info('emails sent');
        }




    }
}
