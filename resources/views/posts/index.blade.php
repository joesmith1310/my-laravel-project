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
                By <a href="/authors/{{ $post->author->username }}">{{ $post->author->name }}</a> in <a href="/categories/{{ $post->category->slug }}">{{ $post->category->name }}</a>
            </p>
            <div>
                {{ $post->excerpt }}
            </div>
        </article>
    @endforeach

@endsection

<!-- For components do: -->

<x-layoutcomponent>

    @include ('posts._header')

    <main class="max-w-6xl mx-auto mt-6 lg:mt-20 space-y-6">
        @if ($posts->count())
            <x-post-grid :posts="$posts"/>
            {{ $posts->links() }}
        @else
            <p class="text-center">No posts yet. Please come back later.</p>
        @endif
    </main>

</x-layoutcomponent>-->