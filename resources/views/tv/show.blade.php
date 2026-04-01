@extends('layouts.app')

@section('title', $show->name)
@section('description', Str::limit($show->overview, 160))
@if ($show->backdrop_path)
  @section('og_image', 'https://image.tmdb.org/t/p/w1280' . $show->backdrop_path)
@endif

@section('content')
  <article class="media-detail">

    {{-- Backdrop --}}
    <div class="media-detail__backdrop-wrap">
      @if ($show->backdrop_path)
        <img
          class="media-detail__backdrop"
          src="https://image.tmdb.org/t/p/w1280{{ $show->backdrop_path }}"
          alt="{{ $show->name }}"
        >
      @endif
      <div class="media-detail__backdrop-overlay"></div>
    </div>

    <div class="media-detail__main">
      <div class="media-detail__layout">

        {{-- Affiche --}}
        <div class="media-detail__poster-wrap">
          @if ($show->poster_path)
            <img
              class="media-detail__poster"
              src="https://image.tmdb.org/t/p/w500{{ $show->poster_path }}"
              alt="{{ $show->name }}"
            >
          @else
            <div class="media-detail__poster--placeholder">
              <x-gmsi-o-tv class="h-16 w-16" />
            </div>
          @endif
        </div>

        {{-- Informations --}}
        <div class="media-detail__info">
          <h1 class="media-detail__title">{{ $show->name }}</h1>
          @if ($show->original_name !== $show->name)
            <p class="media-detail__original-title">{{ $show->original_name }}</p>
          @endif

          <div class="media-detail__meta">
            @if ($show->first_air_date)
              <span>{{ $show->first_air_date->format('Y') }}</span>
              @if ($show->status === 'Ended' && $show->last_air_date)
                <span>— {{ $show->last_air_date->format('Y') }}</span>
              @endif
              <span class="media-detail__meta-separator">·</span>
            @endif
            @if ($show->number_of_seasons > 0)
              <span>{{ $show->number_of_seasons }} saison{{ $show->number_of_seasons > 1 ? 's' : '' }}</span>
              <span class="media-detail__meta-separator">·</span>
              <span>{{ $show->number_of_episodes }} épisode{{ $show->number_of_episodes > 1 ? 's' : '' }}</span>
              <span class="media-detail__meta-separator">·</span>
            @endif
            @if ($show->vote_average > 0)
              <span class="rating-badge {{ $show->vote_average >= 7 ? 'rating-badge--high' : ($show->vote_average >= 5 ? 'rating-badge--mid' : 'rating-badge--low') }}">
                ★ {{ number_format($show->vote_average, 1) }}
              </span>
            @endif
          </div>

          @if ($show->genres->isNotEmpty())
            <div class="media-detail__genres">
              @foreach ($show->genres as $genre)
                <a href="{{ route('genres.tv', $genre->slug) }}" class="genre-badge">
                  {{ $genre->name }}
                </a>
              @endforeach
            </div>
          @endif

          @if ($show->tagline)
            <p class="media-detail__tagline">« {{ $show->tagline }} »</p>
          @endif

          @if ($show->overview)
            <p class="media-detail__overview">{{ $show->overview }}</p>
          @endif

          {{-- Statut --}}
          @if ($show->status)
            @php
              $statusLabel = match($show->status) {
                'Returning Series' => 'En cours',
                'Ended'            => 'Terminée',
                'Canceled'         => 'Annulée',
                'In Production'    => 'En production',
                default            => $show->status,
              };
            @endphp
            <span class="badge badge-outline">{{ $statusLabel }}</span>
          @endif
        </div>
      </div>

      {{-- Casting --}}
      @if ($show->cast->isNotEmpty())
        <div class="media-detail__section">
          <h2 class="media-detail__section-title">Casting</h2>
          <div class="cast-scroll">
            @foreach ($show->cast->take(20) as $person)
              <x-person-card :person="$person" :role="$person->pivot->character" />
            @endforeach
          </div>
        </div>
      @endif

      {{-- Plateformes streaming --}}
      <div class="media-detail__section">
        <h2 class="media-detail__section-title">Où regarder en France</h2>
        <x-streaming-providers :providers="$show->watchProviders" />
      </div>

    </div>
  </article>
@endsection
