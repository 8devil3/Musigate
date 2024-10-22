<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

//TODO: abilitare ShouldQueue
class DeleteAccountNotification extends Notification //implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            ->subject("Musigate - Ci dispiace vederti andare via")
            ->markdown("email.template", [
                'greeting' => "Ci dispiace vederti andare via 😔",
                'intro_lines' => [
                    "Abbiamo ricevuto la tua richiesta di eliminare l'account su Musigate e ci dispiace davvero vederti andare. Sappiamo che a volte le esigenze cambiano, ma speriamo che tu abbia trovato utile il tempo passato con noi!",
                    "Ricorda che sei sempre il benvenuto a tornare su Musigate! Se deciderai di ricominciare o semplicemente di dare un’occhiata, le nostre porte sono sempre aperte. 🎶",
                    "Grazie ancora per aver fatto parte della nostra community."
                ],
            ]);
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
