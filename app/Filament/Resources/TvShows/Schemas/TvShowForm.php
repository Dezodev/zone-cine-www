<?php

namespace App\Filament\Resources\TvShows\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TvShowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([

                        // ── Colonne principale (2/3) ──────────────────────────
                        Group::make()
                            ->columnSpan(2)
                            ->schema([

                                Section::make('Synopsis')
                                    ->schema([
                                        Placeholder::make('name_display')
                                            ->label('Titre')
                                            ->content(fn ($record) => $record?->name ?? '—'),
                                        Textarea::make('tagline')
                                            ->label('Accroche')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->rows(2),
                                        Textarea::make('overview')
                                            ->label('Synopsis')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->rows(6),
                                    ]),

                                Section::make('Audience')
                                    ->columns(3)
                                    ->collapsed()
                                    ->schema([
                                        TextInput::make('vote_average')
                                            ->label('Note (/ 10)')
                                            ->disabled()
                                            ->dehydrated(false),
                                        TextInput::make('vote_count')
                                            ->label('Nombre de votes')
                                            ->disabled()
                                            ->dehydrated(false),
                                        TextInput::make('popularity')
                                            ->label('Popularité')
                                            ->disabled()
                                            ->dehydrated(false),
                                    ]),

                            ]),

                        // ── Colonne secondaire (1/3) ──────────────────────────
                        Group::make()
                            ->columnSpan(1)
                            ->schema([

                                Section::make('Publication')
                                    ->schema([
                                        Toggle::make('hidden')
                                            ->label('Masqué')
                                            ->helperText('Les contenus masqués ne sont pas visibles sur le site.')
                                            ->required(),
                                    ]),

                                Section::make('Affiche')
                                    ->schema([
                                        Html::make(fn ($record) => $record?->poster_path
                                            ? '<img src="https://image.tmdb.org/t/p/w342' . e($record->poster_path) . '"'
                                              . ' alt="' . e($record->name) . '"'
                                              . ' style="width:auto;max-height:360px;display:block;border-radius:6px;">'
                                            : '<div style="width:100%;aspect-ratio:2/3;background:#1f2937;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:.875rem;">Aucune affiche</div>'
                                        ),
                                    ]),

                                Section::make('Détails')
                                    ->schema([
                                        TextInput::make('status')
                                            ->label('Statut')
                                            ->disabled()
                                            ->dehydrated(false),
                                        TextInput::make('type')
                                            ->label('Type')
                                            ->disabled()
                                            ->dehydrated(false),
                                        DatePicker::make('first_air_date')
                                            ->label('Première diffusion')
                                            ->disabled()
                                            ->dehydrated(false),
                                        DatePicker::make('last_air_date')
                                            ->label('Dernière diffusion')
                                            ->disabled()
                                            ->dehydrated(false),
                                        TextInput::make('number_of_seasons')
                                            ->label('Saisons')
                                            ->disabled()
                                            ->dehydrated(false),
                                        TextInput::make('number_of_episodes')
                                            ->label('Épisodes')
                                            ->disabled()
                                            ->dehydrated(false),
                                        TextInput::make('original_language')
                                            ->label('Langue originale')
                                            ->disabled()
                                            ->dehydrated(false),
                                        Placeholder::make('created_at')
                                            ->label('Ajouté le')
                                            ->content(fn ($record) => $record?->created_at?->format('d/m/Y à H:i') ?? '—'),
                                        Placeholder::make('updated_at')
                                            ->label('Mis à jour le')
                                            ->content(fn ($record) => $record?->updated_at?->format('d/m/Y à H:i') ?? '—'),
                                    ]),

                                Section::make('Identité')
                                    ->collapsed()
                                    ->schema([
                                        TextInput::make('original_name')
                                            ->label('Titre original')
                                            ->disabled()
                                            ->dehydrated(false),
                                        TextInput::make('slug')
                                            ->label('Slug')
                                            ->disabled()
                                            ->dehydrated(false),
                                        TextInput::make('tmdb_id')
                                            ->label('TMDB ID')
                                            ->disabled()
                                            ->dehydrated(false),
                                    ]),

                            ]),
                    ]),
            ]);
    }
}
