<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            WeekdaySeeder::class,
            StatusSeeder::class,
            PaymentMethodSeeder::class,
            RoomTypeSeeder::class,
            EquipmentCategorySeeder::class,
            
            StudioSeeder::class,
            LocationSeeder::class,
            SocialSeeder::class,
            RuleSeeder::class,
            CollaborationSeeder::class,
            StudioPhotoSeeder::class,

            // TimebandCategorySeeder::class,
            // TimebandSeeder::class,
            // HolidaySeeder::class,
            // VacationSeeder::class,
            // AvailabilitySeeder::class,
            ServiceSeeder::class,
            ComfortSeeder::class,
            PaymentMethodStudioSeeder::class,
            StudioComfortSeeder::class,
            StudioServiceSeeder::class,
            
            RoomSeeder::class,
            EquipmentSeeder::class,
            // PriceSeeder::class,
            StudioVideoSeeder::class,
            RoomPhotoSeeder::class,
            ContactSeeder::class,
            AvailabilitySeeder::class,
            BookingSettingSeeder::class,
            CancelPolicySettingSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
