<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Models\ProductCategory;
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

class ProductCategoryResource extends CmsResource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationLabel = 'Kategori produk';

    protected static ?string $modelLabel = 'kategori produk';

    protected static ?string $pluralModelLabel = 'kategori produk';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static function permission(): string
    {
        return CmsPermissions::CATALOG;
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
            CmsUpload::image('image_path', 'products/categories')->label('Gambar kategori'),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
            Toggle::make('is_active')->label('Aktif di website')->default(true),
            TextInput::make('seo_title')->label('Judul SEO')->maxLength(255),
            Textarea::make('seo_description')->label('Deskripsi SEO')->rows(3)->maxLength(320),
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
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
