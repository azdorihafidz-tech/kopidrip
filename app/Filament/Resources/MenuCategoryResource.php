<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuCategoryResource\Pages;
use App\Models\MenuCategory;
use App\Support\CmsPermissions;
use App\Support\CmsUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MenuCategoryResource extends CmsResource
{
    protected static ?string $model = MenuCategory::class;

    protected static ?string $navigationLabel = 'Kategori menu';

    protected static ?string $modelLabel = 'kategori menu';

    protected static ?string $pluralModelLabel = 'kategori menu';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static function permission(): string
    {
        return CmsPermissions::MENU;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')->label('Nama kategori')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                    if (blank($get('slug'))) {
                        $set('slug', Str::slug((string) $state));
                    }
                }),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255)->unique(ignoreRecord: true)->rule('regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'),
            Textarea::make('description')->label('Deskripsi')->rows(3)->maxLength(1000)->columnSpanFull(),
            CmsUpload::image('image_path', 'menus/categories')->label('Gambar kategori'),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
            Toggle::make('is_active')->label('Aktif di website')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            ImageColumn::make('image_path')->disk('public')->label('Gambar'),
            TextColumn::make('name')->label('Kategori')->searchable()->sortable(),
            TextColumn::make('slug')->label('Slug')->searchable(),
            IconColumn::make('is_active')->label('Aktif')->boolean(),
            TextColumn::make('sort_order')->label('Urutan')->sortable(),
        ])->filters([TernaryFilter::make('is_active')->label('Status')]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuCategories::route('/'),
            'create' => Pages\CreateMenuCategory::route('/create'),
            'edit' => Pages\EditMenuCategory::route('/{record}/edit'),
        ];
    }
}
