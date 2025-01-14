<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatusEnum;
use App\Enums\RolesEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use function Laravel\Prompts\search;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('slug', Str::slug($state));
                    })->placeholder('Enter the product name'),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')->required()->readOnly(),
                Forms\Components\Select::make('department_id')
                    ->relationship('department', 'name')
                    ->reactive()->searchable()->preload()
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('category_id', null);
                    })->searchable()->required(),
                Forms\Components\Select::make('category_id')
                    ->relationship(
                        'category',
                        'name',
                        function (Builder $query, callable $get) {
                            $departmentID = $get('department_id');
                            if ($departmentID) {
                                $query->where('department_id', $departmentID);
                            }
                        }
                    )->label(__('Category'))->preload()->searchable()->required(),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])->columnSpan(2),
                Forms\Components\TextInput::make('price')
                    ->required()->numeric(),
                Forms\Components\TextInput::make('quantity')
                    ->required()->integer(),
                Forms\Components\Select::make('status')
                    ->required()->options(ProductStatusEnum::labels()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->sortable()->searchable()->words(10),
                TextColumn::make('status')->badge()->colors(ProductStatusEnum::colors()),

                TextColumn::make('department.name')->label('Department'),
                TextColumn::make('category.name')->label('Category'),
                TextColumn::make('price'),
                TextColumn::make('quantity'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')->options(ProductStatusEnum::labels()),
                SelectFilter::make('department_id')->relationship('department', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
    public static function  canViewAny(): bool
    {
        $user = Filament::auth()->user();
        return $user && $user->hasRole(RolesEnum::Vendor);
    }
}
