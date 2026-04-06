@extends('layouts.app')


@section('content')
  <section class="section">
    <div class="section__inner">

      <div class="section__header">
        <h1 class="section__title">Séries</h1>
        <span class="text-sm text-base-content/40">{{ number_format($shows->total()) }} séries</span>
      </div>

      <form class="filter-bar" method="GET" action="{{ route('tv.index') }}">
        <select name="genre" class="filter-bar__select" onchange="this.form.submit()">
          <option value="">Genre</option>
          @foreach ($genres as $genre)
            <option value="{{ $genre->slug }}" @selected(request('genre') === $genre->slug)>{{ $genre->name }}</option>
          @endforeach
        </select>

        <select name="statut" class="filter-bar__select" onchange="this.form.submit()">
          <option value="">Statut</option>
          <option value="Returning Series" @selected(request('statut') === 'Returning Series')>En cours</option>
          <option value="Ended" @selected(request('statut') === 'Ended')>Terminée</option>
          <option value="Canceled" @selected(request('statut') === 'Canceled')>Annulée</option>
        </select>

        <select name="tri" class="filter-bar__select" onchange="this.form.submit()">
          <option value="" @selected(!request('tri'))>Popularité</option>
          <option value="date" @selected(request('tri') === 'date')>Date de diffusion</option>
          <option value="note" @selected(request('tri') === 'note')>Note</option>
        </select>

        @if (request()->hasAny(['genre', 'statut', 'langue', 'tri']))
          <a href="{{ route('tv.index') }}" class="filter-bar__reset">Réinitialiser</a>
        @endif
      </form>

      <div class="media-grid media-grid--wide">
        @forelse ($shows as $show)
          <x-media-card :media="$show" />
        @empty
          <p class="text-base-content/40 col-span-full py-16 text-center">Aucune série trouvée.</p>
        @endforelse
      </div>

      <div class="pagination-nav">
        {{ $shows->links() }}
      </div>

    </div>
  </section>
@endsection
