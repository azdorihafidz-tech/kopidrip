<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionResource\Pages;
use App\Models\Promotion;
use App\Support\CmsPermissions;
use App\Support\CmsUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PromotionResource extends CmsResource
{
    protected static ?string $model = Promotion::class;

    protected static ?string $navigationLabel = 'Promo';

    protected static ?string $modelLabel = 'promo';

    protected static ?string $pluralModelLabel = 'promo';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 8;

    protected static function permission(): string
    {
        return CmsPermissions::PROMOTIONS;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->label('Judul promo')->required()->maxLength(180)->live(onBlur: true)
                ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                    if (blank($get('slug'))) {
                        $set('slug', Str::slug((string) $state));
                    }
                }),
            TextInput::make('slug')->label('URL')->required()->maxLength(200)->unique(ignoreRecord: true)->rule('regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'),
            Textarea::make('summary')->label('Ringkasan')->rows(2)->maxLength(500)->columnSpanFull(),
            RichEditor::make('description')->label('Detail promo')->toolbarButtons([
                ['bold', 'italic', 'link'], ['h2', 'h3'], ['bulletList', 'orderedList'], ['undo', 'redo'],
            ])->columnSpanFull(),
            CmsUpload::image('image_path', 'promotions')->label('Gambar promo'),
            Textarea::make('terms')->label('Syarat dan ketentuan')->rows(3)->maxLength(3000)->columnSpanFull(),
            Select::make('status')->label('Status')->options(['draft' => 'Draft', 'published' => 'Terbit'])->default('draft')->required(),
            DateTimePicker::make('starts_at')->label('Mulai'),
            DateTimePicker::make('ends_at')->label('Berakhir'),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->columns([
            ImageColumn::make('image_path')->disk('public')->label('Gambar'),
            TextColumn::make('title')->label('Promo')->searchable()->sortable(),
            TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
            TextColumn::make('starts_at')->label('Mulai')->dateTime('d M Y H:i')->sortable(),
            TextColumn::make('ends_at')->label('Berakhir')->dateTime('d M Y H:i')->sortable(),
            TextColumn::make('sort_order')->label('Urutan')->sortable(),
        ])->filters([SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Terbit'])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromotions::route('/'),
            'create' => Pages\CreatePromotion::route('/create'),
            'edit' => Pages\EditPromotion::route('/{record}/edit'),
        ];
    }
}
