<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Frontoffice\ReservationController;
use App\Http\Controllers\Frontoffice\LegalTextController;
use App\Http\Controllers\Frontoffice\SearchController;
use App\Http\Controllers\Backoffice\SuggestionController;
use App\Http\Controllers\Backoffice\AccountController;
// use App\Http\Controllers\Backoffice\Studio\BookingSettingController;
// use App\Http\Controllers\Backoffice\Studio\CancelPolicySettingController;
// use App\Http\Controllers\Backoffice\Studio\BookingController;
use App\Http\Controllers\Backoffice\Studio\BundleController;
use App\Http\Controllers\Backoffice\Studio\BundlePhotoController;
use App\Http\Controllers\Backoffice\Studio\BundlePriceController;
use App\Http\Controllers\Backoffice\Studio\DashboardController;
use App\Http\Controllers\Backoffice\Studio\StudioPhotoController;
use App\Http\Controllers\Backoffice\Studio\AvailabilityController;
use App\Http\Controllers\Backoffice\Studio\VideoController;
use App\Http\Controllers\Backoffice\Studio\ContactController;
use App\Http\Controllers\Backoffice\Studio\CollaborationController;
use App\Http\Controllers\Backoffice\Studio\ComfortServiceController;
use App\Http\Controllers\Backoffice\Studio\PaymentMethodController;
use App\Http\Controllers\Backoffice\Studio\DescriptionController;
use App\Http\Controllers\Backoffice\Studio\LocationController;
use App\Http\Controllers\Backoffice\Studio\RuleController;
use App\Http\Controllers\Backoffice\Studio\SocialController;
use App\Http\Controllers\Backoffice\Studio\Rooms\RoomController;
use App\Http\Controllers\Backoffice\Studio\Rooms\RoomPriceController;
use App\Http\Controllers\Backoffice\Studio\Rooms\EquipmentController;
use App\Http\Controllers\Backoffice\Studio\Rooms\RoomPhotoController;
use Illuminate\Notifications\Messages\MailMessage;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Frontoffice/Home');
})->name('home');

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

Route::get('/cerca', [SearchController::class, 'index'])->name('rooms.index');
Route::get('/studio/{studio}', [SearchController::class, 'show'])->name('studio.show');
// Route::get('/prenota/sala/{room}', [ReservationController::class, 'create'])->name('reservation.create');
// Route::post('/prenota/sala/store', [ReservationController::class, 'store'])->name('reservation.store');

//testi legali
Route::get('/privacy', [LegalTextController::class, 'privacy'])->name('privacy');
Route::get('/termini-e-condizioni', [LegalTextController::class, 'tos'])->name('tos');
Route::get('/cookie', [LegalTextController::class, 'cookie'])->name('cookie');

//test layout email
Route::get('/test-email', function(){
    if (config('app.env') !== 'production') {
        $email = (new MailMessage)
            ->subject("Ti diamo il benvenuto a bordo di Musigate!")
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
                // 'ol_list' => [
                //     'option 1',
                //     'option 2',
                //     'option 3',
                //     'option 4',
                // ],
                'action_lines' => [
                    "Clicca sul pulsante in basso per confermare la tua iscrizione"
                ],
                'action_label' => "Conferma email",
                'action_url' => '#',
                'outro_lines' => [
                    "Sei pronto? Iniziamo questo viaggio insieme! 🚀"
                ]
            ]);
    
        return $email->render();
    }

    abort(404);
});


