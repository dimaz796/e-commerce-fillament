<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getFormSchema(): array
    {
        
        return [
            TextInput::make('name')->required(),
            TextInput::make('slug')->required(),
            MarkdownEditor::make('description'),
    
            // Bagian untuk varian produk
            Section::make('Variant Product')->schema([
                Repeater::make('product_variants')
                    ->schema([
                        TextInput::make('variant_type')->required()->label('Variant Type'),
                        TextInput::make('variant_value')->required()->label('Variant Value'),
                        TextInput::make('price')->numeric()->required()->label('Price'),
                        TextInput::make('stock')->numeric()->required()->label('Stock'),
                        FileUpload::make('image')->nullable()->label('Image')
                    ])
                    ->columns(4)
                    ->minItems(1)
            ]),
        ];
    }
}
