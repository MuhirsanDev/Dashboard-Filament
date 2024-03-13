<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [

            Actions\ButtonAction::make('Laporan pdf')->url(fn()=> route('download.tes'))->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }


}
