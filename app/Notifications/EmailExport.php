<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EmailExport extends Notification
{
    use Queueable;

    protected $filename, $user;

    /**
     * EmailExport constructor.
     * @param $filename
     */
    public function __construct($filename, $user)
    {
        $this->user = $user;

        $this->filename = $filename;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $link = storage_path() . "/app/exports/" . $this->filename;

        return (new MailMessage)
            ->subject('Insights Report - '. date('d-m-Y'))
            ->from('insights@unilad.co.uk', 'Insights')
            ->greeting('Hello '. $this->user->name)
            ->line('Here is the latest insights from FB and GA.')
            ->action('Click Here for Pretty Pictures', 'https://insights.uniladgroup.com/overview/1')
            ->attach($link,['as' => $this->filename, 'mime' => 'text/csv',])
            ->line('With Love,')
            ->salutation('The Insights Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
