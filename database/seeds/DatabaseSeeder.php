<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

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
            'name' => 'John Walker',
            'email' => 'john@example.com',
            'password' => Hash::make('secret'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Michael Smith',
            'email' => 'smith@example.com',
            'password' => Hash::make('secret')
        ]);

        factory(App\Book::class, 20)->create()->each(function ($book) {
            $book->authors()->save(factory(App\Author::class)->make());
            $book->authors()->save(factory(App\Author::class)->make());
            
            $book->genres()->save(factory(App\Genre::class)->make());
            $book->genres()->save(factory(App\Genre::class)->make());
            $book->genres()->save(factory(App\Genre::class)->make());
        });

        factory(App\PaymentType::class, 5)->create();
    }
}
