<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\FormsComponent;
use Filament\Pages\Actions;
use Filament\Resources\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected $listeners = ['refresh' => 'refreshForm'];

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    public function refreshForm()
    {
        $this->fillForm();
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Card::make()->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name'),
                        TextInput::make('title')
                            ->reactive()
                            ->afterStateUpdated(function (Closure $set, $state) {
                                $set('slug', \Str::slug($state));
                            })->required(),
                        TextInput::make('slug')->required(),
                        SpatieMediaLibraryFileUpload::make('cover'),
                        RichEditor::make('content'),
                        Toggle::make('status'),
                        Hidden::make('users_id')
                            ->default(Auth::user()->id),
                        TextInput::make('total_comment')
                            ->reactive()
                            ->disabled()
                    ])->columnSpan(2),

                    Grid::make()
                        ->schema([
                            Card::make()->schema([
                                Placeholder::make('created_at')
                                    ->content(fn ($record) => $record->created_at->format('d/m/Y, H:m:s')),
                                Placeholder::make('Author')
                                    ->content(fn ($record) => $record->users->name)
                            ]),

                            Repeater::make('comments')
                                ->relationship()
                                ->schema([
                                    TextInput::make('comment')
                                        ->required()
                                        ->maxLength(255),
                                    Hidden::make('users_id')
                                        ->default(auth()->user()->id)
                                ])->columnSpanFull()

                        ])->columnSpan(1)


                ])->columns(3)
        ];
    }
}
