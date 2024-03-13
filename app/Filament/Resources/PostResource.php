<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Filament\Resources\PostResource\RelationManagers\TagsRelationManager;
use App\Filament\Resources\PostResource\Widgets\StatsOverview;
use App\Helpers\Helper;
use App\Models\Post;
use Carbon\Carbon;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Resources\Pages\EditRecord;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Widgets\StatsOverviewWidget;
use Illuminate\Support\Facades\Auth;
use League\CommonMark\Normalizer\TextNormalizer;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Nette\Utils\Random;
use PhpParser\Node\Expr\Cast\Object_;
use PhpParser\Node\Stmt\Label;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', true)->count();
    }

    protected static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', true)->count() < 3 ? 'danger' : 'primary';
    }

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
                    Toggle::make('status'),
                    Hidden::make('users_id')
                        ->default(Auth::user()->id),
                    TextInput::make('total_comment')
                        ->reactive()
                        ->disabled()
                ]),
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

                TextColumn::make('title')->limit('50')->sortable()->searchable(),
                TextColumn::make('category.name')->toggleable(isToggledHiddenByDefault: true),
                SpatieMediaLibraryImageColumn::make('cover'),
                ToggleColumn::make('status')
                    ->visible(fn () => auth()->user()->hasRole('admin')),


            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Filter::make('publish')
                    ->query(fn (Builder $query): Builder => $query->where('status', true)),
                Filter::make('draft')
                    ->query(fn (Builder $query): Builder => $query->where('status', false)),
                SelectFilter::make('Category')->relationship('category', 'name'),

                DateRangeFilter::make('created_at')
                    ->withIndicator(),
                // Filter::make('created_at')
                // ->form([
                //     Forms\Components\DatePicker::make('From'),
                //     Forms\Components\DatePicker::make('Until'),
                // ])
                // ->indicateUsing(function (array $data): array {
                //     $indicators = [];

                //     if ($data['From'] ?? null) {
                //         $indicators['from'] = 'Created from ' . Carbon::parse($data['From'])->toFormattedDateString();
                //     }

                //     if ($data['Until'] ?? null) {
                //         $indicators['until'] = 'Created until ' . Carbon::parse($data['Until'])->toFormattedDateString();
                //     }

                //     return $indicators;
                // })
                // ->query(function (Builder $query, array $data): Builder {
                //     return $query
                //         ->when(
                //             $data['From'],
                //             fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                //         )
                //         ->when(
                //             $data['Until'],
                //             fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                //         );
                // }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('download')
                    ->color('info')
                    ->icon('heroicon-o-download')
                    ->url(fn (Post $record) => route('download.image', $record))
                    ->openUrlInNewTab(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TagsRelationManager::class,
            CommentsRelationManager::class
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
            'show' => Pages\ShowPost::route('/show/{id}')
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StatsOverview::class
        ];
    }
}
