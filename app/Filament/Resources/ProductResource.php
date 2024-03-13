<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //title
                TextInput::make('title'),

                //brand
                TextInput::make('brand'),

                //category
                TextInput::make('category'),

                //description
                RichEditor::make('description'),

                //price
                TextInput::make('price')
                    ->prefix('$'),

                //rating
                TextInput::make('rating')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Image')
                    ->rounded(),

                //title
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->alignLeft(),

                //brand
                TextColumn::make('brand')
                    ->searchable()
                    ->sortable()
                    ->color('secondary')
                    ->alignLeft(),

                //category
                TextColumn::make('category')
                    ->sortable()
                    ->searchable(),

                //description
                TextColumn::make('description')
                    ->sortable()
                    ->searchable()
                    ->limit(30),

                //price
                BadgeColumn::make('price')
                    ->colors(['secondary'])
                    ->prefix('$')
                    ->sortable()
                    ->searchable(),

                //rating
                BadgeColumn::make('rating')
                    ->colors([
                        'danger' => static fn ($state): bool => $state <= 3,
                        'warning' => static fn ($state): bool => $state > 3 && $state <= 4.5,
                        'success' => static fn ($state): bool => $state > 4.5,
                    ])
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProducts::route('/'),
        ];
    }
}
