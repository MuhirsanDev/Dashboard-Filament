<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Illuminate\Support\Facades\Hash;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    use EditRecord\Concerns\HasWizard;

    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSteps(): array
    {
        return [

            Wizard\Step::make('Authentication')
                ->icon('heroicon-o-key')
                ->schema([
                    Card::make()->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan(2),

                        TextInput::make('email')
                            ->email()
                            ->label('Email Address')
                            ->required()
                            ->maxLength(100)
                            ->columnSpan(2),

                        TextInput::make('password')
                            ->password()
                            ->minLength(8)
                            ->same('passwordConfirmation')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                        TextInput::make('passwordConfirmation')
                            ->password()
                            ->label('Password Confirmation')
                            ->minLength(8)
                            ->dehydrated(false),
                        Select::make('roles')
                            ->multiple()
                            ->relationship('roles', 'name')->preload()
                            ->columnSpan(2),
                    ])->columns(2)
                ]),
            Wizard\Step::make('Profile')
                ->icon('heroicon-o-user')
                ->schema([
                    TextInput::make('birth')
                        ->type('date'),

                    Select::make('gender')
                        ->options([
                            'Male' => 'Male',
                            'Female' => 'Female'
                        ]),
                    Textarea::make('address')
                        ->columnSpan(2)

                ])->columns(2),
            Wizard\Step::make('Biodata')
                ->icon('heroicon-o-pencil')
                ->schema([
                    Textarea::make('biodata')
                        ->rows(9)
                ])

        ];
    }
}
