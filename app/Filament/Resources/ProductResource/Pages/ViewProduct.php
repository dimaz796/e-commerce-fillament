<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(), // Menambahkan tombol edit
        ];
    }

    // Untuk menampilkan relasi varian produk
    protected function getRelations(): array
    {
        return [
            ProductResource\RelationManagers\ProductVariantsRelationManager::class, // Tambahkan relasi varian produk
        ];
    }
}
