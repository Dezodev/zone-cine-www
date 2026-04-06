<?php

namespace App\Filament\Resources\People\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Section::make('Édition manuelle')
                    ->columnSpanFull()
                    ->schema([
                        DatePicker::make('birthday')
                            ->label('Date de naissance'),
                        DatePicker::make('deathday')
                            ->label('Date de décès'),
                        TextInput::make('place_of_birth')
                            ->label('Lieu de naissance')
                            ->columnSpanFull(),
                        Textarea::make('biography')
                            ->label('Biographie')
                            ->rows(8)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Données TMDB (lecture seule)')
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('tmdb_id')
                            ->label('TMDB ID')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nom')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('profile_path')
                            ->label('Photo (path)')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ]);
    }
}
