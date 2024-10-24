<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    private $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
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
            ->subject("Musigate - Richiesta reset password")
            ->markdown("email.template", [
                'greeting' => "Richiesta di reset password",
                'intro_lines' => [
                    "Hai richiesto di reimpostare la tua password su Musigate. Nessun problema, succede!",
                ],
                'action_lines' => [
                    "Per completare l'operazione, clicca sul link qui sotto:"
                ],
                'action_label' => "Reset password",
                'action_url' => route('password.reset', [$this->token, 'email' => $notifiable->email]),
                'outro_lines' => [
                    "Ricorda che il link sarà valido solo per i prossimi 60 minuti.",
                    "Se non hai richiesto tu il reset, puoi tranquillamente ignorare questa email: la tua password attuale resterà valida.",
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
