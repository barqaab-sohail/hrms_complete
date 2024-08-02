<?php

namespace App\Notifications\Hr\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContractExpiryNotification extends Notification
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
        
        $toDay = \Carbon\Carbon::now()->format('Y-m-d');
        $nextTenDays = \Carbon\Carbon::now()->addDays(10)->format('Y-m-d');
        
        if($this->employee->employeeAppointment->expiry_date>$toDay){
            return (new MailMessage)
                ->subject($this->employee->first_name . ' '.$this->employee->last_name
                    .' Contract Agreement will be expired on '.$this->employee->employeeAppointment->expiry_date??'')
                ->from('noreply@barqaab.com', 'HRMS')
                ->line('Mr. '.$this->employee->first_name . ' '.$this->employee->last_name
                    .' Contract Agreement will be expired on '.$this->employee->employeeAppointment->expiry_date??'')
                ->line('Employee No. '.$this->employee->employee_no)
                ->line('Name of Project. '.$this->employee->employeeProject->name)
                ->line('This is a system generated e-mail, therefore, please dont reply to it');
        } else{
            return (new MailMessage)
                ->subject($this->employee->first_name . ' '.$this->employee->last_name
                    .' Contract Agreement has been expired on '.$this->employee->employeeAppointment->expiry_date??'')
                ->from('noreply@barqaab.com', 'HRMS')
                ->line('Mr. '.$this->employee->first_name . ' '.$this->employee->last_name
                    .' Contract Agreement has been expired on '.$this->employee->employeeAppointment->expiry_date??'')
                ->line('Employee No. '.$this->employee->employee_no)
                ->line('Name of Project. '.$this->employee->employeeProject->name)
                ->line('This is a system generated e-mail, therefore, please dont reply to it');
        }
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
