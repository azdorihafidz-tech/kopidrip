<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Support\CmsPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationLabel = 'Pelanggan';

    protected static ?string $modelLabel = 'pelanggan';

    protected static ?string $pluralModelLabel = 'pelanggan';

    protected static ?string $navigationGroup = 'Toko';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return CmsPermissions::allows('customers.manage');
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

    public static function canDelete(Model $record): bool
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
            TextColumn::make('name')->label('Nama')->searchable(),
            TextColumn::make('email')->label('Email')->searchable(),
            TextColumn::make('phone')->label('Telepon'),
            TextColumn::make('city')->label('Kota')->searchable(),
            TextColumn::make('orders_count')->counts('orders')->label('Pesanan')->sortable(),
            TextColumn::make('created_at')->label('Terdaftar')->dateTime('d M Y')->sortable(),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListCustomers::route('/')];
    }
}
