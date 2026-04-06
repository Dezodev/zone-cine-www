<?php

namespace App\Filament\Resources\TvShows\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TvShowForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Édition manuelle')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('hidden')
                            ->label('Masqué')
                            ->helperText('Les contenus masqués ne sont pas visibles sur le site.')
                            ->required(),
                    ]),

                Section::make('Données TMDB (lecture seule)')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('tmdb_id')
                            ->label('TMDB ID')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Titre')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('original_name')
                            ->label('Titre original')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('slug')
                            ->label('Slug')
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
                        TextInput::make('original_language')
                            ->label('Langue originale')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('status')
                            ->label('Statut')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('type')
                            ->label('Type')
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
                        TextInput::make('vote_average')
                            ->label('Note moyenne')
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
                        Textarea::make('tagline')
                            ->label('Accroche')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                        Textarea::make('overview')
                            ->label('Synopsis')
                            ->rows(5)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
