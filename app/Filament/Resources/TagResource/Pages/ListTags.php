<?php

namespace App\Filament\Resources\TagResource\Pages;

use App\Filament\Resources\TagResource;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListTags extends ListRecords
{
    protected static string $resource = TagResource::class;


    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
