<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get the title of book on input
        $title = $request->input('title');

        // Get the filter used on input
        $filter = $request->input('filter', '');

        // If title is not null or empty it will exec the function title() from Model Book.php
        $books = Book::when(
            $title,
            fn($query, $title) => $query->title($title) /* call the method scopeTitle() */
        );

        // Apply filters to book title for which filter was selected
        $books = match($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLast6Months(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLast6Months(),
            default => $books->latest()->withAvgRating()->withReviewsCount() /* default filter applied */
        };

        // Return the results of the query
        //$books = $books->get();

        // Cache the books
        // Return the results of the query and save them on Cache
        $cacheKey = 'books' . $filter . ':' . $title; // set the cache key and the fields it store

        // define the key associated, expire time (in seconds) and the value to be store
        //$books = /* cache()->remember($cacheKey, 3600, fn() => */ $books->get()/* ) */;

        // Paginate the results
        $books = $books->paginate(10);

        // Alternative method to test cache response
        /* $books = cache()->remember($cacheKey, 3600, function () use($books) {
            dd('Not from cache!'); // this will show in case cache key doesnt exist yet
            return $books->get();
        }); */

        return view('books.index', ['books' => $books]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Book $book)
    {
        // Return to form create new book
        return view('books.create', ['book' => $book]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the new book data
        $data = $request->validate([
            'title' => 'required|min:10',
            'author' => 'required|min:5'
        ]);

        // Create and store book in the database
        $book = Book::create($data); // Assuming your Book model uses mass assignment

        // Redirect to the show page of the newly created book
        return redirect()->route('books.show', ['book' => $book])->with('success', 'Book added successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id) // fetch the Book and reviews model on method parameters all at once
    {

        $book = Book::with([
            'reviews' => fn($query) => $query->latest()
        ])->withAvgRating()->withReviewsCount()->findOrFail($id);

        $paginatedReviews = $book->reviews()
            ->orderByDesc('created_at')
            ->paginate(5);

        // Return all fields for selected book
        return view('books.show', ['book' => $book, 'reviews' => $paginatedReviews]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully!');
    }
}
