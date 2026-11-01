<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Services\OrderWorkflowService;
use App\Support\CmsPermissions;
use App\Support\Money;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Pesanan';

    protected static ?string $modelLabel = 'pesanan';

    protected static ?string $pluralModelLabel = 'pesanan';

    protected static ?string $navigationGroup = 'Toko';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 1;

    public static function canViewAny(): bool
    {
        return CmsPermissions::allows('orders.manage');
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
            TextColumn::make('order_number')->label('Nomor')->searchable()->copyable(),
            TextColumn::make('customer_name')->label('Pelanggan')->searchable(),
            TextColumn::make('customer_email')->label('Email')->searchable(),
            TextColumn::make('subtotal')->label('Subtotal')->formatStateUsing(fn ($state) => Money::formatIdr(Money::toMinor((string) $state))),
            TextColumn::make('total')->label('Total')->placeholder('Belum termasuk ongkir')->formatStateUsing(fn ($state) => $state === null ? null : Money::formatIdr(Money::toMinor((string) $state))),
            TextColumn::make('status')->label('Status')->badge()->formatStateUsing(fn (OrderStatus $state) => $state->label()),
            TextColumn::make('shipping_status')->label('Ongkir')->badge(),
            TextColumn::make('payment_status')->label('Pembayaran')->badge(),
            TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable(),
        ])->filters([
            SelectFilter::make('status')->options(collect(OrderStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])),
            SelectFilter::make('payment_status')->options(['unpaid' => 'Belum dibayar', 'paid' => 'Dibayar', 'failed' => 'Gagal']),
        ])->actions([
            Action::make('details')->label('Detail')->icon('heroicon-o-eye')->modalHeading(fn (Order $record) => 'Pesanan '.$record->order_number)
                ->modalSubmitAction(false)->modalCancelActionLabel('Tutup')
                ->modalContent(fn (Order $record) => view('filament.orders.detail', ['order' => $record->load('items')])),
            Action::make('shipping_quote')->label('Tetapkan ongkir')->icon('heroicon-o-truck')->visible(fn (Order $record) => $record->status !== OrderStatus::Cancelled && $record->payment_status->value !== 'paid')
                ->form([
                    TextInput::make('shipping_cost')->label('Ongkos kirim (angka IDR)')->required()->regex('/^\d{1,10}(?:\.\d{1,2})?$/')->validationMessages(['regex' => 'Masukkan angka dengan maksimal dua desimal.']),
                    TextInput::make('courier')->label('Kurir')->required()->maxLength(100),
                    TextInput::make('service')->label('Layanan')->required()->maxLength(120),
                ])->action(function (Order $record, array $data): void {
                    app(OrderWorkflowService::class)->setShippingQuote($record, Money::toMinor((string) $data['shipping_cost']), $data['courier'], $data['service']);
                }),
            Action::make('internal_notes')->label('Catatan admin')->icon('heroicon-o-pencil-square')
                ->form([Textarea::make('admin_notes')->label('Catatan internal')->rows(5)->maxLength(5000)])
                ->fillForm(fn (Order $record) => ['admin_notes' => $record->admin_notes])
                ->action(fn (Order $record, array $data) => $record->update(['admin_notes' => $data['admin_notes'] ?? null])),
            Action::make('transition')->label('Ubah status')->icon('heroicon-o-arrow-path')->visible(fn (Order $record) => $record->status->nextStatuses($record->payment_status) !== [])
                ->form([
                    Select::make('status')->label('Status berikutnya')->options(fn (Order $record) => collect($record->status->nextStatuses($record->payment_status))->mapWithKeys(fn ($status) => [$status->value => $status->label()])->all())->required(),
                    TextInput::make('tracking_number')->label('Nomor resi (opsional)')->maxLength(120)->visible(fn (callable $get) => $get('status') === OrderStatus::Shipped->value),
                ])->action(fn (Order $record, array $data) => app(OrderWorkflowService::class)->transition($record, OrderStatus::from($data['status']), $data['tracking_number'] ?? null)),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListOrders::route('/')];
    }
}
