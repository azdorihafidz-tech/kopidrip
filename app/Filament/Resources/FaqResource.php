<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use App\Support\CmsPermissions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FaqResource extends CmsResource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationLabel = 'FAQ';

    protected static ?string $modelLabel = 'pertanyaan FAQ';

    protected static ?string $pluralModelLabel = 'FAQ';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?int $navigationSort = 6;

    protected static function permission(): string
    {
        return CmsPermissions::FAQ;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('question')->label('Pertanyaan')->required()->maxLength(255),
            TextInput::make('category')->label('Kategori')->maxLength(100)->placeholder('Contoh: Pemesanan'),
            Textarea::make('answer')->label('Jawaban')->required()->rows(5)->maxLength(5000)->columnSpanFull(),
            Select::make('status')->label('Status')->options(['draft' => 'Draft', 'published' => 'Terbit'])->default('draft')->required(),
            TextInput::make('sort_order')->label('Urutan')->numeric()->integer()->minValue(0)->default(0)->required(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('sort_order')->reorderable('sort_order')->columns([
            TextColumn::make('question')->label('Pertanyaan')->searchable()->sortable(),
            TextColumn::make('category')->label('Kategori')->badge(),
            TextColumn::make('status')->label('Status')->badge()->color(fn (string $state): string => $state === 'published' ? 'success' : 'gray'),
            TextColumn::make('sort_order')->label('Urutan')->sortable(),
            TextColumn::make('updated_at')->label('Diperbarui')->dateTime('d M Y')->sortable(),
        ])->filters([
            SelectFilter::make('status')->options(['draft' => 'Draft', 'published' => 'Terbit']),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
