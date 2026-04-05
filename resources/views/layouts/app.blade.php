<!DOCTYPE html>
<html lang="fr" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>@yield('title', 'Zone Ciné') — Zone Ciné</title>
  <meta name="description" content="@yield('description', 'Films, séries et cinémas en France — catalogue complet et séances en temps réel.')">

  {{-- Open Graph --}}
  <meta property="og:title" content="@yield('title', 'Zone Ciné')">
  <meta property="og:description" content="@yield('description', 'Films, séries et cinémas en France.')">
  <meta property="og:image" content="@yield('og_image', asset('images/og-default.jpg'))">
  <meta property="og:type" content="website">
  <meta name="twitter:card" content="summary_large_image">

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

  {{-- Navigation --}}
  <header class="site-header">
    <div class="navbar-start">
      <a href="{{ route('home') }}" class="site-header__logo">
        Zone<span>Ciné</span>
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
      <span class="site-footer__logo">Zone<span>Ciné</span></span>
      <p class="site-footer__credits">
        Données fournies par
        <a href="https://www.themoviedb.org" target="_blank" rel="noopener" class="text-primary hover:underline">TMDB</a>.
        &copy; {{ date('Y') }} zone-cine.fr
      </p>
    </div>
  </footer>

</body>
</html>
