<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\Submission;
use Illuminate\Database\Seeder;
use App\Models\Farmer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Submission::create([
            'region' => 'Arusha',
            'village' => 'Maji ya Chai',
            'crop_type' => 'Maize',
            'pest_detected' => true,
            'rainfall_status' => 'low',
            'crop_condition' => 'poor',
            'market_price' => 42.50,
            'notes' => 'Dry conditions and pest damage reported.',
        ]);

        Submission::create([
            'region' => 'Kilimanjaro',
            'village' => 'Moshi Rural',
            'crop_type' => 'Beans',
            'pest_detected' => false,
            'rainfall_status' => 'normal',
            'crop_condition' => 'average',
            'market_price' => 35.00,
            'notes' => 'Moderate crop growth.',
        ]);

        Submission::create([
            'region' => 'Dodoma',
            'village' => 'Chamwino',
            'crop_type' => 'Sorghum',
            'pest_detected' => false,
            'rainfall_status' => 'low',
            'crop_condition' => 'poor',
            'market_price' => 28.00,
            'notes' => 'Drought affecting yield.',
        ]);

        Alert::create([
            'region' => 'Arusha',
            'alert_type' => 'pest',
            'message' => 'Fall armyworm detected nearby. Check maize crops today.',
            'status' => 'sent',
        ]);

        Alert::create([
            'region' => 'Kilimanjaro',
            'alert_type' => 'weather',
            'message' => 'Rain expected tomorrow. Delay planting by 2 days.',
            'status' => 'pending',
        ]);

        Alert::create([
            'region' => 'Dodoma',
            'alert_type' => 'weather',
            'message' => 'Low rainfall expected this week. Consider irrigation if possible.',
            'status' => 'pending',
        ]);

        Farmer::create([
            'full_name' => 'Musa Hassan',
            'phone_number' => '+255700111222',
            'region' => 'Arusha',
            'village' => 'Maji ya Chai',
            'preferred_language' => 'Swahili',
            'wants_sms' => true,
        ]);

        Farmer::create([
            'full_name' => 'Amina Juma',
            'phone_number' => '+255700333444',
            'region' => 'Arusha',
            'village' => 'Moshi Rural',
            'preferred_language' => 'Swahili',
            'wants_sms' => true,
        ]);

        Farmer::create([
            'full_name' => 'John Peter',
            'phone_number' => '+255700555666',
            'region' => 'Dodoma',
            'village' => 'Chamwino',
            'preferred_language' => 'English',
            'wants_sms' => true,
        ]);
    }
}
