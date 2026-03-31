@extends('layouts.app')

@section('title', $person->name)
@section('description', Str::limit($person->biography, 160))

@section('content')
  <section class="section">
    <div class="section__inner">

      <div class="media-detail__layout">

        {{-- Photo --}}
        <div class="media-detail__poster-wrap">
          @if ($person->profile_path)
            <img
              class="media-detail__poster"
              src="https://image.tmdb.org/t/p/w500{{ $person->profile_path }}"
              alt="{{ $person->name }}"
            >
          @else
            <div class="media-detail__poster--placeholder">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/>
              </svg>
            </div>
          @endif
        </div>

        {{-- Informations --}}
        <div class="media-detail__info">
          <h1 class="media-detail__title">{{ $person->name }}</h1>

          <div class="media-detail__meta">
            @if ($person->birthday)
              <span>Né(e) le {{ $person->birthday->translatedFormat('d F Y') }}</span>
            @endif
            @if ($person->deathday)
              <span class="media-detail__meta-separator">·</span>
              <span>Décédé(e) le {{ $person->deathday->translatedFormat('d F Y') }}</span>
            @endif
            @if ($person->place_of_birth)
              <span class="media-detail__meta-separator">·</span>
              <span>{{ $person->place_of_birth }}</span>
            @endif
          </div>

          @if ($person->biography)
            <p class="media-detail__overview">{{ $person->biography }}</p>
          @endif
        </div>
      </div>

      {{-- Filmographie --}}
      @if ($person->movies->isNotEmpty())
        <div class="media-detail__section">
          <h2 class="media-detail__section-title">Films</h2>
          <div class="media-grid">
            @foreach ($person->movies as $movie)
              <x-media-card :media="$movie" />
            @endforeach
          </div>
        </div>
      @endif

      @if ($person->tvShows->isNotEmpty())
        <div class="media-detail__section">
          <h2 class="media-detail__section-title">Séries</h2>
          <div class="media-grid">
            @foreach ($person->tvShows as $show)
              <x-media-card :media="$show" />
            @endforeach
          </div>
        </div>
      @endif

    </div>
  </section>
@endsection
