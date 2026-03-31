@php
  $groups = [
    'flatrate' => 'Inclus dans l\'abonnement',
    'rent'     => 'Location',
    'buy'      => 'Achat',
    'free'     => 'Gratuit',
  ];
  $byType = $providers->groupBy(fn ($p) => $p->pivot->type);
@endphp

@if ($providers->isNotEmpty())
  <div class="provider-list">
    @foreach ($groups as $type => $label)
      @if ($byType->has($type))
        <div>
          <p class="provider-list__group-title">{{ $label }}</p>
          <div class="provider-list__items">
            @foreach ($byType[$type] as $provider)
              <div class="provider-list__item" data-tip="{{ $provider->name }}">
                @if ($provider->logo_path)
                  <img
                    class="provider-list__logo"
                    src="https://image.tmdb.org/t/p/w92{{ $provider->logo_path }}"
                    alt="{{ $provider->name }}"
                  >
                @else
                  <span class="badge badge-outline">{{ $provider->name }}</span>
                @endif
              </div>
            @endforeach
          </div>
        </div>
      @endif
    @endforeach
  </div>
@else
  <p class="text-sm text-base-content/40">Aucune plateforme disponible en France.</p>
@endif
