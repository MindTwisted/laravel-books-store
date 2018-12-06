<?php

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
        factory(App\User::class, 1)->create();

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
