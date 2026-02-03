<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/PlatformSeeder.php

    public function run()
    {
        $platforms = [
            [
                'name' => 'Netflix',
                'slug' => 'netflix',
                'description' => 'Watch unlimited movies and TV shows',
                'is_active' => 1,
                'status' => 'active',
                'sort_order' => 1
            ],
            [
                'name' => 'Disney+ Hotstar',
                'slug' => 'hotstar',
                'description' => 'Watch your favorite movies, shows & sports',
                'is_active' => 1,
                'status' => 'active',
                'sort_order' => 2
            ],
            [
                'name' => 'Amazon Prime Video',
                'slug' => 'amazon-prime',
                'description' => 'Unlimited ad-free streaming',
                'is_active' => 1,
                'status' => 'active',
                'sort_order' => 3
            ],
            [
                'name' => 'YouTube Premium',
                'slug' => 'youtube-premium',
                'description' => 'Ad-free videos, offline downloads',
                'is_active' => 1,
                'status' => 'active',
                'sort_order' => 4
            ],
            [
                'name' => 'SonyLIV',
                'slug' => 'sonyliv',
                'description' => 'Watch sports, shows and movies',
                'is_active' => 1,
                'status' => 'active',
                'sort_order' => 5
            ],
            [
                'name' => 'Zee5',
                'slug' => 'zee5',
                'description' => 'Entertainment for everyone',
                'is_active' => 1,
                'status' => 'active',
                'sort_order' => 6
            ]
        ];

        foreach ($platforms as $platform) {
            \App\Models\Platform::create($platform);
        }
    }
}
