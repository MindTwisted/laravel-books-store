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

        $books = [];
        $authors = [];
        $genres = [];

        for ($i = 0; $i < 20; $i++)
        {
            $books[] = factory(App\Book::class)->create();
        }

        for ($i = 0; $i < 30; $i++)
        {
            $authors[] = factory(App\Author::class)->create();
        }

        for ($i = 0; $i < 40; $i++)
        {
            $genres[] = factory(App\Genre::class)->create();
        }
        
        foreach($books as $book)
        {
            $book->authors()->save($authors[array_rand($authors)]);
            $book->authors()->save($authors[array_rand($authors)]);

            $book->genres()->save($genres[array_rand($genres)]);
            $book->genres()->save($genres[array_rand($genres)]);
            $book->genres()->save($genres[array_rand($genres)]);

            $book->save();
        }

        factory(App\PaymentType::class, 5)->create();
    }
}
