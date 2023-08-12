<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Define fillable fields
    protected $fillable = ['review', 'rating'];

    /* Define method to relate review to One Book */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Event handler for cache
    protected static function booted()
    {
        // In case a book Review is updated it will clear the cache (doesnt trigger if updated directly through database but work on Tinker)
        static::updated(fn(Review $review) => cache()->forget('book:' . $review->book_id));

        // In case the book Review is deleted it will clear the cache
        static::deleted(fn(Review $review) => cache()->forget('book:' . $review->book_id));

        // In case the book Review is created it will clear the cache
        static::created(fn(Review $review) => cache()->forget('book:' . $review->book_id));
    }
}
