<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Room\Room;
use App\Models\Studio\Bundle;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        Gate::define('crud-room', function(User $user, Room $room){
            return $user->id === $room->studio->user_id;
        });

        Gate::define('crud-bundle', function(User $user, Bundle $bundle){
            return $user->id === $bundle->studio->user_id;
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject("Musigate - Ti diamo il benvenuto a bordo!")
                ->markdown("email.template", [
                    'greeting' => "Ti diamo il benvenuto a bordo! 🎉",
                    'intro_lines' => [
                        "Siamo super felici di averti con noi e non vediamo l'ora di aiutarti a gestire al meglio il tuo Studio. Con Musigate, potrai mostrare le tue Sale, pacchetti e servizi a chi cerca spazi creativi come il tuo.",
                        "Ecco cosa puoi fare subito:",
                    ],
                    'ul_list' => [
                        "Personalizza la tua pagina con foto, descrizione e dettagli importanti",
                        "Imposta i giorni e gli orari di disponibilità",
                        "Crea le Sale e i pacchetti impostando descrizione, tariffe, equipaggiamento e foto",
                    ],
                    'action_lines' => [
                        "Clicca sul pulsante in basso per confermare la tua iscrizione"
                    ],
                    'action_label' => "Conferma email",
                    'action_url' => $url,
                    'outro_lines' => [
                        "Sei pronto? Iniziamo questo viaggio insieme! 🚀"
                    ]
                ]);
        });
    }
}
