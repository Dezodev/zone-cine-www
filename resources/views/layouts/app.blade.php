<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="/images/favicon/favicon-96x96.png?v=20260412" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="/images/favicon/favicon.svg?v=20260412" />
  <link rel="shortcut icon" href="/images/favicon/favicon.ico?v=20260412" />
  <link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-touch-icon.png?v=20260412" />
  <meta name="apple-mobile-web-app-title" content="Zone Ciné" />
  <link rel="manifest" href="/images/favicon/site.webmanifest?v=20260412" />

  {!! SEOMeta::generate() !!}
  {!! OpenGraph::generate() !!}
  {!! Twitter::generate() !!}
  {!! JsonLd::generate() !!}

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

  {{-- Navigation --}}
  <header class="site-header">
    <div class="navbar-start">
      <a href="{{ route('home') }}" class="site-header__logo">
        <img src="{{ asset('images/ZoneCine-logo.svg') }}" alt="Logo ZoneCiné" class="site-header__logo-img"/>
      </a>
    </div>

    <nav class="navbar-center site-header__nav">
      <a href="{{ route('movies.index') }}"
         class="site-header__nav-link {{ request()->routeIs('movies.*') ? 'site-header__nav-link--active' : '' }}">
        Films
      </a>
      <a href="{{ route('tv.index') }}"
         class="site-header__nav-link {{ request()->routeIs('tv.*') ? 'site-header__nav-link--active' : '' }}">
        Séries
      </a>
    </nav>

    <div class="navbar-end site-header__actions">
      <a href="{{ route('search') }}" class="site-header__search-btn {{ request()->routeIs('search') ? 'site-header__search-btn--active' : '' }}" aria-label="Rechercher">
        <x-gmsi-o-search class="h-5 w-5" />
      </a>
    </div>
  </header>

  {{-- Contenu principal --}}
  <main>
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer class="site-footer">
    <div class="site-footer__inner">
      <div class="site-footer__branding">
        <div class="site-footer__logo">
            <img src="{{ asset('images/ZoneCine-logo.svg') }}" alt="Logo ZoneCiné" class="site-footer__logo-img"/>
        </div>
        <p class="site-footer__credits">
          &copy; {{ date('Y') }} zone-cine.fr •
          Données fournies par
          <a href="https://www.themoviedb.org" target="_blank" rel="noopener" class="text-primary hover:underline">TMDB</a>.
        </p>
      </div>
      <nav class="site-footer__legal">
        <a href="{{ route('legal.mentions') }}" class="site-footer__legal-link">Mentions légales</a>
        <span class="site-footer__legal-sep" aria-hidden="true">·</span>
        <a href="{{ route('legal.privacy') }}" class="site-footer__legal-link">Politique de confidentialité</a>
      </nav>
    </div>
  </footer>

</body>
</html>
