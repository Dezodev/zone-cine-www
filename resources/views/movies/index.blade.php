@extends('layouts.app')


@section('content')
  <section class="section">
    <div class="section__inner">

      <div class="section__header">
        <h1 class="section__title">Films</h1>
        <span class="text-sm text-base-content/40">{{ number_format($movies->total()) }} films</span>
      </div>

      <form class="filter-bar" method="GET" action="{{ route('movies.index') }}">
        <select name="genre" class="filter-bar__select" onchange="this.form.submit()">
          <option value="">Genre</option>
          @foreach ($genres as $genre)
            <option value="{{ $genre->slug }}" @selected(request('genre') === $genre->slug)>{{ $genre->name }}</option>
          @endforeach
        </select>

        <select name="annee" class="filter-bar__select" onchange="this.form.submit()">
          <option value="">Année</option>
          @foreach (range(date('Y'), 1900) as $y)
            <option value="{{ $y }}" @selected(request('annee') == $y)>{{ $y }}</option>
          @endforeach
        </select>

        <select name="tri" class="filter-bar__select" onchange="this.form.submit()">
          <option value="" @selected(!request('tri'))>Popularité</option>
          <option value="date" @selected(request('tri') === 'date')>Date de sortie</option>
          <option value="note" @selected(request('tri') === 'note')>Note</option>
        </select>

        @if (request()->hasAny(['genre', 'annee', 'langue', 'tri']))
          <a href="{{ route('movies.index') }}" class="filter-bar__reset">Réinitialiser</a>
        @endif
      </form>

      <div class="media-grid media-grid--wide">
        @forelse ($movies as $movie)
          <x-media-card :media="$movie" />
        @empty
          <p class="text-base-content/40 col-span-full py-16 text-center">Aucun film trouvé.</p>
        @endforelse
      </div>

      <div class="pagination-nav">
        {{ $movies->links() }}
      </div>

    </div>
  </section>
@endsection
