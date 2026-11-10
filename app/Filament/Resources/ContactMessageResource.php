<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use App\Support\CmsPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static ?string $navigationLabel = 'Pesan masuk';

    protected static ?string $modelLabel = 'pesan masuk';

    protected static ?string $pluralModelLabel = 'pesan masuk';

    protected static ?string $navigationGroup = 'Kontak';

    protected static ?string $navigationIcon = 'heroicon-o-inbox';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return CmsPermissions::allows(CmsPermissions::CONTACT_MESSAGES);
    }

    public static function canView(Model $record): bool
    {
        return static::canViewAny();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table->defaultSort('created_at', 'desc')->columns([
            TextColumn::make('created_at')->label('Diterima')->dateTime('d M Y, H:i')->sortable(),
            TextColumn::make('name')->label('Nama')->searchable()->sortable(),
            TextColumn::make('email')->label('Email')->searchable()->copyable(),
            TextColumn::make('subject')->label('Subjek')->searchable()->limit(60),
            TextColumn::make('read_at')->label('Status')->badge()
                ->formatStateUsing(fn ($state): string => $state ? 'Sudah dibaca' : 'Baru')
                ->color(fn ($state): string => $state ? 'gray' : 'warning'),
        ])->filters([
            TernaryFilter::make('read_at')->label('Status pesan')->nullable()
                ->trueLabel('Sudah dibaca')->falseLabel('Belum dibaca'),
        ])->actions([
            Action::make('details')->label('Baca pesan')->icon('heroicon-o-eye')
                ->modalHeading(fn (ContactMessage $record): string => $record->subject)
                ->modalContent(fn (ContactMessage $record) => view('filament.contact-messages.detail', ['contactMessage' => $record]))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),
            Action::make('mark_read')->label('Tandai dibaca')->icon('heroicon-o-check')
                ->visible(fn (ContactMessage $record): bool => $record->read_at === null)
                ->action(fn (ContactMessage $record) => $record->update(['read_at' => now()])),
            DeleteAction::make()->label('Hapus'),
        ])->emptyStateHeading('Belum ada pesan masuk')
            ->emptyStateDescription('Pesan dari formulir Kontak akan muncul di sini.');
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListContactMessages::route('/')];
    }

    public static function getNavigationBadge(): ?string
    {
        $unread = ContactMessage::query()->unread()->count();

        return $unread > 0 ? (string) $unread : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }
}
