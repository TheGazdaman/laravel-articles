@extends('layout')

@section('content')

    <div class="articles">

        @foreach ($articles as $article)

            <div class="article">

                <div class="image">
                    <img src="/images/{{ $article->image_file }}" alt="">
                </div>

                <a href="#">
                    {{ $article->title }}
                </a>

            </div>

        @endforeach

    </div>

@endsection