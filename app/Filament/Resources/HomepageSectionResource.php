<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSectionResource\Pages;
use App\Models\HomepageSection;
use App\Rules\SafeNavigationUrl;
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
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class HomepageSectionResource extends CmsResource
{
    protected static ?string $model = HomepageSection::class;

    protected static ?string $navigationLabel = 'Bagian beranda';

    protected static ?string $modelLabel = 'bagian beranda';

    protected static ?string $pluralModelLabel = 'bagian beranda';

    protected static ?string $navigationGroup = 'Website';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static function permission(): string
    {
        return CmsPermissions::HOMEPAGE;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('section_key')->label('Bagian')->options(array_combine(HomepageSection::KEYS, array_map(
                fn (string $key): string => ucwords(str_replace('_', ' ', $key)),
                HomepageSection::KEYS,
            )))->required()->unique(ignoreRecord: true),
            TextInput::make('label')->label('Nama di admin')->required()->maxLength(100),
            TextInput::make('eyebrow')->label('Teks kecil')->maxLength(255),
            TextInput::make('title')->label('Judul')->maxLength(255),
            TextInput::make('subtitle')->label('Subjudul')->maxLength(255),
            Textarea::make('description')->label('Deskripsi')->rows(4)->maxLength(3000)->columnSpanFull(),
            CmsUpload::image('image_path', 'homepage')->label('Gambar'),
            TextInput::make('image_alt')->label('Teks alternatif gambar')->maxLength(255),
            TextInput::make('button_text')->label('Teks tombol')->maxLength(80),
            TextInput::make('button_url')->label('URL tombol')->rules([new SafeNavigationUrl])->maxLength(2048),
            TextInput::make('secondary_button_text')->label('Teks tombol kedua')->maxLength(80),
            TextInput::make('secondary_button_url')->label('URL tombol kedua')->rules([new SafeNavigationUrl])->maxLength(2048),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
            Toggle::make('is_visible')->label('Tampilkan di website')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            ImageColumn::make('image_path')->disk('public')->label('Gambar'),
            TextColumn::make('label')->label('Bagian')->searchable()->sortable(),
            TextColumn::make('title')->label('Judul'),
            IconColumn::make('is_visible')->label('Tampil')->boolean(),
            TextColumn::make('sort_order')->label('Urutan')->sortable(),
        ])->filters([TernaryFilter::make('is_visible')->label('Status tampilan')]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit' => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }
}
