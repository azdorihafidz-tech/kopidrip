<?php

namespace App\Filament\Pages;

use App\Models\WebsiteSetting as Setting;
use App\Rules\AccessibleBrandColor;
use App\Rules\SafeGoogleMapsEmbedUrl;
use App\Support\CmsPermissions;
use App\Support\CmsUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class WebsiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan website';

    protected static ?string $title = 'Pengaturan website';

    protected static ?string $navigationGroup = 'Website';

    protected static string $view = 'filament.pages.website-settings';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return CmsPermissions::allows(CmsPermissions::SETTINGS);
    }

    public function mount(): void
    {
        $settings = Setting::query()->first() ?? new Setting;
        $this->form->fill([
            ...$settings->attributesToArray(),
            'primary_color' => $settings->primary_color ?: '#1d211c',
            'secondary_color' => $settings->secondary_color ?: '#49382a',
            'accent_color' => $settings->accent_color ?: '#56614a',
        ]);
    }

    public function form(Form $form): Form
    {
        $socialUrl = ['regex:/^https:\/\/[^\s]+$/i'];

        return $form
            ->schema([
                Tabs::make('Pengaturan')->tabs([
                    Tab::make('Identitas')->schema([
                        Section::make('Identitas bisnis')->columns(2)->schema([
                            TextInput::make('business_name')->label('Nama bisnis')->required()->maxLength(120),
                            TextInput::make('tagline')->label('Tagline')->maxLength(180),
                            CmsUpload::image('logo', 'branding/logos')->label('Logo')->helperText('Jika kosong, website memakai logo Kopi Drip bawaan.'),
                            CmsUpload::image('logo_white', 'branding/logos')->label('Logo putih'),
                            CmsUpload::image('favicon', 'branding/favicons')->label('Favicon')->helperText('Jika kosong, logo Kopi Drip bawaan dipakai sebagai ikon tab browser.'),
                        ]),
                        Section::make('Status website')->schema([
                            Toggle::make('site_is_demo')->label('Website masih dalam mode demo')->helperText('Mode demo memasang noindex pada semua halaman publik sampai materi resmi siap.'),
                        ]),
                        Section::make('Warna brand')->description('Warna teks dan tombol harus tetap terbaca pada latar terang.')->columns(3)->schema([
                            ColorPicker::make('primary_color')->label('Primer')->required()->rules([new AccessibleBrandColor]),
                            ColorPicker::make('secondary_color')->label('Sekunder')->required()->rules([new AccessibleBrandColor]),
                            ColorPicker::make('accent_color')->label('Aksen')->required()->rules([new AccessibleBrandColor]),
                        ]),
                    ]),
                    Tab::make('Kontak & lokasi')->schema([
                        Section::make('Kontak')->columns(2)->schema([
                            TextInput::make('email')->label('Email')->email()->maxLength(255),
                            TextInput::make('phone')->label('Telepon')->tel()->maxLength(40),
                            TextInput::make('whatsapp')->label('WhatsApp')->tel()->helperText('Gunakan nomor internasional tanpa tanda +, misalnya awali dengan 62 untuk nomor Indonesia. Nomor lokal yang diawali 0 juga akan disesuaikan otomatis.')->maxLength(40),
                            Textarea::make('address')->label('Alamat')->rows(3)->columnSpanFull(),
                            KeyValue::make('opening_hours')->label('Jam operasional')->keyLabel('Hari')->valueLabel('Jam')->columnSpanFull(),
                            TextInput::make('map_embed_url')->label('URL embed Google Maps')->url()->rules([new SafeGoogleMapsEmbedUrl])->maxLength(2048)->columnSpanFull(),
                        ]),
                    ]),
                    Tab::make('Media sosial')->schema([
                        Section::make('Tautan resmi')->description('Tautan kosong tidak ditampilkan di website. Gunakan URL HTTPS.')->columns(2)->schema([
                            TextInput::make('instagram')->label('Instagram')->url()->rules($socialUrl)->maxLength(2048),
                            TextInput::make('facebook')->label('Facebook')->url()->rules($socialUrl)->maxLength(2048),
                            TextInput::make('tiktok')->label('TikTok')->url()->rules($socialUrl)->maxLength(2048),
                            TextInput::make('youtube')->label('YouTube')->url()->rules($socialUrl)->maxLength(2048),
                        ]),
                    ]),
                ])->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $settings = Setting::query()->first() ?? new Setting;
        $settings->fill($this->form->getState())->save();

        Notification::make()->success()->title('Pengaturan website disimpan')->send();
    }
}
