<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class ReviewController extends Controller
{

    // This is will apply the middleware Throttle (rate limit) to reviews group
    public function __construct()
    {
        $this->middleware('throttle:reviews') // reviews is a custom defined rate limiter on App\Http\Providers\RouteServiceProvider.php
            ->only(['store']); // only apply the middleware to route 'store', in this case the create new review that is limited to 3 per hour
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Book $book)
    {
        // Return to book which the review is being created at
        return view('books.reviews.create', ['book' => $book]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Book $book)
    {
        // Valiate the new review data
        $data = $request->validate([
            'review' => 'required|min:15',
            'rating' => 'required|min:1|max:5|integer'
        ]);

        // Create and store review onto database
        $book->reviews()->create($data);

        // Redirect to same page of book
        return redirect()->route('books.show', $book)->with('success', 'Review added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
