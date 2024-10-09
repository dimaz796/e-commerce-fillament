<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductVariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'product_variants';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('variant_type')->required()->columnSpan(1),
                TextInput::make('variant_value')->required(),
                TextInput::make('price')->numeric()->required()->prefix('Rp. '),
                TextInput::make('stock')->numeric()->required(),
                FileUpload::make('image')->nullable()->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Type')
            ->columns([
                 Tables\Columns\TextColumn::make('nomor')
                ->label('No')
                ->getStateUsing(fn ($rowLoop, $livewire) => ($rowLoop->index + 1) + ($livewire->getTable()->getRecords()->firstItem() - 1)),
                Tables\Columns\TextColumn::make('variant_type'),
                Tables\Columns\TextColumn::make('variant_value'),
                Tables\Columns\TextColumn::make('price'),
                Tables\Columns\TextColumn::make('stock'),
                Tables\Columns\ImageColumn::make('image')
                    ->size(50)
                    ->rounded(),
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

    protected function saved(): void
    {
        // Setelah simpan varian produk, hitung ulang stok di produk utama
        $this->updateProductStock();
    }
    
    protected function deleted(): void
    {
        // Setelah hapus varian produk, hitung ulang stok di produk utama
        $this->updateProductStock();
    }

    protected function updateProductStock(): void
    {
        $product = $this->ownerRecord; // Mendapatkan produk utama

        if ($product) {
            $totalStock = $product->product_variants()->sum('stock'); // Menghitung total stok dari semua varian
            
            $product->stock = $totalStock; // Update stok produk utama
            $product->save(); // Simpan perubahan di produk utama
        }
    }
}
