<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpdateRecordNotification extends Notification
{
    use Queueable;

    protected $oldData;
    protected $newData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($oldData, $newData)
    {
        $this->oldData = $oldData;
        $this->newData = $newData;
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
       
         return (new MailMessage)
            ->subject('Record Update')
            ->from('noreply@barqaab.com', 'HRMS Admin')
            ->line('This Record is New Record '. $this->oldData)
            ->line('This Record is Old Record '. $this->newData)
            ->line('If you did not request for registration, no further action is required.')
            ->line('Thank you for using HRMS');
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
