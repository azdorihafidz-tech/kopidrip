<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Support\CmsPermissions;
use App\Support\CmsUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductResource extends CmsResource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationLabel = 'Produk';

    protected static ?string $modelLabel = 'produk';

    protected static ?string $pluralModelLabel = 'produk';

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static function permission(): string
    {
        return CmsPermissions::CATALOG;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_category_id')->label('Kategori')->relationship('category', 'name')->searchable()->preload()->native(false),
            TextInput::make('name')->label('Nama produk')->required()->maxLength(255)->live(onBlur: true)
                ->afterStateUpdated(function (?string $state, callable $set, callable $get): void {
                    if (blank($get('slug'))) {
                        $set('slug', Str::slug((string) $state));
                    }
                }),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255)->unique(ignoreRecord: true)->rule('regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'),
            TextInput::make('sku')->label('SKU')->maxLength(100)->unique(ignoreRecord: true),
            TextInput::make('price')->label('Harga')->numeric()->minValue(0)->prefix('Rp')->nullable(),
            TextInput::make('compare_price')->label('Harga pembanding')->numeric()->minValue(0)->prefix('Rp')->nullable(),
            TextInput::make('stock')->label('Stok')->numeric()->integer()->minValue(0)->default(0)->required(),
            TextInput::make('weight_grams')->label('Berat (gram)')->numeric()->integer()->minValue(0)->nullable(),
            Textarea::make('short_description')->label('Deskripsi singkat')->rows(3)->maxLength(500)->columnSpanFull(),
            Textarea::make('description')->label('Deskripsi')->rows(6)->maxLength(10000)->columnSpanFull(),
            Repeater::make('images')->label('Foto produk')->relationship()->orderColumn('sort_order')->reorderableWithButtons()->collapsible()
                ->schema([
                    CmsUpload::image('path', 'products', true)->label('Foto'),
                    TextInput::make('alt_text')->label('Teks alternatif')->maxLength(255),
                    Toggle::make('is_primary')->label('Jadikan foto utama'),
                ])->columns(2)->columnSpanFull()->helperText('Unggah beberapa foto, atur urutan, dan pilih satu foto utama.'),
            Repeater::make('variants')->label('Varian (opsional)')->relationship()->orderColumn('sort_order')->reorderableWithButtons()->collapsible()
                ->schema([
                    TextInput::make('attribute_name')->label('Jenis varian')->placeholder('Ukuran, gilingan, atau sangrai')->required()->maxLength(80),
                    TextInput::make('name')->label('Pilihan')->placeholder('200g, V60, Medium')->required()->maxLength(120),
                    TextInput::make('sku')->label('SKU varian')->maxLength(100)->unique(ignoreRecord: true),
                    TextInput::make('price')->label('Harga')->numeric()->minValue(0)->prefix('Rp')->nullable(),
                    TextInput::make('stock')->label('Stok')->numeric()->integer()->minValue(0)->default(0)->required(),
                    TextInput::make('weight_grams')->label('Berat (gram)')->numeric()->integer()->minValue(0)->nullable(),
                    Toggle::make('is_active')->label('Aktif')->default(true),
                ])->columns(2)->columnSpanFull(),
            Select::make('status')->label('Status')->options(['draft' => 'Draft', 'published' => 'Terbit'])->default('draft')->required(),
            DateTimePicker::make('published_at')->label('Waktu terbit')->seconds(false),
            Toggle::make('featured')->label('Tampilkan di beranda'),
            Toggle::make('is_demo')->label('Belum dikonfirmasi')->helperText('Jika aktif, produk tidak ditampilkan di katalog publik dan tidak dapat dipesan.'),
            TextInput::make('seo_title')->label('Judul SEO')->maxLength(255),
            Textarea::make('seo_description')->label('Deskripsi SEO')->rows(3)->maxLength(320),
            CmsUpload::image('og_image', 'products/og')->label('Gambar Open Graph'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('updated_at', 'desc')->columns([
            ImageColumn::make('primary_image_path')->disk('public')->label('Gambar'),
            TextColumn::make('name')->label('Produk')->searchable()->sortable(),
            TextColumn::make('sku')->label('SKU')->searchable(),
            TextColumn::make('category.name')->label('Kategori')->sortable(),
            TextColumn::make('price')->label('Harga')->money('IDR', locale: 'id')->sortable(),
            TextColumn::make('stock')->label('Stok')->sortable(),
            TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
            TextColumn::make('is_demo')->label('Kesiapan')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Perlu verifikasi' : 'Siap tayang')->color(fn (bool $state): string => $state ? 'warning' : 'success'),
            TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->sortable(),
        ])->filters([
            SelectFilter::make('product_category_id')->relationship('category', 'name')->label('Kategori'),
            SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Terbit']),
            TernaryFilter::make('featured')->label('Unggulan'),
        ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['category', 'images', 'primaryImage']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
