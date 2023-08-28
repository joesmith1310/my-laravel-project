<x-post-feature-card :post="$posts[0]" /> <!-- This is how you pass a post through to the component -->
@if ($posts->count() > 1)
    <div class="lg:grid lg:grid-cols-6">
        @foreach ($posts->skip(1) as $post)
            <x-post-card :post="$post" class="{{ $loop->iteration < 3 ? 'col-span-3' : 'col-span-2' }}"/> <!-- You can also pass through HTML attributes -->
        @endforeach
    </div>
@endif