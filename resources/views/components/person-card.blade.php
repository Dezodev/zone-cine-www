<a href="{{ route('people.show', $person->slug) }}" class="person-card">
  <div class="person-card__photo-wrap">
    @if ($person->profile_path)
      <img
        class="person-card__photo"
        src="https://image.tmdb.org/t/p/w185{{ $person->profile_path }}"
        alt="{{ $person->name }}"
        loading="lazy"
      >
    @else
      <div class="person-card__photo--placeholder">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14a7 7 0 0 0-7 7h14a7 7 0 0 0-7-7z"/>
        </svg>
      </div>
    @endif
  </div>
  <p class="person-card__name">{{ $person->name }}</p>
  @if (!empty($role))
    <p class="person-card__role">{{ $role }}</p>
  @endif
</a>
