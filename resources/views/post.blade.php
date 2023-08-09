@extends('layout')

@section('content')
    <article>
        <h1><?= $post->title; ?></h1>
        <p>
            <a href="/categories/{{ $post->category->id }}">{{ $post->category->name }}</a>
        </p>
        <div>
            {!! $post->body !!} <!-- Need to use !! to avoid escaping HTML -->
        </div>
    </article>
    <a href="/">Go Back</a>
@endsection