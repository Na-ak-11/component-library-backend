<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        User::create([
            'firstname'=>'nahom',
            'lastname'=>'saketa',
            'password'=>'abc123',
            'email'=>'nahom@mail.com',
            'github'=>'github.com',
            'linkedin'=>'linkedin.com'
            
        ]);

        User::create([
            'firstname'=>'jo',
            'lastname'=>'mojo',
            'password'=>'abc123',
            'email'=>'jo@mail.com',
            'github'=>'github.com/jo',
            'linkedin'=>'linkedin.com/jo',
            
        ]);
        User::create([
            'firstname'=>'ed',
            'lastname'=>'work',
            'password'=>'abc123',
            'email'=>'ed@mail.com',
            'github'=>'github.com/ed',
            'linkedin'=>'linkedin.com/ed'
            
        ]);
    }
}
