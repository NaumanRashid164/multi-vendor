<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Filament::auth()->user()->id;
        $data['updated_by'] = Filament::auth()->user()->id;
        return $data;
    }
}
