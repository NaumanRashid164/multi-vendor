<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Enums\ProductVariationTypes;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class ProductVariations extends EditRecord
{
    protected static string $resource = ProductResource::class;
    protected static ?string $navigationIcon = 'heroicon-m-numbered-list';
    protected static ?string $title = "Product Variation";

    public function form(Form $form): Form
    {
        $types = $this->record->variationTypes;
        $fields = [];
        foreach ($types as  $type) {
            $fields[] = TextInput::make("variation_type_" . $type->id . '.id')->hidden();
            $fields[] = TextInput::make("variation_type_" . $type->id . '.name')->label($type->name);
        }
        return $form
            ->schema([
                Repeater::make("variations")
                    ->label(false)
                    ->collapsible()
                    ->addable(false)
                    ->defaultItems(1)
                    ->schema([
                        Section::make()->schema($fields)->columns(3),
                        TextInput::make("quantity")->numeric(),
                        TextInput::make("price")->label("Price")->numeric(),

                    ])->columns(2)
                    ->columnSpan(2)
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variations = $this->record->variations->toArray();
        $data['variations'] = $this->mergeCartesianWithExisting($this->record->variationTypes, $variations);
        return $data;
    }

    private function mergeCartesianWithExisting($variationTypes, $existingData)
    {
        $defaultQuantity = $this->record->quantity;
        $defaultPrice = $this->record->price;
        $cartesianProducts = $this->cartesianProduct($variationTypes, $defaultPrice, $defaultQuantity);
        $mergedProducts = [];
        foreach ($cartesianProducts as $product) {
            $optionIds = collect($product)->filter(fn($value, $key) => str_starts_with($key, "variation_type_"))
                ->map(fn($option) => $option["id"])
                ->values()->toArray();
            $match = array_filter($existingData, function ($existingOption) use ($optionIds) {
                return $existingOption["variation_type_option_ids"] === $optionIds;
            });

            // if match is found override quantity and price

            if (!empty($match)) {
                $existingEntry = reset($match);
                $product["quantity"] = $existingEntry["quantity"];
                $product["price"] = $existingEntry["price"];
            } else {
                $product["quantity"] = $defaultQuantity;
                $product["price"] = $defaultPrice;
            }

            $mergedProducts[] = $product;
        }
        return $mergedProducts;
    }
    private function cartesianProduct($variationTypes, $price, $quantity)
    {
        $results = [[]];

        foreach ($variationTypes as $variationType) {
            $temp = [];
            foreach ($variationType->options as $option) {

                // Add Current option to all existing options
                foreach ($results as $combination) {
                    $newCombination = $combination + [
                        "variation_type_" . ($variationType->id) => [
                            "id" => $option->id,
                            "name" => $option->name,
                            "label" => $variationType->name,
                        ],
                    ];
                    $temp[] = $newCombination;
                }
            }
            $results =  $temp;
        }

        foreach ($results as &$combination) {
            if (count($combination) == count($variationTypes)) {
                $combination["quantity"] = $quantity;
                $combination["price"] = $price;
            }
        }
        return $results;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $formattedData = [];
        foreach ($data["variations"] as $option) {
            $variationTypeOptionIds = [];
            foreach ($this->record->variationTypes as $variationType) {
                $variationTypeOptionIds[] = $option["variation_type_" . $variationType->id]["id"];
            }
            $qty = $option["quantity"];
            $price = $option["price"];
            $formattedData[] = [
                "variation_type_option_ids" => $variationTypeOptionIds,
                "quantity" => $qty,
                "price" => $price
            ];
        }
        $data["variations"] = $formattedData;
        return $data;
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $variations = $data["variations"];        
        unset($data["variations"]);
        $record->update($data);
        $record->variations()->delete();
        $record->variations()->createMany($variations);

        return $record;
    }
}
