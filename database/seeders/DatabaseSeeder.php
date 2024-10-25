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
            PaymentMethodSeeder::class,
            EquipmentCategorySeeder::class,
            ServiceSeeder::class,
            ComfortSeeder::class,

            // BookingSettingSeeder::class,
            // CancelPolicySettingSeeder::class,
            // BookingSeeder::class,
        ]);

        if(config('app.env') !== 'production'){
            $this->call([
                StudioSeeder::class,
                LocationSeeder::class,
                SocialSeeder::class,
                RuleSeeder::class,
                CollaborationSeeder::class,
                StudioPhotoSeeder::class,
                AvailabilitySeeder::class,
                ContactSeeder::class,

                PaymentMethodStudioSeeder::class,
                StudioComfortServiceSeeder::class,

                RoomSeeder::class,
                EquipmentSeeder::class,
                VideoSeeder::class,
                RoomPhotoSeeder::class,

                BundleSeeder::class,
                BundlePriceSeeder::class,
            ]);
        }
    }
}
