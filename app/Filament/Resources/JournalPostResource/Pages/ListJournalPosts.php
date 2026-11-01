<?php

namespace App\Filament\Resources\JournalPostResource\Pages;

use App\Filament\Resources\JournalPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJournalPosts extends ListRecords
{
    protected static string $resource = JournalPostResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
