@extends('layouts.app')

@section('title', 'Films ' . $genre->name)
@section('description', 'Tous les films du genre ' . $genre->name . '.')

@section('content')
  <section class="section">
    <div class="section__inner">

      <div class="section__header">
        <h1 class="section__title">Films — {{ $genre->name }}</h1>
        <span class="text-sm text-base-content/40">{{ number_format($movies->total()) }} films</span>
      </div>

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
