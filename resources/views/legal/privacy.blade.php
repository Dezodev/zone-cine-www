@extends('layouts.app')


@section('content')
<section class="section">
  <div class="section__inner legal-page__container">

    <div class="section__header">
      <h1 class="section__title">Politique de confidentialité</h1>
      <span class="text-sm text-base-content/40">Mise à jour le 11 avril 2026</span>
    </div>

        <section class="legal-page__section">
            <h2>Responsable du traitement</h2>
            <p>
                Le responsable du traitement des données collectées sur <strong>zone-cine.fr</strong> est
                l'éditeur du site (Dezodev). Pour toute question relative à vos données personnelles,
                vous pouvez le contacter à l'adresse :
                <a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#104;&#101;&#108;&#108;&#111;&#64;&#100;&#101;&#122;&#111;&#46;&#100;&#101;&#118;" class="legal-page__link">&#104;&#101;&#108;&#108;&#111;&#64;&#100;&#101;&#122;&#111;&#46;&#100;&#101;&#118;</a>.
            </p>
        </section>

        <section class="legal-page__section">
            <h2>Données collectées</h2>
            <p>Zone Ciné est un site consultatif sans compte utilisateur. Les données collectées sont limitées à :</p>
            <ul>
                <li><strong>Données de navigation :</strong> pages visitées, durée de visite, pays, type d'appareil, navigateur. Ces données sont anonymisées et ne permettent pas de vous identifier personnellement.</li>
                <li><strong>Logs serveur :</strong> adresse IP, date et heure de connexion, page demandée. Ces logs sont conservés pour des raisons techniques et de sécurité pendant une durée maximale de 12 mois.</li>
            </ul>
        </section>

        <section class="legal-page__section">
            <h2>Mesure d'audience (Umami)</h2>
            <p>
                Ce site utilise <strong>Umami</strong>, un outil d'analyse d'audience open source auto-hébergé.
                Umami respecte la vie privée des utilisateurs :
            </p>
            <ul>
                <li>Aucun cookie n'est déposé sur votre navigateur.</li>
                <li>Aucune donnée personnelle identifiable n'est collectée.</li>
                <li>Les données sont anonymisées et ne sont pas partagées avec des tiers.</li>
                <li>L'outil est hébergé sur nos propres serveurs en France (OVHcloud), sans aucun transfert vers des services tiers.</li>
            </ul>
            <p>
                Cette collecte ne nécessite pas votre consentement au sens du RGPD, car aucun cookie de traçage n'est utilisé
                et aucune donnée personnelle n'est traitée.
            </p>
        </section>

        <section class="legal-page__section">
            <h2>Cookies</h2>
            <p>
                Zone Ciné n'utilise <strong>aucun cookie publicitaire ou de traçage</strong>.
                Le site peut utiliser des cookies techniques strictement nécessaires à son fonctionnement (session PHP).
                Ces cookies ne requièrent pas votre consentement.
            </p>
        </section>

        <section class="legal-page__section">
            <h2>Partage des données</h2>
            <p>
                Aucune donnée vous concernant n'est vendue, louée ou transmise à des tiers à des fins commerciales.
                Les données de navigation anonymisées restent sur nos serveurs.
            </p>
        </section>

        <section class="legal-page__section">
            <h2>Vos droits (RGPD)</h2>
            <p>
                Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés,
                vous disposez des droits suivants sur vos données personnelles :
            </p>
            <ul>
                <li><strong>Droit d'accès</strong> — obtenir une copie des données vous concernant.</li>
                <li><strong>Droit de rectification</strong> — corriger des données inexactes.</li>
                <li><strong>Droit à l'effacement</strong> — demander la suppression de vos données.</li>
                <li><strong>Droit d'opposition</strong> — vous opposer à un traitement de vos données.</li>
            </ul>
            <p>
                Pour exercer ces droits, contactez-nous à :
                <a href="&#109;&#97;&#105;&#108;&#116;&#111;&#58;&#104;&#101;&#108;&#108;&#111;&#64;&#100;&#101;&#122;&#111;&#46;&#100;&#101;&#118;" class="legal-page__link">&#104;&#101;&#108;&#108;&#111;&#64;&#100;&#101;&#122;&#111;&#46;&#100;&#101;&#118;</a>.
                Vous disposez également du droit d'introduire une réclamation auprès de la
                <a href="https://www.cnil.fr" target="_blank" rel="noopener noreferrer" class="legal-page__link">CNIL</a>.
            </p>
        </section>

        <section class="legal-page__section">
            <h2>Modifications</h2>
            <p>
                Cette politique peut être mise à jour à tout moment. La date de dernière mise à jour est indiquée en haut de cette page.
            </p>
        </section>
  </div>
</section>
@endsection
