<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NavigationItemResource\Pages;
use App\Models\NavigationItem;
use App\Rules\SafeNavigationUrl;
use App\Support\CmsPermissions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class NavigationItemResource extends CmsResource
{
    protected static ?string $model = NavigationItem::class;

    protected static ?string $navigationLabel = 'Navigasi';

    protected static ?string $modelLabel = 'tautan navigasi';

    protected static ?string $pluralModelLabel = 'navigasi';

    protected static ?string $navigationGroup = 'Website';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static function permission(): string
    {
        return CmsPermissions::NAVIGATION;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('label')->label('Label')->required()->maxLength(100),
            TextInput::make('url')->label('URL')->required()->rules([new SafeNavigationUrl])->maxLength(2048)
                ->helperText('Gunakan path internal seperti /about atau alamat eksternal HTTPS.'),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
            Toggle::make('is_active')->label('Aktif')->default(true),
            Toggle::make('open_new_tab')->label('Buka di tab baru'),
            Toggle::make('is_cta')->label('Tampilkan sebagai tombol utama'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            TextColumn::make('label')->label('Label')->searchable()->sortable(),
            TextColumn::make('url')->label('URL')->limit(50),
            TextColumn::make('sort_order')->label('Urutan')->sortable(),
            IconColumn::make('is_active')->label('Aktif')->boolean(),
            IconColumn::make('is_cta')->label('Tombol utama')->boolean(),
        ])->filters([TernaryFilter::make('is_active')->label('Status')]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNavigationItems::route('/'),
            'create' => Pages\CreateNavigationItem::route('/create'),
            'edit' => Pages\EditNavigationItem::route('/{record}/edit'),
        ];
    }
}
