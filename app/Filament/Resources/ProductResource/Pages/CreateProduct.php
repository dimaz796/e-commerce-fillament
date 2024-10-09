<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getState();
        $record = $this->record;

            if (isset($data['product_variants'])) {
                $record->product_variants()->delete(); // Hapus varian lama jika ada

                foreach ($data['product_variants'] as $variant) {
                    $record->product_variants()->create([
                        'variant_type' => $variant['variant_type'],
                        'variant_value' => $variant['variant_value'],
                        'price' => $variant['price'],
                        'stock' => $variant['stock'],
                        'image' => $variant['image'] ?? null,  // pastikan image juga nullable
                    ]);
                }

                //Update stock dan harga product berdasarkan variant

                $totalStock = collect($data['product_variants'])->sum('stock');

                 // Set harga dan stok produk dari varian
                $record->update([
                    'stock' => $totalStock,
                ]);
            }
       
    }

}
