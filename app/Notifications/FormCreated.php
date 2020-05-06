<?php

namespace App\Notifications;

use App\FormRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class FormCreated extends Notification
{
    use Queueable;
    protected $form;

    /**
     * Create a new notification instance.
     *
     * @param FormRequest $form
     */
    public function __construct(FormRequest $form)
    {
        //
        $this->form = $form;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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

    public function toTelegram($notifiable)
    {

        return TelegramMessage::create()
            // Optional recipient user id.
            ->to(-464615878)
            // Markdown supported.
            ->content("Hello there!\nSomeone has created a form")
            // (Optional) Inline Buttons
            ->button('View Form', 'http://kiplikipli.my.id/detail/request/'.$this->form->id)
            ->button('Download Form', 'http://54.169.75.0/api/v1/export/form-request/'.$this->form->id.'/pdf');
    }
}
