@extends('layouts.main')

@section('content')

<div class="single-page-sec">    
    <div class="mb-5 single-page-inner">
        @if($post->thumbnail)
            <div class="blog-single-image"><img src="{{ storage_url($post->thumbnail) }}" alt=""></div>
        @endif

        <div class="blog-title-sec">
                @php $date = \Carbon\Carbon::parse($post->created_at); @endphp
                <div class="date">
                    {{ $date->format('M') }}<br>
                    <span>{{ $date->format('d') }}</span>
                </div>
                <h2>{{ $post->title }}</h2>
        </div>
        <p>{{ $post->summary }}</p>

        {!! $post->content !!}
    </div>
</div>

@endsection