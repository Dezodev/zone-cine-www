<?php

namespace App\Filament\Resources\People\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PeopleTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('birthday')
                    ->label('Naissance')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('place_of_birth')
                    ->label('Lieu de naissance')
                    ->searchable(),
                TextColumn::make('biography')
                    ->label('Biographie')
                    ->limit(60)
                    ->toggleable(),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
