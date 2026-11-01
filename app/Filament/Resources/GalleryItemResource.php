<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GalleryItemResource\Pages;
use App\Models\GalleryItem;
use App\Support\CmsPermissions;
use App\Support\CmsUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GalleryItemResource extends CmsResource
{
    protected static ?string $model = GalleryItem::class;

    protected static ?string $navigationLabel = 'Galeri';

    protected static ?string $modelLabel = 'foto galeri';

    protected static ?string $pluralModelLabel = 'galeri';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static function permission(): string
    {
        return CmsPermissions::GALLERY;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->label('Judul')->required()->maxLength(255),
            Select::make('category')->label('Kategori')->options([
                'coffee' => 'Coffee', 'cafe' => 'Cafe', 'roastery' => 'Roastery', 'product' => 'Product', 'event' => 'Event',
            ])->required()->native(false),
            CmsUpload::image('image_path', 'gallery', true)->label('Foto'),
            TextInput::make('alt_text')->label('Teks alternatif')->required()->maxLength(255),
            Textarea::make('caption')->label('Keterangan singkat')->rows(2)->maxLength(255),
            Textarea::make('description')->label('Deskripsi')->rows(3)->maxLength(1000)->columnSpanFull(),
            Select::make('status')->label('Status')->options(['draft' => 'Draft', 'published' => 'Terbit'])->default('draft')->required(),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            ImageColumn::make('image_path')->disk('public')->label('Pratinjau'),
            TextColumn::make('title')->label('Judul')->searchable()->sortable(),
            TextColumn::make('category')->label('Kategori')->badge()->sortable(),
            TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
            TextColumn::make('sort_order')->label('Urutan')->sortable(),
        ])->filters([
            SelectFilter::make('category')->options(['coffee' => 'Coffee', 'cafe' => 'Cafe', 'roastery' => 'Roastery', 'product' => 'Product', 'event' => 'Event']),
            SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Terbit']),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleryItems::route('/'),
            'create' => Pages\CreateGalleryItem::route('/create'),
            'edit' => Pages\EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
