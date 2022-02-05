<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Task;
use App\Models\User;
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
        User::create([
            'name'  => 'Shadhin Ahmed',
            'email' => 'a@a.com',
            'password' => bcrypt('123'),
            'thumbnail' => 'https://picsum.photos/300'
        ]);
        User::create([
            'name'  => 'Demo User',
            'email' => 'd@d.com',
            'password' => bcrypt('123'),
            'thumbnail' => 'https://picsum.photos/300'
        ]);


        Client::factory(10)->create();

        Task::factory(50)->create();

        Invoice::factory(20)->create();

    }
}
