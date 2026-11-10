<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalPostResource\Pages;
use App\Models\JournalPost;
use App\Support\CmsPermissions;
use App\Support\CmsUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class JournalPostResource extends CmsResource
{
    protected static ?string $model = JournalPost::class;

    protected static ?string $navigationLabel = 'Jurnal';

    protected static ?string $modelLabel = 'tulisan jurnal';

    protected static ?string $pluralModelLabel = 'jurnal';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static function permission(): string
    {
        return CmsPermissions::JOURNAL;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('title')->label('Judul')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                    if (blank($get('slug'))) {
                        $set('slug', Str::slug((string) $state));
                    }
                }),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255)->unique(ignoreRecord: true)->rule('regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'),
            TextInput::make('author_name')->label('Penulis')->maxLength(120),
            Textarea::make('excerpt')->label('Ringkasan')->rows(3)->maxLength(600)->columnSpanFull(),
            RichEditor::make('content')->label('Konten')->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'link'], ['h2', 'h3'], ['bulletList', 'orderedList', 'blockquote'], ['undo', 'redo'],
            ])->columnSpanFull()->helperText('Konten dibersihkan dari script dan atribut HTML berbahaya saat ditampilkan.'),
            CmsUpload::image('image_path', 'journal')->label('Gambar utama'),
            Select::make('status')->label('Status')->options(['draft' => 'Draft', 'published' => 'Terbit'])->default('draft')->required(),
            DateTimePicker::make('published_at')->label('Waktu terbit')->seconds(false),
            TextInput::make('seo_title')->label('Judul SEO')->maxLength(255),
            Textarea::make('seo_description')->label('Deskripsi SEO')->rows(3)->maxLength(320),
            Toggle::make('is_demo')->label('Tahan dari indeks pencarian')->helperText('Aktifkan untuk mengecualikan tulisan dari sitemap dan hasil pencarian.'),
            CmsUpload::image('og_image', 'journal/og')->label('Gambar Open Graph'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('updated_at', 'desc')->columns([
            ImageColumn::make('image_path')->disk('public')->label('Gambar'),
            TextColumn::make('title')->label('Judul')->searchable()->sortable(),
            TextColumn::make('author_name')->label('Penulis')->searchable(),
            TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
            TextColumn::make('is_demo')->label('SEO')->badge()->formatStateUsing(fn (bool $state) => $state ? 'Tinjau dahulu' : 'Siap indeks')->color(fn (bool $state) => $state ? 'warning' : 'success'),
            TextColumn::make('published_at')->label('Waktu terbit')->dateTime()->sortable(),
            TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->sortable(),
        ])->filters([SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Terbit'])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJournalPosts::route('/'),
            'create' => Pages\CreateJournalPost::route('/create'),
            'edit' => Pages\EditJournalPost::route('/{record}/edit'),
        ];
    }
}
