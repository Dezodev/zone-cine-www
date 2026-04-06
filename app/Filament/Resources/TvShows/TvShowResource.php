<?php

namespace App\Filament\Resources\TvShows;

use App\Filament\Resources\TvShows\Pages\EditTvShow;
use App\Filament\Resources\TvShows\Pages\ListTvShows;
use App\Filament\Resources\TvShows\Schemas\TvShowForm;
use App\Filament\Resources\TvShows\Tables\TvShowsTable;
use App\Models\TvShow;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TvShowResource extends Resource
{
    protected static ?string $model = TvShow::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTv;

    protected static ?string $navigationLabel = 'Séries';

    protected static ?string $modelLabel = 'série';

    protected static ?string $pluralModelLabel = 'Séries';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return TvShowForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TvShowsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTvShows::route('/'),
            'edit' => EditTvShow::route('/{record}/edit'),
        ];
    }
}
