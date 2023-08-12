
@if($rating) {{-- If rating exists --}}
    @for ($i = 1; $i <= 5; $i++) {{-- generate 5 stars --}}
        <span class="text-orange-500">{{ $i <= round($rating) ? '★' : '☆' }}</span> {{-- round the available rating score and set the full star to value and empty star to no value --}}
    @endfor
@else
    No rating yet
@endif
