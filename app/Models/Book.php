<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Book extends Model
{
    use HasFactory;

    // Define fillable fields
    protected $fillable = ['title', 'author'];

    /* Define method to apply relation between books and reviews, this says a Book can have Many Reviews */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    /* Define a method to apply a query scope which find text on title field */
    public function scopeTitle(Builder $query, string $title): Builder
    {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }


    /* Method to return with filter reviews count value */
    public function scopeWithReviewsCount(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withCount([
            /* call the method dateRangeFilter within */
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to) /* fn() means function() */
        ]);
    }


    /* Method to return with filter reviews count value */
    public function scopeWithAvgRating(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to) /* fn() means function() */
        ], 'rating');
    }


    /* Method to show most popular books within a given time frame (between two a min and max date) with Reviews Count scope inside */
    public function scopePopular(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withReviewsCount()
            ->orderBy('reviews_count', 'desc');
    }


    /* Method to show highest rated books with Average Rated scope inside */
    public function scopeHighestRated(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvgRating()
            ->orderBy('reviews_avg_rating', 'desc');
    }


    /* Method to show most popular books */
     public function scopePopularOLD(Builder $query): Builder
    {
        return $query->withCount('reviews')
            ->orderBy('reviews_count', 'desc');
    }


    /* Method to show most popular books within a given time frame (between two a min and max date) */
    public function scopePopularOLD2(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount([
            'reviews' => function (Builder $q) use ($from, $to) {
                if ($from && !$to) {
                    $q->where('created_at', '>=', $from);
                } elseif (!$from && $to) {
                    $q->where('created_at', '<=', $to);
                } elseif ($from && $to) {
                    $q->whereBetween('created_at', [$from, $to]);
                }
            }
        ])
            ->orderBy('reviews_count', 'desc');
    }


    /* Method to show most popular books within a given time frame (between two a min and max date) with function recall */
    public function scopePopularOLD3(Builder $query, $from = null, $to = null): Builder
    {
        return $query->withCount([
            /* call the method dateRangeFilter within */
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to) /* fn() means function() */
        ])
            ->orderBy('reviews_count', 'desc');
    }


    /* Method to show highest rated books - OLD */
     public function scopeHighestRatedOLD(Builder $query): Builder|QueryBuilder
    {
        return $query->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }


    /* Method to show highest rated books */
    public function scopeHighestRatedOLD2(Builder $query, $from = null, $to = null): Builder|QueryBuilder
    {
        return $query->withAvg([
            'reviews' => fn(Builder $q) => $this->dateRangeFilter($q, $from, $to) /* fn() means function() */
        ], 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }


    /* Method to filter by reviews minimum count */
    public function scopeMinReviews(Builder $query, $minReviews): Builder|QueryBuilder
    {
        return $query->having('reviews_count', '>=', $minReviews);
    }


    /* Method to filter by date range - to be used inside the other methods - private on this class */
    private function dateRangeFilter(Builder $query, $from = null, $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }


    /* Define scopes for book filters */
    /* Method to filter Popular Last Month */
    public function scopePopularLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonth(), now()) /* return the most popular last month */
            ->highestRated(now()->subMonth(), now()) /* filter popular last month by highest rated */
            ->minReviews(2); /* filter by must have at least 2 reviews */
    }


    /* Method to filter Popular Last 6 Months */
    public function scopePopularLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->popular(now()->subMonth(6), now()) /* return the most popular last 6 months */
            ->highestRated(now()->subMonth(6), now()) /* filter popular last 6 months by highest rated */
            ->minReviews(5); /* filter by must have at least 5 reviews */
    }


    /* Method to filter Highest Last Month */
    public function scopeHighestRatedLastMonth(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonth(), now()) /* return highest rated last month */
            ->popular(now()->subMonth(), now()) /* filter by the most popular last month */
            ->minReviews(2); /* filter by must have at least 2 reviews */
    }


    /* Method to filter Highest Last 6 Months */
    public function scopeHighestRatedLast6Months(Builder $query): Builder|QueryBuilder
    {
        return $query->highestRated(now()->subMonth(6), now()) /* return highest rated last 6 months */
            ->popular(now()->subMonth(6), now()) /* filter by the most popular last 6 months */
            ->minReviews(5); /* filter by must have at least 5 reviews */
    }


    // Event handler for cache
    protected static function booted()
    {
        // In case a Book is updated it will clear the cache (doesnt trigger if updated directly through database but work on Tinker)
        static::updated(fn(Book $book) => cache()->forget('book:' . $book->id));

        // In case the Book is deleted it will clear the cache
        static::deleted(fn(Book $book) => cache()->forget('book:' . $book->id));
    }

}
