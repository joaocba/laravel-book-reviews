<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        /* Seed with books and reviews but manipulate the results with the factory methods */
        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30); /* number of reviews to generate for each book */

            Review::factory()->count($numReviews)
                ->good() /* set ratings to good() */
                ->for($book) /* set id of book */
                ->create();
        });

        /* Seed with books and reviews but manipulate the results with the factory methods */
        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30); /* number of reviews to generate for each book */

            Review::factory()->count($numReviews)
                ->average() /* set ratings to average() */
                ->for($book) /* set id of book */
                ->create();
        });

        /* Seed with books and reviews but manipulate the results with the factory methods */
        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(5, 30); /* number of reviews to generate for each book */

            Review::factory()->count($numReviews)
                ->bad() /* set ratings to bad() */
                ->for($book) /* set id of book */
                ->create();
        });
    }
}