//utenti registrati
Route::middleware(['auth', 'verified'])->group(function () {

    //backoffice studio
    Route::group(['middleware' => 'check_role:studio', 'prefix' => 'gestione-studio'], function () {
        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        //gestione studio
        Route::group(['middleware' => 'check_studio_info', 'as' => 'studio.'], function(){
            //raccolta di links per sola vista mobile
            Route::get('/', function(){
                return Inertia::render('Backoffice/Studio/AppStudioLinks');
            })->name('links');

            //descrizione
            Route::get('/descrizione', [DescriptionController::class, 'edit'])->name('description.edit');
            Route::put('/descrizione', [DescriptionController::class, 'update'])->name('description.update');
            Route::post('/descrizione/logo-carica', [DescriptionController::class, 'logo_upload'])->name('logo_upload');
            Route::delete('/descrizione/logo-elimina', [DescriptionController::class, 'logo_delete'])->name('logo_delete');

            //location
            Route::get('/location', [LocationController::class, 'edit'])->name('location.edit');
            Route::put('/location', [LocationController::class, 'update'])->name('location.update');

            //disponibilità
            Route::get('/disponibilità', [AvailabilityController::class, 'index'])->name('availability.index');
            Route::get('/disponibilità/modifica/{availability}', [AvailabilityController::class, 'edit'])->name('availability.edit');
            Route::put('/disponibilità/aggiorna/{availability}', [AvailabilityController::class, 'update'])->name('availability.update');
            Route::put('/disponibilità/clona/{availability}', [AvailabilityController::class, 'clone'])->name('availability.clone');

            //foto studio
            Route::get('/foto', [StudioPhotoController::class, 'edit'])->name('photos.edit');
            Route::post('/foto/salva', [StudioPhotoController::class, 'update'])->name('photos.update');
            Route::delete('/foto/elimina', [StudioPhotoController::class, 'destroy'])->name('photos.delete');

            //video
            Route::get('/video', [VideoController::class, 'edit'])->name('videos.edit')->withoutMiddleware('check_studio_info');
            Route::put('/video', [VideoController::class, 'update'])->name('videos.update')->withoutMiddleware('check_studio_info');

            //metodi di pagamento
            Route::get('/metodi-pagamento', [PaymentMethodController::class, 'edit'])->name('payment_methods.edit');
            Route::put('/metodi-pagamento', [PaymentMethodController::class, 'update'])->name('payment_methods.update');

            //collaborazioni
            Route::resource('/collaborazioni', CollaborationController::class)->parameter('collaborazioni', 'collaboration')->withoutMiddleware('check_studio_info');

            //comfort e servizi
            Route::get('/comfort-servizi', [ComfortServiceController::class, 'edit'])->name('comforts_services.edit')->withoutMiddleware('check_studio_info');
            Route::put('/comfort-servizi', [ComfortServiceController::class, 'update'])->name('comforts_services.update')->withoutMiddleware('check_studio_info');

            //social
            Route::get('/socials', [SocialController::class, 'edit'])->name('socials.edit')->withoutMiddleware('check_studio_info');
            Route::put('/socials', [SocialController::class, 'update'])->name('socials.update')->withoutMiddleware('check_studio_info');

            //regolamento
            Route::get('/regolamento', [RuleController::class, 'edit'])->name('rules.edit')->withoutMiddleware('check_studio_info');
            Route::put('/regolamento', [RuleController::class, 'update'])->name('rules.update')->withoutMiddleware('check_studio_info');

            //contatti
            Route::get('/contatti', [ContactController::class, 'edit'])->name('contacts.edit');
            Route::put('/contatti', [ContactController::class, 'update'])->name('contacts.update');

            // //impostazioni prenotazioni
            // Route::get('/impostazioni-prenotazioni', [BookingSettingController::class, 'edit'])->name('bookings.settings.edit')->middleware('google_refresh_token');
            // Route::put('/impostazioni-prenotazioni', [BookingSettingController::class, 'update'])->name('bookings.settings.update');
    
            // //impostazioni annullamenti
            // Route::get('/impostazioni-annullamenti', [CancelPolicySettingController::class, 'edit'])->name('cancelling.settings.edit');
            // Route::put('/impostazioni-annullamenti', [CancelPolicySettingController::class, 'update'])->name('cancelling.settings.update');
    
            // //prenotazioni
            // Route::get('/prenotazioni', [BookingController::class, 'index'])->name('bookings.index');
            // Route::get('/prenotazioni/modifica/{booking_id}', [BookingController::class, 'edit'])->name('bookings.edit');
            // Route::put('/prenotazioni/update/{booking_id}', [BookingController::class, 'update'])->name('bookings.update');
        });

        //gestione sale prova
        Route::middleware('check_room_owner')->group(function(){
            Route::resource('/sale', RoomController::class)->parameter('sale', 'room');

            //tariffe sale prova
            Route::get('/sale/{room}/tariffe', [RoomPriceController::class, 'edit'])->name('sale.prices.edit');
            Route::put('/sale/{room}/tariffe', [RoomPriceController::class, 'update'])->name('sale.prices.update');

            //equipaggiamento sale prova
            Route::get('/sale/{room}/equipaggiamento', [EquipmentController::class, 'index'])->name('sale.equipment.index');
            Route::get('/sale/{room}/equipaggiamento/modifica/{category}', [EquipmentController::class, 'edit'])->name('sale.equipment.edit');
            Route::put('/sale/{room}/equipaggiamento/aggiorna/{category}', [EquipmentController::class, 'update'])->name('sale.equipment.update');

            //foto sale prova
            Route::get('/sale/{room}/foto', [RoomPhotoController::class, 'edit'])->name('sale.photos.edit');
            Route::post('/sale/{room}/foto', [RoomPhotoController::class, 'update'])->name('sale.photos.update');
            Route::delete('/sale/{room}/foto', [RoomPhotoController::class, 'destroy'])->name('sale.photos.destroy');
        });

        //gestione pacchetti
        Route::middleware('check_bundle_owner')->group(function(){
            Route::resource('/pacchetti', BundleController::class)->parameter('pacchetti', 'bundle');

            //tariffe pacchetti
            Route::get('/pacchetti/{bundle}/tariffe', [BundlePriceController::class, 'edit'])->name('pacchetti.prices.edit');
            Route::put('/pacchetti/{bundle}/tariffe', [BundlePriceController::class, 'update'])->name('pacchetti.prices.update');

            //foto pacchetti
            Route::get('/pacchetti/{bundle}/foto', [BundlePhotoController::class, 'edit'])->name('pacchetti.photo.edit');
            Route::post('/pacchetti/{bundle}/foto', [BundlePhotoController::class, 'update'])->name('pacchetti.photo.update');
            Route::delete('/pacchetti/{bundle}/foto', [BundlePhotoController::class, 'destroy'])->name('pacchetti.photo.destroy');
        });
    });
    
    //suggerimenti
    Route::get('/suggerimenti-segnalazioni', [SuggestionController::class, 'create'])->name('suggestions.create');
    Route::post('/suggerimenti-segnalazioni', [SuggestionController::class, 'store'])->name('suggestions.store');

    //gestione account
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::post('/avatar', [AccountController::class, 'avatar_upload'])->name('account.avatar_upload');
    Route::delete('/avatar', [AccountController::class, 'avatar_delete'])->name('account.avatar_delete');
});

require __DIR__.'/auth.php';
