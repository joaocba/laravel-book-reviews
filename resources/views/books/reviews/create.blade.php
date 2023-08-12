@extends('layouts.app')

@section('content')
    <h1 class="mb-6 mt-4 text-xl">Add Review for book <span class="font-semibold">{{ $book->title }}</span></h1>

    <form method="POST" action="{{ route('books.reviews.store', $book) }}">
        @csrf
        <div class="mb-4 mt-2">
            <label for="review">Review</label>
            <textarea name="review" id="review"
            @class(['input mt-2', 'border-red-500' => $errors->has('review')])
            placeholder="Write a detailed review about the book" rows="10"></textarea>

            <!-- Show error message related to field -->
            @error('review')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-8 mt-2">
            <label for="rating">Rating</label>
            <select name="rating" id="rating"
            @class(['input mt-2', 'border-red-500' => $errors->has('rating')])>
                <option value="">Select a Rating</option>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>

            <!-- Show error message related to field -->
            @error('rating')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="my-4 flex items-center space-x-2">
            <button type="submit" class="btn h-10">Add Review</button>
            <a href="{{ route('books.show', $book) }}" class="btn h-10">Cancel</a>
        </div>
    </form>
@endsection
