@extends ('layout')

@section ('banner')
    <h1>My Blog</h1>
@endsection

@section ('content') <!-- The section below is whats then loaded at yield('content') -->
    
    <?php foreach ($posts as $post) : ?>
        <article>
            <h1>
                <a href="/posts/<?= $post->slug; ?>">
                    <?= $post->title; ?> 
                </a>
            </h1>
            <div>
                {{ $post->excerpt }} <!-- Blade alternative -->
            </div>
        </article>
    <?php endforeach; ?>
    <h2>The same using a Blade directive:</h2>
    @foreach ($posts as $post) <!-- Blade directive -->
        @if ($loop->odd)
            <h3>This post has an even index ({{$loop->index}})</h3>
        @endif
        <article>
            <h1>
                <a href="/posts/{{ $post->slug }}">
                    {{ $post->title }} 
                </a>
            </h1>
            <p>
                <a href="/categories/{{ $post->category->slug }}">{{ $post->category->name }}</a>
            </p>
            <div>
                {{ $post->excerpt }}
            </div>
        </article>
    @endforeach

@endsection

<!-- For components do:

<x-layoutcomponent>

    *content here*

</x-layoutcomponent>-->