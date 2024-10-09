<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // Metode saved() akan dipanggil setelah data produk disimpan
    protected function saved(): void
    {
        parent::saved();

        // Update stok produk master setelah perubahan disimpan
        $this->updateProductStock();
    }

    // Metode untuk menghitung dan mengupdate stok produk master
    protected function updateProductStock(): void
    {
        // Ambil produk yang sedang diubah
        $product = $this->record;

        // Hitung total stok dari semua varian produk
        $totalStock = $product->product_variants()->sum('stock');

        // Update stok di produk master
        $product->update([
            'stock' => $totalStock,
        ]);
    }

    // Metode afterSave() untuk memastikan stok diupdate setelah disimpan
    protected function afterSave(): void
    {
        $product = $this->record;
        
        if ($product) {
            // Hitung total stok dari semua varian produk
            $totalStock = $product->product_variants()->sum('stock');

            // Update stok langsung di atribut model tanpa set()
            $product->stock = $totalStock;

            // Simpan perubahan ke database
            $product->save();
        }
    }
}
