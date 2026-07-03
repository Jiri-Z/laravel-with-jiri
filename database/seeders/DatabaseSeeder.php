<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['en', 'cs'] as $locale) {
            App::setLocale($locale);
            $this->call(CourseSeeder::class);
        }

        App::setLocale('en');
    }
}
