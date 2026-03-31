@php
  $isMovie  = isset($media->title);
  $title    = $media->title ?? $media->name;
  $year     = ($media->release_date ?? $media->first_air_date)?->format('Y');
  $url      = $isMovie
    ? route('movies.show', $media->slug)
    : route('tv.show', $media->slug);
  $score    = $media->vote_average ?? 0;
  $ratingClass = match(true) {
    $score >= 7  => 'rating-badge--high',
    $score >= 5  => 'rating-badge--mid',
    default      => 'rating-badge--low',
  };
@endphp

<a href="{{ $url }}" class="media-card">
  <div class="media-card__poster-wrap">
    @if ($media->poster_path)
      <img
        class="media-card__poster"
        src="https://image.tmdb.org/t/p/w342{{ $media->poster_path }}"
        alt="{{ $title }}"
        loading="lazy"
      >
    @else
      <div class="media-card__poster--placeholder">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 16h4m10 0h4M4 20h16a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1z"/>
        </svg>
      </div>
    @endif
    @if ($score > 0)
      <div class="media-card__rating">
        <span class="rating-badge {{ $ratingClass }}">{{ number_format($score, 1) }}</span>
      </div>
    @endif
  </div>
  <div class="media-card__body">
    <p class="media-card__title">{{ $title }}</p>
    @if ($year)
      <p class="media-card__year">{{ $year }}</p>
    @endif
  </div>
</a>
