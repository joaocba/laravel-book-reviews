@extends('layouts.app')

@section('content')
    <div class="flex place-content-between">
        <h1 class="mb-10 text-2xl">List of Books</h1>
        <a href="{{ route('books.create') }}" class="btn">Add New Book</a>
    </div>

    {{-- Search field to search for book titles --}}
    <form method="GET" action="{{ route('books.index') }}" class="mb-4 flex items-center space-x-2">
        <input class="input h-10" type="text" name="title" placeholder="Search by title" value="{{ request('title') }}" /> {{-- when it does a request it will search for book titles as defined on BookController.php --}}
        <input type="hidden" name="filter" value="{{ request('filter') }}" /> {{-- allow to increment the filter choice to the GET request on URL --}}
        <button type="submit" class="btn h-10">Search</button>
        <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
    </form>

    {{-- Filters for books --}}
    <div class="filter-container mb-4 flex">

        {{-- Define available filters --}}
        @php
         $filters = [
            '' => 'Latest',
            'popular_last_month' => 'Popular Last Month',
            'popular_last_6months' => 'Popular Last 6 Months',
            'highest_rated_last_month' => 'Highest Rated Last Month',
            'highest_rated_last_6months' => 'Highest Rated Last 6 Months'
         ]
        @endphp

        {{-- Show labels of filters --}}
        @foreach ($filters as $key => $label)
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}" {{-- this allow to keep the input on search field and jump on each filter tab --}}
                class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item hover:bg-gray-200' }}"> {{-- Apply style to active filters --}}
                {{ $label }}
            </a>
        @endforeach

    </div>

    {{-- Show list of books --}}
    <ul>
        @forelse ($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <span class="book-author">by {{ $book->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                {{-- {{ number_format($book->reviews_avg_rating, 1) }} --}}
                                <x-star-rating :rating="$book->reviews_avg_rating" />
                            </div>
                            <div class="book-review-count">
                                out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }} {{-- if a single review it show text 'review' else it shows the plurar of it --}}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>

    {{-- Create pagination for books --}}
    @if ($books->count())
        <nav class="mt-4">
            {{-- This will show pagination --}}
            {{-- {{ $books->links() }} --}}

            {{-- This will show pagination and preserve the inputed search field text --}}
            {{-- {{ $books->appends(['title' => request('title')])->links() }} --}}

            {{-- This will show pagination and preserve the inputed search field text + filter applied --}}
            {{ $books->appends(['title' => request('title'), 'filter' => request('filter')])->links() }}
        </nav>
    @endif
@endsection
