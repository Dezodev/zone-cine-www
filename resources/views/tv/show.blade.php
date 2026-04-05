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

      {{-- En-tête : affiche + titre --}}
      <div class="media-detail__layout">

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
              <span class="text-base-content/30 text-xs">{{ number_format($show->vote_count) }} votes</span>
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
        </div>
      </div>

      {{-- Corps 2 colonnes --}}
      <div class="media-detail__body">

        {{-- Colonne principale : synopsis + bande-annonce --}}
        <div class="media-detail__primary">
          @if ($show->tagline)
            <p class="media-detail__tagline">« {{ $show->tagline }} »</p>
          @endif
          @if ($show->overview)
            <p class="media-detail__overview">{{ $show->overview }}</p>
          @endif

          @if ($show->trailer)
            <div class="media-detail__trailer">
              <lite-youtube
                videoid="{{ $show->trailer->youtube_key }}"
                nocookie
                params="rel=0"
              ></lite-youtube>
            </div>
          @endif
        </div>

        {{-- Colonne secondaire : plateformes + infos --}}
        <aside class="media-detail__secondary">

          <div class="media-detail__secondary-block">
            <h2 class="media-detail__section-title">Où regarder en France</h2>
            <x-streaming-providers :providers="$show->watchProviders" />
          </div>

          <div class="media-detail__secondary-block">
            <h2 class="media-detail__section-title">Fiche technique</h2>
            <dl class="media-detail__facts">
              @if ($show->status)
                @php $status = \App\Enums\TvShowStatus::tryFrom($show->status); @endphp
                <div class="media-detail__fact">
                  <dt>Statut</dt>
                  <dd>
                    <span class="badge badge-sm {{ $status?->badgeClass() ?? 'badge-outline' }}">
                      {{ $status?->label() ?? $show->status }}
                    </span>
                  </dd>
                </div>
              @endif
              @if ($show->type)
                <div class="media-detail__fact">
                  <dt>Type</dt>
                  <dd>{{ \App\Enums\TvShowType::tryFrom($show->type)?->label() ?? $show->type }}</dd>
                </div>
              @endif
              @if ($show->original_language)
                <div class="media-detail__fact">
                  <dt>Langue originale</dt>
                  <dd>{{ \Locale::getDisplayLanguage($show->original_language, 'fr') }}</dd>
                </div>
              @endif
              @if ($show->number_of_seasons > 0)
                <div class="media-detail__facts-row">
                  <div class="media-detail__fact">
                    <dt>Saisons</dt>
                    <dd>{{ $show->number_of_seasons }}</dd>
                  </div>
                  <div class="media-detail__fact">
                    <dt>Épisodes</dt>
                    <dd>{{ $show->number_of_episodes }}</dd>
                  </div>
                </div>
              @endif
            </dl>
          </div>

        </aside>
      </div>

      {{-- Casting (pleine largeur) --}}
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

      {{-- Saisons & épisodes (pleine largeur) --}}
      @if ($show->seasons->isNotEmpty())
        <div class="media-detail__section">
          <h2 class="media-detail__section-title">Saisons & épisodes</h2>
          <div class="seasons-list">
            @foreach ($show->seasons as $season)
              <details class="season-item" {{ $loop->first ? 'open' : '' }}>
                <summary class="season-item__header">
                  <div class="season-item__header-left">
                    @if ($season->poster_path)
                      <img
                        class="season-item__poster"
                        src="https://image.tmdb.org/t/p/w92{{ $season->poster_path }}"
                        alt="{{ $season->name }}"
                        loading="lazy"
                      >
                    @else
                      <div class="season-item__poster season-item__poster--placeholder">
                        <x-gmsi-o-tv class="h-5 w-5" />
                      </div>
                    @endif
                    <div>
                      <span class="season-item__name">{{ $season->name }}</span>
                      <span class="season-item__count">{{ $season->episode_count }} épisode{{ $season->episode_count > 1 ? 's' : '' }}</span>
                    </div>
                  </div>
                  <svg class="season-item__chevron h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <polyline points="6 9 12 15 18 9"/>
                  </svg>
                </summary>

                <div class="season-item__episodes">
                  @forelse ($season->episodes as $episode)
                    <div class="episode-item">
                      @if ($episode->still_path)
                        <img
                          class="episode-item__still"
                          src="https://image.tmdb.org/t/p/w300{{ $episode->still_path }}"
                          alt="{{ $episode->name }}"
                          loading="lazy"
                        >
                      @else
                        <div class="episode-item__still episode-item__still--placeholder">
                          <x-gmsi-o-movie class="h-6 w-6" />
                        </div>
                      @endif
                      <div class="episode-item__body">
                        <div class="episode-item__header">
                          <span class="episode-item__number">E{{ $episode->episode_number }}</span>
                          <span class="episode-item__title">{{ $episode->name }}</span>
                          @if ($episode->runtime)
                            <span class="episode-item__runtime">{{ $episode->runtime }} min</span>
                          @endif
                          @if ($episode->vote_average > 0)
                            <span class="episode-item__rating">★ {{ number_format($episode->vote_average, 1) }}</span>
                          @endif
                        </div>
                        @if ($episode->overview)
                          <p class="episode-item__overview">{{ $episode->overview }}</p>
                        @endif
                        @if ($episode->air_date)
                          <span class="episode-item__date">{{ $episode->air_date->translatedFormat('d M Y') }}</span>
                        @endif
                      </div>
                    </div>
                  @empty
                    <p class="px-4 py-3 text-sm text-base-content/40">Aucun épisode disponible.</p>
                  @endforelse
                </div>
              </details>
            @endforeach
          </div>
        </div>
      @endif

    </div>
  </article>
@endsection
