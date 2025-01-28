<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\ProductVariationTypes;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;

class VariationTypes extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $navigationIcon = 'heroicon-m-numbered-list';
    protected static ?string $title = "Variation Types";

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Repeater::make("variationTypes")
                ->label(false)
                    ->relationship()
                    ->collapsible()
                    ->defaultItems(1)
                    ->addActionLabel("Add new Variation type")
                    ->columns(2)
                    ->columnSpan(2)
                    ->schema([
                        TextInput::make("name")
                            ->required(),
                        Select::make("type")
                            ->options(ProductVariationTypes::labels())
                            ->required(),
                        Repeater::make("options")
                            ->relationship()
                            ->collapsible()
                            ->schema([
                                TextInput::make("name")
                                    ->required()->columnSpan(2),
                                SpatieMediaLibraryFileUpload::make("image")
                                    ->image()
                                    ->collection("images")
                                    ->panelLayout("grid")
                                    ->multiple()
                                    ->openable()
                                    ->reorderable()
                                    ->appendFiles()
                                    ->preserveFilenames(),

                            ])
                            ->columnSpan(2)
                    ])
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
