<?php

namespace App\Notifications\Studio;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

//TODO: abilitare ShouldQueue
class WelcomeGoogleUserNotification extends Notification //implements ShouldQueue
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
            ->subject("Musigate - Ti diamo il benvenuto a bordo!")
            ->markdown("email.template", [
                'greeting' => "Ciao " . $notifiable->first_name . ", ti diamo il benvenuto a bordo! 🎉",
                'intro_lines' => [
                    "Siamo super felici di averti con noi e non vediamo l'ora di aiutarti a gestire al meglio il tuo Studio. Con Musigate, potrai mostrare le tue Sale, pacchetti e servizi a chi cerca spazi creativi come il tuo.",
                    "Ecco cosa puoi fare subito:",
                ],
                'ul_list' => [
                    "Personalizza la tua pagina con foto, descrizione e dettagli importanti",
                    "Imposta i giorni e gli orari di disponibilità",
                    "Crea le Sale e i pacchetti impostando descrizione, tariffe, equipaggiamento e foto",
                ],
                'outro_lines' => [
                    "Sei pronto? Iniziamo questo viaggio insieme! 🚀"
                ]
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
