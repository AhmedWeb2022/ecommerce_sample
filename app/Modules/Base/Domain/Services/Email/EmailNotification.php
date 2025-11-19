<?php

namespace App\Modules\Base\Domain\Services\Email;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailNotification extends Notification
{
    use Queueable;

    protected $otp;
    public $message;
    public $subject;
    public $fromEmail;
    public $mailer;
    /**
     * Create a new notification instance.
     */
    public function __construct($otp)
    {
        $this->message = 'use the blow code for verification process';
        $this->subject = 'Verification needed';
        $this->fromEmail = 'crazyideacompany@gmail.com';
        $this->mailer = 'smtp';
        $this->otp = $otp;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->mailer($this->mailer)
            ->from($this->fromEmail)
            ->subject($this->subject)
            ->greeting('Hello Mr :' . ' ' . $notifiable->name)
            ->line($this->message)
            ->line('Verification code is : ' . $this->otp)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
