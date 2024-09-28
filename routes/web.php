<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontoffice\ReservationController;
use App\Http\Controllers\Backoffice\SuggestionController;
use App\Http\Controllers\Frontoffice\LegalTextController;
use App\Http\Controllers\Frontoffice\StudioController;
use App\Http\Controllers\Backoffice\AccountController;
use App\Http\Controllers\Backoffice\Studio\BookingSettingController;
use App\Http\Controllers\Backoffice\Studio\CancelPolicySettingController;
use App\Http\Controllers\Backoffice\Studio\ServiceController;
use App\Http\Controllers\Backoffice\Studio\BookingController;
use App\Http\Controllers\Backoffice\Studio\DashboardController;
use App\Http\Controllers\Backoffice\Studio\StudioPhotoController;
use App\Http\Controllers\Backoffice\Studio\WeeklyAvailabilityController;
use App\Http\Controllers\Backoffice\Studio\StudioVideoController;
use App\Http\Controllers\Backoffice\Studio\ContactController;
use App\Http\Controllers\Backoffice\Studio\CollaborationController;
use App\Http\Controllers\Backoffice\Studio\ComfortController;
use App\Http\Controllers\Backoffice\Studio\PaymentMethodController;
use App\Http\Controllers\Backoffice\Studio\DescriptionController;
use App\Http\Controllers\Backoffice\Studio\LocationController;
use App\Http\Controllers\Backoffice\Studio\RuleController;
use App\Http\Controllers\Backoffice\Studio\SocialController;
use App\Http\Controllers\Backoffice\Studio\Rooms\RoomController;
use App\Http\Controllers\Backoffice\Studio\Rooms\EquipmentController;
use App\Http\Controllers\Backoffice\Studio\Rooms\RoomPhotoController;
use App\Http\Controllers\Backoffice\Studio\Rooms\DescriptionController as RoomDescriptionController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Frontoffice/Home');
})->name('home');

Route::get('/offline', function () {
    return view('offline');
})->name('offline');

