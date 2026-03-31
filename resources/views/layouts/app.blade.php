<!DOCTYPE html>
<html lang="fr" data-theme="zone-cine">
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
      <button class="site-header__search-btn" aria-label="Rechercher">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
        </svg>
      </button>
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
