<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CmsPageResource\Pages;
use App\Models\CmsPage;
use App\Support\CmsPermissions;
use App\Support\CmsUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CmsPageResource extends CmsResource
{
    protected static ?string $model = CmsPage::class;

    protected static ?string $navigationLabel = 'Halaman';

    protected static ?string $modelLabel = 'halaman';

    protected static ?string $pluralModelLabel = 'halaman';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static function permission(): string
    {
        return CmsPermissions::PAGES;
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
            TextInput::make('slug')->label('Slug')->required()->maxLength(255)->unique(ignoreRecord: true)
                ->rules([
                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                    function (string $attribute, mixed $value, \Closure $fail): void {
                        if (in_array($value, ['admin', 'products', 'menu', 'gallery', 'journal', 'faq', 'promotions', 'sitemap-xml', 'robots-txt', 'storage', 'up'], true)) {
                            $fail('Slug ini digunakan oleh fitur website. Pilih slug lain.');
                        }
                    },
                ])->helperText('Gunakan huruf kecil, angka, dan tanda hubung.'),
            Textarea::make('excerpt')->label('Ringkasan')->rows(3)->maxLength(600)->columnSpanFull(),
            RichEditor::make('content')->label('Konten')->toolbarButtons([
                ['bold', 'italic', 'underline', 'strike', 'link'],
                ['h2', 'h3'],
                ['bulletList', 'orderedList', 'blockquote'],
                ['undo', 'redo'],
            ])->columnSpanFull()->helperText('Script dan atribut HTML tidak dirender oleh website publik.'),
            CmsUpload::image('featured_image', 'pages')->label('Gambar utama'),
            Select::make('status')->label('Status')->options(['draft' => 'Draft', 'published' => 'Terbit'])->default('draft')->required(),
            DateTimePicker::make('published_at')->label('Jadwalkan terbit')->seconds(false),
            TextInput::make('seo_title')->label('Judul SEO')->maxLength(255),
            Textarea::make('seo_description')->label('Deskripsi SEO')->rows(3)->maxLength(320),
            TextInput::make('canonical_url')->label('Canonical URL')->url()->maxLength(2048),
            CmsUpload::image('og_image', 'pages/og')->label('Gambar Open Graph'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('updated_at', 'desc')->columns([
            TextColumn::make('title')->label('Judul')->searchable()->sortable(),
            TextColumn::make('slug')->label('URL')->searchable(),
            TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
            TextColumn::make('published_at')->label('Terbit')->dateTime()->sortable(),
            TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->sortable(),
        ])->filters([SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Terbit'])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCmsPages::route('/'),
            'create' => Pages\CreateCmsPage::route('/create'),
            'edit' => Pages\EditCmsPage::route('/{record}/edit'),
        ];
    }
}
