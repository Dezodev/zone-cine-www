@extends('layouts.app')

@section('title', 'Mentions légales')
@section('description', 'Mentions légales du site Zone Ciné.')

@section('content')
<section class="section">
  <div class="section__inner legal-page__container">

    <div class="section__header">
      <h1 class="section__title">Mentions légales</h1>
    </div>

        <section class="legal-page__section">
            <h2>Éditeur du site</h2>
            <p>Le site <strong>zone-cine.fr</strong> est édité à titre personnel par :</p>
            <ul>
                <li><strong>Pseudonyme :</strong> Dezodev</li>
                <li><strong>Contact :</strong> <a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#104;&#101;&#108;&#108;&#111;&#64;&#100;&#101;&#122;&#111;&#46;&#100;&#101;&#118;" class="legal-page__link">&#104;&#101;&#108;&#108;&#111;&#64;&#100;&#101;&#122;&#111;&#46;&#100;&#101;&#118;</a></li>
            </ul>
            <p class="legal-page__note">
                Conformément à l'article 6 III 2° de la loi n° 2004-575 du 21 juin 2004 pour la confiance dans l'économie numérique (LCEN),
                les coordonnées complètes de l'éditeur sont disponibles sur demande à l'adresse email ci-dessus.
            </p>
        </section>

        <section class="legal-page__section">
            <h2>Hébergement</h2>
            <ul>
                <li><strong>Hébergeur :</strong> OVHcloud</li>
                <li><strong>Adresse :</strong> 2 rue Kellermann, 59100 Roubaix, France</li>
                <li><strong>Site web :</strong> <a href="https://www.ovhcloud.com" target="_blank" rel="noopener noreferrer" class="legal-page__link">www.ovhcloud.com</a></li>
            </ul>
        </section>

        <section class="legal-page__section">
            <h2>Propriété intellectuelle</h2>
            <p>
                Les données relatives aux films et séries (affiches, synopsis, informations) sont fournies par
                <a href="https://www.themoviedb.org" target="_blank" rel="noopener noreferrer" class="legal-page__link">The Movie Database (TMDB)</a>
                via leur API, conformément à leurs
                <a href="https://www.themoviedb.org/api-terms-of-use" target="_blank" rel="noopener noreferrer" class="legal-page__link">conditions d'utilisation</a>.
            </p>
            <p>
                Le code source du site, le design et le contenu éditorial (hors données TMDB) sont la propriété de l'éditeur.
                Toute reproduction ou utilisation sans autorisation est interdite.
            </p>
        </section>

        <section class="legal-page__section">
            <h2>Limitation de responsabilité</h2>
            <p>
                Ce site est fourni à titre informatif. L'éditeur s'efforce de maintenir les informations à jour,
                mais ne peut garantir l'exactitude ou l'exhaustivité des données provenant de sources tierces (TMDB).
            </p>
        </section>

        <section class="legal-page__section">
            <h2>Droit applicable</h2>
            <p>
                Les présentes mentions légales sont soumises au droit français.
                En cas de litige, les tribunaux français sont seuls compétents.
            </p>
        </section>
  </div>
</section>
@endsection
