<?php

namespace App\Filament\Resources\TvShows\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TvShowsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster_path')
                    ->label('')
                    ->state(fn ($record) => $record->poster_path
                        ? 'https://image.tmdb.org/t/p/w342' . $record->poster_path
                        : null)
                    ->height(240)
                    ->width(160)
                    ->extraImgAttributes(['class' => 'rounded object-cover w-full']),

                TextColumn::make('name')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('first_air_date')
                    ->label('Première diffusion')
                    ->date('Y')
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('popularity')
                    ->label('Popularité')
                    ->numeric(decimalPlaces: 1)
                    ->sortable()
                    ->color('gray'),

                IconColumn::make('hidden')
                    ->label('Masqué')
                    ->boolean(),
            ])
            ->contentGrid([
                'sm' => 2,
                'md' => 3,
                'lg' => 4,
                'xl' => 5,
            ])
            ->defaultSort('popularity', 'desc')
            ->filters([
                TernaryFilter::make('hidden')->label('Masqué'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
