@props(['trailer'])

@if ($trailer)
  <button
    class="trailer-btn"
    data-youtube-key="{{ $trailer->youtube_key }}"
    aria-label="Voir la bande-annonce"
    onclick="document.getElementById('trailer-modal').showModal(); document.getElementById('trailer-iframe').src='https://www.youtube.com/embed/{{ $trailer->youtube_key }}?autoplay=1&rel=0';"
  >
    <x-gmsi-o-play-circle class="trailer-btn__icon" />
    <span>Bande-annonce</span>
  </button>

  <dialog id="trailer-modal" class="trailer-modal" onclick="if(event.target===this){this.close();document.getElementById('trailer-iframe').src='';}">
    <div class="trailer-modal__box">
      <button
        class="trailer-modal__close"
        aria-label="Fermer"
        onclick="this.closest('dialog').close();document.getElementById('trailer-iframe').src='';"
      >
        <x-gmsi-o-close class="h-5 w-5" />
      </button>
      <div class="trailer-modal__embed">
        <iframe
          id="trailer-iframe"
          class="trailer-modal__iframe"
          src=""
          allow="autoplay; encrypted-media"
          allowfullscreen
        ></iframe>
      </div>
    </div>
  </dialog>
@endif
