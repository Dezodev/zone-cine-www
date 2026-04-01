@props(['trailer'])

@if ($trailer)
  <button
    class="trailer-btn"
    aria-label="Voir la bande-annonce"
    onclick="document.getElementById('trailer-modal-{{ $trailer->youtube_key }}').showModal();"
  >
    <x-gmsi-o-play_circle class="trailer-btn__icon" />
    <span>Bande-annonce</span>
  </button>

  <dialog
    id="trailer-modal-{{ $trailer->youtube_key }}"
    class="modal"
    onclick="if(event.target===this) this.close();"
  >
    <div class="modal-box trailer-modal__box">
      <button
        class="btn btn-sm btn-circle btn-ghost absolute top-2 right-2 z-10"
        aria-label="Fermer"
        onclick="this.closest('dialog').close();"
      >
        <x-gmsi-o-close class="h-5 w-5" />
      </button>
      <div class="trailer-modal__embed">
        <lite-youtube
          videoid="{{ $trailer->youtube_key }}"
          nocookie
          params="rel=0"
        ></lite-youtube>
      </div>
    </div>
  </dialog>
@endif
