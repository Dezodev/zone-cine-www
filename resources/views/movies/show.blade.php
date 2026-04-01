@extends('layouts.app')

@section('title', $movie->title)
@section('description', Str::limit($movie->overview, 160))
@if ($movie->backdrop_path)
  @section('og_image', 'https://image.tmdb.org/t/p/w1280' . $movie->backdrop_path)
@endif

@section('content')
  <article class="media-detail">

    {{-- Backdrop --}}
    <div class="media-detail__backdrop-wrap">
      @if ($movie->backdrop_path)
        <img
          class="media-detail__backdrop"
          src="https://image.tmdb.org/t/p/w1280{{ $movie->backdrop_path }}"
          alt="{{ $movie->title }}"
        >
      @endif
      <div class="media-detail__backdrop-overlay"></div>
    </div>

    <div class="media-detail__main">
      <div class="media-detail__layout">

        {{-- Affiche --}}
        <div class="media-detail__poster-wrap">
          @if ($movie->poster_path)
            <img
              class="media-detail__poster"
              src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}"
              alt="{{ $movie->title }}"
            >
          @else
            <div class="media-detail__poster--placeholder">
              <x-gmsi-o-movie class="h-16 w-16" />
            </div>
          @endif
        </div>

        {{-- Informations --}}
        <div class="media-detail__info">
          <h1 class="media-detail__title">{{ $movie->title }}</h1>
          @if ($movie->original_title !== $movie->title)
            <p class="media-detail__original-title">{{ $movie->original_title }}</p>
          @endif

          <div class="media-detail__meta">
            @if ($movie->release_date)
              <span>{{ $movie->release_date->format('d/m/Y') }}</span>
              <span class="media-detail__meta-separator">·</span>
            @endif
            @if ($movie->runtime)
              <span>{{ intdiv($movie->runtime, 60) }}h{{ str_pad($movie->runtime % 60, 2, '0', STR_PAD_LEFT) }}</span>
              <span class="media-detail__meta-separator">·</span>
            @endif
            @if ($movie->original_language)
              <span class="uppercase">{{ $movie->original_language }}</span>
              <span class="media-detail__meta-separator">·</span>
            @endif
            @if ($movie->vote_average > 0)
              <span class="rating-badge {{ $movie->vote_average >= 7 ? 'rating-badge--high' : ($movie->vote_average >= 5 ? 'rating-badge--mid' : 'rating-badge--low') }}">
                ★ {{ number_format($movie->vote_average, 1) }}
              </span>
              <span class="text-base-content/30 text-xs">{{ number_format($movie->vote_count) }} votes</span>
            @endif
          </div>

          @if ($movie->genres->isNotEmpty())
            <div class="media-detail__genres">
              @foreach ($movie->genres as $genre)
                <a href="{{ route('genres.movies', $genre->slug) }}" class="genre-badge">
                  {{ $genre->name }}
                </a>
              @endforeach
            </div>
          @endif

          <x-trailer-button :trailer="$movie->trailer" />

          @if ($movie->tagline)
            <p class="media-detail__tagline">« {{ $movie->tagline }} »</p>
          @endif

          @if ($movie->overview)
            <p class="media-detail__overview">{{ $movie->overview }}</p>
          @endif

          {{-- Réalisateurs --}}
          @if ($movie->directors->isNotEmpty())
            <p class="text-sm text-base-content/60">
              <span class="font-semibold text-base-content">Réalisation :</span>
              {{ $movie->directors->pluck('name')->join(', ') }}
            </p>
          @endif
        </div>
      </div>

      {{-- Casting --}}
      @if ($movie->cast->isNotEmpty())
        <div class="media-detail__section">
          <h2 class="media-detail__section-title">Casting</h2>
          <div class="cast-scroll">
            @foreach ($movie->cast->take(20) as $person)
              <x-person-card :person="$person" :role="$person->pivot->character" />
            @endforeach
          </div>
        </div>
      @endif

      {{-- Plateformes streaming --}}
      <div class="media-detail__section">
        <h2 class="media-detail__section-title">Où regarder en France</h2>
        <x-streaming-providers :providers="$movie->watchProviders" />
      </div>

    </div>
  </article>
@endsection
