<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Number;
use Illuminate\Support\Str;


class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-s-squares-2x2';

    protected static ?int $navigationSort = 4;
    
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Group::make()->schema([
                Section::make('Informasi Produk')->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live()
                        ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation
                            === 'create' ? $set('slug', Str::slug($state)) : null),

                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->readOnly()
                        ->dehydrated()
                        ->unique(Product::class, 'slug', ignoreRecord: true),

                    Forms\Components\MarkdownEditor::make('description')
                        ->columnSpanFull()
                        ->fileAttachmentsDirectory('products'),
                ])->columns(2),

                Section::make('Gambar Produk')->schema([
                    Forms\Components\FileUpload::make('images')
                        ->image()
                        ->multiple()
                        ->directory('products')
                        ->maxFiles(5)
                        ->reorderable(),
                ])

            ])->columnSpan(2),

            Group::make()->schema([
                Section::make('Price')->schema([
                    TextInput::make('price')
                    ->label('Harga')
                    ->numeric()
                    ->required()
                    ->prefix('Rp. '),
                ]),

                Section::make('Pilihan')->schema([
                    Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),

                    Select::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name')
                    ->preload()
                    ->searchable()
                    ->required(),
                ]),

                Section::make('Status')->schema([
                    Toggle::make('in_stock')
                    ->label('Tersedia')
                    ->required()
                    ->default(true),
                    Toggle::make('is_active')
                    ->label('Iklan Aktif')
                    ->required()
                    ->default(true),
                    Toggle::make('is_featured')
                    ->label('Item Unggulan')
                    ->required(),
                    Toggle::make('on_sale')
                    ->label('Di Jual')
                    ->required(),
                ]),
            ])->columnSpan(1)
        ])->columns(3);    
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                ->label('No')
                ->getStateUsing(fn ($rowLoop) => $rowLoop->index + 1),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                    Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->sortable(),
                    Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->prefix('Rp.')
                    ->sortable()
                    ->formatStateUsing(fn($state) => Number::currency($state, 'IDR')),

                
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('in_stock')
                    ->boolean(),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                ->relationship('category','name'),
                SelectFilter::make('brand')
                ->relationship('brand','name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                 
                ])
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
}
