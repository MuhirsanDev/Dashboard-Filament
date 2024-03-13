<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
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
                Toggle::make('status')
            ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            TextColumn::make('No')->getStateUsing(
                static function ($rowLoop, HasTable $livewire): string {
                    return (string) ($rowLoop->iteration +
                        ($livewire->tableRecordsPerPage * ($livewire->page - 1
                        ))
                    );
                }
            ),
            TextColumn::make('title')->limit('50')->sortable(),
            TextColumn::make('category.name'),
            SpatieMediaLibraryImageColumn::make('cover'),
            ToggleColumn::make('status')
        ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
