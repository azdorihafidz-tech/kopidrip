<?php

namespace App\Filament\Resources;

use App\Support\CmsPermissions;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

abstract class CmsResource extends Resource
{
    abstract protected static function permission(): string;

    public static function canViewAny(): bool
    {
        return CmsPermissions::allows(static::permission());
    }

    public static function canCreate(): bool
    {
        return CmsPermissions::allows(static::permission());
    }

    public static function canView(Model $record): bool
    {
        return CmsPermissions::allows(static::permission());
    }

    public static function canEdit(Model $record): bool
    {
        return CmsPermissions::allows(static::permission());
    }

    public static function canDelete(Model $record): bool
    {
        return CmsPermissions::allows(static::permission());
    }

    public static function canDeleteAny(): bool
    {
        return CmsPermissions::allows(static::permission());
    }
}
