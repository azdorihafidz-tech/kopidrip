<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use App\Support\CmsPermissions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TestimonialResource extends CmsResource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationLabel = 'Testimoni';

    protected static ?string $modelLabel = 'testimoni';

    protected static ?string $pluralModelLabel = 'testimoni';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?int $navigationSort = 7;

    protected static function permission(): string
    {
        return CmsPermissions::TESTIMONIALS;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([Section::make('Testimoni pelanggan')
            ->description('Terbitkan testimoni dengan izin pelanggan dan teks yang telah mereka setujui.')
            ->columns(2)->schema([
            TextInput::make('customer_name')->label('Nama pelanggan')->required()->maxLength(120),
            TextInput::make('customer_label')->label('Keterangan (opsional)')->maxLength(120)->placeholder('Contoh: Pelanggan Sidikalang'),
            Textarea::make('content')->label('Isi testimoni')->required()->rows(5)->maxLength(2000)->columnSpanFull(),
            Select::make('status')->label('Status')->options(['draft' => 'Draft', 'published' => 'Terbit'])->default('draft')->required(),
            Toggle::make('featured')->label('Tampilkan sebagai unggulan'),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
        ])]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->columns([
            TextColumn::make('customer_name')->label('Pelanggan')->searchable()->sortable(),
            TextColumn::make('customer_label')->label('Keterangan'),
            TextColumn::make('content')->label('Testimoni')->limit(90)->searchable(),
            TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
            IconColumn::make('featured')->label('Unggulan')->boolean(),
            TextColumn::make('sort_order')->label('Urutan')->sortable(),
        ])->filters([SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Terbit'])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
