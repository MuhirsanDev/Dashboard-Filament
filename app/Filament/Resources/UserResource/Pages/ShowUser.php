<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\Page;
use PhpParser\Node\Expr\Cast\Object_;

class ShowUser extends Page
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.show-user';

    protected function getData(): ?object {
        $id = request()->segment(4);

        $result = User::find($id);

        return $result;
    }
}
