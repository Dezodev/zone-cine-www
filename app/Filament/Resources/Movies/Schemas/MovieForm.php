<?php

namespace App\Filament\Resources\Movies\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MovieForm
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
                        TextInput::make('imdb_id')
                            ->label('IMDB ID')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('title')
                            ->label('Titre')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('original_title')
                            ->label('Titre original')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->disabled()
                            ->dehydrated(false),
                        DatePicker::make('release_date')
                            ->label('Date de sortie')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('runtime')
                            ->label('Durée (min)')
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
                        TextInput::make('budget')
                            ->label('Budget ($)')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('revenue')
                            ->label('Recettes ($)')
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
