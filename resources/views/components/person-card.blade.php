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
        <x-gmsi-o-person class="h-8 w-8" />
      </div>
    @endif
  </div>
  <p class="person-card__name">{{ $person->name }}</p>
  @if (!empty($role))
    <p class="person-card__role">{{ $role }}</p>
  @endif
</a>
