<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontoffice\ReservationController;
use App\Http\Controllers\Frontoffice\LegalTextController;
use App\Http\Controllers\Frontoffice\SearchController;
use App\Http\Controllers\Backoffice\SuggestionController;
use App\Http\Controllers\Backoffice\AccountController;
use App\Http\Controllers\Backoffice\Studio\TimebandController;
use App\Http\Controllers\Backoffice\Studio\BookingSettingController;
use App\Http\Controllers\Backoffice\Studio\CancelPolicySettingController;
use App\Http\Controllers\Backoffice\Studio\ServiceController;
use App\Http\Controllers\Backoffice\Studio\BookingController;
use App\Http\Controllers\Backoffice\Studio\DashboardController;
use App\Http\Controllers\Backoffice\Studio\StudioPhotoController;
use App\Http\Controllers\Backoffice\Studio\WeeklyAvailabilityController;
use App\Http\Controllers\Backoffice\Studio\VideoController;
use App\Http\Controllers\Backoffice\Studio\ContactController;
use App\Http\Controllers\Backoffice\Studio\CollaborationController;
use App\Http\Controllers\Backoffice\Studio\ComfortController;
use App\Http\Controllers\Backoffice\Studio\PaymentMethodController;
use App\Http\Controllers\Backoffice\Studio\DescriptionController;
use App\Http\Controllers\Backoffice\Studio\LocationController;
use App\Http\Controllers\Backoffice\Studio\RuleController;
use App\Http\Controllers\Backoffice\Studio\SocialController;
use App\Http\Controllers\Backoffice\Studio\Rooms\RoomController;
use App\Http\Controllers\Backoffice\Studio\Rooms\RoomPriceController;
use App\Http\Controllers\Backoffice\Studio\Rooms\EquipmentController;
use App\Http\Controllers\Backoffice\Studio\Rooms\RoomPhotoController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Frontoffice/Home');
})->name('home');

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

Route::get('/cerca', [SearchController::class, 'index'])->name('rooms.index');
Route::get('/studio/{studio}', [SearchController::class, 'show'])->name('studio.show');
Route::get('/prenota/{room}', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/prenota/store', [ReservationController::class, 'store'])->name('reservation.store');

//testi legali
Route::get('/privacy', [LegalTextController::class, 'privacy'])->name('privacy');
Route::get('/termini-e-condizioni', [LegalTextController::class, 'tos'])->name('tos');
Route::get('/cookie', [LegalTextController::class, 'cookie'])->name('cookie');


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
            Route::get('/disponibilità', [WeeklyAvailabilityController::class, 'edit'])->name('availability.edit');
            Route::put('/disponibilità', [WeeklyAvailabilityController::class, 'update'])->name('availability.update');

            //fasce orarie
            Route::get('/fasce-orarie', [TimebandController::class, 'edit'])->name('timebands.edit');
            Route::put('/fasce-orarie', [TimebandController::class, 'update'])->name('timebands.update');

            //foto studio
            Route::get('/foto', [StudioPhotoController::class, 'edit'])->name('photos.edit');
            Route::post('/foto/salva', [StudioPhotoController::class, 'update'])->name('photos.update');
            Route::delete('/foto/elimina', [StudioPhotoController::class, 'destroy'])->name('photos.delete');

            //video
            Route::get('/video', [VideoController::class, 'edit'])->name('videos.edit');
            Route::put('/video', [VideoController::class, 'update'])->name('videos.update');

            //metodi di pagamento
            Route::get('/metodi-pagamento', [PaymentMethodController::class, 'edit'])->name('payment_methods.edit');
            Route::put('/metodi-pagamento', [PaymentMethodController::class, 'update'])->name('payment_methods.update');

            //collaborazioni
            Route::get('/collaborazioni', [CollaborationController::class, 'index'])->name('collaborations.index');
            Route::post('/collaborazioni', [CollaborationController::class, 'store'])->name('collaborations.store');
            Route::put('/collaborazioni/{collaboration_id}', [CollaborationController::class, 'update'])->name('collaborations.update');
            Route::delete('/collaborazioni/{collaboration_id}', [CollaborationController::class, 'delete'])->name('collaborations.delete');

            //comfort
            Route::get('/comfort', [ComfortController::class, 'edit'])->name('comforts.edit');
            Route::put('/comfort', [ComfortController::class, 'update'])->name('comforts.update');

            //social
            Route::get('/socials', [SocialController::class, 'edit'])->name('socials.edit');
            Route::put('/socials', [SocialController::class, 'update'])->name('socials.update');

            //regolamento
            Route::get('/regolamento', [RuleController::class, 'edit'])->name('rules.edit');
            Route::put('/regolamento', [RuleController::class, 'update'])->name('rules.update');

            //contatti
            Route::get('/contatti', [ContactController::class, 'edit'])->name('contacts.edit');
            Route::put('/contatti', [ContactController::class, 'update'])->name('contacts.update');
        });

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

        //gestione sale prova
        Route::resource('/sale-prova', RoomController::class)->parameter('sale-prova', 'room_id');

        //tariffe sale prova
        Route::get('/sale/tariffe/{room}', [RoomPriceController::class, 'edit'])->name('sale-prova.prices.edit');
        Route::put('/sale/tariffe/{room}', [RoomPriceController::class, 'update'])->name('sale-prova.prices.update');

        //equipaggiamento sale prova
        Route::get('/sale/equipaggiamento/{room}', [EquipmentController::class, 'edit'])->name('sale-prova.equipment.edit');
        Route::put('/sale/equipaggiamento/{room}', [EquipmentController::class, 'update'])->name('sale-prova.equipment.update');

        //foto sale prova
        Route::get('/sale/foto/{room}', [RoomPhotoController::class, 'edit'])->name('sale-prova.photos.edit');
        Route::post('/sale/foto/{room}', [RoomPhotoController::class, 'update'])->name('sale-prova.photos.update');
        Route::delete('/sale/foto/{room}', [RoomPhotoController::class, 'destroy'])->name('sale-prova.photos.destroy');

        //gestione servizi
        Route::resource('/servizi', ServiceController::class)->parameter('servizi', 'service_id');

        //suggerimenti
        Route::get('/suggerimenti-segnalazioni', [SuggestionController::class, 'create'])->name('suggestions.create');
        Route::post('/suggerimenti-segnalazioni', [SuggestionController::class, 'store'])->name('suggestions.store');
    });

    //gestione account
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::post('/avatar', [AccountController::class, 'avatar_upload'])->name('account.avatar_upload');
    Route::delete('/avatar', [AccountController::class, 'avatar_delete'])->name('account.avatar_delete');
});

require __DIR__.'/auth.php';