Route::get('/cerca', [StudioController::class, 'index'])->name('studio.index');
Route::get('/studio/{studio}', [StudioController::class, 'show'])->name('studio.show');
Route::get('/prenota/{room}', [ReservationController::class, 'create'])->name('reservation.create');
Route::post('/prenota/store/{room}', [ReservationController::class, 'store'])->name('reservation.store');

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

            Route::get('/descrizione', [DescriptionController::class, 'edit'])->name('description.edit');
            Route::put('/descrizione', [DescriptionController::class, 'update'])->name('description.update');
            Route::put('/descrizione/logo-carica', [DescriptionController::class, 'logo_upload'])->name('description.logo_upload');
            Route::delete('/descrizione/logo-elimina', [DescriptionController::class, 'logo_delete'])->name('description.logo_delete');
            
            Route::get('/location', [LocationController::class, 'edit'])->name('location.edit');
            Route::put('/location', [LocationController::class, 'update'])->name('location.update');
    
            Route::get('/foto', [StudioPhotoController::class, 'edit'])->name('photos.edit');
            Route::put('/foto', [StudioPhotoController::class, 'update'])->name('photos.update');
            Route::put('/foto/in-evidenza/{photo}', [StudioPhotoController::class, 'featured'])->name('photos.featured');
            Route::delete('/foto', [StudioPhotoController::class, 'delete'])->name('photos.delete');
            
            Route::get('/video', [StudioVideoController::class, 'edit'])->name('videos.edit');
            Route::put('/video', [StudioVideoController::class, 'update'])->name('videos.update');
            
            Route::get('/metodi-pagamento', [PaymentMethodController::class, 'edit'])->name('payment_methods.edit');
            Route::put('/metodi-pagamento', [PaymentMethodController::class, 'update'])->name('payment_methods.update');
        
            Route::get('/collaborazioni', [CollaborationController::class, 'index'])->name('collaborations.index');
            Route::post('/collaborazioni', [CollaborationController::class, 'store'])->name('collaborations.store');
            Route::put('/collaborazioni/{collaboration_id}', [CollaborationController::class, 'update'])->name('collaborations.update');
            Route::delete('/collaborazioni/{collaboration_id}', [CollaborationController::class, 'delete'])->name('collaborations.delete');
    
            Route::get('/comfort', [ComfortController::class, 'edit'])->name('comforts.edit');
            Route::put('/comfort', [ComfortController::class, 'update'])->name('comforts.update');
        
            Route::get('/socials', [SocialController::class, 'edit'])->name('socials.edit');
            Route::put('/socials', [SocialController::class, 'update'])->name('socials.update');
        
            Route::get('/regolamento', [RuleController::class, 'edit'])->name('rules.edit');
            Route::put('/regolamento', [RuleController::class, 'update'])->name('rules.update');
        
            Route::get('/contatti', [ContactController::class, 'edit'])->name('contacts.edit');
            Route::put('/contatti', [ContactController::class, 'update'])->name('contacts.update');

            Route::get('/disponibilità', [WeeklyAvailabilityController::class, 'edit'])->name('availability.edit');
            Route::put('/disponibilità', [WeeklyAvailabilityController::class, 'update'])->name('availability.update');
        });

        //impostazioni prenotazioni
        Route::get('/impostazioni-prenotazioni', [BookingSettingController::class, 'edit'])->name('bookings.settings.edit')->middleware('google_refresh_token');
        Route::put('/impostazioni-prenotazioni', [BookingSettingController::class, 'update'])->name('bookings.settings.update');

        //impostazioni annullamenti
        Route::get('/impostazioni-annullamenti', [CancelPolicySettingController::class, 'edit'])->name('cancelling.settings.edit');
        Route::put('/impostazioni-annullamenti', [CancelPolicySettingController::class, 'update'])->name('cancelling.settings.update');

        //prenotazioni
        Route::get('/prenotazioni', [BookingController::class, 'index'])->name('bookings.index');
        
        //gestione sale studio
        Route::get('/sale-prova', [RoomController::class, 'index'])->name('rooms.index');
        Route::get('/sale-prova/modifica/{room}', [RoomController::class, 'edit'])->name('rooms.edit');
        Route::post('/sale-prova/nuova', [RoomController::class, 'store'])->name('rooms.store');
        
        Route::group(['middleware' => 'check_room_user_id'], function(){
            Route::delete('/sale-studio/elimina/{room}', [RoomController::class, 'delete'])->name('rooms.delete');

            Route::get('/sale-studio/descrizione/{room}', [RoomDescriptionController::class, 'edit'])->name('rooms.description.edit');
            Route::put('/sale-studio/descrizione/{room}', [RoomDescriptionController::class, 'update'])->name('rooms.description.update');
    
            Route::get('/sale-studio/equipaggiamento/{room}', [EquipmentController::class, 'edit'])->name('rooms.equipment.edit');
            Route::put('/sale-studio/equipaggiamento/{room}', [EquipmentController::class, 'update'])->name('rooms.equipment.update');
    
            Route::get('/sale-studio/foto/{room}', [RoomPhotoController::class, 'edit'])->name('rooms.photos.edit');
            Route::put('/sale-studio/foto/{room}', [RoomPhotoController::class, 'update'])->name('rooms.photos.update');
            Route::put('/sale-studio/foto/in-evidenza/{room}/{photo}', [RoomPhotoController::class, 'featured'])->name('rooms.photos.featured');
            Route::delete('/sale-studio/foto/{room}', [RoomPhotoController::class, 'delete'])->name('rooms.photos.delete');
    
            Route::put('/sale-studio/status/{room}', [RoomController::class, 'update_status'])->name('rooms.update_status');
        });

        //gestione servizi
        Route::resource('/servizi', ServiceController::class)->parameter('servizi', 'service');

        //suggerimenti
        Route::get('/suggerimenti-segnalazioni', [SuggestionController::class, 'create'])->name('suggestions.create');
        Route::post('/suggerimenti-segnalazioni', [SuggestionController::class, 'store'])->name('suggestions.store');
    });

    //gestione account
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');
    Route::put('/avatar', [AccountController::class, 'avatar_upload'])->name('account.avatar_upload');
    Route::delete('/avatar', [AccountController::class, 'avatar_delete'])->name('account.avatar_delete');
});

require __DIR__.'/auth.php';
