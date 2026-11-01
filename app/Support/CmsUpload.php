<?php

namespace App\Support;

use Filament\Forms\Components\FileUpload;

final class CmsUpload
{
    public static function image(string $name, string $directory, bool $required = false): FileUpload
    {
        return FileUpload::make($name)
            ->label('Gambar')
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->image()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
            ->maxSize(5120)
            ->required($required)
            ->helperText('JPG, PNG, WebP, atau GIF. Maksimal 5 MB.');
    }
}
