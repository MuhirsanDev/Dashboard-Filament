<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Actions\Action as ActionsAction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function afterSave(): void{

        $name = Auth::user()->name;

        Notification::make()
            ->success()
            ->title('Post Created By ' . $name)
            ->body('New Post Has Been Saved')
            ->actions([
                Action::make('view')
                    ->url(fn() => '/admin/posts/show/'.$this->record->slug, shouldOpenInNewTab:true)
                    ->button(),
            ])->sendToDatabase(User::whereNot('id', auth()->user()->id)->get());
    }

    protected function getRedirectUrl(): string
    {
       $this->afterSave();

       return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
