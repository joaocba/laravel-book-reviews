@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <h1 class="mb-2 text-2xl">{{ $book->title }}</h1>

        <div class="book-info">
            <div class="book-author mb-4 text-lg font-semibold">by {{ $book->author }}</div>
            <div class="book-rating flex items-center">
                <div class="mr-2 text-sm font-medium text-slate-700">

                    <x-star-rating :rating="$book->reviews_avg_rating" /> {{-- This is to import a component --}}
                    ({{ number_format($book->reviews_avg_rating, 1) }})
                </div>
                <span class="book-review-count text-sm text-gray-500">
                    out of {{ $book->reviews_count }} {{ Str::plural('review', $book->reviews_count) }}
                </span>
            </div>
        </div>
    </div>

    <div class="flex place-content-between items-center mb-4">
        <h2 class="text-xl font-semibold">Latest Reviews</h2>
        <div class="flex space-x-2">

            {{-- Add reviews to book --}}
            <a href="{{ route('books.reviews.create', $book) }}" class="btn">Add a Review</a>

            {{-- Return to book list --}}
            <a href="{{ route('books.index') }}" class="btn h-10">Return to Book List</a>

            {{-- Delete book --}}
            <form action="{{ route('books.destroy', ['book' => $book]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn h-10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    {{-- List of latest reviews --}}
    <div>
        <ul>
            @forelse ($reviews as $review)
                <li class="book-item mb-4">
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <div class="font-semibold">
                                {{-- {{ $review->rating }} --}}
                                <x-star-rating :rating="$review->rating" />
                            </div>
                            <div class="book-review-count">
                                {{ $review->created_at->format('M j, Y') }}</div>
                        </div>
                        <p class="text-gray-700">{{ $review->review }}</p>
                    </div>
                </li>
            @empty
                {{-- If no reviews available --}}
                <li class="mb-4">
                    <div class="empty-book-item">
                        <p class="empty-text text-lg font-semibold">No reviews yet</p>
                    </div>
                </li>
            @endforelse
        </ul>

        {{-- Create pagination for books --}}
        @if ($reviews->count())
            <nav class="mt-4">
                {{-- This will show pagination --}}
                {{ $reviews->links() }}
                {{-- {{ $reviews->appends(['page' => $reviews->currentPage()])->links() }} --}}
            </nav>
        @endif
    </div>
@endsection
