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
            PaymentMethodSeeder::class,
            EquipmentCategorySeeder::class,
            
            StudioSeeder::class,
            LocationSeeder::class,
            SocialSeeder::class,
            RuleSeeder::class,
            CollaborationSeeder::class,
            StudioPhotoSeeder::class,
            AvailabilitySeeder::class,
            ContactSeeder::class,

            ServiceSeeder::class,
            ComfortSeeder::class,
            PaymentMethodStudioSeeder::class,
            StudioComfortSeeder::class,
            
            RoomSeeder::class,
            EquipmentSeeder::class,
            StudioVideoSeeder::class,
            RoomPhotoSeeder::class,

            BookingSettingSeeder::class,
            CancelPolicySettingSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
