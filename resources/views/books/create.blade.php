@extends('layouts.app')

@section('content')
    <h1 class="mb-6 mt-4 text-xl">Add New Book <span class="font-semibold">{{ $book->title }}</span></h1>

    <form method="POST" action="{{ route('books.store', $book) }}">
        @csrf
        <div class="mb-4 mt-2">
            <label for="title">Title</label>
            <input text="text" name="title" id="title"
            @class(['input mt-2', 'border-red-500' => $errors->has('title')])
             />

            <!-- Show error message related to field -->
            @error('title')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-8 mt-2">
            <label for="author">Author</label>
            <input text="text" name="author" id="author"
            @class(['input mt-2', 'border-red-500' => $errors->has('author')])
             />

            <!-- Show error message related to field -->
            @error('author')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="my-4 flex items-center space-x-2">
            <button type="submit" class="btn h-10">Add Book</button>
            <a href="{{ route('books.index') }}" class="btn h-10">Cancel</a>
        </div>
    </form>
@endsection
