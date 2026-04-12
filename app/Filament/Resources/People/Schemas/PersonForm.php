<?php

namespace App\Filament\Resources\People\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonForm
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

                                Section::make('Biographie')
                                    ->schema([
                                        Placeholder::make('name_display')
                                            ->label('Nom')
                                            ->content(fn ($record) => $record?->name ?? '—'),
                                        Textarea::make('biography')
                                            ->label('Biographie')
                                            ->rows(10),
                                    ]),

                            ]),

                        // ── Colonne secondaire (1/3) ──────────────────────────
                        Group::make()
                            ->columnSpan(1)
                            ->schema([

                                Section::make('Photo')
                                    ->schema([
                                        Html::make(fn ($record) => $record?->profile_path
                                            ? '<img src="https://image.tmdb.org/t/p/w185' . e($record->profile_path) . '"'
                                              . ' alt="' . e($record->name) . '"'
                                              . ' style="width:auto;max-height:360px;display:block;border-radius:6px;">'
                                            : '<div style="width:100%;aspect-ratio:2/3;background:#1f2937;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#6b7280;font-size:.875rem;">Aucune photo</div>'
                                        ),
                                    ]),

                                Section::make('Détails')
                                    ->schema([
                                        DatePicker::make('birthday')
                                            ->label('Date de naissance'),
                                        DatePicker::make('deathday')
                                            ->label('Date de décès'),
                                        TextInput::make('place_of_birth')
                                            ->label('Lieu de naissance'),
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
