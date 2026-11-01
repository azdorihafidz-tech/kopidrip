<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use App\Support\CmsPermissions;
use App\Support\CmsUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MenuItemResource extends CmsResource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationLabel = 'Item menu';

    protected static ?string $modelLabel = 'item menu';

    protected static ?string $pluralModelLabel = 'item menu';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static function permission(): string
    {
        return CmsPermissions::MENU;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('menu_category_id')->label('Kategori')->relationship('category', 'name')->searchable()->preload()->required(),
            TextInput::make('name')->label('Nama menu')->required()->maxLength(255),
            Textarea::make('description')->label('Deskripsi')->rows(3)->maxLength(1000)->columnSpanFull(),
            TextInput::make('price')->label('Harga')->numeric()->minValue(0)->prefix('Rp')->nullable()
                ->helperText('Kosongkan jika harga belum ditentukan.'),
            CmsUpload::image('image_path', 'menus/items')->label('Gambar'),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
            Toggle::make('is_available')->label('Tersedia di website')->default(true),
            Toggle::make('is_featured')->label('Unggulan'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            ImageColumn::make('image_path')->disk('public')->label('Gambar'),
            TextColumn::make('name')->label('Menu')->searchable()->sortable(),
            TextColumn::make('category.name')->label('Kategori')->sortable(),
            TextColumn::make('price')->label('Harga')->money('IDR', locale: 'id'),
            IconColumn::make('is_available')->label('Tersedia')->boolean(),
            IconColumn::make('is_featured')->label('Unggulan')->boolean(),
        ])->filters([
            SelectFilter::make('menu_category_id')->relationship('category', 'name')->label('Kategori'),
            TernaryFilter::make('is_available')->label('Status ketersediaan'),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('category');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
