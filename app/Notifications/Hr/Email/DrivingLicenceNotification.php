<?php

namespace App\Notifications\Hr\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DrivingLicenceNotification extends Notification
{
    use Queueable;

     protected $employee;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($employee)
    {
         $this->employee = $employee;
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
            ->subject($this->employee->first_name . ' '.$this->employee->last_name
                .' Driving Licence has been expired on '.$this->employee->hrDriving->licence_expiry??'')
            ->from('noreply@barqaab.com', 'HRMS Admin')
            ->line('Mr. '.$this->employee->first_name . ' '.$this->employee->last_name
                .' Driving Licence has been expired on '.$this->employee->hrDriving->licence_expiry??'')
            ->line('Employee No. '.$this->employee->employee_no)
            ->line($this->employee->cnic)
            ->line('Name of Project. '.$this->employee->employeeProject->name)
            ->line('This is a system generated e-mail, therefore, please dont reply to it');
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
