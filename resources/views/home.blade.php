@extends('layouts.app')


@section('content')

  {{-- Hero : film populaire --}}
  @if ($nowPlaying->isNotEmpty())
    @php $featured = $nowPlaying->first(); @endphp
    <section class="page-hero">
      @if ($featured->backdrop_path)
        <img
          class="page-hero__backdrop"
          src="https://image.tmdb.org/t/p/original{{ $featured->backdrop_path }}"
          alt="{{ $featured->title }}"
        >
      @endif
      <div class="page-hero__overlay"></div>
      <div class="page-hero__content">
        <span class="page-hero__label">Populaire</span>
        <h1 class="page-hero__title">{{ $featured->title }}</h1>
        <div class="page-hero__meta">
          @if ($featured->release_date)
            <span>{{ $featured->release_date->format('Y') }}</span>
            <span class="page-hero__meta-separator">·</span>
          @endif
          @if ($featured->runtime)
            <span>{{ intdiv($featured->runtime, 60) }}h{{ $featured->runtime % 60 }}min</span>
            <span class="page-hero__meta-separator">·</span>
          @endif
          @if ($featured->vote_average > 0)
            <span>★ {{ number_format($featured->vote_average, 1) }}</span>
          @endif
        </div>
        @if ($featured->overview)
          <p class="page-hero__overview">{{ $featured->overview }}</p>
        @endif
        <div class="page-hero__actions">
          <a href="{{ route('movies.show', $featured->slug) }}" class="btn btn-primary">
            Voir le film
          </a>
        </div>
      </div>
    </section>
  @endif

  {{-- Films populaires --}}
  @if ($nowPlaying->count() > 1)
    <section class="section">
      <div class="section__inner">
        <div class="section__header">
          <h2 class="section__title">Films populaires</h2>
          <a href="{{ route('movies.index') }}" class="section__link">Voir tout →</a>
        </div>
        <div class="media-scroll">
          @foreach ($nowPlaying->skip(1) as $movie)
            <x-media-card :media="$movie" />
          @endforeach
        </div>
      </div>
    </section>
  @endif

  {{-- Séries populaires --}}
  @if ($popularShows->isNotEmpty())
    <section class="section">
      <div class="section__inner">
        <div class="section__header">
          <h2 class="section__title">Séries populaires</h2>
          <a href="{{ route('tv.index') }}" class="section__link">Voir tout →</a>
        </div>
        <div class="media-scroll">
          @foreach ($popularShows as $show)
            <x-media-card :media="$show" />
          @endforeach
        </div>
      </div>
    </section>
  @endif

@endsection
