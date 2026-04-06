@extends('layouts.app')


@section('content')
  <section class="section">
    <div class="section__inner">

      <div class="section__header">
        <h1 class="section__title">Séries — {{ $genre->name }}</h1>
        <span class="text-sm text-base-content/40">{{ number_format($shows->total()) }} séries</span>
      </div>

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
